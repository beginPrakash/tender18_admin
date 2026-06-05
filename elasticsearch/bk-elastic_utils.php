<?php

function highlight_search_term($text, $searchTerm)
{
    $highlightMarkup = '<b>';
    $closingHighlightMarkup = '</b>';
    $highlightedText = preg_replace("/({$searchTerm})/i", $highlightMarkup . '$1' . $closingHighlightMarkup, $text);
    return $highlightedText;
}

function normalize_filters_array($value, $toLower = true)
{
    if (empty($value)) return [];

    $normalize = function ($v) use ($toLower) {
        $v = trim($v);
        return $toLower ? mb_strtolower($v) : $v;
    };

    if (is_array($value)) {
        return array_values(
            array_filter(
                array_map($normalize, $value)
            )
        );
    }

    // Convert comma-separated string to array
    return array_values(
        array_filter(
            array_map(
                $normalize,
                explode(',', $value)
            )
        )
    );
}

/**
 * Priority resolver
 * 1. POST value (if not empty)
 * 2. User stored value
 * 3. Default
 */
function prefer($primary, $secondary = null, $default = null)
{
    // Handle arrays
    if (is_array($primary)) {
        return !empty($primary)
            ? $primary
            : (!empty($secondary) ? $secondary : $default);
    }

    // Handle numbers and strings safely
    if ($primary !== null && $primary !== '' && $primary !== 0) {
        return $primary;
    }

    if ($secondary !== null && $secondary !== '' && $secondary !== 0) {
        return $secondary;
    }

    return $default;
}

function merge_keywords(string $keywords = '', string $words = ''): string
{
    $arr1 = array_filter(array_map('trim', explode(',', $keywords)));
    $arr2 = array_filter(array_map('trim', explode(',', $words)));

    if (empty($arr1) && empty($arr2)) {
        return '';
    }

    return implode(',', array_merge($arr1, $arr2));
}


function normalize_filter_tender_types(array $input): array
{
    return array_map(function ($item) {
        return trim(preg_replace('/\btenders\b/i', '', $item));
    }, $input);
}

function build_elastic_query(array $filters, int $page = 1, int $size = 10): array
{
    $from = ($page - 1) * $size;

    $must = [];
    $filter = [];
    $should = [];
    $sort = [];

    /* ---------- Exact match ---------- */
    if (!empty($filters['ref_no'])) {
        $must[] = ['term' => ['ref_no' => $filters['ref_no']]];
    }

    if (!empty($filters['tender_id'])) {
        $must[] = ['term' => ['tender_id' => $filters['tender_id']]];
    }

    /* ---------- Full text search ---------- */
    if (!empty($filters['keyword'])) {
        $keyword = trim($filters['keyword']);

        $must[] = [
            'multi_match' => [
                'query'    => $keyword,
                'fields'   => ['title', 'description'],
                'operator' => 'and'
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'title' => [
                    'query' => $keyword,
                    'boost' => 15
                ]
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'title' => [
                    'query' => $keyword,
                    'slop'  => 4,
                    'boost' => 10
                ]
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'description' => [
                    'query' => $keyword,
                    'slop'  => 6,
                    'boost' => 4
                ]
            ]
        ];

        $sort[] = ['_score' => ['order' => 'desc']];
    } else {
        $sort[] = ['publish_date' => ['order' => 'desc']];
    }

    /* ---------- Publish Date range ---------- */
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $must[] = [
            'range' => [
                'publish_date' => [
                    'gte' => $filters['start_date'],
                    'lte' => $filters['end_date']
                ]
            ]
        ];
    }

    /* ---------- Due Date range ---------- */
    if (!empty($filters['due_date'])) {
         $filter_due_date = explode(" ~ ", $filters['due_date']);
        $start_due_date = $filter_due_date[0];
        $timestamp1 = strtotime($start_due_date);
        $start_due_date = date("Y-m-d", $timestamp1);

        $end_due_date = $filter_due_date[1];
        $timestamp2 = strtotime($end_due_date);
        $end_due_date = date("Y-m-d", $timestamp2);
        $must[] = [
            'range' => [
                'due_date' => [
                    'gte' => $start_due_date,
                    'lte' => $end_due_date
                ]
            ]
        ];
    }

    /* ---------- Tender value range ---------- */
    if (!empty($filters['tender_value']) && !empty($filters['tender_value_to'])) {
        $filter[] = [
            'range' => [
                'tender_value' => [
                    'gte' => (float)$filters['tender_value_to'],
                    'lte' => (float)$filters['tender_value']
                ]
            ]
        ];
    }

    /* ---------- IN filters ---------- */
    $states = normalize_filters_array($filters['state']);
    if (!empty($states)) {
        $filter[] = ['terms' => ['state.keyword' => $states]];
    }

    $cities = normalize_filters_array($filters['city']);
    if (!empty($cities)) {
        $filter[] = ['terms' => ['city.keyword' => $cities]];
    }

    // Agency filter
    $agencies = normalize_filters_array($filters['agency'], false);
    if (!empty($agencies)) {
        $filter[] = ['terms' => ['agency_type.keyword' => $agencies]];
    }

    // Department filter
    $department = normalize_filters_array($filters['department']);
    if (!empty($department)) {
        $normalizeDepartment = normalize_filter_tender_types($department);
        $filter[] = ['terms' => ['department.keyword' => $normalizeDepartment]];
    }

    // tender type filter
    $tender_type = normalize_filters_array($filters['tender_type']);
    if (!empty($tender_type)) {
        $filter[] = ['terms' => ['tender_type.keyword' => $tender_type]];
    }

    /* ---------- Build BOOL safely ---------- */
    $bool = [];

    if ($must)   $bool['must'] = $must;
    if ($filter) $bool['filter'] = $filter;
    if ($should) {
        $bool['should'] = $should;
        $bool['minimum_should_match'] = 1;
    }

    /* ---------- Final Query ---------- */
    return [
        'from' => $from,
        'size' => $size,
        'track_total_hits' => true,
        'query' => $bool
            ? ['bool' => $bool]
            : ['match_all' => (object)[]],
        'sort' => $sort

    ];
}

