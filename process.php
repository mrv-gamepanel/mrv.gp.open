<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  process.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

////////////////////////////////////////////////////////
if(!($Admin->IsLoged()) == false) {
	// If do not login;
	if (!($User->IsLoged()) == true) {
		header('Location: /login');
		die();
	}
}
/* Save FTP file */
if (isset($GET['saveFtpFile'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$Alert->SaveAlert('Sorry The Demo account has no permission to execute this option!', 'error');
		header('Location: /servers');
		die('Error');
    }
    // Get Server ID
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// Is admin?
	if(!($Admin->IsLoged()) == false) {
		// It's my server?
		if(!($Server->isMyServer($serverID)) == true) {
			$Alert->SaveAlert('This is not Owner!', 'error');
			header('Location: /servers');
			die();
		}
	}
    // File Path
	$Path 		= $Secure->SecureTxt($POST['Path']);
	// File
	$File 		= $Secure->SecureTxt($POST['File']);
	// File Edit;
	$fileEdit 	= $_POST['fileEdit'];
	// Save File
	if (!($webFTP->saveFileFTP($serverID, $Path, $File, $fileEdit)) == false) {
		$Alert->SaveAlert('Success :)', 'success');
		header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f='.$File); 
		die('Success');
	} else {
		$Alert->SaveAlert('Wrong :(', 'error');
		header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f='.$File); 
		die('Error');
	}
}
/* Ftp delete Folder */
if (isset($GET['deleteFolder'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$Alert->SaveAlert('Sorry The Demo account has no permission to execute this option!', 'error');
		header('Location: /servers');
		die('Error');
    }
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	if(!($Admin->IsLoged()) == false) {
		// It's my server?
		if(!($Server->isMyServer($serverID)) == true) {
			$Alert->SaveAlert('This is not Owner!', 'error');
			header('Location: /servers');
			die();
		}
	}
    // Get Path
	$Path 		= $Secure->SecureTxt($POST['Path']);
	// Get folder name
	$folderName = $Secure->SecureTxt($POST['folderName']);
	// Delete folder
	if (!($webFTP->deleteFTPfolder($serverID, $Path, $folderName)) == false) {
		$Alert->SaveAlert('Success :)', 'success');
		header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
		die('Success');
	} else {
		$Alert->SaveAlert('Wrong :(', 'error');
		header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
		die('Error');
	}
}
/* Ftp delete file */
if (isset($GET['deleteFile'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$Alert->SaveAlert('Sorry The Demo account has no permission to execute this option!', 'error');
		header('Location: /servers');
		die('Error');
    }
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	if(!($Admin->IsLoged()) == false) {
		// It's my server?
		if(!($Server->isMyServer($serverID)) == true) {
			$Alert->SaveAlert('This is not Owner!', 'error');
			header('Location: /servers');
			die();
		}
	}
    // Get Path
	$Path 		= $Secure->SecureTxt($POST['Path']);
	// Get file name
	$fileName = $Secure->SecureTxt($POST['fileName']);
	// Delete file
	if (!($webFTP->deleteFTPfile($serverID, $Path, $fileName)) == false) {
		$Alert->SaveAlert('Success :)', 'success');
		header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
		die('Success');
	} else {
		$Alert->SaveAlert('Wrong :(', 'error');
		header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
		die('Error');
	}
}
/* Uplaod file */
if (isset($GET['ftpFileUpload'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$Alert->SaveAlert('Sorry The Demo account has no permission to execute this option!', 'error');
		header('Location: /servers');
		die('Error');
    }
    // Get server ID
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// Is admin?
	if(!($Admin->IsLoged()) == false) {
		// It's my server?
		if(!($Server->isMyServer($serverID)) == true) {
			$Alert->SaveAlert('This is not Owner!', 'error');
			header('Location: /servers');
			die();
		}
	}
    // Get Path
	$Path 		= $Secure->SecureTxt($POST['Path']);
	// Get file
	$File 		= $_FILES['newFile'];
	if (empty($File['name'])) {
		$Alert->SaveAlert('File is not uploaded!', 'error');
		if(!($Admin->IsLoged()) == true) {
			header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
		} else {
			header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
		}
		die('Error');
	}
	// Only allowed file ext;
	//File Name
	$fileUpName 	= $Secure->SecureTxt(basename($File['name']));
	//File Extensions
	$Ext 			= strtolower(pathinfo($fileUpName, PATHINFO_EXTENSION));
	if (isset($Ext) && in_array($Ext, $webFTP->allowExtForUpload())) {
		if (!($webFTP->uplaodFileOnFTP($serverID, $Path, $File)) == false) {
			$Alert->SaveAlert('Success!', 'success');
			if(!($Admin->IsLoged()) == true) {
				header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
			} else {
				header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
			}
			die('Success');
		} else {
			$Alert->SaveAlert('Error!', 'error');
			if(!($Admin->IsLoged()) == true) {
				header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
			} else {
				header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
			}
			die('Error');
		}
	} else {
		$Alert->SaveAlert('File is not support for upload on this form', 'error');
		if(!($Admin->IsLoged()) == true) {
			header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
		} else {
			header('Location: /web_ftp?id='.$serverID.'&p='.$Path.'&f=');
		}
		die('Error');
	}
}
/* Change FTP password */
if (isset($GET['changeFTPpw'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$Alert->SaveAlert('Sorry The Demo account has no permission to execute this option!', 'error');
		header('Location: /servers');
		die('Error');
    }
    // Get server ID
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// Is admin?
	if(!($Admin->IsLoged()) == false) {
		// It's my server?
		if(!($Server->isMyServer($serverID)) == true) {
			$Alert->SaveAlert('This is not Owner!', 'error');
			header('Location: /servers');
			die();
		}
	}
	// Pin Code
    if (!($User->isPinCode()) == true) {
    	$rMsg = ['error', 'Pin code is not a valid!?'];
        echo json_encode($rMsg);
        die();
    }
    // New FTP PW
	$newPW = @$Secure->RandKey(8);
	// Change FTP password
	if (!($webFTP->changeFTPpw($serverID, $newPW)) == false) {
		$rMsg = ['success', 'FTP password has changed!', $newPW];
        echo json_encode($rMsg);
        die();
	} else {
		$rMsg = ['error', 'Wrong :(<br><small>FTP password has not changed!</small>'];
        echo json_encode($rMsg);
        die();
	}
}
/* New Admin */
if (isset($GET['newAdmin'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$Alert->SaveAlert('Sorry The Demo account has no permission to execute this option!', 'error');
		header('Location: /servers');
		die('Error');
    }
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $Alert->SaveAlert('This is not Owner!', 'error');
        header('Location: /servers');
        die();
    }
    // Path
    $Path = $Secure->SecureTxt($POST['Path']);
    // File
    $File = $Secure->SecureTxt($POST['File']);
    // Type
    $adminType = $Secure->SecureTxt($POST['adminType']);
    // Admin
    $Admin = $Secure->SecureTxt($POST['Admin']);
    // Password
    $Password = $Secure->SecureTxt($POST['Password']);
    // Permissions
    $Permissions = $Secure->SecureTxt($POST['Permissions']);
    if (isset($Permissions) && $Permissions == 'slot') {
    	// SLot
    	$Perm = 'a';
    } else if (isset($Permissions) && $Permissions == 'slot_i') {
    	// Slot & Imunitet
    	$Perm = 'ab';
    } else if (isset($Permissions) && $Permissions == 'low_admin') {
    	// Low Admin
    	$Perm = 'abcdei';
    } else if (isset($Permissions) && $Permissions == 'full_admin') {
		// Full Admin
    	$Perm = 'abcdefijkmu';
    } else if (isset($Permissions) && $Permissions == 'head_admin') {
    	// Head admin
    	$Perm = 'abcdefghijkmlnopqrstu';
    } else if (isset($Permissions) && $Permissions == 'custom') {
    	$Perm='';
    	// Custom Permissions
    	$customPerm = $POST['customPerm'];
    	for ($i=0; $i < count($customPerm); $i++) { 
    		$Perm .= $customPerm[$i];
    	}
    }
    // Comment
    $Comment = $Secure->SecureTxt($POST['Comment']);
    // Admin line
    if (isset($adminType) && $adminType == 'Nick') {
		$admLine = '"'.$Admin.'" "'.$Password.'" "'.$Perm.'" "ab" // '.$Comment;
	} else if (isset($adminType) && $adminType == 'steamID') {
		if ($sifra == '') {
			$admLine = '"'.$Admin.'" "'.$Password.'" "'.$Perm.'" "ce" // '.$Comment;
		} else {
			$admLine = '"'.$Admin.'" "'.$Password.'" "'.$Perm.'" "ca" // '.$Comment;
		}
	} else if (isset($adminType) && $adminType == 'IPaddr') {
		$admLine = '"'.$Admin.'" "'.$Password.'" "'.$Perm.'" "ca" // '.$Comment;
	}
	$fileContent = $webFTP->getFileContent($serverID, $Path, $File).PHP_EOL.PHP_EOL; // (PHP_EOL = blank line)
	$fileContent .= $admLine;
	// Update new user.ini
	if (!($webFTP->saveFileFTP($serverID, $Path, $File, $fileContent)) == false) {
		// If set Rcon password set command
		if (!empty($Server->loadServerCFG($serverID, 'rcon_password'))) {
			// Send command - amx_reloadadmins for reload admins;
			$rconPass = $Server->loadServerCFG($serverID, 'rcon_password');
			include_once($url.'/core/class/rcon_hl_net.php');
			$M = new Rcon();

			$M->Connect($Box->boxByID($Server->serverByID($serverID)['boxID'])['Host'], $Server->serverByID($serverID)['Port'], $rconPass);
			$M->RconCommand('amx_reloadadmins');
		}
		$Alert->SaveAlert('Success!', 'success');
		header('Location: /admins?id='.$serverID);
		die('Success');
	} else {
		$Alert->SaveAlert('Error!', 'error');
		header('Location: /admins?id='.$serverID);
		die('Error');
	}
}
/* Action Server || Start,Restart,Stop,Reinstall */
if (isset($GET['actionServer'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
		$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// It's my server?
	if(!($Server->isMyServer($serverID)) == true) {
		$rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
	}
	/* Block Server Action */
	if($Secure->SecureTxt($Server->serverByID($serverID)['serverOption']) !== '1') {
		// Ispričavamo se trenutno vam je ugašena opcija da možete da upravljate serverom, molimo obratite se supportu za pomoć!
   		$rMsg = ['error', 'We apologize, your option to manage the server is currently disabled, please contact support for assistance!'];
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
		$Server->upStartStatus($serverID, $startStatus, $User->UserData()['id']);

		sleep(2);

		$Alert->SaveAlert('Success!', 'success');
		die('Success');
	} else {
		// Update Server Start Status
		$Server->upStartStatus($serverID, '0', $User->UserData()['id']);

		sleep(2);

		$Alert->SaveAlert('Error!', 'error');
		die('Error');
	}
}

/* Send Command by rcon (Console) */
if (isset($GET['sCommandByRcon'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$Alert->SaveAlert('Sorry The Demo account has no permission to execute this option!', 'error');
		header('Location: /servers');
		die('Error');
    }
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $Alert->SaveAlert('This is not Owner!', 'error');
        header('Location: /servers');
        die();
    }
    // Command
	$Command 	= $Secure->SecureTxt($POST['Command']);
	if (empty($Command) || $Command == '') {
		$Alert->SaveAlert('Empty command?', 'error');
        header('Location: /console?id='.$serverID);
        die();
	}
	// If set Rcon password set command
	if (!empty($Server->loadServerCFG($serverID, 'rcon_password'))) {
		// Send command
		$rconPass = $Server->loadServerCFG($serverID, 'rcon_password');
		include_once($url.'/core/class/rcon_hl_net.php');
		$M = new Rcon();

		$M->Connect($Box->boxByID($Server->serverByID($serverID)['boxID'])['Host'], $Server->serverByID($serverID)['Port'], $rconPass);
		if ($M->RconCommand($Command) !== 'Bad rcon_password.') {
			$Alert->SaveAlert('Success!', 'success');
			header('Location: /console?id='.$serverID);
	        die();	
		} else {
			$Alert->SaveAlert('Error!', 'error');
			header('Location: /console?id='.$serverID);
	        die();
		}
	}
}
/* Install mod */
if (isset($GET['installMod'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
    // Get Server ID;
	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
    }
    $modID 		= $Secure->SecureTxt($POST['modID']);
    // It's valid mod?
   	if (empty($Mods->getModByID($modID)['id'])) {
   		// Return response
   		$rMsg = ['error', 'Mod is not valid?!'];
        echo json_encode($rMsg);
        die();
   	}
   	// Server is stopped?
   	if ($Server->serverByID($serverID)['isStart'] !== '0') {
   		// Return response
   		$rMsg = ['error', 'Server mora biti stopiran!'];
        echo json_encode($rMsg);
        die();
   	}
   	// Sm Game
   	$smGameName = $Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'];
   	// Install mod
   	if (!($Server->installMod($smGameName, $serverID, $modID)) == false) {
   		// Update mod
   		$Server->updateModOnServer($serverID, $modID);
   		// Return response
   		$rMsg = ['success', 'Mod je uspesno instaliran! Molimo sacekajte minimalno 30 sekundi prije pokretanja servera.'];
        echo json_encode($rMsg);
        die();
   	} else {
   		// Return response
   		$rMsg = ['error', 'Error!'];
        echo json_encode($rMsg);
        die();
   	}
}
/* Auto Restart */
if (isset($GET['autoRs'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$Alert->SaveAlert('Sorry The Demo account has no permission to execute this option!', 'error');
		header('Location: /servers');
		die('Error');
    }

	$serverID 	= $Secure->SecureTxt($POST['srvID']);
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
    }

    $autoRsTime = $POST['autoRS'];

    if ($autoRsTime[0] == '0') {
    	$autoRsTimeAlo = '0';
    } else {
    	$autoRsTimeAlo = [];

    	for ($i=0; $i < count($autoRsTime); $i++) { 
			if (isset($autoRsTime[$i]) && empty($autoRsTime[$i])) {
				die('dje\'s poso bolan kume');
			} else {
				$podeliTime = explode(':', $autoRsTime[$i]);
				if (!isset($podeliTime)) {
					die('Jbg..');
				} else if (isset($podeliTime[0]) && !is_numeric($podeliTime[0])) {
					die('Jbg #2');
				} else if (isset($podeliTime[1]) && !is_numeric($podeliTime[1])) {
					die('Jbg #2');
				} else {
					$autoRsTimeAlo[] = $autoRsTime[$i];
				}
			}
    	}
    	// To serialize
    	$autoRsTimeAlo = serialize($autoRsTimeAlo);
    }

    // Save to DataBase
    if (!($Server->saveAutoRs($serverID, $autoRsTimeAlo)) == false) {
		$Alert->SaveAlert('Success!', 'success');
		header('Location: /autorestart?id='.$serverID);
        die();
	} else {
		$Alert->SaveAlert('Error!', 'error');
		header('Location: /autorestart?id='.$serverID);
        die();
	}

}
/* Open new ticket */
if (isset($GET['openTicket'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
	// Check Title
	$Title 			= $Secure->SecureTxt($POST['Title']);
	if (empty($Title)) {
		$rMsg = ['error', 'Title is empty..'];
        echo json_encode($rMsg);
        die();
	}
	// check Type
	$Type 			= $Secure->SecureTxt($POST['Type']);
	if (isset($Type) && !is_numeric($Type)) {
		$Type = '1';
	}
	// Check Server
	$serverID 		= $Secure->SecureTxt($POST['serverID']);
	if (isset($serverID) && empty($serverID)) {
		$serverID = 'all';
	} else {
		// It's my server?
	    if(!($Server->isMyServer($serverID)) == true) {
	        $rMsg = ['error', 'This is not Owner!'];
	        echo json_encode($rMsg);
	        die();
	    }
	}
	// Check Problem msg..
	$Problem 		= $Secure->SecureTxt($POST['Problem']);
	if (empty($Problem)) {
		$rMsg = ['error', 'Problem is empty..'];
        echo json_encode($rMsg);
        die();
	}
	// Save to DB;
	if (!($Support->newTicket($serverID, $Title, $Problem, $Type, $User->UserData()['id'])) == false) {
		$Alert->SaveAlert('The ticket has been forwarded to support!', 'success');
		$rMsg = ['success', 'The ticket has been forwarded to support!'];
        echo json_encode($rMsg);
        die();
	} else {
		$rMsg = ['error', 'An error occurred, the ticket was not sent!'];
        echo json_encode($rMsg);
        die();
	}
}
/* Add answer on Ticket */
if (isset($GET['AnswOnTicket'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
	// Check Ticket ID
	$ticketID = $Secure->SecureTxt($POST['tID']);
	// Is valid Ticket ID
	if (empty($Support->ticketByID($ticketID, $User->UserData()['id'])['id'])) {
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
	if (!($Support->answOnTicket($ticketID, $User->UserData()['id'], '', $Message)) == false) {
		// Add status 'Open = Open';
		$Support->upStatusOnTicket($ticketID, '1');
		// Alert
		$Alert->SaveAlert('Your response has been forwarded to support!', 'success');
		$rMsg = ['success', 'Your response has been forwarded to support!'];
        echo json_encode($rMsg);
        die();
	} else {
		$rMsg = ['error', 'An error has occurred! Your response has not been forwarded to support!'];
        echo json_encode($rMsg);
        die();
	}
}
/* Edit map name */
if (isset($GET['editMapOnSrv'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
	// Check Server
	$serverID 		= $Secure->SecureTxt($POST['serverID']);
	if (empty($serverID) || !is_numeric($serverID)) {
		$rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
	}
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
    }
	// Check MapName
	$mapName = $Secure->SecureTxt($POST['mapName']);
	if (empty($mapName) || strlen($mapName) < 3) {
		$rMsg = ['error', 'Map is empty!?'];
        echo json_encode($rMsg);
        die();
	}
	// Edit map
	if (!($Server->updateMap($mapName, $serverID)) == false) {
		$rMsg = ['success', 'Success!'];
        echo json_encode($rMsg);
        die();
	} else {
		$rMsg = ['error', 'Map is not saved!'];
        echo json_encode($rMsg);
        die();
	}
}
/* Show FTP pin code */
if (isset($GET['showFtpPw'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
		$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
	// Check Server
	$serverID 		= @$Secure->SecureTxt($POST['serverID']);
	if (empty($serverID) || !is_numeric($serverID)) {
		$rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
	}
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
    }
    // Pin Codes
    if (!($User->isPinCode()) == true) {
    	$rMsg = ['error', 'Valid?'];
        echo json_encode($rMsg);
        die();
    } else {
    	$rMsg = ['success', $Server->serverByID($serverID)['Password']];
        echo json_encode($rMsg);
        die();
    }
}
/* Enter Pin Code */
if (isset($GET['EnpC'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
	// Check pinCode
	$pinCode = @$Secure->SecureTxt($POST['pC']);
	if (empty($pinCode) || strlen($pinCode) < 5) {
		$rMsg = ['error', 'Min&Max 5 length!'];
        echo json_encode($rMsg);
        die();
	}
	// Check is Valid Pin Code
	if (empty($User->checkPinCode($pinCode, $User->UserData()['id'])['id'])) {
		$rMsg = ['error', 'Pin Code?!'];
        echo json_encode($rMsg);
        die();
	} else {
		// Save Pin Code
		$_SESSION['UserLogin']['pC'] = $User->UserData()['pC'];
		// Success
        $rMsg = ['success', 'Success'];
        echo json_encode($rMsg);
        die();
	}
}
/* Order server */
if (isset($GET['orderServer'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
	}
	// Da li ima vise od 3 narudzbine vec, anti spamm
	if($User->UserData()['Email'] !== 'break@mrv-hosting.com') {
		if($Billing->onlyByNotPay($User->UserData()['id'])['Count'] >= 3) {
			$rMsg = ['error', 'You alrady have 3 orders!'];
	        echo json_encode($rMsg);
	        die();
	    }
	}
	// Order Game
	$orderGame 		= (int) @$Secure->SecureTxt($POST['orderGame']);
	// Valid game
	if (isset($orderGame) && empty($orderGame) || $orderGame == '' || !is_numeric($orderGame)) {
		$rMsg = ['error', 'Game is not a valid!'];
        echo json_encode($rMsg);
        die();
	}
	if (empty($Games->gameByID($orderGame)['id'])) {
		$rMsg = ['error', 'Game is not a valid!'];
        echo json_encode($rMsg);
        die();
	}
	// Order server location
	$Location 		= @$Secure->SecureTxt($POST['orderLocation']);
	// Valid game
	if (isset($Location) && empty($Location) || $Location == '') {
		// Ispričavamo se tu lokaciju trenutno nemamo, probacemo da je nabavimo u skorije vreme!
		$rMsg = ['error', 'We apologize for not having this location at the moment, we will try to get it soon!'];
        echo json_encode($rMsg);
        die();
	}
	// Order Game -> Slot
	$orderSlots 	= (int) @$Secure->SecureTxt($POST['orderSlots']);
	// Valid game slot
	if (isset($orderSlots) && empty($orderSlots) || $orderSlots == '' || !is_numeric($orderGame)) {
		$rMsg = ['error', 'Game slot is not a valid!'];
        echo json_encode($rMsg);
        die();
	}
	if ($orderSlots <= 0) {
		$rMsg = ['error', 'Game slot is not a valid!'];
        echo json_encode($rMsg);
        die();
	}
	// Order Month
	$orderMonths 	= (int) @$Secure->SecureTxt($POST['orderMonths']);
	// Valid game Month
	if (isset($orderMonths) && empty($orderMonths) || $orderMonths == '' || !is_numeric($orderGame)) {
		$rMsg = ['error', 'How much do you want to rent your server for?'];
        echo json_encode($rMsg);
        die();
	}
	if ($orderMonths <= 0) {
		$rMsg = ['error', 'How much do you want to rent your server for?'];
        echo json_encode($rMsg);
        die();
	}
	// Popust na vise meseci
	if($orderMonths == '2') {
        $orderDiscount = (5/100);
	} else if($orderMonths == '3') {
        $orderDiscount = (10/100);
	} else if($orderMonths == '4') {
        $orderDiscount = (10/100);
    } else if($orderMonths == '6') {
        $orderDiscount = (15/100);
    } else if($orderMonths == '12') {
        $orderDiscount = (20/100);
    } else {
    	$orderDiscount = 0;
    }

	// Order Game mod
	$orderMod 		= (int) @$Secure->SecureTxt($POST['orderMod']);
	/*// Valid game mod
	if (isset($orderMod) && empty($orderMod) || $orderMod == '' || !is_numeric($orderGame)) {
		// Mod koji želite da vam instaliramo na vasem serveru nakon uplate istog ne postoji!
		$rMsg = ['error', 'The mode you want us to install on your server after paying for it does not exist!'];
        echo json_encode($rMsg);
        die();
	}*/
	if (!empty($orderMod)) {
		if(empty($Mods->getModByID($orderMod)['id'])) {
			$rMsg = ['error', 'The mode you want us to install on your server after paying for it does not exist!'];
	        echo json_encode($rMsg);
	        die();
		}
	}
	// Order Server name
	$orderName 		= @$Secure->SecureTxt($POST['orderName']);
	// Valid server name
	if (isset($orderName) && empty($orderName) || $orderName == '') {
		// Ime servera je nedopustivo!
		$rMsg = ['error', 'Server name is invalid!'];
        echo json_encode($rMsg);
        die();
	}
	$orderGB 	= (int) @$Secure->SecureTxt($POST['orderGB']);
	if(isset($orderGB) && empty($orderGB) || $orderGB == '') {
		$orderGB = 'auto';
	} else {
		$orderGB = $orderGB;
	}
	// Server Price
	if ($Games->gameByID($orderGame)['smName'] == 'cs16') { // Cena za Counter-Striek 1.6 servere
		// Lokacija Njemacka
		if ($Location == 'Germany') {
			// Centa za Slota (Germany lite location);
			$SlotPrice 		= '0.375';
			// Currency (vrednost valute - euro)
			$Currency 		= 100;
			// Get Price
			$Price 			= ((($orderSlots * $SlotPrice) - (($orderSlots * $SlotPrice ) * $orderDiscount)) * $orderMonths * $Currency/100); 
			// Order Price
			$orderPrice 	= @$Secure->pNormalMoney($Price, 'EUR', 'noc');
		}
		// ..
	} else if ($Games->gameByID($orderGame)['smName'] == 'samp') { // Cena za SAMP servere
		// Lokacija Njemacka
		if ($Location == 'Germany') {
			// Centa za Slota (Germany lite location);
			$SlotPrice 		= '0.09';
			// Currency (vrednost valute - euro)
			$Currency 		= 100;
			// Get Price
			$Price 			= ((($orderSlots * $SlotPrice) - (($orderSlots * $SlotPrice ) * $orderDiscount)) * $orderMonths * $Currency/100); 
			// Order Price
			$orderPrice 	= @$Secure->pNormalMoney($Price, 'EUR', 'noc');
		}
		// ..
	} else if ($Games->gameByID($orderGame)['smName'] == 'fivem') { // Cena za FIVEM servere
		// Lokacija Njemacka
		if ($Location == 'Germany') {
			// Centa za Slota (Germany lite location);
			$SlotPrice 		= '0.46';
			// Cena za GB;
			$priceGB 		= '1';
			// Currency (vrednost valute - euro)
			$Currency 		= 100;
			// Order price
			$o_ 			= $orderSlots * $SlotPrice + (int) $orderGB;
			// Get Price
			$Price 			= ((($o_) - (($o_) * $orderDiscount)) * $orderMonths * $Currency/100);
			// Order Price
			$orderPrice 	= @$Secure->pNormalMoney($Price, 'EUR', 'noc');
		}
	} else if ($Games->gameByID($orderGame)['smName'] == 'csgo') { // Cena za CS:GO servere
		// Lokacija Njemacka
		if ($Location == 'Germany') {
			// Centa za Slota (Germany lite location);
			$SlotPrice 		= '0.5';
			// Currency (vrednost valute - euro)
			$Currency 		= 100;
			// Get Price
			$Price 			= ((($orderSlots * $SlotPrice) - (($orderSlots * $SlotPrice ) * $orderDiscount)) * $orderMonths * $Currency/100); 
			// Order Price
			$orderPrice 	= @$Secure->pNormalMoney($Price, 'EUR', 'noc');
		}
		// ..
	} else if ($Games->gameByID($orderGame)['smName'] == 'fdl') { // Cena za FDL servere
		// Lokacija Njemacka
		if ($Location == 'Germany') {
			// Centa za Slota (Germany lite location);
			$SlotPrice 		= '2';
			// Currency (vrednost valute - euro)
			$Currency 		= 100;
			// Get Price
			$Price 			= ((($orderSlots * $SlotPrice) - (($orderSlots * $SlotPrice ) * $orderDiscount)) * $orderMonths * $Currency/100); 
			// Order Price
			$orderPrice 	= @$Secure->pNormalMoney($Price, 'EUR', 'noc');
		}
		// ..
	} else if ($Games->gameByID($orderGame)['smName'] == 'mc') { // Cena za MC servere
		// Lokacija Njemacka
		if ($Location == 'Germany') {
			// Centa za Slota (Germany lite location);
			$SlotPrice 		= '0.65';
			// Cena za GB;
			$priceGB 		= '1';
			// Currency (vrednost valute - euro)
			$Currency 		= 100;
			// Order price
			$o_ 			= $orderSlots * $SlotPrice + (int) $orderGB;
			// Get Price
			$Price 			= ((($o_) - (($o_) * $orderDiscount)) * $orderMonths * $Currency/100);
			// Order Price
			$orderPrice 	= @$Secure->pNormalMoney($Price, 'EUR', 'noc');
		}
		// ..
	}
	// Provera za Cenu
	if (isset($orderPrice) && empty($orderPrice)) {
		$rMsg = ['error', 'Rado bismo vam izasli u susret nego: no-profit man.'];
        echo json_encode($rMsg);
        die();
	} else if (isset($orderPrice) && $orderPrice < 1 || $orderPrice == 0) {
		$rMsg = ['error', 'Rado bismo vam izasli u susret nego: no-profit man.'];
        echo json_encode($rMsg);
        die();
	}
	// Save Order :)
	if (!($Billing->SaveOrder($orderGame, $Location, $orderSlots, $orderMonths, $orderMod, $orderName, $orderPrice, $orderGB, $User->UserData()['id'])) == false) {
		// Vaša narudžba je kreirana, sada možete uplatiti sredstva za podizanje servera.
		$rMsg = ['success', 'Your order has been created, you can now pay for the server.'];
        echo json_encode($rMsg);
        $Alert->SaveAlert('Your order has been created, you can now pay for the server.', 'success');
        header('Location: /billing');
        die();
	} else {
		$rMsg = ['error', 'Sorry there was an error, please try again later!'];
        echo json_encode($rMsg);
        die();
	}
}
/* Change Profile info */
if (isset($GET['editprofile'])) {
	$fName 		= @$Secure->SecureTxt($POST['fName']);
	$lName 		= @$Secure->SecureTxt($POST['lName']);
	$Username 	= @$Secure->SecureTxt($POST['Username']);
	// $Email 		= $Secure->SecureTxt($POST['Email']);
	$Token 		= @$Secure->SecureTxt($POST['Token']);
	// Unesite pin kod
	$pinCode 	= @$Secure->SecureTxt($POST['pinCode']);
	if (empty($pinCode) || !is_numeric($pinCode)) {
		$Alert->SaveAlert('Pin Code wrong!!!', 'error');
		header('Location: /profile');
        die();
	}
	if (empty($User->checkPinCode($pinCode, $User->UserData()['id'])['id'])) {
		$Alert->SaveAlert('Pin Code wrong!!!', 'error');
		header('Location: /profile');
        die();
	}
	// Valid inputs.
    if (empty($fName) || empty($lName) || empty($Username)) {
    	$Alert->SaveAlert('Error!', 'error');
		header('Location: /profile');
        die();
	}
	// Update info
	if (!($User->editProfile($fName, $lName, $Token, $User->UserData()['id'])) == false) {
		$Alert->SaveAlert('Success!', 'success');
		header('Location: /profile');
        die();
	} else {
		$Alert->SaveAlert('Error!', 'error');
		header('Location: /profile');
        die();
	}
}
/* Change Password */
if (isset($GET['changepw'])) {
	$cPass 		= @$Secure->SecureTxt($POST['cPass']);
	$nPass 		= @$Secure->SecureTxt($POST['nPass']);
	$rnPass 	= @$Secure->SecureTxt($POST['rnPass']);

	// Unesite pin kod
	$pinCode 	= @$Secure->SecureTxt($POST['pinCode']);
	if (empty($pinCode) || !is_numeric($pinCode)) {
		$Alert->SaveAlert('Pin Code wrong!!!', 'error');
		header('Location: /profile');
        die();
	}
	if (empty($User->checkPinCode($pinCode, $User->UserData()['id'])['id'])) {
		$Alert->SaveAlert('Pin Code wrong!!!', 'error');
		header('Location: /profile');
        die();
	}
	// Check inputs
	if (empty($cPass) || empty($nPass) || empty($rnPass)) {
		$Alert->SaveAlert('Password is wrong?!', 'error');
		header('Location: /profile');
        die();
	}
	// Is valid c Pass
	if (md5($cPass) !== $User->UserData()['Password']) {
		$Alert->SaveAlert('Current password is wrong', 'error');
		header('Location: /profile');
        die();
	}
	// Check n and rnPass
	if (!(md5($nPass) == md5($rnPass))) {
		$Alert->SaveAlert('Your new password does not match', 'error');
		header('Location: /profile');
        die();
	} else {
		$Password = md5($nPass);
	}
	// Update password
	if (!($User->editPassword($Password, $User->UserData()['id'])) == false) {
		$Alert->SaveAlert('Success!', 'success');
		header('Location: /profile');
        die();
	} else {
		$Alert->SaveAlert('Error!', 'error');
		header('Location: /profile');
        die();
	}
}
/* Install Plugin */
if (isset($GET['installPlugin'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
    // Get Server ID;
	$serverID 	= (int) @$Secure->SecureTxt($GET['serverID']);
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
    }
    $pluginID 	= (int) @$Secure->SecureTxt($GET['pluginID']);
    // It's valid plugin?
   	if (empty($Plugins->getPluginByID($pluginID)['id'])) {
   		$rMsg = ['error', 'Plugin is not valid?!'];
        echo json_encode($rMsg);
        die();
   	}
	// SM Game name;
   	$smGameName 	= $Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'];
   	// Proveri dali je ovaj plugin vec instaliran;

	// File Name
    $pluginFile 	= $Secure->SecureTxt(basename($Plugins->getPluginByID($pluginID)['pluginDir']));
    // Plugin Path
    $Path 			= urldecode('cstrike/addons/amxmodx/plugins/'.$pluginFile);
    // Box: Host | Server IP
    $boxID          = $Server->serverByID($serverID)['boxID'];
    $serverHost     = $Box->boxByID($boxID)['Host'];
    $serverUser     = $Server->serverByID($serverID)['Username'];
    $serverPass     = $Server->serverByID($serverID)['Password'];
    // If exists
    $pluginPath = 'ftp://'.urlencode($serverUser).':'.urlencode($serverPass).'@'.$serverHost.':21/'.$Path;
    @file_exists($pluginPath) ? $pluginLocated = true : $pluginLocated = false;
    // Get Parameter
    $isPluginEnable = @$Server->getParamByFile($serverID, '/cstrike/addons/amxmodx/configs/', 'mrv-plugins.ini', $pluginFile);
    // If Installed & Enabled;
    if ($pluginLocated == true && $isPluginEnable == true) {
        $pluginInstalled = true;    // Plugin je instaliran;
    } else {
        $pluginInstalled = false;
    }
    // If plugin not installed; install it;
    if ($pluginInstalled == false) {
    	// Instaliraj plugin
    	if (!($Plugins->installPlugin($serverID, $pluginID)) == false) {
    		// Ukljuci plugin; - Dodaj plugin na listi file: mrv-plugins.ini | plugin_name.amxx ..
    		if ($isPluginEnable == false) {
    			// Add plugin from list;
    			$fileContent  = $webFTP->getFileContent($serverID, '/cstrike/addons/amxmodx/configs/', 'mrv-plugins.ini').PHP_EOL; // PHP_EOL = (blank line)
    			$fileContent .= $pluginFile;
    			// Enable plugin
    			if (!($webFTP->saveFileFTP($serverID, '/cstrike/addons/amxmodx/configs/', 'mrv-plugins.ini', $fileContent)) == false) {
					$r = 'ok';
				} else {
					$r = 'err';
				}
    		} else {
    			$r = 'ok';
    		}
    	} else {
    		$r = 'err';
    	}
    } else {
    	// Ovaj plugin je vec instaliran;
    	$r = 'ok';
    }
    // Return;
    if ($r == 'ok') {
    	$Alert->SaveAlert('Success :)', 'success');
    	header('Location: /plugins?id='.$serverID);
    	die();
    } else {
   		$Alert->SaveAlert('Wrong :(', 'error');
    	header('Location: /plugins?id='.$serverID);
    	die();
    }
}
/* Server settings */
if (isset($GET['saveServerSettings'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
    // Get Server ID;
	$serverID 	= (int) @$Secure->SecureTxt($POST['serverID']);
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();
    }
    // Get Type (Key)
	$Type 		= @$Secure->SecureTxt($POST['sfType']);
	if (empty($Type) || $Type == '') {
		$rMsg = ['error', 'This Key is not a valid!'];
        echo json_encode($rMsg);
        die();
	}
	// New Value
	$NewValue 	= @$Secure->SecureTxt($POST['sfNewValue']);
	// Get Last Key;
	$getKey 	= @$Secure->SecureTxt($Server->loadServerCFG($serverID, $Type));
	// Parametri; - Last Value and New Value; - replace
	if ($Type == 'sv_password') {
		$lValue = 'sv_password "'.$getKey.'"';
		$nValue = 'sv_password "'.$NewValue.'"';
		if (empty($NewValue)) {
			$nValue = '""';
		}
	} else if ($Type == 'hostname') {
		$lValue = 'hostname "'.$getKey.'"';
		$nValue = 'hostname "'.$NewValue.'"';
		if (empty($NewValue)) {
			$nValue = '""';
		}
	} else if ($Type == 'rcon_password') {
		$lValue = 'rcon_password "'.$getKey.'"';
		$nValue = 'rcon_password "'.$NewValue.'"';
		if (empty($NewValue)) {
			$nValue = '""';
		}
	} else if ($Type == 'sv_gravity') {
		$lValue = 'sv_gravity '.$getKey;
		$nValue = 'sv_gravity '.$NewValue;
	} else if ($Type == 'mp_friendlyfire') {
		$lValue = 'mp_friendlyfire '.$getKey;
		$nValue = 'mp_friendlyfire '.$NewValue;
	} else if ($Type == 'mp_freezetime') {
		$lValue = 'mp_freezetime '.$getKey;
		$nValue = 'mp_freezetime '.$NewValue;
	} else if ($Type == 'mp_startmoney') {
		$lValue = 'mp_startmoney '.$getKey;
		$nValue = 'mp_startmoney '.$NewValue;
	} else if ($Type == 'mp_timelimit') {
		$lValue = 'mp_timelimit '.$getKey;
		$nValue = 'mp_timelimit '.$NewValue;
	} else if ($Type == 'mp_maxrounds') {
		$lValue = 'mp_maxrounds '.$getKey;
		$nValue = 'mp_maxrounds '.$NewValue;
	} else if ($Type == 'mp_buytime') {
		$lValue = 'mp_buytime '.$getKey;
		$nValue = 'mp_buytime '.$NewValue;
	} else {
		$rMsg = ['error', 'Wrong :(<br><small>This Key is not a valid!</small>'];
        echo json_encode($rMsg);
        die();
	}
	// Get server.cfg file;
	$getServerCFG = $webFTP->getFileContent($serverID, '/cstrike/', 'server.cfg');
	// Change Value;
	$getServerCFG = str_ireplace($lValue, $nValue, $getServerCFG);
	// Save file;
	if (!($webFTP->saveFileFTP($serverID, '/cstrike/', 'server.cfg', $getServerCFG)) == false) {
		$rMsg = ['success', 'Success :)<br><small>Saved!</small>'];
        echo json_encode($rMsg);
        die();
	} else {
		$rMsg = ['error', 'Wrong :(<br><small>This config is not saved!</small>'];
        echo json_encode($rMsg);
        die();
	}
}
/* Add MySQL */
if(isset($GET['addMySQL'])) {
	// Block for Demo :)
    if ($User->UserData()['Username'] === 'mrv-demo') {
    	// Ispričavamo se Demo nalog nema permisije da izvrši ovu opciju!
    	$rMsg = ['error', 'Sorry The Demo account has no permission to execute this option!'];
        echo json_encode($rMsg);
        die();
    }
    // Get Server ID;
	$serverID 	= (int) @$Secure->SecureTxt($GET['serverID']);
	// It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        /*$rMsg = ['error', 'This is not Owner!'];
        echo json_encode($rMsg);
        die();*/
        $Alert->SaveAlert('Wrong :(<br>This is not Owner!', 'error');
		header('Location: /servers');
		die('Error');
    }
    // Server max database : 2
    if($sMySQL->myMySQLservers($serverID, $User->UserData()['id'])['Count'] >= 2) {
		/*$rMsg = ['error', 'Wrong :(<br><small>You can have a maximum of 2 MySQL databases per server!</small>'];
        echo json_encode($rMsg);
        die();*/
    	$Alert->SaveAlert('Wrong :(<br>You can have a maximum of 2 MySQL databases per server!', 'error');
		header('Location: /mysql?id='.$serverID);
		die('Error');
    }
    // Box: Host : (IP)
	$boxID      =  '4';
	// Get Box  :: Host IP
	$boxHost    = $Box->boxByID($boxID)['Host'];
	// Get Box  :: SSH2 port
	$sshPort    = $Box->boxByID($boxID)['sshPort'];
	// Get Box :: Username
	$boxUser    = $Box->boxByID($boxID)['Username'];
	// Get Box :: Password
	$boxPass    = $Box->boxByID($boxID)['Password'];

	$rLfUser    = @$Secure->randKeyForLink(5); // Random key (5)
	$Parm 		= 'mrv_'.$User->UserData()['id'].'_'.$serverID.'_'.$rLfUser; // Exp: mrv_1_1_sd1g3
	// MySQL new user Params;
	$dbName 	= $Parm;
	$mysqlUser 	= $Parm;
	$mysqlPass 	= @$Secure->randKeyForLink(10); // Random password (10)
	/* Create connection */
	$conn = new mysqli($boxHost, $boxUser, $boxPass);
	/* Check connection */
	if ($conn->connect_error) {
	    // die("Connection failed: " . $conn->connect_error);
	    $Alert->SaveAlert('Wrong :(<br>Connection failed', 'error');
		header('Location: /mysql?id='.$serverID);
		die('Error');
	}
	// Add new DataBase & User
	$addQueries = array(
	    "CREATE DATABASE IF NOT EXISTS $dbName;",
	    "CREATE USER IF NOT EXISTS '$mysqlUser'@'%' IDENTIFIED BY '$mysqlPass';",
	    "GRANT ALL PRIVILEGES ON $dbName.* TO '$mysqlUser'@'%' IDENTIFIED BY '$mysqlPass';",
	    "FLUSH PRIVILEGES;"
	);
	// Execute
	foreach($addQueries as $Query) {
	    echo 'Executing query: "'.htmlentities($Query).'" ... ';
	    $ex = $conn->query($Query);
	    echo ($ex ? 'OK' : 'FAIL') . '<br>';
	}
	// Close connecton
	$conn->close();
	if(!($sMySQL->newMySQLuser($boxID, $serverID, $User->UserData()['id'], $mysqlUser, $mysqlPass)) == false) {
		/*$rMsg = ['success', 'Success :)<br><small>Saved!</small>'];
        echo json_encode($rMsg);
        die();*/
        $Alert->SaveAlert('Success :)<br>MySQL created!', 'success');
		header('Location: /mysql?id='.$serverID);
		die('Error');
	} else {
		/*$rMsg = ['error', 'Wrong :(<br><small>This config is not saved!</small>'];
        echo json_encode($rMsg);
        die();*/
        $Alert->SaveAlert('Wrong :(<br>MySQL was not created!', 'error');
		header('Location: /mysql?id='.$serverID);
		die('Error');
	}
}
/* Remove MySQL User & DB; */
if(isset($GET['rmMySQL'])) {
	// https://localhost/process?rmMySQL

	// Box: Host : (IP)
	$boxID      =  '4';
	// Get Box  :: Host IP
	$boxHost    = $Box->boxByID($boxID)['Host'];
	// Get Box  :: SSH2 port
	$sshPort    = $Box->boxByID($boxID)['sshPort'];
	// Get Box :: Username
	$boxUser    = $Box->boxByID($boxID)['Username'];
	// Get Box :: Password
	$boxPass    = $Box->boxByID($boxID)['Password'];
	// MySQL new user Params;
	$dbName 	= 'mrv_1_30_yaijg';
	$mysqlUser 	= 'mrv_1_30_yaijg';
	/* Create connection */
	$conn = new mysqli($boxHost, $boxUser, $boxPass);
	/* Check connection */
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	// Remove DataBase & User
	$rmQueries = array(
	    "REVOKE ALL PRIVILEGES, GRANT OPTION FROM '$mysqlUser'@'%';",
	    "DROP USER IF EXISTS '$mysqlUser'@'%';",
	    "DROP DATABASE IF EXISTS $dbName;",
	    "FLUSH PRIVILEGES;"
	);
	// Execute
	foreach($rmQueries as $Query) {
	    echo 'Executing query: "'.htmlentities($Query).'" ... ';
	    $ex = $conn->query($Query);
	    echo ($ex ? 'OK' : 'FAIL') . '<br>';
	}
	// Close connecton
	$conn->close();
}