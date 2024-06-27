<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getWhomData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $whomData = mysqli_query($con, "SELECT * FROM `tender_support_other`");
    $whomResult = mysqli_num_rows($whomData);
    if ($whomResult == 1) {
        while ($row = mysqli_fetch_assoc($whomData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['service_title']);
            $result['main']['description'] = htmlspecialcode_generator($row['service_description']);
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
