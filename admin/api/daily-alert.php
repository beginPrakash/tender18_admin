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
    case 'saveDailyAlertData':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = daily_alert($con, $postData);
        } else {
            $result['message'] = "Invalid method";
        }
        break;
    default:
        $result = null;
}

function daily_alert($con, $postData)
{
    $name = mysqli_real_escape_string($con, $postData['name']);
    $company_name = mysqli_real_escape_string($con, $postData['company_name']);
    $email = mysqli_real_escape_string($con, $postData['email']);
    $mobile = mysqli_real_escape_string($con, $postData['mobile']);
    $state = mysqli_real_escape_string($con, $postData['state']);
    $inquiry_url = mysqli_real_escape_string($con, $postData['inquiry_url']);

    $q1 = "INSERT INTO inquiries(`name`, `company_name`, `email`, `mobile`, `state`,`type`) VALUES ('$name', '$company_name', '$email', '$mobile', '$state','get_quote_form')";
    mysqli_query($con, $q1);

    $url = "https://t18cms.tender18.com/wp-json/myapi/v1/add-product";

    // Post data
    $data = [
        'tender_ref_no'   => '',
        'inquiry_url' => $inquiry_url,
        'name'=> $name,
        'company_name'  => $company_name,
        'email'  => $email,
        'mobile_no'  => $mobile,
        'state'  => $state,
        'description'  => '',
        'gem_state_city'  => '',
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $app_password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    // Optional: Disable SSL verification for localhost
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);


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
    $message = 'Hello Admin, below are the Get A Free Quote form details...<br><br>';
    $message .= '<b>Name :</b> ' . htmlspecialcode_generator($name) . '<br>';
    $message .= '<b>Company Name :</b> ' . htmlspecialcode_generator($company_name) . '<br>';
    $message .= '<b>Email :</b> ' . htmlspecialcode_generator($email) . '<br>';
    $message .= '<b>Mobile :</b> ' . htmlspecialcode_generator($mobile) . '<br>';
    $message .= '<b>State :</b> ' . htmlspecialcode_generator($state) . '<br>';
    $message .= '';
    $subject = 'Get A Free Quote';

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
        $mail->send();
        //echo 'Email sent successfully';
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $result['name'] = htmlspecialcode_generator($name);
    $result['company_name'] = htmlspecialcode_generator($company_name);
    $result['email'] = htmlspecialcode_generator($email);
    $result['mobile'] = htmlspecialcode_generator($mobile);
    $result['state'] = htmlspecialcode_generator($state);
    $result['message'] = "Data saved";
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
