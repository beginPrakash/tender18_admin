<?php
/**
 * delete-by-ref-no.php
 *
 * Script to safely delete records from both Elasticsearch and MySQL
 * based on a `ref_no` range across all tender tables.
 *
 * Deletion order: ARCHIVE → LIVE → NEW → ALL
 *
 * Run from CLI:
 *   php delete-by-ref-no.php <start_ref_no> <end_ref_no>
 *
 * Example:
 *   php delete-by-ref-no.php 15487490 15509829
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

// ─────────────────────────────────────────────
// 1. Validate CLI arguments
// ─────────────────────────────────────────────
if ($argc < 3) {
    echo "Usage:   php delete-by-ref-no.php <start_ref_no> <end_ref_no>\n";
    echo "Example: php delete-by-ref-no.php 15487490 15509829\n";
    exit(1);
}

$start_ref = trim($argv[1]);
$end_ref   = trim($argv[2]);

if (!ctype_digit($start_ref) || !ctype_digit($end_ref)) {
    die("Error: ref_no values must be numeric integers.\n");
}

if ((int)$start_ref > (int)$end_ref) {
    die("Error: start_ref_no must be less than or equal to end_ref_no.\n");
}

// ─────────────────────────────────────────────
// 2. Table → ES index map  (deletion order)
// ─────────────────────────────────────────────
$tables = [
    ['table' => 'tenders_archive', 'index_key' => 'ARCHIVE', 'label' => 'ARCHIVE'],
    ['table' => 'tenders_live',    'index_key' => 'LIVE',    'label' => 'LIVE'],
    ['table' => 'tenders_new',     'index_key' => 'NEW',     'label' => 'NEW'],
    ['table' => 'tenders_all',     'index_key' => 'ALL',     'label' => 'ALL'],
];

// ─────────────────────────────────────────────
// 3. Preview: count matching rows per table
// ─────────────────────────────────────────────
echo "\n";
echo "=====================================\n";
echo "         DELETE BY REF_NO            \n";
echo "=====================================\n";
echo "ref_no range : $start_ref  TO  $end_ref\n";
echo "=====================================\n\n";

$grandTotal = 0;
$tableCounts = [];

foreach ($tables as $entry) {
    $table = $entry['table'];
    $label = $entry['label'];

    $count_sql = "SELECT COUNT(id) AS total FROM `$table` WHERE ref_no BETWEEN ? AND ?";
    $stmt = mysqli_prepare($con, $count_sql);
    mysqli_stmt_bind_param($stmt, "ss", $start_ref, $end_ref);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $row  = mysqli_fetch_assoc($res);
    $cnt  = (int)$row['total'];

    $tableCounts[$table] = $cnt;
    $grandTotal += $cnt;

    echo sprintf("  %-10s  →  %s  records\n", $label, number_format($cnt));
}

echo "\n  TOTAL        →  " . number_format($grandTotal) . "  records\n";
echo "\n=====================================\n";

if ($grandTotal === 0) {
    echo "No matching records found. Exiting.\n";
    exit(0);
}

// ─────────────────────────────────────────────
// 4. Confirmation prompt
// ─────────────────────────────────────────────
echo "\nWARNING: This will permanently delete records from MySQL AND Elasticsearch.\n";
echo "Type  yes  to continue or anything else to abort: ";
$handle = fopen("php://stdin", "r");
$answer = trim(fgets($handle));
if (strtolower($answer) !== 'yes') {
    echo "Aborted. No records were deleted.\n";
    exit(0);
}

// ─────────────────────────────────────────────
// 5. Helper – process one table in batches
// ─────────────────────────────────────────────
function deleteTableByRefNo(
    $con,
    string $table,
    string $es_index,
    string $label,
    string $start_ref,
    string $end_ref,
    int $batchSize = 1000
): array {
    $totalEsDeleted = 0;
    $totalDbDeleted = 0;
    $failedIds      = [];
    $batchNum       = 0;

    echo "\n[{$label}] Starting deletion from table `{$table}` (index: {$es_index})...\n";

    while (true) {
        // Always re-select the first $batchSize rows; as we delete them the
        // next iteration automatically picks up the next batch.
        $sql  = "SELECT id FROM `{$table}` WHERE ref_no BETWEEN ? AND ? ORDER BY id ASC LIMIT ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $start_ref, $end_ref, $batchSize);
        mysqli_stmt_execute($stmt);
        $res  = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($res) === 0) {
            break; // all done for this table
        }

        $ids  = [];
        $bulk = [];

        while ($row = mysqli_fetch_assoc($res)) {
            $id     = (int)$row['id'];
            $ids[]  = $id;
            $bulk[] = json_encode([
                'delete' => [
                    '_index' => $es_index,
                    '_id'    => (string)$id
                ]
            ]);
        }

        $batchNum++;
        $es_deleted_in_batch = 0;

        // ── 5a. Delete from Elasticsearch first ──────────────────────────
        if (!empty($bulk)) {
            $body = implode("\n", $bulk) . "\n";
            try {
                $resp    = es_request('POST', '_bulk', $body);
                $bodyObj = is_array($resp['body'])
                    ? $resp['body']
                    : json_decode($resp['body'], true);

                if (isset($bodyObj['items'])) {
                    foreach ($bodyObj['items'] as $item) {
                        $status = $item['delete']['status'] ?? 0;
                        if (in_array($status, [200, 404])) {
                            // 200 = deleted, 404 = already gone – both acceptable
                            $es_deleted_in_batch++;
                        } else {
                            $failedIds[] = $item['delete']['_id'] ?? 'unknown';
                        }
                    }
                    $totalEsDeleted += $es_deleted_in_batch;
                } else {
                    echo "  [WARNING] ES bulk response unparsable for batch #{$batchNum}.\n";
                }
            } catch (Exception $e) {
                echo "  [ERROR] Elasticsearch communication failed: " . $e->getMessage() . "\n";
                $failedIds = array_merge($failedIds, $ids);
                echo "  Stopping [{$label}] to avoid inconsistency.\n";
                break;
            }
        }

        // ── 5b. Delete from MySQL (with transaction) ─────────────────────
        if (!empty($ids)) {
            mysqli_begin_transaction($con);
            try {
                $idsStr     = implode(',', $ids);
                $delete_sql = "DELETE FROM `{$table}` WHERE id IN ($idsStr)";
                $del_res    = mysqli_query($con, $delete_sql);

                if ($del_res) {
                    $db_deleted_in_batch = mysqli_affected_rows($con);
                    $totalDbDeleted     += $db_deleted_in_batch;
                    mysqli_commit($con);

                    echo "  Batch #{$batchNum}: ES={$es_deleted_in_batch}  MySQL={$db_deleted_in_batch}\n";
                } else {
                    throw new Exception(mysqli_error($con));
                }
            } catch (Exception $e) {
                mysqli_rollback($con);
                echo "  [ERROR] MySQL delete failed: " . $e->getMessage() . "\n";
                $failedIds = array_merge($failedIds, $ids);
                echo "  Stopping [{$label}] to avoid further inconsistency.\n";
                break;
            }
        }
    }

    return [
        'label'      => $label,
        'table'      => $table,
        'es_deleted' => $totalEsDeleted,
        'db_deleted' => $totalDbDeleted,
        'failed_ids' => $failedIds,
    ];
}

// ─────────────────────────────────────────────
// 6. Execute deletions in order: ARCHIVE → LIVE → NEW → ALL
// ─────────────────────────────────────────────
$results    = [];
$batchSize  = 1000;

foreach ($tables as $entry) {
    $table     = $entry['table'];
    $label     = $entry['label'];
    $esIndex   = ES_INDEXES[$entry['index_key']];

    if ($tableCounts[$table] === 0) {
        echo "\n[{$label}] No records found – skipping.\n";
        $results[] = [
            'label'      => $label,
            'table'      => $table,
            'es_deleted' => 0,
            'db_deleted' => 0,
            'failed_ids' => [],
        ];
        continue;
    }

    $result    = deleteTableByRefNo($con, $table, $esIndex, $label, $start_ref, $end_ref, $batchSize);
    $results[] = $result;
}

// ─────────────────────────────────────────────
// 7. Final Summary
// ─────────────────────────────────────────────
$totalEsAll  = 0;
$totalDbAll  = 0;
$allFailures = [];

echo "\n\n";
echo "=============================================================\n";
echo "                    EXECUTION SUMMARY                        \n";
echo "=============================================================\n";
echo sprintf("  %-10s  %-12s  %-12s  %-10s\n", 'TABLE', 'ES Deleted', 'DB Deleted', 'Status');
echo "-------------------------------------------------------------\n";

foreach ($results as $r) {
    $hasFailed = !empty($r['failed_ids']);
    $status    = $hasFailed ? 'ERRORS' : 'OK';
    echo sprintf(
        "  %-10s  %-12s  %-12s  %-10s\n",
        $r['label'],
        number_format($r['es_deleted']),
        number_format($r['db_deleted']),
        $status
    );
    $totalEsAll  += $r['es_deleted'];
    $totalDbAll  += $r['db_deleted'];
    $allFailures  = array_merge($allFailures, $r['failed_ids']);
}

echo "-------------------------------------------------------------\n";
echo sprintf("  %-10s  %-12s  %-12s\n", 'TOTAL', number_format($totalEsAll), number_format($totalDbAll));
echo "=============================================================\n";
echo "ref_no range  : $start_ref  TO  $end_ref\n";

if (!empty($allFailures)) {
    echo "Failed IDs    : " . count($allFailures) . " total\n";
    echo "First 10 IDs  : " . implode(', ', array_slice($allFailures, 0, 10)) . "\n";
    echo "Overall Status: COMPLETED WITH ERRORS\n";
} else {
    echo "Overall Status: SUCCESS\n";
}

echo "=============================================================\n";
