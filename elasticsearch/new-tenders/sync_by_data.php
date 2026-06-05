<?php
// sync_by_data.php
// Usage: include this file and call sync_new_tender_by_id($id, $row, $index)

require_once __DIR__ . '/../elastic_client.php';

$index = ES_INDEXES['NEW'];

/**
 * Sync single tender record from provided data to Elasticsearch
 */
function sync_new_tender_by_id($id, $row, $index) {

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
        return es_index_document($index, $docId, $doc);
    } catch (Exception $e) {
        return [
            'error' => 'es_error',
            'msg'   => $e->getMessage()
        ];
    }
}
