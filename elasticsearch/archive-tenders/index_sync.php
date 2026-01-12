<?php
// index_sync.php
// Usage: include this file and call sync_archive_tender_by_id($id)

require_once __DIR__ . '/../elastic_client.php';
require_once __DIR__ . '/../../admin/includes/connection.php'; // defines $con

if (!$con) {
    die('MySQL connect error: ' . mysqli_connect_error());
}
/**
 * Sync single tender record from MySQL to Elasticsearch
 */
function sync_archive_tender_by_id($id, $archive_tenders_index) {

    global $con;

    $sql = "SELECT title, tender_id, ref_no, agency_type, due_date, tender_value, pincode, publish_date, tender_fee, tender_emd, documents, city, state, department, description, tender_type, opening_date FROM tenders_archive WHERE id = ? LIMIT 1";

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

    $openingDate = !empty($row['opening_date'])
        ? substr($row['opening_date'], 0, 10)
        : null;

    $doc = [
        'title'         => $row['title'],
        'tender_id'     => $row['tender_id'],
        'ref_no'        => $row['ref_no'],
        'agency_type'   => $row['agency_type'],
        'due_date'      => $dueDate,
        'tender_value'  => (float)$row['tender_value'],
        'pincode'       => $row['pincode'],
        'publish_date'  => $publishDate,
        'tender_fee'    => (float)$row['tender_fee'],
        'tender_emd'    => (float)$row['tender_emd'],
        'documents'     => $row['documents'],
        'city'          => $row['city'],
        'state'         => $row['state'],
        'department'    => $row['department'],
        'description'   => $row['description'],
        'tender_type'   => $row['tender_type'],
        'opening_date'  => $openingDate 
    ];

    // Use numeric ID (recommended)
    $docId = (string)$id;

    try {
        // 1. Index into ARCHIVE TENDERS
        es_index_document($archive_tenders_index, $docId, $doc);
    } catch (Exception $e) {
        return [
            'error' => 'es_error',
            'msg'   => $e->getMessage()
        ];
    }
}
