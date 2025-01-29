<?php

include '../includes/connection.php';

include '../includes/functions.php';

header("Content-Type: application/json");

header('Access-Control-Allow-Origin: *');



$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';



// switch ($endpoint) {

//     case 'getAgencyList':

//         $result = get_results($con);

//         break;

//     default:

//         $result = null;

// }



$result = get_results($con);

function get_results($con)

{

    $agency_like = $_GET['q'] ?? '';

    if(!empty($agency_like)):

        $agency_data = mysqli_query($con, "SELECT * FROM `tender_agencies` WHERE agency_name like '$agency_like%' group By agency_name order By agency_name ASC");

    else:

        $agency_data = mysqli_query($con, "SELECT * FROM `tender_agencies` group By agency_name order By agency_name ASC");

    endif;

    

    $state_result = mysqli_num_rows($agency_data);

    if ($state_result > 0) {

        $count = 1;

        while ($row = mysqli_fetch_assoc($agency_data)) {

            $result[$count]['id'] = $row['id'];

            $result[$count]['agency_name'] = $row['agency_name'];

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

