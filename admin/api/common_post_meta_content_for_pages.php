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
    case 'getcommonMetaData':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = get_results($con, $postData);
        } else {
            $result = null;
        }
        break;
    default:
        $result = null;
}

function get_results($con,$postData)
{

    $type = $postData['type'] ?? '';
    $meta_arr = [];
   
    
    $meta_data = mysqli_query($con, "SELECT * FROM `tenderdetail_page_meta_content` where `type`= '$type' ");
    
    $meta_result = mysqli_num_rows($meta_data);
    if ($meta_result == 1) {
        while ($row = mysqli_fetch_assoc($meta_data)) {
            $meta_arr =  $row;
        }
    }


    $faq_data = mysqli_query($con, "SELECT * FROM `faq_meta` where `type`= '$type' order by id desc");

    $faq_result = mysqli_num_rows($faq_data);

    if ($faq_result > 0) {

        $count = 0;

        while ($row = mysqli_fetch_assoc($faq_data)) {
            $result['faqs'][$count]['faq_id'] = $row['id'];
            $result['faqs'][$count]['title'] = $row['title'];
            $result['faqs'][$count]['description'] = $row['description'];
            $count++;
        }
    }else{
        $result['faqs'] = [];
    }



    if(!empty($meta_arr)){
        $result['meta']['title'] = latin1_to_utf8($meta_arr['title']);
        $result['meta']['description'] = latin1_to_utf8($meta_arr['description']);
        $result['meta']['keywords'] = latin1_to_utf8($meta_arr['keywords']);
        $result['meta']['h1'] = latin1_to_utf8($meta_arr['h1']);
        $result['meta']['content'] = latin1_to_utf8($meta_arr['content']);
        $result['meta']['tab_title'] = latin1_to_utf8($meta_arr['tab_title']);
        $result['meta']['tab_description'] = latin1_to_utf8($meta_arr['tab_description']);
        $result['meta']['tab_title2'] = latin1_to_utf8($meta_arr['tab_title2']);
        $result['meta']['tab_description2'] = latin1_to_utf8($meta_arr['tab_description2']);
        $result['meta']['tab_title3'] = latin1_to_utf8($meta_arr['tab_title3']);
        $result['meta']['tab_description3'] = latin1_to_utf8($meta_arr['tab_description3']);
        $result['meta']['tab_title4'] = latin1_to_utf8($meta_arr['tab_title4']);
        $result['meta']['tab_description4'] = latin1_to_utf8($meta_arr['tab_description4']);
        $result['meta']['faq_main_title'] = latin1_to_utf8($meta_arr['faq_main_title']);
        
    }else{
        $result['meta'] = [];
    }
    return $result;

}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
