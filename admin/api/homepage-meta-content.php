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
    case 'getHomepageMetaData':
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

    $meta_arr = [];
    
    $meta_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `homepage_meta_content` where id=1 ");
    
    $meta_result = mysqli_num_rows($meta_data);
    if ($meta_result == 1) {
        while ($row = mysqli_fetch_assoc($meta_data)) {
            $meta_arr =  $row;
        }
    }



    if(!empty($meta_arr)){
        $result['meta']['title'] = $meta_arr['title'];
        $result['meta']['description'] = $meta_arr['description'];
        $result['meta']['keywords'] = $meta_arr['keywords'];
        $result['meta']['h1'] = $meta_arr['h1'];
        $result['meta']['content'] = $meta_arr['content'];
    }else{
        $result['meta']['title'] = '';
        $result['meta']['description'] = '';
        $result['meta']['keywords'] = '';
        $result['meta']['h1'] = '';
        $result['meta']['content'] = '';
        $result['meta']['label'] = '';
    }
    return $result;

}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
