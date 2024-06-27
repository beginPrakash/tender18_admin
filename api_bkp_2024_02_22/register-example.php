<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

if (empty($endpoint)) {
    $endpoint = isset($_POST['endpoint']) ? $_POST['endpoint'] : '';
}

switch ($endpoint) {
    case 'getData':
        $result = get_users();
        break;
    case 'postData':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = post_users($con);
        } else {
            $result = null;
        }
        break;
    default:
        $result = null;
}

function get_users()
{
    return "GET";
}

function post_users($con)
{
    return $con;
    // $username = $_POST['username'];
    // $fullname = $_POST['fullname'];
    // $company_name = $_POST['company_name'];
    // $email = $_POST['email'];
    // $pass = getRandomCode();
    // $user_role = $_POST['user_role'];
    // $unique_code = getRandomCode();
    // $fetch = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE  users_name='$username' OR users_email='$email'"));
    // if ($fetch < 1) {
    //     $q = "INSERT INTO users(users_name, users_email ,users_password, user_role, user_unique_id) VALUES ('$username', '$email', '$pass', '$user_role', '$unique_code')";
    //     $sql = mysqli_query($con, $q);
    //     if ($sql) {
    //         $_SESSION['success'] = 'Registration successfully.';
    //     } else {
    //         $_SESSION['error'] = 'Something went wrong.';
    //     }
    // } else {
    //     $_SESSION['error'] = 'Username or Email already exists.';
    // }
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
