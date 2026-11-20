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

/**
 * Build a URL slug: lowercase, only a-z, 0-9 and single dashes.
 */
function make_slug($text)
{
    $text = html_entity_decode((string) $text, ENT_QUOTES, 'UTF-8');
    $text = strtolower(trim($text));
    $text = str_replace('&', ' and ', $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return substr($text, 0, 200);
}

/**
 * True when another blog already uses $slug. $exclude_id skips the blog being edited.
 */
function blog_slug_exists($con, $slug, $exclude_id = 0)
{
    $stmt = mysqli_prepare($con, "SELECT id FROM `blogs` WHERE `slug` = ? AND `id` != ? LIMIT 1");
    $exclude_id = (int) $exclude_id;
    mysqli_stmt_bind_param($stmt, 'si', $slug, $exclude_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

/**
 * Return $slug, or $slug-2, $slug-3 ... if it is already taken.
 */
function unique_blog_slug($con, $slug, $exclude_id = 0)
{
    $candidate = $slug;
    $i = 2;
    while (blog_slug_exists($con, $candidate, $exclude_id)) {
        $candidate = $slug . '-' . $i++;
    }
    return $candidate;
}

/* ---------- Blog authors & tags ---------- */

/** Active authors as [id => name] (plus $include_id even if inactive, so edit forms keep it). */
function get_blog_author_options($con, $include_id = 0)
{
    $include_id = (int) $include_id;
    $out = [];
    $res = mysqli_query($con, "SELECT id, name, status FROM `blog_authors` WHERE status = 1 OR id = $include_id ORDER BY name ASC");
    while ($res && $row = mysqli_fetch_assoc($res)) {
        $out[(int) $row['id']] = $row['name'] . ($row['status'] ? '' : ' (inactive)');
    }
    return $out;
}

/** Id of the first-created author ("Tender18 Team"), used as default. */
function get_default_blog_author_id($con)
{
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM `blog_authors` WHERE status = 1 ORDER BY id ASC LIMIT 1"));
    return $row ? (int) $row['id'] : 0;
}

/** Tags of one blog, in saved order: [['id','name','slug'], ...] */
function get_blog_tags($con, $blog_id)
{
    $blog_id = (int) $blog_id;
    $out = [];
    $res = mysqli_query($con, "SELECT t.id, t.name, t.slug FROM `blog_tag_relations` r
        JOIN `blog_tags` t ON t.id = r.tag_id WHERE r.blog_id = $blog_id ORDER BY r.sort_order ASC, t.name ASC");
    while ($res && $row = mysqli_fetch_assoc($res)) {
        $out[] = $row;
    }
    return $out;
}

/** Replace a blog's tags with the comma-separated names in $csv. New tags are created. */
function save_blog_tags($con, $blog_id, $csv)
{
    $blog_id = (int) $blog_id;
    mysqli_query($con, "DELETE FROM `blog_tag_relations` WHERE blog_id = $blog_id");

    $seen = [];
    $order = 0;
    foreach (explode(',', (string) $csv) as $name) {
        $name = trim(preg_replace('/\s+/', ' ', strip_tags($name)));
        $name = mb_substr($name, 0, 100);
        $slug = make_slug($name);
        if ($name === '' || $slug === '' || isset($seen[$slug])) {
            continue;
        }
        $seen[$slug] = true;

        $stmt = mysqli_prepare($con, "SELECT id FROM `blog_tags` WHERE slug = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $slug);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        if ($row) {
            $tag_id = (int) $row['id'];
        } else {
            $stmt = mysqli_prepare($con, "INSERT INTO `blog_tags` (`name`, `slug`) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, 'ss', $name, $slug);
            mysqli_stmt_execute($stmt);
            $tag_id = (int) mysqli_insert_id($con);
        }
        if ($tag_id) {
            mysqli_query($con, "INSERT IGNORE INTO `blog_tag_relations` (`blog_id`, `tag_id`, `sort_order`) VALUES ($blog_id, $tag_id, " . $order++ . ")");
        }
    }
}

/** All tag names, for the tag input suggestions. */
function get_all_blog_tag_names($con)
{
    $out = [];
    $res = mysqli_query($con, "SELECT name FROM `blog_tags` ORDER BY name ASC");
    while ($res && $row = mysqli_fetch_assoc($res)) {
        $out[] = $row['name'];
    }
    return $out;
}

/** "2026-11-18" -> "18 Nov 2026" */
function format_blog_date($date)
{
    $ts = $date ? strtotime($date) : false;
    return $ts ? date('d M Y', $ts) : '';
}

/** Save author, publish date and tags posted from the blog add/edit form. */
function save_blog_meta($con, $blog_id, $post)
{
    $blog_id = (int) $blog_id;

    $author_id = (int) ($post['author_id'] ?? 0);
    $exists = $author_id ? mysqli_num_rows(mysqli_query($con, "SELECT id FROM `blog_authors` WHERE id = $author_id")) : 0;
    if (!$exists) {
        $author_id = get_default_blog_author_id($con);
    }

    // Optional: empty publish date = the date the blog was created
    $date = DateTime::createFromFormat('Y-m-d', (string) ($post['publish_date'] ?? ''));
    if ($date) {
        $publish_date = $date->format('Y-m-d');
    } else {
        $created = mysqli_fetch_assoc(mysqli_query($con, "SELECT DATE(created_at) d FROM `blogs` WHERE id = $blog_id"));
        $publish_date = $created['d'] ?? date('Y-m-d');
    }

    $stmt = mysqli_prepare($con, "UPDATE `blogs` SET author_id = ?, publish_date = ?, updated_at = NOW() WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'isi', $author_id, $publish_date, $blog_id);
    mysqli_stmt_execute($stmt);

    save_blog_tags($con, $blog_id, $post['tags'] ?? '');
}
