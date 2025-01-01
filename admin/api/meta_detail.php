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
    case 'getMetaDetailData':
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

    $metaState = $postData['metaState'] ?? '';
    $metaCity = $postData['metaCity'] ?? '';
    $metaKeyword = $postData['metaKeyword'] ?? '';
    $metaAgency = $postData['metaAgency'] ?? ''; 
    $metaAgencyStr = str_replace('-', ' ', $metaAgency);
    $metaDepartment = $postData['metaDepartment'] ?? '';
    $meta_arr = [];
    $deptmeta_arr = [];
    $agencymeta_arr = [];
    $citymeta_arr = [];
    $agmeta_arr=[];
    $keymeta_arr=[];

    if(!empty($metaState)){
        $state_data = mysqli_query($con, "SELECT `name`,`title`,`description`,`keywords`,`h1`,`content` FROM `states` where name LIKE '%$metaState%'");
        $state_result = mysqli_num_rows($state_data);
        if ($state_result == 1) {
            while ($row = mysqli_fetch_assoc($state_data)) {
                $meta_arr =  $row;
            }
        }
    }

    if(!empty($metaAgencyStr)){
        //echo "SELECT `agency_name` FROM `tender_agencies` where agency_name LIKE '%$metaAgencyStr' order by `id` desc limit 1";exit;
        $state_data = mysqli_query($con, "SELECT `agency_name` FROM `tender_agencies` where agency_name LIKE '%$metaAgencyStr%' order by `id` desc limit 1");
        $state_result = mysqli_num_rows($state_data);
        if ($state_result == 1) {
            while ($row = mysqli_fetch_assoc($state_data)) {
                $agencymeta_arr =  $row;
            }
        }

        $select = mysqli_query($con, "SELECT * FROM `tender_agencies` WHERE  `agency_name`='{$metaAgencyStr}' order by `id` asc limit 1");
        $result_data = mysqli_num_rows($select);
        $pass = mysqli_fetch_assoc($select);
        
        if ($result_data == 1) {
            $select1 = mysqli_query($con, "SELECT * FROM `agency_meta_content_individual` WHERE  `agency_id`='{$pass['id']}'");
            $result_data1 = mysqli_num_rows($select1);
            $pass1 = mysqli_fetch_assoc($select1);
            if($result_data1 == 1){
                $ameta_data = mysqli_query($con, "SELECT * FROM `agency_meta_content_individual` where agency_id = {$pass['id']} ");
            }else{
                $ameta_data = mysqli_query($con, "SELECT * FROM `agency_meta_content` where id = 1");
            }
        }

        $ameta_result = mysqli_num_rows($ameta_data);
        if ($ameta_result == 1) {
            while ($row = mysqli_fetch_assoc($ameta_data)) {
                $agmeta_arr =  $row;
            }
        }
    }

    if(!empty($metaCity)){
        $select = mysqli_query($con, "SELECT * FROM `tender_zipcodes` WHERE  `city`='{$metaCity}' order by city asc limit 1");
        $result_data = mysqli_num_rows($select);
        $pass = mysqli_fetch_assoc($select);
        if ($result_data == 1) {
            $select1 = mysqli_query($con, "SELECT * FROM `city_meta_content_individual` WHERE  `city_id`='{$pass['id']}'");
            $result_data1 = mysqli_num_rows($select1);
            $pass1 = mysqli_fetch_assoc($select1);
            if($result_data1 == 1){
                
                $city_data = mysqli_query($con, "SELECT * FROM `city_meta_content_individual` where city_id = {$pass['id']} ");
            
            }else{
                $city_data = mysqli_query($con, "SELECT * FROM `city_meta_content` where id = 1 ");
            }
        }
        
        $city_result = mysqli_num_rows($city_data);
        if ($city_result == 1) {
            while ($row = mysqli_fetch_assoc($city_data)) {
                $citymeta_arr =  $row;
            }
        }
    }

    if(!empty($metaKeyword)){
        $select = mysqli_query($con, "SELECT * FROM `keywords` WHERE  `name`='{$metaKeyword}' limit 1");
        $result_data = mysqli_num_rows($select);
        $pass = mysqli_fetch_assoc($select);
        if ($result_data == 1) {
            $select1 = mysqli_query($con, "SELECT * FROM `keyword_meta_content_individual` WHERE  `keyword_id`='{$pass['id']}'");
            $result_data1 = mysqli_num_rows($select1);
            $pass1 = mysqli_fetch_assoc($select1);
            if($result_data1 == 1){
                
                $keyw_data = mysqli_query($con, "SELECT * FROM `keyword_meta_content_individual` where keyword_id = {$pass['id']} ");
            
            }else{
                $keyw_data = mysqli_query($con, "SELECT * FROM `keyword_meta_content` where id = 1 ");
            }
        }
        $keyw_result = mysqli_num_rows($keyw_data);
        if ($keyw_result == 1) {
            while ($row = mysqli_fetch_assoc($keyw_data)) {
                $keymeta_arr =  $row;
            }
        }
    }

    if(!empty($metaDepartment)){
        $dept_data = mysqli_query($con, "SELECT `name`,`title`,`description`,`keywords`,`h1`,`content` FROM `departments` where name LIKE '%$metaDepartment%'");
        $dept_result = mysqli_num_rows($dept_data);
        if ($dept_result == 1) {
            while ($row = mysqli_fetch_assoc($dept_data)) {
                $deptmeta_arr =  $row;
            }
        }
    }

    if(!empty($meta_arr)){
        $result['meta']['title'] = $meta_arr['title'];
        $result['meta']['description'] = $meta_arr['description'];
        $result['meta']['keywords'] = $meta_arr['keywords'];
        $result['meta']['h1'] = $meta_arr['h1'];
        $result['meta']['content'] = $meta_arr['content'];
        $result['meta']['label'] = $metaState;
    }else{
        $result['meta']['title'] = '';
        $result['meta']['description'] = '';
        $result['meta']['keywords'] = '';
        $result['meta']['h1'] = '';
        $result['meta']['content'] = '';
        $result['meta']['label'] = '';
    }


    if(!empty($deptmeta_arr)){
        $result['dept_meta']['title'] = $deptmeta_arr['title'];
        $result['dept_meta']['description'] = $deptmeta_arr['description'];
        $result['dept_meta']['keywords'] = $deptmeta_arr['keywords'];
        $result['dept_meta']['h1'] = $deptmeta_arr['h1'];
        $result['dept_meta']['content'] = $deptmeta_arr['content'];
        $result['dept_meta']['label'] = $deptmeta_arr['name'];
    }else{
        $result['dept_meta']['title'] = '';
        $result['dept_meta']['description'] = '';
        $result['dept_meta']['keywords'] = '';
        $result['dept_meta']['h1'] = '';
        $result['dept_meta']['content'] = '';
        $result['dept_meta']['label'] = '';
    }
    
    if(!empty($agmeta_arr) && !empty($agencymeta_arr)){
        $ag_name = $agencymeta_arr['agency_name'] ?? '';
        $short_form = trim(substr($ag_name, strpos($ag_name, "- ") + 1));
        $title = str_replace("(ag_name)",$ag_name,$agmeta_arr['title']);
        $final_title = str_replace("(short_form)",$short_form,$title);
        $description = str_replace("(ag_name)",$ag_name,$agmeta_arr['description']);
        $final_description = str_replace("(short_form)",$short_form,$description);
        $keywords = str_replace("(ag_name)",$ag_name,$agmeta_arr['keywords']);
        $h1 = str_replace("(ag_name)",$ag_name,$agmeta_arr['h1']);
        $h1_final = str_replace("(short_form)",$short_form,$h1);
        $content = str_replace("(ag_name)",$ag_name,$agmeta_arr['content']);
        $final_content = str_replace("(short_form)",$short_form,$content);
        $result['agency_meta']['title'] = $final_title;
        $result['agency_meta']['description'] = $final_description;
        $result['agency_meta']['keywords'] = $keywords;
        $result['agency_meta']['h1'] = $h1_final;
        $result['agency_meta']['content'] = $final_content;
        $result['agency_meta']['label'] = $ag_name;
    }else{
        $result['agency_meta']['title'] = '';
        $result['agency_meta']['description'] = '';
        $result['agency_meta']['keywords'] = '';
        $result['agency_meta']['h1'] = '';
        $result['agency_meta']['content'] = '';
        $result['agency_meta']['label'] = '';
    }
    if(!empty($citymeta_arr)){
        $metaCity = ucfirst($metaCity);
        $title = str_replace("(City)",$metaCity,$citymeta_arr['title']);
        $final_title = str_replace("(Brand name)",'Tender 18',$title);
        $description = str_replace("(City)",$metaCity,$citymeta_arr['description']);
        $final_description = str_replace("(Brand name)",'Tender 18',$description);
        $keywords = str_replace("(City)",$metaCity,$citymeta_arr['keywords']);
        $h1 = str_replace("(City)",$metaCity,$citymeta_arr['h1']);
        $content = str_replace("(City)",$metaCity,$citymeta_arr['content']);
        $result['city_meta']['title'] = $final_title;
        $result['city_meta']['description'] = $final_description;
        $result['city_meta']['keywords'] = $keywords;
        $result['city_meta']['h1'] = $h1;
        $result['city_meta']['content'] = $content;
        $result['city_meta']['label'] = $metaCity;
    }else{
        $result['city_meta']['title'] = '';
        $result['city_meta']['description'] = '';
        $result['city_meta']['keywords'] = '';
        $result['city_meta']['h1'] = '';
        $result['city_meta']['content'] = '';
        $result['city_meta']['label'] = '';
    }
    if(!empty($keymeta_arr)){
        $metaKeyword = ucfirst($metaKeyword);
        $title = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['title']);
        $final_title = str_replace("(Brand name)",'Tender 18',$title);
        $description = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['description']);
        $final_description = str_replace("(Brand name)",'Tender 18',$description);
        $keywords = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['keywords']);
        $h1 = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['h1']);
        $content = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['content']);
        $result['keyword_meta']['title'] = $final_title;
        $result['keyword_meta']['description'] = $final_description;
        $result['keyword_meta']['keywords'] = $keywords;
        $result['keyword_meta']['h1'] = $h1;
        $result['keyword_meta']['content'] = $content;
        $result['keyword_meta']['label'] = $metaKeyword;
    }else{
        $result['keyword_meta']['title'] = '';
        $result['keyword_meta']['description'] = '';
        $result['keyword_meta']['keywords'] = '';
        $result['keyword_meta']['h1'] = '';
        $result['keyword_meta']['content'] = '';
        $result['keyword_meta']['label'] = '';
    }
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
