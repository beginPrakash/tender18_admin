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
    $header_data = mysqli_query($con, "SELECT * FROM `archive_tenders_banner`");
    $header_result = mysqli_num_rows($header_data);
    if ($header_result == 1) {
        while ($row = mysqli_fetch_assoc($header_data)) {
            $result['main']['main_title'] = htmlspecialcode_generator($row['main_title']);
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
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
