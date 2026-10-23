<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getGeMServicesData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $bannerData = mysqli_query($con, "SELECT * FROM `homepage_gem_services`");
    $bannerResult = mysqli_num_rows($bannerData);
    if ($bannerResult == 1) {
        while ($row = mysqli_fetch_assoc($bannerData)) {
            $result['main']['state_title'] = htmlspecialcode_generator($row['state_title']);
            $result['main']['city_title'] = htmlspecialcode_generator($row['city_title']);
            $result['main']['keyword_title'] = htmlspecialcode_generator($row['keyword_title']);
            $result['main']['gem_title'] = htmlspecialcode_generator($row['gem_title']);
            $result['main']['gem_button_text'] = htmlspecialcode_generator($row['service_gem_button_text']);
            $result['main']['gem_button_link'] = $row['service_gem_button_link'];
        }
    } else {
        $result['main'] = "No data found";
    }
    $state_services_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_state`");
    $state_services_details_result = mysqli_num_rows($state_services_details_data);
    if ($state_services_details_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($state_services_details_data)) {
            $result['state'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['state'][$count]['link'] = $row['link'];
            $count++;
        }
    } else {
        $result['state'] = "No data found";
    }
    $city_services_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_city`");
    $city_services_details_result = mysqli_num_rows($city_services_details_data);
    if ($city_services_details_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($city_services_details_data)) {
            $result['city'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['city'][$count]['link'] = $row['link'];
            $count++;
        }
    } else {
        $result['city'] = "No data found";
    }
    $keyword_services_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_keyword`");
    $keyword_services_details_result = mysqli_num_rows($keyword_services_details_data);
    if ($keyword_services_details_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($keyword_services_details_data)) {
            $result['keyword'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['keyword'][$count]['link'] = $row['link'];
            $count++;
        }
    } else {
        $result['keyword'] = "No data found";
    }
    $gem_services_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_gem`");
    $gem_services_details_result = mysqli_num_rows($gem_services_details_data);
    if ($gem_services_details_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($gem_services_details_data)) {
            $result['services'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $count++;
        }
    } else {
        $result['services'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
