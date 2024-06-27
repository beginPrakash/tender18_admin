<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
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
    case 'getUserLiveTendersData':
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
    $condition = "";
    $user_unique_id = $postData['user_unique_id'];
    $token = $postData['token'];
    $user_data = mysqli_query($con, "SELECT * FROM `users` WHERE  `user_unique_id`='$user_unique_id' AND `token`='$token'");

    $user_result = mysqli_num_rows($user_data);
    if ($user_result == 1) {
        while ($row = mysqli_fetch_assoc($user_data)) {
            $keywords = $row['keywords'];
            $words = $row['words'];
            $not_used_keywords = $row['not_used_keywords'];
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
            $whereCondition1 = implode(' or ', $whereClauses1);
            $whereCondition2 = implode(' or ', $whereClauses2);
            $condition = "WHERE (" . $whereCondition . " or " . $whereCondition2 . ") AND (" . $whereCondition1 . ")";
        }

        if (!empty($whereClauses) && empty($whereClauses1) && !empty($whereClauses2)) {
            $whereCondition = implode(' or ', $whereClauses);
            $whereCondition2 = implode(' or ', $whereClauses2);
            $condition = "WHERE (" . $whereCondition .  " or " . $whereCondition2 . ")";
        }

        if (empty($whereClauses) && !empty($whereClauses1) && !empty($whereClauses2)) {
            $whereCondition1 = implode(' or ', $whereClauses1);
            $whereCondition2 = implode(' or ', $whereClauses2);
            $condition = "WHERE (" . $whereCondition2 . ") AND (" . $whereCondition1 . ")";
        }

        if (!empty($whereClauses) && !empty($whereClauses1) && empty($whereClauses2)) {
            $whereCondition = implode(' or ', $whereClauses);
            $whereCondition1 = implode(' or ', $whereClauses1);
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
            $whereCondition1 = implode(' or ', $whereClauses1);
            $condition = "WHERE (" . $whereCondition1 . ")";
        }
    }

    if (!empty($condition)) {
        $condition .= "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
    } else {
        $condition = "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
    }

    function highlightSearchTerm($text, $searchTerm)
    {
        $highlightedTerm = "<b>$searchTerm</b>";
        return str_ireplace($searchTerm, $highlightedTerm, $text);
    }

    $limit = 10;
    $sql_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM `tenders_posts` $condition order by id desc "));
    // $result['main']['sql'] = "SELECT COUNT(*) as total FROM `tenders_posts` $condition order by id desc ";
    $total_query = $sql_query['total'];
    $total = ceil($total_query / $limit);
    $page = isset($postData['page_no']) ? abs((int) $postData['page_no']) : 1;
    $offset = ($page * $limit) - $limit;
    $tender_data = mysqli_query($con, "SELECT * FROM `tenders_posts` $condition order by id desc LIMIT $offset, $limit");
    $tender_result = mysqli_num_rows($tender_data);
    if ($limit > $total_query) {
        $limit = $total_query;
    }
    if ($tender_result > 0) {
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
                foreach ($keywords as $keyword) {
                    $highlightedResult .= highlightSearchTerm($highlightedResult, $keyword);
                }
                foreach ($words as $word) {
                    $highlightedResult = highlightSearchTerm($highlightedResult, $word);
                }
                $result['tenders'][$count]['title'] = htmlspecialcode_generator($highlightedResult);
            } else if (!empty($keywords)) {
                $highlightedResult = $row['title'];
                foreach ($keywords as $keyword) {
                    $highlightedResult = highlightSearchTerm($highlightedResult, $keyword);
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
            $count++;
        }
    } else {
        $result['tenders'] = "No data found";
    }
    if ($user_result != 1) {
        $result = null;
    }
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
