<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getTestimonialsData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $testimonialsData = mysqli_query($con, "SELECT * FROM `testimonials`");
    $testimonialsResult = mysqli_num_rows($testimonialsData);
    if ($testimonialsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($testimonialsData)) {
            $result['main'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['main'][$count]['name'] = htmlspecialcode_generator($row['name']);
            $result['main'][$count]['description'] = htmlspecialcode_generator($row['description']);
            $count++;
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
