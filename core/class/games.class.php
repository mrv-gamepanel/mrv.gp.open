<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  games.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Games {

	/* Get Game information by GameID */
	public function gameByID($gameID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `games` WHERE `id` = :gameID");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Get all billings by userID */
	public function GamesList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `games`");
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	
	
}