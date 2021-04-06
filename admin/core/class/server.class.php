<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  server.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Server {

	/* Get Server information by BoxID & Port */
	public function isServerValid($boxID, $Port)	{
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `boxID` = :boxID AND `Port` = :Port;");
		$DataBase->Bind(':boxID', $boxID);
		$DataBase->Bind(':Port', $Port);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Get Server information by Server ID */
	public function serverByID($serverID)	{
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `id` = :serverID");
		$DataBase->Bind(':serverID', $serverID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Delete Server */
	public function rmServer($serverID) {
		global $DataBase;

		$DataBase->Query("DELETE FROM `servers` WHERE `id` = :serverID");
		$DataBase->Bind(':serverID', $serverID);

		return $DataBase->Execute();
	}

	/* Is my server */
	public function isMyServer($serverID) {
		global $Server, $User;

		// Is my server?
	    if ($Server->serverByID($serverID)['userID'] == $User->UserData()['id']) {
			return true;
	    } else {
	    	return false;
	    }
	}

	/* Get IP only */
	public function ipOnly($serverID) {
		global $Server, $Box;

		// Box: Host | Server: Port
		$boxID 	= $Server->serverByID($serverID)['boxID'];
		// Ispravi IP Adresu
		return $Box->boxByID($boxID)['Host'];
	}

	/* Get Port only */
	public function portOnly($serverID) {
		global $Server;

		// Ispravi Port
		return $Server->serverByID($serverID)['Port'];
	}

	/* Get Full Server IP */
	public function ipAddress($serverID) {
		global $Server, $Box;

		// Box: Host | Server: Port
		$boxID 	= $Server->serverByID($serverID)['boxID'];
		// Ispravi IP Adresu
		$ipAddr = $Box->boxByID($boxID)['Host'].':'.$Server->serverByID($serverID)['Port'];
		// Return IP
		return $ipAddr;
	}

	/* Get isStart */
	public function isStart($serverID) {
		global $Server;
		
		return $Server->serverByID($serverID)['isStart'];
	}

	/* Server Status */
	public function isOnline($Status) {
		global $Server;
		
		if ($Status == 1) {
	        $Status = '<span class="badge badge-success"> Online </span>';
	    } else {
	        $Status = '<span class="badge badge-danger"> Offline </span>';
	    }
		return $Status;
	}

	/* Server Status */
	public function serverStatus($serverID) {
		global $Server;
		$status = $Server->serverByID($serverID)['Status'];

		if ($status == 1) {
	        $Status = '<span class="badge badge-success"> Active </span>';
	    } else if($Status == 2) {
	        $status = '<span class="badge badge-warning"> Suspend </span>';
	    } else if($Status == 3) {
	        $status = '<span class="badge badge-danger"> Deactived </span>';
	    }
		return $Status;
	}

	/* serverRestart */
	public function serverAction($Game, $serverID, $Action) {
		global $Server, $Box, $Mods;

		if ($Game == '') {
			return false;
		} else {
			// PHP seclib
			set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
			include('Net/SSH2.php');

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
			$serverPassword = $Server->serverByID($serverID)['Password'];

			// Get Server Path
			$serverPath = '/home/'.$serverUsername;

			// Counter-Strike 1.6
			if ($Game == 'cs16') {
				if ($Action == 'restart') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);
					// Remove "./hlds_run and cp orginal"
					$SSH2->exec("su -lc 'rm hlds_run' ".$serverUsername); // Remove hlds_run;
					// Get mod directory (gamefiles)
					$getMyMod = $Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Add orginal "./hlds_run" file;
					$SSH2->exec("su -lc 'cp -Rf ".$getMyMod."/hlds_run /home/".$serverUsername."' ".$serverUsername); // Add orginal hlds_run file;
					$SSH2->setTimeout(1);
					// Chown user;
					$SSH2->exec('chown -R '.$serverUsername.' /home/'.$serverUsername);
					// Start server
					$SSH2->exec("su -lc 'screen -dmSL ".$serverUsername." ./hlds_run -game cstrike +ip ".$boxHost." +port ".$Server->serverByID($serverID)['Port']." +maxplayers ".$Server->serverByID($serverID)['Slot']." +map ".$Server->serverByID($serverID)['Map']." +sys_ticrate 300 +servercfgfile server.cfg' ".$serverUsername); // if not working remove +servercfgfile server.cfg
					$SSH2->setTimeout(3);

					$return = true;
				} else if ($Action == 'stop') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					$return = true;
				} else if ($Action == 'reinstall') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					// Get mod directory (gamefiles)
					$getMyMod = $Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Re-install server
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0700 /home/".$serverUsername."/*");
					$SSH2->setTimeout(3);

					$return = true;
				}
			} else if ($Game == 'samp') {
				if ($Action == 'restart') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);
					// Chown user;
					$SSH2->exec('chown -R '.$serverUsername.' /home/'.$serverUsername);
					// Start server
					$SSH2->exec("su -lc 'screen -dmSL ".$serverUsername." ./samp03svr' ".$serverUsername);
					$SSH2->setTimeout(3);

					$return = true;
				} else if ($Action == 'stop') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					$return = true;
				} else if ($Action == 'reinstall') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					// Get mod directory (gamefiles)
					$getMyMod = $Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Re-install server
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0700 /home/".$serverUsername."/*");
					$SSH2->setTimeout(3);

					$return = true;
				}
			} else if ($Game == 'fivem') {
				if ($Action == 'restart') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);
					// Chown user;
					$SSH2->exec('chown -R '.$serverUsername.' /home/'.$serverUsername);
					// Start server
					$SSH2->exec("su -lc 'cd server-data; screen -dmSL ".$serverUsername." ../run.sh +exec server.cfg' ".$serverUsername);
					$SSH2->setTimeout(3);

					$return = true;
				} else if ($Action == 'stop') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					$return = true;
				} else if ($Action == 'reinstall') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					// Get mod directory (gamefiles)
					$getMyMod = $Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Re-install server
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0700 /home/".$serverUsername."/*");
					$SSH2->setTimeout(3);

					// nice -n 19 rm -Rf /home/srv_1_3/* && cp -Rf /home/gamefiles/cs/Public/* /home/srv_1_3 && chown -Rf srv_1_3 /home/srv_1_3 && chmod 700 /home/srv_1_3 && exit

					$return = true;
				}
			} else if ($Game == 'csgo') {
				if ($Action == 'restart') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);
					// Chown user;
					$SSH2->exec('chown -R '.$serverUsername.' /home/'.$serverUsername);
					// Start server
					$SSH2->exec("su -lc 'cd ".$serverUsername."; screen -dmSL ".$serverUsername." ./srcds_run -game csgo -console -usercon +net_public_adr ".$boxHost." -port ".$Server->serverByID($serverID)['Port']." +game_type 0 +game_mode 1 +mapgroup mg_bomb ".$Server->serverByID($serverID)['Map']." -maxplayers_override ".$Server->serverByID($serverID)['Slot']." -autoupdate' ".$serverUsername);
					$SSH2->setTimeout(4);

					$return = true;
				} else if ($Action == 'stop') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					$return = true;
				} else if ($Action == 'reinstall') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					// Get mod directory (gamefiles)
					$getMyMod = $Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Re-install server
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0700 /home/".$serverUsername."/*");
					$SSH2->setTimeout(3);

					$return = true;
				}
			} else if ($Game == 'mc') {
				if ($Action == 'restart') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);
					// Chown user;
					$SSH2->exec('chown -R '.$serverUsername.' /home/'.$serverUsername);
					// https://minecraft.gamepedia.com/Tutorials/Setting_up_a_server
					// Start server
					$SSH2->exec("su -lc 'screen -dmSL srv_1_2_519uj java -Xmx1024M -Xms1024M -jar server.jar nogui' ".$serverUsername);
					$SSH2->setTimeout(4);

					$return = true;
				} else if ($Action == 'stop') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					$return = true;
				} else if ($Action == 'reinstall') {
					// Connect
					$SSH2 = new Net_SSH2($boxHost, $sshPort);
					if (!$SSH2->login($boxUser, $boxPass)) {
						die('Login Failed');
					}
					// Kill all screen
					$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
					$SSH2->setTimeout(1);

					// Get mod directory (gamefiles)
					$getMyMod = $Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Re-install server
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0700 /home/".$serverUsername."/*");
					$SSH2->setTimeout(3);

					$return = true;
				}
			}
		}
		return $return;
	}

	/* Update server start status */
	public function upStartStatus($srvID, $Status, $userID) {
		global $DataBase;

		$DataBase->Query("UPDATE `servers` SET `isStart` = :Status WHERE `id` = :srvID AND `userID` = :userID;");
		$DataBase->Bind(':Status', $Status);
		$DataBase->Bind(':srvID', $srvID);
		$DataBase->Bind(':userID', $userID);

		return $DataBase->Execute();
	}

	/* Load server.cfg | Game :: CS 1.6 */
	public function loadServerCFG($serverID, $byParm='') {
		global $Secure, $webFTP;

		// Get server.cfg content
		$serverCFG = $webFTP->getFileContent($serverID, '/cstrike/', 'server.cfg');

		$pattern = preg_quote($byParm, '/');
		$pattern = "/^.*$pattern.*\$/m";

		if(preg_match_all($pattern, $serverCFG, $matches)){
			$text = implode("\n", $matches[0]);
			$g = explode('"', $text);
			return $g[1];
		}
	}

	/* Install mod */
	public function installMod($Game, $serverID, $modID) {
		global $Server, $Box, $Mods;

		if ($Game == '') {
			return false;
		} else {
			// PHP seclib
			set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
			include('Net/SSH2.php');

			// Counter-Strike 1.6
			if ($Game == 'cs16') {
				// Box: Host : (IP)
				$boxID 	= $Server->serverByID($serverID)['boxID'];
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
				$serverPassword = $Server->serverByID($serverID)['Password'];

				// Get Server Path
				$serverPath = '/home/'.$serverUsername;
				// Connect
				$SSH2 = new Net_SSH2($boxHost, $sshPort);
				if (!$SSH2->login($boxUser, $boxPass)) {
					die('Login Failed');
				}
				// Kill all screen
				$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
				$SSH2->setTimeout(1);
				// Get mod directory (gamefiles)
				$getMyMod = $Mods->getModByID($modID)['modDir'];
				// Re-install server
				$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername." && chown -Rf ".$serverUsername." /home/".$serverUsername." && chmod 700 /home/".$serverUsername." && exit");
				$SSH2->setTimeout(3);

				$return = true;
			}
		}

		return $return;
	}

	/* Update mod */
	public function updateModOnServer($serverID, $modID) {
		global $DataBase;

		$DataBase->Query("UPDATE `servers` SET `modID` = :modID WHERE `id` = :serverID;");
		$DataBase->Bind(':modID', $modID);
		$DataBase->Bind(':serverID', $serverID);

		return $DataBase->Execute();
	}

	/* AutoRestart update */
	public function saveAutoRs($serverID, $autoRsTime) {
		global $DataBase;

		$DataBase->Query("UPDATE `servers` SET `autoRestart` = :autoRsTime WHERE `id` = :serverID;");
		$DataBase->Bind(':autoRsTime', $autoRsTime);
		$DataBase->Bind(':serverID', $serverID);
		
		return $DataBase->Execute();
	}

	/* Ping UserName */
	public function findServerUname($UserName) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `Username` = :Username");
		$DataBase->Bind(':Username', $UserName);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* DAj zanji server */
	public function myLastSrvID($userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `userID` = :userID ORDER by `id` DESC LIMIT 1");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Create new server */
	public function createServer($boxID, $gameID, $modID, $serverSlot, $serverPort, $serverUsername, $serverPassword, $userID, $serverName, $serverIstice) {
		global $Server, $Box, $Mods, $User, $Admin, $Secure;

		// Box: Host : (IP)
		// Get Box  :: Host IP
		$boxHost 	= $Box->boxByID($boxID)['Host'];
		// Get Box  :: SSH2 port
		$sshPort 	= $Box->boxByID($boxID)['sshPort'];
		// Get Box :: Username
		$boxUser 	= $Box->boxByID($boxID)['Username'];
		// Get Box :: Password
		$boxPass 	= $Box->boxByID($boxID)['Password'];
		// PHP seclib
		set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
		include('Net/SSH2.php');
		// Connect
		$SSH2 = new Net_SSH2($boxHost, $sshPort);
		if (!$SSH2->login($boxUser, $boxPass)) {
			die('Login Failed');
		}
		// $serverUsername = 'srv_1_3';
		// Create FTP user
		$SSH2->exec('useradd -m -s /bin/bash '.$serverUsername);
		$SSH2->setTimeout(1);
		// Change pw
		$SSH2->exec('echo -e "'.$serverPassword.'\n'.$serverPassword.'" | passwd '.$serverUsername);
		$SSH2->setTimeout(1);
		// Chown
		$SSH2->exec('chown -R '.$serverUsername.' /home/'.$serverUsername);
		$SSH2->setTimeout(1);
		// Install mod;
		// Kill all screen
		$SSH2->exec("su -lc 'killall screen' ".$serverUsername);
		$SSH2->setTimeout(1);
		// Get mod directory (gamefiles)
		$getMyMod = $Mods->getModByID($modID);
		// Re-install server
		$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod['modDir']."/* /home/".$serverUsername." && chown -Rf ".$serverUsername." /home/".$serverUsername." && chmod 700 /home/".$serverUsername." && exit");
		$SSH2->setTimeout(2);
		// Save Server to DataBase;
		if (!($Server->saveServer($boxID, $gameID, $modID, $serverSlot, $serverPort, $getMyMod['Map'], $serverUsername, $serverPassword, $userID, $serverName, $serverIstice, $Admin->AdminData()['id'])) == false) {
			return true; 
		} else {
			return false;
		}
	}

	/* Save Server */
	public function saveServer($boxID, $gameID, $modID, $serverSlot, $serverPort, $Map, $serverUsername, $serverPassword, $userID, $serverName, $serverIstice, $createdBy) {
		global $DataBase;

		$DataBase->Query("INSERT INTO `servers` (`id`, `userID`, `boxID`, `gameID`, `modID`, `Name`, `Port`, `Map`, `Slot`, `fps`, `expiresFor`, `Username`, `Password`, `Status`, `Online`, `isStart`, `commandLine`, `Note`, `isFree`, `autoRestart`, `serverOption`, `ftpBlock`, `createdBy`, `createdDate`, `lastactivity`) VALUES (NULL, :userID, :boxID, :gameID, :modID, :Name, :Port, :Map, :Slot, :fps, :expiresFor, :Username, :Password, :Status, :Online, :isStart, :commandLine, :Note, :isFree, :autoRestart, '1', '0', :createdBy, :createdDate, :lastactivity);");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Bind(':boxID', $boxID);
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Bind(':modID', $modID);
		$DataBase->Bind(':Name', $serverName);
		$DataBase->Bind(':Port', $serverPort);
		$DataBase->Bind(':Map', $Map);
		$DataBase->Bind(':Slot', $serverSlot);
		$DataBase->Bind(':fps', '1000');
		$DataBase->Bind(':expiresFor', $serverIstice);
		$DataBase->Bind(':Username', $serverUsername);
		$DataBase->Bind(':Password', $serverPassword);
		$DataBase->Bind(':Status', '1');
		$DataBase->Bind(':Online', '0');
		$DataBase->Bind(':isStart', '0');
		$DataBase->Bind(':commandLine', '');
		$DataBase->Bind(':Note', '');
		$DataBase->Bind(':isFree', '0');
		$DataBase->Bind(':autoRestart', '');
		$DataBase->Bind(':createdBy', $createdBy);
		$DataBase->Bind(':createdDate', date('d/m/Y, H:ia'));
		$DataBase->Bind(':lastactivity', time());
		
		return $DataBase->Execute();
	}

	/* Get Server is Free */
	public function isFree($serverID) {
		global $Server;
		// Uzima is Free
		$number	 = $Server->serverByID($serverID)['isFree'];
		// Prevodi
		if(!($number == 0)) {
			$isfree = 'Yes';
		} else {
			$isfree = 'No';
		}
		// Return
		return $isfree;
	}

	/* Get server by ID */
	public function CountServerByID($serverID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `id` = :ID");
		$DataBase->Bind(':ID', $serverID);
		$DataBase->Execute();

		$nArr = Array(
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Block Server Action */
	public function blockServerOption($serverID, $serverOption) {
		global $DataBase;

		$DataBase->Query("UPDATE `servers` SET `serverOption` = :serverOption WHERE `id` = :serverID;");
		$DataBase->Bind(':serverID', $serverID);
		$DataBase->Bind(':serverOption', $serverOption);

		return $DataBase->Execute();
	}

	/* Block FTP on Server */
	public function blockFTPServerOption($serverID, $ftpBlock) {
		global $DataBase;

		$DataBase->Query("UPDATE `servers` SET `ftpBlock` = :ftpBlock WHERE `id` = :serverID;");
		$DataBase->Bind(':serverID', $serverID);
		$DataBase->Bind(':ftpBlock', $ftpBlock);

		return $DataBase->Execute();
	}

	/* Create new FDL server */
	public function createFDLServer($boxID, $serverUsername, $serverPassword, $userID, $isFree, $serverIstice) {
		global $Server, $Box, $Admin;
		// Box: Host : (IP)
		// Get Box  :: Host IP
		$boxHost 	= $Box->boxByID($boxID)['Host'];
		// Get Box  :: SSH2 port
		$sshPort 	= $Box->boxByID($boxID)['sshPort'];
		// Get Box :: Username
		$boxUser 	= $Box->boxByID($boxID)['Username'];
		// Get Box :: Password
		$boxPass 	= $Box->boxByID($boxID)['Password'];
		// PHP seclib
		set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
		include('Net/SSH2.php');
		// Connect
		$SSH2 = new Net_SSH2($boxHost, $sshPort);
		if (!$SSH2->login($boxUser, $boxPass)) {
			die('Login Failed');
		}
		// Create FTP user
		$SSH2->exec('useradd -m -s /bin/bash '.$serverUsername);
		$SSH2->setTimeout(1);
		// Change FTP default dir;
		$SSH2->exec('usermod -m -d /var/www/html/fdl/user/'.$serverUsername.' '.$serverUsername);
		$SSH2->setTimeout(1);
		// Change pw
		$SSH2->exec('echo -e "'.$serverPassword.'\n'.$serverPassword.'" | passwd '.$serverUsername);
		$SSH2->setTimeout(1);
		// Chown
		$SSH2->exec('chown -R '.$serverUsername.' /var/www/html/fdl/user/'.$serverUsername);
		$SSH2->setTimeout(1);

		// Save Server to DataBase;
		if (!($Server->saveFDLserver($boxID, $serverUsername, $serverPassword, $userID, $isFree, $expiresFor, $Admin->AdminData()['id'])) == false) {
			return true; 
		} else {
			return false;
		}
	}
	/* Save FDL Server */
	public function saveFDLserver($boxID, $serverUsername, $serverPassword, $userID, $isFree, $expiresFor, $createdBy) {
		global $DataBase;

		$DataBase->Query("INSERT INTO `fdl_servers` (`id`, `boxID`, `Username`, `Password`, `userID`, `isFree`, `expiresFor`, `createdBy`, `createdDate`, `lastactivity`) VALUES (NULL, :boxID, :Username, :Password, :userID, :isFree, :expiresFor, :createdBy, :createdDate, :lastactivity);");
		$DataBase->Bind(':boxID', $boxID);
		$DataBase->Bind(':Username', $serverUsername);
		$DataBase->Bind(':Password', $serverPassword);
		$DataBase->Bind(':userID', $userID);
		$DataBase->Bind(':isFree', $isFree);
		$DataBase->Bind(':expiresFor', $expiresFor);
		$DataBase->Bind(':createdBy', $createdBy);
		$DataBase->Bind(':createdDate', date('d/m/Y, H:ia'));
		$DataBase->Bind(':lastactivity', time());
		
		return $DataBase->Execute();
	}
	/* Get FDL Info */
	public function FDLinfo($serverID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `fdl_servers` WHERE `id` = :serverID ORDER by `id` DESC LIMIT 1;");
		$DataBase->Bind(':serverID', $serverID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Update Graph12h */
	public function upGraph12($serverID, $graphData) {
		global $DataBase;

		$DataBase->Query("UPDATE `servers` SET `Graph12` = :graphData WHERE `id` = :serverID;");
		$DataBase->Bind(':graphData', $graphData);
		$DataBase->Bind(':serverID', $serverID);

		return $DataBase->Execute();
	}

	/* Update Server Cron */
	public function upServerCron($serverID, $Time) {
		global $DataBase;

		$DataBase->Query("UPDATE `servers` SET `serverCron` = :Time WHERE `id` = :serverID;");
		$DataBase->Bind(':Time', $Time);
		$DataBase->Bind(':serverID', $serverID);

		return $DataBase->Execute();
	}
}