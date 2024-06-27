<?php

include '../includes/authentication.php';



error_reporting(0);



// Include the PHPExcel library

require '../PHPExcel/Classes/PHPExcel.php';



$newFileName = $_POST['filename'];



// Now you can process the Excel file using PHPExcel

$objPHPExcel = PHPExcel_IOFactory::load($newFileName);

$worksheet = $objPHPExcel->getActiveSheet();



// $cellValue = $worksheet->getCell('A2')->getValue();

// echo 'Data in cell A2: ' . $cellValue;



// Get the highest row and column in the worksheet

$highestRow = $worksheet->getHighestRow();

$highestColumn = $worksheet->getHighestColumn();



// Initialize an array to store the results

$results = array();



// Loop through the rows and columns to get the data

for ($row = 1; $row <= $highestRow; $row++) {

    $rowData = array();

    for ($col = 'A'; $col <= $highestColumn; $col++) {

        $cellValue = $worksheet->getCell($col . $row)->getValue();

        if ($cellValue === null || $cellValue === '') {

            $rowData[] = null;
        } else {

            if ($col == 'G' || $col == 'H' || $col == 'I') {

                $rowData[] = $cellValue . "value";
            } else {

                $rowData[] = $cellValue;
            }
        }
    }

    $results[] = $rowData;
}



$data_send = [];



$logfilename1 =  'error-logs.txt';

$logfilelocation1 = dirname(__FILE__) . '/' . $logfilename1;

$fileHandle = fopen($logfilelocation1, 'w');

fclose($fileHandle);



//$tender_ref_no = '';

$header_data = mysqli_query($con, "SELECT `value` FROM `settings` where `key` ='last_ref_id'");
$header_result = mysqli_num_rows($header_data);

if ($header_result == 1) {
    while ($row = mysqli_fetch_assoc($header_data)) {
        $tender_ref_no = $row['value'];
    }
}

/*$header_data = mysqli_query($con, "SELECT * FROM `tenders_posts` ORDER BY `id` DESC LIMIT 1");
$header_result = mysqli_num_rows($header_data);

if ($header_result == 1) {
    while ($row = mysqli_fetch_assoc($header_data)) {
        $tender_ref_no = $row['ref_no'];
    }
}

if (empty($tender_ref_no)) {

    $tender_ref_no = 200000;
}*/

