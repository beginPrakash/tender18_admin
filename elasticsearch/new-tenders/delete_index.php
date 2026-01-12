<?php
require_once '../elastic_client.php';

$index = ES_INDEXES['NEW'];
$resp = es_request('DELETE', $index);
print_r($resp);
