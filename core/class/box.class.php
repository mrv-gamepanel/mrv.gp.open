<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  box.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Box {

	/* Get Box information by ID */
	public function boxByID($boxID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `box_list` WHERE `id` = :boxID");
		$DataBase->Bind(':boxID', $boxID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Get Random Box by Location */
	public function getRandomBoxByLocation($boxLocation) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `box_list` WHERE `boxLocation` = :boxLocation AND `id` NOT IN (3) ORDER BY RAND() LIMIT 1");
		$DataBase->Bind(':boxLocation', $boxLocation);
		$DataBase->Execute();

		return $DataBase->Single();
	}

}