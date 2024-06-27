<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getPartnersData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $partnersData = mysqli_query($con, "SELECT * FROM `partners`");
    $partnersResult = mysqli_num_rows($partnersData);
    if ($partnersResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($partnersData)) {
            $result['main'][$count]['image'] = ADMIN_URL . 'uploads/images/' . $row['image'];
            $count++;
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
