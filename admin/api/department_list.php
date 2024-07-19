<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getDepartmentList':
        $result = get_results($con);
        break;
    default:
        $result = null;
}


function get_results($con)
{
    $dept_data = mysqli_query($con, "SELECT * FROM `tenders_archive` where department != '' group By department");
    $dept_result = mysqli_num_rows($dept_data);
    if ($dept_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($dept_data)) {
            $result[$count]['name'] = $row['department'];
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
