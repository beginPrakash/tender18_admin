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

//     case 'cmsCustomerDetail':

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

    $cms_customer_id = $postData['cms_customer_id'];
    
    $user_data = mysqli_query($con, "SELECT * FROM `cms_customer` WHERE  `customer_id` = '$cms_customer_id'");



    $user_result = mysqli_num_rows($user_data);
    $result = [];

    if ($user_result == 1) {

        while ($row = mysqli_fetch_assoc($user_data)) {

            $result['customer_name'] = $row['customer_name'];
            $result['company_name'] = htmlspecialcode_generator($row['company_name']);
            $result['email_ids'] = $row['email_ids'];
            $result['mail_type'] = $row['mail_type'];
            $result['keywords'] = $row['keywords'];
            $result['words'] = $row['words'];
            $result['not_used_keywords'] = $row['not_used_keywords'];
            $result['filter_city'] = $row['filter_city'];
            $result['filter_state'] = $row['filter_state'];
            $result['filter_tender_value'] = $row['filter_tender_value'];
            $result['filter_agency'] = $row['filter_agency'];
            $result['filter_department'] = $row['filter_department'];
            $result['filter_type'] = $row['filter_type'];
            $result['sender_email_id'] = $row['sender_email_id'];
            $result['reply_email_id'] = $row['reply_email_id'];
            $result['customer_id'] = $row['customer_id'];

        }

    } else {

        $result = [];

    }



    return $result;

}



if ($result === null) {

    echo json_encode(array("status" => "error"));

} else {

    echo json_encode(array("status" => " success", "data" => $result));

}

die();

