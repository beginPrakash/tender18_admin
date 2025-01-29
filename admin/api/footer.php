<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getFooterData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $header_data = mysqli_query($con, "SELECT * FROM `header`");
    $header_result = mysqli_num_rows($header_data);
    if ($header_result == 1) {
        while ($row = mysqli_fetch_assoc($header_data)) {
            $result['main']['whatsapp_num'] = $row['whatsapp_num'];
        }
    }
    $footerData = mysqli_query($con, "SELECT * FROM `footer`");
    $footerResult = mysqli_num_rows($footerData);
    if ($footerResult == 1) {
        while ($row = mysqli_fetch_assoc($footerData)) {
            $result['main']['copyright_text'] = htmlspecialcode_generator($row['copyright_text']);
            $result['main']['quick_menu_title'] = htmlspecialcode_generator($row['quick_menu_title']);
            $result['main']['tender_menu_title'] = htmlspecialcode_generator($row['tender_menu_title']);
            $result['main']['contact_menu_title'] = htmlspecialcode_generator($row['contact_menu_title']);
            $result['main']['terms_link'] = $row['terms_link'];
            $result['main']['terms_text'] = htmlspecialcode_generator($row['terms_text']);
            $result['main']['first_email'] = $row['first_email'];
            $result['main']['second_email'] = $row['second_email'];
            $result['main']['address'] = htmlspecialcode_generator($row['address']);
            $result['main']['contact_no'] = $row['contact_no'];
            $result['main']['facebook_link'] = $row['facebook_link'];
            $result['main']['twitter_link'] = $row['twitter_link'];
            $result['main']['linked_link'] = $row['linked_link'];
            $result['main']['youtube_link'] = $row['youtube_link'];
            $result['main']['instagram_link'] = $row['instagram_link'];
        }
    } else {
        $result['main'] = "No data found";
    }
    $menus_data = mysqli_query($con, "SELECT * FROM `menus` where `location`='quick_links'");
    $menus_result = mysqli_num_rows($menus_data);
    if ($menus_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($menus_data)) {
            $result['quick_links'][$count]['menu_title'] = htmlspecialcode_generator($row['menu_title']);
            $result['quick_links'][$count]['menu_link'] = $row['menu_link'];
            $count++;
        }
    } else {
        $result['quick_links'] = "No data found";
    }
    $menus_data = mysqli_query($con, "SELECT * FROM `menus` where `location`='tenders_by_product'");
    $menus_result = mysqli_num_rows($menus_data);
    if ($menus_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($menus_data)) {
            $result['tenders_by_product'][$count]['menu_title'] = htmlspecialcode_generator($row['menu_title']);
            $result['tenders_by_product'][$count]['menu_link'] = $row['menu_link'];
            $count++;
        }
    } else {
        $result['tenders_by_product'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
