<?php
include '../includes/connection.php';
include '../includes/functions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/vendor/autoload.php';

function highlightSearchTerm($text, $searchTerm)
        {
            // $highlightedTerm = "<b>$searchTerm</b>";
            // return str_ireplace($searchTerm, $highlightedTerm, $text);
    
            $highlightMarkup = '<strong style=color:#cb192d;margin-right:3px;>';
            $closingHighlightMarkup = '</strong>';
            $highlightedText = preg_replace("/({$searchTerm})/i", $highlightMarkup . '$1' . $closingHighlightMarkup, $text);
            return $highlightedText;
        }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        }
    }
    $mcount = 0;
    if (!empty($ids)) {
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
                if(!empty($cust_from_email)){
                    $from_email = $cust_from_email;
                }else{
                    $from_email = $row['from_email'];
                }
                $from_name = $row['from_name'];
            }
        }
        $idArray= [];
        if(!empty($ids)):
            $idArray = explode(',', $ids);
        endif;
        $usersData = mysqli_query($con, "SELECT `mail_type`,`email_ids`,`company_name`,`user_unique_id`,`not_used_keywords`,`words`,`keywords`,`filter_city`,`filter_state`,`filter_tender_value`,`filter_agency`,`filter_department`,`filter_type` FROM `users` WHERE user_id IN ($ids)");
        $usersResult = mysqli_num_rows($usersData);
        $company_name = "";
        if ($usersResult > 0) {
            while ($row = mysqli_fetch_assoc($usersData)) {
                $uemail_ids = $row['email_ids'];
                if(!empty($uemail_ids)):
                    $mail_type= $row['mail_type'];
                    $company_name= $row['company_name'];
                    $city = $row['filter_city'];
                    $state = $row['filter_state'];
                    $tenderValue = $row['filter_tender_value'];
                    $agency = $row['filter_agency'];
                    $department = $row['filter_department'];
                    $type = $row['filter_type'];
                    $keywords = $row['keywords'];
                    $not_used_keywords = $row['not_used_keywords'];
                    $words = $row['words'];
                    //$exp_uemail_ids = explode(',',$uemail_ids);
                    $exp_uemail_ids = explode(',', $cms_emailids);
                    $user_unique_id= $row['user_unique_id'];
                    $ADMIN_URL = ADMIN_URL;
                    $HOME_URL = HOME_URL;
                    $mail = new PHPMailer(true);
                    $cdate = date('M d, Y');
                    if($mail_type == 'link'):
                        
                        $template = file_get_contents('../cms_tender/link_email_template.php');
                        $texts = "Today's";
                        $decoded_text = html_entity_decode($texts, ENT_QUOTES, 'UTF-8');
                        $template = str_replace('{{decoded_text}}', $decoded_text, $template);
                        $template = str_replace('{{company_name}}', $company_name, $template);
                        $template = str_replace('{{cdate}}', $cdate, $template);
                        $template = str_replace('{{user_unique_id}}', $user_unique_id, $template);
                        $template = str_replace('{{ADMIN_URL}}', $ADMIN_URL, $template);
                        $template = str_replace('{{HOME_URL}}', $HOME_URL, $template);
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
                            $subject = "Today’s New Tenders  - $cdate From TENDER18.COM";
                            $mail->isHTML(true); // Set email format to HTML
                            $mail->Subject = "=?UTF-8?B?'".base64_encode($subject)."'?=";
                            $mail->Body    = $template;
                        // print_r($mail);exit;
                            // Send the email
                            $mail->send();
                            sleep(1);
                            $mcount++;
                            //echo 'Email sent successfully';
                        } catch (Exception $e) {
                            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    elseif($mail_type == 'list'):
                        $condition_new = "";
                        $condition_city = "";
                        if (!empty($city)) {
                            $city = explode(",", $city);
                            if (!empty($city)) {
                                
                                foreach ($city as $key => $value) {
                                    if ($key > 0) {
                                        $condition_city .= " or city='$value'";
                                    } else {
                                        $condition_city .= " city='$value'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_city . " )";
                            }
                        }
                        $condition_state = "";
                        if (!empty($state)) {
                            $state = explode(",", $state);
                            if (!empty($state)) {
                                
                                foreach ($state as $key => $value) {
                                    if ($key > 0) {
                                        $condition_state .= " or state='$value'";
                                    } else {
                                        $condition_state .= " state='$value'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_state . " )";
                            }
                        }
                
                        if (!empty($tenderValue)) {
                            $condition_new .= " and tender_value between 0 and $tenderValue";
                        }
                        $condition_agency = "";
                        if (!empty($agency)) {
                            $agency = explode(",", $agency);
                            if (!empty($agency)) {
                                
                                foreach ($agency as $key => $value) {
                                    if ($key > 0) {
                                        $condition_agency .= " or agency_type LIKE '%$value%'";
                                    } else {
                                        $condition_agency .= " agency_type LIKE '%$value%'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_agency . " )";
                            }
                        }
                        $condition_department = "";
                        if (!empty($department)) {
                            $department = explode(",", $department);
                            if (!empty($department)) {
                                
                                foreach ($department as $key => $value) {
                                    if ($key > 0) {
                                        $condition_department .= " or department LIKE '%$value%'";
                                    } else {
                                        $condition_department .= " department LIKE '%$value%'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_department . " )";
                            }
                        }
                        $condition_type = "";
                        if (!empty($type)) {
                            $type = explode(",", $type);
                            if (!empty($type)) {
                                
                                foreach ($type as $key => $value) {
                                    if ($key > 0) {
                                        $condition_type .= " or tender_type LIKE '%$value%'";
                                    } else {
                                        $condition_type .= " tender_type LIKE '%$value%'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_type . " )";
                            }
                        }
                        $whereClauses = [];
                        $whereClauses1 = [];
                        $whereClauses2 = [];
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

                        if (!empty($not_used_keywords)) {
                            $not_used_keywords = explode(',', $not_used_keywords);
                            foreach ($not_used_keywords as $not_keyword) {
                                $not_keyword_arr = explode(' ', $not_keyword);
                                $arr_not_keyword = "";
                                $cnt_in = 0;
                                // echo count($keyword_arr);
                                foreach ($not_keyword_arr as $key) {
                                    if ($cnt_in > 0) {
                                        $arr_not_keyword .= " and ";
                                    }
                                    $arr_not_keyword .= "title NOT LIKE '%$key%'";
                                    $cnt_in++;
                                }
                                if (count($not_keyword_arr) > 1 && !empty($not_keyword_arr)) {
                                    $arr_not_keyword = " ( " . $arr_not_keyword . " ) ";
                                } else {
                                    $arr_not_keyword = " ( " . $arr_not_keyword . " ) ";
                                }
                                $whereClauses1[] = $arr_not_keyword;

                                $arr_not_keyword1 = "";
                                $cnt_in = 0;
                                foreach ($not_keyword_arr as $key) {
                                    if ($cnt_in > 0) {
                                        $arr_not_keyword1 .= " and ";
                                    }
                                    $arr_not_keyword1 .= "description NOT LIKE '%$key%'";
                                    $cnt_in++;
                                }
                                if (count($not_keyword_arr) > 1 && !empty($not_keyword_arr)) {
                                    $arr_not_keyword1 = " ( " . $arr_not_keyword1 . " ) ";
                                } else {
                                    $arr_not_keyword1 = " ( " . $arr_not_keyword1 . " ) ";
                                }
                                $whereClauses1[] = $arr_not_keyword1;
                            }
                        }

                        if (!empty($words)) {
                            $words = explode(',', $words);
                            foreach ($words as $word) {
                                $whereClauses2[] = "( title LIKE '%$word%' )";
                                $whereClauses2[] = "( description LIKE '%$word%' )";
                            }
                        }

                        if (!empty($whereClauses) && !empty($whereClauses1) && !empty($whereClauses2)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $whereCondition1 = implode(' and ', $whereClauses1);
                            $whereCondition2 = implode(' or ', $whereClauses2);
                            $condition = "WHERE (" . $whereCondition . " or " . $whereCondition2 . ") AND (" . $whereCondition1 . ")";
                        }

                        if (!empty($whereClauses) && empty($whereClauses1) && !empty($whereClauses2)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $whereCondition2 = implode(' or ', $whereClauses2);
                            $condition = "WHERE (" . $whereCondition .  " or " . $whereCondition2 . ")";
                        }

                        if (empty($whereClauses) && !empty($whereClauses1) && !empty($whereClauses2)) {
                            $whereCondition1 = implode(' and ', $whereClauses1);
                            $whereCondition2 = implode(' or ', $whereClauses2);
                            $condition = "WHERE (" . $whereCondition2 . ") AND (" . $whereCondition1 . ")";
                        }

                        if (!empty($whereClauses) && !empty($whereClauses1) && empty($whereClauses2)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $whereCondition1 = implode(' and ', $whereClauses1);
                            $condition = "WHERE (" . $whereCondition . ") AND (" . $whereCondition1 . ")";
                        }

                        if (!empty($whereClauses) && empty($whereClauses1)  && empty($whereClauses2)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $condition = "WHERE (" . $whereCondition . ")";
                        }

                        if (empty($whereClauses) && empty($whereClauses1)  && !empty($whereClauses2)) {
                            $whereCondition2 = implode(' or ', $whereClauses2);
                            $condition = "WHERE (" . $whereCondition2 . ")";
                        }

                        if (!empty($whereClauses1) && empty($whereClauses)  && empty($whereClauses2)) {
                            $whereCondition1 = implode(' and ', $whereClauses1);
                            $condition = "WHERE (" . $whereCondition1 . ")";
                        }

                        if (empty($condition) && !empty($condition_new)) {
                            $condition = preg_replace('/and/', 'where', $condition_new, 1);
                        } else {
                            $condition .= $condition_new;
                        }
                        $ar = '';
                        //echo "SELECT * FROM `tenders_posts` $condition order by id desc";exit;

                        $kcounter = 0;
                        $ks=0;
                        $keyword_key_val = '';
                        $condition_orderque_key = '';
                        if(!empty($keywords)):
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

                        $tender_data = mysqli_query($con, "SELECT * FROM `tenders_posts` $condition $condition_orderque_key");
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
                                        <h4 style="margin-top: 0;margin-bottom: 0;"><a target="_blank" href="'.$HOME_URL.'tenders-details/'.$row['ref_no'].'/'.$user_unique_id.'" style="text-decoration:none !important;font-size: 16px;font-family: DMSans;font-weight: 700;color: #016492;margin-bottom: 0;-webkit-line-clamp: 1;-webkit-box-orient: vertical;display: -webkit-box;overflow: hidden;text-transform: capitalize;">'.htmlspecialcode_generator($highlightedResult).'</a></h4>
                                        </td>
                                    </tr>
                                </table>
                                <hr style="margin-top:-10px;color: inherit;opacity: .25;">';
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
                                        <p style="text-align:right;margin-top: 0;"><a class="btn" target="_blank" href="'.$HOME_URL.'tenders-details/'.$row['ref_no'].'/'.$user_unique_id.'" style="text-decoration:none !important;height: unset;width: unset;color: #222;padding: 4px;border: 1px solid #222;font-weight: 600;border-radius: 0;font-family: Arial, sans-serif;font-size:10px;">View Documents</a></p>
                                        </td>
                                    </tr>
                                </table>';
                                $ar.='</td></tr></table>';
                                //print_r($row);exit;
                            }
                        }

                        $template = file_get_contents('../cms_tender/list_email_template.php');
                        $texts = "Today's";
                        $decoded_text = html_entity_decode($texts, ENT_QUOTES, 'UTF-8');
                        $template = str_replace('{{decoded_text}}', $decoded_text, $template);
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
                            $subject = "Today’s New Tenders  - $cdate From TENDER18.COM";
                            $mail->isHTML(true); // Set email format to HTML
                            $mail->Subject = "=?UTF-8?B?'".base64_encode($subject)."'?=";
                            $mail->Body = $template;
                            //print_r($mail);exit;
                            // Send the email
                            if ($tender_result > 0) {
                                $mail->send();
                                sleep(1);
                            }
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
                window.location.href='" . ADMIN_URL . "cms_tender/active.php?id=".$cms_id."&st=1';
                </script>";
            }else{
                echo "<script>
                window.location.href='" . ADMIN_URL . "cms_tender/active.php?id=".$cms_id."&st=0';
            
            </script>";
            }
            
        }
       
        
// /exit;
    }
    
}
