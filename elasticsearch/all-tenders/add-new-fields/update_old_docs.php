<?php
require_once '../../elastic_client.php';

$index = ES_INDEXES['ALL'];

$payload = array(
    'script' => array(
        'source' => 'ctx._source.tenders = ""',
        'lang'   => 'painless'
    ),
    'query' => array(
        'bool' => array(
            'must_not' => array(
                'exists' => array(
                    'field' => 'tenders'
                )
            )
        )
    )
);

try {

    $resp = es_request(
        'POST',
        $index . '/_update_by_query',
        $payload
    );

    echo "Response code: " . $resp['status'] . PHP_EOL;
    print_r($resp['body']);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
?>