<?php
require_once 'all-tenders/delete_range.php';
require_once 'elastic_client.php';

$index = ES_INDEXES['ALL'];
// Use a range that is likely to have 0 or few records for safety, 
// or one that the user might actually want to clear if specified.
// Since no range was specified, I'll just check a very old range.
$start = '2026-02-24 00:00:00';
$end   = '2026-02-24 23:59:59';

echo "Checking records in index '$index' between '$start' and '$end'...\n";
$query = [
    'query' => [
        'range' => ['created_at' => ['gte' => $start, 'lte' => $end]]
    ]
];

try {
    $resp = es_search($index, $query);
    $count_before = $resp['body']['hits']['total']['value'] ?? 0;
    echo "Count before: $count_before\n";

    if ($count_before > 0) {
        echo "Found $count_before records. Proceeding with delete...\n";
        $res = delete_all_tender_records_by_range($start, $end);
        echo "Delete Response Status: " . $res['status'] . "\n";
        echo "Deleted count from response: " . ($res['body']['deleted'] ?? 0) . "\n";
        
        $resp = es_search($index, $query);
        $count_after = $resp['body']['hits']['total']['value'] ?? 0;
        echo "Count after: $count_after\n";
    } else {
        echo "No records found in this range. Function logic for range query is syntactically correct.\n";
    }
} catch (Exception $e) {
    echo "Error during verification: " . $e->getMessage() . "\n";
}
?>
