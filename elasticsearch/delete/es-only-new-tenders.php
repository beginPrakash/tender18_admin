<?php
/**
 * es-only-new-tenders.php
 * 
 * Script to safely delete records ONLY from Elasticsearch
 * based on a `created_at` date range in `tenders_posts` table.
 * 
 * Run from CLI:
 * php es-only-new-tenders.php 2026-05-01 2026-05-28
 */

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

// Handle large datasets safely
set_time_limit(0);
ini_set('memory_limit', '-1');

// Adjust paths depending on the exact execution location
require_once __DIR__ . '/../elastic_client.php';
require_once __DIR__ . '/../../admin/includes/connection.php';

if (!$con) {
    die("MySQL connect error: " . mysqli_connect_error() . "\n");
}

$index = ES_INDEXES['NEW'];

// 1. Validate Input Dates
if ($argc < 2) {
    echo "Usage: php es-only-new-tenders.php YYYY-MM-DD [YYYY-MM-DD]\n";
    echo "Example: php es-only-new-tenders.php 2026-05-01 2026-05-28\n";
    exit(1);
}

$start_date = $argv[1];
$end_date = isset($argv[2]) ? $argv[2] : $start_date;

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if (!validateDate($start_date) || !validateDate($end_date)) {
    die("Error: Invalid date format. Please use YYYY-MM-DD.\n");
}

// Convert to date ranges (from 00:00:00 to 23:59:59)
$start_datetime = $start_date . " 00:00:00";
$end_datetime = $end_date . " 23:59:59";

echo "Checking records from '$start_datetime' to '$end_datetime'...\n";

// 2. Count matching records directly in Elasticsearch
$queryBody = [
    'query' => [
        'range' => [
            'created_at' => [
                'gte' => $start_datetime,
                'lte' => $end_datetime,
                'format' => 'yyyy-MM-dd HH:mm:ss'
            ]
        ]
    ]
];

$countResp = es_request('POST', $index . '/_count', json_encode($queryBody));
$countObj = is_array($countResp['body']) ? $countResp['body'] : json_decode($countResp['body'], true);
$total_found = $countObj['count'] ?? 0;

if ($total_found === 0) {
    echo "No matching records found in Elasticsearch index '$index'. Exiting.\n";
    exit(0);
}

echo "Found $total_found records to delete directly from Elasticsearch index '$index'.\n";

// 3. Add confirmation prompt
echo "Are you sure you want to delete $total_found records? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
if (strtolower($line) !== 'yes') {
    echo "Aborted.\n";
    exit(0);
}

echo "\nExecuting Elasticsearch _delete_by_query...\n";

// 4. Execute _delete_by_query
$totalEsDeleted = 0;
$failed = 0;

try {
    $delResp = es_request('POST', $index . '/_delete_by_query?conflicts=proceed', json_encode($queryBody));
    $delObj = is_array($delResp['body']) ? $delResp['body'] : json_decode($delResp['body'], true);
    
    if (isset($delObj['deleted'])) {
        $totalEsDeleted = $delObj['deleted'];
    }
    
    if (isset($delObj['version_conflicts']) && $delObj['version_conflicts'] > 0) {
        $failed = $delObj['version_conflicts'];
        echo "Warning: {$delObj['version_conflicts']} version conflicts occurred.\n";
    }
    
    if (isset($delObj['failures']) && !empty($delObj['failures'])) {
        echo "Warning: Some shards failed during deletion.\n";
    }
    
} catch (Exception $e) {
    echo "Error communicating with Elasticsearch: " . $e->getMessage() . "\n";
    exit(1);
}

// 5. Print Execution Summary
echo "\n=====================================\n";
echo "         EXECUTION SUMMARY         \n";
echo "=====================================\n";
echo "Total Records Found   : $total_found\n";
echo "ES Deleted Count      : $totalEsDeleted\n";

if ($failed > 0 || $totalEsDeleted < $total_found) {
    echo "Status                : COMPLETED WITH ISSUES (Check ES logs)\n";
} else {
    echo "Status                : SUCCESS\n";
}
echo "=====================================\n";

