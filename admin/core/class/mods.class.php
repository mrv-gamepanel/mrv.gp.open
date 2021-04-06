<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  mods.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Mods {

	/* Get all mods */
	public function modsList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `mods` WHERE `Status` = :Status");
		$DataBase->Bind(':Status', '1');
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get all mods */
	public function modsListbyID($gameID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `mods` WHERE `gameID` = :gameID AND `Status` = :Status");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Bind(':Status', '1');
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get Mod by ID */
	public function getModByID($modID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `mods` WHERE `id` = :modID");
		$DataBase->Bind(':modID', $modID);
		$DataBase->Execute();

		return $DataBase->Single();
	}


}