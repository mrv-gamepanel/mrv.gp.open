<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  site.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - info@mskoko.me
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Site {

	public function SiteConfig($id=1) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `site_settings` WHERE `id`=:ID");
		$DataBase->Bind(':ID', $id);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Create folder */
	public function createFolder($Path) {
		global $Site;

	    if (is_dir($Path)) return true;
	    $prev_path = substr($Path, 0, strrpos($Path, '/', -2) + 1);
	    $return = $Site->createFolder($prev_path);
	    return ($return && is_writable($prev_path)) ? mkdir($Path) : false;
	}

	// return true if already folder | Ako folder postoji vrati true;
	public function isFolder($fName) {
		if (is_dir($fName)) {
			return true;
		} else {
			return false;
		}
	}

	/* IS file */
	public function fileIs($isFile) {
		if (file_exists($isFile)) {
			return true;
		} else {
			return false;
		}
	}

	/* Create file */
	public function createFile($Path, $fileText) {
		$newFile = fopen($Path, 'w+') or die('Unable to open file!');
		fwrite($newFile, $fileText);
		fclose($newFile);
	}

	/* Count Users */
	public function UsersCount() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `users`");
		$DataBase->Execute();

		$Return = Array(
			'Count' 	=> $DataBase->RowCount()
		);
		return $Return;
	}

	/* Count Servers */
	public function ServersCount() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `servers`");
		$DataBase->Execute();

		$Return = Array(
			'Count' 	=> $DataBase->RowCount()
		);
		return $Return;
	}

	/* Count Boxs */
	public function BoxesCount() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `box_list`");
		$DataBase->Execute();

		$Return = Array(
			'Count' 	=> $DataBase->RowCount()
		);
		return $Return;
	}

	/* Count Tickets */
	public function TicketsCount() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `tickets`");
		$DataBase->Execute();

		$Return = Array(
			'Count' 	=> $DataBase->RowCount()
		);
		return $Return;
	}

}