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
    case 'saveDailyAlertData':
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
    $company_name = mysqli_real_escape_string($con, $postData['company_name']);
    $email = mysqli_real_escape_string($con, $postData['email']);
    $mobile = mysqli_real_escape_string($con, $postData['mobile']);
    $state = mysqli_real_escape_string($con, $postData['state']);

    $q1 = "INSERT INTO inquiries(`name`, `company_name`, `email`, `mobile`, `state`,`type`) VALUES ('$name', '$company_name', '$email', '$mobile', '$state','get_quote_form')";
    mysqli_query($con, $q1);

    $mail_name = 'Tender18';
    $to = ADMIN_EMAIL;
    $message = 'Hello Admin, below are the Get A Free Quote form details...<br><br>';
    $message .= '<b>Name :</b> ' . htmlspecialcode_generator($name) . '<br>';
    $message .= '<b>Company Name :</b> ' . htmlspecialcode_generator($company_name) . '<br>';
    $message .= '<b>Email :</b> ' . htmlspecialcode_generator($email) . '<br>';
    $message .= '<b>Mobile :</b> ' . htmlspecialcode_generator($mobile) . '<br>';
    $message .= '<b>State :</b> ' . htmlspecialcode_generator($state) . '<br>';
    $message .= '';
    $subject = 'Get A Free Quote';

    email($mail_name, $to, $message, $subject);

    $result['name'] = htmlspecialcode_generator($name);
    $result['company_name'] = htmlspecialcode_generator($company_name);
    $result['email'] = htmlspecialcode_generator($email);
    $result['mobile'] = htmlspecialcode_generator($mobile);
    $result['state'] = htmlspecialcode_generator($state);
    $result['message'] = "Data saved";
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
