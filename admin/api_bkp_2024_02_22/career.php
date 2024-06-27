<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getCareerData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $header_data = mysqli_query($con, "SELECT * FROM `career_page`");
    $header_result = mysqli_num_rows($header_data);
    if ($header_result == 1) {
        while ($row = mysqli_fetch_assoc($header_data)) {
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['work_title'] = htmlspecialcode_generator($row['work_title']);
            $result['main']['work_description'] = htmlspecialcode_generator($row['work_description']);
            $result['main']['about_title'] = htmlspecialcode_generator($row['about_title']);
            $result['main']['about_description'] = htmlspecialcode_generator($row['about_description']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $menus_data = mysqli_query($con, "SELECT * FROM `career_posts`");
    $menus_result = mysqli_num_rows($menus_data);
    if ($menus_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($menus_data)) {
            $result['jobs'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['jobs'][$count]['job_status'] = htmlspecialcode_generator($row['job_status']);
            $result['jobs'][$count]['description'] = htmlspecialcode_generator($row['description']);
            $result['jobs'][$count]['job_profile'] = htmlspecialcode_generator($row['job_profile']);
            $result['jobs'][$count]['experience'] = htmlspecialcode_generator($row['experience']);
            $result['jobs'][$count]['anual_package'] = htmlspecialcode_generator($row['anual_package']);
            $result['jobs'][$count]['location'] = htmlspecialcode_generator($row['location']);
            $count++;
        }
    } else {
        $result['jobs'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
