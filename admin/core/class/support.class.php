<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  support.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Support {

	/* Ticket List */
	public function ticketsList() {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `tickets` ORDER by `id` DESC");
		$DataBase->Execute();

		$Return = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount(),
		);
		return $Return;
	}

	/* Ticket List by Status */
	public function ticketsListByStatus($Status) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `tickets` WHERE `Status` = :Status ORDER by `id` DESC");
		$DataBase->Bind(':Status', $Status);
		$DataBase->Execute();

		$Return = Array(
			'Response' 	=> $DataBase->ResultSet(),
			'Count' 	=> $DataBase->RowCount(),
		);
		return $Return;
	}

	/* Ticket By ID */
	public function ticketByID($tID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `tickets` WHERE `id` = :tID");
		$DataBase->Bind(':tID', $tID);
		$DataBase->Execute();

		return $DataBase->Single();
	}

	/* Ticket Status */
	public function ticketStatus($Status) {
		if (isset($Status) && empty($Status)) {
			$nArr = ['red', 'Status?'];
		} else if($Status == '1') {
			$nArr = ['green', 'Open'];
		} else if($Status == '2') {
			$nArr = ['#ffc107', 'Responsible'];
		} else if($Status == '3') {
			$nArr = ['red', 'Closed'];
		}
		return $nArr;
	}

	/* Ticket Priority */
	public function ticketPriority($Priority) {
		if (isset($Priority) && empty($Priority)) {
			$nArr = ['red', 'Priority?'];
		} else if($Priority == '1') {
			$nArr = ['green', 'Normal'];
		} else if($Priority == '2') {
			$nArr = ['#ffc107', 'Medium'];
		} else if($Priority == '3') {
			$nArr = ['red', 'Urgent'];
		}
		return $nArr;
	}

	/* Add Answer */
	public function answOnTicket($tID, $userID, $supportID, $Message) {
		global $DataBase;

		$DataBase->Query("INSERT INTO `ticket_answ` (`id`, `tID`, `userID`, `supportID`, `Message`, `Date`, `lastactivity`) VALUES (NULL, :tID, :userID, :supportID, :Message, :Date, :lastactivity);");
		$DataBase->Bind(':tID', $tID);
		$DataBase->Bind(':userID', $userID);
		$DataBase->Bind(':supportID', $supportID);
		$DataBase->Bind(':Message', $Message);
		$DataBase->Bind(':Date', date('d/m/Y, H:ia'));
		$DataBase->Bind(':lastactivity', time());

		return $DataBase->Execute();
	}

	/* Update Ticket */
	public function upStatusOnTicket($tID, $Status) {
		global $DataBase;
		
		$DataBase->Query("UPDATE `tickets` SET `Status` = :Status WHERE `id` = :tID;");
		$DataBase->Bind(':tID', $tID);
		$DataBase->Bind(':Status', $Status);

		return $DataBase->Execute();
	}

	/* Answer on Ticket list */
	public function answOnTicketList($tID) {
		global $DataBase;

		$DataBase->Query("SELECT * FROM `ticket_answ` WHERE `tID` = :tID ORDER by id ASC");
		$DataBase->Bind(':tID', $tID);
		$DataBase->Execute();

		return $DataBase->ResultSet();
	}


}