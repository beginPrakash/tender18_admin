<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$result = get_results($con);

function get_results($con)
{

    $sample_tender_data = mysqli_query($con, "SELECT * FROM `sample_tender_details`");
    $tender_details_result = mysqli_num_rows($sample_tender_data);
    if ($tender_details_result > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($sample_tender_data)) {
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
