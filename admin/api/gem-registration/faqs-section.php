<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getFAQsData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $bannerDetailsData = mysqli_query($con, "SELECT * FROM `gem_reg_faq_section`");
    $bannerDetailsResult = mysqli_num_rows($bannerDetailsData);
    if ($bannerDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($bannerDetailsData)) {
            $result['faqs'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['faqs'][$count]['description'] = htmlspecialcode_generator($row['description']);
            $count++;
        }
    } else {
        $result['faqs'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
