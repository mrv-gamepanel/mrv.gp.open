<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  logout.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//Proveri login;
if (!($Admin->IsLoged()) == false) {
	// Get Current date, time
	$current_time = time();

	// Remove cookie
	$cookie_expiration_time = $current_time - (30 * 24 * 60 * 60);
	setcookie('PHPSESSID', '', $cookie_expiration_time, '/', null, null, TRUE);

	// Destroy session
	session_unset();
	session_destroy();
}

header('Location: /admin/');
die();