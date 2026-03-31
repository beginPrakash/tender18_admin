<?php

include '../includes/connection.php';

include '../includes/functions.php';

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

    case 'getMetaDetailData':

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



    $type = $postData['type'] ?? '';

    $metaState = $postData['state'] ?? '';

    $metaAgency = $postData['agency'] ?? '';

    $metaCity = $postData['city'] ?? '';

    $metaSource = $postData['department'] ?? '';

    $metaKeyword = $postData['keyword'] ?? '';

    $metaTenderType = $postData['tendertype'] ?? '';
    
    $metaTender_Type = $postData['tender-type'] ?? '';

    $meta_arr = [];

    $acmeta_arr = [];

    $asmeta_arr = [];

    $ksmeta_arr = [];

    $kcmeta_arr = [];

    $kameta_arr = [];

    $kdmeta_arr = [];

    $csmeta_arr = [];

    $ssmeta_arr = [];

    $tsmeta_arr = [];

    $tcmeta_arr = [];

    $tameta_arr = [];

    $tdmeta_arr = [];
    
    $tkmeta_arr = [];

    $agencymeta_arr = [];
    if(!empty($metaAgency)){
        $state_data = mysqli_query($con, "SELECT `agency_type` FROM `tenders_all` where agency_type LIKE '%$metaAgency%' order by `id` desc limit 1");
        $state_result = mysqli_num_rows($state_data);
        if ($state_result == 1) {
            while ($row = mysqli_fetch_assoc($state_data)) {
                $agencymeta_arr =  $row;
            }
        }
    }

    if(!empty($metaState) && !empty($metaAgency) && ($type == 'agency-state' || $type == 'state-agency')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'agency-state'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $meta_arr =  $row;

            }

        }

    }



    if(!empty($metaCity) && !empty($metaAgency) && ($type == 'agency-city' || $type == 'city-agency')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'agency-city'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $acmeta_arr =  $row;

            }

        }

    }



    if(!empty($metaSource) && !empty($metaAgency) && ($type == 'agency-department') || $type == 'department-agency'){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'agency-source'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $asmeta_arr =  $row;

            }

        }

    }



    if(!empty($metaKeyword) && !empty($metaState) && ($type == 'keyword-state') || $type == 'state-keyword'){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'keyword-state'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $ksmeta_arr =  $row;

            }

        }

    }



    if(!empty($metaKeyword) && !empty($metaCity) && ($type == 'keyword-city' || $type == 'city-keyword')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'keyword-city'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $kcmeta_arr =  $row;

            }

        }

    }



    if(!empty($metaKeyword) && !empty($metaAgency) && ($type == 'keyword-agency' || $type == 'agency-keyword')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'keyword-agency'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $kameta_arr =  $row;

            }

        }

    }



    if(!empty($metaKeyword) && !empty($metaSource) && ($type == 'keyword-department' || $type == 'department-keyword')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'keyword-source'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $kdmeta_arr =  $row;

            }

        }

    }



    if(!empty($metaCity) && !empty($metaSource) && ($type == 'city-department' || $type == 'department-city')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'city-source'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $csmeta_arr =  $row;

            }

        }

    }



    if(!empty($metaState) && !empty($metaSource) && ($type == 'state-department' || $type == 'department-state')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'state-source'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $ssmeta_arr =  $row;

            }

        }

    }


    if(!empty($metaTenderType) && !empty($metaState) && ($type == 'tendertype-state' || $type == 'state-tendertype')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'tendertype-state'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $tsmeta_arr =  $row;

            }

        }

    }
    
    if(!empty($metaTender_Type) && !empty($metaKeyword) && ($type == 'tender-type-keyword' || $type == 'keyword-tender-type')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'tendertype-keyword'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $tkmeta_arr =  $row;

            }

        }

    }



    if(!empty($metaTenderType) && !empty($metaCity) && ($type == 'tendertype-city' || $type == 'city-tendertype')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'tendertype-city'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $tcmeta_arr =  $row;

            }

        }

    }



    if(!empty($metaTenderType) && !empty($metaAgency) && ($type == 'tendertype-agency' || $type == 'agency-tendertype')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'tendertype-agency'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $tameta_arr =  $row;

            }

        }

    }



    if(!empty($metaTenderType) && !empty($metaSource) && ($type == 'tendertype-department' || $type == 'department-tendertype')){

        $state_data = mysqli_query($con, "SELECT `title`,`description`,`keywords`,`h1`,`content` FROM `multiurl_meta_content` where `type` = 'tendertype-source'");

        $state_result = mysqli_num_rows($state_data);

        if ($state_result == 1) {

            while ($row = mysqli_fetch_assoc($state_data)) {

                $tdmeta_arr =  $row;

            }

        }

    }



    



    if(!empty($meta_arr) && !empty($agencymeta_arr)){

        $metaAgency = $agencymeta_arr['agency_type'] ?? '';

        $title = str_replace("(Agency)",$metaAgency,$meta_arr['title']);

        $final_title = str_replace("(State)",$metaState,$title);

        $description = str_replace("(Agency)",$metaAgency,$meta_arr['description']);

        $final_description = str_replace("(State)",$metaState,$description);

        $keywords = str_replace("(Agency)",$metaAgency,$meta_arr['keywords']);

        $final_keywords = str_replace("(State)",$metaState,$keywords);

        $h1 = str_replace("(Agency)",$metaAgency,$meta_arr['h1']);

        $final_h1 = str_replace("(State)",$metaState,$h1);

        $content = str_replace("(Agency)",$metaAgency,$meta_arr['content']);

        $final_content = str_replace("(State)",$metaState,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'agency-state'){
            $result['meta']['label1'] = $metaAgency ?? '';

            $result['meta']['label2'] = $metaState ?? '';
        }else{
            $result['meta']['label1'] = $metaState ?? '';

            $result['meta']['label2'] = $metaAgency ?? '';
        }

        

    }elseif(!empty($acmeta_arr) && !empty($agencymeta_arr)){

        $metaAgency = $agencymeta_arr['agency_type'] ?? '';

        $title = str_replace("(Agency)",$metaAgency,$acmeta_arr['title']);

        $final_title = str_replace("(City)",$metaCity,$title);

        $description = str_replace("(Agency)",$metaAgency,$acmeta_arr['description']);

        $final_description = str_replace("(City)",$metaCity,$description);

        $keywords = str_replace("(Agency)",$metaAgency,$acmeta_arr['keywords']);

        $final_keywords = str_replace("(City)",$metaCity,$keywords);

        $h1 = str_replace("(Agency)",$metaAgency,$acmeta_arr['h1']);

        $final_h1 = str_replace("(City)",$metaCity,$h1);

        $content = str_replace("(Agency)",$metaAgency,$acmeta_arr['content']);

        $final_content = str_replace("(City)",$metaCity,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'agency-city'){
            $result['meta']['label1'] = $metaAgency ?? '';

            $result['meta']['label2'] = $metaCity ?? '';
        }else{
            $result['meta']['label1'] = $metaCity ?? '';

            $result['meta']['label2'] = $metaAgency ?? '';
        }

    }elseif(!empty($asmeta_arr) && !empty($agencymeta_arr)){

        $metaAgency = $agencymeta_arr['agency_type'] ?? '';

        $title = str_replace("(Agency)",$metaAgency,$asmeta_arr['title']);

        $final_title = str_replace("(Source)",$metaSource,$title);

        $description = str_replace("(Agency)",$metaAgency,$asmeta_arr['description']);

        $final_description = str_replace("(Source)",$metaSource,$description);

        $keywords = str_replace("(Agency)",$metaAgency,$asmeta_arr['keywords']);

        $final_keywords = str_replace("(Source)",$metaSource,$keywords);

        $h1 = str_replace("(Agency)",$metaAgency,$asmeta_arr['h1']);

        $final_h1 = str_replace("(Source)",$metaSource,$h1);

        $content = str_replace("(Agency)",$metaAgency,$asmeta_arr['content']);

        $final_content = str_replace("(Source)",$metaSource,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'agency-department'){
            $result['meta']['label1'] = $metaAgency ?? '';

            $result['meta']['label2'] = $metaSource ?? '';
        }else{
            $result['meta']['label1'] = $metaSource ?? '';

            $result['meta']['label2'] = $metaAgency ?? '';
        }

    }elseif(!empty($ksmeta_arr)){

        $title = str_replace("(Keyword)",$metaKeyword,$ksmeta_arr['title']);

        $final_title = str_replace("(State)",$metaState,$title);

        $description = str_replace("(Keyword)",$metaKeyword,$ksmeta_arr['description']);

        $final_description = str_replace("(State)",$metaState,$description);

        $keywords = str_replace("(Keyword)",$metaKeyword,$ksmeta_arr['keywords']);

        $final_keywords = str_replace("(State)",$metaState,$keywords);

        $h1 = str_replace("(Keyword)",$metaKeyword,$ksmeta_arr['h1']);

        $final_h1 = str_replace("(State)",$metaState,$h1);

        $content = str_replace("(Keyword)",$metaKeyword,$ksmeta_arr['content']);

        $final_content = str_replace("(State)",$metaState,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'keyword-state'){
            
            $result['meta']['label1'] = $metaKeyword ?? '';
    
            $result['meta']['label2'] = $metaState ?? '';
        }else{
            
            $result['meta']['label1'] = $metaState ?? '';
    
            $result['meta']['label2'] = $metaKeyword ?? '';
        }


        

    }elseif(!empty($kcmeta_arr)){

        $title = str_replace("(Keyword)",$metaKeyword,$kcmeta_arr['title']);

        $final_title = str_replace("(City)",$metaCity,$title);

        $description = str_replace("(Keyword)",$metaKeyword,$kcmeta_arr['description']);

        $final_description = str_replace("(City)",$metaCity,$description);

        $keywords = str_replace("(Keyword)",$metaKeyword,$kcmeta_arr['keywords']);

        $final_keywords = str_replace("(City)",$metaCity,$keywords);

        $h1 = str_replace("(Keyword)",$metaKeyword,$kcmeta_arr['h1']);

        $final_h1 = str_replace("(City)",$metaCity,$h1);

        $content = str_replace("(Keyword)",$metaKeyword,$kcmeta_arr['content']);

        $final_content = str_replace("(City)",$metaCity,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'keyword-city'){
            $result['meta']['label1'] = $metaKeyword ?? '';

            $result['meta']['label2'] = $metaCity ?? '';
        }else{
            $result['meta']['label1'] = $metaCity ?? '';

            $result['meta']['label2'] = $metaKeyword ?? '';
        }
    }elseif(!empty($kameta_arr) && !empty($agencymeta_arr)){

        $metaAgency = $agencymeta_arr['agency_type'] ?? '';

        $title = str_replace("(Keyword)",$metaKeyword,$kameta_arr['title']);

        $final_title = str_replace("(Agency)",$metaAgency,$title);

        $description = str_replace("(Keyword)",$metaKeyword,$kameta_arr['description']);

        $final_description = str_replace("(Agency)",$metaAgency,$description);

        $keywords = str_replace("(Keyword)",$metaKeyword,$kameta_arr['keywords']);

        $final_keywords = str_replace("(Agency)",$metaAgency,$keywords);

        $h1 = str_replace("(Keyword)",$metaKeyword,$kameta_arr['h1']);

        $final_h1 = str_replace("(Agency)",$metaAgency,$h1);

        $content = str_replace("(Keyword)",$metaKeyword,$kameta_arr['content']);

        $final_content = str_replace("(Agency)",$metaAgency,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'keyword-agency'){
            $result['meta']['label1'] = $metaKeyword ?? '';

            $result['meta']['label2'] = $metaAgency ?? '';
        }else{
            $result['meta']['label1'] = $metaAgency ?? '';

            $result['meta']['label2'] = $metaKeyword ?? '';
        }

    }elseif(!empty($kdmeta_arr)){

        $title = str_replace("(Keyword)",$metaKeyword,$kdmeta_arr['title']);

        $final_title = str_replace("(Source)",$metaSource,$title);

        $description = str_replace("(Keyword)",$metaKeyword,$kdmeta_arr['description']);

        $final_description = str_replace("(Source)",$metaSource,$description);

        $keywords = str_replace("(Keyword)",$metaKeyword,$kdmeta_arr['keywords']);

        $final_keywords = str_replace("(Source)",$metaSource,$keywords);

        $h1 = str_replace("(Keyword)",$metaKeyword,$kdmeta_arr['h1']);

        $final_h1 = str_replace("(Source)",$metaSource,$h1);

        $content = str_replace("(Keyword)",$metaKeyword,$kdmeta_arr['content']);

        $final_content = str_replace("(Source)",$metaSource,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'keyword-department'){
            $result['meta']['label1'] = $metaKeyword ?? '';

            $result['meta']['label2'] = $metaSource ?? '';
        }else{
            $result['meta']['label1'] = $metaSource ?? '';

            $result['meta']['label2'] = $metaKeyword ?? '';
        }

    }elseif(!empty($csmeta_arr)){

        $title = str_replace("(City)",$metaCity,$csmeta_arr['title']);

        $final_title = str_replace("(Source)",$metaSource,$title);

        $description = str_replace("(City)",$metaCity,$csmeta_arr['description']);

        $final_description = str_replace("(Source)",$metaSource,$description);

        $keywords = str_replace("(City)",$metaCity,$csmeta_arr['keywords']);

        $final_keywords = str_replace("(Source)",$metaSource,$keywords);

        $h1 = str_replace("(City)",$metaCity,$csmeta_arr['h1']);

        $final_h1 = str_replace("(Source)",$metaSource,$h1);

        $content = str_replace("(City)",$metaCity,$csmeta_arr['content']);

        $final_content = str_replace("(Source)",$metaSource,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;

        if($type == 'city-department'){
            $result['meta']['label1'] = $metaCity ?? '';

            $result['meta']['label2'] = $metaSource ?? '';
        }else{
            $result['meta']['label1'] = $metaSource ?? '';

            $result['meta']['label2'] = $metaCity ?? '';
        }

    }elseif(!empty($ssmeta_arr)){

        $title = str_replace("(State)",$metaState,$ssmeta_arr['title']);

        $final_title = str_replace("(Source)",$metaSource,$title);

        $description = str_replace("(State)",$metaState,$ssmeta_arr['description']);

        $final_description = str_replace("(Source)",$metaSource,$description);

        $keywords = str_replace("(State)",$metaState,$ssmeta_arr['keywords']);

        $final_keywords = str_replace("(Source)",$metaSource,$keywords);

        $h1 = str_replace("(State)",$metaState,$ssmeta_arr['h1']);

        $final_h1 = str_replace("(Source)",$metaSource,$h1);

        $content = str_replace("(State)",$metaState,$ssmeta_arr['content']);

        $final_content = str_replace("(Source)",$metaSource,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'state-department'){
            $result['meta']['label1'] = $metaState ?? '';

            $result['meta']['label2'] = $metaSource ?? '';
        }else{
            $result['meta']['label1'] = $metaSource ?? '';

            $result['meta']['label2'] = $metaState ?? '';
        }

    }elseif(!empty($tsmeta_arr)){

        $title = str_replace("(TenderType)",$metaTenderType,$tsmeta_arr['title']);

        $final_title = str_replace("(State)",$metaState,$title);

        $description = str_replace("(TenderType)",$metaTenderType,$tsmeta_arr['description']);

        $final_description = str_replace("(State)",$metaState,$description);

        $keywords = str_replace("(TenderType)",$metaTenderType,$tsmeta_arr['keywords']);

        $final_keywords = str_replace("(State)",$metaState,$keywords);

        $h1 = str_replace("(TenderType)",$metaTenderType,$tsmeta_arr['h1']);

        $final_h1 = str_replace("(State)",$metaState,$h1);

        $content = str_replace("(TenderType)",$metaTenderType,$tsmeta_arr['content']);

        $final_content = str_replace("(State)",$metaState,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'tendertype-state'){
            $result['meta']['label1'] = $metaTenderType ?? '';

            $result['meta']['label2'] = $metaState ?? '';
        }else{
            $result['meta']['label1'] = $metaState ?? '';

            $result['meta']['label2'] = $metaTenderType ?? '';
        }

    }elseif(!empty($tcmeta_arr)){

        $title = str_replace("(TenderType)",$metaTenderType,$tcmeta_arr['title']);

        $final_title = str_replace("(City)",$metaCity,$title);

        $description = str_replace("(TenderType)",$metaTenderType,$tcmeta_arr['description']);

        $final_description = str_replace("(City)",$metaCity,$description);

        $keywords = str_replace("(TenderType)",$metaTenderType,$tcmeta_arr['keywords']);

        $final_keywords = str_replace("(City)",$metaCity,$keywords);

        $h1 = str_replace("(TenderType)",$metaTenderType,$tcmeta_arr['h1']);

        $final_h1 = str_replace("(City)",$metaCity,$h1);

        $content = str_replace("(TenderType)",$metaTenderType,$tcmeta_arr['content']);

        $final_content = str_replace("(City)",$metaCity,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'tendertype-city'){
            $result['meta']['label1'] = $metaTenderType ?? '';

            $result['meta']['label2'] = $metaCity ?? '';
        }else{
            $result['meta']['label1'] = $metaCity ?? '';

            $result['meta']['label2'] = $metaTenderType ?? '';
        }

    }elseif(!empty($tameta_arr) && !empty($agencymeta_arr)){

        $metaAgency = $agencymeta_arr['agency_type'] ?? '';

        $title = str_replace("(TenderType)",$metaTenderType,$tameta_arr['title']);

        $final_title = str_replace("(Agency)",$metaAgency,$title);

        $description = str_replace("(TenderType)",$metaTenderType,$tameta_arr['description']);

        $final_description = str_replace("(Agency)",$metaAgency,$description);

        $keywords = str_replace("(TenderType)",$metaTenderType,$tameta_arr['keywords']);

        $final_keywords = str_replace("(Agency)",$metaAgency,$keywords);

        $h1 = str_replace("(TenderType)",$metaTenderType,$tameta_arr['h1']);

        $final_h1 = str_replace("(Agency)",$metaAgency,$h1);

        $content = str_replace("(TenderType)",$metaTenderType,$tameta_arr['content']);

        $final_content = str_replace("(Agency)",$metaAgency,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'tendertype-agency'){
            $result['meta']['label1'] = $metaTenderType ?? '';

            $result['meta']['label2'] = $metaAgency ?? '';
        }else{
            $result['meta']['label1'] = $metaAgency ?? '';

            $result['meta']['label2'] = $metaTenderType ?? '';
        }

    }elseif(!empty($tdmeta_arr)){

        $title = str_replace("(TenderType)",$metaTenderType,$tdmeta_arr['title']);

        $final_title = str_replace("(Source)",$metaSource,$title);

        $description = str_replace("(TenderType)",$metaTenderType,$tdmeta_arr['description']);

        $final_description = str_replace("(Source)",$metaSource,$description);

        $keywords = str_replace("(TenderType)",$metaTenderType,$tdmeta_arr['keywords']);

        $final_keywords = str_replace("(Source)",$metaSource,$keywords);

        $h1 = str_replace("(TenderType)",$metaTenderType,$tdmeta_arr['h1']);

        $final_h1 = str_replace("(Source)",$metaSource,$h1);

        $content = str_replace("(TenderType)",$metaTenderType,$tdmeta_arr['content']);

        $final_content = str_replace("(Source)",$metaSource,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'tendertype-department'){
            $result['meta']['label1'] = $metaTenderType ?? '';

            $result['meta']['label2'] = $metaSource ?? '';
        }else{
            $result['meta']['label1'] = $metaSource ?? '';

            $result['meta']['label2'] = $metaTenderType ?? '';
        }

    
    }elseif(!empty($tkmeta_arr)){

        $title = str_replace("(TenderType)",$metaTender_Type,$tkmeta_arr['title']);

        $final_title = str_replace("(Keyword)",$metaKeyword,$title);

        $description = str_replace("(TenderType)",$metaTender_Type,$tkmeta_arr['description']);

        $final_description = str_replace("(Keyword)",$metaKeyword,$description);

        $keywords = str_replace("(TenderType)",$metaTender_Type,$tkmeta_arr['keywords']);

        $final_keywords = str_replace("(Keyword)",$metaKeyword,$keywords);

        $h1 = str_replace("(TenderType)",$metaTender_Type,$tkmeta_arr['h1']);

        $final_h1 = str_replace("(Keyword)",$metaKeyword,$h1);

        $content = str_replace("(TenderType)",$metaTender_Type,$tkmeta_arr['content']);

        $final_content = str_replace("(Keyword)",$metaKeyword,$content);

        $result['meta']['title'] = $final_title;

        $result['meta']['description'] = $final_description;

        $result['meta']['keywords'] = $final_keywords;

        $result['meta']['h1'] = $final_h1;

        $result['meta']['content'] = $final_content;
        
        if($type == 'tender-type-keyword'){
            $result['meta']['label1'] = $metaTenderType ?? '';

            $result['meta']['label2'] = $metaKeyword ?? '';
        }else{
            $result['meta']['label1'] = $metaKeyword ?? '';

            $result['meta']['label2'] = $metaTenderType ?? '';
        }

        
    }else{

        $result['meta']['title'] = '';

        $result['meta']['description'] = '';

        $result['meta']['keywords'] = '';

        $result['meta']['h1'] = '';

        $result['meta']['content'] = '';

        $result['meta']['label1'] = '';

        $result['meta']['label2'] = '';

    }



    return $result;

}



if ($result === null) {

    echo json_encode(array("status" => "error"));

} else {

    echo json_encode(array("status" => " success", "data" => $result));

}

die();

