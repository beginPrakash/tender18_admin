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
    case 'getTenderDetailsData':
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
    $ref_no = $postData['ref_no'];
    $user_unique_id = $postData['user_unique_id'];
    $keywords = "";
    $not_used_keywords = "";
    $words = "";
    $condition = "";

    $whatsapp_no = "";
    $header_data = mysqli_query($con, "SELECT * FROM `header`");
    $header_result = mysqli_num_rows($header_data);
    if ($header_result == 1) {
        while ($row = mysqli_fetch_assoc($header_data)) {
            $whatsapp_no = $row['whatsapp_num'];
        }
    }

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
            $is_view_document = $row['is_view_document'];
            $client_type = $row['client_type'];
        }

        $condition_new = "";

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


        if (!empty($condition)) {
            $condition .= " and `ref_no`='$ref_no'";
        } else {
            $condition .= " WHERE `ref_no`='$ref_no'";
        }

        $tender_data = mysqli_query($con, "SELECT * FROM `tenders_posts` $condition  order by id desc");
        $tender_result = mysqli_num_rows($tender_data);
        if($tender_result==0){
            $tender_data = mysqli_query($con, "SELECT * FROM `tenders_live` $condition order by id desc");
            $tender_result = mysqli_num_rows($tender_data);
            if($tender_result==0){
                $tender_data = mysqli_query($con, "SELECT * FROM `tenders_archive` $condition order by id desc");
                $tender_result = mysqli_num_rows($tender_data);
            }
        }
        // $result['sql'] = "SELECT * FROM `tenders_posts` $condition  order by id desc";
        if ($tender_result > 0) {
            while ($row = mysqli_fetch_assoc($tender_data)) {
                // $result['tenders']['id'] = $row['id'];
                $result['tenders']['tender_id'] = $row['tender_id'];
                $result['tenders']['ref_no'] = $row['ref_no'];
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
                $result['tenders']['city'] = $row['city'];
                $result['tenders']['state'] = $row['state'];
                $result['tenders']['location'] = $location;
                $pincode = "";
                if (empty($row['pincode'])) {
                    $pincode = 'Refer Document';
                } else {
                    $pincode = $row['pincode'];
                }
                $result['tenders']['pincode'] = $pincode;
                $result['tenders']['title'] = htmlspecialcode_generator($row['title']);
                $result['tenders']['description'] = htmlspecialcode_generator($row['description']);
                $result['tenders']['agency'] = htmlspecialcode_generator($row['agency_type']);
                $result['tenders']['publish_date'] = date('M d, Y', strtotime($row['publish_date']));
                $result['tenders']['due_date'] = date('M d, Y', strtotime($row['due_date']));
                $result['tenders']['opening_date'] = date('M d, Y', strtotime($row['opening_date']));
                $result['tenders']['is_view_document'] = $is_view_document;
                $result['tenders']['client_type'] = $client_type;
                
                $tender_value = "";
                if (empty($row['tender_value']) && $row['tender_value'] > 0) {
                    $tender_value = 'Refer Document';
                } else {
                    $tender_value = $row['tender_value'];
                }
                $result['tenders']['tender_value'] = $tender_value;
                $tender_fee = "";
                if (empty($row['tender_fee']) && $row['tender_fee'] > 0) {
                    $tender_fee = 'Refer Document';
                } else {
                    $tender_fee = $row['tender_fee'];
                }
                $result['tenders']['tender_fee'] = $tender_fee;
                $tender_emd = "";
                if (empty($row['tender_emd']) && $row['tender_emd'] > 0) {
                    $tender_emd = 'Refer Document';
                } else {
                    $tender_emd = $row['tender_emd'];
                }
                $result['tenders']['tender_emd'] = $tender_emd;

                $documents = explode(",", $row['documents']);
                $result['tenders']['documents'] = $documents;

                $result['tenders']['whatsapp_no'] = $whatsapp_no;
            }
        } else {
            $result['tenders'] = "No data found";
        }
    } else {
        $tender_data = mysqli_query($con, "SELECT * FROM `tenders_posts` WHERE `ref_no`='$ref_no' order by id desc");
        $tender_result = mysqli_num_rows($tender_data);
        if($tender_result==0){
            $tender_data = mysqli_query($con, "SELECT * FROM `tenders_live` WHERE `ref_no`='$ref_no' order by id desc");
            $tender_result = mysqli_num_rows($tender_data);
            if($tender_result==0){
                $tender_data = mysqli_query($con, "SELECT * FROM `tenders_archive` WHERE `ref_no`='$ref_no' order by id desc");
                $tender_result = mysqli_num_rows($tender_data);
            }
        }
        // $result['sql'] = "SELECT * FROM `tenders_posts` `ref_no`='$ref_no'  order by id desc";
        if ($tender_result > 0) {
            while ($row = mysqli_fetch_assoc($tender_data)) {
                // $result['tenders']['id'] = $row['id'];
                $result['tenders']['tender_id'] = $row['tender_id'];
                $result['tenders']['ref_no'] = $row['ref_no'];
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
                $result['tenders']['city'] = $row['city'];
                $result['tenders']['tend_city'] = str_replace(' ', '-', $row['city']).'-tenders';
                $result['tenders']['state'] = $row['state'];
                $result['tenders']['tend_state'] = str_replace(' ', '-', $row['state']).'-tenders';
                $result['tenders']['location'] = $location;
                $pincode = "";
                if (empty($row['pincode'])) {
                    $pincode = 'Refer Document';
                } else {
                    $pincode = $row['pincode'];
                }
                $result['tenders']['pincode'] = $pincode;
                $result['tenders']['title'] = htmlspecialcode_generator($row['title']);
                $result['tenders']['description'] = htmlspecialcode_generator($row['description']);
                $result['tenders']['agency'] = htmlspecialcode_generator($row['agency_type']);
                $result['tenders']['tend_agency'] = str_replace(' ', '-', htmlspecialcode_generator($row['agency_type'])).'-tenders';
                $result['tenders']['publish_date'] = date('M d, Y', strtotime($row['publish_date']));
                $result['tenders']['due_date'] = date('M d, Y', strtotime($row['due_date']));
                $result['tenders']['opening_date'] = date('M d, Y', strtotime($row['opening_date']));
                $result['tenders']['is_view_document'] = '';
                $result['tenders']['client_type'] = '';
                $tender_value = "";
                if (empty($row['tender_value']) && $row['tender_value'] > 0) {
                    $tender_value = 'Refer Document';
                } else {
                    $tender_value = $row['tender_value'];
                }
                $result['tenders']['tender_value'] = $tender_value;
                $tender_fee = "";
                if (empty($row['tender_fee']) && $row['tender_fee'] > 0) {
                    $tender_fee = 'Refer Document';
                } else {
                    $tender_fee = $row['tender_fee'];
                }
                $result['tenders']['tender_fee'] = $tender_fee;
                $tender_emd = "";
                if (empty($row['tender_emd']) && $row['tender_emd'] > 0) {
                    $tender_emd = 'Refer Document';
                } else {
                    $tender_emd = $row['tender_emd'];
                }
                $result['tenders']['tender_emd'] = $tender_emd;

                $result['tenders']['documents'] = [];

                $result['tenders']['whatsapp_no'] = $whatsapp_no;
            }
        } else {
            $result['tenders'] = "No data found";
        }
    }
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
