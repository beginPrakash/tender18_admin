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
    case 'getAFilterAllTendersData':
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
    $metaState = $postData['metaState'];
    $metaCity = $postData['metaCity'];
    $metaKeyword = $postData['metaKeyword'];
    $metaAgency = $postData['metaAgency']; 
    $metaAgencyStr = str_replace('-', ' ', $metaAgency);
    // $formatted = strrchr($metaAgencyStrrepl,' ');
    // $metaAgencyStr= preg_replace('/\W\w+\s*(\W*)$/', '$1', $metaAgencyStrrepl);

    //$metaAgencyStr = $withoutLast.' -'. strtoupper($formatted);

    $metaDepartment = $postData['metaDepartment'];
    $keyw = $postData['keyword'];
    $condition = "";
    $cnt = 0;
    $meta_arr = [];
    $deptmeta_arr = [];
    $agencymeta_arr = [];
    $citymeta_arr = [];
    $agmeta_arr=[];
    $keymeta_arr=[];

    if (!empty($filter_ref_no) || !empty($filter_keyword) || !empty($filter_state) || !empty($filter_city) || !empty($filter_agency) || !empty($filter_tender_id) || !empty($filter_due_date) || !empty($filter_tender_value) || !empty($filter_tender_value_to) || !empty($filter_department) || !empty($filter_type) || !empty($start_date)) {
        $condition = "WHERE";
    }

    if (!empty($filter_ref_no)) {
        $condition .= " ref_no='$filter_ref_no'";
        $cnt++;
    }

    if(!empty($metaState)){
        $state_data = mysqli_query($con, "SELECT `name`,`title`,`description`,`keywords`,`h1`,`content` FROM `states` where name LIKE '%$metaState%'");
        $state_result = mysqli_num_rows($state_data);
        if ($state_result == 1) {
            while ($row = mysqli_fetch_assoc($state_data)) {
                $meta_arr =  $row;
            }
        }
    }

    if(!empty($metaAgencyStr)){
        //echo "SELECT `agency_name` FROM `tender_agencies` where agency_name LIKE '%$metaAgencyStr' order by `id` desc limit 1";exit;
        $state_data = mysqli_query($con, "SELECT `agency_name` FROM `tender_agencies` where agency_name LIKE '%$metaAgencyStr%' order by `id` desc limit 1");
        $state_result = mysqli_num_rows($state_data);
        if ($state_result == 1) {
            while ($row = mysqli_fetch_assoc($state_data)) {
                $agencymeta_arr =  $row;
            }
        }

        $ameta_data = mysqli_query($con, "SELECT * FROM `agency_meta_content` where id = 1 ");
        $ameta_result = mysqli_num_rows($ameta_data);
        if ($ameta_result == 1) {
            while ($row = mysqli_fetch_assoc($ameta_data)) {
                $agmeta_arr =  $row;
            }
        }
    }

    if(!empty($metaCity)){
        $city_data = mysqli_query($con, "SELECT * FROM `city_meta_content` where id = 1 ");
        $city_result = mysqli_num_rows($city_data);
        if ($city_result == 1) {
            while ($row = mysqli_fetch_assoc($city_data)) {
                $citymeta_arr =  $row;
            }
        }
    }

    if(!empty($metaKeyword)){
        $keyw_data = mysqli_query($con, "SELECT * FROM `keyword_meta_content` where id = 1 ");
        $keyw_result = mysqli_num_rows($keyw_data);
        if ($keyw_result == 1) {
            while ($row = mysqli_fetch_assoc($keyw_data)) {
                $keymeta_arr =  $row;
            }
        }
    }

    if(!empty($metaDepartment)){
        $dept_data = mysqli_query($con, "SELECT `name`,`title`,`description`,`keywords`,`h1`,`content` FROM `departments` where name LIKE '%$metaDepartment%'");
        $dept_result = mysqli_num_rows($dept_data);
        if ($dept_result == 1) {
            while ($row = mysqli_fetch_assoc($dept_data)) {
                $deptmeta_arr =  $row;
            }
        }
    }

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
            $condition .= " " . $condition_key_val . " (" . $condition_key . " )";
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
            $condition .= " and publish_date between '$start_date' and '$end_date'";
        } else {
            $condition .= " publish_date between '$start_date' and '$end_date'";
            $cnt++;
        }
    }

    if (!empty($start_date) && !empty($end_date)) {
        if ($cnt > 0) {
            $condition .= " and publish_date between '$start_date' and '$end_date'";
        } else {
            $condition .= " publish_date between '$start_date' and '$end_date'";
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
    $order_query = '';
    $order_key_val = '';
    $condition_orderque = '';
    $counter = 0;
    $cnt = 0;
    $g =1 ;
    $filter_keyword = explode(" ", $keyw);

    foreach ($filter_keyword as $keyword) {
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
        $s_condition = str_replace("WHERE","and",$condition);
        $tender_data = mysqli_query($con, "(SELECT `ref_no`,`city`,`state`,`pincode`,`title`,`agency_type`,`publish_date`,`due_date`,`tender_value`,`tender_fee`,`tender_emd` FROM `tenders_all` $condition ) UNION ALL (SELECT `ref_no`,`city`,`state`,`pincode`,`title`,`agency_type`,`publish_date`,`due_date`,`tender_value`,`tender_fee`,`tender_emd` FROM `tenders_all` $condition_u $s_condition) $condition_orderque LIMIT $offset, $limit");
    else:
        $tender_data = mysqli_query($con, "SELECT `ref_no`,`city`,`state`,`pincode`,`title`,`agency_type`,`publish_date`,`due_date`,`tender_value`,`tender_fee`,`tender_emd` FROM `tenders_all` $condition $condition_orderque LIMIT $offset, $limit");
       
    endif;
    //echo"SELECT * FROM `tenders_all` $condition $condition_orderque LIMIT $offset, $limit";exit;
    //echo "(SELECT * FROM `tenders_all` $condition ) UNION ALL (SELECT * FROM `tenders_all` $condition_u $s_condition) $condition_orderque LIMIT $offset, $limit";exit;
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
    if(!empty($meta_arr)){
        $result['meta']['title'] = $meta_arr['title'];
        $result['meta']['description'] = $meta_arr['description'];
        $result['meta']['keywords'] = $meta_arr['keywords'];
        $result['meta']['h1'] = $meta_arr['h1'];
        $result['meta']['content'] = $meta_arr['content'];
    }else{
        $result['meta']['title'] = '';
        $result['meta']['description'] = '';
        $result['meta']['keywords'] = '';
        $result['meta']['h1'] = '';
        $result['meta']['content'] = '';
    }


    if(!empty($deptmeta_arr)){
        $result['dept_meta']['title'] = $deptmeta_arr['title'];
        $result['dept_meta']['description'] = $deptmeta_arr['description'];
        $result['dept_meta']['keywords'] = $deptmeta_arr['keywords'];
        $result['dept_meta']['h1'] = $deptmeta_arr['h1'];
        $result['dept_meta']['content'] = $deptmeta_arr['content'];
    }else{
        $result['dept_meta']['title'] = '';
        $result['dept_meta']['description'] = '';
        $result['dept_meta']['keywords'] = '';
        $result['dept_meta']['h1'] = '';
        $result['dept_meta']['content'] = '';
    }
    
    if(!empty($agmeta_arr) && !empty($agencymeta_arr)){
        $ag_name = $agencymeta_arr['agency_name'] ?? '';
        $short_form = trim(substr($ag_name, strpos($ag_name, "- ") + 1));
        $title = str_replace("(ag_name)",$ag_name,$agmeta_arr['title']);
        $final_title = str_replace("(short_form)",$short_form,$title);
        $description = str_replace("(ag_name)",$ag_name,$agmeta_arr['description']);
        $final_description = str_replace("(short_form)",$short_form,$description);
        $keywords = str_replace("(ag_name)",$ag_name,$agmeta_arr['keywords']);
        $h1 = str_replace("(ag_name)",$ag_name,$agmeta_arr['h1']);
        $h1_final = str_replace("(short_form)",$short_form,$h1);
        $content = str_replace("(ag_name)",$ag_name,$agmeta_arr['content']);
        $final_content = str_replace("(short_form)",$short_form,$content);
        $result['agency_meta']['title'] = $final_title;
        $result['agency_meta']['description'] = $final_description;
        $result['agency_meta']['keywords'] = $keywords;
        $result['agency_meta']['h1'] = $h1_final;
        $result['agency_meta']['content'] = $final_content;
    }else{
        $result['agency_meta']['title'] = '';
        $result['agency_meta']['description'] = '';
        $result['agency_meta']['keywords'] = '';
        $result['agency_meta']['h1'] = '';
        $result['agency_meta']['content'] = '';
    }
    if(!empty($citymeta_arr)){
        $title = str_replace("(City)",$metaCity,$citymeta_arr['title']);
        $final_title = str_replace("(Brand name)",'Tender 18',$title);
        $description = str_replace("(City)",$metaCity,$citymeta_arr['description']);
        $final_description = str_replace("(Brand name)",'Tender 18',$description);
        $keywords = str_replace("(City)",$metaCity,$citymeta_arr['keywords']);
        $h1 = str_replace("(City)",$metaCity,$citymeta_arr['h1']);
        $content = str_replace("(City)",$metaCity,$citymeta_arr['content']);
        $result['city_meta']['title'] = $final_title;
        $result['city_meta']['description'] = $final_description;
        $result['city_meta']['keywords'] = $keywords;
        $result['city_meta']['h1'] = $h1;
        $result['city_meta']['content'] = $content;
    }else{
        $result['city_meta']['title'] = '';
        $result['city_meta']['description'] = '';
        $result['city_meta']['keywords'] = '';
        $result['city_meta']['h1'] = '';
        $result['city_meta']['content'] = '';
    }

    if(!empty($keymeta_arr)){
        $title = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['title']);
        $final_title = str_replace("(Brand name)",'Tender 18',$title);
        $description = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['description']);
        $final_description = str_replace("(Brand name)",'Tender 18',$description);
        $keywords = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['keywords']);
        $h1 = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['h1']);
        $content = str_replace("(Keyword)",$metaKeyword,$keymeta_arr['content']);
        $result['keyword_meta']['title'] = $final_title;
        $result['keyword_meta']['description'] = $final_description;
        $result['keyword_meta']['keywords'] = $keywords;
        $result['keyword_meta']['h1'] = $h1;
        $result['keyword_meta']['content'] = $content;
    }else{
        $result['keyword_meta']['title'] = '';
        $result['keyword_meta']['description'] = '';
        $result['keyword_meta']['keywords'] = '';
        $result['keyword_meta']['h1'] = '';
        $result['keyword_meta']['content'] = '';
    }
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
