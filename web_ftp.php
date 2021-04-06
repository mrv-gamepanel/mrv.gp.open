<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  web_ftp.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//////////////////////////

// If do not login;
if (!($User->IsLoged()) == true) {
    header('Location: /login');
    die();
}

/* Server ID */
if (isset($GET['id'])) {
    $serverID = $Secure->SecureTxt($GET['id']);
    // IS empty id
    if (empty($serverID) || !is_numeric($serverID)) {
        $Alert->SaveAlert('Server?', 'error');
        header('Location: /servers');
        die();
    }
    // Is Valid server
    if (empty($Server->serverByID($serverID)['id'])) {
        $Alert->SaveAlert('Server?', 'error');
        header('Location: /servers');
        die();
    }
    // It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $Alert->SaveAlert('This is not Owner!', 'error');
        header('Location: /servers');
        die();
    }
    /* Block Server Action */
    if($Secure->SecureTxt($Server->serverByID($serverID)['ftpBlock']) !== '0') {
        // Ispričavamo se trenutno vam je ugašena opcija da možete da upravljate serverom, molimo obratite se supportu za pomoć!
        // Alert
        $Alert->SaveAlert('We apologize, your option to manage the server is currently disabled, please contact support for assistance!', 'error');
        header('Location: /server?id='.$serverID);
        die();
    }

} else {
    $Alert->SaveAlert('Server?', 'error');
    header('Location: /servers');
    die();
}

/* FTP connection */
if(isset($GET['p'])) {
    $Path = $Secure->SecureTxt($GET['p']);
    $back_link = dirname($Path);

    $ftp_path = substr($Path, 1);
    $breadcrumbs = preg_split('/[\/]+/', $ftp_path, 9); 
    $breadcrumbs = str_replace("/", "", $breadcrumbs);

    $ftp_pth = '';
    if(($bsize = sizeof($breadcrumbs)) > 0) {
        $sofar = '';
        for($bi=0;$bi<$bsize;$bi++) {
            if($breadcrumbs[$bi]) {
                $sofar = $sofar . $breadcrumbs[$bi].'/';

                $ftp_pth .= '<i class="fa fa-chevron-right"></i>
                                <a href="/web_ftp?id='.$serverID.'&p=/'.$sofar.'&f=">
                                <i class="fa fa-folder-open folder"></i> '.$breadcrumbs[$bi].'</a>';
            }
        }
    }
} else {
    $Path = '';
    header('Location: /web_ftp?id='.$serverID.'&p=/&f=');
    die();
}

if(isset($GET['p'])) {
    $old_path = $Secure->SecureTxt($GET['p']).'/';
    $old_path = str_ireplace('//', '/', $old_path);
}

if (!isset($GET['f'])) {
    $Path = '';
    header('Location: /web_ftp?id='.$serverID.'&p=/&f=');
    die();
}
$allowed_ext = $webFTP->allowExtView();
@$ftpList = $webFTP->serverFiles($serverID, $Path)['List'];
// pre_r($ftpList);
// pre_r($ftpList['dir']); 
// pre_r($ftpList['file']);

