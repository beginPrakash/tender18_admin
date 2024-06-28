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
    case 'loginData':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = post_users($con, $postData);
        } else {
            $result['status'] = "error";
            $result['message'] = "Invalid method";
        }
        break;
    default:
        $result['status'] = "error";
        $result['message'] = "Invalid endpoint";
}

function post_users($con, $postData)
{
    // return $con;
    $name = $postData['name'];
    $password = md5($postData['password']);
    $select = mysqli_query($con, "SELECT * FROM `users` WHERE  users_name='$name' OR users_email='$name'");
    $result_data = mysqli_num_rows($select);
    $pass = mysqli_fetch_assoc($select);
    $role = ['user'];
    $user_status = ['Expired'];
    if ($result_data == 1 && $pass['users_password'] == $password) {
        if (in_array($pass['user_role'], $role)) {
            if (!in_array($pass['status'], $user_status)) {
                $fetch = mysqli_query($con, "SELECT * FROM users WHERE  users_name='$name' OR users_email='$name'");
                while ($row = mysqli_fetch_assoc($fetch)) {
                    $user_unique_id = $row['user_unique_id'];
                    $user_name = $row['users_name'];
                    $user_email = $row['users_email'];
                }
                $token = md5(random_bytes(32));
                $result['status'] = "success";
                $result['message'] = "Logged in successfully";
                $result['user_id'] = $user_unique_id;
                $result['user_name'] = $user_name;
                $result['user_email'] = $user_email;
                $result['token'] = $token;
                mysqli_query($con, "UPDATE `users` SET `token`='$token' WHERE `user_unique_id`='$user_unique_id'");
            } else {
                $result['status'] = "error";
                $result['message'] = "Membership expired";
            }
        } else {
            $result['status'] = "error";
            $result['message'] = "Invalid credentials";
        }
    } else {
        $result['status'] = "error";
        $result['message'] = "User not exists";
    }
    return $result;
}

echo json_encode($result);
die();
