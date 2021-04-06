<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  user.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class User {

	/* Login */
	public function LogIn($Email, $Password, $AutoLogin=false, $ZapamtiME, $isReseller=false) {
		global $DataBase, $Alert;

		if($isReseller == false) {
			$DataBase->Query("SELECT id, Email, Password, Token, Status FROM `users` WHERE `Email` = :Email ORDER by `id` DESC LIMIT 1");
		} else {
			$DataBase->Query("SELECT id, Email, Password, Token, Status FROM `resellers_clients` WHERE `Email` = :Email ORDER by `id` DESC LIMIT 1");
		}
		$DataBase->Bind(':Email', $Email);
		$DataBase->Execute();
		$UserData = $DataBase->Single();

		if ($UserData['Status'] !== '1') {
			$Alert->SaveAlert('Your account has been deactivated.', 'error');
			header('Location: '.(($isReseller) ? '/reseller/login.php' : '/login'));
			die();
		}

		$UserCount 	= $DataBase->RowCount();

		// If for Autologin
		if ($AutoLogin == false) {
			$Provera = md5($Password) == $UserData['Password'];
		} else {
			$Provera = !empty($Password);
		}

		if($UserCount != 0 && $Provera) {
			$_SESSION['UserLogin']['ID'] 	= $UserData['id']; // User ID
			$_SESSION['UserLogin']['pC'] 	= ''; 	// Pin Code

			// if(isset($_COOKIE['accept_cookie']) && $_COOKIE['accept_cookie'] == '1') {
			    if ($ZapamtiME == '1') {
			    	// Get Current date, time
					$current_time = time();

					// Set Cookie expiration for 1 month
					$cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month

			    	setcookie('member_login', '1', $cookie_expiration_time);
			    	//Set Secure Cookies -> HttpOnly
			    	setcookie('l0g1n', $UserData['Token'].'_'.$UserData['id'].'_'.$isReseller, $cookie_expiration_time, '/', null, null, TRUE);
			    }
			// }

			$Alert->SaveAlert('Welcome back!', 'success');
			header('Location: ' . (($isReseller) ? '/reseller/index.php' : '/gp-home'));
			die();
		} else {
			$Alert->SaveAlert('You have entered incorrect information. Please try again!', 'error');
			header('Location: ' . (($isReseller) ? '/reseller/login.php' : '/login'));
			die();
		}
	}

	/* Is User Logged In */
	public function IsLoged() {
		global $User;

		if(isset($_SESSION['UserLogin']['ID']) && !empty($User->UserDataID($_SESSION['UserLogin']['ID'])['id'])) {
			$return = true;
		} else {
			if (isset($_COOKIE['l0g1n'])) {
				$GetUid = explode('_', $_COOKIE['l0g1n']);
				if (!empty($GetUid[1])) {
					if (!empty($User->UserDataID($GetUid[1])['id'])) {
						if ($User->UserDataID($GetUid[1])['UserTokenKey'] == $GetUid[0]) {
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
		}
		return $return;
	}

	/* Is valid Pin Code */
	public function isPinCode() {
		if(isset($_SESSION['UserLogin']['pC']) && !empty($_SESSION['UserLogin']['pC'])) {
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
	/* Check Pin Code */
	public function checkPinCode($pinCode, $userID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `users` WHERE `pC` = :pinCode AND `id` = :userID LIMIT 1");
		$DataBase->Bind(':pinCode', $pinCode);
		$DataBase->Bind(':userID', $userID);
		$DataBase->Execute();

		return $DataBase->Single();
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

	/* Get User Information by Email */
	public function UserDataIDemail($Email) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `users` WHERE `Email` = :Email");
		$DataBase->Bind(':Email', $Email);
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

	/* Add new User (Register) */
	public function Register($Username, $Password, $Email, $Name, $lName, $Image, $pC, $Token, $Status) {
		global $DataBase;
		
		$DataBase->Query("INSERT INTO `users` (`id`, `Username`, `Password`, `Email`, `Name`, `Lastname`, `Image`, `pC`, `Token`, `Status`) VALUES (NULL, :Username, :Password, :Email, :Name, :lName, :Image, :pC, :Token, :Status);");
		$DataBase->Bind(':Username', $Username);
		$DataBase->Bind(':Password', $Password);
		$DataBase->Bind(':Email', $Email);
		$DataBase->Bind(':Name', $Name);
		$DataBase->Bind(':lName', $lName);
		$DataBase->Bind(':Image', $Image);
		$DataBase->Bind(':pC', $pC); // PinCode
		$DataBase->Bind(':Token', $Token);
		$DataBase->Bind(':Status', $Status);

		return $DataBase->Execute();
	}

	/* Save user profile */
	public function editProfile($fName, $lName, $Token, $userID) {
		global $DataBase;

		$DataBase->Query("UPDATE `users` SET `Name` = :fName, `Lastname` = :lName WHERE `id` = :userID;");
		$DataBase->Bind(':fName', $fName);
		$DataBase->Bind(':lName', $lName);
		// $DataBase->Bind(':Token', $Token);
		$DataBase->Bind(':userID', $userID);

		return $DataBase->Execute();
	}

	/* Edit password */
	public function editPassword($Password, $userID) {
		global $DataBase;

		$DataBase->Query("UPDATE `users` SET `Password` = :Password WHERE `id` = :userID;");
		$DataBase->Bind(':Password', $Password);
		$DataBase->Bind(':userID', $userID);

		return $DataBase->Execute();
	}

	/* MRV Cash - User Money */
	public function getUserCash($userID, $t) {
		global $User, $Secure;

		if ($t == 'full') {
			$Cash = $Secure->pNormalMoney($User->UserDataID($userID)['mrvCash'], 'EUR');
		} else if($t = 'db') {
			$Cash = $Secure->pNormalMoney($User->UserDataID($userID)['mrvCash'], 'EUR', 'noc');
		} else {
			$Cash = '';
		}
		return $Cash;
	}
	// Update User Cash
	public function updateUserCash($Cash, $userID) {
		global $DataBase;

		$DataBase->Query("UPDATE `users` SET `mrvCash` = :Cash WHERE `id` = :userID");
		$DataBase->Bind(':Cash', $Cash);
		$DataBase->Bind(':userID', $userID);

		return $DataBase->Execute();
	}


}