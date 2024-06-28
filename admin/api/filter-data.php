<?php
include '../includes/connection.php';
include '../includes/functions.php';
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

switch ($endpoint) {
    case 'getFilterData':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = get_results($con, $postData);
        } else {
            $result = null;
        }
        break;
    default:
        $result = null;
}

function get_results($con, $postData)
{
    // return $con;
    $filter_city = "";
    $filter_state = "";
    $filter_tender_value = "";
    $filter_agency = "";
    $filter_department = "";
    $filter_type = "";
    $filter_keywords = "";
    $all_filters = "";
    $user_unique_id = $postData['user_unique_id'];
    $token = $postData['token'];
    $user_data = mysqli_query($con, "SELECT * FROM `users` WHERE  `user_unique_id`='$user_unique_id' AND `token`='$token'");

    $user_result = mysqli_num_rows($user_data);
    if ($user_result == 1) {
        while ($row = mysqli_fetch_assoc($user_data)) {
            $filter_city = $row['filter_city'];
            $filter_state = $row['filter_state'];
            $filter_tender_value = $row['filter_tender_value'];
            $filter_agency = $row['filter_agency'];
            $filter_department = $row['filter_department'];
            $filter_type = $row['filter_type'];
            $filter_keywords = $row['keywords'];
            $all_filters = $row['all_filters'];
        }
        if (!empty($all_filters)) {
            $all_filters = explode(',', $all_filters);
            foreach ($all_filters as $filter) {
                $result['filters'][] = $filter;
            }
            $result['filter_city'] = htmlspecialcode_generator($filter_city);
            $result['filter_state'] = htmlspecialcode_generator($filter_state);
            $result['filter_tender_value'] = htmlspecialcode_generator($filter_tender_value);
            $result['filter_agency'] = htmlspecialcode_generator($filter_agency);
            $result['filter_department'] = htmlspecialcode_generator($filter_department);
            $result['filter_type'] = htmlspecialcode_generator($filter_type);
            $result['filter_keywords'] = htmlspecialcode_generator($filter_keywords);
        }
    } else {
        $result = null;
    }
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
