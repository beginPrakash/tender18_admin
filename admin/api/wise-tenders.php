<?php
include '../includes/connection.php';
include '../includes/functions.php';
include '../../elasticsearch/elastic_client.php';
include '../../elasticsearch/elastic_utils.php';

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

$index = ES_INDEXES['ALL'];

// Get the raw POST data
$rawData = file_get_contents("php://input");
// Decode the JSON data
$postData = json_decode($rawData, true);

$endpoint = isset($postData['endpoint']) ? $postData['endpoint'] : '';

switch ($endpoint) {
    case 'getWiseTendersData':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = get_wise_results($con, $index, $postData);
        } else {
            $result = null;
        }
        break;
    default:
        $result = null;
}

function get_wise_results($con, $index, $postData)
{
    $filters = [
        'state' => $postData['state'] ?? [],
        'city' => $postData['city'] ?? [],
        'agency' => $postData['agency'] ?? [],
        'department' => $postData['department'] ?? []
    ];

    $body = build_elastic_wise_query($filters, 1, 10);

    $resp = es_search($index, $body);
    $data = isset($resp['body']['hits']['hits']) ? $resp['body']['hits']['hits'] : array();

    $tenders = [];
    if (isset($data) && count($data) > 0) {
        $count = 1;
        foreach ($data as $source) {
            $row = $source['_source'];
            
            $tenders[$count] = [
                'ref_no' => $row['ref_no'] ?? "",
                'title' => htmlspecialcode_generator($row['title']),
                'tenders' => $row['tenders'] ?? ""
            ];
            $count++;
        }
    }

    $total = isset($resp['body']['hits']['total']['value']) ? $resp['body']['hits']['total']['value'] : 0;

    $result = [
        'tenders' => $tenders,
        'total' => $total
    ];

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result), JSON_PARTIAL_OUTPUT_ON_ERROR);
}
die();
