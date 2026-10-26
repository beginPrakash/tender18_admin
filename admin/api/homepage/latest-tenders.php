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

$result = get_wise_results($con, $index, $postData);

function get_wise_results($con, $index, $postData)
{
    $size = $postData['size'] ?? 10;
    $filters = [
        'department' => $postData['department'] ?? []
    ];

    $body = build_elastic_wise_query($filters, 1, $size);

    $resp = es_search($index, $body);
    $data = isset($resp['body']['hits']['hits']) ? $resp['body']['hits']['hits'] : array();

    // WhatsApp Number
    $whatsapp_no = "";
    $header_data = mysqli_query($con, "SELECT * FROM `header`");
    if ($header_data && mysqli_num_rows($header_data) == 1) {
        $whatsapp_no = mysqli_fetch_assoc($header_data)['whatsapp_num'];
    }

    $tenders = [];
    if (isset($data) && count($data) > 0) {
        $count = 1;
        foreach ($data as $source) {
            $row = $source['_source'];

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
            
            $pincode = "";
            if (empty($row['pincode'])) {
                $pincode = 'Refer Document';
            } else {
                $pincode = $row['pincode'];
            }

            $tender_value = "";
            if (empty($row['tender_value']) && $row['tender_value'] > 0) {
                $tender_value = 'Refer Document';
            } else {
                $tender_value = $row['tender_value'];
            }

            $tender_fee = "";
            if (empty($row['tender_fee']) && $row['tender_fee'] > 0) {
                $tender_fee = 'Refer Document';
            } else {
                $tender_fee = $row['tender_fee'];
            }

            $tender_emd = "";
            if (empty($row['tender_emd']) && $row['tender_emd'] > 0) {
                $tender_emd = 'Refer Document';
            } else {
                $tender_emd = $row['tender_emd'];
            }
            
            
            $tenders[$count] = [
                'ref_no' => $row['ref_no'] ?? "",
                'title' => htmlspecialcode_generator($row['title']),
                'city' => $city,
                'tend_city' => str_replace(' ', '-', $city).'-tenders',
                'state' => $state,
                'location' => $location,
                'pincode' => $pincode,
                'tend_state' => str_replace(' ', '-', $state).'-tenders',
                'agency' => htmlspecialcode_generator($row['agency_type']),
                'tend_agency' => str_replace(' ', '-', htmlspecialcode_generator($row['agency_type'])).'-tenders',
                'publish_date' => date('M d, Y', strtotime($row['publish_date'])),
                'due_date' => date('M d, Y', strtotime($row['due_date'])),
                'tender_value' => $tender_value,
                'tender_fee' => $tender_fee,
                'tender_emd' => $tender_emd,
                'documents' => "#",
                'whatsapp_no' => $whatsapp_no,
                'dep_type' => $row['department'],
                'tenders' => $row['tenders'] ?? ""
            ];
            $count++;
        }
    }

    $total = isset($resp['body']['hits']['total']['value']) ? $resp['body']['hits']['total']['value'] : 0;

    $result = [
        'tenders' => $tenders,
        'total' => $total
    ];

    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result), JSON_PARTIAL_OUTPUT_ON_ERROR);
}
die();
