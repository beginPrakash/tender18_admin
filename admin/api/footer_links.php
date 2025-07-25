<?php

include '../includes/connection.php';

include '../includes/functions.php';

header("Content-Type: application/json");

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

header('Access-Control-Allow-Headers: Content-Type, Authorization');

header('Access-Control-Allow-Credentials: true');


$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getFooterLinksData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}



function get_results($con)

{

    $user_data = mysqli_query($con, "SELECT * FROM `footer_links`");



    $user_result = mysqli_num_rows($user_data);


    if ($user_result > 0) {

        $count = 1;

        while ($row = mysqli_fetch_assoc($user_data)) {

            $title_url1 = json_decode($row['title_url']);
            if(!empty($title_url1)){
                $result[$count]['main_title'] = $row['main_title'];

                $result[$count]['more_link_url'] = $row['more_link_url'];

                $titlearr1 = [];
                    foreach($title_url1 as $key => $val){
                    
                        $titlearr1[$key]['link_title'] = $val->link_title;
                        $titlearr1[$key]['link_url'] = $val->link_url;
                    }
                
                $result[$count]['title_url'] = $titlearr1;
                $count++;
            }
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

