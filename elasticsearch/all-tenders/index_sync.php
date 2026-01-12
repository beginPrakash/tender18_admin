<?php
// index_sync.php
// Usage: include this file and call sync_tender_by_id($id)

require_once __DIR__ . '/../elastic_client.php';
require_once __DIR__ . '/../../admin/includes/connection.php'; // defines $con

if (!$con) {
    die('MySQL connect error: ' . mysqli_connect_error());
}
$index = ES_INDEXES['ALL'];
/**
 * Sync single tender record from MySQL to Elasticsearch
 */
function sync_tender_by_id($id, $index) {

    global $con;

    $sql = "SELECT ref_no, department, city, state, pincode, title, description, agency_type, publish_date, due_date, tender_value, tender_fee, tender_emd FROM tenders_all WHERE id = ? LIMIT 1";

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        return [
            'error' => 'prepare_failed',
            'msg'   => mysqli_error($con)
        ];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$row) {
        return ['error' => 'not_found'];
    }

    // Normalize dates (ES expects yyyy-MM-dd)
    $publishDate = !empty($row['publish_date'])
        ? substr($row['publish_date'], 0, 10)
        : null;

    $dueDate = !empty($row['due_date'])
        ? substr($row['due_date'], 0, 10)
        : null;

    $doc = [
        'ref_no'        => $row['ref_no'],
        'department'    => $row['department'],
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
        'tender_emd'    => (float)$row['tender_emd']
    ];

    // Use numeric ID (recommended)
    $docId = (string)$id;

    try {
        return es_index_document($index, $docId, $doc);
    } catch (Exception $e) {
        return [
            'error' => 'es_error',
            'msg'   => $e->getMessage()
        ];
    }
}

/* ------------------ TEST ------------------ */
// echo "<pre>";
// print_r(sync_tender_by_id(13544400, $index));
// die();
