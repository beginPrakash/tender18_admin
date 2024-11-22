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
    $start    = '2022-01-01';
    $end      = date('Y-m-d');
    $getRangeYear   = range(gmdate('Y', strtotime($start)), gmdate('Y', strtotime($end)));
    $month = date('n');
    $cyear = date('Y');
    if (count($getRangeYear) > 0) {
        rsort($getRangeYear);
        $count = 1;
        $scount = 100;
        foreach($getRangeYear as $key => $val){
            if((($month >= 1 && $month <= 12) && $cyear==$val) || ($val<=$cyear)):
                if($val==$cyear && ($month >= 1 && $month <= 12)):
                    $result[$count]['label'] = 'Jan to June '.$val;
                    $result[$count]['value'] = '01-01-'.$val.'/30-06-'.$val;
                elseif($val!=$cyear || ($month >= 1 && $month <= 12)):
                    $result[$count]['label'] = 'Jan to June '.$val;
                    $result[$count]['value'] = '01-01-'.$val.'/30-06-'.$val;
                endif;
            endif;
            if((($month >= 7 && $month <= 12) && $cyear==$val) || ($val<=$cyear)):
                if($val==$cyear && ($month >= 7 && $month <= 12)):
                    $result[$scount]['label'] = 'July to Dec '.$val;
                    $result[$scount]['value'] = '01-07-'.$val.'/31-12-'.$val;
                elseif($val!=$cyear || ($month >= 7 && $month <= 12)):
                    $result[$scount]['label'] = 'July to Dec '.$val;
                    $result[$scount]['value'] = '01-07-'.$val.'/31-12-'.$val;
                endif;
            endif;
            $count++;
            $scount++;
            
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
