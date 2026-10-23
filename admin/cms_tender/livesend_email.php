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
            $words = explode(',',$row['words']);
            $mobile_no= $row['mobile_no'];
            $company_name= $row['company_name'];
            $keywords= $row['keywords'];
            $cust_keywords = $row['keywords'];
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
        $rfrom_email='';
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
                $rfrom_email = $row['from_email'];
               
            }
        }
      
        function highlightSearchTerm($text, $searchTerm)
        {
             $highlightedTerm = "<strong style=color:#cb192d;margin-right:2px;>$searchTerm</strong>";
             return str_ireplace($searchTerm, $highlightedTerm, $text);
  
            // $highlightMarkup = '<strong style=color:#cb192d;margin-right:0px;>';
            // $closingHighlightMarkup = '</strong>';
            // $highlightedText = preg_replace("/({$searchTerm})/i", $highlightMarkup . '$1' . $closingHighlightMarkup, $text);
            // return $highlightedText;
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

            $tender_data = mysqli_query($con, "SELECT * FROM `tenders_live` WHERE id IN ($ids) $condition_orderque_key");
            $tender_result = mysqli_num_rows($tender_data);
            if ($tender_result > 0) {
                while ($row = mysqli_fetch_assoc($tender_data)) {
                    
                    $c_keywords = explode(',',$cust_keywords);
                                
                    $result_title = "";
        
                    if (!empty($c_keywords) && !empty($words)) {
                        $highlightedResult = $row['title'];
                        foreach ($words as $word) {
                            $highlightedResult = highlightSearchTerm($highlightedResult, $word);
                        }
                        foreach ($c_keywords as $keyword) {
                            $keyword_arr = explode(' ', $keyword);
                            foreach ($keyword_arr as $key) {
                                if($key != ''){
                                    $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                                }
                            }
                        }
            
                        $result_title = htmlspecialcode_generator($highlightedResult);
                    } else if (!empty($c_keywords)) {
            
                        $highlightedResult = $row['title'];
                        foreach ($c_keywords as $keyword) {
                            $keyword_arr = explode(' ', $keyword);
                            foreach ($keyword_arr as $key) {
                                if($key != ''){
                                    $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                                }
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
                    $location = "";
                    if (!empty($row['city'])) {
                        $location = $row['city'];
                    }
                    if (!empty($row['state'])) {
                        if (!empty($location)) {
                            $location .= ", " . $row['state'];
                        } else {
                            $location = $row['state'];
                        }
                    }

                    $dep_type = "";
                    $dep_text = "";
                    if (isset($row['department']) && $row['department'] == 'gem') {
                        $dep_text = "Source :";
                        $dep_type = "GEM Tenders";
                    }

                    $tender_value = "";
                    if (empty($row['tender_value']) || $row['tender_value'] <= 0) {
                        $tender_value = 'Refer Document';
                    } else {
                        $tender_value = $row['tender_value'];
                    }

                    $due_date_formatted = !empty($row['due_date']) ? date('M d, Y', strtotime($row['due_date'])) : '';

                    $ar .= '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding-bottom:12px;">';
                    $ar .= '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#ffffff;border:1px solid #e2e8f0;border-left:5px solid #016492;border-radius:16px;border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-shadow:0 4px 20px rgba(0,0,0,0.02);">';
                    $ar .= '<tr><td style="padding:16px 20px;text-align:left;background-color:#ffffff;border-radius:16px;">';

                    // Header row (Ref No, Location, Source)
                    $ar .= '<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom:12px;"><tr>';
                    
                    // Ref No Badge & Label
                    $ar .= '<td style="vertical-align:middle;padding-right:8px;">';
                    $ar .= '<table cellspacing="0" cellpadding="0" border="0" style="width:26px;height:26px;background-color:#016492;border-radius:6px;"><tr><td align="center" valign="middle" style="width:26px;height:26px;text-align:center;vertical-align:middle;line-height:1;padding:0;">';
                    $ar .= '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;"><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path></svg>';
                    $ar .= '</td></tr></table></td>';
                    $ar .= '<td style="vertical-align:middle;font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;color:#1a202c;white-space:nowrap;">';
                    $ar .= '<strong style="color:#1a202c;font-weight:700;">T18 Ref No :</strong> ' . ($row['ref_no'] ?? '') . '</td>';

                    if (!empty($location)) {
                        $ar .= '<td style="vertical-align:middle;padding:0 12px;"><div style="width:1.5px;height:16px;background-color:#e2e8f0;border-left:1.5px solid #e2e8f0;line-height:16px;font-size:1px;">&nbsp;</div></td>';
                        $ar .= '<td style="vertical-align:middle;padding-right:6px;line-height:1;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#016492" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></td>';
                        $ar .= '<td style="vertical-align:middle;font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;color:#1a202c;text-transform:capitalize;">';
                        $ar .= '<strong style="color:#1a202c;font-weight:700;">Location : </strong> ' . $location . '</td>';
                    }

                    if (!empty($dep_type)) {
                        $ar .= '<td style="vertical-align:middle;padding:0 12px;"><div style="width:1.5px;height:16px;background-color:#e2e8f0;border-left:1.5px solid #e2e8f0;line-height:16px;font-size:1px;">&nbsp;</div></td>';
                        $ar .= '<td style="vertical-align:middle;padding-right:6px;line-height:1;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#016492" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="9" y1="22" x2="9" y2="16"></line><line x1="15" y1="22" x2="15" y2="16"></line><line x1="9" y1="16" x2="15" y2="16"></line><path d="M8 6h.01M16 6h.01M8 10h.01M16 10h.01M12 6h.01M12 10h.01M8 14h.01M16 14h.01"></path></svg></td>';
                        $ar .= '<td style="vertical-align:middle;font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;color:#1a202c;">';
                        $ar .= '<strong style="color:#1a202c;font-weight:700;">' . ($dep_text ?: 'Source :') . ' </strong> ' . $dep_type . '</td>';
                    }

                    $ar .= '</tr></table>';

                    // Horizontal Divider under Header
                    $ar .= '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom:14px;"><tr>';
                    $ar .= '<td style="border-top:1px solid #f1f5f9;height:1px;line-height:1px;font-size:1px;">&nbsp;</td>';
                    $ar .= '</tr></table>';

                    // Title row
                    $ar .= '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom:14px;"><tr>';
                    $ar .= '<td style="text-align:left;vertical-align:top;">';
                    $ar .= '<a target="_blank" href="' . $HOME_URL . 'tenders-details/' . ($row['ref_no'] ?? '') . '" style="text-decoration:none !important;font-size:16px;font-family:DMSans, \'Times New Roman\', Times, Georgia, serif;font-weight:700;color:#016492;line-height:1.4;display:block;-webkit-line-clamp: 1;-webkit-box-orient: vertical;display: -webkit-box;overflow: hidden;text-transform:capitalize;">' . htmlspecialcode_generator($highlightedResult) . '</a>';
                    $ar .= '</td></tr></table>';

                    // Horizontal Divider
                    $ar .= '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom:14px;"><tr>';
                    $ar .= '<td style="border-top:1px solid #f1f5f9;height:1px;line-height:1px;font-size:1px;">&nbsp;</td>';
                    $ar .= '</tr></table>';

                    // Footer row
                    $ar .= '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr>';
                    
                    // Footer left details
                    $ar .= '<td style="vertical-align:middle;">';
                    $ar .= '<table cellspacing="0" cellpadding="0" border="0"><tr>';
                    
                    // Agency Block
                    $ar .= '<td style="vertical-align:middle;padding-right:10px;">';
                    $ar .= '<table cellspacing="0" cellpadding="0" border="0" style="width:36px;height:36px;background-color:#f0f7fa;border-radius:50%;"><tr><td align="center" valign="middle" style="width:36px;height:36px;text-align:center;vertical-align:middle;border-radius:50%;padding:0;line-height:1;">';
                    $ar .= '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#016492" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;"><path d="M3 22v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6"></path><path d="M6 14V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v10"></path><path d="M10 6h4M10 10h4"></path></svg>';
                    $ar .= '</td></tr></table></td>';
                    $ar .= '<td style="vertical-align:middle;padding-right:14px;width:140px;max-width:140px;">';
                    $ar .= '<div style="font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;font-weight:700;color:#1a202c;line-height:1.2;margin-bottom:2px;">Agency / Dept :</div>';
                    $ar .= '<div style="font-family:\'DM Sans\', Arial, sans-serif;font-size:13px;font-weight:400;color:#1a202c;line-height:1.35;text-transform:capitalize;">' . htmlspecialcode_generator($row['agency_type'] ?? '') . '</div>';
                    $ar .= '</td>';

                    // Vertical Divider 1
                    $ar .= '<td style="vertical-align:middle;padding:0 14px;"><div style="width:1.5px;height:32px;background-color:#e2e8f0;border-left:1.5px solid #e2e8f0;line-height:32px;font-size:1px;">&nbsp;</div></td>';

                    // Due Date Block
                    $ar .= '<td style="vertical-align:middle;padding-right:10px;">';
                    $ar .= '<table cellspacing="0" cellpadding="0" border="0" style="width:36px;height:36px;background-color:#f0f7fa;border-radius:50%;"><tr><td align="center" valign="middle" style="width:36px;height:36px;text-align:center;vertical-align:middle;border-radius:50%;padding:0;line-height:1;">';
                    $ar .= '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#016492" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
                    $ar .= '</td></tr></table></td>';
                    $ar .= '<td style="vertical-align:middle;padding-right:14px;white-space:nowrap;">';
                    $ar .= '<div style="font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;font-weight:700;color:#1a202c;line-height:1.2;margin-bottom:2px;">Due Date :</div>';
                    $ar .= '<div style="font-family:\'DM Sans\', Arial, sans-serif;font-size:13px;font-weight:400;color:#1a202c;line-height:1.35;text-transform:capitalize;">' . $due_date_formatted . '</div>';
                    $ar .= '</td>';

                    // Vertical Divider 2
                    $ar .= '<td style="vertical-align:middle;padding:0 14px;"><div style="width:1.5px;height:32px;background-color:#e2e8f0;border-left:1.5px solid #e2e8f0;line-height:32px;font-size:1px;">&nbsp;</div></td>';

                    // Tender Value Block
                    $ar .= '<td style="vertical-align:middle;padding-right:10px;">';
                    $ar .= '<table cellspacing="0" cellpadding="0" border="0" style="width:36px;height:36px;background-color:#f0f7fa;border-radius:50%;"><tr><td align="center" valign="middle" style="width:36px;height:36px;text-align:center;vertical-align:middle;border-radius:50%;padding:0;line-height:1;">';
                    $ar .= '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#016492" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;"><path d="M6 3h12M6 8h12M6 8a5 5 0 0 1 5 5H6l8 8"></path></svg>';
                    $ar .= '</td></tr></table></td>';
                    $ar .= '<td style="vertical-align:middle;padding-right:14px;white-space:nowrap;">';
                    $ar .= '<div style="font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;font-weight:700;color:#1a202c;line-height:1.2;margin-bottom:2px;">Tender Value :</div>';
                    $ar .= '<div style="font-family:\'DM Sans\', Arial, sans-serif;font-size:13px;font-weight:400;color:#1a202c;line-height:1.35;text-transform:capitalize;">' . $tender_value . '</div>';
                    $ar .= '</td>';

                    $ar .= '</tr></table></td>';

                    // View Documents Button
                    $ar .= '<td align="right" style="vertical-align:middle;text-align:right;white-space:nowrap;">';
                    $ar .= '<a target="_blank" href="' . $HOME_URL . 'tenders-details/' . ($row['ref_no'] ?? '') . '" style="text-decoration:none !important;display:inline-block;padding:8px 16px;border:1.5px solid #cb192d;background-color:#ffffff;color:#cb192d;border-radius:8px;font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;font-weight:700;text-align:center;vertical-align:middle;line-height:1;white-space:nowrap;">';
                    $ar .= '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#cb192d" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:6px;margin-top:-2px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>';
                    $ar .= '<span style="color:#cb192d;font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;font-weight:700;vertical-align:middle;">View Documents</span>';
                    $ar .= '</a></td>';

                    $ar .= '</tr></table>';

                    // Close card table & spacing wrapper
                    $ar .= '</td></tr></table></td></tr></table>';
                    //print_r($row);exit;
                }
            }

            $template = file_get_contents('../cms_tender/list_email_template.php');
            $texts = "Tender's";
            $decoded_text = html_entity_decode($texts, ENT_QUOTES, 'UTF-8');
            $template = str_replace('{{decoded_text}}', $decoded_text, $template);
            $template = str_replace('{{company_name}}', $company_name, $template);
            $template = str_replace('{{cdate}}', $cdate, $template);
            $template = str_replace('{{mobile_no}}', $mobile_no, $template);
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
                $mail->addReplyTo($rfrom_email, '');

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
            window.location.href='" . ADMIN_URL . "cms_tender/live.php?id=".$cms_id."&st=1';
            </script>";
        }else{
            echo "<script>
        window.location.href='" . ADMIN_URL . "cms_tender/live.php?id=".$cms_id."&st=0';
        </script>";
        }
        
       
        
// /exit;
    }
    
}
