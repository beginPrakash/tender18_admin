<?php
require_once dirname(__DIR__) . '/elastic_client.php';
require_once dirname(__DIR__, 2) . '/admin/includes/connection.php';

/**
 * Delete records from MySQL 'tenders_archive' table based on created_at range.
 * 
 * @param string $start_date Format: YYYY-MM-DD HH:MM:SS or YYYY-MM-DD
 * @param string $end_date   Format: YYYY-MM-DD HH:MM:SS or YYYY-MM-DD
 * @return int Number of deleted MySQL rows
 */
function delete_archive_tender_mysql_records_by_range($start_date, $end_date) {
    global $con;
    if (!$con) {
        throw new Exception("MySQL database connection not available.");
    }

    $sql = "DELETE FROM tenders_archive WHERE created_at >= ? AND created_at <= ?";
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("MySQL prepare error: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $deleted_count = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    return $deleted_count;
}

/**
 * Delete records from Elasticsearch 'ARCHIVE' tenders index based on created_at range.
 * 
 * @param string $start_date Format: YYYY-MM-DD HH:MM:SS
 * @param string $end_date   Format: YYYY-MM-DD HH:MM:SS
 * @return array Response from Elasticsearch
 */
function delete_archive_tender_es_records_by_range($start_date, $end_date) {
    $index = ES_INDEXES['ARCHIVE'];
    $path = $index . '/_delete_by_query';
    
    $body = [
        'query' => [
            'range' => [
                'created_at' => [
                    'gte' => $start_date,
                    'lte' => $end_date
                ]
            ]
        ]
    ];
    
    return es_request('POST', $path, $body);
}

/**
 * Delete records from BOTH MySQL ('tenders_archive') and Elasticsearch ('ARCHIVE' index) based on created_at range.
 * 
 * @param string $start_date Format: YYYY-MM-DD HH:MM:SS
 * @param string $end_date   Format: YYYY-MM-DD HH:MM:SS
 * @return array Combined result with ES response and MySQL deleted count
 */
function delete_archive_tender_records_by_range($start_date, $end_date) {
    $es_res = delete_archive_tender_es_records_by_range($start_date, $end_date);
    $mysql_deleted = delete_archive_tender_mysql_records_by_range($start_date, $end_date);

    return [
        'status' => $es_res['status'] ?? 200,
        'body' => $es_res['body'] ?? [],
        'es' => $es_res,
        'mysql_deleted' => $mysql_deleted
    ];
}
?>
