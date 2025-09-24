<?php

include '../includes/connection.php';

include '../includes/functions.php';

header("Content-Type: application/json");

header('Access-Control-Allow-Origin: *');



$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';



// switch ($endpoint) {

//     case 'getKeywordList':

//         $result = get_results($con);

//         break;

//     default:

//         $result = null;

// }

$result = get_results($con);



function get_results($con)

{

    $key_like = $_GET['q'];

    $agency_data = mysqli_query($con, "SELECT * FROM `keywords` WHERE name like '$key_like%' order by name ASC");

    $state_result = mysqli_num_rows($agency_data);

    $agency_data1 = mysqli_query($con, "DELETE t1
FROM keywords t1
JOIN (
   SELECT name, MIN(id) as min_id
   FROM keywords
   GROUP BY name
   HAVING COUNT(*) > 1
) t2 ON t1.name = t2.name AND t1.id = t2.min_id");


    if ($state_result > 0) {

        $count = 1;

        while ($row = mysqli_fetch_assoc($agency_data)) {

            $result[$count]['id'] = $row['id'];

            $result[$count]['name'] = trim($row['name']);

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