function build_elastic_user_query(array $filters, int $page = 1, int $size = 10): array
{
    $from = ($page - 1) * $size;

    $must = [];
    $filter = [];
    $must_not = [];
    $should = [];
    $sort = [];

    /* ---------- Exact match ---------- */
    if (!empty($filters['ref_no'])) {
        $must[] = ['term' => ['ref_no' => $filters['ref_no']]];
    }

    if (!empty($filters['tender_id'])) {
        $must[] = ['term' => ['tender_id' => $filters['tender_id']]];
    }

    /* ---------- Search Keyword ---------- */
    if (!empty($filters['search_keyword'])) {
        /*
          This handles the "search within results" scenario:
          The user already has a set of filtered tenders (via saved keywords),
          and now wants to strict-match a new phrase in Title/Description.
        */
        $searchKeyword = trim($filters['search_keyword']);
        if ($searchKeyword !== '') {
            $must[] = [
                'multi_match' => [
                    'query'    => $searchKeyword,
                    'fields'   => ['title', 'description'],
                    'operator' => 'and'
                ]
            ];
        }
    }

    /* ---------- Full text search ---------- */
    if (!empty($filters['keyword']) && is_array($filters['keyword'])) {
        // $priority = 20; // higher = more important keyword

        // foreach ($filters['keyword'] as $kw) {
        //     $kw = trim($kw);
        //     if ($kw === '') continue;
        //     // LIKE '%keyword%' → highest priority
        //     $should[] = [
        //         'match_phrase' => [
        //             'title' => [
        //                 'query' => $kw,
        //                 'slop'  => 4,
        //                 'boost' => max(1, $priority)
        //             ]
        //         ]
        //     ];

        //     $priority--; // preserve ORDER BY CASE order
        // }

        // $sort[] = ['_score' => ['order' => 'desc']];

        $priority = count($filters['keyword']);

        foreach ($filters['keyword'] as $kw) {
            $kw = trim($kw);
            if ($kw === '') continue;
            // LIKE '%keyword%' → highest priority
            $should[] = [
                'constant_score' => [
                    'filter' => [
                        'match_phrase' => [
                            'title' => [
                                'query' => $kw,
                                'slop'  => 4
                            ]
                        ]
                    ],
                    'boost' => $priority * $priority * $priority // Cubic or just explicitly spaced?
            // Let's use: ($priority * 100)
                ]
            ];
           $priority--; 
        }

        $sort[] = ['_score' => ['order' => 'desc']];
    } elseif (!empty($filters['keyword'])) {
        $keyword = trim($filters['keyword']);

        $must[] = [
            'multi_match' => [
                'query'    => $keyword,
                'fields'   => ['title', 'description'],
                'operator' => 'and'
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'title' => [
                    'query' => $keyword,
                    'boost' => 15
                ]
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'title' => [
                    'query' => $keyword,
                    'slop'  => 4,
                    'boost' => 10
                ]
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'description' => [
                    'query' => $keyword,
                    'slop'  => 6,
                    'boost' => 4
                ]
            ]
        ];

        $sort[] = ['_score' => ['order' => 'desc']];
    } else {
        $sort[] = ['publish_date' => ['order' => 'desc']];
    }

    /* ---------- NOT USED KEYWORDS (EXCLUDE) ---------- */
    if (!empty($filters['not_used_keywords']) && is_array($filters['not_used_keywords'])) {

        foreach ($filters['not_used_keywords'] as $badKw) {

            $badKw = trim($badKw);
            if ($badKw === '') continue;

            $must_not[] = [
                'multi_match' => [
                    'query'    => $badKw,
                    'fields'   => ['title', 'description'],
                    'operator' => 'and'
                ]
            ];
        }
    }

    /* ---------- Publish Date range publish_date ---------- */
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $must[] = [
            'range' => [
                'due_date' => [
                    'gte' => $filters['start_date'],
                    'lte' => $filters['end_date']
                ]
            ]
        ];
    }

    /* ---------- Due Date range ---------- */
    if (!empty($filters['due_date'])) {
         $filter_due_date = explode(" ~ ", $filters['due_date']);
        $start_due_date = $filter_due_date[0];
        $timestamp1 = strtotime($start_due_date);
        $start_due_date = date("Y-m-d", $timestamp1);

        $end_due_date = $filter_due_date[1];
        $timestamp2 = strtotime($end_due_date);
        $end_due_date = date("Y-m-d", $timestamp2);
        $must[] = [
            'range' => [
                'due_date' => [
                    'gte' => $start_due_date,
                    'lte' => $end_due_date
                ]
            ]
        ];
    }

    /* ---------- Tender value range ---------- */
    if (!empty($filters['tender_value']) && !empty($filters['tender_value_to'])) {
        $filter[] = [
            'range' => [
                'tender_value' => [
                    'gte' => (float)$filters['tender_value_to'],
                    'lte' => (float)$filters['tender_value']
                ]
            ]
        ];
    }

    /* ---------- IN filters ---------- */
    $states = normalize_filters_array($filters['state']);
    if (!empty($states)) {
        $filter[] = ['terms' => ['state.keyword' => $states]];
    }

    $cities = normalize_filters_array($filters['city']);
    if (!empty($cities)) {
        $filter[] = ['terms' => ['city.keyword' => $cities]];
    }

    // Agency filter
    $agencies = normalize_filters_array($filters['agency'], false);
    if (!empty($agencies)) {
        $filter[] = ['terms' => ['agency_type.keyword' => $agencies]];
    }

    // Department filter
    $department = normalize_filters_array($filters['department']);
    if (!empty($department)) {
        $normalizeDepartment = normalize_filter_tender_types($department);
        $filter[] = ['terms' => ['department.keyword' => $normalizeDepartment]];
    }

    // tender type filter
    $tender_type = normalize_filters_array($filters['tender_type']);
    if (!empty($tender_type)) {
        $filter[] = ['terms' => ['tender_type.keyword' => $tender_type]];
    }

    /* ---------- Build BOOL safely ---------- */
    $bool = [];

    if ($must)   $bool['must'] = $must;
    if ($filter) $bool['filter'] = $filter;
    if ($must_not)  $bool['must_not'] = $must_not;
    if ($should) {
        $bool['should'] = $should;
        $bool['minimum_should_match'] = 1;
    }

    /* ---------- Final Query ---------- */
    return [
        'from' => $from,
        'size' => $size,
        'track_total_hits' => true,
        'query' => $bool
            ? ['bool' => $bool]
            : ['match_all' => (object)[]],
        'sort' => $sort

    ];
}

