<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getLiveTendersData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $serviceData = mysqli_query($con, "SELECT * FROM `homepage_tenders`");
    $serviceResult = mysqli_num_rows($serviceData);
    if ($serviceResult == 1) {
        while ($row = mysqli_fetch_assoc($serviceData)) {
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['description'] = htmlspecialcode_generator($row['description']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $header_data = mysqli_query($con, "SELECT * FROM `header`");
    $header_result = mysqli_num_rows($header_data);
    if ($header_result == 1) {
        while ($row = mysqli_fetch_assoc($header_data)) {
            $result['main']['whatsapp_num'] = $row['whatsapp_num'];
        }
    } else {
        $result['main']['whatsapp_num'] = null;
    }
    // $serviceDetailsData = mysqli_query($con, "SELECT * FROM `tenders_posts` WHERE DATE(opening_date) >= DATE(NOW()) AND DATE(due_date) >= DATE(NOW())");
    // $serviceDetailsResult = mysqli_num_rows($serviceDetailsData);
    // if ($serviceDetailsResult > 0) {
    //     $count = 1;
    //     while ($row = mysqli_fetch_assoc($serviceDetailsData)) {
    //         $result['details'][$count]['ref_no'] = $row['ref_no'];
    //         $result['details'][$count]['state'] = $row['state'];
    //         $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);
    //         $result['details'][$count]['agency_type'] = htmlspecialcode_generator($row['agency_type']);
    //         $result['details'][$count]['tender_value'] = $row['tender_value'];
    //         $result['details'][$count]['due_date'] = date('M d, Y', strtotime($row['due_date']));
    //         $count++;
    //     }
    // } else {
    //     $result['details'] = "No data found";
    // }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
