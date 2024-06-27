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
    $why_data = mysqli_query($con, "SELECT * FROM `about_us_why`");
    $why_result = mysqli_num_rows($why_data);
    if ($why_result == 1) {
        while ($row = mysqli_fetch_assoc($why_data)) {
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['subtitle'] = htmlspecialcode_generator($row['description']);
            $result['main']['image'] = ADMIN_URL . 'uploads/images/' . $row['image'];
        }
    } else {
        $result['main'] = "No data found";
    }
    $why_details_data = mysqli_query($con, "SELECT * FROM `about_us_why_details`");
    $why_details_result = mysqli_num_rows($why_details_data);
    if ($why_details_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($why_details_data)) {
            $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['details'][$count]['description'] = htmlspecialcode_generator($row['description']);
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
