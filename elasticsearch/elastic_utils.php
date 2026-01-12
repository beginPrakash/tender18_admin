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