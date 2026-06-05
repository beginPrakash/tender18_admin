<?php

if (!function_exists('parseKeywords')) {

    /**
     * Parse keywords into a clean, unique phrase array.
     *
     * Supports:
     *  - String input  ("cctv camera")
     *  - Array input   (["cctv camera", "road", "ecg machine"])
     *
     * Example:
     *  Input:  "cctv camera"
     *  Output: ["cctv camera"]
     *
     *  Input:  ["cctv camera", "road", "ecg machine"]
     *  Output: ["cctv camera", "road", "ecg machine"]
     *
     * @param mixed $input
     * @return array
     */
    function parseKeywords($input)
    {
        if (empty($input)) {
            return [];
        }

        $keywords = [];

        // Normalize to array
        $inputs = is_array($input) ? $input : [$input];

        foreach ($inputs as $item) {

            if (!is_string($item)) {
                continue;
            }

            $item = trim($item);

            if ($item === '') {
                continue;
            }

            // Lowercase (UTF-8 safe if mbstring exists)
            if (function_exists('mb_strtolower')) {
                $item = mb_strtolower($item, 'UTF-8');
            } else {
                $item = strtolower($item);
            }

            // Remove special characters (keep letters, numbers, spaces)
            $item = preg_replace('/[^\p{L}\p{N}\s]/u', '', $item);

            // Normalize multiple spaces
            $item = preg_replace('/\s+/', ' ', $item);

            $item = trim($item);

            // Add unique full phrase (NOT splitting into words)
            if ($item !== '' && !in_array($item, $keywords, true)) {
                $keywords[] = $item;
            }
        }

        return $keywords;
    }

    /**
     * Specialized parser for the "words" filter.
     * Splits by common delimiters like comma.
     */
    function parseWordsInput($input)
    {
        if (empty($input)) {
            return [];
        }

        // Split by common delimiters if string
        if (is_string($input)) {
            $delimiters = ["\r\n", "\n", "\r", ",", ";", "|", "/", "\\"];
            $input = str_replace($delimiters, '|||', $input);
            $input = explode('|||', $input);
        }

        return parseKeywords($input);
    }
}


// if (!function_exists('buildKeywordPriority')) {

//     /**
//      * Build prioritized keyword list.
//      *
//      * Supports:
//      *  - String input  ("cctv camera")
//      *  - Array input   (["cctv camera", "road", "ecg machine"])
//      *
//      * Example:
//      *  Input:  "cctv camera"
//      *  Output: ["cctv camera", "cctv", "camera"]
//      *
//      *  Input:  ["cctv camera", "road", "ecg machine"]
//      *  Output: [
//      *      "cctv camera",
//      *      "cctv",
//      *      "camera",
//      *      "road",
//      *      "ecg machine",
//      *      "ecg",
//      *      "machine"
//      *  ]
//      *
//      * @param mixed $input
//      * @return array
//      */
//     function buildKeywordPriority($input)
//     {
//         if (empty($input)) {
//             return [];
//         }

//         $result = [];

//         // Normalize input to array
//         $inputs = is_array($input) ? $input : [$input];

//         foreach ($inputs as $item) {

//             if (!is_string($item)) {
//                 continue;
//             }

//             $item = trim($item);

//             if ($item === '') {
//                 continue;
//             }

//             // Lowercase safely (UTF-8 fallback)
//             if (function_exists('mb_strtolower')) {
//                 $item = mb_strtolower($item, 'UTF-8');
//             } else {
//                 $item = strtolower($item);
//             }

//             // Remove special characters except letters, numbers and space
//             $item = preg_replace('/[^\p{L}\p{N}\s]/u', '', $item);

//             // Normalize multiple spaces
//             $item = preg_replace('/\s+/', ' ', $item);

//             if ($item === '') {
//                 continue;
//             }

//             // 1️⃣ Add full phrase first
//             if (!in_array($item, $result, true)) {
//                 $result[] = $item;
//             }

//             // 2️⃣ Add individual words
//             $words = explode(' ', $item);

