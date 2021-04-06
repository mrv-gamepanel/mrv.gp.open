<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/core/inc/config.php');

/**
* Create a new server
*/

function SMS_Create_Server($cl_email, $game_id, $slot) {
	if (empty($cl_email)||empty($game_id)||empty($slot)) {
		$return = false;
	}

	//** DOBIJANJE VREDNOSTI IZ FUNKCIJE(varijable)
	$Game_ID 		= $game_id;
	$Buy_Slot 		= $slot;

	//** DODELI DEFAULT VREDNOST
	$Buy_Name 		= 'SMS Server';
	$Buy_Location 	= 'lite1';
	$Buy_Mod 		= 1;
	$Buy_Mesec 		= 0;
	$Buy_Cena 		= 0;
	
	$Buy_Date 		= date('d.m.Y, H:i');

	if (valid_email($cl_email) == true) {
		$get_u = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `email` = '$cl_email'"));
		if (!$get_u) {
			$return = false;
		} else {
			$Get_User_ID = txt($get_u['klijentid']);

			$num_sms_srv 	= mysql_query("SELECT * FROM `billing` WHERE `user_id` = '$Get_User_ID'");
			if (!mysql_num_rows($num_sms_srv) == 0) {
				$return = false;
			} else {
				$return = true;
			}
		}
	} else if (valid_email($cl_email) == false) {
		$get_u = mysql_fetch_array(mysql_query("SELECT * FROM `klijenti` WHERE `username` = '$cl_email'"));
		if (!$get_u) {
			$return = false;
		} else {
			$Get_User_ID = txt($get_u['klijentid']);

			$num_sms_srv 	= mysql_query("SELECT * FROM `billing` WHERE `user_id` = '$Get_User_ID'");
			if (!mysql_num_rows($num_sms_srv) == 0) {
				$return = false;
			} else {
				$return = true;
			}
		}
	} else {
		$return = false;
	}

	if ($return == true) {
		$in_base = mysql_query("INSERT INTO `billing` (`id`, `user_id`, `game_id`, `mod_id`, `location`, `slotovi`, `mesec`, `name`, `cena`, `date`, `status`, `srv_z_install`, `srv_install`, `buy_sms`) VALUES (NULL, '$Get_User_ID', '$Game_ID', '$Buy_Mod', '$Buy_Location', '$Buy_Slot', '$Buy_Mesec', '$Buy_Name', '$Buy_Cena', '$Buy_Date', 'success', '1', '1', '1')");
		if (!$in_base) {
			$return = false;
		} else {
			$return = true;
		}
	} else {
		$return = false;
	}

	return $return;
}

?>