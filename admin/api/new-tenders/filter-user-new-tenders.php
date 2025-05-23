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
    case 'getFilterUserNewTendersData':
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
    $filter_ref_no = $postData['ref_no'];
    $filter_keyword = $postData['keyword'];
    $filter_state = $postData['state'];
    $filter_city = $postData['city'];
    $filter_agency = $postData['agency'];
    $filter_tender_id = $postData['tender_id'];
    $filter_due_date = $postData['due_date'];
    $filter_tender_value = $postData['tender_value'];
    $filter_tender_value_to = $postData['tender_value_to'];
    $filter_department = $postData['department'];
    $filter_type = $postData['type'];
    $keyw = $postData['keyword'];
    $condition_filter = "";
    $condition = "";
    $cnt = 0;

    if (!empty($filter_ref_no) || !empty($filter_keyword) || !empty($filter_state) || !empty($filter_city) || !empty($filter_agency) || !empty($filter_tender_id) || !empty($filter_due_date) || !empty($filter_tender_value) || !empty($filter_tender_value_to) || !empty($filter_department) || !empty($filter_type)) {
        $condition_filter = "WHERE";
    }

    if (!empty($filter_ref_no)) {
        $condition_filter .= " ref_no='$filter_ref_no'";
        $cnt++;
    }

    // if (!empty($filter_keyword)) {
    //     $filter_keyword = trim($filter_keyword);
    //     $boolean_mode_keyword = "+" . str_replace(" ", " +", $filter_keyword); // Convert to Boolean Mode format
    //     $condition_key = "";
    //     $ucondition_key = "";
        
    //     // Build the MATCH AGAINST condition
    //     $condition_key = "MATCH(title, description) AGAINST('$boolean_mode_keyword' IN BOOLEAN MODE)";
    //     $ucondition_key = "MATCH(title) AGAINST('$boolean_mode_keyword' IN BOOLEAN MODE)";
        
    //     // Final WHERE clauses
    //     $condition_filter .= " $condition_key";
    //     $filter_keyword = explode(",", $filter_keyword);
    //     $cnt++;
    // }

    if (!empty($filter_keyword)) {
        $filter_keyword = explode(",", $filter_keyword);
        if (!empty($filter_keyword)) {
            $condition_key = "";
            $condition_key_val = "";
            $ucondition_key = "";
            $ucondition_key_val = "";
            $notq = "";
            $counter = 0;
            if ($cnt > 0) {
                $condition_key_val = "and";
            }
            foreach ($filter_keyword as $keyword) {
                $keyword_arr = explode(' ', $keyword);
                $count = count($keyword_arr);
                foreach ($keyword_arr as $key => $value) {
                    if ($count > 1) {
                        if ($counter > 0 && $key <= 0) {
                            $condition_key .= " or ";
                        }
                        if ($key == 0) {
                            $condition_key .= " ( ";
                        }
                        if ($key > 0) {
                            $condition_key .= " and title LIKE '%$value%'";
                            $ucondition_key .= " and title LIKE '%$value%' and title NOT LIKE '%$value%'";
                        } else {
                            $condition_key .= "title LIKE '%$value%'";
                            $ucondition_key .= "title LIKE '%$value%'";
                        }
                        if ($key == ($count - 1)) {
                            $condition_key .= " ) ";
                        }
                    } else {
                        if ($counter > 0) {
                            $condition_key .= " or ";
                            $ucondition_key .= " or ";
                        }
                        $condition_key .= "( title LIKE '%$value%' )";
                        $ucondition_key .= "( title LIKE '%$value%' )";
                    }
                    $counter++;
                    $cnt++;
                }
            }

            $counter = 0;
            if ($cnt > 0) {
                $condition_key .= " or ";
            }
            foreach ($filter_keyword as $keyword) {
                $keyword_arr = explode(' ', $keyword);
                $count = count($keyword_arr);
                foreach ($keyword_arr as $key => $value) {
                    if ($count > 1) {
                        if ($counter > 0 && $key <= 0) {
                            $condition_key .= " or ";
                        }
                        if ($key == 0) {
                            $condition_key .= " ( ";
                        }
                        if ($key > 0) {
                            $condition_key .= " and description LIKE '%$value%'";
                        } else {
                            $condition_key .= "description LIKE '%$value%'";
                        }
                        if ($key == ($count - 1)) {
                            $condition_key .= " ) ";
                        }
                    } else {
                        if ($counter > 0) {
                            $condition_key .= " or ";
                        }
                        $condition_key .= "( description LIKE '%$value%' )";
                    }
                    $counter++;
                    $cnt++;
                }
            }
            $condition_filter .= " " . $condition_key_val . " (" . $condition_key . " )";
            $condition_u .= " WHERE (" . $ucondition_key . " ) AND title NOT LIKE '%$keyw%'";
        }
    }

    if (!empty($filter_state)) {
        $filter_state = explode(",", $filter_state);
        if (!empty($filter_state)) {
            $condition_state = "";
            $condition_state_val = "";
            foreach ($filter_state as $key => $value) {
                if ($cnt > 0) {
                    if ($key > 0) {
                        $condition_state .= " or state='$value'";
                    } else {
                        $condition_state .= " state='$value'";
                        $condition_state_val = " and";
                    }
                } else {
                    $condition_state .= " state='$value'";
                    $cnt++;
                }
            }
            $condition_filter .= " " . $condition_state_val . " (" . $condition_state . " )";
        }
    }

    if (!empty($filter_city)) {
        $filter_city = explode(",", $filter_city);
        if (!empty($filter_city)) {
            $condition_city = "";
            $condition_city_val = "";
            foreach ($filter_city as $key => $value) {
                if ($cnt > 0) {
                    if ($key > 0) {
                        $condition_city .= " or city='$value'";
                    } else {
                        $condition_city .= " city='$value'";
                        $condition_city_val = " and";
                    }
                } else {
                    $condition_city .= " city='$value'";
                    $cnt++;
                }
            }
            $condition_filter .= " " . $condition_city_val . " (" . $condition_city . " )";
        }
    }

    if (!empty($filter_agency)) {
        $filter_agency = explode(",", $filter_agency);
        if (!empty($filter_agency)) {
            $condition_agency = "";
            $condition_agency_val = "";
            foreach ($filter_agency as $key => $value) {
                if ($cnt > 0) {
                    if ($key > 0) {
                        $condition_agency .= " or agency_type LIKE '%$value%'";
                    } else {
                        $condition_agency .= " agency_type LIKE '%$value%'";
                        $condition_agency_val = " and";
                    }
                } else {
                    $condition_agency .= " agency_type LIKE '%$value%'";
                    $cnt++;
                }
            }
            $condition_filter .= " " . $condition_agency_val . " (" . $condition_agency . " )";
        }
    }

    if (!empty($filter_tender_id)) {
        if ($cnt > 0) {
            $condition_filter .= " and tender_id='$filter_tender_id'";
        } else {
            $condition_filter .= " tender_id='$filter_tender_id'";
            $cnt++;
        }
    }

    if (!empty($filter_due_date)) {
        $filter_due_date = explode(" ~ ", $filter_due_date);
        $start_date = $filter_due_date[0];
        $timestamp1 = strtotime($start_date);
        $start_date = date("Y-m-d", $timestamp1);

        $end_date = $filter_due_date[1];
        $timestamp2 = strtotime($end_date);
        $end_date = date("Y-m-d", $timestamp2);
        if ($cnt > 0) {
            $condition_filter .= " and due_date between '$start_date' and '$end_date'";
        } else {
            $condition_filter .= " due_date between '$start_date' and '$end_date'";
            $cnt++;
        }
    }

    if (!empty($filter_tender_value) && $filter_tender_value > 0 && !empty($filter_tender_value_to) && $filter_tender_value_to > 0) {
        if ($cnt > 0) {
            $condition_filter .= " and tender_value between $filter_tender_value_to and $filter_tender_value";
        } else {
            $condition_filter .= " tender_value between $filter_tender_value_to and $filter_tender_value";
            $cnt++;
        }
    } elseif ($filter_tender_value_to == '0' && !empty($filter_tender_value) && $filter_tender_value > 0) {
        if (empty($condition_filter)) {
            $condition_filter = "WHERE";
        }
        if ($cnt > 0) {
            $condition_filter .= " and tender_value between 0 and $filter_tender_value";
        } else {
            $condition_filter .= " tender_value between 0 and $filter_tender_value";
            $cnt++;
        }
    } elseif ($filter_tender_value == '0' && $filter_tender_value_to == '0') {
        if (empty($condition_filter)) {
            $condition_filter = "WHERE";
        }
        if ($cnt > 0) {
            $condition_filter .= " and tender_value between 0 and 0";
        } else {
            $condition_filter .= " tender_value between 0 and 0";
            $cnt++;
        }
    }

    if (!empty($filter_department)) {
        $filter_department = explode(",", $filter_department);
        if (!empty($filter_department)) {
            $condition_department = "";
            $condition_department_val = "";
            foreach ($filter_department as $key => $value) {
                $dep_name = str_replace(" Tenders", "",$value);
                if ($cnt > 0) {
                    if ($key > 0) {
                        $condition_department .= " or department LIKE '%$dep_name%'";
                    } else {
                        $condition_department .= " department LIKE '%$dep_name%'";
                        $condition_department_val = " and";
                    }
                } else {
                    $condition_department .= " department LIKE '%$dep_name%'";
                    $cnt++;
                }
            }
            $condition_filter .= " " . $condition_department_val . " (" . $condition_department . " )";
        }
    }

    if (!empty($filter_type)) {
        $filter_type = explode(",", $filter_type);
        if (!empty($filter_type)) {
            $condition_type = "";
            $condition_type_val = "";
            foreach ($filter_type as $key => $value) {
                if ($cnt > 0) {
                    if ($key > 0) {
                        $condition_type .= " or tender_type LIKE '%$value%'";
                    } else {
                        $condition_type .= " tender_type LIKE '%$value%'";
                        $condition_type_val = " and";
                    }
                } else {
                    $condition_type .= " tender_type LIKE '%$value%'";
                    $cnt++;
                }
            }
            $condition_filter .= " " . $condition_type_val . " (" . $condition_type . " )";
        }
    }

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
    $user_unique_id = $postData['user_unique_id'];
    $token = $postData['token'];
    $user_data = mysqli_query($con, "SELECT * FROM `users` WHERE  `user_unique_id`='$user_unique_id' AND `token`='$token'");

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
        }

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


        // // Initialize components for the WHERE clause
        // $match_conditions = [];
        // $not_match_conditions = [];
        // $additional_conditions = [];

        // // Process $keywords if provided
        // if (!empty($keywords)) {
        //     $keywords = explode(',', $keywords);
        //     foreach ($keywords as $keyword) {
        //         $match_conditions[] = '+' . str_replace(' ', ' +', trim($keyword)); // Format for BOOLEAN MODE
        //     }
        // }

        // // Process $words if provided
        // if (!empty($words)) {
        //     $words = explode(',', $words);
        //     foreach ($words as $word) {
        //         $match_conditions[] = '+' . str_replace(' ', ' +', trim($word));
        //     }
        // }

        // // Combine match conditions for keywords and words
        // if (!empty($match_conditions)) {
        //     // Match conditions for inclusion
        //     $positive_conditions = [];
        //     // Example: For '+cctv +camera' and '+ecg +machine'
        //     $positive_keywords = $match_conditions;
        //     foreach ($positive_keywords as $positive_keyword) {
        //         $positive_conditions[] = "MATCH(title, description) AGAINST('$positive_keyword' IN BOOLEAN MODE)";
        //     }

        //     // Combine all match conditions
        //     $additional_conditions[] = '(' . implode(' OR ', $positive_conditions) . ')';
        // }

        // // Process $not_used_keywords if provided
        // if (!empty($not_used_keywords)) {
        //     $not_used_keywords = explode(',', $not_used_keywords);
        //     foreach ($not_used_keywords as $not_keyword) {
        //         $not_match_conditions[] = '+' . str_replace(' ', ' +', trim($not_keyword));
        //     }

        //     if (!empty($not_match_conditions)) {
        //         // Match conditions for exclusion
        //         $negative_conditions = [];
        //         // Example: For '+ptz +cameras' and '+hard +disk'
        //         $negative_keywords = $not_match_conditions;
        //         foreach ($negative_keywords as $negative_keyword) {
        //             $negative_conditions[] = "MATCH(title, description) AGAINST('$negative_keyword' IN BOOLEAN MODE)";
        //         }

        //         // Combine all NOT match conditions
        //         $additional_conditions[] = 'NOT (' . implode(' OR ', $negative_conditions) . ')';
        //     }
        // }

        // if (!empty($additional_conditions)) {
        //     // Combine all conditions into the WHERE clause
        //     $condition = "WHERE " . implode(" AND ", $additional_conditions);
        // }

        if (empty($condition) && !empty($condition_new)) {
            $condition = preg_replace('/and/', 'where', $condition_new, 1);
        } else {
            $condition .= $condition_new;
        }
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

    if (!empty($condition) && !empty($condition_filter)) {
        $condition_filter = str_replace("WHERE", "AND", $condition_filter);
    }

    $limit = 10;
    $sql_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM `tenders_posts` $condition $condition_filter"));
    // $result['main']['sql'] = "SELECT * FROM `tenders_posts` $condition $condition_filter";
    $total_query = $sql_query['total'];
    $total = ceil($total_query / $limit);
    $page = isset($postData['page_no']) ? abs((int) $postData['page_no']) : 1;
    if (empty($page) || $page < 1) {
        $page = 1;
    }
    $offset = ($page * $limit) - $limit;
    $order_query = '';
    $order_key_val = '';
    $condition_orderque = '';
    $counter = 0;
    $cnt = 0;
    $g =1 ;
    $filter_keywords = explode(" ", $keyw);

    foreach ($filter_keywords as $keyword) {
        $keyword_arr = explode(' ', $keyword);
        $count = count($keyword_arr);
        foreach ($keyword_arr as $key => $value) {
            if ($counter == 0 && $key <= 0) {
                $order_key_val .= " ORDER BY CASE WHEN title LIKE '%$keyw%' THEN 0";
            } 
                $order_query .= " WHEN title LIKE '%$value%' THEN $g";
            
            $counter++;
            $cnt++;
            $g++;
        }
        
    }
    if($order_key_val != ''){
        $condition_orderque .= " " . $order_key_val . "  " . $order_query;
    }
    $keywords_arr = explode(' ', $keyw);
    $k_count = count($keywords_arr);
    $condition_orderque .= " ELSE " . $k_count . " END, title ASC";
    if(!empty($keyw)):
        // $tender_data = mysqli_query($con, "SELECT `ref_no`,`city`,`state`,`pincode`,`title`,`agency_type`,`publish_date`,`due_date`,`tender_value`,`tender_fee`,`tender_emd` FROM `tenders_posts` $condition $condition_filter $condition_orderque LIMIT $offset, $limit");

        $tender_data = mysqli_query($con, "(SELECT * FROM `tenders_posts` $condition $condition_filter) UNION ALL (SELECT * FROM `tenders_posts` $condition_u $condition_filter) $condition_orderque LIMIT $offset, $limit");
    else:
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
            $tender_data = mysqli_query($con, "SELECT `ref_no`,`department`,`city`,`state`,`pincode`,`title`,`agency_type`,`publish_date`,`due_date`,`tender_value`,`tender_fee`,`tender_emd` FROM `tenders_posts` $condition $condition_filter $condition_orderque_key LIMIT $offset, $limit");

            // $tender_data = mysqli_query($con, "SELECT * FROM `tenders_posts` $condition $condition_filter $condition_orderque_key LIMIT $offset, $limit");
        
        else:
            $tender_data = mysqli_query($con, "SELECT `ref_no`,`department`,`city`,`state`,`pincode`,`title`,`agency_type`,`publish_date`,`due_date`,`tender_value`,`tender_fee`,`tender_emd` FROM `tenders_posts` $condition $condition_filter $condition_orderque order by publish_date desc LIMIT $offset, $limit");

            // $tender_data = mysqli_query($con, "SELECT * FROM `tenders_posts` $condition $condition_filter $condition_orderque order by publish_date desc LIMIT $offset, $limit");
        endif;
        
    endif;
        //echo "(SELECT * FROM `tenders_posts` $condition $condition_filter) UNION ALL (SELECT * FROM `tenders_posts` $condition $condition_filter) $condition_orderque_key LIMIT $offset, $limit";exit;
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

            $result_title = "";
            if (!empty($keywords) && !empty($words)) {
                $highlightedResult = $row['title'];
                foreach ($words as $word) {
                    $highlightedResult = highlightSearchTerm($highlightedResult, $word);
                }
                foreach ($keywords as $keyword) {
                    $keyword_arr = explode(' ', $keyword);
                    foreach ($keyword_arr as $key) {
                        if(!empty($key)):
                            $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                        endif;
                    }
                }
                $result_title = htmlspecialcode_generator($highlightedResult);
            } else if (!empty($keywords)) {
                $highlightedResult = $row['title'];
                foreach ($keywords as $keyword) {
                    $keyword_arr = explode(' ', $keyword);
                    foreach ($keyword_arr as $key) {
                        if(!empty($key)):
                            $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                        endif;
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
                        if(!empty($key)):
                            $keyword_arr[] = $key;
                        endif;
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

            $result['tenders'][$count]['title'] = htmlspecialcode_generator($highlightedResult);

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
