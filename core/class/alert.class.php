<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  alert.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Alert {

	public function SaveAlert($msg_txt, $msg_mode) {
		if ($msg_mode == 'success') {
			$_SESSION['msg_success'] = $msg_txt;
		} else if ($msg_mode == 'error') {
			$_SESSION['msg_error'] = $msg_txt;
		} else if ($msg_mode == 'info') {
			$_SESSION['msg_info'] = $msg_txt;
		} else if ($msg_mode == 'warning') {
			$_SESSION['msg_warning'] = $msg_txt;
		} else {
			echo "Error.";
		}
		
	}

	public function PrintAlert() {
		//empty.
		$eAlert = '';

		if (isset($_SESSION['msg_success'])) {
			$eMSG = $_SESSION['msg_success'];

			$eAlert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>Success: </strong> '.$eMSG.'
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>';
		} else if (isset($_SESSION['msg_error'])) {
			$eMSG = $_SESSION['msg_error'];

			$eAlert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Error: </strong> '.$eMSG.'
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>';
		} else if (isset($_SESSION['msg_info'])) {
			$eMSG = $_SESSION['msg_info'];

			$eAlert = '<div class="alert alert-info alert-dismissible fade show" role="alert">
					<strong></strong> '.$eMSG.'
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>';
		} else if (isset($_SESSION['msg_warning'])) {
			$eMSG = $_SESSION['msg_warning'];

			$eAlert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<strong>Warning: </strong> '.$eMSG.'
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>';
		} else {
			$eMSG = "";
		}

		return $eAlert;
	}


	public function RemoveAlert() {
		if (isset($_SESSION['msg_success'])) {
			unset($_SESSION['msg_success']);
		} else if (isset($_SESSION['msg_error'])) {
			unset($_SESSION['msg_error']);
		} else if (isset($_SESSION['msg_info'])) {
			unset($_SESSION['msg_info']);
		} else if (isset($_SESSION['msg_warning'])) {
			unset($_SESSION['msg_warning']);
		} else {
			
		}
	}


	
	
}