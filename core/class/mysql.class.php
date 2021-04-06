<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  mysql.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class sMySQL {

	// Create new MySQL user
	public function newMySQLuser($boxID, $serverID, $userID, $mysqlUser, $mysqlPass) {
		global $DataBase;

		$DataBase->Query("INSERT INTO `mysql_servers` (`id`, `boxID`, `serverID`, `userID`, `mysqlUser`, `mysqlPass`, `createdDate`, `lastactivity`) VALUES (NULL, :boxID, :serverID, :userID, :mysqlUser, :mysqlPass, :createdDate, :lastactivity);");
		$DataBase->Bind(':boxID', $boxID);
		$DataBase->Bind(':serverID', $serverID);
		$DataBase->Bind(':userID', $userID);
		$DataBase->Bind(':mysqlUser', $mysqlUser);
		$DataBase->Bind(':mysqlPass', $mysqlPass);
		$DataBase->Bind(':createdDate', date('d/m/Y, H:ia'));
		$DataBase->Bind(':lastactivity', time());

		return $DataBase->Execute();
	}
	// Get my MySQL servers | By : Server & User ID
	public function myMySQLservers($serverID, $userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `mysql_servers` WHERE `serverID` = :serverID AND `userID` = :userID");
		$DataBase->Bind(':serverID', $serverID);
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}
	// Remove MySQL server
	public function rmMySQLserver($mID, $serverID, $userID) {
		global $DataBase;

		// $DataBase->Query("");

	}


}