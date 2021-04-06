<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  billing.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Billing {

	/* Get all billings */
	public function BillingList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `orders` ORDER by `id` DESC");
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}


	/* Get all billings by userID */
	public function BillingsByUser($userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `orders` WHERE `userID` = :userID");
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

}