//             foreach ($words as $word) {
//                 $word = trim($word);

//                 if ($word !== '' && !in_array($word, $result, true)) {
//                     $result[] = $word;
//                 }
//             }
//         }

//         return $result;
//     }
// }

if (!function_exists('buildKeywordPriority')) {

    /**
     * Build keyword list.
     *
     * Supports:
     *  - String input
     *  - Array input
     *
     * Example:
     * Input:  "cctv camera"
     * Output: ["cctv camera"]
     *
     * Input:  ["cctv camera", "road", "ecg machine"]
     * Output: ["cctv camera", "road", "ecg machine"]
     *
     * @param mixed $input
     * @return array
     */
    function buildKeywordPriority($input)
    {
        if (empty($input)) {
            return [];
        }

        $result = [];

        // Normalize input to array
        $inputs = is_array($input) ? $input : [$input];

        foreach ($inputs as $item) {

            if (!is_string($item)) {
                continue;
            }

            $item = trim($item);

            if ($item === '') {
                continue;
            }

            // Lowercase safely
            if (function_exists('mb_strtolower')) {
                $item = mb_strtolower($item, 'UTF-8');
            } else {
                $item = strtolower($item);
            }

            // Remove special characters except letters, numbers and space
            $item = preg_replace('/[^\p{L}\p{N}\s]/u', '', $item);

            // Normalize spaces
            $item = preg_replace('/\s+/', ' ', $item);

            if ($item !== '' && !in_array($item, $result, true)) {
                $result[] = $item;
            }
        }

        return $result;
    }
}

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

function normalize_filter_tender_types(array $input): array
{
    return array_map(function ($item) {
        return trim(preg_replace('/\btenders\b/i', '', $item));
    }, $input);
}

function buildDetailedKeywordQuery($keyword)
{
    // Build per-word mandatory clauses: every word must appear somewhere in title or description.
    // This enforces strict AND — e.g. "pcc road repair" requires "pcc", "road", AND "repair" all present.
    $words = array_values(array_filter(array_map('trim', explode(' ', $keyword))));

    $perWordMust = array_values(array_filter(
        array_map(function ($word) {
            if ($word === '') return null;
            return [
                'multi_match' => [
                    'query'  => $word,
                    'fields' => ['title^3', 'description'],
                    'type'   => 'phrase_prefix'
                ]
            ];
        }, $words)
    ));

    return [
        'bool' => [
            // MUST: all words must be present (AND gate) — prevents partial-word matches
            'must'   => $perWordMust,
            // SHOULD: boost scoring for phrase/proximity/fuzzy matches (does NOT filter)
            'should' => [
                // 1. Exact phrase prefix (all words consecutive, slop=0) — highest precision
                [
                    'multi_match' => [
                        'query'    => $keyword,
                        'fields'   => ['title', 'description'],
                        'type'     => 'phrase_prefix',
                        'slop'     => 0,
                        'boost'    => 5
                    ]
                ],
                // 2. Flexible Title (proximity — words can be apart, handles word order)
                [
                    'match_phrase_prefix' => [
                        'title' => [
                            'query' => $keyword,
                            'slop'  => 50,
                            'boost' => 3
                        ]
                    ]
                ],
                // 3. Flexible Description (proximity)
                [
                    'match_phrase_prefix' => [
                        'description' => [
                            'query' => $keyword,
                            'slop'  => 50,
                            'boost' => 1
                        ]
                    ]
                ],
                // 4. Fuzzy ALL-words match — handles singular/plural, typos
                //    e.g. "arrangement" will match "arrangements"
                [
                    'multi_match' => [
                        'query'     => $keyword,
                        'fields'    => ['title^3', 'description'],
                        'operator'  => 'and',
                        'fuzziness' => 'AUTO',
                        'boost'     => 2
                    ]
                ],
            ]
        ]
    ];
}

