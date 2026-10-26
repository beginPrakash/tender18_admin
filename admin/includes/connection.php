<?php


// HTTP_HOST is not set when scripts run from cron / CLI
if (($_SERVER['HTTP_HOST'] ?? '') == 'localhost') {

	define('HOME_URL', 'http://localhost/tender18_admin/');
	define('INDEX_URL', 'http://localhost/tender18_admin/');
	define('ADMIN_URL', 'http://localhost/tender18_admin/admin/');
	define('ADMIN_EMAIL', 'dev@clickthedemo.com');
	$server = 'localhost';
	$user = 'root';
	$password = '';
	$db = 'tender_live';
} else {
	define('HOME_URL', 'https://tender18.com/');
	define('INDEX_URL', 'https://tender18.com/');
	define('ADMIN_URL', 'https://tender18.com/admin/');
	define('ADMIN_EMAIL', 'sales@tender18.com');
	$server = 'localhost';
	$user = 'tender18_newsite';
	$password = '8$pWt5g2.CMp';
	$db = 'tender18_newsite';
}


// PHP 8.1+ throws mysqli_sql_exception on every DB error by default.
// Keep the PHP 7 behaviour (return false + warning) that the existing code relies on.
mysqli_report(MYSQLI_REPORT_OFF);

// @: the failure is logged by the guard below; a printed warning would break JSON responses
$con = @mysqli_connect($server, $user, $password, $db);
date_default_timezone_set("Asia/Kolkata");


// On PHP 8 every mysqli_* call with $con === false is a fatal TypeError,
// so stop here with a clean response instead.
if (!$con) {
	error_log('DB connection failed: ' . mysqli_connect_error());

	if (PHP_SAPI === 'cli') {
		fwrite(STDERR, "DB connection failed: " . mysqli_connect_error() . "\n");
		exit(1);
	}

	if (!headers_sent()) {
		http_response_code(503);
		header('Retry-After: 30');
	}

	if (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/api/') !== false) {
		if (!headers_sent()) {
			header('Content-Type: application/json');
			header('Access-Control-Allow-Origin: *');
		}
		echo json_encode(array("status" => "error", "message" => "Service temporarily unavailable. Please try again shortly."));
	} else {
		echo 'Service temporarily unavailable. Please try again in a few moments.';
	}
	exit;
}
//echo "Connected successfully";
