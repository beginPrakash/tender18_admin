<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getGemServiceOfferData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $gemServiceOfferSectionData = mysqli_query($con, "SELECT * FROM `homepage_gem_service_offer_section`");
    $gemServiceOfferSectionResult = mysqli_num_rows($gemServiceOfferSectionData);
    if ($gemServiceOfferSectionResult == 1) {
        while ($row = mysqli_fetch_assoc($gemServiceOfferSectionData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['description'] = htmlspecialcode_generator($row['description']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $gemServiceOfferData = mysqli_query($con, "SELECT * FROM `homepage_gem_service_offer`");
    $gemServiceOfferResult = mysqli_num_rows($gemServiceOfferData);
    if ($gemServiceOfferResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($gemServiceOfferData)) {
            $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['details'][$count]['description'] = htmlspecialcode_generator($row['description']);
            $result['details'][$count]['image'] = ADMIN_URL . 'uploads/images/gem-service-offer/' . $row['image'];
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
