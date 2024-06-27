<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getHowData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $bannerData = mysqli_query($con, "SELECT * FROM `gem_reg_banner_section`");
    $bannerResult = mysqli_num_rows($bannerData);
    if ($bannerResult == 1) {
        while ($row = mysqli_fetch_assoc($bannerData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['get_title']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $bannerDetailsData = mysqli_query($con, "SELECT * FROM `gem_reg_get_section`");
    $bannerDetailsResult = mysqli_num_rows($bannerDetailsData);
    if ($bannerDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($bannerDetailsData)) {
            $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
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
