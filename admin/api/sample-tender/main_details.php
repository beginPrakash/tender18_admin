<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$result = get_results($con);

function get_results($con)
{
    // return $con;
    $samepletenderdata = mysqli_query($con, "SELECT * FROM `sample_tender`");
    $tenderResult = mysqli_num_rows($samepletenderdata);
    if ($tenderResult == 1) {
        while ($row = mysqli_fetch_assoc($samepletenderdata)) {
            $result['main']['main_title'] = htmlspecialcode_generator($row['main_title']);
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['sub_title'] = htmlspecialcode_generator($row['sub_title']);
        }
    } else {
        $result['main'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
