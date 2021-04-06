<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  user.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Admin {

	/* Login */
	public function LogIn($Email, $Password, $AutoLogin=false, $ZapamtiME) {
		global $DataBase, $Alert;

		$DataBase->Query("SELECT id, Email, Password, Token, Status FROM `admins` WHERE `Email` = :Email");
		$DataBase->Bind(':Email', $Email);
		$DataBase->Execute();

		$UserData 	= $DataBase->Single();

		if ($UserData['Status'] !== '1') {
			$Alert->SaveAlert('Your account has been deactivated.', 'error');
			header('Location: /admin/login?login');
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
			$_SESSION['AdminLogin']['ID'] = $UserData['id'];

			if(isset($_COOKIE['accept_cookie']) && $_COOKIE['accept_cookie'] == '1') {
			    if ($ZapamtiME == '1') {
			    	// Get Current date, time
					$current_time = time();

					// Set Cookie expiration for 1 month
					$cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month

			    	setcookie('member_login', '1', $cookie_expiration_time);
			    	//Set Secure Cookies -> HttpOnly
			    	setcookie('bl_l0g1n', $UserData['Token'].'_'.$UserData['id'], $cookie_expiration_time, '/', null, null, TRUE);
			    }
			}

			$Alert->SaveAlert('Welcome back!', 'success');
			header('Location: /admin/home');
			die();
		} else {
			$Alert->SaveAlert('You have entered incorrect information. Please try again!', 'error');
			header('Location: /admin/login');
			die();
		}
	}

	/* Is Admin Logged In */
	public function IsLoged() {
		global $Admin;

		if(isset($_SESSION['AdminLogin'])) {
			$return = true;
		} else {
			if (isset($_COOKIE['bl_l0g1n'])) {
				$GetUid = explode('_', $_COOKIE['bl_l0g1n']);
				if (!empty($GetUid[1])) {
					if (!empty($Admin->UserDataID($GetUid[1])['id'])) {
						if ($Admin->UserDataID($GetUid[1])['Token'] == $GetUid[0]) {
							$return = $Admin->ProduziLogin($GetUid[1]);
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
		global $Admin;

		if (!empty($uID) && is_numeric($uID)) {
			if (!empty($Admin->AdminDataID($uID)['id'])) {
				$_SESSION['AdminLogin']['ID'] = $uID;
				$return = true;
			} else {
				$return = false;
			}
		}
		return $return;
	}

	/* Get Admin Information by SESSION */
	public function AdminData() {
		global $DataBase;

		if(isset($_SESSION['AdminLogin'])) {
			$DataBase->Query("SELECT * FROM `admins` WHERE `id` = :userID");
			$DataBase->Bind(':userID', $_SESSION['AdminLogin']['ID']);
			$DataBase->Execute();

			return $DataBase->Single();
		} else {
			return false;
		}
	}

	/* Get Admin Information by ID */
	public function AdminDataID($adminID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `admins` WHERE `id` = :adminID;");
		$DataBase->Bind(':adminID', $adminID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Get Admin Full name by adminID */
	public function getFullName($adminID) {
		global $Admin, $Secure;

		return $Secure->SecureTxt($Admin->AdminDataID($adminID)['Name'].' '.$Admin->AdminDataID($adminID)['Lastname']);
	}

	/* Admins list */
	public function adminList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `admins`");
		$DataBase->Execute();

		$nArr = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount()
		);
		return $nArr;
	}

	/* Admin Rank */
	public function adminRank($Rank) {
		if (isset($Rank) && empty($Rank)) {
			$nArr = ['white', 'Rank?'];
		} else if($Rank == '1') {
			$nArr = ['#00ffff', 'Support'];
		} else if($Rank == '2') {
			$nArr = ['#0091ea', 'Glavni Support'];
		} else if($Rank == '3') {
			$nArr = ['red', 'Administrator'];
		} else if($Rank == '4') {
			$nArr = ['#8affa9', 'Developer'];
		} else if($Rank == '5') {
			$nArr = ['#00ff43', 'Owner'];
		}
		return $nArr;
	}

	/* Add new Worker */
	public function addNewAdmin($Username, $Password, $Email, $Name, $LastName, $Rank, $admPerm, $suppZa, $AdminID) {
		global $DataBase;

		$DataBase->Query("INSERT INTO `admins` (`id`, `Username`, `Password`, `Email`, `Name`, `Lastname`, `Image`, `Token`, `Rank`, `Status`, `AdminPerm`, `AdminSupp`, `createdBy`, `lastactivity`) VALUES (NULL, :Username, :Password, :Email, :Name, :Lastname, :Image, :Token, :Rank, :Status, :AdminPerm, :AdminSupp, :createdBy, :lastactivity);");
		$DataBase->Bind(':Username', $Username);
		$DataBase->Bind(':Password', $Password);
		$DataBase->Bind(':Email', $Email);
		$DataBase->Bind(':Name', $Name);
		$DataBase->Bind(':Lastname', $LastName);
		$DataBase->Bind(':Image', 'assets/img/i/logo/logo.png');
		$DataBase->Bind(':Token', '1312');
		$DataBase->Bind(':Rank', $Rank);
		$DataBase->Bind(':Status', '1');
		$DataBase->Bind(':AdminPerm', $admPerm);
		$DataBase->Bind(':AdminSupp', $suppZa);
		$DataBase->Bind(':createdBy', $AdminID);
		$DataBase->Bind(':lastactivity', time());
		
		return $DataBase->Execute();
	}

	/* Admin Perm is valid */
	public function AdminPermValid($adminID, $suppZa) {
		global $Admin;
		// fixed
		$return = false;
		// Get admin permision
		$admPerm = $Admin->AdminDataID($adminID)['AdminPerm'];
		if(empty($admPerm)) {
			$return = false;
		} else {
			$admPerm = @unserialize($admPerm);
			if(isset($admPerm) && empty($admPerm[0])) {
				$return = false;
			} else {
				if (in_array($suppZa, $admPerm)) {
					$return = true;
				} else {
					$return = false;
				}
			}
		}
		return $return;
	}

	/* Support Perm is valid */
	public function suppPermValid($adminID, $gameID) {
		global $Admin;
		// fixed
		$return = false;
		// Get admin permision
		$suppPerm = $Admin->AdminDataID($adminID)['AdminSupp'];
		if(empty($suppPerm)) {
			$return = false;
		} else {
			$suppPerm = @unserialize($suppPerm);
			if(isset($suppPerm) && empty($suppPerm[0])) {
				$return = false;
			} else {
				if (in_array($gameID, $suppPerm)) {
					$return = true;
				} else {
					$return = false;
				}
			}
		}
		return $return;
	}

}