<?php
// sync_by_data.php (for all-tenders)
// Usage: include this file and call sync_tender_by_id($id, $row, $index)

require_once __DIR__ . '/../elastic_client.php';

$index = ES_INDEXES['ALL'];

/**
 * Sync single tender record from provided data to Elasticsearch
 */
function sync_tender_by_id($id, $row, $index) {

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

    $createdAt = !empty($row['created_at'])
        ? date('Y-m-d H:i:s', strtotime($row['created_at']))
        : date('Y-m-d H:i:s'); // Default to now if not provided            

    $doc = [
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
        'opening_date'  => $openingDate,
        'tenders'       => $row['tenders'],
        'created_at'    => $createdAt
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
