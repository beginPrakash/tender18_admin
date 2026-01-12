<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// search_tenders.php
require_once '../elastic_client.php';

$index = ES_INDEXES['LIVE'];

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Get the raw POST data
$rawData = file_get_contents("php://input");
// Decode the JSON data
$postData = json_decode($rawData, true);

$endpoint = isset($postData['endpoint']) ? $postData['endpoint'] : '';
$result = get_results($postData);

function get_results($postData, $index)
{
    // return $con;
    $start_date = $_GET['startDate'];
    $timestamp1 = strtotime($start_date);
    $start_date = date("Y-m-d", $timestamp1);

    $end_date = $_GET['endDate'];
    $timestamp2 = strtotime($end_date);
    $end_date = date("Y-m-d", $timestamp2);

    $filter_ref_no = $postData['ref_no'];
    $filter_keyword = $postData['keyword'];
    $filter_state = $postData['state'];
    $filter_city = $postData['city'];
    $filter_agency = $postData['agency'];
    $filter_tender_id = $postData['tender_id'];
    $filter_due_date = $postData['due_date'];
    $filter_tender_value = $postData['tender_value'];
    $filter_tender_value_to = $postData['tender_value_to'];
    $filter_department = $postData['department'];
    $filter_type = $postData['type'];
    $keyw = $postData['keyword'];
    $condition = "";
    $condition_u = "";
    $cnt = 0;

    //$page = isset($postData['page_no']) ? abs((int) $postData['page_no']) : 1;
    // $size = isset($_GET['size']) ? (int)$_GET['size'] : 10;

    $page = isset($postData['page_no']) ? max(1, (int)$postData['page_no']) : 1;
    $size = isset($postData['size']) ? max(1, (int)$postData['size']) : 10;
    $from = ($page - 1) * $size;

    $body = array(
        'query' => array(
            'bool' => array(
                'must' => array(
                    array(
                        'multi_match' => array(
                            'query' => $filter_keyword,
                            'fields' => array('title^3', 'description')
                        )
                    ),
                    array(
                        'range' => array(
                            'publish_date' => array('gte' => $start_date, 'lte' => $end_date)
                        )
                    )
                )
            )
        ),
        // 'sort' => array(
        //     array('_score' => array('order' => 'desc')),
        //     array('title.keyword' => array('order' => 'asc'))
        // ),
        'from' => $from,
        'size' => $size
    );

    $resp = es_search($index, $body);
    echo "<pre>"; print_r($resp); die;
    $result = isset($resp['body']['hits']['hits']) ? $resp['body']['hits']['hits'] : array();

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result),JSON_PARTIAL_OUTPUT_ON_ERROR);
}
die();


?>