<?php
// sync_diff.php
// Run from CLI: php sync_diff.php
// Finds differences between MySQL database and Elasticsearch index,
// deletes orphaned ES records, and bulk inserts missing MySQL records into ES.

set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '-1');

require_once '../elastic_client.php';
require_once '../../admin/includes/connection.php';

$index = ES_INDEXES['ALL'];

// Validate DB connection
if (!$con) {
    die('MySQL connect error: ' . mysqli_connect_error());
}

echo "=== Syncing IDs between MySQL and Elasticsearch ===\n";

// 1. Fetch all IDs from Database
echo "Fetching all IDs from Database...\n";
$dbIds = [];
$sql = "SELECT id FROM tenders_all";
$res = mysqli_query($con, $sql);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $dbIds[] = (string)$row['id'];
    }
} else {
    die("Error fetching DB IDs: " . mysqli_error($con) . "\n");
}
echo "Total DB Records: " . count($dbIds) . "\n";

// 2. Fetch all IDs from Elasticsearch using Scroll API
echo "Fetching all IDs from Elasticsearch...\n";
$esIds = [];
$scrollQuery = [
    'size' => 5000,
    '_source' => false, // We only need the _id field
];
$resp = es_request('POST', $index . '/_search?scroll=2m', $scrollQuery);

if (isset($resp['body']['_scroll_id'])) {
    $scrollId = $resp['body']['_scroll_id'];
    $hits = $resp['body']['hits']['hits'] ?? [];
    
    while (count($hits) > 0) {
        foreach ($hits as $hit) {
            $esIds[] = $hit['_id'];
        }
        
        $resp = es_request('POST', '_search/scroll', [
            'scroll' => '2m',
            'scroll_id' => $scrollId
        ]);

        if (isset($resp['body']['error'])) {
            echo "\nElasticsearch scroll error: " . print_r($resp['body']['error'], true) . "\n";
            break;
        }

        $hits = $resp['body']['hits']['hits'] ?? [];
        if (isset($resp['body']['_scroll_id'])) {
            $scrollId = $resp['body']['_scroll_id'];
        }
    }
    
    // Clear scroll context to free resources
    es_request('DELETE', '_search/scroll', [
        'scroll_id' => $scrollId
    ]);
} else {
    echo "Warning: Could not fetch from ES or index is empty.\n";
}
echo "Total ES Records: " . count($esIds) . "\n";

// 3. Calculate Differences
echo "Calculating differences...\n";
$toDeleteFromEs = array_diff($esIds, $dbIds);
$toInsertToEs   = array_diff($dbIds, $esIds);

// Re-index arrays to ensure they are sequential
$toDeleteFromEs = array_values($toDeleteFromEs);
$toInsertToEs   = array_values($toInsertToEs);

echo "Records to delete from ES (orphaned): " . count($toDeleteFromEs) . "\n";
echo "Records to insert to ES (missing): " . count($toInsertToEs) . "\n";

// 4. Delete missing DB records from ES
if (!empty($toDeleteFromEs)) {
    echo "Deleting excess records from ES...\n";
    if (function_exists('es_bulk_delete_by_ids')) {
        $delResp = es_bulk_delete_by_ids($toDeleteFromEs, $index, 1000);
        echo "Deleted " . ($delResp['deleted'] ?? 0) . " records. " . ($delResp['message'] ?? '') . "\n";
    } else {
        // Fallback if es_bulk_delete_by_ids is not available
        $chunks = array_chunk($toDeleteFromEs, 1000);
        $totalDeleted = 0;
        foreach ($chunks as $chunk) {
            $bulk = [];
            foreach ($chunk as $docId) {
                $bulk[] = json_encode([
                    'delete' => [
                        '_index' => $index,
                        '_id'    => (string)$docId
                    ]
                ]);
            }
            $body = implode("\n", $bulk) . "\n";
            $respDel = es_request('POST', '_bulk', $body);
            $totalDeleted += count($chunk);
            echo "Deleted chunk ES_STATUS=" . $respDel['status'] . "\n";
        }
    }
}

// 5. Bulk Insert missing ES records from DB
if (!empty($toInsertToEs)) {
    echo "Inserting missing records to ES...\n";
    $chunks = array_chunk($toInsertToEs, 1000);
    
    $totalIndexed = 0;
    foreach ($chunks as $chunkIndex => $chunk) {
        $idsList = implode(',', array_map('intval', $chunk));
        $dataSql = "
            SELECT 
                id, ref_no, tender_id, department, tender_type, city, state, pincode,
                title, description, agency_type,
                publish_date, due_date,
                tender_value, tender_fee, tender_emd, documents, opening_date, created_at, tenders
            FROM tenders_all
            WHERE id IN ($idsList)
        ";
        
        $dataRes = mysqli_query($con, $dataSql);
        if ($dataRes && mysqli_num_rows($dataRes) > 0) {
            $bulk = [];
            while ($row = mysqli_fetch_assoc($dataRes)) {
                $docId = (string)$row['id'];
                
                // Bulk metadata
                $bulk[] = json_encode([
                    'index' => [
                        '_index' => $index,
                        '_id'    => $docId
                    ]
                ]);

                // Normalize dates
                $publishDate = !empty($row['publish_date'])
                    ? substr($row['publish_date'], 0, 10)
                    : null;

                $dueDate = !empty($row['due_date'])
                    ? substr($row['due_date'], 0, 10)
                    : null;

                $openingDate = !empty($row['opening_date'])
                    ? substr($row['opening_date'], 0, 10)
                    : null;

                $createdAt = !empty($row['created_at'])
                    ? date('Y-m-d H:i:s', strtotime($row['created_at']))
                    : null;            

                // Document body
                $bulk[] = json_encode([
                    'ref_no'        => $row['ref_no'],
                    'tender_id'     => $row['tender_id'],
                    'department'    => $row['department'],
                    'tender_type'   => $row['tender_type'],
                    'city'          => $row['city'],
                    'state'         => $row['state'],
                    'pincode'       => $row['pincode'],
                    'title'         => $row['title'],
                    'description'   => $row['description'],
                    'agency_type'   => $row['agency_type'],
                    'publish_date'  => $publishDate,
                    'due_date'      => $dueDate,
                    'tender_value'  => (float)$row['tender_value'],
                    'tender_fee'    => (float)$row['tender_fee'],
                    'tender_emd'    => (float)$row['tender_emd'],
                    'documents'     => $row['documents'],
                    'opening_date'  => $openingDate,
                    'tenders'       => $row['tenders'],
                    'created_at'    => $createdAt
                ]);

                $totalIndexed++;
            }
            
            // Bulk request
            $body = implode("\n", $bulk) . "\n";
            $insResp = es_request('POST', '_bulk', $body);
            
            echo "Indexed batch " . ($chunkIndex + 1) . " of " . count($chunks) . ", ES_STATUS={$insResp['status']}\n";
        }
    }
    echo "Total successfully inserted into ES: {$totalIndexed}\n";
}

echo "=== Sync completed ===\n";
