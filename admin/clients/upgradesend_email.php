<?php
include '../includes/connection.php';
include '../includes/functions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/vendor/autoload.php';

function highlightSearchTerm($text, $searchTerm)
        {
            if (empty($searchTerm)) {
                return $text;
            }
        
            $escapedTerm = preg_quote($searchTerm, '/');
        
            $highlightStart = '<strong style=color:#cb192d;margin-right:2px;>';
            $highlightEnd   = '</strong>';
        
            return preg_replace(
                "/(?![^<]*>)($escapedTerm)/i",
                $highlightStart . '$1' . $highlightEnd,
                $text
            );
        }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = isset($_POST['ids']) ? $_POST['ids'] : '';
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
                $from_email = $row['from_email'];
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
        $keywords="";
        $words="";
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
                    $t_keywords = $row['keywords'];
                    $not_used_keywords = $row['not_used_keywords'];
                    $words = $row['words'];
                    $exp_uemail_ids = explode(',',$uemail_ids);
                    $user_unique_id= $row['user_unique_id'];
                    $ADMIN_URL = ADMIN_URL;
                    $HOME_URL = HOME_URL;
                    $mail = new PHPMailer(true);
                    $cdate = date('M d, Y');
                    if($mail_type == 'link'):
                        
                        $template = file_get_contents('../clients/link_email_template.php');
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
                        $condition_orderque_key = '';
                        $keyword_key_val = '';
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

                        $tender_id_arr = [];
                        $tender_datas = mysqli_query($con, "SELECT * FROM `tenders_posts` $condition $condition_orderque_key");
                        $tender_ids_count = mysqli_num_rows($tender_datas);
                        if($tender_datas > 0){
                            while ($rowst = mysqli_fetch_assoc($tender_datas)) {
                                if($tender_datas > 0){
                                    $tender_id_arr[] = $rowst['id'];
                                }
                            }
                        }   

                        if(count($tender_id_arr) > 0){
                            $exp_ten_ids = implode(',',$tender_id_arr);

                            $tender_data = mysqli_query($con, "SELECT * FROM `tenders_posts` where `id` in($exp_ten_ids)");
                        
                            $tender_result = mysqli_num_rows($tender_data);
                            $result_title = "";
                            while ($row = mysqli_fetch_assoc($tender_data)) {
                              
                                $c_keywords = explode(',',$t_keywords);
                               
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
                                } else if (!empty($c_keywords) && empty($words)) {
                                
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
                                } else if (!empty($words) && empty($c_keywords)) {
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

                                $ar .= '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-bottom:12px;">';
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
                                $ar .= '<a target="_blank" href="' . $HOME_URL . 'tenders-details/' . ($row['ref_no'] ?? '') . '/' . $user_unique_id . '" style="text-decoration:none !important;font-size:16px;font-family:DMSans, \'Times New Roman\', Times, Georgia, serif;font-weight:700;color:#016492;line-height:1.4;display:block;-webkit-line-clamp: 1;-webkit-box-orient: vertical;display: -webkit-box;overflow: hidden;text-transform:capitalize;">' . htmlspecialcode_generator($highlightedResult) . '</a>';
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
                                $ar .= '<a target="_blank" href="' . $HOME_URL . 'tenders-details/' . ($row['ref_no'] ?? '') . '/' . $user_unique_id . '" style="text-decoration:none !important;display:inline-block;padding:8px 16px;border:1.5px solid #cb192d;background-color:#ffffff;color:#cb192d;border-radius:8px;font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;font-weight:700;text-align:center;vertical-align:middle;line-height:1;white-space:nowrap;">';
                                $ar .= '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#cb192d" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:6px;margin-top:-2px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>';
                                $ar .= '<span style="color:#cb192d;font-family:\'DM Sans\', Arial, sans-serif;font-size:14px;font-weight:700;vertical-align:middle;">View Documents</span>';
                                $ar .= '</a></td>';

                                $ar .= '</tr></table>';

                                // Close card table & spacing wrapper
                                $ar .= '</td></tr></table></td></tr></table>';
                            }
                            
                            $template = file_get_contents('../clients/list_email_template.php');
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

                                // Content
                                $subject = "Today’s New Tenders  - $cdate From TENDER18.COM";
                                $mail->isHTML(true); // Set email format to HTML
                                $mail->Subject = "=?UTF-8?B?'".base64_encode($subject)."'?=";
                                $mail->Body = $template;
                              
                                // Send the email
                                //if ($tender_result > 0) {
                                    $mail->send();
                                    sleep(1);
                                //}
                                $mcount++;
                                //echo 'Email sent successfully';
                            } catch (Exception $e) {
                                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }
                        }
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
