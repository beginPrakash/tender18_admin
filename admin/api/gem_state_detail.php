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

    case 'getGemStateDetailData':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = get_results($con, $postData);

        } else {

            $result = null;

        }

        break;

    default:

        $result = null;

}



function get_results($con, $postData)

{

    $state_name = $postData['state'];

    $user_data = mysqli_query($con, "SELECT * FROM `gem_state_data` WHERE  `id` = 1");



    $user_result = mysqli_num_rows($user_data);


    if ($user_result == 1) {

        while ($row = mysqli_fetch_assoc($user_data)) {

            $image = $row['state_image'];

            $state_image = '';

            if (!empty($image)) {

                $state_image =  ADMIN_URL . 'uploads/images/' . $image;

            }

            //replace variable to state
            $state_nam = ucfirst($state_name);
            $title = str_replace("(State)",$state_nam,$row['title']);
            $description = str_replace("(State)",$state_nam,$row['description']);
            $meta_title = str_replace("(State)",$state_nam,$row['meta_title']);
            $meta_description = str_replace("(State)",$state_nam,$row['meta_description']);
            $h1 = str_replace("(State)",$state_nam,$row['h1']);
            $result['main']['title'] = htmlspecialcode_generator($title);

            $result['main']['description'] = htmlspecialcode_generator($description);

            $result['main']['meta_title'] = htmlspecialcode_generator($meta_title);

            $result['main']['meta_description'] = htmlspecialcode_generator($meta_description);

            $result['main']['h1'] = htmlspecialcode_generator($h1);

            $result['main']['state_image'] = $state_image;

            $main_title = $row['main_title'];

            //get blog links
            $blog_links = "";

            $title_url = [];
            $title_url1 = [];
            $title_url2 = [];

            $main_title = $row['main_title'];

            $title_url = json_decode($row['title_urls']);

            if(!empty($title_url) && count($title_url) > 0){
                $result['main']['main_title'] = $main_title;
                $result['main']['more_link_url'] = $row['more_link_url'];
                $titlearr = [];
                foreach($title_url as $key => $val){
                
                    $titlearr[$key]['link_title'] = $val->link_title;
                    $titlearr[$key]['link_url'] = $val->link_url;
                }
            
                $result['title_urls'] = $titlearr;

            }else{
                $result['title_urls'] = [];
            }

            $main_title1 = $row['main_title1'];

            $title_url1 = json_decode($row['title_urls1']);

            if(!empty($title_url1) && count($title_url1) > 0){
                $result['main']['main_title1'] = $main_title1;
                $result['main']['more_link_url1'] = $row['more_link_url1'];
                $titlearr1 = [];
                foreach($title_url1 as $key => $val){
                
                    $titlearr1[$key]['link_title'] = $val->link_title;
                    $titlearr1[$key]['link_url'] = $val->link_url;
                }
            
                $result['title_urls1'] = $titlearr1;

            }else{
                $result['title_urls1'] = [];
            }
            $main_title2 = $row['main_title2'];

            $title_url2 = json_decode($row['title_urls2']);

            if(!empty($title_url2) && count($title_url2) > 0){
                $result['main']['main_title2'] = $main_title2;
                $result['main']['more_link_url2'] = $row['more_link_url2'];
                $titlearr2 = [];
                foreach($title_url2 as $key => $val){
                

                    $titlearr2[$key]['link_title'] = $val->link_title;
                    $titlearr2[$key]['link_url'] = $val->link_url;
                }
            
                $result['title_urls2'] = $titlearr2;

            }else{
                $result['title_urls2'] = [];
            }

        }

    } else {

        $result['main'] = "No data found";
        $result['title_urls'] = "No data found";
        $result['title_urls1'] = "No data found";
        $result['title_urls2'] = "No data found";
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

