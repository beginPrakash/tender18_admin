<?php
// create_index.php
require_once '../elastic_client.php';

$index = ES_INDEXES['NEW'];

$mapping = array(
    'settings' => array(
        'analysis' => array(
            'analyzer' => array(
                'default' => array('type' => 'english')
            )
        )
    ),
    'mappings' => array(
        'properties' => array(

            // 2025_LSGD_805030_6
            'ref_no' => array(
                'type' => 'keyword'
            ),

            // 12088669
            'tender_id' => array(
                'type' => 'keyword'
            ),

            // etender
            'department' => array(
                'type' => 'text',
                'fields' => array(
                    'keyword' => array('type' => 'keyword')
                )
            ),

            // normal tender
            'tender_type' => array(
                'type' => 'text',
                'fields' => array(
                    'keyword' => array('type' => 'keyword')
                )
            ),

            // thodupuzha
            'city' => array(
                'type' => 'text',
                'fields' => array(
                    'keyword' => array('type' => 'keyword')
                )
            ),

            // kerala
            'state' => array(
                'type' => 'text',
                'fields' => array(
                    'keyword' => array('type' => 'keyword')
                )
            ),

            // 685584
            'pincode' => array(
                'type' => 'keyword'
            ),

            // tender for general-maintenance jairani school road...
            'title' => array(
                'type' => 'text',
                'fields' => array(
                    'keyword' => array(
                        'type' => 'keyword',
                        'ignore_above' => 256
                    )
                )
            ),


            // https://etenders.kerala.gov.in/...
            'description' => array(
                'type' => 'text'
            ),

            // local self government department LSGD
            'agency_type' => array(
                'type' => 'text',
                'fields' => array(
                    'keyword' => array('type' => 'keyword')
                )
            ),

            // 2025-10-04
            'publish_date' => array(
                'type'   => 'date',
                'format' => 'yyyy-MM-dd||epoch_millis'
            ),

            // 2025-10-13
            'due_date' => array(
                'type'   => 'date',
                'format' => 'yyyy-MM-dd||epoch_millis'
            ),

            // 1386016
            'tender_value' => array(
                'type' => 'double'
            ),

            // 2760
            'tender_fee' => array(
                'type' => 'double'
            ),

            // 34650
            'tender_emd' => array(
                'type' => 'double'
            ),

            // 2025-10-13
            'opening_date' => array(
                'type'   => 'date',
                'format' => 'yyyy-MM-dd||epoch_millis'
            ),

            // https://bidplus.gem.gov.in/showbidDocument/5607007
            'documents' => array(
                'type' => 'text'
            ),
        )
    )
);

try {
    $resp = es_request('PUT', $index, $mapping);
    echo "Response code: " . $resp['status'] . PHP_EOL;
    print_r($resp['body']);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
?>
