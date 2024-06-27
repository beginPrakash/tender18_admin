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
    $whyData = mysqli_query($con, "SELECT * FROM `tender_support_other`");
    $whyResult = mysqli_num_rows($whyData);
    if ($whyResult == 1) {
        while ($row = mysqli_fetch_assoc($whyData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['why_title']);
            $result['main']['description'] = htmlspecialcode_generator($row['why_description']);
        }
    } else {
        $result['main'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
