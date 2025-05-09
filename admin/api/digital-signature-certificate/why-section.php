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
    $other_data = mysqli_query($con, "SELECT * FROM `digital_cert_service`");
    $other_result = mysqli_num_rows($other_data);
    if ($other_result == 1) {
        while ($row = mysqli_fetch_assoc($other_data)) {
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
