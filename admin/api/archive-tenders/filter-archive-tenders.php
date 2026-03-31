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

$index = ES_INDEXES['ARCHIVE'];

// Get the raw POST data
$rawData = file_get_contents("php://input");
// Decode the JSON data
$postData = json_decode($rawData, true);

$endpoint = isset($postData['endpoint']) ? $postData['endpoint'] : '';

switch ($endpoint) {
    case 'getFilterArchiveTendersData':
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

    $filter_keyword = $postData['keyword'] ?? null;
    
    $filters = [
        'ref_no' => $postData['ref_no'] ?? null,
        'tender_id' => $postData['tender_id'] ?? null,
        'due_date' => $postData['due_date'] ?? null,
        'keyword' => $filter_keyword ?? null,
        'state' => $postData['state'] ?? [],
        'city' => $postData['city'] ?? [],
        'agency' => $postData['agency'] ?? [],
        'department' => $postData['department'] ?? [],
        'tender_type' => $postData['type'] ?? [],
        'start_date' => $start_date,
        'end_date' => $end_date
    ];

    $page = $postData['page_no'] ?? 1;
    $size = $postData['size'] ?? 10;

    $body = build_elastic_query($filters, $page, $size);
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
                 
                foreach ($keyword_arr as $keyword) {
                    $highlightedResult = highlight_search_term($highlightedResult, $keyword);
                }
            }

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
            $count++;
        }
    } else {
        $result['tenders'] = [];
    }

    $meta_arr = [];
    $meta_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `archieve_tenders_meta_content` where id=1 ");
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
    echo json_encode(array("status" => " success", "data" => $result),JSON_PARTIAL_OUTPUT_ON_ERROR);
}
die();
