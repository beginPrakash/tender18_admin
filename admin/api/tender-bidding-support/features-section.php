<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getFeaturesData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $featuresData = mysqli_query($con, "SELECT * FROM `tender_support_banner`");
    $featuresResult = mysqli_num_rows($featuresData);
    if ($featuresResult == 1) {
        while ($row = mysqli_fetch_assoc($featuresData)) {
            $result['main']['below_title'] = htmlspecialcode_generator($row['below_title']);
            $result['main']['below_image'] = ADMIN_URL . 'uploads/images/' . $row['below_image'];
        }
    } else {
        $result['main'] = "No data found";
    }
    $featuresDetailsData = mysqli_query($con, "SELECT * FROM `tender_support_features`");
    $featuresDetailsResult = mysqli_num_rows($featuresDetailsData);
    if ($featuresDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($featuresDetailsData)) {
            if($count <= 4){
                $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
            }else{
                $result['last_data'][$count]['title'] = htmlspecialcode_generator($row['title']);
            }
                $count++;
        }
    } else {
        $result['details'] = "No data found";
        $result['last_data'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
