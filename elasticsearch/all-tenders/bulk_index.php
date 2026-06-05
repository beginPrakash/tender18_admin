<?php
// bulk_index.php
// Run from CLI: php bulk_index.php

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

$batchSize   = 1000;
$offset      = 0;
$totalIndexed = 0;

while (true) {

    $sql = "
        SELECT 
            id, ref_no, tender_id, department, tender_type, city, state, pincode,
            title, description, agency_type,
            publish_date, due_date,
            tender_value, tender_fee, tender_emd, documents, opening_date, created_at
        FROM tenders_all
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
            'created_at'    => $createdAt
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
