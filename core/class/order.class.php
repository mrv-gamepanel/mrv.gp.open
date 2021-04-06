<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  order.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Order {

	// Update Status
	public function upOrderStatus($Status, $userID, $oID) {
		global $DataBase;

		$DataBase->Query("UPDATE `orders` SET `orderStatus` = :Status WHERE `id` = :oID AND `userID` = :userID;");
		$DataBase->Bind(':Status', $Status);
		$DataBase->Bind(':oID', $oID);
		$DataBase->Bind(':userID', $userID);

		return $DataBase->Execute();
	}

	
	
}