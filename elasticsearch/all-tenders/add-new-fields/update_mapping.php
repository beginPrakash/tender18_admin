<?php
require_once '../../elastic_client.php';

$index = ES_INDEXES['ALL'];

$mapping = array(
    'properties' => array(
        'tenders' => array(
            'type' => 'keyword'
        )
    )
);

try {
    $resp = es_request(
        'PUT',
        $index . '/_mapping',
        $mapping
    );

    echo "Response code: " . $resp['status'] . PHP_EOL;
    print_r($resp['body']);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
?>