<?php
include '../includes/authentication.php';

error_reporting(0);

// Include PhpSpreadsheet (replaces PHPExcel, which does not run on PHP 8)
require '../includes/excel.php';

$newFileName = $_POST['filename'];

// Now you can process the Excel file using PhpSpreadsheet
$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($newFileName);
$worksheet = $objPHPExcel->getActiveSheet();

// $cellValue = $worksheet->getCell('A2')->getValue();
// echo 'Data in cell A2: ' . $cellValue;

// Get the highest row and column in the worksheet
$highestRow = $worksheet->getHighestRow();
$highestColumn = $worksheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

// Initialize an array to store the results
$results = array();

// Loop through the rows and columns to get the data
for ($row = 1; $row <= $highestRow; $row++) {
    $rowData = array();
    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $cellValue = $worksheet->getCell([$col, $row])->getValue();
        $rowData[] = $cellValue;
    }
    $results[] = $rowData;
}

$data = [];
for ($i = 1; $i < count($results); $i++) {
    $pseudo_name = mysqli_real_escape_string($con, $results[$i][0]);
    $agency_name = mysqli_real_escape_string($con, $results[$i][1]);

    $q1 = "INSERT INTO tender_agencies(`pseudo_name`, `agency_name`) VALUES ('" . $pseudo_name . "', '" . $agency_name . "')";
    $sql1 = mysqli_query($con, $q1);

    $data[] = $results[$i][0];
}
print_r($data);
die();
