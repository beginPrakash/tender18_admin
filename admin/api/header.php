<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getHeaderData':
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
            $result['main']['desktop_logo'] = ADMIN_URL . 'uploads/images/' . $row['desktop_logo'];
            $result['main']['mobile_logo'] = ADMIN_URL . 'uploads/images/' . $row['mobile_logo'];
            $result['main']['button_link'] = $row['button_link'];
            $result['main']['button_text'] = htmlspecialcode_generator($row['button_text']);
            $result['main']['button_link1'] = $row['button_link1'];
            $result['main']['button_text1'] = htmlspecialcode_generator($row['button_text1']);
            $result['main']['button_link2'] = $row['button_link2'];
            $result['main']['button_text2'] = htmlspecialcode_generator($row['button_text2']);
            $result['main']['whatsapp_num'] = $row['whatsapp_num'];
        }
    } else {
        $result['main'] = "No data found";
    }
    $menus_data = mysqli_query($con, "SELECT * FROM `menus` where `location`='header' order by id ASC limit 5");
    $menus_result = mysqli_num_rows($menus_data);
    if ($menus_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($menus_data)) {
            $result['menu'][$count]['menu_title'] = htmlspecialcode_generator($row['menu_title']);
            $result['menu'][$count]['menu_link'] = $row['menu_link'];
            $count++;
        }
    } else {
        $result['menu'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
