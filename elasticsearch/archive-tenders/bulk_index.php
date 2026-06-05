<?php
// bulk_index.php
// Run from CLI: php bulk_index.php

set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '-1');

require_once '../elastic_client.php';
require_once '../../admin/includes/connection.php';

$index = ES_INDEXES['ARCHIVE'];

// Validate DB connection
if (!$con) {
    die('MySQL connect error: ' . mysqli_connect_error());
}

$batchSize   = 1000;
$offset      = 0;
$totalIndexed = 0;

while (true) {

    $sql = "
        SELECT 
            id, ref_no, tender_id, department, tender_type, city, state, pincode,
            title, description, agency_type,
            publish_date, due_date,
            tender_value, tender_fee, tender_emd, documents, opening_date
        FROM tenders_archive
        ORDER BY id ASC
        LIMIT ?, ?
    ";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $offset, $batchSize);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (!$res || mysqli_num_rows($res) === 0) {
        break;
    }

    $bulk = [];

    while ($row = mysqli_fetch_assoc($res)) {

        $docId = (string)$row['id'];

        // Bulk metadata
        $bulk[] = json_encode([
            'index' => [
                '_index' => $index, // 🔥 alias only
                '_id'    => $docId
            ]
        ]);

       
        // Document body
        $bulk[] = json_encode([
            'title'         => $row['title'],
            'tender_id'     => $row['tender_id'],
            'ref_no'        => $row['ref_no'],
            'agency_type'   => $row['agency_type'],
            'due_date'      => !empty($row['due_date']) ? substr($row['due_date'], 0, 10) : null,
            'publish_date'  => !empty($row['publish_date']) ? substr($row['publish_date'], 0, 10) : null,
            'opening_date'  => !empty($row['opening_date']) ? substr($row['opening_date'], 0, 10) : null,
            'tender_value'  => (float)$row['tender_value'],
            'tender_fee'    => (float)$row['tender_fee'],
            'tender_emd'    => (float)$row['tender_emd'],
            'pincode'       => $row['pincode'],
            'documents'     => $row['documents'],
            'city'          => $row['city'],
            'state'         => $row['state'],
            'department'    => $row['department'],
            'description'   => $row['description'],
            'tender_type'   => $row['tender_type']
        ]);

        $totalIndexed++;
    }

    // Bulk request
    $body = implode("\n", $bulk) . "\n";

    $resp = es_request('POST', '_bulk', $body);

    echo "Indexed batch OFFSET={$offset}, ES_STATUS={$resp['status']}\n";

    $offset += $batchSize;
    mysqli_stmt_close($stmt);
}

echo "Total indexed documents: {$totalIndexed}\n";
