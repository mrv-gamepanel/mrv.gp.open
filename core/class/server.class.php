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

	/* Is my server */
	public function isMyServer($serverID) {
		global $Server, $User;

		if (empty($serverID) || !is_numeric($serverID)) {
			die('Nismo usepli pronaci ovaj server!');
		}

		// Is my server?
	    if ($Server->serverByID($serverID)['userID'] == $User->UserData()['id']) {
			return true;
	    } else {
	    	return false;
	    }
	}

	/* DAj zanji server */
	public function myLastSrvID($userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `userID` = :userID ORDER by `id` DESC LIMIT 1");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Ping UserName */
	public function findServerUname($UserName) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `Username` = :Username");
		$DataBase->Bind(':Username', $UserName);
		$DataBase->Execute();

		return $DataBase->Single();
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

	/* Server Status */
	public function serverStatus($Status) {
		if ($Status == 1) {
	        $Status = '<span class="badge badge-success"> Active </span>';
	    } else if($Status == 2) {
	        $Status = '<span class="badge badge-warning"> Suspend </span>';
	    } else if($Status == 3) {
	        $Status = '<span class="badge badge-danger"> Deactived </span>';
	    }
		return $Status;
	}

	/* Server Online Status */
	public function serverOnlineStatus($Status) {
		if ($Status == 1) {
	        $Status = '<span class="badge badge-success"> Online </span>';
	    } else {
	        $Status = '<span class="badge badge-danger"> Offline </span>';
	    }
		return $Status;
	}

	/* Server name */
	public function serverName($serverID) {
		global $Server, $Secure;

		return $Secure->SecureTxt($Server->serverByID($serverID)['Name']);
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
					$SSH2->exec("su -lc 'rm hlds_linux' ".$serverUsername); // Remove hlds_linux;
					// Get mod directory (gamefiles)
					$getMyMod = $Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Add orginal "./hlds_run" file;
					$SSH2->exec("su -lc 'cp -Rf ".$getMyMod."/hlds_run /home/".$serverUsername."' ".$serverUsername); // Add orginal hlds_run file;
					$SSH2->exec("su -lc 'cp -Rf ".$getMyMod."/hlds_linux /home/".$serverUsername."' ".$serverUsername); // Add orginal hlds_linux file;
					$SSH2->setTimeout(1);
					// Chown user;
					$SSH2->exec('chown -R '.$serverUsername.' /home/'.$serverUsername);
					$SSH2->setTimeout(1);
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
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0755 /home/".$serverUsername."/*");
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
					// Get mod directory (gamefiles)
					$getMyMod = $Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Remove "./hlds_run and cp orginal"
					$SSH2->exec("su -lc 'rm samp03svr' ".$serverUsername); // Remove hlds_run;
					// Add orginal "./hlds_run" file;
					$SSH2->exec("su -lc 'cp -Rf ".$getMyMod."/samp03svr /home/".$serverUsername."' ".$serverUsername); // Add orginal hlds_run file;
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
					$getMyMod = @$Mods->getModByID($Server->serverByID($serverID)['modID'])['modDir'];
					// Re-install server
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0755 /home/".$serverUsername."/*");
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
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0755 /home/".$serverUsername."/*");
					$SSH2->setTimeout(3);

					// nice -n 19 rm -Rf /home/srv_1_3/* && cp -Rf /home/gamefiles/cs/Public/* /home/srv_1_3 && chown -Rf srv_1_3 /home/srv_1_3 && chmod 700 /home/srv_1_3 && exit

					$return = true;
				}
			} else if ($Game == 'csgo') {
				// CS:GO WIKI https://developer.valvesoftware.com/wiki/Counter-Strike:_Global_Offensive_Dedicated_Servers
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
					$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername."; chown -Rf ".$serverUsername." /home/".$serverUsername."; chmod 0755 /home/".$serverUsername."/*");
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
			if (empty($g[1])) {
				$g = explode(' ', $text);
			}
			return $g[1];
		}
	}
	/* Get by parametar | Game :: CS 1.6 */
	public function getParamByFile($serverID, $Path, $File, $byParm='') {
		global $Secure, $webFTP;
		// Get server file content
		$loadServerFile = $webFTP->getFileContent($serverID, $Path, $File);

		$pattern = preg_quote($byParm, '/');
		$pattern = "/^.*$pattern.*\$/m";

		if(preg_match_all($pattern, $loadServerFile, $matches)) {
			$text 	= implode("\n", $matches[0]);
			$t 		= explode("\n", $text);
			if ($t[0] == $byParm) {
				$r 	= true;
			} else {
				$r 	= false;
			}
		} else {
			$r 		= false;
		}
		return $r;
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
				$SSH2->exec("nice -n 19 rm -Rf /home/".$serverUsername."/* && cp -Rf ".$getMyMod."/* /home/".$serverUsername." && chown -Rf ".$serverUsername." /home/".$serverUsername." && chmod 700 /home/".$serverUsername." * && exit");
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

	/* Update map */
	public function updateMap($mapName, $serverID) {
		global $DataBase;

		$DataBase->Query("UPDATE `servers` SET `Map` = :mapName WHERE `id` = :serverID;");
		$DataBase->Bind(':mapName', $mapName);
		$DataBase->Bind(':serverID', $serverID);

		return $DataBase->Execute();
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

	/* Get Normal usage */
	public function getUsage($Usage='') {
		$rUsage=0;
		if(isset($Usage) || !empty($Usage[0])) {
			foreach ($Usage as $v) {
			    $rUsage += (int) $v;
			}
		} else {
			$rUsage = 'n/a';
		}
		return $rUsage;
	}

}