if (isset($GET['f'])) {
    $File_ = @$Secure->SecureTxt($GET['f']);
    $Ext = strtolower(pathinfo($File_, PATHINFO_EXTENSION));
    if (isset($Ext) && in_array($Ext, $allowed_ext)) {
        // header('Location: /web_ftp?id='.$serverID.'&p=/&f=');
        // die();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>

    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/head.php'); ?>
</head>
<body>
    <!--[HEADER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/header.php'); ?>

    <!--[CONTENT]-->
    <div class="container mt150">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header"><i class="fa fa-angle-right"></i> <img src="<?php echo $Games->gameByID($Server->serverByID($serverID)['gameID'])['Icon']; ?>" alt="<?php echo $Games->gameByID($Server->serverByID($serverID)['gameID'])['Name']; ?>" title="<?php echo $Games->gameByID($Server->serverByID($serverID)['gameID'])['Name']; ?>" style="width:20px;"> <?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Name']); ?></h5>

                    <!--[SERVER NAVIGATION]-->
                    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/server_nav.php'); ?>
                    
                    <!--[SERVER CONTENT]-->
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="container">
                                <div class="row justify-content-between">
                                    <div class="col-md-8">
                                        <?php if(isset($GET['p'])) { ?>
                                            <div class="ftpShortcuts">
                                                <a href="/web_ftp?id=<?php echo $serverID; ?>&p=/&f=">
                                                    <i class="fa fa-home folder"></i> root
                                                </a>
                                                <?php echo $ftp_pth; if(isset($GET['f']) && !empty($GET['f'])) { ?>
                                                    <i class="fa fa-caret-right"></i>
                                                    <i class="fa fa-file"></i> 
                                                <?php echo $Secure->SecureTxt($GET['f']); } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?php if(isset($GET['f']) && empty($GET['f']) || $Secure->SecureTxt($GET['f']) == '') { ?>
                                            <form action="/process?ftpFileUpload" method="POST" autocomplete="off" enctype="multipart/form-data" style="float:right;margin-right:10%;">
                                                <input type="text" name="srvID" value="<?php echo $serverID; ?>" style="display:none;">
                                                <input type="text" name="Path" value="<?php echo $Path; ?>" style="display:none;">
                                                
                                                <div class="srvWebFtpUplaodFile">
                                                    <li><input type="file" name="newFile" required="" style="margin-left:-20%;padding:2px;"></li>
                                                    <button class="btn btn-sm btn-success" style="position:absolute;"><i class="fa fa-upload"></i></button>
                                                </div>
                                            </form>
                                        <?php } else { ?><br><?php } ?>
                                    </div>
                                </div> <hr>
                                <div class="row srvInfor">
                                    <div class="col-md-12">
                                        <?php if(isset($GET['f']) && empty($GET['f']) || $Secure->SecureTxt($GET['f']) == '') { ?>
                                            <table class="table table-hover" id="sPlOnlineTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Size</th>
                                                        <!--<th scope="col">User</th>
                                                        <th scope="col">Group</th>-->
                                                        <th scope="col">Permissions</th>
                                                        <th scope="col" style="text-align:right;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="webFTPtable">
                                                    <?php $back_link = str_replace("\\", '/', $back_link);
                                                    if(isset($GET['p']) && $Secure->SecureTxt($GET['p']) != '/') { ?>
                                                        <tr>
                                                            <td colspan="7" style="cursor:pointer;" onClick="window.location='?id=<?php echo $serverID; ?><?php if ($back_link != '/') { ?>&p=<?php echo $back_link; } ?>&f='">
                                                                <i class="fa fa-arrow-left"></i> ...
                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                                    <?php if ($ftpList['dir'] !== '') {
                                                        // Folder List
                                                        foreach ($ftpList['dir'] as $dir_key => $dir_val) {
                                                            if (!($dir_val['8'] == '.' || $dir_val['8'] == '..')) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <a href="/web_ftp?id=<?php echo $serverID; ?>&p=<?php echo $old_path.urlencode($dir_val['8']); ?>&f=">
                                                                            <i class="fa fa-folder folder"></i> <?php echo $Secure->SecureTxt($dir_val['8']); ?>
                                                                        </a>
                                                                    </td>
                                                                    <td>-</td>
                                                                    <!--<td><?php echo $Secure->SecureTxt($dir_val['2']); ?></td>
                                                                    <td><?php echo $Secure->SecureTxt($dir_val['3']); ?></td>-->
                                                                    <td><?php echo $Secure->SecureTxt($dir_val['0']); ?></td>
                                                                    <td style="text-align:right;">
<!--[DELETE FOLDER]-->
<a href="javascript:;" data-toggle="modal" data-target="#<?php echo $Secure->SecureTxt('delFolder'.$dir_key); ?>" class="btn btn-sm btn-danger" title="<?php echo $Secure->SecureTxt('Remove dir: '.$dir_val['8']); ?>" style="padding:5px 10px;"><i class="fa fa-trash"></i></a>

<div class="modal fade" id="<?php echo $Secure->SecureTxt('delFolder'.$dir_key); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    Delete folder <small>(<?php echo $Secure->SecureTxt($dir_val['8']); ?>)?</small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/process?deleteFolder" method="POST" autocomplete="off">
                    <input type="text" name="srvID" value="<?php echo $serverID; ?>" style="display:none;">
                    <input type="text" name="Path" value="<?php echo $Path; ?>" style="display:none;">
                    <input type="text" name="folderName" value="<?php echo $Secure->SecureTxt($dir_val['8']); ?>" style="display:none;">
                    
                    <span type="button" class="btn btn-sm btn-primary active" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-remove"></i> Cancel
                    </span>

                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-check"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--[LOCK FOLDER]-->
<!-- <a href="javascript:;" data-toggle="modal" data-target="#<?php echo $Secure->SecureTxt('lockFolder'.$dir_key); ?>" class="btn btn-sm btn-warning"><i class="fa fa-lock"></i></a> -->
                                                                    </td>
                                                                </tr>
                                                            <?php } }
                                                    }
                                                    // File list
                                                    if ($ftpList['file'] !== '') {
                                                        foreach ($ftpList['file'] as $file_key => $file_val) {
                                                            $Ext = strtolower(pathinfo($file_val['8'], PATHINFO_EXTENSION)); ?>
                                                            <tr >
                                                                <td>
                                                                    <?php if (isset($Ext) && in_array($Ext, $allowed_ext)) { ?>
                                                                        <a href="/web_ftp?id=<?php echo $serverID; ?>&p=<?php echo $Path; ?>&f=<?php echo urlencode($file_val['8']); ?>">
                                                                            <i class="fa fa-file file"></i> <?php echo $Secure->SecureTxt($file_val['8']); ?>
                                                                        </a>
                                                                    <?php } else {
                                                                        echo $Secure->SecureTxt($file_val['8']);
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        if($file_val['4'] == 'file link') echo $file_val['4'];
                                                                        else {          
                                                                            if($file_val['4'] < 1024) echo $file_val['4'].' byte';
                                                                            else if($file_val['4'] < 1048576) echo round(($file_val['4']/1024), 0).' KB';
                                                                            else echo round(($file_val['4']/1024/1024), 0).' MB';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <!--<td><?php echo $Secure->SecureTxt($file_val['2']); ?></td>
                                                                <td><?php echo $Secure->SecureTxt($file_val['3']); ?></td>-->
                                                                <td><?php echo $Secure->SecureTxt($file_val['0']); ?></td>
                                                                <td style="text-align:right;">
<a href="javascript:;" data-toggle="modal" data-target="#<?php echo $Secure->SecureTxt('delFile'.$file_key); ?>" class="btn btn-sm btn-danger" title="<?php echo $Secure->SecureTxt('Remove file: '.$file_val['8']); ?>" style="padding:5px 10px;"><i class="fa fa-trash"></i></a>

<div class="modal fade" id="<?php echo $Secure->SecureTxt('delFile'.$file_key); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    Delete file <small>(<?php echo $Secure->SecureTxt($file_val['8']); ?>)?</small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/process?deleteFile" method="POST" autocomplete="off">
                    <input type="text" name="srvID" value="<?php echo $serverID; ?>" style="display:none;">
                    <input type="text" name="Path" value="<?php echo $Path; ?>" style="display:none;">
                    <input type="text" name="fileName" value="<?php echo $Secure->SecureTxt($file_val['8']); ?>" style="display:none;">
                    
                    <span type="button" class="btn btn-sm btn-primary active" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-remove"></i> Cancel
                    </span>

                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-check"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--[LOCK FILE]-->
<!-- <a href="javascript:;" data-toggle="modal" data-target="#<?php echo $Secure->SecureTxt('lockFile'.$file_key); ?>" class="btn btn-sm btn-warning"><i class="fa fa-lock"></i></a> -->
                                                                </td>
                                                            </tr>
                                                    <?php } } ?>
                                                </tbody>
                                            </table>
                                        <?php } else { ?>
                                            <div id="ftpSaveFile" style="margin:15px 0;">
                                                <form action="/process?saveFtpFile" method="POST">
                                                    <input type="text" name="File" value="<?php echo $Secure->SecureTxt($GET['f']); ?>" style="display:none;">
                                                    <input type="text" name="Path" value="<?php echo $Path; ?>" style="display:none;">
                                                    <input type="text" name="srvID" value="<?php echo $serverID; ?>" style="display:none;">
                                                    <textarea name="fileEdit" class="form-control" rows="25"><?php echo $webFTP->getFileContent($serverID, $Path, $Secure->SecureTxt($GET['f'])); ?></textarea> <hr>
                                                    <button class="btn btn-sm btn-primary" style="float:right;">
                                                        <i class="fa fa-save"></i> SAVE
                                                    </button>
                                                </form>     
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
</body>
</html>