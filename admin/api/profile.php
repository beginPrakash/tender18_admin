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
    case 'getProfileData':
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
    $user_unique_id = $postData['user_unique_id'];
    $token = $postData['token'];
    $user_data = mysqli_query($con, "SELECT * FROM `users` WHERE  `user_unique_id`='$user_unique_id' AND `token`='$token'");

    $user_result = mysqli_num_rows($user_data);
    if ($user_result == 1) {
        while ($row = mysqli_fetch_assoc($user_data)) {
            $result['customer_name'] = $row['customer_name'];
            $result['company_name'] = htmlspecialcode_generator($row['company_name']);
            $result['users_name'] = $row['users_name'];
            $result['users_email'] = $row['users_email'];
            $result['alt_email'] = $row['alt_email'];
            $result['mobile_number'] = $row['mobile_number'];
            $result['alt_mobile'] = $row['alt_mobile'];
            $result['whatsapp_alert_no'] = $row['whatsapp_alert_no'];
            $result['address'] = htmlspecialcode_generator($row['address']);
            $result['state'] = $row['state'];
            $result['status'] = $row['status'];
            $result['keywords'] = htmlspecialcode_generator($row['keywords']);
            $result['words'] = htmlspecialcode_generator($row['words']);
            $result['not_used_keywords'] = htmlspecialcode_generator($row['not_used_keywords']);
            $result['start_date'] = date('M d, Y', strtotime($row['start_date']));
            $result['expired_date'] = date('M d, Y', strtotime($row['expired_date']));
            $result['duration'] = $row['duration'];
            $result['all_filters'] = $row['all_filters'];
            $result['filter_state'] = htmlspecialcode_generator($row['filter_state']);
            $result['filter_city'] = htmlspecialcode_generator($row['filter_city']);
            $result['filter_agency'] = htmlspecialcode_generator($row['filter_agency']);
            $result['filter_department'] = htmlspecialcode_generator($row['filter_department']);
            $result['filter_type'] = htmlspecialcode_generator($row['filter_type']);
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