function build_elastic_cms_query(array $filters, int $page = 1, int $size = 10): array
{
    $from = ($page - 1) * $size;

    $must = [];
    $filter = [];
    $must_not = [];
    $should = [];
    $sort = [];

    /* ---------- Exact match ---------- */
    if (!empty($filters['ref_no'])) {
        $must[] = ['term' => ['ref_no' => $filters['ref_no']]];
    }

    if (!empty($filters['tender_id'])) {
        $must[] = ['term' => ['tender_id' => $filters['tender_id']]];
    }

    /* ---------- Search Keyword ---------- */
    if (!empty($filters['search_keyword'])) {
        /*
          This handles the "search within results" scenario:
          The user already has a set of filtered tenders (via saved keywords),
          and now wants to strict-match a new phrase in Title/Description.
        */
        $searchKeyword = trim($filters['search_keyword']);
        if ($searchKeyword !== '') {
            $must[] = [
                'multi_match' => [
                    'query'    => $searchKeyword,
                    'fields'   => ['title', 'description'],
                    'operator' => 'and'
                ]
            ];
        }
    }

    /* ---------- Full text search ---------- */
    if (!empty($filters['keyword']) && is_array($filters['keyword'])) {
        $priority = count($filters['keyword']);

        foreach ($filters['keyword'] as $kw) {
            $kw = trim($kw);
            if ($kw === '') continue;
            // LIKE '%keyword%' → highest priority
            $should[] = [
                'constant_score' => [
                    'filter' => [
                        'match_phrase' => [
                            'title' => [
                                'query' => $kw,
                                'slop'  => 4
                            ]
                        ]
                    ],
                    'boost' => $priority * $priority * $priority // Cubic or just explicitly spaced?
            // Let's use: ($priority * 100)
                ]
            ];
           $priority--; 
        }

        $sort[] = ['_score' => ['order' => 'desc']];
    } elseif (!empty($filters['keyword'])) {
        $keyword = trim($filters['keyword']);

        $must[] = [
            'multi_match' => [
                'query'    => $keyword,
                'fields'   => ['title', 'description'],
                'operator' => 'and'
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'title' => [
                    'query' => $keyword,
                    'boost' => 15
                ]
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'title' => [
                    'query' => $keyword,
                    'slop'  => 4,
                    'boost' => 10
                ]
            ]
        ];

        $should[] = [
            'match_phrase' => [
                'description' => [
                    'query' => $keyword,
                    'slop'  => 6,
                    'boost' => 4
                ]
            ]
        ];

        $sort[] = ['_score' => ['order' => 'desc']];
    } else {
        $sort[] = ['publish_date' => ['order' => 'desc']];
    }

    /* ---------- NOT USED KEYWORDS (EXCLUDE) ---------- */
    if (!empty($filters['not_used_keywords']) && is_array($filters['not_used_keywords'])) {

        foreach ($filters['not_used_keywords'] as $badKw) {

            $badKw = trim($badKw);
            if ($badKw === '') continue;

            $must_not[] = [
                'multi_match' => [
                    'query'    => $badKw,
                    'fields'   => ['title', 'description'],
                    'operator' => 'and'
                ]
            ];
        }
    }

    /* ---------- Publish Date range publish_date ---------- */
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $must[] = [
            'range' => [
                'due_date' => [
                    'gte' => $filters['start_date'],
                    'lte' => $filters['end_date']
                ]
            ]
        ];
    }

    /* ---------- Due Date range ---------- */
    if (!empty($filters['due_date'])) {
         $filter_due_date = explode(" ~ ", $filters['due_date']);
        $start_due_date = $filter_due_date[0];
        $timestamp1 = strtotime($start_due_date);
        $start_due_date = date("Y-m-d", $timestamp1);

        $end_due_date = $filter_due_date[1];
        $timestamp2 = strtotime($end_due_date);
        $end_due_date = date("Y-m-d", $timestamp2);
        $must[] = [
            'range' => [
                'due_date' => [
                    'gte' => $start_due_date,
                    'lte' => $end_due_date
                ]
            ]
        ];
    }

    /* ---------- Tender value range ---------- */
    if (!empty($filters['tender_value']) && !empty($filters['tender_value_to'])) {
        $filter[] = [
            'range' => [
                'tender_value' => [
                    'gte' => (float)$filters['tender_value_to'],
                    'lte' => (float)$filters['tender_value']
                ]
            ]
        ];
    }

    /* ---------- IN filters ---------- */
    $states = normalize_filters_array($filters['state']);
    if (!empty($states)) {
        $filter[] = ['terms' => ['state.keyword' => $states]];
    }

    $cities = normalize_filters_array($filters['city']);
    if (!empty($cities)) {
        $filter[] = ['terms' => ['city.keyword' => $cities]];
    }

    // Agency filter
    $agencies = normalize_filters_array($filters['agency'], false);
    if (!empty($agencies)) {
        $filter[] = ['terms' => ['agency_type.keyword' => $agencies]];
    }

    // Department filter
    $department = normalize_filters_array($filters['department']);
    if (!empty($department)) {
        $normalizeDepartment = normalize_filter_tender_types($department);
        $filter[] = ['terms' => ['department.keyword' => $normalizeDepartment]];
    }

    // tender type filter
    $tender_type = normalize_filters_array($filters['tender_type']);
    if (!empty($tender_type)) {
        $filter[] = ['terms' => ['tender_type.keyword' => $tender_type]];
    }

    /* ---------- Build BOOL safely ---------- */
    $bool = [];

    if ($must)   $bool['must'] = $must;
    if ($filter) $bool['filter'] = $filter;
    if ($must_not)  $bool['must_not'] = $must_not;
    if ($should) {
        $bool['should'] = $should;
        $bool['minimum_should_match'] = 1;
    }

    /* ---------- Final Query ---------- */
    return [
        'from' => $from,
        'size' => $size,
        'track_total_hits' => true,
        'query' => $bool
            ? ['bool' => $bool]
            : ['match_all' => (object)[]],
        'sort' => $sort

    ];
}

