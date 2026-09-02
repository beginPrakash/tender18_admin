<?php
require_once 'live-tenders/delete_range.php';
require_once 'elastic_client.php';
require_once '../admin/includes/connection.php';

$index = ES_INDEXES['LIVE'];
// php delete_live_tender_records_by_range.php 2026-09-01 2026-09-01

// Allow dates from CLI arguments if provided, else use default sample range
if (isset($argv[1])) {
    $start = (strlen($argv[1]) === 10) ? $argv[1] . ' 00:00:00' : $argv[1];
} else {
    $start = '1997-02-24 00:00:00';
}

if (isset($argv[2])) {
    $end = (strlen($argv[2]) === 10) ? $argv[2] . ' 23:59:59' : $argv[2];
} else {
    $end = isset($argv[1]) ? (strlen($argv[1]) === 10 ? $argv[1] . ' 23:59:59' : $argv[1]) : '1997-02-24 23:59:59';
}

echo "Checking records in index '$index' and MySQL table 'tenders_live' between '$start' and '$end'...\n";

/**
 * Get count of matching records in MySQL 'tenders_live' for the given date range.
 */
function get_mysql_count($con, $start, $end) {
    if (!$con) return 0;
    $sql = "SELECT COUNT(id) as total FROM tenders_live WHERE created_at >= ? AND created_at <= ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $start, $end);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        return (int)($row['total'] ?? 0);
    }
    return 0;
}

$query = [
    'query' => [
        'range' => ['created_at' => ['gte' => $start, 'lte' => $end]]
    ]
];

try {
    // Check Elasticsearch count before
    $resp = es_search($index, $query);
    $es_count_before = $resp['body']['hits']['total']['value'] ?? 0;

    // Check MySQL count before
    $mysql_count_before = get_mysql_count($con, $start, $end);

    echo "Count before -> ES: $es_count_before | MySQL: $mysql_count_before\n";

    if ($es_count_before > 0 || $mysql_count_before > 0) {
        echo "Found records (ES: $es_count_before, MySQL: $mysql_count_before). Proceeding with delete...\n";
        $res = delete_live_tender_records_by_range($start, $end);
        
        echo "Delete Response Status: " . ($res['status'] ?? 'N/A') . "\n";
        echo "Deleted count from ES response: " . ($res['body']['deleted'] ?? 0) . "\n";
        echo "Deleted count from MySQL: " . ($res['mysql_deleted'] ?? 0) . "\n";
        
        // Verification after deletion
        $resp_after = es_search($index, $query);
        $es_count_after = $resp_after['body']['hits']['total']['value'] ?? 0;
        $mysql_count_after = get_mysql_count($con, $start, $end);

        echo "Count after -> ES: $es_count_after | MySQL: $mysql_count_after\n";
    } else {
        echo "No records found in this range in Elasticsearch or MySQL.\n";
    }
} catch (Exception $e) {
    echo "Error during verification/execution: " . $e->getMessage() . "\n";
}
?>
