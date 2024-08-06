<?php
include '../includes/connection.php';
include '../includes/functions.php';
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

// switch ($endpoint) {
//     case 'getMonthYearList':
//         $result = get_results($con);
//         break;
//     default:
//         $result = null;
// }
$result = get_results($con);

function get_results($con)
{
    $start    = '2011-01-01';
    $end      = date('Y-m-d');
    $getRangeYear   = range(gmdate('Y', strtotime($start)), gmdate('Y', strtotime($end)));
    if (count($getRangeYear) > 0) {
        rsort($getRangeYear);
        $count = 1;
        $scount = 100;
        $mcount = 200;
        $fcount = 300;
        foreach($getRangeYear as $key => $val){
            $result[$count]['label'] = 'Oct to Dec '.$val;
            $result[$count]['value'] = '01-10-'.$val.'/31-12-'.$val;

            $result[$scount]['label'] = 'July to Sep '.$val;
            $result[$scount]['value'] = '01-07-'.$val.'/30-09-'.$val;

            $result[$mcount]['label'] = 'April to June '.$val;
            $result[$mcount]['value'] = '01-04-'.$val.'/30-06-'.$val;

            $result[$fcount]['label'] = 'Jan to Mar '.$val;
            $result[$fcount]['value'] = '01-01-'.$val.'/31-03-'.$val;    

            $count++;
            $scount++;
            $mcount++;
            $fcount++;
            
        }
        $result = array_values($result);
    } else {
        $result = "No data found";
    }
    //echo'<pre>';print_r($result);exit;
    return $result;
}

if ($result === null) {
    echo json_encode(array("status" => "error"));
} else {
    echo json_encode(array("status" => " success", "data" => $result));
}
die();
