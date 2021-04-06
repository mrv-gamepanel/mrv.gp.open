<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  plugins.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Plugins {

	/* Get all plugins */
	public function pluginList($gameID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `plugins` WHERE `gameID` = :gameID AND `Status` = :Status");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Bind(':Status', '1');
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get Plugin by ID */
	public function getPluginByID($pluginID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `plugins` WHERE `id` = :pluginID");
		$DataBase->Bind(':pluginID', $pluginID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Install Plugin on server */
	public function installPlugin($serverID, $pluginID) {
		global $Games, $Server, $Box, $Secure, $Plugins;

		// Get Sm Game name;
   		$smGameName 	= $Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'];
   		// Only for cs 1.6
   		if ($smGameName == 'cs16') {
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
			// File Name
		    $pluginFile 	= $Secure->SecureTxt(basename($Plugins->getPluginByID($pluginID)['pluginDir']));
		    // Plugin Path
		    $Path 			= urldecode('cstrike/addons/amxmodx/plugins/'.$pluginFile);
		    // Connect
			$SSH2 = new Net_SSH2($boxHost, $sshPort);
			if (!$SSH2->login($boxUser, $boxPass)) {
				die('Login Failed');
			}
			// Copy plugin in server;
			$SSH2->exec('cp -avr /home/plugins/cs16/bad_camper/bad_camper.amxx /home/'.$serverUsername.'/'.$Path);
			$SSH2->setTimeout(1);
			// Chown user;
			$SSH2->exec('chown -R '.$serverUsername.' /home/'.$serverUsername);
			$SSH2->setTimeout(1);

			$return = true;
		} else {
			$return = false;
		}
		// Return
		return $return;
	}


}