// function build_elastic_user_query(array $filters, int $page = 1, int $size = 10): array
// {
//     $from = ($page - 1) * $size;

//     $must = [];
//     $filter = [];
//     $must_not = [];
//     $should = [];
//     $sort = [];

//     /* ---------- Exact match ---------- */
//     if (!empty($filters['ref_no'])) {
//         $must[] = ['term' => ['ref_no' => $filters['ref_no']]];
//     }

//     if (!empty($filters['tender_id'])) {
//         $must[] = ['term' => ['tender_id' => $filters['tender_id']]];
//     }

//     /* ---------- Full text search ---------- */
//     if (!empty($filters['keyword']) && is_array($filters['keyword'])) {
//         $priority = 20; // higher = more important keyword

//         foreach ($filters['keyword'] as $kw) {
//             $kw = trim($kw);
//             if ($kw === '') continue;
//             // LIKE '%keyword%' → highest priority
//             $should[] = [
//                 'match_phrase' => [
//                     'title' => [
//                         'query' => $kw,
//                         'slop'  => 4,
//                         'boost' => max(1, $priority)
//                     ]
//                 ]
//             ];

//             $priority--; // preserve ORDER BY CASE order
//         }

//         $sort[] = ['_score' => ['order' => 'desc']];
//     } elseif (!empty($filters['keyword'])) {
//         $keyword = trim($filters['keyword']);

//         $must[] = [
//             'multi_match' => [
//                 'query'    => $keyword,
//                 'fields'   => ['title', 'description'],
//                 'operator' => 'and'
//             ]
//         ];

//         $should[] = [
//             'match_phrase' => [
//                 'title' => [
//                     'query' => $keyword,
//                     'boost' => 15
//                 ]
//             ]
//         ];

//         $should[] = [
//             'match_phrase' => [
//                 'title' => [
//                     'query' => $keyword,
//                     'slop'  => 4,
//                     'boost' => 10
//                 ]
//             ]
//         ];

//         $should[] = [
//             'match_phrase' => [
//                 'description' => [
//                     'query' => $keyword,
//                     'slop'  => 6,
//                     'boost' => 4
//                 ]
//             ]
//         ];

//         $sort[] = ['_score' => ['order' => 'desc']];
//     } else {
//         $sort[] = ['publish_date' => ['order' => 'desc']];
//     }

//     /* ---------- NOT USED KEYWORDS (EXCLUDE) ---------- */
//     if (!empty($filters['not_used_keywords']) && is_array($filters['not_used_keywords'])) {

//         foreach ($filters['not_used_keywords'] as $badKw) {

//             $badKw = trim($badKw);
//             if ($badKw === '') continue;

//             $must_not[] = [
//                 'multi_match' => [
//                     'query'    => $badKw,
//                     'fields'   => ['title', 'description'],
//                     'operator' => 'and'
//                 ]
//             ];
//         }
//     }

//     /* ---------- Publish Date range publish_date ---------- */
//     if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
//         $must[] = [
//             'range' => [
//                 'due_date' => [
//                     'gte' => $filters['start_date'],
//                     'lte' => $filters['end_date']
//                 ]
//             ]
//         ];
//     }

//     /* ---------- Due Date range ---------- */
//     if (!empty($filters['due_date'])) {
//          $filter_due_date = explode(" ~ ", $filters['due_date']);
//         $start_due_date = $filter_due_date[0];
//         $timestamp1 = strtotime($start_due_date);
//         $start_due_date = date("Y-m-d", $timestamp1);

//         $end_due_date = $filter_due_date[1];
//         $timestamp2 = strtotime($end_due_date);
//         $end_due_date = date("Y-m-d", $timestamp2);
//         $must[] = [
//             'range' => [
//                 'due_date' => [
//                     'gte' => $start_due_date,
//                     'lte' => $end_due_date
//                 ]
//             ]
//         ];
//     }

//     /* ---------- Tender value range ---------- */
//     if (!empty($filters['tender_value']) && !empty($filters['tender_value_to'])) {
//         $filter[] = [
//             'range' => [
//                 'tender_value' => [
//                     'gte' => (float)$filters['tender_value_to'],
//                     'lte' => (float)$filters['tender_value']
//                 ]
//             ]
//         ];
//     }

