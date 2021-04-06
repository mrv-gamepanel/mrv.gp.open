<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  reseller.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Reseller {

	/* My Reseller */
	public function myReseller($secretKey, $tokenKey) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `resellers` WHERE `secretKey` = :secretKey AND `tokenKey` = :tokenKey ORDER by `id` DESC LIMIT 1");
		$DataBase->Bind(':secretKey', $secretKey);
		$DataBase->Bind(':tokenKey', $tokenKey);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* My Clients */
	public function myClients($ID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `resellers_clients` WHERE `id` = :ID");
		$DataBase->Bind(':ID', $ID);
		$DataBase->Execute();

		$nArr = Array(
			'Count'		=> $DataBase->RowCount(),
			'Response'  => $DataBase->ResultSet()
		)
		return $nArr;
	}

	/* Client by ID */
	public function myClientByID($resellerID, $clientID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `resellers_clients` WHERE `id` = :clientID AND `resellerID` = :resellerID");
		$DataBase->Bind(':clientID', $clientID);
		$DataBase->Bind(':resellerID', $resellerID);
		$DataBase->Execute();

		$nArr = Array(
			'Count'		=> $DataBase->RowCount(),
			'Response'  => $DataBase->ResultSet()
		)
		return $nArr;
	}


}