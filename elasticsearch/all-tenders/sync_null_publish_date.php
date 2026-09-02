<?php
// sync_null_publish_date.php
// Script to sync records where publish_date IS NULL in MySQL tenders_all table.
// Updates publish_date = created_at in MySQL, then bulk indexes updated records into Elasticsearch.
// Usage CLI: php sync_null_publish_date.php

set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '-1');

require_once '../elastic_client.php';
require_once '../../admin/includes/connection.php';

// Validate DB connection
if (!$con) {
    die("MySQL connect error: " . mysqli_connect_error() . "\n");
}

$index = ES_INDEXES['ALL'];
$batchSize = 1000;
$batchNumber = 0;

$totalProcessed = 0;
$totalMySQLUpdated = 0;
$totalESSynced = 0;
$totalESErrors = 0;

echo "Starting synchronization for tenders_all records with publish_date IS NULL or publish_date != DATE(created_at)...\n";
echo "Target ES Index: {$index}\n";
echo "Batch Size: {$batchSize}\n";
echo "--------------------------------------------------------------------------------\n";

while (true) {
    $batchNumber++;

    // Step 1: Find records where publish_date IS NULL or publish_date != DATE(created_at)
    $sql = "
        SELECT 
            id, publish_date, created_at
        FROM tenders_all
        WHERE publish_date IS NULL
        ORDER BY id ASC
        LIMIT ?
    ";

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        echo "Error preparing select query for batch {$batchNumber}: " . mysqli_error($con) . "\n";
        break;
    }

    mysqli_stmt_bind_param($stmt, 'i', $batchSize);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res || mysqli_num_rows($res) === 0) {
        mysqli_stmt_close($stmt);
        echo "No more records found needing publish_date update/sync.\n";
        break;
    }

    $rows = [];
    $batchIds = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $rows[] = $row;
        $batchIds[] = (int)$row['id'];
    }
    mysqli_stmt_close($stmt);

    $currentBatchCount = count($rows);

    // Step 2: Update publish_date = DATE(created_at) in MySQL within transaction
    mysqli_begin_transaction($con);

    $idPlaceholders = implode(',', array_fill(0, count($batchIds), '?'));
    $types = str_repeat('i', count($batchIds));
    $updateSql = "UPDATE tenders_all SET publish_date = DATE(created_at) WHERE id IN ($idPlaceholders)";
    
    $updateStmt = mysqli_prepare($con, $updateSql);
    if (!$updateStmt) {
        mysqli_rollback($con);
        echo "Error preparing update query for batch {$batchNumber}: " . mysqli_error($con) . "\n";
        break;
    }

    mysqli_stmt_bind_param($updateStmt, $types, ...$batchIds);
    $updateSuccess = mysqli_stmt_execute($updateStmt);
    $mysqlUpdated = mysqli_stmt_affected_rows($updateStmt);
    mysqli_stmt_close($updateStmt);

    if (!$updateSuccess) {
        mysqli_rollback($con);
        echo "Error executing MySQL update for batch {$batchNumber}: " . mysqli_error($con) . "\n";
        break;
    }

    // Commit MySQL transaction before syncing to Elasticsearch
    mysqli_commit($con);
    $totalMySQLUpdated += ($mysqlUpdated > 0 ? $mysqlUpdated : 0);

    // Step 3: Prepare Elasticsearch partial update bulk documents
    $bulk = [];
    foreach ($rows as $row) {
        $docId = (string)$row['id'];

        $bulk[] = json_encode([
            'update' => [
                '_index' => $index,
                '_id'    => $docId
            ]
        ]);

        // Extract exact YYYY-MM-DD string directly from created_at without timezone shifts
        $rawCreatedAt = trim((string)$row['created_at']);
        $publishDate = !empty($rawCreatedAt)
            ? substr($rawCreatedAt, 0, 10)
            : null;

        $bulk[] = json_encode([
            'doc' => [
                'publish_date' => $publishDate
            ]
        ]);
    }

    // Step 4: Bulk index/update records in Elasticsearch
    $body = implode("\n", $bulk) . "\n";
    
    $esSyncedCount = 0;
    $esErrors = [];
    $esStatus = 'N/A';

    try {
        $resp = es_request('POST', '_bulk', $body);
        $esStatus = isset($resp['status']) ? $resp['status'] : 'N/A';

        if (isset($resp['body']['items']) && is_array($resp['body']['items'])) {
            foreach ($resp['body']['items'] as $item) {
                $action = key($item);
                $itemData = $item[$action];
                if (isset($itemData['status']) && $itemData['status'] >= 200 && $itemData['status'] < 300) {
                    $esSyncedCount++;
                } else {
                    $docId = isset($itemData['_id']) ? $itemData['_id'] : 'unknown';
                    $errMsg = isset($itemData['error']['reason']) ? $itemData['error']['reason'] : json_encode($itemData['error'] ?? 'Unknown error');
                    $esErrors[] = "Doc ID {$docId}: {$errMsg}";
                }
            }
        }
    } catch (Exception $e) {
        $esStatus = 'EXCEPTION';
        $esErrors[] = $e->getMessage();
    }

    $totalESSynced += $esSyncedCount;
    $totalESErrors += count($esErrors);
    $totalProcessed += $currentBatchCount;

    // Step 5: Display progress in CLI
    echo "Batch #{$batchNumber}: Fetched = {$currentBatchCount}, MySQL Updated = {$mysqlUpdated}, ES Synced = {$esSyncedCount}, ES Status = {$esStatus}\n";
    if (!empty($esErrors)) {
        echo "  ES Errors (" . count($esErrors) . "):\n";
        foreach (array_slice($esErrors, 0, 5) as $err) {
            echo "    - {$err}\n";
        }
        if (count($esErrors) > 5) {
            echo "    ... and " . (count($esErrors) - 5) . " more error(s).\n";
        }
    }
}

echo "--------------------------------------------------------------------------------\n";
echo "Synchronization completed.\n";
echo "Total Batches Processed: {$batchNumber}\n";
echo "Total Records Processed: {$totalProcessed}\n";
echo "Total MySQL Records Updated: {$totalMySQLUpdated}\n";
echo "Total Elasticsearch Records Synced: {$totalESSynced}\n";
echo "Total Elasticsearch Document Errors: {$totalESErrors}\n";
?>
