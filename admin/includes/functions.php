<?php
//---------- Check user loged or not ----------//
function check_login($user_id)
{
    if (!isset($user_id)) {
        header("location: " . HOME_URL . 'login/');
    }
}

//---------- Generate randomcode ----------//
function getRandomCode()
{
    $an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $su = strlen($an) - 1;
    return substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1);
}

//---------- senitize input fields ----------//
function make_safe($variable)
{
    $variable = strip_tags(mysqli_real_escape_string($GLOBALS['con'], trim((string)$variable)));
    return $variable;
}

function make_post_safe($array)
{
    foreach ($array as $key => $value) {
        $_POST[$key] = make_safe($value);
    }
    return $_POST;
}
//---------- end senitize input fields ----------//

//---------- ISO-8859-1 to UTF-8 ----------//
// Same output as utf8_encode(), which is deprecated since PHP 8.2
function latin1_to_utf8($string)
{
    return mb_convert_encoding((string)$string, 'UTF-8', 'ISO-8859-1');
}

//---------- get user data ----------//
function get_user_details($user_id)
{
    $user_query = mysqli_query($GLOBALS['con'], "SELECT * FROM user WHERE user_code='" . $user_id . "'");
    if (mysqli_num_rows($user_query)) {
        $user_data = mysqli_fetch_assoc($user_query);
        // $empdata = mysqli_fetch_assoc(mysqli_query($GLOBALS['con'],"SELECT * FROM employees WHERE unique_code='".$user_id."'"));
        if (!empty($empdata)) {
            //$user_data = array_merge($user_data,$empdata);
            $user_data = array_merge($user_data);
        }
    } else {
        $user_data = '';
    }
    return $user_data;
}
//---------- get user data end----------//

//---------- Generate randomcode of 4 length ----------//
function getRandomCodeoffourlength()
{
    $an = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $su = strlen($an) - 1;
    return substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1) .
        substr($an, rand(0, $su), 1);
}

function htmlspecialcode_generator($string)
{
    // PHP 8.1+: passing null to strpos() is deprecated; null is returned unchanged as before
    if ($string === null) {
        return $string;
    }
    if (strpos($string, "'")) {
        if (strpos($string, "\'")) {
            $string = str_replace("\'", "&#39;", $string);
        } else {
            $string = str_replace("'", "&#39;", $string);
        }
    }
    if (strpos($string, '"')) {
        if (strpos($string, '\"')) {
            $string = str_replace('\"', '&#34;', $string);
        } else {
            $string = str_replace('"', "&#34;", $string);
        }
    }
    return $string;
}

function _get_user_perby_role($user_id,$key_name,$con){
    // Empty user_id made invalid SQL; mysqli_fetch_assoc(false) is a fatal TypeError on PHP 8
    if (empty($user_id)) {
        return '';
    }
    $query = mysqli_query($GLOBALS['con'], "SELECT `key_value` FROM `user_permission` where user_id=" . (int)$user_id . " AND key_name='" . mysqli_real_escape_string($GLOBALS['con'], $key_name) . "'");
    $fetch_per = $query ? mysqli_fetch_assoc($query) : null;
    $val = (!empty($fetch_per['key_value'])) ? $fetch_per['key_value'] : '';
    return $val;
}

function highlight_search_term_box($text, $searchTerm)
{
    if (empty($searchTerm)) {
        return $text;
    }

    $highlightMarkup        = '<b>';
    $closingHighlightMarkup = '</b>';

    // Split the HTML into alternating tokens:
    //   odd-indexed  = HTML tags  (<...>)  — leave untouched
    //   even-indexed = text nodes          — apply highlighting
    $parts = preg_split('/(<[^>]+>)/s', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

    $result = '';
    foreach ($parts as $i => $part) {
        if ($i % 2 === 1) {
            // HTML tag — pass through unchanged (protects href, src, etc.)
            $result .= $part;
        } else {
            // Plain text node — safe to highlight
            $result .= preg_replace(
                '/(' . preg_quote($searchTerm, '/') . ')/iu',
                $highlightMarkup . '$1' . $closingHighlightMarkup,
                $part
            );
        }
    }

    return $result;
}

/**
 * Highlight one or more keywords inside a text string.
 *
 * Handles:
 *  - String or array input for $keywords
 *  - Multi-word keywords (each word is highlighted individually)
 *  - Case-insensitive matching
 *  - Duplicate keyword removal
 *  - Sorting by length (longest first) to avoid partial-match conflicts
 *  - HTML-safe <b> wrapping via highlight_search_term_box()
 *
 * Usage:
 *  $content = highlight_keywords_in_text($content, $metaKeyword);
 *  $title   = highlight_keywords_in_text($title,   $metaKeyword);
 *  $city    = highlight_keywords_in_text($city,    $cityName);
 *
 * @param  string       $text     The source text to highlight within.
 * @param  string|array $keywords A keyword string or array of keyword strings.
 * @return string                 The text with matched keywords wrapped in <b> tags.
 */
function highlight_keywords_in_text($text, $keywords)
{
    if (empty($text) || empty($keywords)) {
        return $text;
    }

    // Normalize to array
    $inputs = is_array($keywords) ? $keywords : [$keywords];

    // Flatten: split every phrase into individual words
    $word_arr = [];
    foreach ($inputs as $phrase) {
        $phrase = trim($phrase);
        if ($phrase === '') continue;

        $words = explode(' ', $phrase);
        foreach ($words as $word) {
            $word = trim($word);
            if ($word === '') continue;

            // Case-insensitive deduplication
            $lower = mb_strtolower($word, 'UTF-8');
            $already = false;
            foreach ($word_arr as $existing) {
                if (mb_strtolower($existing, 'UTF-8') === $lower) {
                    $already = true;
                    break;
                }
            }
            if (!$already) {
                $word_arr[] = $word;
            }
        }
    }

    if (empty($word_arr)) {
        return $text;
    }

    // Sort longest first to prevent shorter sub-strings from stealing matches
    usort($word_arr, function ($a, $b) {
        $cmp = strlen($b) - strlen($a);
        return $cmp !== 0 ? $cmp : strcmp($a, $b);
    });

    // Apply highlighting for each keyword
    $highlighted = $text;
    foreach ($word_arr as $keyword) {
        $highlighted = highlight_search_term_box($highlighted, preg_quote($keyword, '/'));
    }

    return $highlighted;
}
