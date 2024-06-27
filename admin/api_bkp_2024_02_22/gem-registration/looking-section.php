<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getLookingData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $bannerData = mysqli_query($con, "SELECT * FROM `gem_reg_looking_section`");
    $bannerResult = mysqli_num_rows($bannerData);
    if ($bannerResult == 1) {
        while ($row = mysqli_fetch_assoc($bannerData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['description'] = htmlspecialcode_generator($row['description']);
            $result['main']['image'] = ADMIN_URL . 'uploads/images/' . $row['image'];
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
