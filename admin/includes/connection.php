<?php


if ($_SERVER['HTTP_HOST'] == 'localhost') {

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


$con = mysqli_connect($server, $user, $password, $db);
date_default_timezone_set("Asia/Kolkata");


// if (!$con) {
//   die("Connection failed: " . mysqli_connect_error());
// }
//echo "Connected successfully";
