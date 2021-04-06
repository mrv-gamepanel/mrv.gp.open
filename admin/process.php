<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  process.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/admin/includes.php');


////////////////////////////////////////////////////////

// If do not login;
if (!($Admin->IsLoged()) == true) {
    header('Location: /admin/login');
    die();
}

/* Add new Server */
if (isset($GET['newServer'])) {
	$boxID 			= @$Secure->SecureTxt($POST['boxID']);
	$gameID 		= @$Secure->SecureTxt($POST['gameID']);
	$userID 		= @$Secure->SecureTxt($POST['userID']);
	$serverIstice 	= @$Secure->SecureTxt($POST['serverIstice']);
	
	$rLfUser 		= @$Secure->randKeyForLink(5); // 5 exp: (fAf2g)

	// Username
	if (empty($Server->myLastSrvID($userID)['Username'])) {
		$serverUsername = 'srv_'.$userID.'_1_'.$rLfUser;
	} else {
		$gSv = explode('_', $Server->myLastSrvID($userID)['Username']);
		$serverUsername = $gSv[0].'_'.$userID.'_'.($gSv[2]+1).'_'.$rLfUser;
		if (!empty($Server->findServerUname($serverUsername)['id'])) {
			$serverUsername = $gSv[0].'_'.$userID.'_'.($gSv[2]+2).'_'.$rLfUser;
		}
	}
	$serverPassword = '';
	// Password
	if ($serverPassword == '' || empty($serverPassword)) {
		$serverPassword = $Secure->RandKey(8);
	}
	// For FDL
	if ($Games->gameByID($gameID)['smName'] == 'fdl') {
		if(empty($boxID) || empty($gameID) || empty($userID) || empty($serverIstice)) {
			die('Sva polja moraju biti popunjena!');
		}

		/* Add server */
		if (!($Server->createFDLServer($boxID, $serverUsername, $serverPassword, $userID, '0', $serverIstice)) == false) {
			header('Location: /admin/servers?game='.$gameID.'#Success');
			die();
		} else {
			header('Location: /admin/servers?game='.$gameID.'#Error');
			die();
		}
	} else { // For other games;
		$modID 			= @$Secure->SecureTxt($POST['modID']);
		$serverName 	= @$Secure->SecureTxt($POST['serverName']);
		$serverSlot 	= @$Secure->SecureTxt($POST['serverSlot']);
		$serverPort 	= @$Secure->SecureTxt($POST['serverPort']);

		if(empty($boxID) || empty($gameID) || empty($userID) || empty($modID) || empty($serverName) || empty($serverSlot) || empty($serverPort) || empty($serverIstice)) {
			die('Sva polja moraju biti popunjena!');
		}

		/* Add server */
		if (!($Server->createServer($boxID, $gameID, $modID, $serverSlot, $serverPort, $serverUsername, $serverPassword, $userID, $serverName, $serverIstice)) == false) {
			header('Location: /admin/servers#Success');
			die();
		} else {
			header('Location: /admin/servers#Error');
			die();
		}
	}
}
/* New Box */
if (isset($GET['newBox'])) {
	$boxName 		= $Secure->SecureTxt($POST['boxName']);
	$boxHost 		= $Secure->SecureTxt($POST['boxHost']); 
	$sshPort 		= $Secure->SecureTxt($POST['sshPort']); 
	$ftpPort 		= $Secure->SecureTxt($POST['ftpPort']); 
	$Username 		= $Secure->SecureTxt($POST['Username']); 
	$Password 		= $Secure->SecureTxt($POST['Password']); 
	$gameID 		= $Secure->SecureTxt($POST['gameID']); 
	$boxLocation 	= $Secure->SecureTxt($POST['boxLocation']); 
	$Note 			= $Secure->SecureTxt($POST['Note']);
	/* Not empty! */
	if (empty($boxName) || empty($boxHost) || empty($sshPort) || empty($ftpPort) || empty($Username) || empty($Password) || empty($gameID) || empty($boxLocation)) {
		die('Polja koja imaju <b>*</b> moraju biti popunjena.');
	}
	/* Save Box if ssh2 connected */
	// PHP seclib
	set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
	include('Net/SSH2.php');
	// Connect
	$SSH2 = new Net_SSH2($boxHost, $sshPort);
	if ($SSH2->login($Username, $Password)) {
		// Save Box
		if (!($Box->saveBox($boxName, $boxHost, $Username, $Password, $sshPort, $ftpPort, $Note, $boxLocation, $gameID, $Admin->AdminData()['id'])) == false) {
			header('Location: /admin/box_list?Success');
   			die();
		} else {
			header('Location: /admin/box_list?Error');
    		die();
		}
	} else {
		// Block
		die('Login Failed');
	}
}
/* Add answer on Ticket */
if (isset($GET['AnswOnTicket'])) {
	// Check Ticket ID
	$ticketID = $Secure->SecureTxt($POST['tID']);
	// Is valid Ticket ID
	if (empty($Support->ticketByID($ticketID)['id'])) {
	    $rMsg = ['error', 'This ticket does not exist or is not linked to your account!'];
        echo json_encode($rMsg);
        die();
	}
	// Check Message
	$Message = $Secure->SecureTxt($POST['Message']);
	if (empty($Message)) {
		$rMsg = ['error', 'Message is empty!?'];
        echo json_encode($rMsg);
        die();
	}
	// Save to DB;
	if (!($Support->answOnTicket($ticketID, '', $Admin->AdminData()['id'], 'Poštovani, '.$Message)) == false) {
		// Add status 'Responsible = Odgovoren';
		$Support->upStatusOnTicket($ticketID, '2');
		// Alert
		$Alert->SaveAlert('Your response has been forwarded!', 'success');
		$rMsg = ['success', 'Your response has been forwarded!'];
        echo json_encode($rMsg);
        die();
	} else {
		$rMsg = ['error', 'An error has occurred! Your response has not been forwarded!'];
        echo json_encode($rMsg);
        die();
	}
}
/* Lock Ticket */
if (isset($GET['lockTicket'])) {
	// Check Ticket ID
	$ticketID = $Secure->SecureTxt($POST['tID']);
	// Is valid Ticket ID
	if (empty($Support->ticketByID($ticketID)['id'])) {
	    $rMsg = ['error', 'This ticket does not exist or is not linked to your account!'];
        echo json_encode($rMsg);
        die();
	}
	// Add status 'Lock = Zakljucan';
	if (!($Support->upStatusOnTicket($ticketID, '2')) == false) {
		// Uspješno ste zaključili tiket!
		$rMsg = ['success', 'You have successfully completed the ticket!'];
        echo json_encode($rMsg);
        die();
	} else {
		$rMsg = ['error', 'An error has occurred! Your response has not been forwarded!'];
        echo json_encode($rMsg);
        die();
	}
}
/* Add new Admin */
if (isset($GET['newAdmin'])) {
	$Username 			= $Secure->SecureTxt($POST['Username']);
	$Password 			= md5($POST['Password']);
	$Email	 			= $Secure->SecureTxt($POST['Email']);
	$Name 	 			= $Secure->SecureTxt($POST['Name']);
	$LastName 			= $Secure->SecureTxt($POST['LastName']);
	$Rank 				= $Secure->SecureTxt($POST['Rank']);
	$admPerm 			= $POST['admin_perm'];
	$suppZa 			= $POST['admin_supp'];
	// if(empty($Username) || empty($Password) || empty($Email) || empty($Name) || empty($LastName) || empty($Rank)) {
	// 	die('Sva polja moraju biti popunjena!');
	// }
	if(count($admPerm) <= 0) {
		die('Adm perm?');
	} else {
		$admPerm = serialize($admPerm);
	}
	if(count($suppZa) <= 0) {
		die('Supporter za?');
	} else {
		$suppZa = serialize($suppZa);
	}
	if(!($Admin->addNewAdmin($Username, $Password, $Email, $Name, $LastName, $Rank, $admPerm, $suppZa, $Admin->AdminData()['id'])) == false) {
		$Alert->SaveAlert('Success!', 'success');
		$rMsg = ['success', 'Success!'];
        echo json_encode($rMsg);
        die();
	}  else {
		$Alert->SaveAlert('Wrong :(', 'error');
		$rMsg = ['error', 'Wrong :('];
        echo json_encode($rMsg);
        die();
	}
}
/* Add new User */
if (isset($GET['newUser'])) {
	$Username 			= $Secure->SecureTxt($POST['userUsername']);
	$Password 			= md5($POST['userPassword']);
	$Email	 			= $Secure->SecureTxt($POST['userEmail']);
	$Name 	 			= $Secure->SecureTxt($POST['userName']);
	$LastName 			= $Secure->SecureTxt($POST['userLastname']);
	$userPin 			= $Secure->SecureTxt($POST['userPin']);
	if(empty($Username) || empty($Password) || empty($Email) || empty($Name) || empty($LastName) || empty($Rank)) {
		die('Sva polja moraju biti popunjena!');
	}
	if(count($admPerm) <= 0) {
		die('Adm perm?');
	} else {
		$admPerm = serialize($admPerm);
	}
	if(count($suppZa) <= 0) {
		die('Supporter za?');
	} else {
		$suppZa = serialize($suppZa);
	}
	if(!($Admin->addNewAdmin($Username, $Password, $Email, $Name, $LastName, $Rank, $admPerm, $suppZa, $Admin->AdminData()['id'])) == false) {
		$Alert->SaveAlert('Success!', 'success');
		$rMsg = ['success', 'Success!'];
        echo json_encode($rMsg);
        die();
	}  else {
		$Alert->SaveAlert('Wrong :(', 'error');
		$rMsg = ['error', 'Wrong :('];
        echo json_encode($rMsg);
        die();
	}
}
/* Block Server Action */
if (isset($GET['blockServerAction'])) {
	// Server ID
	$serverID 	= @$Secure->SecureTxt($POST['sID']);
	if (empty($serverID) || $serverID == '' || !is_numeric($serverID)) {
		$Alert->SaveAlert('Wrong :( # Server is not found!', 'error');
		$rMsg = ['error', 'Wrong :( # Server is not found!'];
        echo json_encode($rMsg);
        die();
	}
	// Action
	$Action 	= @$Secure->SecureTxt($POST['Action']);
	if (empty($Action) || $Action == '') {
		$Alert->SaveAlert('Wrong :( # Action is not found!', 'error');
		$rMsg = ['error', 'Wrong :( # Action is not found!'];
        echo json_encode($rMsg);
        die();
	}
	if ($Action == 'serverOption') {
		$n = $Server->serverByID($serverID)['serverOption'];
		if ($n == '' || $n == '1') {
			$n = '0';
		} else {
			$n = '1';
		}
		// block
		if (!($Server->blockServerOption($serverID, $n)) == false) {
			$Alert->SaveAlert('Success!', 'success');
			$rMsg = ['success', 'Success!'];
	        echo json_encode($rMsg);
	        die();
		} else {
			$Alert->SaveAlert('Wrong :(', 'error');
			$rMsg = ['error', 'Wrong :('];
	        echo json_encode($rMsg);
	        die();
		}
	} else if ($Action == 'ftpBlock') {
		$n = $Server->serverByID($serverID)['ftpBlock'];
		if ($n == '' || $n == '0') {
			$n = '1';
		} else {
			$n = '0';
		}
		// block
		if (!($Server->blockFTPServerOption($serverID, $n)) == false) {
			$Alert->SaveAlert('Success!', 'success');
			$rMsg = ['success', 'Success!'];
	        echo json_encode($rMsg);
	        die();
		} else {
			$Alert->SaveAlert('Wrong :(', 'error');
			$rMsg = ['error', 'Wrong :('];
	        echo json_encode($rMsg);
	        die();
		}
	}
}
/* Action Server || Start,Restart,Stop,Reinstall */
if (isset($GET['actionServer'])) {
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	if (empty($serverID) || $serverID == '' || !is_numeric($serverID)) {
		$Alert->SaveAlert('Wrong :( # Server is not found!', 'error');
		$rMsg = ['error', 'Wrong :( # Server is not found!'];
        echo json_encode($rMsg);
        die();
	}
	// It's my server?
	if (!($Admin->IsLoged()) == true) {
		// Alert
   		$Alert->SaveAlert('Brato dje\'s posao?', 'error');
   		// Return response
   		$rMsg = ['error', 'Brato dje\'s posao?'];
        echo json_encode($rMsg);
        die();
	}
	$Action 	= $Secure->SecureTxt($POST['action']);
    // Restart Server
    $smGameName = $Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'];
    // Fix this.. (Zabranid a se jebe server .. menjanje porta i sl..)
    if ($smGameName == 'samp') {
    	// Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        $serverPass     = $Server->serverByID($serverID)['Password'];
        $Path 			= '.';
        // FTP Connect
        $cFTP = ftp_connect($serverHost, 21);
        if(!$cFTP) {
            return false;
        }
        // FTP Login
        if (@ftp_login($cFTP, $serverUser, $serverPass)) {
            ftp_pasv($cFTP, true);
            ftp_chdir($cFTP, $Path);
            // Backup last fileatime(filename)
            $loadFileB = $webFTP->getFileContent($serverID, $Path, 'server.cfg');
            // Create Game Folder if not exist
            $gameFolder = $_SERVER['DOCUMENT_ROOT'].'/files/b/'.$smGameName;
            if (!($Site->isFolder($gameFolder)) == true) {
                $Site->createFolder($gameFolder);
            } else {
                //echo '<b>Step 1)</b> - Game folder is already!<hr>';
            }
            // Create User Folder if not exist
            if (!($Site->isFolder($gameFolder.'/'.$serverUser)) == true) {
                $Site->createFolder($gameFolder.'/'.$serverUser);
            } else {
                //echo '<b>Step 2)</b> - User folder is already!<hr>';
            }
            // Backup File
            $bckFileName = $serverUser.'-server.cfg';
            // Create the backup file
            $Site->createFile($gameFolder.'/'.$serverUser.'/'.$bckFileName, $loadFileB);
            $lFile = $gameFolder.'/'.$serverUser.'/'.$bckFileName;
            // Load File
            $loadFile = file($lFile, FILE_IGNORE_NEW_LINES);

            // False..
            $bind = false;$port = false;$maxplayers = false;
		    foreach ($loadFile as &$line) {
				$val = explode(' ', $line);
				if ($val[0] == 'port') {
					$val[1] 	= $Server->serverByID($serverID)['Port'];
					$line 		= implode(' ', $val);
					$port 		= true;
				} else if ($val[0] == 'maxplayers') {
					$val[1] 	= $Server->serverByID($serverID)['Slot'];
					$line 		= implode(' ', $val);
					$maxplayers = true;
				} else if ($val[0] == 'bind') {
					$val[1] 	= $serverHost;
					$line 		= implode(' ', $val);
					$bind 		= true;
				}
			}
			unset($line);
			$nFileSecure = $gameFolder.'/'.$serverUser.'/samp_server.cfg';
		    if (!$fw = fopen(''.$nFileSecure.'', 'w+')) {
				die('error');
			}
			foreach($loadFile as $line) {
				$fb = fwrite($fw, $line.PHP_EOL);
			}
			if (!$port) {
				fwrite($fw, 'port '.$Server->serverByID($serverID)['Port'].''.PHP_EOL);
			}
			if (!$maxplayers) {
				fwrite($fw, 'maxplayers '.$Server->serverByID($serverID)['Slot'].''.PHP_EOL);
			}
			if (!$bind) {
				fwrite($fw, 'bind '.$serverHost.''.PHP_EOL);
			}
			// Save new file (fix the problem - file is not saved)
            if (ftp_put($cFTP, 'server.cfg', $nFileSecure, FTP_BINARY)) {
                $return = true;
            } else {
                $return = false;
            }
            unlink($lFile); unlink($nFileSecure);
        }
    } else if ($smGameName == 'fivem') {
    	// Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        $serverPass     = $Server->serverByID($serverID)['Password'];
        $Path 			= '/server-data/';
        // FTP Connect
        $cFTP = ftp_connect($serverHost, 21);
        if(!$cFTP) {
            return false;
        }
        // FTP Login
        if (@ftp_login($cFTP, $serverUser, $serverPass)) {
            ftp_pasv($cFTP, true);
            ftp_chdir($cFTP, $Path);

            // Backup last fileatime(filename)
            $loadFileB = $webFTP->getFileContent($serverID, $Path, 'server.cfg');

            // Create Game Folder if not exist
            $gameFolder = $_SERVER['DOCUMENT_ROOT'].'/files/b/'.$smGameName;
            if (!($Site->isFolder($gameFolder)) == true) {
                $Site->createFolder($gameFolder);
            } else {
                //echo '<b>Step 1)</b> - Game folder is already!<hr>';
            }
            // Create User Folder if not exist
            if (!($Site->isFolder($gameFolder.'/'.$serverUser)) == true) {
                $Site->createFolder($gameFolder.'/'.$serverUser);
            } else {
                //echo '<b>Step 2)</b> - User folder is already!<hr>';
            }
            // Backup File
            $bckFileName = $serverUser.'-server.cfg';
            // Create the backup file
            $Site->createFile($gameFolder.'/'.$serverUser.'/'.$bckFileName, $loadFileB);
            $lFile = $gameFolder.'/'.$serverUser.'/'.$bckFileName;
            // Load File
            $loadFile = file($lFile, FILE_IGNORE_NEW_LINES);

            // False..
            $udp = false;$tcp = false;$maxplayers = false;
		    foreach ($loadFile as &$line) {
				$val = explode(' ', $line);
				if ($val[0] == 'endpoint_add_tcp') {
					$val[1] 	= '"'.$serverHost.':'.$Server->serverByID($serverID)['Port'].'"';
					$line 		= implode(' ', $val);
					$tcp 		= true;
				} else if ($val[0] == 'endpoint_add_udp') {
					$val[1] 	= '"'.$serverHost.':'.$Server->serverByID($serverID)['Port'].'"';
					$line 		= implode(' ', $val);
					$udp = true;
				} else if ($val[0] == 'sv_maxclients') {
					$val[1] 	= $Server->serverByID($serverID)['Slot'];
					$line 		= implode(' ', $val);
					$maxplayers = true;
				}
			}
			unset($line);
			$nFileSecure = $gameFolder.'/'.$serverUser.'/fivem_server.cfg';
		    if (!$fw = fopen(''.$nFileSecure.'', 'w+')) {
				die('error');
			}
			foreach($loadFile as $line) {
				$fb = fwrite($fw, $line.PHP_EOL);
			}
			if (!$tcp) {
				fwrite($fw, 'endpoint_add_tcp "'.$serverHost.':'.$Server->serverByID($serverID)['Port'].'"'.PHP_EOL);
			}
			if (!$udp) {
				fwrite($fw, 'endpoint_add_udp "'.$serverHost.':'.$Server->serverByID($serverID)['Port'].'"'.PHP_EOL);
			}
			if (!$maxplayers) {
				fwrite($fw, 'sv_maxclients '.$Server->serverByID($serverID)['Slot'].''.PHP_EOL);
			}
			// Save new file (fix the problem - file is not saved)
            if (ftp_put($cFTP, 'server.cfg', $nFileSecure, FTP_BINARY)) {
                $return = true;
            } else {
                $return = false;
            }
            unlink($lFile); unlink($nFileSecure);
        }
	}
    // Action
	if (!($Server->serverAction($smGameName, $serverID, $Action)) == false) {
		// Update Server Start Status
		if ($Action == 'start' || $Action == 'restart') {
			$startStatus = '1';
		} else if($Action == 'stop' || $Action == 'reinstall') {
			$startStatus = '0';
		}
		$Server->upStartStatus($serverID, $startStatus, $Server->serverByID($serverID)['userID']); sleep(2);
		$Alert->SaveAlert('Success!', 'success');
		die('Success');
	} else {
		// Update Server Start Status
		$Server->upStartStatus($serverID, '0', $Server->serverByID($serverID)['userID']); sleep(2);
		$Alert->SaveAlert('Error!', 'error');
		die('Error');
	}
}
/* Remove server */
if (isset($GET['removeServer'])) {
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// It's my server?
	if (!($Admin->IsLoged()) == true) {
   		// Return response
   		$rMsg = ['error', 'Brato dje\'s posao?'];
        echo json_encode($rMsg);
        die();
	}
	// Is Server stoped?
	if ($Server->serverByID($serverID)['isStart'] === '1') {
		// Return response
   		$rMsg = ['error', 'Server mora biti stopiran!'];
        echo json_encode($rMsg);
        die();
	}
	// Box: Host : (IP)
	$boxID 		= $Server->serverByID($serverID)['boxID'];
	// Get Box  :: Host IP
	$boxHost 	= $Box->boxByID($boxID)['Host'];
	// Get Box  :: SSH2 port
	$sshPort 	= $Box->boxByID($boxID)['sshPort'];
	// Get Box :: Username
	$boxUser 	= $Box->boxByID($boxID)['Username'];
	// Get Box :: Password
	$boxPass 	= $Box->boxByID($boxID)['Password'];
	// Get Server Username
	$serverUsername = $Server->serverByID($serverID)['Username'];

	// PHP seclib
	set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
	include('Net/SSH2.php');
	// Connect
	$SSH2 = new Net_SSH2($boxHost, $sshPort);
	if (!$SSH2->login($boxUser, $boxPass)) {
		$rMsg = ['error', 'Try connect to BOX (Connection problem)'];
        echo json_encode($rMsg);
        die();
	}
	// Remove all files from FTP (user)
	$SSH2->exec("su -lc 'rm -Rf *' ".$serverUsername);
	$SSH2->setTimeout(2);
	// Delete FTP user
	$SSH2->exec("userdel ".$serverUsername);
	$SSH2->setTimeout(1);
	$SSH2->exec("rm -Rf /home/".$serverUsername);
	$SSH2->setTimeout(1);
	// Remove server from DB;
	if(!($Server->rmServer($serverID)) == false) {
		// Return response
   		$rMsg = ['success', 'Success! Server has deleted!'];
        echo json_encode($rMsg);
        die();
	} else {
	    // Return response
   		$rMsg = ['error', 'Wrong :( # Server is not deleted!'];
        echo json_encode($rMsg);
        die();
	}
}
/* Save FTP file */
if (isset($GET['saveFtpFile'])) {
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	if (empty($serverID) || $serverID == '' || !is_numeric($serverID)) {
		$Alert->SaveAlert('Wrong :( # Server is not found!', 'error');
		$rMsg = ['error', 'Wrong :( # Server is not found!'];
        echo json_encode($rMsg);
        die();
	}
	// It's my server?
	if (!($Admin->IsLoged()) == true) {
		// Alert
   		$Alert->SaveAlert('Brato dje\'s posao?', 'error');
   		// Return response
   		$rMsg = ['error', 'Brato dje\'s posao?'];
        echo json_encode($rMsg);
        die();
	}
    // File Path
	$Path 		= $Secure->SecureTxt($POST['Path']);
	// File
	$File 		= $Secure->SecureTxt($POST['File']);
	$fileEdit 	= $_POST['fileEdit'];
	// Save File
	if (!($webFTP->saveFileFTP($serverID, $Path, $File, $fileEdit)) == false) {
		$Alert->SaveAlert('Success!', 'success');
		if(!($Admin->IsLoged()) == true) { 
			header('Location: /admin/web_ftp?id='.$serverID.'&p='.$Path.'&f='.$File); 
			die('Success');
		} else {
			header('Location: /admin/web_ftp?id='.$serverID.'&p='.$Path.'&f='.$File); 
			die('Success');
		}
	} else {
		$Alert->SaveAlert('Error!', 'error');
		if(!($Admin->IsLoged()) == true) { 
			header('Location: /admin/web_ftp?id='.$serverID.'&p='.$Path.'&f='.$File); 
			die('Error');
		} else {
			header('Location: /admin/web_ftp?id='.$serverID.'&p='.$Path.'&f='.$File); 
			die('Error');
		}
	}
}
/* Get Server port */
if (isset($GET['getServerPort'])) {
	$gameID 	= @$Secure->SecureTxt($POST['gameID']);	
	$boxID 		= @$Secure->SecureTxt($POST['boxID']);
	if(empty($boxID) || empty($gameID)) {
		// Return response
   		$rMsg = ['error', 'Game & Box ID is not a valid!<br><small>Report for breaK!</small>'];
        echo json_encode($rMsg);
        die();
	}

	$Host = $Box->boxByID($boxID)['Host']; // Get IP;

	if ($Games->gameByID($gameID)['smName'] == 'cs16') {
		for($Port = 27015; $Port <= 29999; $Port++) {
			if (empty($Server->isServerValid($boxID, $Port)['id'])) { // CS 1.6
				$sPort = $Port; // Port je slobodan!
				break;
			}
		}
	} else if ($Games->gameByID($gameID)['smName'] == 'samp') {
		for($Port = 7777; $Port <= 9999; $Port++) {
			if (empty($Server->isServerValid($boxID, $Port)['id'])) { // SAMP
				$sPort = $Port; // Port je slobodan!
				break;
			}
		}
	} else if ($Games->gameByID($gameID)['smName'] == 'fivem') { // fiveM
		for($Port = 30120; $Port <= 39120; $Port++) {
			if (empty($Server->isServerValid($boxID, $Port)['id'])) {
				$sPort = $Port; // Port je slobodan!
				break;
			}
		}
	} else if ($Games->gameByID($gameID)['smName'] == 'csgo') { // CS:GO
		for($Port = 27015; $Port <= 29999; $Port++) {
			if (empty($Server->isServerValid($boxID, $Port)['id'])) {
				$sPort = $Port; // Port je slobodan!
				break;
			}
		}
	} else if ($Games->gameByID($gameID)['smName'] == 'mc') { // Minecraft
		for($Port = 25565; $Port <= 25999; $Port++) {
			if (empty($Server->isServerValid($boxID, $Port)['id'])) {
				$sPort = $Port; // Port je slobodan!
				break;
			}
		}
	} else {
		$rMsg = ['error', 'Ovu igru nemamo u ponudi!<br>Potrebno ju je dodati!'];
	    echo json_encode($rMsg);
	    die();
	}
	// Response
	if (empty($sPort) || !is_numeric($sPort)) {
		$rMsg = ['error', 'Svi portovi za ovu igru su zauzeti!<br>Potrebno je dodati novu masinu!'];
	    echo json_encode($rMsg);
	    die();
	} else {
		$rMsg = ['success', 'MRV sistem je pronasao automatski slobodan port: '.$sPort.'; Molimo vas da ga ne menjate!', $sPort];
	    echo json_encode($rMsg);
	    die();
	}
}
/* Install FDL */
if (isset($GET['installFDL'])) {
	// Get FDL ID;
	$fdlID = @$Secure->SecureTxt($POST['fdlID']);
	if (empty($fdlID) || $fdlID == '' || !is_numeric($fdlID)) {
		$rMsg = ['error', 'Wrong :(<br>FDL ID is empty!'];
        echo json_encode($rMsg);
        die();
	}
	// Is Default?
	$isDef = @$Secure->SecureTxt($POST['default']);
	if (empty($isDef) || $isDef == '') {
		$rMsg = ['error', 'Wrong :(<br>FDL Installation is not valid!'];
	    echo json_encode($rMsg);
	    die();
	}
	// Get FDL information
	$fdlInfo    = @$Server->FDLinfo($fdlID);
	// Get Box  :: BoxID
	$boxID      = $fdlInfo['boxID'];
	// Get Box  :: Host IP
	$boxHost    = $Box->boxByID($boxID)['Host'];
	// Get Box  :: SSH2 port
	$sshPort    = $Box->boxByID($boxID)['sshPort'];
	// Get Box :: Username
	$boxUser    = $Box->boxByID($boxID)['Username'];
	// Get Box :: Password
	$boxPass    = $Box->boxByID($boxID)['Password'];
	// PHP seclib
	set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
	include('Net/SSH2.php');
	// Connect
	$SSH2 = new Net_SSH2($boxHost, $sshPort);
	if (!$SSH2->login($boxUser, $boxPass)) {
	    die('Login Failed');
	}
	// FDL ~ FTP info
	$fdlUsername 		= @$Secure->SecureTxt($fdlInfo['Username']); 
	$fdlPassword 		= @$Secure->SecureTxt($fdlInfo['Password']);
	// If for FDL type installation
	if($isDef == 'false') {
		// Install FDL :: Files from my server;
		$serverID = @$Secure->SecureTxt($POST['serverID']);
		if (empty($serverID) || $serverID == '' || !is_numeric($serverID)) {
			$rMsg = ['error', 'Wrong :(<br>Server is not found!'];
	        echo json_encode($rMsg);
	        die();
		}
		// Remove all from FTP
		$SSH2->exec('rm -Rf /var/www/html/fdl/user/'.$fdlUsername.'/*');
		$SSH2->setTimeout(1);
		// Get Box  :: BoxID
		$boxID_g      = $Server->serverByID($serverID)['boxID'];
		// Get Box  :: Host IP
		$boxHost_g    = $Box->boxByID($boxID_g)['Host'];
		// FDL link:
		$fdlLink 	  = $boxHost_g.':8811';
		// Get Box  :: SSH2 port
		$sshPort_g    = $Box->boxByID($boxID_g)['sshPort'];
		// Get Box :: Username
		$boxUser_g    = $Box->boxByID($boxID_g)['Username'];
		// Get Box :: Password
		$boxPass_g    = $Box->boxByID($boxID_g)['Password'];
		// Connect
		$SSH2_g = new Net_SSH2($boxHost_g, $sshPort_g);
		if (!$SSH2_g->login($boxUser_g, $boxPass_g)) {
		    die('Login Failed');
		}
		// Server Information
		$serverUsername 	= @$Secure->SecureTxt($Server->serverByID($serverID)['Username']); 
		$serverPassword 	= @$Secure->SecureTxt($Server->serverByID($serverID)['Password']);
		// Kreiraj cstrike kopiju foldera
		$SSH2_g->exec("su -lc 'mkdir cstrike_fdl' ".$serverUsername);
		// Copy: models
		$SSH2_g->exec("su -lc 'screen -dmSL ".$serverUsername." cp -Rf /home/".$serverUsername."/cstrike/models cstrike_fdl' ".$serverUsername);
		$SSH2_g->setTimeout(2);
		// Copy: maps
		$SSH2_g->exec("su -lc 'screen -dmSL ".$serverUsername." cp -Rf /home/".$serverUsername."/cstrike/maps cstrike_fdl' ".$serverUsername);
		$SSH2_g->setTimeout(2);
		// Copy: sound
		$SSH2_g->exec("su -lc 'screen -dmSL ".$serverUsername." cp -Rf /home/".$serverUsername."/cstrike/sound cstrike_fdl' ".$serverUsername);
		$SSH2_g->setTimeout(2);
		// Copy: prites
		$SSH2_g->exec("su -lc 'screen -dmSL ".$serverUsername." cp -Rf /home/".$serverUsername."/cstrike/sprites cstrike_fdl' ".$serverUsername);
		$SSH2_g->setTimeout(2);
		// Zipuj folder;
		$SSH2_g->exec("su -lc 'tar -czvf fdl_files_".$serverUsername.".tar.gz /home/".$serverUsername."/cstrike_fdl/*' ".$serverUsername);
		$SSH2_g->setTimeout(5);
		// Obrisi folder;
		$SSH2_g->exec('rm -Rf /home/'.$serverUsername.'/cstrike_fdl');
		// Pomeri .tar.gz u /var/www/html/fdl_files;
		$SSH2_g->exec('mv /home/'.$serverUsername.'/fdl_files_'.$serverUsername.'.tar.gz /var/www/html/fdl');
		$SSH2_g->setTimeout(3);
		// Wget Files on FDL server;
		$SSH2->exec("su -lc 'wget http://".$fdlLink."/fdl/fdl_files_".$serverUsername.".tar.gz' ".$fdlUsername);
		$SSH2->setTimeout(5);
		// UnTar
		$SSH2->exec("su -lc 'tar -zxvf fdl_files_".$serverUsername.".tar.gz' ".$fdlUsername);
		$SSH2->setTimeout(5);
		// Create folder
		$SSH2->exec("su -lc 'mkdir cstrike' ".$fdlUsername);
		$SSH2->setTimeout(1);
		// Move files to cstrike folders
		$SSH2->exec("su -lc 'mv /var/www/html/fdl/user/".$fdlUsername."/home/".$serverUsername."/cstrike_fdl/* /var/www/html/fdl/user/".$fdlUsername."/cstrike/' ".$fdlUsername);
		$SSH2->setTimeout(5);
		// Obrisi nepotrebne fajlove;
		$SSH2->exec("su -lc 'rm -Rf fdl_files_".$serverUsername.".tar.gz; rm -Rf home' ".$fdlUsername);
		$SSH2->setTimeout(1);
		/*
		$rMsg = ['success', 'Success :(<br>FDL has been installed!'];
        echo json_encode($rMsg);
        die();
		 */
	} else if($isDef == 'true') {
		// Default installation (from Public mode);

		// Remove all from FTP
		$SSH2->exec('rm -Rf /var/www/html/fdl/user/'.$fdlUsername.'/*');
		$SSH2->setTimeout(1);
		// Install Files from /home/games/cs/Public/* (default fdl);

	}

	$Alert->SaveAlert('Success :(<br>FDL has been installed!', 'success');
	header('Location: /admin/fdl_view?id='.$fdlID.'&settings');
	die();
}