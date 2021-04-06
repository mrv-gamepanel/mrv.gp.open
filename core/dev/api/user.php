<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  user.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');


/* API - Provera za login */
if(isset($GET['login'])) {
	if (!($User->IsLoged()) == false) {
	    $data = ['login' => true];
	} else {
		$data = ['login' => false];
	}
	// xml or json
	if(isset($GET['xml'])) {
		echo 'xml_response';
	} else {
		header('Content-type: application/json');
		echo json_encode( $data );
	}
	die();
}