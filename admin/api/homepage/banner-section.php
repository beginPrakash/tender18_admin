<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getBannerData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $bannerData = mysqli_query($con, "SELECT * FROM `homepage_banner`");
    $bannerResult = mysqli_num_rows($bannerData);
    if ($bannerResult == 1) {
        while ($row = mysqli_fetch_assoc($bannerData)) {
            if(!empty($row['mobile_image'])){
                $mobile_image = $row['mobile_image'];
            }else{
                $mobile_image = $row['image'];
            }
            $result['main']['title'] = $row['title'];
            $result['main']['description'] = $row['description'];
            $result['main']['image'] = ADMIN_URL . 'uploads/images/' . $row['image'];
            $result['main']['mobile_image'] = ADMIN_URL . 'uploads/images/' . $mobile_image;
        }
    } else {
        $result['main'] = "No data found";
    }
    $bannerDetailsData = mysqli_query($con, "SELECT * FROM `homepage_banner_details`");
    $bannerDetailsResult = mysqli_num_rows($bannerDetailsData);
    if ($bannerDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($bannerDetailsData)) {
            $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['details'][$count]['sub_title'] = htmlspecialcode_generator($row['sub_title']);
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
