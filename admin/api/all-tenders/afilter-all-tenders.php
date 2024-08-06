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
    case 'getFilterAllTendersData':
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
    $start_date = $_GET['startDate'];
    $timestamp1 = strtotime($start_date);
    $start_date = date("Y-m-d", $timestamp1);

    $end_date = $_GET['endDate'];
    $timestamp2 = strtotime($end_date);
    $end_date = date("Y-m-d", $timestamp2);

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
    $condition = "";
    $cnt = 0;

    if (!empty($filter_ref_no) || !empty($filter_keyword) || !empty($filter_state) || !empty($filter_city) || !empty($filter_agency) || !empty($filter_tender_id) || !empty($filter_due_date) || !empty($filter_tender_value) || !empty($filter_tender_value_to) || !empty($filter_department) || !empty($filter_type) || !empty($start_date) || !empty($end_date)) {
        $condition = "WHERE";
    }

    if (!empty($filter_ref_no)) {
        $condition .= " ref_no='$filter_ref_no'";
        $cnt++;
    }

    if (!empty($filter_keyword)) {
        $filter_keyword = explode(",", $filter_keyword);
        if (!empty($filter_keyword)) {
            $condition_key = "";
            $condition_key_val = "";
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
                        } else {
                            $condition_key .= "title LIKE '%$value%'";
                        }
                        if ($key == ($count - 1)) {
                            $condition_key .= " ) ";
                        }
                    } else {
                        if ($counter > 0) {
                            $condition_key .= " or ";
                        }
                        $condition_key .= "( title LIKE '%$value%' )";
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
            $condition .= " " . $condition_key_val . " (" . $condition_key . " )";
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
            $condition .= " " . $condition_state_val . " (" . $condition_state . " )";
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
            $condition .= " " . $condition_city_val . " (" . $condition_city . " )";
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
            $condition .= " " . $condition_agency_val . " (" . $condition_agency . " )";
        }
    }

    if (!empty($filter_tender_id)) {
        if ($cnt > 0) {
            $condition .= " and tender_id='$filter_tender_id'";
        } else {
            $condition .= " tender_id='$filter_tender_id'";
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
            $condition .= " and due_date between '$start_date' and '$end_date'";
        } else {
            $condition .= " due_date between '$start_date' and '$end_date'";
            $cnt++;
        }
    }

    if (!empty($start_date) && !empty($end_date)) {
        if ($cnt > 0) {
            $condition .= " and due_date between '$start_date' and '$end_date'";
        } else {
            $condition .= " due_date between '$start_date' and '$end_date'";
            $cnt++;
        }
    }

    if (!empty($filter_tender_value) && $filter_tender_value > 0 && !empty($filter_tender_value_to) && $filter_tender_value_to > 0) {
        if ($cnt > 0) {
            $condition .= " and tender_value between $filter_tender_value_to and $filter_tender_value";
        } else {
            $condition .= " tender_value between $filter_tender_value_to and $filter_tender_value";
            $cnt++;
        }
    } elseif ($filter_tender_value_to == '0' && !empty($filter_tender_value) && $filter_tender_value > 0) {
        if (empty($condition)) {
            $condition = "WHERE";
        }
        if ($cnt > 0) {
            $condition .= " and tender_value between 0 and $filter_tender_value";
        } else {
            $condition .= " tender_value between 0 and $filter_tender_value";
            $cnt++;
        }
    } elseif ($filter_tender_value == '0' && $filter_tender_value_to == '0') {
        if (empty($condition)) {
            $condition = "WHERE";
        }
        if ($cnt > 0) {
            $condition .= " and tender_value between 0 and 0";
        } else {
            $condition .= " tender_value between 0 and 0";
            $cnt++;
        }
    }

    if (!empty($filter_department)) {
        $filter_department = explode(",", $filter_department);
        if (!empty($filter_department)) {
            $condition_department = "";
            $condition_department_val = "";
            foreach ($filter_department as $key => $value) {
                if ($cnt > 0) {
                    if ($key > 0) {
                        $condition_department .= " or department LIKE '%$value%'";
                    } else {
                        $condition_department .= " department LIKE '%$value%'";
                        $condition_department_val = " and";
                    }
                } else {
                    $condition_department .= " department LIKE '%$value%'";
                    $cnt++;
                }
            }
            $condition .= " " . $condition_department_val . " (" . $condition_department . " )";
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
            $condition .= " " . $condition_type_val . " (" . $condition_type . " )";
        }
    }

    // $result['condition'] = $condition;

    function highlightSearchTerm($text, $searchTerm)
    {
        $highlightMarkup = '<b>';
        $closingHighlightMarkup = '</b>';
        $highlightedText = preg_replace("/({$searchTerm})/i", $highlightMarkup . '$1' . $closingHighlightMarkup, $text);
        return $highlightedText;
    }

    $whatsapp_no = "";
    $header_data = mysqli_query($con, "SELECT * FROM `header`");
    $header_result = mysqli_num_rows($header_data);
    if ($header_result == 1) {
        while ($row = mysqli_fetch_assoc($header_data)) {
            $whatsapp_no = $row['whatsapp_num'];
        }
    }
    $limit = 10;
    $sql_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM `tenders_all` $condition order by id desc "));
    $result['main']['sql'] = "SELECT * FROM `tenders_all` $condition order by id desc ";
    $total_query = $sql_query['total'];
    $total = ceil($total_query / $limit);
    $page = isset($postData['page_no']) ? abs((int) $postData['page_no']) : 1;
    if (empty($page) || $page < 1) {
        $page = 1;
    }
    $offset = ($page * $limit) - $limit;
    $tender_data = mysqli_query($con, "SELECT * FROM `tenders_all` $condition order by id desc LIMIT $offset, $limit");
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

            $highlightedResult = $row['title'];
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
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
