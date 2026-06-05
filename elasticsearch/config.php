<?php
/**
 * Elasticsearch central configuration
 * Supports multiple indexes & environments
 */

$ES_ENV = 'dev'; // dev | prod

/* ------------------ HOSTS ------------------ */
$ES_HOSTS = [
    'dev'  => 'http://localhost:9200',
    'prod' => 'http://localhost:9200'
];

/* ------------------ INDEX MAP ------------------ */
$ES_INDEXES = [
    'dev' => [
        'ALL'      => 'tenders_dev',
        'LIVE'     => 'tenders_live_dev',
        'ARCHIVE'  => 'tenders_archive_dev',
        'NEW'      => 'tenders_new_dev'
    ],
    'prod' => [
        'ALL'      => 'tenders_prod', // tenders_prod_v2
        'LIVE'     => 'tenders_live_prod',
        'ARCHIVE'  => 'tenders_archive_prod',
        'NEW'      => 'tenders_new_prod'
    ]
];

/* ------------------ VALIDATION ------------------ */
if (!isset($ES_HOSTS[$ES_ENV])) {
    die('❌ Invalid ES_ENV: ' . $ES_ENV);
}

/* ------------------ CONSTANTS ------------------ */
define('ES_ENV', $ES_ENV);
define('ES_HOST', $ES_HOSTS[$ES_ENV]);
define('ES_INDEXES', $ES_INDEXES[$ES_ENV]);