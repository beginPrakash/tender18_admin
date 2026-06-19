<?php
// bulk_update_tenders_type.php
// Run from CLI: php bulk_update_tenders_type.php

set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '-1');

require_once '../../elastic_client.php';
require_once '../../../admin/includes/connection.php';

$index = ES_INDEXES['ALL'];

// Validate DB connection
if (!$con) {
    die('MySQL connect error: ' . mysqli_connect_error());
}

$batchSize    = 1000;
$offset       = 0;
$totalUpdated = 0;

while (true) {

    /*
    |--------------------------------------------------------------------------
    | Get matching records from tenders_all + tenders_archive
    |--------------------------------------------------------------------------
    | If ref_no exists in tenders_archive table,
    | then update ES field "tenders" => ""
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT 
            ta.id,
            ta.ref_no
        FROM tenders_all ta
        INNER JOIN tenders_archive taa 
            ON taa.ref_no = ta.ref_no
        ORDER BY ta.id ASC
        LIMIT ?, ?
    ";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die('Prepare failed: ' . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 'ii', $offset, $batchSize);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if (!$res || mysqli_num_rows($res) === 0) {
        break;
    }

    $bulk = [];
    $ids  = [];

    while ($row = mysqli_fetch_assoc($res)) {
        
        $docId = (string)$row['id'];

        // Store ids for bulk MySQL update
        $ids[] = (int)$row['id'];

        /*
        |--------------------------------------------------------------------------
        | Elasticsearch Bulk Update
        |--------------------------------------------------------------------------
        */

        $bulk[] = json_encode([
            'update' => [
                '_index' => $index,
                '_id'    => $docId
            ]
        ]);

        $bulk[] = json_encode([
            'doc' => [
                'tenders' => ''
            ]
        ]);

        $totalUpdated++;
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk MySQL Update
    |--------------------------------------------------------------------------
    */

    if (!empty($ids)) {

        $idsString = implode(',', $ids);

        $updateQuery = "
            UPDATE tenders_all
            SET tenders = ''
            WHERE id IN ($idsString)
        ";

        mysqli_query($con, $updateQuery);

        echo "MySQL Updated Rows: " . count($ids) . "\n";
    }

    /*
    |--------------------------------------------------------------------------
    | Execute Elasticsearch Bulk Update
    |--------------------------------------------------------------------------
    */

    if (!empty($bulk)) {

        $body = implode("\n", $bulk) . "\n";

        $resp = es_request('POST', '_bulk', $body);

        echo "ES Updated Batch OFFSET={$offset}, STATUS={$resp['status']}\n";
    }

    /*
    |--------------------------------------------------------------------------
    | Next Batch
    |--------------------------------------------------------------------------
    */

    $offset += $batchSize;

    mysqli_stmt_close($stmt);
}

echo "\nTotal Updated Documents: {$totalUpdated}\n";