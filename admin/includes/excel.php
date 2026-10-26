<?php
// PhpSpreadsheet loader + helpers (replaces the old PHPExcel library, which does not run on PHP 8+)
$phpSpreadsheetAutoload = __DIR__ . '/../PhpSpreadsheet/vendor/autoload.php';
if (!is_file($phpSpreadsheetAutoload)) {
    // Without this check a missing library is a blank HTTP 500 (the upload scripts use error_reporting(0))
    error_log('PhpSpreadsheet not found at ' . $phpSpreadsheetAutoload . ' - upload the admin/PhpSpreadsheet/ folder');
    http_response_code(500);
    exit('Excel library missing on the server: upload the admin/PhpSpreadsheet/ folder.');
}
require_once $phpSpreadsheetAutoload;

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

//---------- Excel date cell to unix timestamp ----------//
// Drop-in replacement for PHPExcel_Shared_Date::ExcelToPHP().
// Empty cells return today's date (same as PHPExcel did); text dates in
// DD-MM-YYYY or DD/MM/YYYY are parsed instead of raising a TypeError.
function excel_date_to_timestamp($value)
{
    if (is_numeric($value)) {
        return ExcelDate::excelToTimestamp((float)$value);
    }

    $value = trim((string)$value);
    foreach (['d-m-Y', 'd/m/Y'] as $format) {
        $date = DateTime::createFromFormat('!' . $format, $value, new DateTimeZone('UTC'));
        if ($date !== false) {
            return $date->getTimestamp();
        }
    }

    return gmmktime(0, 0, 0);
}