function buildDetailedWordsQuery($wordsInput)
{
    $wordsArray = array_values(array_filter(array_map('trim', explode(' ', $wordsInput))));
    if (empty($wordsArray)) return ['match_none' => (object)[]];

    return [
        'bool' => [
            'must' => [
                [
                    // Use multi_match with phrase_prefix for efficiency over span_multi/prefix
                    'multi_match' => [
                        'query'  => $wordsInput,
                        'fields' => ['title^5', 'description'],
                        'type'   => 'phrase_prefix',
                        'slop'   => 0,
                        'boost'  => 10
                    ]
                ]
            ],
            'should' => [
                // Extra boost for exact phrase match (hits without prefix expansion)
                [
                    'multi_match' => [
                        'query'  => $wordsInput,
                        'fields' => ['title^10', 'description^2'],
                        'type'   => 'phrase',
                        'slop'   => 0,
                        'boost'  => 20
                    ]
                ]
            ]
        ]
    ];
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
    $allKwArray = [];
    if (!empty($filters['keyword'])) {
        $keywords = parseKeywords($filters['keyword']);
        if (!empty($keywords)) {
            foreach ($keywords as $keyword) {
                // Use the new detailed query builder
                $should[] = buildDetailedKeywordQuery($keyword);
                $allKwArray[] = $keyword;
            }
        }
    }

    /* ---------- Tender Words ---------- */
    if (!empty($filters['words'])) {
        $wordsArray = parseKeywords($filters['words']);
        if (!empty($wordsArray)) {
            foreach ($wordsArray as $word) {
                $should[] = buildDetailedWordsQuery($word);
                $allKwArray[] = $word;
            }
        }
    }

    $functionScore = null;
    if (!empty($allKwArray)) {
        $sortKeywords = buildKeywordPriority($allKwArray);

        $functions = [];
        $baseWeight = 1000;

        foreach ($sortKeywords as $index => $keyword) {
            $weight = $baseWeight - ($index * 10);

            $functions[] = [
                'filter' => [
                    'match_phrase' => [
                        'title' => [
                            'query' => $keyword,
                            'slop' => 1
                        ]
                    ]
                ],
                'weight' => $weight + 100
            ];

            $functions[] = [
                'filter' => [
                    'match' => [
                        'title' => [
                            'query' => $keyword,
                            'operator' => 'and'
                        ]
                    ]
                ],
                'weight' => $weight
            ];

            // OPTIONAL: boost exact phrase higher
            $functions[] = [
                'filter' => [
                    'match_phrase' => [
                        'title' => $keyword
                    ]
                ],
                'weight' => $weight + 50
            ];
        }

        $functionScore = [
            'function_score' => [
                'query' => null, // will assign later
                'functions' => $functions,
                'score_mode' => 'max',
                'boost_mode' => 'replace'
            ]
        ];

    } else {
        $sort[] = ['publish_date' => ['order' => 'desc']];
    }

    // All-india-tenders script sort — apply regardless of keyword presence
    $allIndiaTendersSort = null;
    if (!empty($filters['all-india-tenders'])) {
        $allIndiaTendersSort = [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'lang' => 'painless',
                    'source' => "if (doc.containsKey('tenders') && doc['tenders'].size() > 0) { def val = doc['tenders'].value; if (val == 'new') return 1; if (val == 'live') return 2; } return 3;"
                ],
                'order' => 'asc'
            ]
        ];
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
    if ($filters['tender_value_from'] !== '' && $filters['tender_value_from'] > 0 && $filters['tender_value_to'] !== '' && $filters['tender_value_to'] > 0) {
        $filter[] = [
            'range' => [
                'tender_value' => [
                    'gte' => (float)$filters['tender_value_from'],
                    'lte' => (float)$filters['tender_value_to']
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
        'query' => (function() use ($bool, $functionScore) {
            $baseQuery = $bool
                ? ['bool' => $bool]
                : ['match_all' => (object)[]];

            if ($functionScore) {
                $functionScore['function_score']['query'] = $baseQuery;
                return $functionScore;
            }

            return $baseQuery;
        })(),
        'sort' => !empty($allKwArray)
        ? array_values(array_filter([
            $allIndiaTendersSort,
            ['_score' => ['order' => 'desc']],
            ['title.keyword' => ['order' => 'asc']]
          ]))
        : (array_merge(
            $allIndiaTendersSort ? [$allIndiaTendersSort] : [],
            $sort
          ))

    ];
}


// function build_elastic_query(array $filters, int $page = 1, int $size = 10): array
// {
//     $from = ($page - 1) * $size;

//     $must = [];
//     $filter = [];
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
//     $allKwArray = [];
//     if (!empty($filters['keyword'])) {
//         $keywords = parseKeywords($filters['keyword']);
//         if (!empty($keywords)) {
//             foreach ($keywords as $keyword) {
//                 // Use the new detailed query builder
//                 $should[] = buildDetailedKeywordQuery($keyword);
//                 $allKwArray[] = $keyword;
//             }
//         }
//     }

//     /* ---------- Tender Words ---------- */
//     if (!empty($filters['words'])) {
//         $wordsArray = parseKeywords($filters['words']);
//         if (!empty($wordsArray)) {
//             foreach ($wordsArray as $word) {
//                 $should[] = buildDetailedWordsQuery($word);
//                 $allKwArray[] = $word;
//             }
//         }
//     }

//     $functionScore = null;
//     if (!empty($allKwArray)) {
//         $sortKeywords = buildKeywordPriority($allKwArray);

//         $functions = [];
//         $baseWeight = 1000;

//         foreach ($sortKeywords as $index => $keyword) {
//             $weight = $baseWeight - ($index * 10);

//             $functions[] = [
//                 'filter' => [
//                     'match_phrase' => [
//                         'title' => [
//                             'query' => $keyword,
//                             'slop' => 1
//                         ]
//                     ]
//                 ],
//                 'weight' => $weight + 100
//             ];

//             $functions[] = [
//                 'filter' => [
//                     'match' => [
//                         'title' => [
//                             'query' => $keyword,
//                             'operator' => 'and'
//                         ]
//                     ]
//                 ],
//                 'weight' => $weight
//             ];

//             // OPTIONAL: boost exact phrase higher
//             $functions[] = [
//                 'filter' => [
//                     'match_phrase' => [
//                         'title' => $keyword
//                     ]
//                 ],
//                 'weight' => $weight + 50
//             ];
//         }

//         $functionScore = [
//             'function_score' => [
//                 'query' => null, // will assign later
//                 'functions' => $functions,
//                 'score_mode' => 'max',
//                 'boost_mode' => 'replace'
//             ]
//         ];

//         // $sort[] = [
//         //     '_script' => [
//         //         'type' => 'number',
//         //         'script' => [
//         //             'lang'   => 'painless',
//         //             'source' => "
//         //                 String title = '';
//         //                 if (params['_source'].containsKey('title')) {
//         //                     title = params['_source']['title'].toString().toLowerCase();
//         //                 } else if (doc.containsKey('title.keyword') && doc['title.keyword'].size() > 0) {
//         //                     title = doc['title.keyword'].value.toLowerCase();
//         //                 }

//         //                 if (title.length() > 0) {
//         //                     for (int i = 0; i < params.keywords.length; i++) {
//         //                         String kw = params.keywords[i];
                                
//         //                         // 1. Direct contains (covers 'chair' -> 'chairs', 'air condition' -> 'air conditioner')
//         //                         if (title.contains(kw)) {
//         //                             return i;
//         //                         }

//         //                         // 2. Handle 'sample' -> 'sampling' (dropping 'e')
//         //                         if (kw.length() > 3 && kw.endsWith('e')) {
//         //                             String root = kw.substring(0, kw.length() - 1);
//         //                             if (title.contains(root)) {
//         //                                 return i;
//         //                             }
//         //                         }
                                
//         //                         // 3. Handle 'y' -> 'ies' (e.g. 'supply' -> 'supplies')
//         //                         if (kw.length() > 3 && kw.endsWith('y')) {
//         //                              String root = kw.substring(0, kw.length() - 1); // suppl
//         //                              if (title.contains(root)) {
//         //                                  return i;
//         //                              }
//         //                         }
//         //                     }
//         //                 }
//         //                 return params.keywords.length;
//         //             ",
//         //             'params' => ['keywords' => $sortKeywords]
//         //         ],
//         //         'order' => 'asc'
//         //     ]
//         // ];

//         // // Secondary Sort: Title ASC
//         // $sort[] = ['title.keyword' => ['order' => 'asc']];
//     } else {
//         $sort[] = ['publish_date' => ['order' => 'desc']];
//     }

//     /* ---------- Publish Date range ---------- */
//     if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
//         $must[] = [
//             'range' => [
//                 'publish_date' => [
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
//     if ($filters['tender_value_from'] !== '' && $filters['tender_value_from'] > 0 && $filters['tender_value_to'] !== '' && $filters['tender_value_to'] > 0) {
//         $filter[] = [
//             'range' => [
//                 'tender_value' => [
//                     'gte' => (float)$filters['tender_value_from'],
//                     'lte' => (float)$filters['tender_value_to']
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
//     if ($should) {
//         $bool['should'] = $should;
//         $bool['minimum_should_match'] = 1;
//     }

//     /* ---------- Final Query ---------- */
//     return [
//         'from' => $from,
//         'size' => $size,
//         'track_total_hits' => true,
//         'query' => (function() use ($bool, $functionScore) {
//             $baseQuery = $bool
//                 ? ['bool' => $bool]
//                 : ['match_all' => (object)[]];

//             if ($functionScore) {
//                 $functionScore['function_score']['query'] = $baseQuery;
//                 return $functionScore;
//             }

//             return $baseQuery;
//         })(),
//         'sort' => !empty($allKwArray)
//         ? [
//             ['_score' => ['order' => 'desc']],
//             ['title.keyword' => ['order' => 'asc']]
//           ]
//         : $sort
//         // 'query' => $bool
//         //     ? ['bool' => $bool]
//         //     : ['match_all' => (object)[]],
//         // 'sort' => $sort

//     ];
// }

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
        $searchKeyword = parseKeywords($filters['search_keyword']);
        if (!empty($searchKeyword)) {
            foreach ($searchKeyword as $keyword) {
                $must[] = [
                    'multi_match' => [
                        'query'    => $keyword,
                        'fields'   => ['title', 'description'],
                        'operator' => 'and'
                    ]
                ]; 
            }
        }
    }

    /* ---------- Full text search ---------- */
    $allKwArray = [];
    if (!empty($filters['keyword'])) {
        $keywords = parseKeywords($filters['keyword']);
        if (!empty($keywords)) {
            foreach ($keywords as $kw) {
                // Use the new detailed query builder
                $should[] = buildDetailedKeywordQuery($kw);
                $allKwArray[] = $kw;
            }
        }
    }

    /* ---------- Tender Words ---------- */
    if (!empty($filters['words'])) {
        $wordsArray = parseWordsInput($filters['words']);
        if (!empty($wordsArray)) {
            foreach ($wordsArray as $word) {
                $should[] = buildDetailedWordsQuery($word);
                $allKwArray[] = $word;
            }
        }
    }

    /* ---------- Sorting for Keyword and Words ---------- */
    if (!empty($allKwArray)) {
        // Primary Sort: Sort by the index of the matched keyword (e.g., if "chair" is first, matches get score 0)
        $sortKeywords = buildKeywordPriority($allKwArray);
        $sort[] = [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'lang'   => 'painless',
                    'source' => "
                        String title = '';
                        if (doc.containsKey('title.keyword') && doc['title.keyword'].size() > 0) {
                            title = doc['title.keyword'].value.toLowerCase();
                        } else if (params['_source'].containsKey('title')) {
                            title = params['_source']['title'].toString().toLowerCase();
                        }

                        if (title.length() > 0) {
                            for (int i = 0; i < params.keywords.length; i++) {
                                String kw = params.keywords[i];
                                
                                // 1. Direct contains (covers 'chair' -> 'chairs', 'air condition' -> 'air conditioner')
                                if (title.contains(kw)) {
                                    return i;
                                }

                                // 2. Handle 'sample' -> 'sampling' (dropping 'e')
                                if (kw.length() > 3 && kw.endsWith('e')) {
                                    String root = kw.substring(0, kw.length() - 1);
                                    if (title.contains(root)) {
                                        return i;
                                    }
                                }
                                
                                // 3. Handle 'y' -> 'ies' (e.g. 'supply' -> 'supplies')
                                if (kw.length() > 3 && kw.endsWith('y')) {
                                        String root = kw.substring(0, kw.length() - 1); // suppl
                                        if (title.contains(root)) {
                                            return i;
                                        }
                                }
                            }
                        }
                        return params.keywords.length;
                    ",
                    'params' => ['keywords' => $sortKeywords]
                ],
                'order' => 'asc'
            ]
        ];

        // Secondary Sort: Title ASC
        $sort[] = ['title.keyword' => ['order' => 'asc']];
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
    if ($filters['tender_value_from'] !== '' && $filters['tender_value_from'] > 0 && $filters['tender_value_to'] !== '' && $filters['tender_value_to'] > 0) {
        $filter[] = [
            'range' => [
                'tender_value' => [
                    'gte' => (float)$filters['tender_value_from'],
                    'lte' => (float)$filters['tender_value_to']
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

/**
 * Lightweight ES query for the Archive page.
 * Key differences from build_elastic_cms_query:
 *  - No Painless script sort (expensive JVM script runs on every hit).
 *  - Date range in FILTER context (cached by ES, much faster).
 *  - _source projection built-in (reduces response payload).
 */
function build_elastic_cms_archive_query(array $filters, int $page = 1, int $size = 10): array
{
    $from = ($page - 1) * $size;

    $must     = [];
    $filter   = [];
    $must_not = [];
    $should   = [];

    /* ---------- Search Keyword ---------- */
    if (!empty($filters['search_keyword'])) {
        /*
          This handles the "search within results" scenario:
          The user already has a set of filtered tenders (via saved keywords),
          and now wants to strict-match a new phrase in Title/Description.
        */
        $searchKeyword = parseKeywords($filters['search_keyword']);
        if (!empty($searchKeyword)) {
            foreach ($searchKeyword as $keyword) {
                $must[] = [
                    'multi_match' => [
                        'query'    => $keyword,
                        'fields'   => ['title', 'description'],
                        'operator' => 'and'
                    ]
                ]; 
            }
        }
    }

    /* ---------- Full text keyword search ---------- */
    if (!empty($filters['keyword'])) {
        $keywords = parseKeywords($filters['keyword']);
        if (!empty($keywords)) {
            foreach ($keywords as $kw) {
                $should[] = buildDetailedKeywordQuery($kw);
            }
        }
    }

    /* ---------- Exclude keywords ---------- */
    if (!empty($filters['not_used_keywords']) && is_array($filters['not_used_keywords'])) {
        foreach ($filters['not_used_keywords'] as $badKw) {
            $badKw = trim($badKw);
            if ($badKw === '') continue;
            $must_not[] = ['multi_match' => ['query' => $badKw, 'fields' => ['title', 'description'], 'operator' => 'and']];
        }
    }

    /* ---------- Date range (filter context — benefits from ES cache) ---------- */
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $filter[] = ['range' => ['publish_date' => ['gte' => $filters['start_date'], 'lte' => $filters['end_date']]]];
    }

    /* ---------- Geo / category filters ---------- */
    $states = normalize_filters_array($filters['state']);
    if (!empty($states)) $filter[] = ['terms' => ['state.keyword' => $states]];

    $cities = normalize_filters_array($filters['city']);
    if (!empty($cities)) $filter[] = ['terms' => ['city.keyword' => $cities]];

    $agencies = normalize_filters_array($filters['agency'], false);
    if (!empty($agencies)) $filter[] = ['terms' => ['agency_type.keyword' => $agencies]];

    $department = normalize_filters_array($filters['department']);
    if (!empty($department)) $filter[] = ['terms' => ['department.keyword' => normalize_filter_tender_types($department)]];

    $tender_type = normalize_filters_array($filters['tender_type']);
    if (!empty($tender_type)) $filter[] = ['terms' => ['tender_type.keyword' => $tender_type]];

    if (!empty($filters['tender_value_from']) && $filters['tender_value_from'] > 0 && !empty($filters['tender_value_to']) && $filters['tender_value_to'] > 0) {
        $filter[] = ['range' => ['tender_value' => ['gte' => (float)($filters['tender_value_from'] ?? 0), 'lte' => (float)$filters['tender_value_to']]]];
    }

    /* ---------- Bool ---------- */
    $bool = [];
    if ($must)     $bool['must']     = $must;
    if ($filter)   $bool['filter']   = $filter;
    if ($must_not) $bool['must_not'] = $must_not;
    if ($should) { $bool['should'] = $should; $bool['minimum_should_match'] = 1; }

    return [
        'from'             => $from,
        'size'             => $size,
        'track_total_hits' => true,
        'query'            => $bool ? ['bool' => $bool] : ['match_all' => (object)[]],
        'sort'             => [['publish_date' => ['order' => 'desc']], ['_id' => ['order' => 'desc']]],
        '_source'          => ['title','ref_no','city','state','pincode','agency_type','publish_date','due_date','tender_value','tender_fee','tender_emd','department'],
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
        $searchKeyword = parseKeywords($filters['search_keyword']);
        if (!empty($searchKeyword)) {
            foreach ($searchKeyword as $keyword) {
                $must[] = [
                    'multi_match' => [
                        'query'    => $keyword,
                        'fields'   => ['title', 'description'],
                        'operator' => 'and'
                    ]
                ]; 
            }
        }
    }

    /* ---------- Full text search ---------- */
    $allKwArray = [];
    if (!empty($filters['keyword'])) {
        $keywords = parseKeywords($filters['keyword']);
        if (!empty($keywords)) {
            foreach ($keywords as $kw) {
                // Use the new detailed query builder
                $should[] = buildDetailedKeywordQuery($kw);
                $allKwArray[] = $kw;
            }
        }
    }


   /* ---------- Tender Words ---------- */
    if (!empty($filters['words'])) {
        $wordsArray = parseWordsInput($filters['words']);
        if (!empty($wordsArray)) {
            foreach ($wordsArray as $word) {
                $should[] = buildDetailedWordsQuery($word);
                $allKwArray[] = $word;
            }
        }
    }

    /* ---------- Sorting for Keyword and Words ---------- */
    if (!empty($allKwArray)) {
        // Primary Sort: Sort by the index of the matched keyword (e.g., if "chair" is first, matches get score 0)
        $sortKeywords = buildKeywordPriority($allKwArray);
        $sort[] = [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'lang'   => 'painless',
                    'source' => "
                        String title = '';
                        if (doc.containsKey('title.keyword') && doc['title.keyword'].size() > 0) {
                            title = doc['title.keyword'].value.toLowerCase();
                        } else if (params['_source'].containsKey('title')) {
                            title = params['_source']['title'].toString().toLowerCase();
                        }

                        if (title.length() > 0) {
                            for (int i = 0; i < params.keywords.length; i++) {
                                String kw = params.keywords[i];
                                
                                // 1. Direct contains (covers 'chair' -> 'chairs', 'air condition' -> 'air conditioner')
                                if (title.contains(kw)) {
                                    return i;
                                }

                                // 2. Handle 'sample' -> 'sampling' (dropping 'e')
                                if (kw.length() > 3 && kw.endsWith('e')) {
                                    String root = kw.substring(0, kw.length() - 1);
                                    if (title.contains(root)) {
                                        return i;
                                    }
                                }
                                
                                // 3. Handle 'y' -> 'ies' (e.g. 'supply' -> 'supplies')
                                if (kw.length() > 3 && kw.endsWith('y')) {
                                        String root = kw.substring(0, kw.length() - 1); // suppl
                                        if (title.contains(root)) {
                                            return i;
                                        }
                                }
                            }
                        }
                        return params.keywords.length;
                    ",
                    'params' => ['keywords' => $sortKeywords]
                ],
                'order' => 'asc'
            ]
        ];

        // Secondary Sort: Title ASC
        $sort[] = ['title.keyword' => ['order' => 'asc']];
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

    /* ---------- Due Date range ---------- */
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
    if ($filters['tender_value_from'] !== '' && $filters['tender_value_from'] > 0 && $filters['tender_value_to'] !== '' && $filters['tender_value_to'] > 0) {
        $filter[] = [
            'range' => [
                'tender_value' => [
                    'gte' => (float)$filters['tender_value_from'],
                    'lte' => (float)$filters['tender_value_to']
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
        $searchKeyword = parseKeywords($search);
        if (!empty($searchKeyword)) {
            foreach ($searchKeyword as $keyword) {
                $must[] = [
                    'multi_match' => [
                        'query'    => $keyword,
                        'fields'   => ['title', 'description', 'tender_id', 'ref_no', 'agency_type'],
                        'operator' => 'and'
                    ]
                ];
            }
        }
    }        

    /* ---------- Full text search ---------- */
    $allKwArray = [];
    if (!empty($filters['keyword'])) {
        $keywords = parseKeywords($filters['keyword']);
        if (!empty($keywords)) {
            foreach ($keywords as $kw) {
                // Use the new detailed query builder
                $should[] = buildDetailedKeywordQuery($kw);
                $allKwArray[] = $kw;
            }
        }
    }

    /* ---------- Tender Words ---------- */
    if (!empty($filters['words'])) {
        $wordsArray = parseWordsInput($filters['words']);
        if (!empty($wordsArray)) {
            foreach ($wordsArray as $word) {
                $should[] = buildDetailedWordsQuery($word);
                $allKwArray[] = $word;
            }
        }
    }

    /* ---------- Sorting for Keyword and Words ---------- */
    if (!empty($allKwArray)) {
        // Primary Sort: Sort by the index of the matched keyword (e.g., if "chair" is first, matches get score 0)
        $sortKeywords = buildKeywordPriority($allKwArray);
        $sort[] = [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'lang'   => 'painless',
                    'source' => "
                        String title = '';
                        if (doc.containsKey('title.keyword') && doc['title.keyword'].size() > 0) {
                            title = doc['title.keyword'].value.toLowerCase();
                        } else if (params['_source'].containsKey('title')) {
                            title = params['_source']['title'].toString().toLowerCase();
                        }

                        if (title.length() > 0) {
                            for (int i = 0; i < params.keywords.length; i++) {
                                String kw = params.keywords[i];
                                
                                // 1. Direct contains (covers 'chair' -> 'chairs', 'air condition' -> 'air conditioner')
                                if (title.contains(kw)) {
                                    return i;
                                }

                                // 2. Handle 'sample' -> 'sampling' (dropping 'e')
                                if (kw.length() > 3 && kw.endsWith('e')) {
                                    String root = kw.substring(0, kw.length() - 1);
                                    if (title.contains(root)) {
                                        return i;
                                    }
                                }
                                
                                // 3. Handle 'y' -> 'ies' (e.g. 'supply' -> 'supplies')
                                if (kw.length() > 3 && kw.endsWith('y')) {
                                        String root = kw.substring(0, kw.length() - 1); // suppl
                                        if (title.contains(root)) {
                                            return i;
                                        }
                                }
                            }
                        }
                        return params.keywords.length;
                    ",
                    'params' => ['keywords' => $sortKeywords]
                ],
                'order' => 'asc'
            ]
        ];

        // Secondary Sort: Title ASC
        $sort[] = ['title.keyword' => ['order' => 'asc']];
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
    if ($filters['tender_value_from'] !== '' && $filters['tender_value_from'] > 0 && $filters['tender_value_to'] !== '' && $filters['tender_value_to'] > 0) {
        $filter[] = [
            'range' => [
                'tender_value' => [
                    'gte' => (float)$filters['tender_value_from'],
                    'lte' => (float)$filters['tender_value_to']
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


