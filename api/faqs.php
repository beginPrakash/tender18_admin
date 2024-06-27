<?php

include '../includes/connection.php';

include '../includes/functions.php';

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

    $header_data = mysqli_query($con, "SELECT * FROM `faq_page`");

    $header_result = mysqli_num_rows($header_data);

    if ($header_result == 1) {

        while ($row = mysqli_fetch_assoc($header_data)) {

            $result['main']['title'] = htmlspecialcode_generator($row['title']);

        }

    } else {

        $result['main'] = "No data found";

    }

    $menus_data = mysqli_query($con, "SELECT * FROM `faq_page_details`");

    $menus_result = mysqli_num_rows($menus_data);

    if ($menus_result > 0) {

        $count = 1;

        while ($row = mysqli_fetch_assoc($menus_data)) {

            $result['details'][$count]['title'] = htmlspecialcode_generator($row['title']);

            $result['details'][$count]['description'] = htmlspecialcode_generator($row['description']);

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

