<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');



$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getcommonMetaData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{

    $type = $_GET['type'] ?? '';
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
        $result['meta']['title'] = utf8_encode($meta_arr['title']);
        $result['meta']['description'] = utf8_encode($meta_arr['description']);
        $result['meta']['keywords'] = utf8_encode($meta_arr['keywords']);
        $result['meta']['h1'] = utf8_encode($meta_arr['h1']);
        $result['meta']['content'] = utf8_encode($meta_arr['content']);
        $result['meta']['tab_title'] = utf8_encode($meta_arr['tab_title']);
        $result['meta']['tab_description'] = utf8_encode($meta_arr['tab_description']);
        $result['meta']['tab_title2'] = utf8_encode($meta_arr['tab_title2']);
        $result['meta']['tab_description2'] = utf8_encode($meta_arr['tab_description2']);
        $result['meta']['tab_title3'] = utf8_encode($meta_arr['tab_title3']);
        $result['meta']['tab_description3'] = utf8_encode($meta_arr['tab_description3']);
        $result['meta']['tab_title4'] = utf8_encode($meta_arr['tab_title4']);
        $result['meta']['tab_description4'] = utf8_encode($meta_arr['tab_description4']);
        $result['meta']['faq_main_title'] = utf8_encode($meta_arr['faq_main_title']);
        
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
