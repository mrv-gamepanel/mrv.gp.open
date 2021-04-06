<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  billing.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Billing {

	/* Save Order */
	public function saveOrder($gameID, $Location, $Slots, $Months, $modID, $serverName, $Price, $orderGB, $userID) {
		global $DataBase;

		$DataBase->Query("INSERT INTO `orders` (`id`, `gameID`, `userID`, `Location`, `Slots`, `Months`, `modID`, `serverName`, `Price`, `gb`, `orderDate`, `lastactivity`, `orderStatus`) VALUES (NULL, :gameID, :userID, :Location, :Slots, :Months, :modID, :serverName, :Price, :orderGB, :Date, :Time, :orderStatus);");
		$DataBase->Bind(':gameID', $gameID);
		$DataBase->Bind(':userID', $userID);
		$DataBase->Bind(':Location', $Location);
		$DataBase->Bind(':Slots', $Slots);
		$DataBase->Bind(':Months', $Months);
		$DataBase->Bind(':modID', $modID);
		$DataBase->Bind(':serverName', $serverName);
		$DataBase->Bind(':Price', $Price);
		$DataBase->Bind(':orderGB', $orderGB);
		$DataBase->Bind(':Date', date('d/m/Y, H:ia'));
		$DataBase->Bind(':Time', time());
		$DataBase->Bind(':orderStatus', '1');

		return $DataBase->Execute();
	}

	/* Get all billings by userID */
	public function BillingsByUser($userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `orders` WHERE `userID` = :userID ORDER by `id` DESC;");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get all billings by userID */
	public function onlyByNotPay($userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `orders` WHERE `userID` = :userID AND `orderStatus` != '2';");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get Order information by Order ID */
	public function orderByID($oID)	{
		global $DataBase;

		$DataBase->Query("SELECT * FROM `orders` WHERE `id` = :oID");
		$DataBase->Bind(':oID', $oID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Remove Order */
	public function removeOrder($oID, $userID) {
		global $DataBase;

		$DataBase->Query("DELETE FROM `orders` WHERE `id` = :oID AND `userID` = :userID;");
		$DataBase->Bind(':oID', $oID);
		$DataBase->Bind(':userID', $userID);

		return $DataBase->Execute();
	}

	/* Order Status */
	public function orderStatus($statusID) {
		if ($statusID == '1') {
			$r = '<span style="color:yellow;">Na cekanju</span>';
		} else if($statusID == '2') {
			$r = '<span style="color:#26ff26;">Uplaceno</span>';
		} else if($statusID == '0') {
			$r = '<span style="color:red;">Lazno</span>';
		} else {
			$r = '<span style="color:red;">Proverava se</span>';
		}
		return $r;
	}

	/* Update Status by Order ID */
	public function upStatusOrderID($oID, $userID ,$Status)	{
		global $DataBase;

		$DataBase->Query("UPDATE `orders` SET `orderStatus` = :Status WHERE `id` = :oID AND `userID` = :userID;");
		$DataBase->Bind(':oID', $oID);
		$DataBase->Bind(':userID', $userID);
		$DataBase->Bind(':Status', $Status);
		
		return $DataBase->Execute();
	}




}