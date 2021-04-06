<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  plugins.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Plugins {

	/* Get all plugins */
	public function pluginsList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `plugins` WHERE `Status` = :Status");
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

	/* Get Plugin by Game ID */
	public function getPluginByGameID($gameID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `plugins` WHERE `gameID` = :gameID");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}


}