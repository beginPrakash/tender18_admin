<?php

include '../includes/connection.php';
include '../includes/functions.php';

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');

$result = get_results($con);

function get_results($con)
{
    $result = [];

    // Start from current month
    $currentDate = new DateTime(date('Y-m-01'));

    // Oldest limit
    $minDate = new DateTime('2021-01-01');

    $index = 0;

    while ($currentDate >= $minDate) {

        // Quarter end month = current month
        $endDate = clone $currentDate;
        $endDate->modify('last day of this month');

        // Quarter start month = current month -2 months
        $startDate = clone $currentDate;
        $startDate->modify('-2 months');
        $startDate->modify('first day of this month');

        // Label Example: Mar to May 2026
        $startMonth = $startDate->format('M');
        $endMonth   = $endDate->format('M');

        // If year changes
        if ($startDate->format('Y') != $endDate->format('Y')) {

            $label = $startMonth . ' ' . $startDate->format('Y') .
                ' to ' .
                $endMonth . ' ' . $endDate->format('Y');

        } else {

            $label = $startMonth . ' to ' .
                $endMonth . ' ' . $endDate->format('Y');
        }

        $result[$index]['label'] = $label;

        $result[$index]['value'] =
            $startDate->format('d-m-Y') .
            '/' .
            $endDate->format('d-m-Y');

        // Move backward by 3 months
        $currentDate->modify('-3 months');

        $index++;
    }

    return $result;
}

if ($result === null) {

    echo json_encode(array("status" => "error"));

} else {

    echo json_encode(array(
        "status" => "success",
        "data" => $result
    ));
}

die();