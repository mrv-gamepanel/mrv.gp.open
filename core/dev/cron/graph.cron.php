<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  graph.cron.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/admin/includes.php');

// sranje je kod. radio je poso hahaha

/* Get All Servers (Game:CS 1.6) */
foreach ($Servers->ServerListByGame($Games->gameBySmName('cs16')['id'])['Response'] as $srv_k => $srv_v) {
	// Clear all Graph
	// $Server->upGraph12($srv_v['id'], '');

	$graph12h = @$Server->serverByID($srv_v['id'])['Graph12'];
	// If empty Graph
	if (!empty($graph12h)) {
		// Get Graph form DB;
		$graph12h = $graph12h;
	} else {
		// Set Graph
		$graph12h = Array(
		    '0'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '1'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '2'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '3'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '4'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '5'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '6'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '7'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '8'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '9'  => Array( 'y' => 0, 'label' => 'n/a' ),
		    '10' => Array( 'y' => 0, 'label' => 'n/a' ),
		    '11' => Array( 'y' => 0, 'label' => 'n/a' ),
		);
		$graph12h = serialize($graph12h);
	}
	// Un-Serialize (Get Normall Array)
	$graph12h = @unserialize($graph12h); // pre_r($graph12h);
	// Obrisi prvi red iz array
	unset($graph12h[0]);
	// Normal Array;
	$graph12h 	= array_values($graph12h);
	
	// pre_r($graph12h);
	// die();

	if (time() >= ($Server->serverByID($srv_v['id'])['serverCron']+60*5)) {
		// Get Server information;
		$srvIP = $Server->ipAddress($srv_v['id']);

		$smGameName = @$Games->gameByID($Server->serverByID($srv_v['id'])['gameID'])['smName'];
		$GameQ->addServer([
		    'type' => $smGameName,
		    'host' => $srvIP,
		]);
		$GameQ->setOption('debug', false);
		$GameQ->setOption('timeout', 3);

		$sInfo      = $GameQ->process();
		$serverInfo = $sInfo[$srvIP];
		// pre_r($serverInfo);
		$Players = isset($serverInfo['num_players']) ? $serverInfo['num_players'] : 0;
		
		// Nova linija
		$newLine 	= Array(
			'0' => Array( 'y' => $Players, 'label' => date('H:i') )
		);
		// Dodaj najnoviji red;
		$graph12h = array_merge($graph12h, $newLine); // pre_r($graph12h);
		// Serialize:
		$graph12h = @serialize($graph12h);
		
		// Save To DataBase;
		if (!($Server->upGraph12($srv_v['id'], $graph12h)) == false) {
			$Server->upServerCron($srv_v['id'], time());

			echo "Grafik je uspesno updejtovan!<br>";
		} else {
			echo "Doslo je do greske!<br>";
		}
	} else {
		echo "Za ovaj server cron je odradio svoj posao.<br>";
	}
	// Clear :)
	unset($graph12h);
	unset($serverInfo);
}