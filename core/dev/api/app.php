<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  app.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/admin/includes.php');


/* API - Provera za login */
if(isset($GET['isValidRequest'])) {
	header('Content-type: application/json');
	// Get Token;
	$Token = @$Secure->SecureTxt($POST['Token']);
	if(!empty($Token)) {
		$Token = @explode('_', $Token); // serverID_userID_boxID_Token
		/************** [ SERVER ] *****************/
		$serverID 	= (int) @$Token[0]; // serverID
		$serverInfo = @$Server->serverByID($serverID);
		// is valid server id
	    if (empty($serverID) || !is_numeric($serverID)) {
	       echo json_encode(['error']);
	       die();
	    }
	    // is valid server
	    if (empty($serverInfo['id'])) {
	       echo json_encode(['error']);
	       die();
	    }
		/************** [ USER ] *******************/
		$userID 	= (int) @$Token[1]; // userID
		$userInfo 	= $User->UserDataID($userID);
		// is valid user id
	    if (empty($userID) || !is_numeric($userID)) {
	       echo json_encode(['error']);
	       die();
	    }
	    // is valid server
	    if (empty($userInfo['id'])) {
	       echo json_encode(['error']);
	       die();
	    }
		/************** [ BOX ] *********************/
		$boxID 		= (int) @$Token[2]; // boxID
		/// is valid box id
	    if (empty($boxID) || !is_numeric($boxID)) {
	       echo json_encode(['error']);
	       die();
	    }
	    // is valid box
	    if (empty($Box->boxByID($boxID)['id'])) {
	       echo json_encode(['error']);
	       die();
	    }
		/************** [ USER TOKEN ] **************/
		$uToken 	= @$Token[3]; // User Token
		// is valid user token
	    if (empty($uToken) || $uToken == '') {
	       echo json_encode(['error']);
	       die();
	    }
	    // is valid user token
	    if (empty($userInfo['Token'])) {
	       echo json_encode(['error']);
	       die();
	    }
	    // it's my token?
	    if (!($uToken === $userInfo['Token']) == true) {
	       echo json_encode(['error']);
	       die();
	    }
	    /************** [ FINAL SERVER (IF) ] *****************/
		// It's my server?
	    if(!($Server->isMyServerToken($serverID, $uToken)) == true) {
	        echo json_encode(['error']);
	        die();
	    }
	    /* ( FILE NAME ) */
	    $Game = @$Games->gameByID($serverInfo['gameID'])['smName'];
	    if(empty($Game) || $Game == '') {
	    	echo json_encode(['error']);
	        die();
	    }
	    // Set filename for game;
	    if($Game === 'cs16') {
	    	$Filename = 'screenlog.0';
	    } else if($Game === 'samp') {
			$Filename = 'server_log.txt';
	    } else {
	    	$Filename = 'screenlog.0';
	    }
	  	/************** [ VRATI MI INFO ] *****************/
	    $respInfo = Array(
	    	'Server' => Array(
	    		'User' 			=> $serverInfo['Username'],
	    		'Status'		=> $serverInfo['Status'],
	    		'Slot'			=> $serverInfo['Slot'],
	    		'isFree'		=> $serverInfo['isFree'],
	    		'expiresFor'	=> $serverInfo['expiresFor'],
	    		'Filename'		=> $Filename,
	    	)
	    );
		echo json_encode($respInfo);
		die();
	} else {
		echo json_encode(['error']);;
		die();
	}
}
/* Get All servers */
if(isset($GET['allServers'])) {
	$s = (int) @$Secure->SecureTxt($GET['s_']);
	if(isset($s) && $s == '1812') {
		$serverProblem = ['success'];
		foreach ($Servers->ServerList()['Response'] as $s_K => $s_V) {
			/* GameQ Online Server information */
			$srvIP 		= @$Server->ipAddress($s_V['id']);
			$smGameName = @$Games->gameByID($s_V['gameID'])['smName'];
			// Valid game;
			if(empty($smGameName)) {
			    die('Game?');
			} else {
			    if ($smGameName == 'fivem') {
			        $smGameName = 'gta5m';
			    } else if ($smGameName == 'mc') {
			        $smGameName = 'minecraft';
			    } else {
			        $smGameName = $Games->gameByID($s_V['gameID'])['smName'];
			    }
			}
			/* Process only by (status started in DB) */
			if(isset($s_V['isStart']) && $s_V['isStart'] === '1') {
				$GameQ->addServer([
				    'type' => $smGameName,
				    'host' => $srvIP,
				]);
				$sProcess  	= @$GameQ->process();
				$sInfo 		= @$sProcess[$srvIP];
				/* If not online */
				if(!($sInfo['gq_online'] === '1' || $sInfo['gq_online'] === true)) {
					// pre_r($sInfo);
					$serverProblem[] = Array(
						'serverID' 	=> $s_V['id'],
						'serverIP' 	=> $srvIP,
						'clientID' 	=> $s_V['userID'],
					);
				}
			}
		}
		echo json_encode($serverProblem);
		die();
	} else {
		echo json_encode(['error']);;
		die();
	}
}