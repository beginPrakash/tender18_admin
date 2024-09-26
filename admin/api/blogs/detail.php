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
    $user_data = mysqli_query($con, "SELECT * FROM `blogs` WHERE  `title` LIKE '%$blog_title%'");

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
            $result['main']['blog_image'] = $blog_image;
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
