<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getWhyData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $whyData = mysqli_query($con, "SELECT * FROM `homepage_why_section`");
    $whyResult = mysqli_num_rows($whyData);
    if ($whyResult == 1) {
        while ($row = mysqli_fetch_assoc($whyData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['description'] = htmlspecialcode_generator($row['description']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $whyDetailsData = mysqli_query($con, "SELECT * FROM `homepage_why_details`");
    $whyDetailsResult = mysqli_num_rows($whyDetailsData);
    if ($whyDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($whyDetailsData)) {
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
