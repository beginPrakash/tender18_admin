<?php
include '../includes/connection.php';
include '../includes/functions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/vendor/autoload.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = isset($_POST['ids']) ? $_POST['ids'] : '';
    $mcount = 0;
    if (!empty($ids)) {
        $idArray= [];
        if(!empty($ids)):
            $idArray = explode(',', $ids);
        endif;
        $usersData = mysqli_query($con, "SELECT `mail_type`,`email_ids`,`company_name`,`user_unique_id`,`keywords` FROM `users` WHERE user_id IN ($ids)");
        $usersResult = mysqli_num_rows($usersData);
        $company_name = "";
        if ($usersResult > 0) {
            while ($row = mysqli_fetch_assoc($usersData)) {
                $uemail_ids = $row['email_ids'];
                if(!empty($uemail_ids)):
                    $mail_type= $row['mail_type'];
                    $company_name= $row['company_name'];
                    
                    $keywords = $row['keywords'];
                    $exp_uemail_ids = explode(',',$uemail_ids);
                    $user_unique_id= $row['user_unique_id'];
                    $ADMIN_URL = ADMIN_URL;
                    $HOME_URL = HOME_URL;
                    $mail = new PHPMailer(true);
                    $cdate = date('M d, Y');
                    if($mail_type == 'link'):
                        
                        $template = file_get_contents('../clients/link_email_template.php');
                        
                        $template = str_replace('{{company_name}}', $company_name, $template);
                        $template = str_replace('{{cdate}}', $cdate, $template);
                        $template = str_replace('{{user_unique_id}}', $user_unique_id, $template);
                        $template = str_replace('{{ADMIN_URL}}', $ADMIN_URL, $template);
                        $template = str_replace('{{HOME_URL}}', $HOME_URL, $template);
                        try {
                            // Server settings
                            $mail->SMTPDebug = 0; // Disable verbose debug output
                            $mail->isSMTP(); // Send using SMTP
                            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                            $mail->SMTPAuth = true; // Enable SMTP authentication
                            $mail->Username = 'sales@tender18mail.in'; // SMTP username
                            $mail->Password = 'zgtm dlbm nqal jwpm'; // SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                            $mail->Port = 587; // TCP port to connect to

                            // Recipients
                            $mail->setFrom('sales@tender18mail.in', 'Tender 18');
                            
                            // Add multiple recipients
                            $recipients = $exp_uemail_ids;
                            foreach ($recipients as $recipient) {
                                $mail->addAddress($recipient);
                            }

                            // Content
                            $subject = "Today’s New Tenders  - $cdate From TENDER18.COM";
                            $mail->isHTML(true); // Set email format to HTML
                            $mail->Subject = "=?UTF-8?B?'".base64_encode($subject)."'?=";
                            $mail->Body    = $template;
                        // print_r($mail);exit;
                            // Send the email
                            $mail->send();
                            $mcount++;
                            //echo 'Email sent successfully';
                        } catch (Exception $e) {
                            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    elseif($mail_type == 'list'):

                        $whereClauses = [];
                        if (!empty($keywords)) {
                            $keywords = explode(',', $keywords);
                            foreach ($keywords as $keyword) {
                                $keyword_arr = explode(' ', $keyword);
                                $arr_keyword = "";
                                $cnt_in = 0;
                                // echo count($keyword_arr);
                                foreach ($keyword_arr as $key) {
                                    if ($cnt_in > 0) {
                                        $arr_keyword .= " and ";
                                    }
                                    $arr_keyword .= "title LIKE '%$key%'";
                                    $cnt_in++;
                                }
                                if (count($keyword_arr) > 1 && !empty($keyword_arr)) {
                                    $arr_keyword = " ( " . $arr_keyword . " ) ";
                                } else {
                                    $arr_keyword = " ( " . $arr_keyword . " ) ";
                                }
                                $whereClauses[] = $arr_keyword;

                                $arr_keyword1 = "";
                                $cnt_in = 0;
                                foreach ($keyword_arr as $key) {
                                    if ($cnt_in > 0) {
                                        $arr_keyword1 .= " and ";
                                    }
                                    $arr_keyword1 .= "description LIKE '%$key%'";
                                    $cnt_in++;
                                }
                                if (count($keyword_arr) > 1 && !empty($keyword_arr)) {
                                    $arr_keyword1 = " ( " . $arr_keyword1 . " ) ";
                                } else {
                                    $arr_keyword1 = " ( " . $arr_keyword1 . " ) ";
                                }
                                $whereClauses[] = $arr_keyword1;
                            }
                        }

                        if (!empty($whereClauses)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $condition = "WHERE (" . $whereCondition . ")";
                        }
                        $ar = '';
                        $tender_data = mysqli_query($con, "SELECT * FROM `tenders_posts` $condition order by id desc");
                        $tender_result = mysqli_num_rows($tender_data);
                        if ($tender_result > 0) {
                            while ($row = mysqli_fetch_assoc($tender_data)) {
                                //print_r($row);exit;
                                $ar.='<table class="table_container" width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-top:10px;"><tr style="background-color:#fff;"><td>';
                                $ar.='<table class="row" width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr style="background-color:#016492;color:#fff !important">
                                        <!-- Left Column -->
                                        <td class="column column-50" style="width:50%; padding: 10px;">
                                        <h6 style="margin-top: 0;">T18 Ref No : <span>'.$row['ref_no'].'</span></h6>
                                        </td>
                                        <!-- Right Column -->
                                        <td class="column column-50" style="width:50%; padding: 10px;">
                                        <h6 style="text-align:right;margin-top: 0;">Location : <span>'.$row['city'].', '.$row['state'].'</span></h6>
                                        </td>
                                    </tr>
                                </table>';
                                $ar.='<table class="row" width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td class="column column-100" style="padding: 10px;">
                                        <h4 style="margin-top: 0;margin-bottom: 0;"><a target="_blank" href="'.$HOME_URL.'tenders-details/'.$user_unique_id.'" style="text-decoration:none !important;"><b>'.$row['title'].'</b></a></h4>
                                        </td>
                                    </tr>
                                </table>
                                <hr style="margin-top:-10px">';
                                $ar.='<table class="row" width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <!-- Left Column -->
                                        <td class="column column-50" style="width:50%; padding: 10px;">
                                        <h6 style="text-align:left;margin-top: 0;">Agency / Dept : <span style="color:#777">'.$row['agency_type'].'</span></h6>
                                        </td>
                                        <!-- Right Column -->
                                        <td class="column column-50" style="width:50%; padding: 10px;">
                                        <h6 style="text-align:right;margin-top: 0;">Tender Value : <span style="color:#777">'.$row['tender_value'].'</span></h6>
                                        </td>
                                    </tr>
                                </table>';
                                $ar.='<table class="row" width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <!-- Left Column -->
                                        <td class="column column-50" style="width:50%; padding: 10px;">
                                        <h6 style="text-align:left;margin-top: 0;">Due Date : <span style="color:#777">'.date('M d, Y',strtotime($row['due_date'])).'</span></h6>
                                        </td>
                                        <!-- Right Column -->
                                        <td class="column column-50" style="width:50%; padding: 10px;">
                                        <p style="text-align:right;margin-top: 0;"><a class="btn" target="_blank" href="'.$HOME_URL.'tenders-details/'.$user_unique_id.'" style="text-decoration:none !important;">View Documents</a></p>
                                        </td>
                                    </tr>
                                </table>';
                                $ar.='</td></tr></table>';
                                //print_r($row);exit;
                            }
                        }
        
                        $tender_query = 
                        $template = file_get_contents('../clients/list_email_template.php');
                    
                        $template = str_replace('{{company_name}}', $company_name, $template);
                        $template = str_replace('{{cdate}}', $cdate, $template);
                        $template = str_replace('{{user_unique_id}}', $user_unique_id, $template);
                        $template = str_replace('{{ADMIN_URL}}', $ADMIN_URL, $template);
                        $template = str_replace('{{HOME_URL}}', $HOME_URL, $template);
                        $template = str_replace('{{ar}}', $ar, $template);
                        try {
                            // Server settings
                            $mail->SMTPDebug = 0; // Disable verbose debug output
                            $mail->isSMTP(); // Send using SMTP
                            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                            $mail->SMTPAuth = true; // Enable SMTP authentication
                            $mail->Username = 'sales@tender18mail.in'; // SMTP username
                            $mail->Password = 'zgtm dlbm nqal jwpm'; // SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                            $mail->Port = 587; // TCP port to connect to

                            // Recipients
                            $mail->setFrom('sales@tender18mail.in', 'Tender 18');
                            
                            // Add multiple recipients
                            $recipients = $exp_uemail_ids;
                            foreach ($recipients as $recipient) {
                                $mail->addAddress($recipient);
                            }

                            // Content
                            $subject = "Today’s New Tenders  - $cdate From TENDER18.COM";
                            $mail->isHTML(true); // Set email format to HTML
                            $mail->Subject = "=?UTF-8?B?'".base64_encode($subject)."'?=";
                            $mail->Body = $template;
                            //print_r($mail);exit;
                            // Send the email
                            $mail->send();
                            $mcount++;
                            //echo 'Email sent successfully';
                        } catch (Exception $e) {
                            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    else:
                    endif;
                endif;   
            }
            if($mcount > 0){
                echo "<script>
                window.location.href='" . ADMIN_URL . "clients/upgrade.php?st=1';
                </script>";
            }else{
                echo "<script>
            window.location.href='" . ADMIN_URL . "clients/upgrade.php?st=0';
            </script>";
            }
            
        }
       
        
// /exit;
    }
    
}
