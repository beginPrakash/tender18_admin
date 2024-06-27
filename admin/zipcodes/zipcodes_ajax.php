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
        $rowData[] = $cellValue;
    }
    $results[] = $rowData;
}

$data = [];
for ($i = 1; $i < count($results); $i++) {

    $q1 = "INSERT INTO tender_zipcodes(`zipcode`, `city`, `state`) VALUES ('" . $results[$i][0] . "', '" . $results[$i][1] . "', '" . $results[$i][2] . "')";
    $sql1 = mysqli_query($con, $q1);

    $data[] = $results[$i][0];
}
print_r($data);
die();
