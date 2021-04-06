<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  webftp.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class webFTP {

    /* Only allow for Web FTP View */
    public function allowExtView() {
        $allowExtView = array('txt', 'sma', 'cfg', 'inf', 'log', 'rc', 'ini', 'yml', 'json', 'properties', 'conf');
        return $allowExtView;
    }
    /* Only allow ext for Uplaod on Web FTP (file ext support) */
    public function allowExtForUpload() {
        $allowExtForUpload = array('txt', 'sma', 'cfg', 'inf', 'log', 'rc', 'ini', 'yml', 'json', 'properties', 'conf', 'amxx', 'mdl');
        return $allowExtForUpload;
    }
    /* Web FTP list server files */
    public function serverFiles($serverID, $Path='.') {
        global $Box, $Server, $Secure;

        $Path = urldecode($Path);
        // Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        $serverPass     = $Server->serverByID($serverID)['Password'];
        // FTP Connect
        $cFTP = ftp_connect($serverHost, 21);
        if(!$cFTP) {
            $nArr = Array(
                'Error'     => '1',
                'Message'   => 'FTP connecting problem',
                'List'      => Array(
                    'dir'   => 'n/a',
                    'file'  => 'n/a'
                )
            );
        }
        // FTP Login
        if (@ftp_login($cFTP, $serverUser, $serverPass)) {
            ftp_pasv($cFTP, true);
            @ftp_chdir($cFTP, $Path);
            $ftp_contents = ftp_rawlist($cFTP, '-a .');

            foreach ($ftp_contents as $k_f) {
                $current = preg_split("/[\s]+/", $k_f, 9);

                $isdir = ftp_size($cFTP, $current[8]);
                if (substr($current[0][0], 0 - 1) == 'l') {
                    $ext = explode('.', $current[8]);
                    print_r($ext);
                    $xa = explode('->', $current[8]);
                    
                    $current[8] = $xa[0];
                    
                    $current[0] = 'link';
                    
                    $current[4] = 'file link';
                    
                    $fileList[] = $current;
                } else if (substr($current[0][0], 0 - 1 ) == 'd') {
                    $dirList[] = $current;
                } else {
                    $fileList[] = $current;
                }
            }
            $nArr = Array(
                'Error'     => '0',
                'Message'   => 'Success Login!',
                'List'      => Array(
                    'dir'   => isset($dirList)  ? $dirList  : '',
                    'file'  => isset($fileList) ? $fileList : '',
                )
            );
        } else {
            $nArr = Array(
                'Error'     => '1',
                'Message'   => 'Error Login (User or Password is not correct)',
                'List'      => Array(
                    'dir'   => 'n/a',
                    'file'  => 'n/a'
                )
            );
        }
        ftp_close($cFTP);
        return $nArr;
    }
    /* Load File content */
    public function getFileContent($serverID, $Path, $File) {
        global $Box, $Server, $Secure;

        $Path = urldecode($Path);

        // Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        $serverPass     = $Server->serverByID($serverID)['Password'];

        $filePath = 'ftp://'.urlencode($serverUser).':'.urlencode($serverPass).'@'.$serverHost.':21/'.$Path.'/'.$File;
        
        return @file_get_contents($filePath);
    }
    /* Edit File */
    public function saveFileFTP($serverID, $Path, $File, $fileText) {
        global $Box, $Server, $Site, $Games, $webFTP;

        $Path = urldecode($Path);
        // Get Small Game Name (cs16, mc, samp..)
        $smGameName     = $Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'];
        // Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        $serverPass     = $Server->serverByID($serverID)['Password'];
        // FTP Connect
        $cFTP = ftp_connect($serverHost, 21);
        if(!$cFTP) {
            return false;
        }
        // FTP Login
        if (@ftp_login($cFTP, $serverUser, $serverPass)) {
            ftp_pasv($cFTP, true);
            @ftp_chdir($cFTP, $Path);
            // Backup last file
            $loadFile = $webFTP->getFileContent($serverID, $Path, $File);
            // Create Game Folder if not exist
            $gameFolder = $_SERVER['DOCUMENT_ROOT'].'/files/b/'.$smGameName;
            if (!($Site->isFolder($gameFolder)) == true) {
                $Site->createFolder($gameFolder);
            } else {
                //echo '<b>Step 1)</b> - Game folder is already!<hr>';
            }
            // Create User Folder if not exist
            if (!($Site->isFolder($gameFolder.'/'.$serverUser)) == true) {
                $Site->createFolder($gameFolder.'/'.$serverUser);
            } else {
                //echo '<b>Step 2)</b> - User folder is already!<hr>';
            }
            // Backup File
            $bckFileName = $serverUser.'-'.time().'-'.$File;
            // Create the backup file
            $Site->createFile($gameFolder.'/'.$serverUser.'/'.$bckFileName, $loadFile);
            // Create new file
            $secFileName = $serverUser.'-'.time().'-sec-'.$File;
            $newFile = $Site->createFile($gameFolder.'/'.$serverUser.'/'.$secFileName, $fileText);
            // Get File full location
            $getFullFileLoc = $gameFolder.'/'.$serverUser.'/'.$secFileName;
            // Save new file (fix the problem - file is not saved)
            if (ftp_put($cFTP, $Path.'/'.$File, $getFullFileLoc, FTP_BINARY)) {
                $return = true;
            } else {
                $return = false;
            }
            unlink($getFullFileLoc);
            if ($return == true) {
                return true;
            } else {
                return false;
            }
        }
    }
    /* Delete FTP folder */
    public function deleteFTPfolder($serverID, $Path, $folderName) {
        global $Box, $Server, $Site, $Games, $webFTP;

        $Path = urldecode($Path);
        // Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        $serverPass     = $Server->serverByID($serverID)['Password'];
        // FTP Connect
        $cFTP = ftp_connect($serverHost, 21);
        if(!$cFTP) {
            return false;
        }
        // FTP Login
        if (@ftp_login($cFTP, $serverUser, $serverPass)) {
            ftp_pasv($cFTP, true);
            @ftp_chdir($cFTP, $Path);
            // Delete Folder
            if(ftp_rmdir($cFTP, $Path.'/'.$folderName)) {
                return true;
            } else {
                return false;
            }
        }
    }
    /* Delete FTP file */
    public function deleteFTPfile($serverID, $Path, $fileName) {
        global $Box, $Server, $Site, $Games, $webFTP;

        $Path = urldecode($Path);
        // Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        $serverPass     = $Server->serverByID($serverID)['Password'];
        // FTP Connect
        $cFTP = ftp_connect($serverHost, 21);
        if(!$cFTP) {
            return false;
        }
        // FTP Login
        if (@ftp_login($cFTP, $serverUser, $serverPass)) {
            ftp_pasv($cFTP, true);
            @ftp_chdir($cFTP, $Path);
            // Delete Folder
            if(ftp_delete($cFTP, $Path.'/'.$fileName)) {
                return true;
            } else {
                return false;
            }
        }
    }
    /* Upload file on FTP server */
    public function uplaodFileOnFTP($serverID, $Path, $File) {
        global $Box, $Server, $Site, $Games, $webFTP;

        $Path = urldecode($Path);
        // Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        $serverPass     = $Server->serverByID($serverID)['Password'];
        // FTP Connect
        $cFTP = ftp_connect($serverHost, 21);
        if(!$cFTP) {
            return false;
        }
        // FTP Login
        if (@ftp_login($cFTP, $serverUser, $serverPass)) {
            ftp_pasv($cFTP, true);
            @ftp_chdir($cFTP, $Path);

            // Upload file on FTP server
            if(ftp_put($cFTP, $Path.'/'.$File['name'], $File['tmp_name'], FTP_BINARY)) {
                return true;
            } else {
                return false;
            }
        }
    }
    // Update FTP PW;
    public function upFTPpw($serverID, $newPW, $userID) {
        global $DataBase;

        // Update Password
        $DataBase->Query("UPDATE `servers` SET `Password` = :newPW WHERE `id` = :serverID AND `userID` = :userID;");
        $DataBase->Bind(':newPW', $newPW);
        $DataBase->Bind(':serverID', $serverID);
        $DataBase->Bind(':userID', $userID);

        return $DataBase->Execute();
    }
    // Change FTP Password
    public function changeFTPpw($serverID, $newPW) {
        global $Server, $webFTP, $Box, $User;

        // Box: Host | Server IP
        $boxID          = $Server->serverByID($serverID)['boxID'];
        $serverHost     = $Box->boxByID($boxID)['Host'];
        $serverUser     = $Server->serverByID($serverID)['Username'];
        // FTP Connect
        $cFTP = ftp_connect($serverHost, 21);
        if(!$cFTP) {
            $return = false;
        } else {
            $return = true;
        }
        // Change FTP password
        if ($return === true) {
            // Box: Host : (IP)
            // Get Box  :: Host IP
            $boxHost    = $Box->boxByID($boxID)['Host'];
            // Get Box  :: SSH2 port
            $sshPort    = $Box->boxByID($boxID)['sshPort'];
            // Get Box :: Username
            $boxUser    = $Box->boxByID($boxID)['Username'];
            // Get Box :: Password
            $boxPass    = $Box->boxByID($boxID)['Password'];
            // PHP seclib
            set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
            include('Net/SSH2.php');
            // Connect
            $SSH2 = new Net_SSH2($boxHost, $sshPort);
            if (!$SSH2->login($boxUser, $boxPass)) {
                die('Login Failed');
            }
            // Change pw
            $SSH2->exec('echo "'.$serverUser.':'.$newPW.'" | sudo chpasswd'); // echo "srv_49_1:g1ff1" | sudo chpasswd
            $SSH2->setTimeout(1);
            // Change pw;
            if(!($webFTP->upFTPpw($serverID, $newPW, $User->UserData()['id'])) == false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}