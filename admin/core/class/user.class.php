<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  user.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class User {

	/* Login */
	public function LogIn($Email, $Password, $AutoLogin=false, $ZapamtiME) {
		global $DataBase, $Alert;

		$DataBase->Query("SELECT id, Email, Password, Token, Status FROM `users` WHERE `Email` = :Email");
		$DataBase->Bind(':Email', $Email);
		$DataBase->Execute();

		$UserData 	= $DataBase->Single();

		if ($UserData['Status'] !== '1') {
			$Alert->SaveAlert('Your account has been deactivated.', 'error');
			header('Location: /login?login');
			die();
		}

		$UserCount 	= $DataBase->RowCount();

		// If for Autologin
		if ($AutoLogin == false) {
			$Provera = md5($Password) == $UserData['Password'];
		} else {
			$Provera = !empty($Password);
		}

		if($UserCount == true && $Provera) {
			$_SESSION['UserLogin']['ID'] = $UserData['id'];

			if(isset($_COOKIE['accept_cookie']) && $_COOKIE['accept_cookie'] == '1') {
			    if ($ZapamtiME == '1') {
			    	// Get Current date, time
					$current_time = time();

					// Set Cookie expiration for 1 month
					$cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month

			    	setcookie('member_login', '1', $cookie_expiration_time);
			    	//Set Secure Cookies -> HttpOnly
			    	setcookie('l0g1n', $UserData['Token'].'_'.$UserData['id'], $cookie_expiration_time, '/', null, null, TRUE);
			    }
			}

			$Alert->SaveAlert('Welcome back!', 'success');
			header('Location: /home');
			die();
		} else {
			$Alert->SaveAlert('You have entered incorrect information. Please try again!', 'error');
			header('Location: /login');
			die();
		}
	}

	/* Is User Logged In */
	public function IsLoged() {
		global $User;

		if(isset($_SESSION['UserLogin'])) {
			$return = true;
		} else {
			if (isset($_COOKIE['l0g1n'])) {
				$GetUid = explode('_', $_COOKIE['l0g1n']);
				if (!empty($GetUid[1])) {
					if (!empty($User->UserDataID($GetUid[1])['id'])) {
						if ($User->UserDataID($GetUid[1])['Token'] == $GetUid[0]) {
							$return = $User->ProduziLogin($GetUid[1]);
						} else {
							$return = false;
						}
					}
				} else {
					$return = false;
				}
			} else {
				$return = false;
			}

			$return = false;
		}

		return $return;
	}

	/* new session (produzi login) */
	public function ProduziLogin($uID) {
		global $User;

		if (!empty($uID) && is_numeric($uID)) {
			if (!empty($User->UserDataID($uID)['id'])) {
				$_SESSION['UserLogin']['ID'] = $uID;
				$return = true;
			} else {
				$return = false;
			}
		}
		return $return;
	}

	/* Get User Information by SESSION */
	public function UserData() {
		global $DataBase;

		if(isset($_SESSION['UserLogin'])) {
			$DataBase->Query("SELECT * FROM `users` WHERE `id` = :userID");
			$DataBase->Bind(':userID', $_SESSION['UserLogin']['ID']);
			$DataBase->Execute();

			return $DataBase->Single();
		} else {
			return false;
		}
	}

	/* Get User Information by ID */
	public function UserDataID($userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `users` WHERE `id` = :userID");
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Get User Full name by UserID */
	public function getFullName($userID) {
		global $User, $Secure;

		return $Secure->SecureTxt($User->UserDataID($userID)['Name'].' '.$User->UserDataID($userID)['Lastname']);
	}

	/* Get All Users */
	public function userList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `users` ORDER by `Name` ASC");
		$DataBase->Execute();

		$Return = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $Return;
	}

	/* Get User by ID */
	public function CountUserByID($userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `users` WHERE `id` = :ID");
		$DataBase->Bind(':ID', $userID);
		$DataBase->Execute();

		$nArr = Array(
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Get User First and Lastname */
	public function UserFL($userID) {
		global $User, $Secure;
		// Uzima ime i prezime
		$FirstName 	= $User->UserDataID($userID)['Name'];
		$LastName 	= $User->UserDataID($userID)['Lastname'];
		// Napravi Ime Prezime
		$IP = $Secure->SecureTxt($FirstName).' '.$Secure->SecureTxt($LastName);
		// Return IP
		return $IP;
	}

}