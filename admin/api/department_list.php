<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

// switch ($endpoint) {
//     case 'getDepartmentData':
//         $result = get_results($con);
//         break;
//     default:
//         $result = null;
// }

$result = get_results($con);
function get_results($con)
{
    if(!empty($_GET['name'])){
        $dept_like = $_GET['name'];
        $dept_data = mysqli_query($con, "SELECT * FROM `departments` where name like '$dept_like%' Group By name");
    }else{
        $dept_data = mysqli_query($con, "SELECT * FROM `departments` Group By name");
    }
    
    $dept_result = mysqli_num_rows($dept_data);
    if ($dept_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($dept_data)) {
            $result[$count]['tender_archieve_id'] = $row['id'];
            $result[$count]['department'] = $row['name'];
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
