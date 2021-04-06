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

		$DataBase->Query("SELECT * FROM `servers` WHERE `userID` = :userID");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	// Get Random server by Game
	public function getRandomServer($gameID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers` WHERE `gameID` = :gameID ORDER BY RAND() LIMIT 1");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

}