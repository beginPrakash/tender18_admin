<?php

include '../includes/connection.php';

include '../includes/functions.php';

header("Content-Type: application/json");

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

header('Access-Control-Allow-Headers: Content-Type, Authorization');

header('Access-Control-Allow-Credentials: true');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

$result = get_results($con);
function get_results($con)

{


    $select = mysqli_query($con, "SELECT * FROM tender_zipcodes where city != '' group by city order By city ASC");

    $result_data = mysqli_num_rows($select);

    if ($result_data > 0) {

        $count = 1;

        while ($row = mysqli_fetch_assoc($select)) {

            $result[$count]['id'] = $row['id'];

            $result[$count]['city_name'] = $row['city'];
            $count++;
        }
        $result = array_values($result);


    } else {

        $result = "No data found";

    }

    return $result;

}


if ($result === null) {

    echo json_encode(array("status" => "error"));

} else {
    echo json_encode(array("status" => " success", "data" => $result),JSON_PARTIAL_OUTPUT_ON_ERROR);

}
die();

