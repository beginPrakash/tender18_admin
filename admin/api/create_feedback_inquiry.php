<?php
include '../includes/connection.php';
include '../includes/functions.php';
require_once('../MailConfig.php');
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
    case 'saveFeedbackData':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = daily_alert($con, $postData);
        } else {
            $result['message'] = "Invalid method";
        }
        break;
    default:
        $result = null;
}

function daily_alert($con, $postData)
{
    $name = mysqli_real_escape_string($con, $postData['name']);
    $email = mysqli_real_escape_string($con, $postData['email']);
    $mobile = mysqli_real_escape_string($con, $postData['mobile']);
    $description = mysqli_real_escape_string($con, $postData['description']);
    $tender_id = mysqli_real_escape_string($con, $postData['tender_id']);

    $tender_data = mysqli_query($con, "SELECT * FROM `users` where user_unique_id='".$tender_id."'");
    $tender_result = mysqli_num_rows($tender_data);
    
    if ($tender_result == 1) {
        while ($row = mysqli_fetch_assoc($tender_data)) {
            $tender_id = $row['user_id'];
        }
    }


    $q1 = "INSERT INTO feedback_inquiry_form(`name`, `email`, `mobile`, `description`, `tender_id`) VALUES ('$name', '$email', '$mobile', '$description', '$tender_id')";
    mysqli_query($con, $q1);
    

    $result['name'] = htmlspecialcode_generator($name);
    $result['email'] = htmlspecialcode_generator($email);
    $result['mobile'] = htmlspecialcode_generator($mobile);
    $result['description'] = htmlspecialcode_generator($description);
    $result['tender_id'] = $tender_id;
    $result['message'] = "Data saved";
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
