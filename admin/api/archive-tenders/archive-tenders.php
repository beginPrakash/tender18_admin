<?php
include '../../includes/connection.php';
include '../../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'getArchiveTendersData':
        $result = get_results($con);
        break;
    default:
        $result = null;
}

function get_results($con)
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
    $limit = 10;
    $sql_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM `tenders_archive` order by id desc "));
    $total_query = $sql_query['total'];
    $total = ceil($total_query / $limit);
    $page = isset($_GET['page_no']) ? abs((int) $_GET['page_no']) : 1;
    if (empty($page) || $page < 1) {
        $page = 1;
    }
    $offset = ($page * $limit) - $limit;
    $tender_data = mysqli_query($con, "SELECT * FROM `tenders_archive` order by id desc LIMIT $offset, $limit");
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
            $result['tenders'][$count]['title'] = htmlspecialcode_generator($row['title']);
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
