<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getServiceData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $serviceData = mysqli_query($con, "SELECT * FROM `homepage_services`");
    $serviceResult = mysqli_num_rows($serviceData);
    if ($serviceResult == 1) {
        while ($row = mysqli_fetch_assoc($serviceData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['description'] = htmlspecialcode_generator($row['description']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $serviceDetailsData = mysqli_query($con, "SELECT * FROM `homepage_services_details`");
    $serviceDetailsResult = mysqli_num_rows($serviceDetailsData);
    if ($serviceDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($serviceDetailsData)) {
            $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['details'][$count]['description'] = htmlspecialcode_generator($row['description']);
            $result['details'][$count]['link'] = $row['link'];
            $result['details'][$count]['image'] = ADMIN_URL . 'uploads/images/' . $row['image'];
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
