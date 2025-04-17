<?php
include '../includes/connection.php';
include '../includes/functions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/vendor/autoload.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cms_emailids='';
    $ids = isset($_POST['ids']) ? $_POST['ids'] : '';
    $cust_from_email = isset($_POST['cust_from_email']) ? $_POST['cust_from_email'] : '';
    $cust_reply_email = isset($_POST['cust_reply_email']) ? $_POST['cust_reply_email'] : '';
    $cms_id = isset($_POST['cms_id']) ? $_POST['cms_id'] : '';
    $customer_id = base64_decode($cms_id);
    //find cms customer id
    $banner_data = mysqli_query($con, "SELECT * FROM `cms_customer` where customer_id='" . $customer_id . "'");
    $banner_result = mysqli_num_rows($banner_data);
    if ($banner_result == 1) {
        while ($row = mysqli_fetch_assoc($banner_data)) {
            $cms_emailids = $row['email_ids'];
            $mobile_no= $row['mobile_no'];
            $company_name= $row['company_name'];
            $keywords= $row['keywords'];
        }
    }


    $mcount = 0;
    $m_arr = [];
    if (!empty($ids)) {
        $mai_data = mysqli_query($con, "SELECT * FROM `cms_smtp_management` where id = 1");
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
                if(!empty($cust_from_email)){
                    $from_email = $cust_from_email;
                }else{
                    $from_email = $row['from_email'];
                }
               
            }
        }
      
        function highlightSearchTerm($text, $searchTerm)
        {
            // $highlightedTerm = "<b>$searchTerm</b>";
            // return str_ireplace($searchTerm, $highlightedTerm, $text);
    
            $highlightMarkup = '<strong style=color:#cb192d;margin-right:3px;>';
            $closingHighlightMarkup = '</strong>';
            $highlightedText = preg_replace("/({$searchTerm})/i", $highlightMarkup . '$1' . $closingHighlightMarkup, $text);
            return $highlightedText;
        }

        

        $exp_uemail_ids = explode(',', $cms_emailids);
        
        $ADMIN_URL = ADMIN_URL;
        $HOME_URL = HOME_URL;
        $mail = new PHPMailer(true);
        $cdate = date('M d, Y');

            $ar = '';
            //echo "SELECT * FROM `tenders_posts` $condition order by id desc";exit;

            $kcounter = 0;
            $ks=0;
            $keyword_key_val = '';
            $condition_orderque_key = '';
            if(!empty($keywords)):
                $keywords = explode(',',$keywords);
            foreach ($keywords as $key => $value) {
                        if ($kcounter == 0 && $key <= 0) {
                            $keyword_key_val .= " ORDER BY CASE";
                        } 
                            $keyword_key_val .= " WHEN title LIKE '%$value%' THEN $ks";
                        
                        $kcounter++;
                        $ks++;
                    }
                endif;

            if($keyword_key_val != ''){
                $condition_orderque_key .= " " . $keyword_key_val;
            }
            if(!empty($keywords)):
                $keys_count = count($keywords);
                $condition_orderque_key .= " ELSE " . $keys_count . " END, title ASC";
            endif;

            $tender_data = mysqli_query($con, "SELECT * FROM `tenders_archive` WHERE id IN ($ids) $condition_orderque_key");
            $tender_result = mysqli_num_rows($tender_data);
            if ($tender_result > 0) {
                while ($row = mysqli_fetch_assoc($tender_data)) {

                    $result_title = "";
                    
                    if (!empty($keywords) && !empty($words)) {
                        $highlightedResult = $row['title'];
                        foreach ($words as $word) {
                            $highlightedResult = highlightSearchTerm($highlightedResult, $word);
                        }
                        foreach ($keywords as $keyword) {
                            $keyword_arr = explode(' ', $keyword);
                            foreach ($keyword_arr as $key) {
                                $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                            }
                        }
                        
                        $result_title = htmlspecialcode_generator($highlightedResult);
                    } else if (!empty($keywords)) {
                    
                        $highlightedResult = $row['title'];
                        foreach ($keywords as $keyword) {
                            $keyword_arr = explode(' ', $keyword);
                            foreach ($keyword_arr as $key) {
                                $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                            }
                        }
                        $result_title = htmlspecialcode_generator($highlightedResult);
                    } else if (!empty($words)) {
                        $highlightedResult = $row['title'];
                        foreach ($words as $word) {
                            $highlightedResult = highlightSearchTerm($highlightedResult, $word);
                        }
                        $result_title = htmlspecialcode_generator($highlightedResult);
                    } else {
                        $result_title = htmlspecialcode_generator($row['title']);
                    }

                    $highlightedResult = $result_title;
                    if (!empty($filter_keyword)) {
                        $keyword_arr = [];
                        foreach ($filter_keyword as $keyword) {
                            $keyword_arr_new = explode(' ', $keyword);
                            foreach ($keyword_arr_new as $key) {
                                $keyword_arr[] = $key;
                            }
                        }
                        usort($keyword_arr, function ($a, $b) {
                            $lengthComparison = strlen($b) - strlen($a);
                            if ($lengthComparison !== 0) {
                                return $lengthComparison;
                            }
                            return strcmp($a, $b);
                        });
                        // print_r($keyword_arr);
                        foreach ($keyword_arr as $keyword) {
                            $highlightedResult = highlightSearchTerm($highlightedResult, $keyword);
                        }
                    }
                    $dep_type = "";
                    $dep_text = "";
                    if($row['department'] == 'gem'){
                        $dep_text = "Source :";
                        $dep_type = "GEM Tenders";
                    }
                    //print_r($row);exit;
                    $ar.='<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-top:10px;"><tr style="background-color:#fff;"><td>';
                    $ar.='<table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr style="background-color:#016492;color:#fff !important">
                            <!-- Left Column -->
                            <td style="width:30%; padding: 10px;">
                            <h6 style="margin-top: 0;font-size: 14px;font-family: DMSans;font-weight: 600;margin-bottom: 0;text-transform: capitalize;">T18 Ref No : <span style="font-weight: 400;text-transform: capitalize;">'.$row['ref_no'].'</span></h6>
                            </td>
                            <!-- Right Column -->
                            <td style="width:40%; padding: 10px;">
                            <h6 style="text-align:center;margin-top: 0;font-size: 14px;font-family: DMSans;font-weight: 600;margin-bottom: 0;text-transform: capitalize;">Location : <span style="font-weight: 400;text-transform: capitalize;">'.$row['city'].', '.$row['state'].'</span></h6>
                            </td>
                            <td style="width:30%; padding: 10px;">
                            <h6 style="text-align:right;margin-top: 0;font-size: 14px;font-family: DMSans;font-weight: 600;margin-bottom: 0;text-transform: capitalize;">'.$dep_text.' <span style="font-weight: 400;text-transform: capitalize;">'.$dep_type.'</span></h6>
                            </td>
                        </tr>
                        </table>';
                    $ar.='<table width="100%" cellspacing="0" cellpadding="0" border="0">

                    <tr>
                            <td style="padding: 10px;">
                            <h4 style="margin-top: 0;margin-bottom: 0;"><a target="_blank" href="'.$HOME_URL.'tenders-details/'.$row['ref_no'].'" style="text-decoration:none !important;font-size: 16px;font-family: DMSans;font-weight: 700;color: #016492;margin-bottom: 0;-webkit-line-clamp: 1;-webkit-box-orient: vertical;display: -webkit-box;overflow: hidden; text-transform: capitalize;">'.htmlspecialcode_generator($highlightedResult).'</a></h4>
                            </td>
                        </tr>
                    </table>
                    <hr style="margin-top:5px;color: inherit;opacity: .25;">';
                    $ar.='<table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <!-- Left Column -->
                            <td style="width:50%; padding: 10px;">
                            <h6 style="text-align:left;margin-top: 0;font-size: 14px;font-family: DMSans;font-weight: 600;margin-bottom: 0;text-transform: capitalize;">Agency / Dept : <span style="color:#777;font-weight: 400;text-transform: capitalize;">'.$row['agency_type'].'</span></h6>
                            </td>
                            <!-- Right Column -->
                            <td style="width:50%; padding: 10px;">
                            <h6 style="text-align:right;margin-top: 0;font-size: 14px;font-family: DMSans;font-weight: 600;margin-bottom: 0;text-transform: capitalize;">Tender Value : <span style="color:#777;font-weight: 400;text-transform: capitalize;">'.$row['tender_value'].'</span></h6>
                            </td>
                        </tr>
                        </table>';
                    $ar.='<table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                            <!-- Left Column -->
                            <td style="width:50%; padding: 10px;">
                            <h6 style="text-align:left;margin-top: 0;font-size: 14px;font-family: DMSans;font-weight: 600;margin-bottom: 0;text-transform: capitalize;">Due Date : <span style="color:#777;font-weight: 400;text-transform: capitalize;">'.date('M d, Y',strtotime($row['due_date'])).'</span></h6>
                            </td>
                            <!-- Right Column -->
                            <td style="width:50%; padding: 10px;">
                            <p style="text-align:right;margin-top: 0;"><a class="btn" target="_blank" href="'.$HOME_URL.'tenders-details/'.$row['ref_no'].'" style="text-decoration:none !important;height: unset;width: unset;color: #222;padding: 4px;border: 1px solid #222;font-weight: 600;border-radius: 0;font-family: Arial, sans-serif;font-size:10px;">View Documents</a></p>
                            </td>
                        </tr>
                    </table>';
                    $ar.='</td></tr></table>';
                    //print_r($row);exit;
                }
            }

            $template = file_get_contents('../cms_tender/list_email_template.php');
            $texts = "Tender's";
            $decoded_text = html_entity_decode($texts, ENT_QUOTES, 'UTF-8');
            $template = str_replace('{{decoded_text}}', $decoded_text, $template);
            $template = str_replace('{{company_name}}', $company_name, $template);
            $template = str_replace('{{mobile_no}}', $mobile_no, $template);
            $template = str_replace('{{cdate}}', $cdate, $template);
            $template = str_replace('{{ADMIN_URL}}', $ADMIN_URL, $template);
            $template = str_replace('{{HOME_URL}}', $HOME_URL, $template);
            $template = str_replace('{{ar}}', $ar, $template);
            
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
                
                // Add multiple recipients
                
                $recipients = $exp_uemail_ids;
                foreach ($recipients as $recipient) {
                    $mail->addAddress($recipient);
                }

                if(!empty($cust_reply_email)){
                    // ✅ Set Reply-To
                    $mail->addReplyTo($cust_reply_email, '');
                }
                

                // Content
                $subject = "Tender’s Alert From Tender18.com - ".$company_name."";
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = "=?UTF-8?B?'".base64_encode($subject)."'?=";
                $mail->Body = $template;
            
                // Send the email
                //print_r($mail);exit;
                if ($tender_result > 0) {
                    $mail->send();
                    sleep(1);
                }
                $mcount++;
                //echo 'Email sent successfully';
            } catch (Exception $e) {
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
       
                
        if($mcount > 0){
            echo "<script>
            window.location.href='" . ADMIN_URL . "cms_tender/archive.php?id=".$cms_id."&st=1';
            </script>";
        }else{
            echo "<script>
        window.location.href='" . ADMIN_URL . "cms_tender/archive.php?id=".$cms_id."&st=0';
        </script>";
        }
        
       
        
// /exit;
    }
    
}