//     /* ---------- IN filters ---------- */
//     $states = normalize_filters_array($filters['state']);
//     if (!empty($states)) {
//         $filter[] = ['terms' => ['state.keyword' => $states]];
//     }

//     $cities = normalize_filters_array($filters['city']);
//     if (!empty($cities)) {
//         $filter[] = ['terms' => ['city.keyword' => $cities]];
//     }

//     // Agency filter
//     $agencies = normalize_filters_array($filters['agency'], false);
//     if (!empty($agencies)) {
//         $filter[] = ['terms' => ['agency_type.keyword' => $agencies]];
//     }

//     // Department filter
//     $department = normalize_filters_array($filters['department']);
//     if (!empty($department)) {
//         $normalizeDepartment = normalize_filter_tender_types($department);
//         $filter[] = ['terms' => ['department.keyword' => $normalizeDepartment]];
//     }

//     // tender type filter
//     $tender_type = normalize_filters_array($filters['tender_type']);
//     if (!empty($tender_type)) {
//         $filter[] = ['terms' => ['tender_type.keyword' => $tender_type]];
//     }

//     /* ---------- Build BOOL safely ---------- */
//     $bool = [];

//     if ($must)   $bool['must'] = $must;
//     if ($filter) $bool['filter'] = $filter;
//     if ($must_not)  $bool['must_not'] = $must_not;
//     if ($should) {
//         $bool['should'] = $should;
//         $bool['minimum_should_match'] = 1;
//     }

//     /* ---------- Final Query ---------- */
//     return [
//         'from' => $from,
//         'size' => $size,
//         'track_total_hits' => true,
//         'query' => $bool
//             ? ['bool' => $bool]
//             : ['match_all' => (object)[]],
//         'sort' => $sort

//     ];
// }

function build_elastic_admin_query(array $filters, int $page = 1, int $size = 10): array
{
    $from = ($page - 1) * $size;
    $filter = [];
    $should = [];
    $sort = [];

    // Normalize keyword
    $keyword = isset($filters['keyword']) ? trim(urldecode($filters['keyword'])) : '';

    // Date filter (non-scoring)
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $filter[] = [
            'range' => [
                'due_date' => [
                    'gte' => $filters['start_date'],
                    'lte' => $filters['end_date']
                ]
            ]
        ];
    }
    
    /* ---------- Keyword-based search ---------- */
    if ($keyword !== '') {
     /* 🔥 Exact match FIRST (keyword fields) */
        $should[] = [
            'term' => [
                'tender_id' => [
                    'value' => $keyword,
                    'boost' => 500
                ]
            ]
        ];

        $should[] = [
            'term' => [
                'ref_no' => [
                    'value' => $keyword,
                    'boost' => 450
                ]
            ]
        ];

        /* ⚠️ Fallback only (partial / dirty data) */
        $should[] = [
            'wildcard' => [
                'tender_id' => [
                    'value' => '*' . $keyword . '*',
                    'boost' => 150
                ]
            ]
        ];

        $should[] = [
            'wildcard' => [
                'ref_no' => [
                    'value' => '*' . $keyword . '*',
                    'boost' => 120
                ]
            ]
        ];

        /* ---------- Text search ---------- */
        $should[] = [
            'match_phrase' => [
                'title' => [
                    'query' => $keyword,
                    'boost' => 40
                ]
            ]
        ];

        $should[] = [
            'multi_match' => [
                'query' => $keyword,
                'fields' => ['title^10', 'city^5', 'state^5'],
                'operator' => 'and'
            ]
        ];

        $sort[] = ['_score' => ['order' => 'desc']];
    } else {
        // No keyword: default sort
        $sort[] = ['due_date' => ['order' => 'desc']];
    }

    /* ---------- Build BOOL query ---------- */
    $bool = [];

    if (!empty($filter)) {
        $bool['filter'] = $filter;
    }

    if (!empty($should)) {
        $bool['should'] = $should;
        $bool['minimum_should_match'] = 1;
    }

    return [
        'from' => $from,
        'size' => $size,
        'track_total_hits' => true,
        'query' => [
            'bool' => $bool
        ],
        'sort' => $sort
    ];
}

