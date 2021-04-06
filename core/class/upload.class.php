<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  upload.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Upload {

	public function UploadFile($File, $save_to_loction, $newFileName, $Only_for_Type, $w=800, $h=650) {
		global $User;
		global $Secure;

		//File upload to dir;
		$fUploadDir 	= $save_to_loction;

		//File Name
		$fFileName 		= $Secure->SecureTxt(basename($File['name']));
		//File Extensions
		$Ext 			= strtolower(pathinfo($fFileName, PATHINFO_EXTENSION));

		//$pDuplikat 		= pathinfo($fFileName, PATHINFO_FILENAME);
		$FileName 		= $newFileName.'.'.$Ext;

		$FileTmp 		= $Secure->SecureTxt($File['tmp_name']);
		
		//File full putanja
		$FilePutanja 	= $_SERVER['DOCUMENT_ROOT'].'/'.$fUploadDir.'/'.$FileName;
		if(!(stristr($Only_for_Type, $Ext)) == false) {
			//File provera za duplikat
			//if (!(file_exists($FilePutanja)) == true) {
				//File upload
				if (move_uploaded_file($FileTmp, $FilePutanja)) {
					$return = true;
				} else {
					$return = false;
				}

				if ($Ext == 'jpeg'||$Ext == 'jpg'||$Ext == 'png') {
					//fixed
					include_once($_SERVER['DOCUMENT_ROOT'].'/core/class/resize.class.php');

		   			$resizeObj = new resize($FilePutanja);
		   			$resizeObj->resizeImage($w, $h, 'auto');
		   			$resizeObj->saveImage($FilePutanja, 80);

		   			$return = true;
				}
				
			//}
		} else {
			$return = false;
		}

		return $return;
	}


}