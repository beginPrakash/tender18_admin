<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getAboutTender18Data':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $serviceData = mysqli_query($con, "SELECT * FROM `homepage_about`");
    $serviceResult = mysqli_num_rows($serviceData);
    if ($serviceResult == 1) {
        while ($row = mysqli_fetch_assoc($serviceData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['title1']);
            $result['main']['description'] = htmlspecialcode_generator($row['description1']);
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
