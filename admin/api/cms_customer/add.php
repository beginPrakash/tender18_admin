<?php

include '../../includes/connection.php';

include '../../includes/functions.php';

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



// switch ($endpoint) {

//     case 'saveCmsCustomer':

//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//             $result = get_results($con, $postData);

//         } else {

//             $result = null;

//         }

//         break;

//     default:

//         $result = null;

// }
$result = get_results($con, $postData);



function get_results($con, $postData)

{

    $customer_id = $postData['customer_id'];
    $company_name = mysqli_real_escape_string($con, $postData['company_name']);
    $customer_name = mysqli_real_escape_string($con, $postData['customer_name']);
    $email_ids = mysqli_real_escape_string($con, $postData['email_ids']);
    $mobile_no = mysqli_real_escape_string($con, $postData['mobile_no']);
    $keywords = mysqli_real_escape_string($con, $postData['keywords']);
    $words = mysqli_real_escape_string($con, $postData['words']);
    $not_used_keywords = mysqli_real_escape_string($con, $postData['not_used_keywords']);
    $filter_city = mysqli_real_escape_string($con, $postData['filter_city']);
    $filter_state = mysqli_real_escape_string($con, $postData['filter_state']);
    $filter_tender_value = mysqli_real_escape_string($con, $postData['filter_tender_value']);
    $filter_agency = mysqli_real_escape_string($con, $postData['filter_agency']);
    $filter_department = mysqli_real_escape_string($con, $postData['filter_department']);
    $filter_type = mysqli_real_escape_string($con, $postData['filter_type']);
    $sender_email_id = mysqli_real_escape_string($con, $postData['sender_email_id']);
    $reply_email_id = mysqli_real_escape_string($con, $postData['reply_email_id']);

    $user_data = mysqli_query($con, "SELECT * FROM `cms_customer` WHERE  `customer_id` = '$customer_id'");

    $user_result = mysqli_num_rows($user_data);

    $result = [];

    if ($user_result >= 1) {
        $q1 = "UPDATE `cms_customer` SET `company_name`='$company_name', `customer_name`='$customer_name', `email_ids`='$email_ids', `keywords`='$keywords', `words`='$words', `not_used_keywords`='$not_used_keywords', `filter_city`='$filter_city', `filter_state`='$filter_state', `filter_tender_value`='$filter_tender_value', `filter_agency`='$filter_agency', `company_name`='$company_name', `filter_department`='$filter_department', `filter_type`='$filter_type',`sender_email_id`='$sender_email_id', `reply_email_id` = '$reply_email_id', `mobile_no` = '$mobile_no' WHERE `customer_id`='$customer_id'";
            
    }else{
        $q1 = "INSERT INTO cms_customer(`customer_id`, `company_name`, `customer_name`, `email_ids`,`keywords`,`words`,`not_used_keywords`,`filter_city`,`filter_state`,`filter_tender_value`,`filter_agency`,`filter_department`,`filter_type`,`sender_email_id`,`reply_email_id`,`mobile_no`) 
        VALUES ('$customer_id', '$company_name', '$customer_name', '$email_ids','$keywords','$words','$not_used_keywords','$filter_city','$filter_state','$filter_tender_value','$filter_agency','$filter_department','$filter_type','$sender_email_id','$reply_email_id','$mobile_no')";
        
        }
      
    mysqli_query($con, $q1);

    $result['company_name'] = htmlspecialcode_generator($company_name);
    $result['q1'] = $q1;
    $result['message'] = "Data saved";
    return $result;

}



if ($result === null) {

    echo json_encode(array("status" => "error"));

} else {

    echo json_encode(array("status" => " success", "data" => $result));

}

die();

