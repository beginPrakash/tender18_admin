<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
include '../../../elasticsearch/elastic_client.php';
include '../../../elasticsearch/elastic_utils.php';

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

$index = ES_INDEXES['ALL'];

// Get the raw POST data
$rawData = file_get_contents("php://input");
// Decode the JSON data
$postData = json_decode($rawData, true);

$endpoint = isset($postData['endpoint']) ? $postData['endpoint'] : '';

switch ($endpoint) {
    case 'getAFilterUserAllTendersData':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = get_results($con, $index, $postData);
        } else {
            $result = null;
        }
        break;
    default:
        $result = null;
}

function get_results($con, $index, $postData)
{
    // return $con;
    $start_date = $_GET['startDate'];
    $timestamp1 = strtotime($start_date);
    $start_date = date("Y-m-d", $timestamp1);

    $end_date = $_GET['endDate'];
    $timestamp2 = strtotime($end_date);
    $end_date = date("Y-m-d", $timestamp2);

    // START After Login User
    $user_unique_id = $postData['user_unique_id'];
    $token = $postData['token'];

    $sql = "SELECT keywords, words, not_used_keywords, filter_city, filter_state, filter_tender_value, filter_agency, filter_department, filter_type FROM users WHERE user_unique_id = ? AND token = ? LIMIT 1";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $user_unique_id, $token);
    mysqli_stmt_execute($stmt);

    $user_result = mysqli_stmt_get_result($stmt);

    $userFilters = [
        'keywords'          => [],
        'words'             => [],
        'not_used_keywords' => [],
        'city'              => [],
        'state'             => [],
        'tender_value_to'   => 0,
        'tender_value'      => null,
        'agency'            => [],
        'department'        => [],
        'type'              => [],
    ];

    if ($row = mysqli_fetch_assoc($user_result)) {
        $userFilters = [
            'keywords'          => normalize_filters_array($row['keywords']),
            'words'             => normalize_filters_array($row['words']),
            'not_used_keywords' => normalize_filters_array($row['not_used_keywords']),
            'city'              => normalize_filters_array($row['filter_city']),
            'state'             => normalize_filters_array($row['filter_state']),
            'tender_value_to'   => 0,
            'tender_value'      => $row['filter_tender_value'],
            'agency'            => normalize_filters_array($row['filter_agency']),
            'department'        => normalize_filters_array($row['filter_department']),
            'type'              => normalize_filters_array($row['filter_type']),
        ];
    }

    mysqli_stmt_close($stmt);
    // END After Login User

     $filter_keyword = array_values(array_unique(array_filter(array_merge(
        is_string($postData['keyword'] ?? null)
            ? [trim($postData['keyword'])]
            : [],
        is_array($userFilters['keywords'] ?? null)
            ? $userFilters['keywords']
            : [],
        is_array($userFilters['words'] ?? null)
            ? $userFilters['words']
            : []
    ))));

    $filters = [
        'search_keyword' => $postData['keyword'] ?? null,
        'ref_no' => $postData['ref_no'] ?? null,
        'tender_id' => $postData['tender_id'] ?? null,
        'due_date' => $postData['due_date'] ?? null,
        'tender_value_to'   => prefer($postData['tender_value_to'] ?? null, $userFilters['tender_value_to'] ?? null, 0),
        'tender_value'      => prefer($postData['tender_value'] ?? null, $userFilters['tender_value'] ?? null, 0),
        'keyword' => prefer(null, $userFilters['keywords']),
        'words' => prefer(null, $userFilters['words']),
        'not_used_keywords' => $userFilters['not_used_keywords'] ?? [],
        'state' => prefer($postData['state'] ?? [], $userFilters['state'], []),
        'city' => prefer($postData['city'] ?? [], $userFilters['city'], []),
        'agency' => prefer($postData['agency'] ?? [], $userFilters['agency'], []),
        'department' => prefer($postData['department'] ?? [], $userFilters['department'], []),
        'tender_type' => prefer($postData['type'] ?? [], $userFilters['type'], []),
        'start_date' => $start_date,
        'end_date' => $end_date,
        'all-india-tenders' => true
    ];
    
    $page = $postData['page_no'] ?? 1;
    $size = $postData['size'] ?? 10;

    $body = build_elastic_user_query($filters, $page, $size);
    // echo "<pre>"; print_r($body); die;

    $resp = es_search($index, $body);
    $total_query = isset($resp['body']['hits']['total']['value']) ? (int)$resp['body']['hits']['total']['value'] : 0; 
    $total = ceil($total_query / $size);
    $data = isset($resp['body']['hits']['hits']) ? $resp['body']['hits']['hits'] : array();

    // WhatsApp Number 
    $whatsapp_no = "";
    $header_data = mysqli_query($con, "SELECT * FROM `header`");
    $header_result = mysqli_num_rows($header_data);
    if ($header_result == 1) {
        while ($row = mysqli_fetch_assoc($header_data)) {
            $whatsapp_no = $row['whatsapp_num'];
        }
    }

    if (isset($data) && count($data) > 0) {
        $count = 1;
        foreach ($data as $source) {
            $row = $source['_source'];
            
            $result['tenders'][$count]['ref_no'] = $row['ref_no'];
            $location = "";
            $city = "";
            $state="";
            if (!empty($row['city'])) {
                $location = $row['city'];
                $city = $row['city'];
            }
            if (!empty($row['state'])) {
                if (!empty($location)) {
                    $location .= ", " . $row['state'];
                    $state = $row['state'];
                } else {
                    $location = $row['state'];
                    $state = $row['state'];
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
             // Ensure array
            if (!is_array($filter_keyword)) {
                $filter_keyword = [$filter_keyword];
            }
           
            $highlightedResult = highlight_all_keywords($highlightedResult, $filter_keyword);

            $result['tenders'][$count]['title'] = htmlspecialcode_generator($highlightedResult);
            $result['tenders'][$count]['city'] = $city;
            $result['tenders'][$count]['tend_city'] = str_replace(' ', '-', $city).'-tenders';
            $result['tenders'][$count]['state'] = $state;
            $result['tenders'][$count]['tend_state'] = str_replace(' ', '-', $state).'-tenders';
            $result['tenders'][$count]['agency'] = htmlspecialcode_generator($row['agency_type']);
            $result['tenders'][$count]['tend_agency'] = str_replace(' ', '-', htmlspecialcode_generator($row['agency_type'])).'-tenders';
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
            $result['tenders'][$count]['tenders'] = $row['tenders'];
            $count++;
        }
    } else {
        $result['tenders'] = [];
    }

    $meta_arr = [];
    $meta_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `all_tenders_meta_content` where id=1 ");
    $meta_result = mysqli_num_rows($meta_data);
    if ($meta_result == 1) {
        while ($row = mysqli_fetch_assoc($meta_data)) {
            $meta_arr =  $row;
        }
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
        $result['meta']['label'] = '';
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