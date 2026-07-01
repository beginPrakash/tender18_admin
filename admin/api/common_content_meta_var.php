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

function get_results($con, $postData)
{

    $city = $postData['city'] ?? '';
    $state = $postData['state'] ?? '';
    $source = $postData['department'] ?? '';
    $agency = $postData['agency'] ?? '';
    $keyword = $postData['keyword'] ?? '';
    $metaTenderType = $postData['tendertype'] ?? '';
    $type = $postData['type'] ?? '';
    $meta_arr = [];

    if($type == 'agency-state' || $type == 'state-agency'){
        $type = 'agency-state';
    }elseif($type == 'agency-city' || $type == 'city-agency'){
        $type = 'agency-city';
    }elseif(($type == 'agency-department') || $type == 'department-agency'){
        $type = 'agency-department';
    }elseif(($type == 'keyword-state') || $type == 'state-keyword'){
        $type = 'keyword-state';
    }elseif($type == 'keyword-city' || $type == 'city-keyword'){
        $type = 'keyword-city';
    }elseif($type == 'keyword-agency' || $type == 'agency-keyword'){
        $type = 'keyword-agency';
    }elseif($type == 'keyword-department' || $type == 'department-keyword'){
        $type = 'keyword-department';
    }elseif($type == 'city-department' || $type == 'department-city'){
        $type = 'city-department';
    }elseif($type == 'state-department' || $type == 'department-state'){
        $type = 'state-department';
    }elseif($type == 'tendertype-state' || $type == 'state-tendertype'){
        $type = 'tendertype-state';
    }elseif($type == 'tendertype-keyword' || $type == 'keyword-tendertype'){
        $type = 'tendertype-keyword';
    }elseif($type == 'tendertype-city' || $type == 'city-tendertype'){
        $type = 'tendertype-city';
    }elseif($type == 'tendertype-agency' || $type == 'agency-tendertype'){
        $type = 'tendertype-agency';
    }elseif($type == 'tendertype-department' || $type == 'department-tendertype'){
        $type = 'tendertype-source';
    }else{
        $type = $type;
    }

    
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
            if($type == 'city_page'){
                $search = array("(City)");
                $replace = array($city);
            }elseif($type == 'state_page'){
                $search = array("(State)");
                $replace = array($state);
            }elseif($type == 'keyword_page'){
                $search = array("(Keyword)");
                $replace = array($keyword);
            }elseif($type == 'agency_page'){
                $search = array("(ag_name)", "(Agency)");
                $replace = array($agency, $agency);
            }elseif($type == 'department_page'){
                $search = array("(Source)");
                $replace = array($source);
            }elseif($type == 'gem_state_page'){
                $search = array("(State)");
                $replace = array($state);
            }elseif($type == 'gem_city_page'){
                $search = array("(City)");
                $replace = array($city);
            }elseif($type == 'bidding_agencies'){
                $search = array("(Agency)");
                $replace = array($agency);
            }elseif(!empty($state) && !empty($agency) && ($type == 'agency-state' || $type == 'state-agency')){
                $search = array("(ag_name)", "(State)", "(Agency) ");
                $replace = array($agency, $state, $agency);
            }elseif(!empty($city) && !empty($agency) && ($type == 'agency-city' || $type == 'city-agency')){
                $search = array("(ag_name)", "(City)", "(Agency) ");
                $replace = array($agency, $city, $agency);
            }elseif(!empty($source) && !empty($agency) && ($type == 'agency-department') || $type == 'department-agency'){
                $search = array("(ag_name)", "(Source)", "(Agency)");
                $replace = array($agency, $source, $agency);
            }elseif(!empty($keyword) && !empty($state) && ($type == 'keyword-state') || $type == 'state-keyword'){
                $search = array("(Keyword)", "(State)");
                $replace = array($keyword, $state);
            }elseif(!empty($keyword) && !empty($city) && ($type == 'keyword-city' || $type == 'city-keyword')){
                $search = array("(Keyword)", "(City)");
                $replace = array($keyword, $city);
            }elseif(!empty($keyword) && !empty($agency) && ($type == 'keyword-agency' || $type == 'agency-keyword')){
                $search = array("(ag_name)", "(Keyword)", "(Agency)");
                $replace = array($agency, $ketword, $agency);
            }elseif(!empty($keyword) && !empty($source) && ($type == 'keyword-department' || $type == 'department-keyword')){
                $search = array("(Keyword)", "(Source)");
                $replace = array($keyword, $source);
            }elseif(!empty($city) && !empty($source) && ($type == 'city-department' || $type == 'department-city')){
                $search = array("(City)", "(Source)");
                $replace = array($city, $source);
            }elseif(!empty($state) && !empty($source) && ($type == 'state-department' || $type == 'department-state')){
                $search = array("(State)", "(Source)");
                $replace = array($state, $source);
            }elseif(!empty($metaTenderType) && !empty($state) && ($type == 'tendertype-state' || $type == 'state-tendertype')){
                $search = array("(TenderType)", "(State)");
                $replace = array($metaTenderType, $state);
            }elseif(!empty($metaTenderType) && !empty($keyword) && ($type == 'tendertype-keyword' || $type == 'keyword-tendertype')){
                $search = array("(TenderType)", "(Keyword)");
                $replace = array($metaTenderType, $keyword);
            }elseif(!empty($metaTenderType) && !empty($city) && ($type == 'tendertype-city' || $type == 'city-tendertype')){
                $search = array("(TenderType)", "(City)");
                $replace = array($metaTenderType, $city);
            }elseif(!empty($metaTenderType) && !empty($agency) && ($type == 'tendertype-agency' || $type == 'agency-tendertype')){
                $search = array("(TenderType)", "(Agency)", "(ag_name)");
                $replace = array($metaTenderType, $agency, $agency);
            }elseif(!empty($metaTenderType) && !empty($source) && ($type == 'tendertype-source' || $type == 'source-tendertype')){
                $search = array("(TenderType)", "(Source)");
                $replace = array($metaTenderType, $source);
            }


            $result['faqs'][$count]['faq_id'] = $row['id'];
            $result['faqs'][$count]['title'] = str_replace($search, $replace, $row['title']);
            $result['faqs'][$count]['description'] = str_replace($search, $replace, $row['description']);
            $count++;
        }
    }else{
        $result['faqs'] = [];
    }



    if(!empty($meta_arr)){
        if($type == 'city_page'){
            $search = array("(City)");
            $replace = array($city);
        }elseif($type == 'state_page'){
            $search = array("(State)");
            $replace = array($state);
        }elseif($type == 'keyword_page'){
            $search = array("(Keyword)");
            $replace = array($keyword);
        }elseif($type == 'agency_page'){
            $search = array("(ag_name)", "(Agency)");
            $replace = array($agency,$agency);
        }elseif($type == 'department_page'){
            $search = array("(Source)");
            $replace = array($source);
        }elseif($type == 'gem_state_page'){
            $search = array("(State)");
            $replace = array($state);
        }elseif($type == 'gem_city_page'){
            $search = array("(City)");
            $replace = array($city);
        }elseif($type == 'bidding_agencies'){
            $search = array("(Agency)");
            $replace = array($agency);
        }elseif(!empty($state) && !empty($agency) && ($type == 'agency-state' || $type == 'state-agency')){
            $search = array("(ag_name)", "(State)", "(Agency)");
            $replace = array($agency, $state, $agency);
        }elseif(!empty($city) && !empty($agency) && ($type == 'agency-city' || $type == 'city-agency')){
            $search = array("(ag_name)", "(City)", "(Agency) ");
            $replace = array($agency, $city, $agency);
        }elseif(!empty($source) && !empty($agency) && ($type == 'agency-department') || $type == 'department-agency'){
            $search = array("(ag_name)", "(Source)", "(Agency)");
            $replace = array($agency, $source, $agency);
        }elseif(!empty($keyword) && !empty($state) && ($type == 'keyword-state') || $type == 'state-keyword'){
            $search = array("(Keyword)", "(State)");
            $replace = array($keyword, $state);
        }elseif(!empty($keyword) && !empty($city) && ($type == 'keyword-city' || $type == 'city-keyword')){
            $search = array("(Keyword)", "(City)");
            $replace = array($keyword, $city);
        }elseif(!empty($keyword) && !empty($agency) && ($type == 'keyword-agency' || $type == 'agency-keyword')){
            $search = array("(ag_name)", "(Keyword)", "(Agency)");
            $replace = array($agency, $keyword, $agency);
        }elseif(!empty($keyword) && !empty($source) && ($type == 'keyword-department' || $type == 'department-keyword')){
            $search = array("(Keyword)", "(Source)");
            $replace = array($keyword, $source);
        }elseif(!empty($city) && !empty($source) && ($type == 'city-department' || $type == 'department-city')){
            $search = array("(City)", "(Source)");
            $replace = array($city, $source);
        }elseif(!empty($state) && !empty($source) && ($type == 'state-department' || $type == 'department-state')){
            $search = array("(State)", "(Source)");
            $replace = array($state, $source);
        }elseif(!empty($metaTenderType) && !empty($state) && ($type == 'tendertype-state' || $type == 'state-tendertype')){
            $search = array("(TenderType)", "(State)");
            $replace = array($metaTenderType, $state);
        }elseif(!empty($metaTenderType) && !empty($keyword) && ($type == 'tendertype-keyword' || $type == 'keyword-tendertype')){
            $search = array("(TenderType)", "(Keyword)");
            $replace = array($metaTenderType, $keyword);
        }elseif(!empty($metaTenderType) && !empty($city) && ($type == 'tendertype-city' || $type == 'city-tendertype')){
            $search = array("(TenderType)", "(City)");
            $replace = array($metaTenderType, $city);
        }elseif(!empty($metaTenderType) && !empty($agency) && ($type == 'tendertype-agency' || $type == 'agency-tendertype')){
            $search = array("(TenderType)", "(Agency)", "(ag_name)");
            $replace = array($metaTenderType, $agency, $agency);
        }elseif(!empty($metaTenderType) && !empty($source) && ($type == 'tendertype-source' || $type == 'source-tendertype')){
            $search = array("(TenderType)", "(Source)");
            $replace = array($metaTenderType, $source);
        }

        $result['meta']['title'] = utf8_encode(str_replace($search, $replace, $meta_arr['title']));
        $result['meta']['description'] = utf8_encode(str_replace($search, $replace, $meta_arr['description']));
        $result['meta']['keywords'] = utf8_encode(str_replace($search, $replace, $meta_arr['keywords']));
        $result['meta']['h1'] = utf8_encode(str_replace($search, $replace, $meta_arr['h1']));
        $result['meta']['content'] = utf8_encode(str_replace($search, $replace, $meta_arr['content']));
        $result['meta']['tab_title'] = utf8_encode(str_replace($search, $replace, $meta_arr['tab_title']));
        $result['meta']['tab_description'] = utf8_encode(str_replace($search, $replace, $meta_arr['tab_description']));
        $result['meta']['tab_title2'] = utf8_encode(str_replace($search, $replace, $meta_arr['tab_title2']));
        $result['meta']['tab_description2'] = utf8_encode(str_replace($search, $replace, $meta_arr['tab_description2']));
        $result['meta']['tab_title3'] = utf8_encode(str_replace($search, $replace, $meta_arr['tab_title3']));
        $result['meta']['tab_description3'] = utf8_encode(str_replace($search, $replace, $meta_arr['tab_description3']));
        $result['meta']['tab_title4'] = utf8_encode(str_replace($search, $replace, $meta_arr['tab_title4']));
        $result['meta']['tab_description4'] = utf8_encode(str_replace($search, $replace, $meta_arr['tab_description4']));
        $result['meta']['faq_main_title'] = utf8_encode(str_replace($search, $replace, $meta_arr['faq_main_title']));
        
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