function build_elastic_client_query(array $filters, int $page = 1, int $size = 10): array
{
    $from = ($page - 1) * $size;

    $must = [];
    $filter = [];
    $must_not = [];
    $should = [];
    $sort = [];

    // Normalize search
    $search = isset($filters['search']) ? trim(urldecode($filters['search'])) : '';

    /* ==========================================================
       SEARCH STRING (tender_id / ref_no / free text)
    ========================================================== */
    if ($search !== '') {

        /*
          This handles the "search within results" scenario:
          The user already has a set of filtered tenders (via saved keywords),
          and now wants to strict-match a new phrase in Title/Description.
        */
        $must[] = [
            'multi_match' => [
                'query'    => $search,
                'fields'   => ['title', 'description', 'tender_id', 'ref_no', 'agency_type'],
                'operator' => 'and'
            ]
        ];
        
    }

    /* ==========================================================
       KEYWORD ARRAY (STRICT ORDER ENFORCED)
    ========================================================== */
    if (!empty($filters['keyword']) && is_array($filters['keyword'])) {
        $priority = 20; // higher = more important keyword

        foreach ($filters['keyword'] as $kw) {
            $kw = trim($kw);
            if ($kw === '') continue;
            // LIKE '%keyword%' → highest priority
            $should[] = [
                'match_phrase' => [
                    'title' => [
                        'query' => $kw,
                        'slop'  => 4,
                        'boost' => max(1, $priority)
                    ]
                ]
            ];

            $priority--; // preserve ORDER BY CASE order
        }

        $sort[] = ['_score' => ['order' => 'desc']];
    } else {
        $sort[] = ['due_date' => ['order' => 'desc']];
    }

    /* ==========================================================
       EXCLUDE KEYWORDS
    ========================================================== */
    if (!empty($filters['not_used_keywords']) && is_array($filters['not_used_keywords'])) {
        foreach ($filters['not_used_keywords'] as $badKw) {
            $badKw = trim($badKw);
            if ($badKw === '') continue;

            $must_not[] = [
                'multi_match' => [
                    'query'    => $badKw,
                    'fields'   => ['title', 'description'],
                    'operator' => 'and'
                ]
            ];
        }
    }

    /* ---------- Tender value range ---------- */
    if (!empty($filters['tender_value']) && !empty($filters['tender_value_to'])) {
        $filter[] = [
            'range' => [
                'tender_value' => [
                    'gte' => (float)$filters['tender_value_to'],
                    'lte' => (float)$filters['tender_value']
                ]
            ]
        ];
    }

    /* ---------- IN filters ---------- */
     $states = normalize_filters_array($filters['state']);
    if (!empty($states)) {
        $filter[] = ['terms' => ['state.keyword' => $states]];
    }

    $cities = normalize_filters_array($filters['city']);
    if (!empty($cities)) {
        $filter[] = ['terms' => ['city.keyword' => $cities]];
    }

    // Agency filter
    $agencies = normalize_filters_array($filters['agency'], false);
    if (!empty($agencies)) {
        $filter[] = ['terms' => ['agency_type.keyword' => $agencies]];
    }

    // Department filter
    $department = normalize_filters_array($filters['department']);
    if (!empty($department)) {
        $normalizeDepartment = normalize_filter_tender_types($department);
        $filter[] = ['terms' => ['department.keyword' => $normalizeDepartment]];
    }

    // tender type filter
    $tender_type = normalize_filters_array($filters['tender_type']);
    if (!empty($tender_type)) {
        $filter[] = ['terms' => ['tender_type.keyword' => $tender_type]];
    }

    /* ---------- Build BOOL safely ---------- */
    $bool = [];

    if ($must)   $bool['must'] = $must;
    if ($filter) $bool['filter'] = $filter;
    if ($must_not)  $bool['must_not'] = $must_not;
    if ($should) {
        $bool['should'] = $should;
        $bool['minimum_should_match'] = 1;
    }

    /* ---------- Final Query ---------- */
    return [
        'from' => $from,
        'size' => $size,
        'track_total_hits' => true,
        'query' => $bool
            ? ['bool' => $bool]
            : ['match_all' => (object)[]],
        'sort' => $sort

    ];
}

// function build_elastic_client_query(array $filters, int $page = 1, int $size = 10): array
// {
//     $from = ($page - 1) * $size;

//     $must = [];
//     $filter = [];
//     $must_not = [];
//     $should = [];
//     $sort = [];

//     // Normalize search
//     $search = isset($filters['search']) ? trim(urldecode($filters['search'])) : '';

//     /* ==========================================================
//        SEARCH STRING (tender_id / ref_no / free text)
//     ========================================================== */
//     if ($search !== '') {

//         $should[] = [
//             'term' => [
//                 'tender_id' => [
//                     'value' => $search,
//                     'boost' => 500
//                 ]
//             ]
//         ];

//         $should[] = [
//             'term' => [
//                 'ref_no' => [
//                     'value' => $search,
//                     'boost' => 450
//                 ]
//             ]
//         ];

//         $should[] = [
//             'match_phrase' => [
//                 'title' => [
//                     'query' => $search,
//                     'boost' => 40
//                 ]
//             ]
//         ];

//         $should[] = [
//             'multi_match' => [
//                 'query' => $search,
//                 'fields' => ['title^10', 'agency_type^5'],
//                 'operator' => 'and'
//             ]
//         ];

//         $query = [
//             'bool' => [
//                 'should' => $should,
//                 'minimum_should_match' => 1
//             ]
//         ];

//         $sort[] = ['_score' => ['order' => 'desc']];
//     }

//     /* ==========================================================
//        KEYWORD ARRAY (STRICT ORDER ENFORCED 🔥)
//     ========================================================== */
//     elseif (!empty($filters['keyword']) && is_array($filters['keyword'])) {

//         $functions = [];
//         $maxWeight = 1000; // plywood
//         $step      = 20;   // drop per keyword

//         foreach ($filters['keyword'] as $i => $kw) {
//             $kw = trim(mb_strtolower($kw));
//             if ($kw === '') continue;

//             $weight = max(1, $maxWeight - ($i * $step));

//             $functions[] = [
//                 'filter' => [
//                     'match_phrase' => [
//                         'title' => $kw
//                     ]
//                 ],
//                 'weight' => $weight
//             ];
//         }

//         $query = [
//             'function_score' => [
//                 'query' => [
//                     'bool' => [
//                         'should' => [
//                             [
//                                 'multi_match' => [
//                                     'query'  => implode(' ', $filters['keyword']),
//                                     'fields' => ['title^2', 'description'],
//                                     'operator' => 'or'
//                                 ]
//                             ]
//                         ],
//                         'minimum_should_match' => 1
//                     ]
//                 ],
//                 'functions'  => $functions,
//                 'score_mode' => 'max',
//                 'boost_mode' => 'replace'
//             ]
//         ];

//         $sort[] = ['_score' => ['order' => 'desc']];
//     }

//     /* ==========================================================
//        DEFAULT SORT
//     ========================================================== */
//     else {
//         $query = ['match_all' => (object)[]];
//         $sort[] = ['due_date' => ['order' => 'desc']];
//     }