for ($i = 1; $i < count($results); $i++) {



    $error_print = "";

    $error_print_status = false;

    $error_print_status1 = false;



    $tender_title = $results[$i][0];

    $tender_id = $results[$i][1];

    $tender_agency = $results[$i][2];

    $tender_pincode = $results[$i][3];

    $tender_city = $results[$i][4];

    $tender_state = $results[$i][5];

    $tender_value = $results[$i][6];

    $tender_fee = $results[$i][7];

    $tender_emd = $results[$i][8];

    $documents = $results[$i][12];

    $tender_department = $results[$i][13];

    $boq_title = $results[$i][14];

    $tender_type = $results[$i][15];



    $publish_timestap = PHPExcel_Shared_Date::ExcelToPHP($results[$i][9]);

    $due_timestap = PHPExcel_Shared_Date::ExcelToPHP($results[$i][10]);

    $opening_timestap = PHPExcel_Shared_Date::ExcelToPHP($results[$i][11]);



    $publish_date = date('Y-m-d', $publish_timestap);

    $due_date = date('Y-m-d', $due_timestap);

    $opening_date = date('Y-m-d', $opening_timestap);



    if (empty($tender_value)) {

        $tender_value = 'Refer Document';
    } else {

        $tender_value = str_replace("value", "", $tender_value);
    }

    if (empty($tender_fee)) {

        $tender_fee = 'Refer Document';
    } else {

        $tender_fee = str_replace("value", "", $tender_fee);
    }

    if (empty($tender_emd)) {

        $tender_emd = 'Refer Document';
    } else {

        $tender_emd = str_replace("value", "", $tender_emd);
    }



    if (!empty($tender_city) && !empty($tender_state) && !empty($tender_pincode)) {

        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `zipcode`='" . $tender_pincode . "'");

        $tenders_result = mysqli_num_rows($tenders_data);

        if ($tenders_result > 0) {

            $data = mysqli_fetch_assoc($tenders_data);

            $tender_city = $data['city'];

            $tender_state = $data['state'];
        } else {

            $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `city`='" . $tender_city . "'");

            $tenders_result = mysqli_num_rows($tenders_data);

            if ($tenders_result > 0) {

                $data = mysqli_fetch_assoc($tenders_data);

                // $tender_pincode = $data['zipcode'];

                $tender_pincode = "";

                $tender_state = $data['state'];
            } else {

                $error_print_status = true;
            }

            // $error_print_status = true;

        }

        // $tender_city = ucwords($tender_city);

        // $tender_state = ucwords($tender_state);

        $tender_city = $tender_city;

        $tender_state = $tender_state;
    }



    if (!empty($tender_city) && empty($tender_state) && !empty($tender_pincode)) {

        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `zipcode`='" . $tender_pincode . "'");

        $tenders_result = mysqli_num_rows($tenders_data);

        if ($tenders_result > 0) {

            $data = mysqli_fetch_assoc($tenders_data);

            $tender_city = $data['city'];

            $tender_state = $data['state'];
        } else {

            $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `city`='" . $tender_city . "'");

            $tenders_result = mysqli_num_rows($tenders_data);

            if ($tenders_result > 0) {

                $data = mysqli_fetch_assoc($tenders_data);

                // $tender_pincode = $data['zipcode'];

                $tender_pincode = "";

                $tender_state = $data['state'];
            } else {

                $error_print_status = true;
            }

            // $error_print_status = true;

        }

        // $tender_city = ucwords($tender_city);

        // $tender_state = ucwords($tender_state);

        $tender_city = $tender_city;

        $tender_state = $tender_state;
    }



    if (!empty($tender_city) && empty($tender_pincode) && !empty($tender_state)) {

        // $tender_city = ucwords($tender_city);

        $tender_city = $tender_city;

        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `city`='" . $tender_city . "'");

        $tenders_result = mysqli_num_rows($tenders_data);

        if ($tenders_result > 0) {

            $data = mysqli_fetch_assoc($tenders_data);

            // $tender_pincode = $data['zipcode'];

            $tender_pincode = "";

            $tender_state = $data['state'];
        } else {

            $error_print_status = true;
        }

        // $tender_state = ucwords($tender_state);

        $tender_state = $tender_state;
    }



    if (empty($tender_city) && !empty($tender_state) && !empty($tender_pincode)) {

        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `zipcode`='" . $tender_pincode . "'");

        $tenders_result = mysqli_num_rows($tenders_data);

        if ($tenders_result > 0) {

            $data = mysqli_fetch_assoc($tenders_data);

            $tender_city = $data['city'];

            $tender_state = $data['state'];
        } else {

            $error_print_status = true;
        }

        // $tender_city = ucwords($tender_city);

        // $tender_state = ucwords($tender_state);

        $tender_city = $tender_city;

        $tender_state = $tender_state;
    }



    if (empty($tender_city) && empty($tender_state) && !empty($tender_pincode)) {

        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `zipcode`='" . $tender_pincode . "'");

        $tenders_result = mysqli_num_rows($tenders_data);

        if ($tenders_result > 0) {

            $data = mysqli_fetch_assoc($tenders_data);

            $tender_city = $data['city'];

            $tender_state = $data['state'];
        } else {

            $error_print_status = true;
        }

        // $tender_city = ucwords($tender_city);

        // $tender_state = ucwords($tender_state);

        $tender_city = $tender_city;

        $tender_state = $tender_state;
    }



    if (!empty($tender_city) && empty($tender_pincode) && empty($tender_state)) {

        // $tender_city = ucwords($tender_city);

        $tender_city = $tender_city;

        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `city`='" . $tender_city . "'");

        $tenders_result = mysqli_num_rows($tenders_data);

        if ($tenders_result > 0) {

            $data = mysqli_fetch_assoc($tenders_data);

            // $tender_pincode = $data['zipcode'];

            $tender_pincode = "";

            $tender_state = $data['state'];
        } else {

            $error_print_status = true;
        }

        // $tender_state = ucwords($tender_state);

        $tender_state = $tender_state;
    }



    if (empty($tender_city) && empty($tender_pincode) && !empty($tender_state)) {

        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where `state`='" . $tender_state . "'");

        $tenders_result = mysqli_num_rows($tenders_data);

        if ($tenders_result > 0) {

            $data = mysqli_fetch_assoc($tenders_data);

            $tender_pincode = "";

            $tender_city = "";

            $tender_state = $data['state'];
        } else {

            $error_print_status = true;
        }

        $tender_state = $tender_state;
    }



    if (empty($tender_city) && empty($tender_pincode) && empty($tender_state)) {

        $error_print_status = true;
    }



    if ($error_print_status) {

        $error_print = $tender_id . " Invalid city/state";
    }



    if (!empty($tender_agency)) {

        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_agencies` where `pseudo_name`='" . $tender_agency . "'");

        $tenders_result = mysqli_num_rows($tenders_data);

        if ($tenders_result > 0) {

            $data = mysqli_fetch_assoc($tenders_data);

            $tender_agency = $data['agency_name'];
        } else {

            $error_print_status1 = true;
        }
    }



    if ($error_print_status1) {

        if (!empty($error_print)) {

            $error_print .= " and Invalid agency";
        } else {

            $error_print = $tender_id . " Invalid agency";
        }
    }



    echo $error_print;



    if (!empty($error_print)) {

        file_put_contents($logfilelocation1, $error_print . PHP_EOL, FILE_APPEND);
    }



    if (!empty($tender_state)) {

        // $tender_state = ucwords($tender_state);

        $tender_state = $tender_state;
    }



    $tender_title = mysqli_real_escape_string($con, $tender_title);

    $tender_agency = mysqli_real_escape_string($con, $tender_agency);

    $tender_department = mysqli_real_escape_string($con, $tender_department);

    $boq_title = mysqli_real_escape_string($con, $boq_title);

    $tender_type = mysqli_real_escape_string($con, $tender_type);



    if (!$error_print_status && !$error_print_status1) {

        $tender_ref_no = $tender_ref_no + 1;

        $q1 = "INSERT INTO tenders_posts(`title`, `tender_id`, `ref_no`, `agency_type`, `due_date`, `tender_value`, `pincode`, `publish_date`, `tender_fee`, `tender_emd`, `documents`, `city`, `state`, `department`, `description`, `tender_type`, `opening_date`) VALUES ('" . $tender_title . "', '" . $tender_id . "', '" . $tender_ref_no . "', '" . $tender_agency . "', '" . $due_date . "', '" . $tender_value . "', '" . $tender_pincode . "', '" . $publish_date . "', '" . $tender_fee . "', '" . $tender_emd . "', '" . $documents . "', '" . $tender_city . "', '" . $tender_state . "', '" . $tender_department . "', '" . $boq_title . "', '" . $tender_type . "', '" . $opening_date . "')";

        $sql1 = mysqli_query($con, $q1);

        $ref = "UPDATE settings SET value = '" . $tender_ref_no . "' WHERE `key` = 'last_ref_id'";
        $ref1 = mysqli_query($con, $ref);
    }



    $data_send[] = $tender_id;
}



echo "<pre>";

print_r($data_send);

echo "</pre>";

die();
