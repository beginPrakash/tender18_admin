<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

function getMonthWithYearList() {
    $data = [];
    $currentYear = date("Y");
    $currentMonth = (int)date("m");

    // Quarters for current and previous year
    for ($year = $currentYear; $year >= $currentYear - 1; $year--) {
        for ($q = 4; $q >= 1; $q--) {
            $startMonth = ($q - 1) * 3 + 1;
            $endMonth = $q * 3;
            
            // Skip future quarters in current year
            if ($year == $currentYear && $startMonth > $currentMonth) {
                continue;
            }

            $startDate = sprintf("%04d-%02d-01", $year, $startMonth);
            $endDate = date("Y-m-t", strtotime(sprintf("%04d-%02d-01", $year, $endMonth)));
            
            $data[] = [
                "label" => "Q$q $year",
                "value" => "$startDate|$endDate"
            ];
        }
    }

    // Last 12 months
    for ($i = 0; $i < 12; $i++) {
        $timestamp = strtotime("-$i months");
        $startDate = date("Y-m-01", $timestamp);
        $endDate = date("Y-m-t", $timestamp);
        $label = date("F Y", $timestamp);
        
        $data[] = [
            "label" => $label,
            "value" => "$startDate|$endDate"
        ];
    }

    return $data;
}

$response = [
    "status" => "success",
    "data" => getMonthWithYearList()
];

echo json_encode($response);
exit;
