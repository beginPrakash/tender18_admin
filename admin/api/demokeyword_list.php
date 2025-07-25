<?php

include '../includes/connection.php';

include '../includes/functions.php';

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

    case 'getDemoKeywordList':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = post_users($con, $postData);

        } else {

            $result['status'] = "error";

            $result['message'] = "Invalid method";

        }

        break;

    default:

        $result['status'] = "error";

        $result['message'] = "Invalid endpoint";

}



function post_users($con, $postData)

{

    // return $con;

    $key_like = $postData['q'];

    $select = mysqli_query($con, "SELECT * FROM `keywords` WHERE name like '$key_like%' order by name ASC");

    $result_data = mysqli_num_rows($select);

    if ($result_data > 0) {

        $result['status'] = "success";

        while ($row = mysqli_fetch_assoc($select)) {

            $result['agencies'][] = $row['name'];

        }

    } else {

        $result['status'] = "error";

        $result['message'] = "Keywords not found";

    }

    return $result;

}



echo json_encode($result,JSON_PARTIAL_OUTPUT_ON_ERROR);

die();

