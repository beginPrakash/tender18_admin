<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getFeatureData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $digital_cert_data = mysqli_query($con, "SELECT * FROM `digital_cert_banner`");
    $digital_cert_result = mysqli_num_rows($digital_cert_data);
    if ($digital_cert_result == 1) {
        while ($row = mysqli_fetch_assoc($digital_cert_data)) {
            $result['main']['title'] = htmlspecialcode_generator($row['feature_title']);
            $result['main']['image'] = ADMIN_URL . 'uploads/images/' . $row['feature_image'];
        }
    } else {
        $result['main'] = "No data found";
    }
    $digital_feature_data = mysqli_query($con, "SELECT * FROM `digital_cert_feature`");
    $digital_feature_result = mysqli_num_rows($digital_feature_data);
    if ($digital_feature_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($digital_feature_data)) {
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
