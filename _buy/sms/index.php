<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*   file                 :  _buy/paypal/paypal.php
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*   author               :  Muhamed Skoko - mskoko.me@gmail.com
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//////////////////////////

// If do not login;
if (!($User->IsLoged()) == true) {
    header('Location: /login');
    die();
}

// check the signature
$secret = '15633eeb94cf9d3b57faa6be24f722af'; // insert your secret between ''
if(empty($secret)||!check_signature($_GET, $secret)) {
	header("HTTP/1.0 404 Not Found");
	die("Error: Invalid signature");
}

$sender 	= $_GET['sender'];//phone num.
$amount 	= $_GET['amount'];//credit
$cuid 		= $_GET['cuid'];//resource i.e. user
$payment_id = $_GET['payment_id'];//unique id
$test 		= $_GET['test']; // this parameter is present only when the payment is a test payment, it's value is either 'ok' or 'fail'

//hint: find or create payment by payment_id
//additional parameters: operator, price, user_share, country

if(preg_match("/completed/i", $_GET['status'])) {
	// mark payment as successful
}

// pre_r($GET);
// die();

// print out the reply
if($test) {
	echo('TEST OK');
} else {
	echo('OK');
}

function check_signature($params_array, $secret) {
	ksort($params_array);

	$str = '';
	foreach ($params_array as $k=>$v) {
		if($k != 'sig') {
			$str .= "$k=$v";
		}
	}
	$str .= $secret;
	$signature = md5($str);

	return ($params_array['sig'] == $signature);
}


$myfile = fopen("req.txt", "w+") or die("Unable to open file!");
fwrite($myfile, print_r($_REQUEST, true));
fclose($myfile);