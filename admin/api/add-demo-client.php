<?php
include '../includes/connection.php';
include '../includes/functions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/vendor/autoload.php';
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

    case 'saveDemoClientData':

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
    $name = mysqli_real_escape_string($con, $postData['name']);
    $company_name = mysqli_real_escape_string($con, $postData['company_name']);
    $email_id = mysqli_real_escape_string($con, $postData['email']);
    $phone_no = mysqli_real_escape_string($con, $postData['mobile']);
    $state = mysqli_real_escape_string($con, $postData['state']);
    $keywords = mysqli_real_escape_string($con, $postData['keywords']);
    $pass = $postData['password'];

    $q1 = "INSERT INTO demo_client(`name`,`company_name`, `email_id`, `phone_no`, `state`,`keywords`,`password`) VALUES ('$name', '$company_name', '$email_id', '$phone_no', '$state','$keywords','$pass')";
    mysqli_query($con, $q1);

    $result['name'] = htmlspecialcode_generator($name);
    $result['company_name'] = htmlspecialcode_generator($company_name);
    $result['email'] = htmlspecialcode_generator($email_id);
    $result['mobile'] = htmlspecialcode_generator($phone_no);
    $result['state'] = htmlspecialcode_generator($state);
    $result['keywords'] = htmlspecialcode_generator($keywords);
    $result['message'] = "Data saved";
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => "success", "data" => $result));
}
die();
