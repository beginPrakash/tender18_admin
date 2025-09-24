<?php

include '../../includes/connection.php';

include '../../includes/functions.php';

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

    case 'getBlogDetailData':

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

    $blog_id = $postData['id'];

    $blog_title = str_replace("-"," ",$blog_id);
    $originalString = urldecode($blog_title);
    
    $user_data = mysqli_query($con, "SELECT * FROM `blogs` WHERE  `title` LIKE '%$originalString%'");



    $user_result = mysqli_num_rows($user_data);



    if ($user_result == 1) {

        while ($row = mysqli_fetch_assoc($user_data)) {

            $image = $row['blog_image'];

            $blog_image = '';

            if (!empty($image)) {

                $blog_image =  ADMIN_URL . 'uploads/images/' . $image;

            }

            $result['main']['title'] = htmlspecialcode_generator($row['title']);

            $result['main']['description'] = htmlspecialcode_generator($row['description']);

            $result['main']['meta_description'] = strip_tags(substr($row['description'], 0, 300));

            $result['main']['blog_image'] = $blog_image;

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

