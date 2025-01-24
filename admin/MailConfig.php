<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
function email($name, $to, $message, $subject)
{
    $mail = new PHPMailer(true);

    try {
        /* if(!empty($mail_cc))
		{
            $ccs = explode(',',$mail_cc);
        }
        else
		{
            $ccs = '';
        }*/

        //$from_name  = SMTP_TITLE;
        $from       = 'dailyalert@tender18.in';
        $to_name = $name;

        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'email-smtp.ap-south-1.amazonaws.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'AKIAYPL3DWHYSKHC2OGG';                     //SMTP username
        $mail->Password   = 'BLQmCM7s+xhMetgml4334fiQ9gx8op8uGBSA0ckvqmDH';                               //SMTP password
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->SMTPSecure = "tls";
        $mail->Port       = 25;
        //Recipients
        $mail->setFrom($from, $to_name);
        // foreach($to as $tonew)
        // {
        // 	$mail->addAddress($tonew);                  // name is optional
        // }

        //var_dump($to);exit;
        // $mail->addAddress("dev@clickthedemo.com");
        $mail->addAddress($to);
        // Add a recipient
        // $mail->addAddress('ellen@example.com');               // Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        /*        if(isset($ccs) && $ccs!='')
        {
            foreach($ccs as $cc)
            {
               $mail->AddCC($cc, $to_name);
            }
        }
        if(!empty($attachment)){
            $filePath = dirname(__FILE__);
            $attachments = explode(',',$attachment);
            foreach($attachments as $attachment){
                $mail->AddAttachment($filePath. $attachment);
            }
        }
*/
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        /*$mail->Subject = "sge";
        $mail->Body    = "SGIUNERVN";*/

        $mail->Subject = $subject;
        $mail->Body    = $message;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
