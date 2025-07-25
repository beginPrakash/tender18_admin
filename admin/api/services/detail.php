<?php

include '../../includes/connection.php';

include '../../includes/functions.php';

header("Content-Type: application/json");

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

header('Access-Control-Allow-Headers: Content-Type, Authorization');

header('Access-Control-Allow-Credentials: true');



// Get the raw POST data

$rawData = file_get_contents("php://input");

// Decode the JSON data

$postData = json_decode($rawData, true);



$endpoint = isset($postData['endpoint']) ? $postData['endpoint'] : '';



switch ($endpoint) {

    case 'getServiceDetailData':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = get_results($con, $postData);

        } else {

            $result = null;

        }

        break;

    default:

        $result = null;

}



function get_results($con, $postData)

{

    $service_id = $postData['id'];

    $service_title = str_replace("-"," ",$service_id);
    $originalString = urldecode($service_title);
    
    $user_data = mysqli_query($con, "SELECT * FROM `services` WHERE  `title` LIKE '%$originalString%'");



    $user_result = mysqli_num_rows($user_data);



    if ($user_result == 1) {

        while ($row = mysqli_fetch_assoc($user_data)) {

            $image = $row['service_image'];

            $service_image = '';

            if (!empty($image)) {

                $service_image =  ADMIN_URL . 'uploads/images/' . $image;

            }

            $result['main']['title'] = htmlspecialcode_generator($row['title']);

            $result['main']['description'] = htmlspecialcode_generator($row['description']);

            $result['main']['service_image'] = $service_image;

            $result['main']['h1'] = $row['h1'];

            $result['main']['meta_title'] = $row['meta_title'];

            $result['main']['meta_description'] = strip_tags(substr($row['description'], 0, 150));

        }

    } else {

        $result['main'] = "No data found";
    }



    return $result;

}



if ($result === null) {

    echo json_encode(array("status" => "error"));

} else {
    // Ensure UTF-8 encoding
    function utf8ize($data) {
        if (is_array($data)) {
            return array_map('utf8ize', $data);
        } elseif (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        }
        return $data;
    }
    
    $withque_data = utf8ize($result);
    
    function remove_question_marks($data) {
        if (is_array($data)) {
            return array_map('remove_question_marks', $data);
        } elseif (is_string($data)) {
            return str_replace('?', ' ', $data); // Remove "?"
        }
        return $data;
    }
    
    $data = remove_question_marks($withque_data);
    echo json_encode(array("status" => " success", "data" => $data));

}

die();

