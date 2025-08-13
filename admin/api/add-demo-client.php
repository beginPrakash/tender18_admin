<?php
include '../includes/connection.php';
include '../includes/functions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/vendor/autoload.php';
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

    case 'saveDemoClientData':

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
    $name = mysqli_real_escape_string($con, $postData['name']);
    $company_name = mysqli_real_escape_string($con, $postData['company_name']);
    $email_id = mysqli_real_escape_string($con, $postData['email']);
    $phone_no = mysqli_real_escape_string($con, $postData['mobile']);
    $state = mysqli_real_escape_string($con, $postData['state']);
    $keywords = mysqli_real_escape_string($con, $postData['keywords']);
    $pass = $postData['password'];

    $banner_data = mysqli_query($con, "SELECT * FROM `demo_client` where email_id='" . $email_id . "'");
    $banner_result = mysqli_num_rows($banner_data);

    $mai_data = mysqli_query($con, "SELECT * FROM `smtp_management` where id = 1");
    $tend_result = mysqli_num_rows($mai_data);
    $host = 'smtp.gmail.com';
    $user_name = 'sales@tender18mail.in';
    $password = 'zgtm dlbm nqal jwpm';
    $port = '587';
    $from_email = 'sales@tender18mail.in';
    $from_name = 'Tender 18';
    if ($tend_result == 1) {
        while ($row = mysqli_fetch_assoc($mai_data)) {
            $host = $row['host'];
            $user_name = $row['user_name'];
            $password = $row['password'];
            $port = $row['port'];
            $from_email = $row['from_email'];
            $from_name = $row['from_name'];
        }
    }

    $mail_name = 'Tender18';
    $to = ADMIN_EMAIL;
    $message = 'Hello Admin, below are the Sample Tender form details...<br><br>';
    $message .= '<b>Name :</b> ' . htmlspecialcode_generator($name) . '<br>';
    $message .= '<b>Company Name :</b> ' . htmlspecialcode_generator($company_name) . '<br>';
    $message .= '<b>Email :</b> ' . htmlspecialcode_generator($email_id) . '<br>';
    $message .= '<b>Mobile :</b> ' . htmlspecialcode_generator($phone_no) . '<br>';
    $message .= '<b>State :</b> ' . htmlspecialcode_generator($state) . '<br>';
    $message .= '<b>Keywords :</b> ' . htmlspecialcode_generator($keywords) . '<br>';
    $message .= '';
    $subject = 'Sample Tender';

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->SMTPDebug = 0; // Disable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = $host; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $user_name; // SMTP username
        $mail->Password = $password; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port = $port; // TCP port to connect to

        // Recipients
        $mail->setFrom($from_email, $from_name);
      
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
     //print_r($mail);exit;
        // Send the email
        if ($banner_result == 0) {
            $mail->send();
        }
        //echo 'Email sent successfully';
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    
    if ($banner_result != 0) {
        $result['message'] = "Email already exists";
    }else{
        $q1 = "INSERT INTO demo_client(`name`,`company_name`, `email_id`, `phone_no`, `state`,`keywords`,`password`) VALUES ('$name', '$company_name', '$email_id', '$phone_no', '$state','$keywords','$pass')";
        mysqli_query($con, $q1);
        $result['message'] = "Data Saved Successfully";

    }
    
    

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => "success", "data" => $result));
}
die();
