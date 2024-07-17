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
    case 'getCityData':
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
    $city_like = $postData['q'];
    $select = mysqli_query($con, "SELECT * FROM tender_zipcodes WHERE city like '%" . $city_like . "%' group by city");
    $result_data = mysqli_num_rows($select);
    if ($result_data > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($select)) {
            $s_code = $row['state'];
            $state_data = mysqli_query($con, "SELECT * FROM `states` WHERE  `state_code`='$s_code'");

            $state_result = mysqli_num_rows($state_data);

            if ($state_result == 1) {
                while ($rows = mysqli_fetch_assoc($state_data)) {
                    $state_id = $rows['id'];
                    $state_title = $rows['name'];
                }
            }
            $result[$count]['id'] = $row['id'];
            $result[$count]['title'] = $row['city'];
            $result[$count]['state']['id'] = $state_id;
            $result[$count]['state']['title'] = $state_title;
            $result[$count]['state']['state_code'] = $row['state'];
            $count++;
        }
        $result = array_values($result);
    } else {
        $result['status'] = "error";
        $result['message'] = "City not found";
    }
    return $result;

}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