//     /* ==========================================================
//        EXCLUDE KEYWORDS
//     ========================================================== */
//     if (!empty($filters['not_used_keywords']) && is_array($filters['not_used_keywords'])) {
//         foreach ($filters['not_used_keywords'] as $badKw) {
//             $badKw = trim($badKw);
//             if ($badKw === '') continue;

//             $must_not[] = [
//                 'multi_match' => [
//                     'query'    => $badKw,
//                     'fields'   => ['title', 'description'],
//                     'operator' => 'and'
//                 ]
//             ];
//         }
//     }

//     /* ==========================================================
//        RANGE + FILTERS
//     ========================================================== */
//     if (!empty($filters['tender_value']) && !empty($filters['tender_value_to'])) {
//         $filter[] = [
//             'range' => [
//                 'tender_value' => [
//                     'gte' => (float)$filters['tender_value_to'],
//                     'lte' => (float)$filters['tender_value']
//                 ]
//             ]
//         ];
//     }

//     $states = normalize_filters_array($filters['state']);
//     if ($states) $filter[] = ['terms' => ['state.keyword' => $states]];

//     $cities = normalize_filters_array($filters['city']);
//     if ($cities) $filter[] = ['terms' => ['city.keyword' => $cities]];

//     $agencies = normalize_filters_array($filters['agency'], false);
//     if ($agencies) $filter[] = ['terms' => ['agency_type.keyword' => $agencies]];

//     $department = normalize_filters_array($filters['department']);
//     if ($department) {
//         $filter[] = [
//             'terms' => [
//                 'department.keyword' => normalize_filter_tender_types($department)
//             ]
//         ];
//     }

//     $tender_type = normalize_filters_array($filters['tender_type']);
//     if ($tender_type) {
//         $filter[] = ['terms' => ['tender_type.keyword' => $tender_type]];
//     }

//     /* ==========================================================
//        FINAL BOOL WRAP
//     ========================================================== */
//     $bool = [];

//     if ($filter)   $bool['filter']   = $filter;
//     if ($must_not) $bool['must_not'] = $must_not;

//     return [
//         'from' => $from,
//         'size' => $size,
//         'track_total_hits' => true,
//         'query' => $bool
//             ? ['bool' => array_merge($bool, ['must' => [$query]])]
//             : $query,
//         'sort' => $sort
//     ];
// }


// function build_elastic_client_query(array $filters, int $page = 1, int $size = 10): array
// {
//     $from = ($page - 1) * $size;

//     $must = [];
//     $filter = [];
//     $must_not = [];
//     $should = [];
//     $sort = [];

//      // Normalize search
//     $search = isset($filters['search']) ? trim(urldecode($filters['search'])) : '';

//     /* ---------- Keyword-based search ---------- */
//     if ($search !== '') {
//      /* 🔥 Exact match FIRST (keyword fields) */
//         $should[] = [
//             'term' => [
//                 'tender_id' => [
//                     'value' => $search,
//                     'boost' => 500
//                 ]
//             ]
//         ];

//         $should[] = [
//             'term' => [
//                 'ref_no' => [
//                     'value' => $search,
//                     'boost' => 450
//                 ]
//             ]
//         ];

//         /* ---------- Text search ---------- */
//         $should[] = [
//             'match_phrase' => [
//                 'title' => [
//                     'query' => $search,
//                     'boost' => 40
//                 ]
//             ]
//         ];

//         $should[] = [
//             'multi_match' => [
//                 'query' => $search,
//                 'fields' => ['title^10', 'agency_type^5'],
//                 'operator' => 'and'
//             ]
//         ];

//         $sort[] = ['_score' => ['order' => 'desc']];
//     } elseif (!empty($filters['keyword']) && is_array($filters['keyword'])) {

//         $baseBoost = 100; // high start
//         $step      = 2;   // boost drop per keyword
//         $minBoost  = 5;

//         foreach ($filters['keyword'] as $index => $kw) {

//             $kw = trim(mb_strtolower($kw));
//             if ($kw === '') continue;

//             // Higher priority keywords get higher boost
//             $boost = max($baseBoost - ($index * $step), $minBoost);

//             /* ---------- Exact phrase in TITLE (highest priority) ---------- */
//             $should[] = [
//                 'match_phrase' => [
//                     'title' => [
//                         'query' => $kw,
//                         'boost' => $boost
//                     ]
//                 ]
//             ];

//             /* ---------- Exact phrase in DESCRIPTION (lower priority) ---------- */
//             $should[] = [
//                 'match_phrase' => [
//                     'description' => [
//                         'query' => $kw,
//                         'boost' => $boost * 0.4
//                     ]
//                 ]
//             ];
//         }

//         $sort[] = ['_score' => ['order' => 'desc']];
//     } else {
//         $sort[] = ['due_date' => ['order' => 'desc']];
//     }

//     /* ---------- NOT USED KEYWORDS (EXCLUDE) ---------- */
//     if (!empty($filters['not_used_keywords']) && is_array($filters['not_used_keywords'])) {

//         foreach ($filters['not_used_keywords'] as $badKw) {

//             $badKw = trim($badKw);
//             if ($badKw === '') continue;

//             $must_not[] = [
//                 'multi_match' => [
//                     'query'    => $badKw,
//                     'fields'   => ['title', 'description'],
//                     'operator' => 'and'
//                 ]
//             ];
//         }
//     }

//     /* ---------- Tender value range ---------- */
//     if (!empty($filters['tender_value']) && !empty($filters['tender_value_to'])) {
//         $filter[] = [
//             'range' => [
//                 'tender_value' => [
//                     'gte' => (float)$filters['tender_value_to'],
//                     'lte' => (float)$filters['tender_value']
//                 ]
//             ]
//         ];
//     }

