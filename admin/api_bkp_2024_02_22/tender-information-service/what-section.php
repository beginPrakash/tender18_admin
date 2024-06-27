<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getWhatData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $other_data = mysqli_query($con, "SELECT * FROM `tender_information_other`");
    $other_result = mysqli_num_rows($other_data);
    if ($other_result == 1) {
        while ($row = mysqli_fetch_assoc($other_data)) {
            $result['main']['title'] = htmlspecialcode_generator($row['get_title']);
            $result['main']['description'] = htmlspecialcode_generator($row['get_description']);
            $result['main']['subtitle'] = htmlspecialcode_generator($row['get_subtitle']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $support_experts_data = mysqli_query($con, "SELECT * FROM `tender_information_get_details`");
    $support_experts_result = mysqli_num_rows($support_experts_data);
    if ($support_experts_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($support_experts_data)) {
            $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['details'][$count]['icon'] = htmlspecialcode_generator($row['icon']);
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
