<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  news.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class News {

	/* Get all News */
	public function getAllNews() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `news` ORDER by `id` DESC");
		$DataBase->Execute();

		return $DataBase->ResultSet();
	}

}