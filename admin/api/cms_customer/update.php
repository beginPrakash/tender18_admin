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

// switch ($endpoint) {
//     case 'updateCmsCustomer':
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

    $cms_id = $postData['cms_id'];
            $customer_id = $postData['customer_id'];
            $company_name = $postData['company_name'];
            $customer_name = $postData['customer_name'];
            $email_ids = $postData['email_ids'];
            $mail_type = $postData['mail_type'];
            $keywords = $postData['keywords'];
            $words = $postData['words'];
            $not_used_keywords = $postData['not_used_keywords'];
            $filter_city = $postData['filter_city'];
            $filter_state = $postData['filter_state'];
            $filter_tender_value = $postData['filter_tender_value'];
            $filter_agency = $postData['filter_agency'];
            $filter_department = $postData['filter_department'];
            $filter_type = $postData['filter_type'];
            $sender_email_id = $postData['sender_email_id'];

            $q1 = "UPDATE `cms_customer` SET `customer_id`='$customer_id', `company_name`='$company_name', `customer_name`='$customer_name', `email_ids`='$email_ids', `mail_type`='$mail_type', `keywords`='$keywords', `words`='$words', `not_used_keywords`='$not_used_keywords', 
            `filter_city`='$filter_city', `filter_state`='$filter_state', `filter_tender_value`='$filter_tender_value', `filter_agency`='$filter_agency', `company_name`='$company_name', `filter_department`='$filter_department', `filter_type`='$filter_type', `sender_email_id`='$sender_email_id' WHERE `id`='$cms_id'";
            mysqli_query($con, $q1);
            $result['company_name'] = $company_name;
            $result['message'] = "Data saved";

           
            return $result;
  
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
