<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getPaymentData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
{
    // return $con;
    $paymentData = mysqli_query($con, "SELECT * FROM `payment`");
    $paymentResult = mysqli_num_rows($paymentData);
    if ($paymentResult == 1) {
        while ($row = mysqli_fetch_assoc($paymentData)) {
            $result['main']['main_title'] = htmlspecialcode_generator($row['main_title']);
            $result['main']['payment_link'] = $row['payment_link'];
            $result['main']['title'] = htmlspecialcode_generator($row['title']);
            $result['main']['image'] = ADMIN_URL . 'uploads/images/' . $row['image'];
            $result['main']['bank_title'] = htmlspecialcode_generator($row['bank_title']);
            $result['main']['upi_title'] = htmlspecialcode_generator($row['upi_title']);
        }
    } else {
        $result['main'] = "No data found";
    }
    $paymentDetailsData = mysqli_query($con, "SELECT * FROM `payment_bank_details`");
    $paymentDetailsResult = mysqli_num_rows($paymentDetailsData);
    if ($paymentDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($paymentDetailsData)) {
            $result['details'][$count]['bank_name'] = htmlspecialcode_generator($row['bank_name']);
            $result['details'][$count]['acc_no'] = $row['acc_no'];
            $result['details'][$count]['benf_name'] = $row['benf_name'];
            $result['details'][$count]['ifsc_code'] = $row['ifsc_code'];
            $count++;
        }
    } else {
        $result['main'] = "No data found";
    }
    $upiDetailsData = mysqli_query($con, "SELECT * FROM `payment_upi`");
    $upiDetailsResult = mysqli_num_rows($upiDetailsData);
    if ($upiDetailsResult > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($upiDetailsData)) {
            $result['upi'][$count]['title'] = htmlspecialcode_generator($row['title']);
            $result['upi'][$count]['upi_no'] = $row['upi_no'];
            $count++;
        }
    } else {
        $result['details'] = "No data found";
    }

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
