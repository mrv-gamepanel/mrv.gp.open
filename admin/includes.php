<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	ini_set('error_log', 'dev/logs/errors.log'); // Logging file path
	error_reporting(E_ALL | E_STRICT | E_NOTICE);
	
	if (session_status() == PHP_SESSION_NONE) {
		session_name('SESSION_ID');
	    session_start();
	}
	
	ob_start();

	date_default_timezone_set('Europe/Belgrade');

	// URL =  for global path url
	$url = $_SERVER['DOCUMENT_ROOT'];

	// Admin DIR
	$admDir = 'admin';

	// Configuration Files
	include_once($url.'/core/inc/config.php');         					// MySQL Config File

	// Classes
	include_once($url.'/admin/core/class/db.class.php'); 				// MySQL Managment Class
	include_once($url.'/admin/core/class/site.class.php'); 				// Site Managment Class
	include_once($url.'/admin/core/class/user.class.php'); 				// User Managment Class
	include_once($url.'/admin/core/class/admin.class.php'); 			// Admin Managment Class
	include_once($url.'/admin/core/class/secure.class.php');    		// Secure Managment Class
	include_once($url.'/admin/core/class/upload.class.php');    		// Upload Managment Class
	include_once($url.'/admin/core/class/alert.class.php');    			// Alert Managment Class
	include_once($url.'/admin/core/class/log.class.php');    			// Logs Managment Class
	include_once($url.'/admin/core/class/news.class.php');    			// News Managment Class
	include_once($url.'/admin/core/class/servers.class.php');    		// Servers Managment Class
	include_once($url.'/admin/core/class/server.class.php');    		// Server Managment Class
	include_once($url.'/admin/core/class/games.class.php');    			// Games Managment Class
	include_once($url.'/admin/core/class/box.class.php');    			// Box Managment Class
	include_once($url.'/admin/core/class/mods.class.php');    			// Mods Managment Class
	include_once($url.'/admin/core/class/plugins.class.php');    		// Plugins Managment Class
	include_once($url.'/admin/core/class/webftp.class.php');    		// WebFTP Managment Class
	include_once($url.'/admin/core/class/support.class.php');    		// Support Managment Class
	include_once($url.'/admin/core/class/billing.class.php');    		// Billing Managment Class

	// Initializing Classes
	$DataBase 	= new Database();
	$Site 		= new Site();
	$User 		= new User();
	$Admin 		= new Admin();
	$Secure 	= new Secure();
	$Upload 	= new Upload();
	$Alert 		= new Alert();
	$News 		= new News();
	$Servers 	= new Servers();
	$Server 	= new Server();
	$Games 		= new Games();
	$Box 		= new Box();
	$Mods 		= new Mods();
	$Plugins 	= new Plugins();
	$webFTP 	= new webFTP();
	$Support 	= new Support();
	$Billing 	= new Billing();
	
	// PHPMailer
	require_once($url.'/core/inc/libs/PHPMailer-master/class.phpmailer.php');
	require_once($url.'/core/inc/libs/PHPMailer-master/class.smtp.php');

	$mail = new PHPMailer();

	// Email template
	//include_once($url.'/assets/php/email_tmp.php');

	// GameQ 
	require_once($url.'/core/inc/libs/GameQ/Autoloader.php');
	
	$GameQ = new \GameQ\GameQ();

	//For test;
	function pre_r($Array) {
		echo '<pre>';
		print_r($Array);
		echo '</pre>';
	}

	$POST 	= filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	$GET 	= filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);