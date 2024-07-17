<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

// switch ($endpoint) {
//     case 'getStatesData':
//         $result = get_results($con);
//         break;
//     default:
//         $result = null;
// }
$result = get_results($con);

function get_results($con)
{
    $state_data = mysqli_query($con, "SELECT * FROM `states`");
    $state_result = mysqli_num_rows($state_data);
    if ($state_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($state_data)) {
            $result[$count]['id'] = $row['id'];
            $result[$count]['name'] = $row['name'];
            $result[$count]['state_code'] = $row['state_code'];
            $count++;
        }
        $result = array_values($result);
    } else {
        $result = "No data found";
    }
    //echo'<pre>';print_r($result);exit;
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
