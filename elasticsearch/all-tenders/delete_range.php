<?php
require_once dirname(__DIR__) . '/elastic_client.php';

/**
 * Delete records from 'ALL' tenders index based on created_at range.
 * 
 * @param string $start_date Format: YYYY-MM-DD HH:MM:SS
 * @param string $end_date   Format: YYYY-MM-DD HH:MM:SS
 * @return array Response from Elasticsearch
 */
function delete_all_tender_records_by_range($start_date, $end_date) {
    $index = ES_INDEXES['ALL'];
    $path = $index . '/_delete_by_query';
    
    $body = [
        'query' => [
            'range' => [
                'created_at' => [
                    'gte' => $start_date,
                    'lte' => $end_date
                ]
            ]
        ]
    ];
    
    return es_request('POST', $path, $body);
}
?>
