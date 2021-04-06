<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  box.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Box {

	/* Get Box information by ID */
	public function boxByID($boxID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `box_list` WHERE `id` = :boxID");
		$DataBase->Bind(':boxID', $boxID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Box List */
	public function boxList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `box_list`");
		$DataBase->Execute();

		$Return = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $Return;
	}

	/* Save Box */
	public function saveBox($Name, $Host, $Username, $Password, $sshPort, $ftpPort, $Note, $boxLocation, $gameID, $createdBy) {
		global $DataBase;

		$DataBase->Query("INSERT INTO `box_list` (`id`, `Name`, `Host`, `Username`, `Password`, `sshPort`, `ftpPort`, `Status`, `Online`, `isStart`, `Note`, `boxLocation`, `gameID`, `autoRestart`, `createdBy`, `createdDate`, `lastactivity`) VALUES (NULL, :Name, :Host, :Username, :Password, :sshPort, :ftpPort, :Status, :Online, :isStart, :Note, :boxLocation, :gameID, :autoRestart, :createdBy, :createdDate, :lastactivity);");
		$DataBase->Bind(':Name', $Name);
		$DataBase->Bind(':Host', $Host);
		$DataBase->Bind(':Username', $Username);
		$DataBase->Bind(':Password', $Password);
		$DataBase->Bind(':sshPort', $sshPort);
		$DataBase->Bind(':ftpPort', $ftpPort);
		$DataBase->Bind(':Status', '1');
		$DataBase->Bind(':Online', '1');
		$DataBase->Bind(':isStart', '1');
		$DataBase->Bind(':Note', $Note);
		$DataBase->Bind(':boxLocation', $boxLocation);
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Bind(':autoRestart', '');
		$DataBase->Bind(':createdBy', $createdBy);
		$DataBase->Bind(':createdDate', date('d/m/Y, H:ia'));
		$DataBase->Bind(':lastactivity', time());

		return $DataBase->Execute();
	}


}