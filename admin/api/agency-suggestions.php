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
    case 'getAgencySuggestionsData':
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
    $city_like = $postData['agency_like'];
    $select = mysqli_query($con, "SELECT * FROM tender_agencies WHERE agency_name like '%" . $city_like . "%' group by agency_name");
    $result_data = mysqli_num_rows($select);
    if ($result_data > 0) {
        $result['status'] = "success";
        while ($row = mysqli_fetch_assoc($select)) {
            $result['agencies'][] = $row['agency_name'];
        }
    } else {
        $result['status'] = "error";
        $result['message'] = "Agency not found";
    }
    return $result;
}

echo json_encode($result);
die();
