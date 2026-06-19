<?php
// reset_tenders_field.php
// Sets the "tenders" field to "" on EVERY document in the ALL index.
//
// Uses wait_for_completion=false so ES returns a task ID immediately,
// then polls _tasks/{id} until done — avoids the 30 s cURL timeout.
//
// Run from CLI: php reset_tenders_field.php

set_time_limit(0);

require_once '../../elastic_client.php';

$index     = ES_INDEXES['ALL'];
$pollEvery = 5; // seconds between task-status polls

$payload = [
    'script' => [
        'source' => 'ctx._source.tenders = ""',
        'lang'   => 'painless'
    ],
    'query' => [
        'terms' => [
            'tenders.keyword' => ['new', 'live']
        ]
    ]
];

echo "Submitting async update_by_query on index: {$index}\n";

try {

    // ----------------------------------------------------------------
    // 1. Fire the job asynchronously — returns instantly with a task ID
    // ----------------------------------------------------------------
    $resp = es_request(
        'POST',
        $index . '/_update_by_query?wait_for_completion=false&conflicts=proceed',
        $payload
    );

    if (empty($resp['body']['task'])) {
        echo "Unexpected response (code {$resp['status']}):\n";
        print_r($resp['body']);
        exit(1);
    }

    $taskId = $resp['body']['task'];
    echo "Task started   : {$taskId}\n";
    echo "Polling every  : {$pollEvery}s ...\n\n";

    // ----------------------------------------------------------------
    // 2. Poll until the task is complete
    // ----------------------------------------------------------------
    while (true) {

        sleep($pollEvery);

        $taskResp = es_request('GET', '_tasks/' . $taskId);
        $task     = $taskResp['body'] ?? [];

        $completed = $task['completed'] ?? false;
        $status    = $task['task']['status'] ?? [];

        $total   = $status['total']   ?? '?';
        $updated = $status['updated'] ?? '?';
        $created = $status['created'] ?? 0;
        $noops   = $status['noops']   ?? 0;

        echo "  total={$total}  updated={$updated}  created={$created}  noops={$noops}\n";

        if ($completed) {
            break;
        }
    }

    // ----------------------------------------------------------------
    // 3. Final summary
    // ----------------------------------------------------------------
    $failures = $task['response']['failures'] ?? [];

    echo "\nDone!\n";
    echo "Updated  : " . ($task['response']['updated'] ?? 'N/A') . "\n";
    echo "Total    : " . ($task['response']['total']   ?? 'N/A') . "\n";
    echo "Failures : " . count($failures) . "\n";

    if (!empty($failures)) {
        echo "Failure details:\n";
        print_r($failures);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
