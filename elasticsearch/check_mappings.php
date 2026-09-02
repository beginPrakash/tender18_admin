<?php
// check_mappings.php
require_once 'elastic_client.php';

header("Content-Type: text/plain");

$index = ES_INDEXES['ALL'];
echo "Checking mappings for index: $index\n\n";

try {
    $resp = es_request('GET', "$index/_mapping");
    echo "HTTP Status: " . $resp['status'] . "\n";
    echo "Mappings:\n";
    echo json_encode($resp['body'], JSON_PRETTY_PRINT) . "\n\n";
    
    $settings = es_request('GET', "$index/_settings");
    echo "Settings:\n";
    echo json_encode($settings['body'], JSON_PRETTY_PRINT) . "\n\n";
    
    // Check document count
    $count = es_request('GET', "$index/_count");
    echo "Document Count: " . json_encode($count['body'], JSON_PRETTY_PRINT) . "\n\n";

    // Run test search for elevator on title
    echo "Search title: elevator (default field):\n";
    $query1 = [
        "query" => [
            "match" => [
                "title" => "elevator"
            ]
        ]
    ];
    $search1 = es_request('GET', "$index/_search", json_encode($query1));
    echo "Hits (default title): " . ($search1['body']['hits']['total']['value'] ?? 0) . "\n";
    if (isset($search1['body']['hits']['hits'])) {
        foreach (array_slice($search1['body']['hits']['hits'], 0, 3) as $hit) {
            echo " - ID: " . $hit['_id'] . " | Title: " . $hit['_source']['title'] . "\n";
        }
    }
    
    // Run test search for elevator on title.minimal
    echo "Search title.minimal: elevator:\n";
    $query2 = [
        "query" => [
            "match" => [
                "title.minimal" => "elevator"
            ]
        ]
    ];
    $search2 = es_request('GET', "$index/_search", json_encode($query2));
    echo "Hits (title.minimal): " . ($search2['body']['hits']['total']['value'] ?? 0) . "\n";
    if (isset($search2['body']['hits']['hits'])) {
        foreach (array_slice($search2['body']['hits']['hits'], 0, 3) as $hit) {
            echo " - ID: " . $hit['_id'] . " | Title: " . $hit['_source']['title'] . "\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
