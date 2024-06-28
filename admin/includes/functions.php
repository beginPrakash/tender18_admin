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
    $variable = strip_tags(mysqli_real_escape_string($GLOBALS['con'], trim($variable)));
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
    $fetch_per = mysqli_fetch_assoc(mysqli_query($GLOBALS['con'], "SELECT `key_value` FROM `user_permission` where user_id={$user_id} AND key_name='{$key_name}'"));
    $val = (!empty($fetch_per['key_value'])) ? $fetch_per['key_value'] : '';
    return $val;
  }
