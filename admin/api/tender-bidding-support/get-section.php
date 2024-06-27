<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getGetData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $getData = mysqli_query($con, "SELECT * FROM `tender_support_other`");
    $getResult = mysqli_num_rows($getData);
    if ($getResult == 1) {
        while ($row = mysqli_fetch_assoc($getData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['get_title']);
            $result['main']['description'] = htmlspecialcode_generator($row['get_description']);
            $result['main']['subtitle'] = htmlspecialcode_generator($row['get_subtitle']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $getDetailsData = mysqli_query($con, "SELECT * FROM `tender_support_experts`");
    $getDetailsResult = mysqli_num_rows($getDetailsData);
    if ($getDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($getDetailsData)) {
            $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $count++;
        }
    } else {
        $result['details'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
