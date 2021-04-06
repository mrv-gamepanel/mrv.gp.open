<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  servers.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Servers {

	/* Get all servers by userID */
	public function serversByUser($userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `userID` = :userID ORDER BY `id` DESC");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get all servers by userID & GameID */
	public function serversByUserAndGame($userID, $gameID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `userID` = :userID AND `gameID` = :gameID ORDER BY `id` DESC");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get all servers */
	public function ServerList() {
		global $DataBase;

		// Online status
		$DataBase->Query("SELECT * FROM `servers`");
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get all servers by GameID */
	public function ServerListByGame($gameID) {
		global $DataBase;

		// Online status
		$DataBase->Query("SELECT * FROM `servers` WHERE `gameID` = :gameID");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}
	
	/* Get all servers */
	public function ServerListOnlineStatus($OnlineStatus, $gameID) {
		global $DataBase;

		// Online status
		$DataBase->Query("SELECT * FROM `servers` WHERE `Online` = :OnlineStatus AND `gameID` = :gameID");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Bind(':OnlineStatus', $OnlineStatus);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get all servers */
	public function ServerListPaidStatus($paidStatus, $gameID) {
		global $DataBase;

		// Paid status
		$DataBase->Query("SELECT * FROM `servers` WHERE `isFree` = :paidStatus AND `gameID` = :gameID");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Bind(':paidStatus', $paidStatus);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Fast Download */
	public function ServerListFDL() {
		global $DataBase;

		// Online status
		$DataBase->Query("SELECT * FROM `fdl_servers`");
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}


}