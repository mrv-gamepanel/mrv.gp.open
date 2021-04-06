<?php
	if ($_SERVER['HTTP_HOST'] == 'localhost') {
		// Database for my localhost
		define("DB_HOST", "localhost"); 	// MySQL Database Host
		define("DB_USER", "root");			// MySQL Username
		define("DB_PASS", "");  			// MySQL Password
		define("DB_NAME", "rgb_gpanel");  	// Database Name
	} else {
		// Database for public
		define("DB_HOST", "localhost"); 	// MySQL Database Host
		define("DB_USER", "root");			// MySQL Username
		define("DB_PASS", "");  	// MySQL Password
		define("DB_NAME", "mrv_gp");  		// Database Name
	}

	//App Settings
	$Config = Array(
		'Site' => Array(
			// Name
			'Name' 		=> 'Game Panel',
			// Email
			'Email' 	=> 'mskoko.me@gmail.com',
			// Author
			'Author' 	=> 'Muhamed Skoko',
		),

		'Cron' => Array(
			// Crons

		),

		//Ejj dao sam svoje vreme ispostuj.. :)
	);