<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  log.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Logs {

	/*
	 * Save server error log
	*/
	public function ServerErrorLog($logName, $FileLink) {
		

	}

	/*
	 * Save client error log
	*/
	public function ClientErrorLog($logName, $FileLink, $userID, $CompanyID) {
		

	}


	///////////////////////////////////////////
	
	/*
	 *
	*/
	public function PrintLogs($File) {
		if (file_exists($File)) {
			$fn = fopen($File, 'r');
		
			while(!feof($fn))  {
				$result = fgets($fn);
				if (!empty($result)) {
					echo $result.'<hr>';
				}
			}

			fclose($fn);
		} else {
			echo 'File is not exists!';
		}

	}

	public function LogsCountNum($File) {
		if (file_exists($File)) {
			$fn = fopen($File, 'r');
			
			$log=0;
			while(!feof($fn))  {
				$result = fgets($fn);
				if (!empty($result)) {
					$log++;
				}
			}

			fclose($fn);
		} else {
			$log = 0;
		}

		return $log;
	} 
}