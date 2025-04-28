<?php
include '../includes/connection.php';
include '../includes/functions.php';
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
    case 'getExternalLinkLiveTendersData':
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
    // return $con;
    $search = isset($postData['search']) ? $postData['search'] : '';

    $whatsapp_no = "";
    $header_data = mysqli_query($con, "SELECT * FROM `header`");
    $header_result = mysqli_num_rows($header_data);
    if ($header_result == 1) {
        while ($row = mysqli_fetch_assoc($header_data)) {
            $whatsapp_no = $row['whatsapp_num'];
        }
    }

    $keywords = "";
    $not_used_keywords = "";
    $words = "";
    $city = "";
    $state = "";
    $tenderValue = "";
    $agency = "";
    $department = "";
    $type = "";
    $condition = "";
    $condition_new = "";
    $user_name = "";
    $user_email = "";
    $custom_care_number = "";
    $tech_person_name = "";
    $tech_person_number = "";
    $uname="";
    $uemail="";
    $cname="";
    $mno="";
    $expired_date="";
    $ustatus="";
    $user_unique_id = $postData['user_unique_id'];
    $user_data = mysqli_query($con, "SELECT * FROM `users` WHERE  `user_unique_id`='$user_unique_id'");

    $user_result = mysqli_num_rows($user_data);
    if ($user_result == 1) {
        while ($row = mysqli_fetch_assoc($user_data)) {
            $keywords = $row['keywords'];
            $words = $row['words'];
            $not_used_keywords = $row['not_used_keywords'];
            $city = $row['filter_city'];
            $state = $row['filter_state'];
            $tenderValue = $row['filter_tender_value'];
            $agency = $row['filter_agency'];
            $department = $row['filter_department'];
            $type = $row['filter_type'];
            $custom_care_number = $row['custom_care_number'];
            $tech_person_name = $row['tech_person_name'];
            $tech_person_number = $row['tech_person_number'];
            $uname = $row['customer_name'];
            $uemail = $row['users_email'];
            $cname = $row['company_name'];
            $mno = $row['mobile_number'];
            $expired_date = date('M d, Y',strtotime($row['expired_date']));
            $ustatus = $row['status'];
        }

        $result['custom_care_number'] = $custom_care_number;
        $result['tech_person_name'] = $tech_person_name;
        $result['tech_person_number'] = $tech_person_number;
        $result['uname'] = $uname;
        $result['uemail'] = $uemail;
        $result['mno'] = $mno;
        $result['expired_date'] = $expired_date;
        $result['ustatus'] = $ustatus;
        $result['cname'] = $cname;

        if (!empty($city)) {
            $city = explode(",", $city);
            if (!empty($city)) {
                $condition_city = "";
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

        if (!empty($state)) {
            $state = explode(",", $state);
            if (!empty($state)) {
                $condition_state = "";
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

        if (!empty($agency)) {
            $agency = explode(",", $agency);
            if (!empty($agency)) {
                $condition_agency = "";
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

        if (!empty($department)) {
            $department = explode(",", $department);
            if (!empty($department)) {
                $condition_department = "";
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

        if (!empty($type)) {
            $type = explode(",", $type);
            if (!empty($type)) {
                $condition_type = "";
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
    }

    // if (!empty($condition)) {
    //     $condition .= "AND NOW() BETWEEN created_at + INTERVAL 1 DAY AND due_date";
    // } else {
    //     $condition = "WHERE NOW() BETWEEN created_at + INTERVAL 1 DAY AND due_date";
    // }

    if (!empty($search)) {
        $condition .= " AND ( title LIKE '%$search%' OR ref_no LIKE '%$search%' OR tender_id LIKE '%$search%' OR agency_type LIKE '%$search%' )";
    }

    function highlightSearchTerm($text, $searchTerm)
    {
        // $highlightedTerm = "<b>$searchTerm</b>";
        // return str_ireplace($searchTerm, $highlightedTerm, $text);

        $highlightMarkup = '<b>';
        $closingHighlightMarkup = '</b>';
        $highlightedText = preg_replace("/({$searchTerm})/i", $highlightMarkup . '$1' . $closingHighlightMarkup, $text);
        return $highlightedText;
    }

    $limit = 10;
    $sql_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM `tenders_live` $condition order by id desc "));
    // $result['main']['sql'] = "SELECT * FROM `tenders_posts` $condition order by id desc ";
    $total_query = $sql_query['total'];
    $total = ceil($total_query / $limit);
    $page = isset($postData['page_no']) ? abs((int) $postData['page_no']) : 1;
    $offset = ($page * $limit) - $limit;
    // echo "SELECT * FROM `tenders_live` $condition order by id desc LIMIT $offset, $limit";
    $kcounter = 0;
        $ks=0;
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
        if(!empty($keywords)):
            $tender_data = mysqli_query($con, "SELECT * FROM `tenders_live` $condition $condition_orderque_key LIMIT $offset, $limit");
        else:
            $tender_data = mysqli_query($con, "SELECT * FROM `tenders_live` $condition order by id desc LIMIT $offset, $limit");
        endif;
    
    $tender_result = mysqli_num_rows($tender_data);
    if ($limit > $total_query) {
        $limit = $total_query;
    }
    if ($tender_result > 0) {
        $result['user_name'] = $user_name;
        $result['user_email'] = $user_email;
        $count = 1;
        while ($row = mysqli_fetch_assoc($tender_data)) {
            // $result['tenders'][$count]['id'] = $row['id'];
            $result['tenders'][$count]['ref_no'] = $row['ref_no'];
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
            $result['tenders'][$count]['location'] = $location;
            $pincode = "";
            if (empty($row['pincode'])) {
                $pincode = 'Refer Document';
            } else {
                $pincode = $row['pincode'];
            }
            $result['tenders'][$count]['pincode'] = $pincode;
            // $result['tenders'][$count]['title'] = $row['title'];

            if (!empty($keywords) && !empty($words)) {
                $highlightedResult = $row['title'];
                foreach ($words as $word) {
                    $highlightedResult = highlightSearchTerm($highlightedResult, $word);
                }
                foreach ($keywords as $keyword) {
                    $keyword_arr = explode(' ', $keyword);
                    foreach ($keyword_arr as $key) {
                        if($key != ''){
                            $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                        }
                    }
                }
                $result['tenders'][$count]['title'] = htmlspecialcode_generator($highlightedResult);
            } else if (!empty($keywords)) {
                $highlightedResult = $row['title'];
                foreach ($keywords as $keyword) {
                    $keyword_arr = explode(' ', $keyword);
                    foreach ($keyword_arr as $key) {
                        if($key != ''){
                            $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                        }
                    }
                }
                $result['tenders'][$count]['title'] = htmlspecialcode_generator($highlightedResult);
            } else if (!empty($words)) {
                $highlightedResult = $row['title'];
                foreach ($words as $word) {
                    $highlightedResult = highlightSearchTerm($highlightedResult, $word);
                }
                $result['tenders'][$count]['title'] = htmlspecialcode_generator($highlightedResult);
            } else {
                $result['tenders'][$count]['title'] = htmlspecialcode_generator($row['title']);
            }

            $result['tenders'][$count]['agency'] = htmlspecialcode_generator($row['agency_type']);
            $result['tenders'][$count]['publish_date'] = date('M d, Y', strtotime($row['publish_date']));
            $result['tenders'][$count]['due_date'] = date('M d, Y', strtotime($row['due_date']));
            $tender_value = "";
            if (empty($row['tender_value']) && $row['tender_value'] > 0) {
                $tender_value = 'Refer Document';
            } else {
                $tender_value = $row['tender_value'];
            }
            $result['tenders'][$count]['tender_value'] = $tender_value;
            $tender_fee = "";
            if (empty($row['tender_fee']) && $row['tender_fee'] > 0) {
                $tender_fee = 'Refer Document';
            } else {
                $tender_fee = $row['tender_fee'];
            }
            $result['tenders'][$count]['tender_fee'] = $tender_fee;
            $tender_emd = "";
            if (empty($row['tender_emd']) && $row['tender_emd'] > 0) {
                $tender_emd = 'Refer Document';
            } else {
                $tender_emd = $row['tender_emd'];
            }
            $result['tenders'][$count]['tender_emd'] = $tender_emd;
            $result['tenders'][$count]['documents'] = "#";
            $result['tenders'][$count]['whatsapp_no'] = $whatsapp_no;
            $result['tenders'][$count]['dep_type'] = $row['department'];
            $count++;
        }
    } else {
        $result['tenders'] = [];
    }
    if ($total > 1) {
        if ($page == 2) {
            $result['links'][] = ($page - 1);
        }
        if ($page > 2) {
            $result['links'][] = 1;
            if ($page > 3) {
                $result['links'][] = '...';
            }
        }
        for ($i = max(2, $page - 2); $i < $page; $i++) {
            $result['links'][] = $i;
        }
        $result['links'][$page] = "<b>" . $page . "</b>";
        for ($i = $page + 1; $i <= min($total - 1, $page + 2); $i++) {
            $result['links'][] = $i;
        }
        if ($page < $total - 1) {
            if ($page < $total - 2) {
                $result['links'][] = '...';
            }
            $result['links'][] = $total;
        }
        if ($page == $total - 1) {
            $result['links'][] = ($page + 1);
        }
    } else {
        $result['links'] = [];
    }
    if ($user_result != 1) {
        $result = null;
    }
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result),JSON_PARTIAL_OUTPUT_ON_ERROR);
}
die();
