<?php
/**
 * new-tenders.php
 * 
 * Script to safely delete records from both Elasticsearch and MySQL
 * based on a `created_at` date range in `tenders_posts` table.
 * 
 * Run from CLI:
 * php new-tenders.php 2026-05-01 2026-05-28
 */

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

// Handle large datasets safely
set_time_limit(0);
ini_set('memory_limit', '-1');

// Adjust paths depending on the exact execution location
require_once __DIR__ . '/../elastic_client.php';
require_once __DIR__ . '/../../admin/includes/connection.php';

if (!$con) {
    die("MySQL connect error: " . mysqli_connect_error() . "\n");
}

$index = ES_INDEXES['NEW'];

// 1. Validate Input Dates
if ($argc < 2) {
    echo "Usage: php new-tenders.php YYYY-MM-DD [YYYY-MM-DD]\n";
    echo "Example: php new-tenders.php 2026-05-01 2026-05-28\n";
    exit(1);
}

$start_date = $argv[1];
$end_date = isset($argv[2]) ? $argv[2] : $start_date;

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if (!validateDate($start_date) || !validateDate($end_date)) {
    die("Error: Invalid date format. Please use YYYY-MM-DD.\n");
}

// Convert to date ranges (from 00:00:00 to 23:59:59)
$start_datetime = $start_date . " 00:00:00";
$end_datetime = $end_date . " 23:59:59";

echo "Checking records from '$start_datetime' to '$end_datetime'...\n";

// 2. Show total matching records before delete
$count_query = "SELECT COUNT(id) as total FROM tenders_posts WHERE created_at >= ? AND created_at <= ?";
$stmt = mysqli_prepare($con, $count_query);
mysqli_stmt_bind_param($stmt, "ss", $start_datetime, $end_datetime);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$total_found = (int)$row['total'];

if ($total_found === 0) {
    echo "No matching records found. Exiting.\n";
    exit(0);
}

echo "Found $total_found records to delete.\n";

// 3. Add confirmation prompt
echo "Are you sure you want to delete $total_found records? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
if (strtolower($line) !== 'yes') {
    echo "Aborted.\n";
    exit(0);
}

$batchSize = 1000;
$totalEsDeleted = 0;
$totalDbDeleted = 0;
$failedIds = [];

echo "\nStarting deletion process in batches of $batchSize...\n";

// 4. Delete in batches
while (true) {
    // We select the first $batchSize records that match the date range.
    // As we delete them from MySQL, the next batch will naturally shift into the limit window.
    $sql = "SELECT id FROM tenders_posts WHERE created_at >= ? AND created_at <= ? ORDER BY id ASC LIMIT ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $start_datetime, $end_datetime, $batchSize);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) === 0) {
        break; // No more records
    }

    $ids = [];
    $bulk = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $id = (int)$row['id'];
        $ids[] = $id;

        // Build Elasticsearch bulk delete payload
        $bulk[] = json_encode([
            'delete' => [
                '_index' => $index,
                '_id'    => (string)$id
            ]
        ]);
    }

    // 5. Delete from Elasticsearch first
    $es_deleted_in_batch = 0;
    if (!empty($bulk)) {
        $body = implode("\n", $bulk) . "\n";
        try {
            $resp = es_request('POST', '_bulk', $body);
            
            // Parse Elasticsearch response to count exact successes and handle failures
            // es_request may already return a parsed array in $resp['body']
            $bodyObj = is_array($resp['body']) ? $resp['body'] : json_decode($resp['body'], true);
            if (isset($bodyObj['items'])) {
                foreach ($bodyObj['items'] as $item) {
                    if (isset($item['delete']['status']) && in_array($item['delete']['status'], [200, 404])) {
                        // 200 = successfully deleted, 404 = not found (already deleted), both are acceptable
                        $es_deleted_in_batch++;
                    } else {
                        // Failed to delete this specific doc
                        $failedIds[] = $item['delete']['_id'] ?? 'unknown';
                    }
                }
                $totalEsDeleted += $es_deleted_in_batch;
            } else {
                echo "Warning: ES Bulk request returned unparsable response.\n";
            }
        } catch (Exception $e) {
            echo "Error communicating with Elasticsearch: " . $e->getMessage() . "\n";
            $failedIds = array_merge($failedIds, $ids);
            break; // Stop process on ES failure to avoid data inconsistency
        }
    }

    // 6. Delete from MySQL using transactions
    if (!empty($ids)) {
        mysqli_begin_transaction($con);
        try {
            $idsStr = implode(',', $ids);
            $delete_sql = "DELETE FROM tenders_posts WHERE id IN ($idsStr)";
            $del_res = mysqli_query($con, $delete_sql);
            
            if ($del_res) {
                $db_deleted_in_batch = mysqli_affected_rows($con);
                $totalDbDeleted += $db_deleted_in_batch;
                mysqli_commit($con);
                
                echo "Batch processed: Deleted $es_deleted_in_batch from ES, $db_deleted_in_batch from MySQL.\n";
            } else {
                throw new Exception(mysqli_error($con));
            }
        } catch (Exception $e) {
            mysqli_rollback($con);
            echo "Error deleting from MySQL: " . $e->getMessage() . "\n";
            $failedIds = array_merge($failedIds, $ids);
            break; // Stop on DB failure
        }
    }
}

// 7. Print Execution Summary
echo "\n=====================================\n";
echo "         EXECUTION SUMMARY         \n";
echo "=====================================\n";
echo "Total Records Found   : $total_found\n";
echo "ES Deleted Count      : $totalEsDeleted\n";
echo "MySQL Deleted Count   : $totalDbDeleted\n";

if (!empty($failedIds)) {
    echo "Failed IDs count      : " . count($failedIds) . "\n";
    echo "First 10 Failed IDs   : " . implode(', ', array_slice($failedIds, 0, 10)) . "\n";
    echo "Status                : COMPLETED WITH ERRORS\n";
} else {
    echo "Status                : SUCCESS\n";
}
echo "=====================================\n";