//     /* ---------- IN filters ---------- */
//     $states = normalize_filters_array($filters['state']);
//     if (!empty($states)) {
//         $filter[] = ['terms' => ['state.keyword' => $states]];
//     }

//     $cities = normalize_filters_array($filters['city']);
//     if (!empty($cities)) {
//         $filter[] = ['terms' => ['city.keyword' => $cities]];
//     }

//     // Agency filter
//     $agencies = normalize_filters_array($filters['agency'], false);
//     if (!empty($agencies)) {
//         $filter[] = ['terms' => ['agency_type.keyword' => $agencies]];
//     }

//     // Department filter
//     $department = normalize_filters_array($filters['department']);
//     if (!empty($department)) {
//         $normalizeDepartment = normalize_filter_tender_types($department);
//         $filter[] = ['terms' => ['department.keyword' => $normalizeDepartment]];
//     }

//     // tender type filter
//     $tender_type = normalize_filters_array($filters['tender_type']);
//     if (!empty($tender_type)) {
//         $filter[] = ['terms' => ['tender_type.keyword' => $tender_type]];
//     }

//     /* ---------- Build BOOL safely ---------- */
//     $bool = [];

//     if ($must)   $bool['must'] = $must;
//     if ($filter) $bool['filter'] = $filter;
//     if ($must_not)  $bool['must_not'] = $must_not;
//     if ($should) {
//         $bool['should'] = $should;
//         $bool['minimum_should_match'] = 1;
//     }

//     /* ---------- Final Query ---------- */
//     return [
//         'from' => $from,
//         'size' => $size,
//         'track_total_hits' => true,
//         'query' => $bool
//             ? ['bool' => $bool]
//             : ['match_all' => (object)[]],
//         'sort' => $sort

//     ];
// }

// function build_elastic_client_query(
//     array $filters,
//     int $page = 1,
//     int $size = 10,
//     bool $explain = false
// ): array {

//     $from = ($page - 1) * $size;

//     $must = [];
//     $filter = [];
//     $must_not = [];
//     $should = [];
//     $sort = [];

//     /* ---------- SEARCH ---------- */
//     $search = isset($filters['search'])
//         ? trim(urldecode($filters['search']))
//         : '';

//     if ($search !== '') {

//         // Exact ID boost
//         $should[] = [
//             'term' => ['tender_id' => ['value' => $search, 'boost' => 500]]
//         ];

//         $should[] = [
//             'term' => ['ref_no' => ['value' => $search, 'boost' => 450]]
//         ];

//         // Phrase first
//         $should[] = [
//             'match_phrase' => [
//                 'title' => ['query' => $search, 'boost' => 40]
//             ]
//         ];

//         // AND text match
//         $should[] = [
//             'multi_match' => [
//                 'query' => $search,
//                 'fields' => ['title^10', 'description^5'],
//                 'operator' => 'and'
//             ]
//         ];
//     }  elseif (!empty($filters['keyword']) && is_array($filters['keyword'])) {
//         /* ---------- KEYWORD PRIORITY (MYSQL CASE WHEN) ---------- */
//         $priorityKeywords = $filters['keyword'];
//         $functions = [];
//         $weight = count($priorityKeywords) + 10;

//         foreach ($priorityKeywords as $kw) {

//     $functions[] = [
//         'filter' => [
//             'match_phrase' => [
//                 'title' => [
//                     'query' => $kw,
//                     'slop'  => 0   // EXACT ORDER
//                 ]
//             ]
//         ],
//         'weight' => $weight--
//     ];

//     // fallback: phrase in description (slightly lower priority)
//     $functions[] = [
//         'filter' => [
//             'match_phrase' => [
//                 'description' => [
//                     'query' => $kw,
//                     'slop'  => 0
//                 ]
//             ]
//         ],
//         'weight' => $weight - 1
//     ];
// }

//     }

//     /* ---------- EXCLUDE KEYWORDS ---------- */
//     if (!empty($filters['not_used_keywords'])) {
//         foreach ($filters['not_used_keywords'] as $bad) {
//             $must_not[] = [
//                 'multi_match' => [
//                     'query' => $bad,
//                     'fields' => ['title', 'description'],
//                     'operator' => 'and'
//                 ]
//             ];
//         }
//     }

//     /* ---------- FILTERS ---------- */
//     if (!empty($filters['tender_type'])) {
//         $filter[] = ['terms' => ['tender_type.keyword' => (array)$filters['tender_type']]];
//     }

//     if (!empty($filters['department'])) {
//         $filter[] = ['terms' => ['department.keyword' => (array)$filters['department']]];
//     }

//     /* ---------- BOOL ---------- */
//     $bool = [];

//     if ($must)      $bool['must'] = $must;
//     if ($filter)    $bool['filter'] = $filter;
//     if ($must_not)  $bool['must_not'] = $must_not;
//     if ($should) {
//         $bool['should'] = $should;
//         $bool['minimum_should_match'] = 1;
//     }

//     /* ---------- FINAL QUERY ---------- */
//     $query = [
//         'from' => $from,
//         'size' => $size,
//         'track_total_hits' => true,
//         'query' => [
//             'function_score' => [
//                 'query' => $bool ? ['bool' => $bool] : ['match_all' => (object)[]],
//                 'functions' => $functions,
//                 'score_mode' => 'max',
//                 'boost_mode' => 'replace'
//             ]
//         ],
//         'sort' => [
//             ['_score' => ['order' => 'desc']],
//             ['title.keyword' => ['order' => 'asc']]
//         ]
//     ];

//     if ($explain) {
//         $query['explain'] = true;
//     }

//     return $query;
// }


