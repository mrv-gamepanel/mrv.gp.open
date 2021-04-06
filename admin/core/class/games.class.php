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

		$DataBase->Query("SELECT * FROM `games` WHERE `id` = :gameID;");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Get Game information by smGame */
	public function gameBySmName($smGameName) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `games` WHERE `smName` = :smGameName;");
		$DataBase->Bind(':smGameName', $smGameName);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Game list */
	public function gameList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `games`");
		$DataBase->Execute();

		$rArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $rArr;
	}

}