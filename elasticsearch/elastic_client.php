<?php
// elastic_client.php
// Simple Elasticsearch helper for PHP (no composer dependency)
// Requires PHP with curl and json support (PHP 7.0+)
require_once 'config.php';

$ES_HOST = ES_HOST; // change if needed (e.g. http://localhost:9200)

function es_request($method, $path, $body = null) {
    global $ES_HOST;
    $url = rtrim($ES_HOST, '/') . '/' . ltrim($path, '/');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Connection: keep-alive']);
    curl_setopt($ch, CURLOPT_TCP_KEEPALIVE, 1);
    curl_setopt($ch, CURLOPT_TCP_KEEPIDLE, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);   // fail fast if ES unreachable
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);          // max 30s for the full request
    if (!is_null($body)) {
        // If body is already a string (bulk API), send raw; otherwise json-encode
        if (is_string($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }
    }
    $resp = curl_exec($ch);
    $errno = curl_errno($ch);
    $err = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($errno) {
        throw new Exception('cURL error: ' . $err);
    }
    $decoded = json_decode($resp, true);
    return array('status' => $http_code, 'body' => $decoded);
}

function es_index_document($index, $id, $doc) {
    $path = "$index/_doc/" . urlencode($id);
    return es_request('PUT', $path, $doc);
}

function es_delete_document($index, $docId) {
    $path = $index . '/_doc/' . urlencode($docId);
    return es_request('DELETE', $path);
}

function es_bulk_delete_by_ids(array $ids, string $index, int $chunkSize = 1000): array
{
    if (empty($ids)) {
        return [ 'deleted' => 0, 'message' => 'No IDs provided'];
    }

    $deleted = 0;

    // Split IDs into chunks to avoid ES payload limit
    $chunks = array_chunk($ids, $chunkSize);

    foreach ($chunks as $batch) {

        $bulk = [];

        foreach ($batch as $id) {
            $bulk[] = json_encode([
                'delete' => [
                    '_index' => $index,
                    '_id'    => (string)$id
                ]
            ]);
        }

        $body = implode("\n", $bulk) . "\n";

        $response = es_request('POST', '_bulk', $body);

        // Count successful deletes
        if (!empty($response['body']['items'])) {
            foreach ($response['body']['items'] as $item) {
                if (isset($item['delete']['result']) && $item['delete']['result'] === 'deleted') {
                    $deleted++;
                }
            }
        }
    }

    return [
        'deleted' => $deleted,
        'message' => 'Bulk delete completed'
    ];
}

function es_search($index, $queryBody) {
    $path = "$index/_search";
    return es_request('POST', $path, $queryBody);
}
?>