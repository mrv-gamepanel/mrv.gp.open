<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  fdl_view.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/*
View FDL info; [OK]
Install FDL from my server;
Default FDL installation;
Detele all from my FDL;
*/


include_once($_SERVER['DOCUMENT_ROOT'].'/admin/includes.php');

//////////////////////////

// If do not login;
if (!($Admin->IsLoged()) == true) {
    header('Location: /admin/login');
    die();
}
// Set Game ID;
$gameID = 5;  // Fast Download Game ID;
// If not permission die me
if(!($Admin->suppPermValid($Admin->AdminData()['id'], $gameID)) == true) {
    $Alert->SaveAlert('You have no acces.', 'error');
    // header('Location: /admin/');
    die();
}

$serverID   = @$Secure->SecureTxt($GET['id']);
if(!(isset($serverID) || is_numeric($serverID))) {
    die('Molimo upisite ID servera');
}
// Get FDL information
$fdlInfo    = @$Server->FDLinfo($serverID);

// Get Box  :: BoxID
$boxID      = $fdlInfo['boxID'];
// Get Box  :: Host IP
$boxHost    = $Box->boxByID($boxID)['Host'];
// Get Box  :: SSH2 port
$sshPort    = $Box->boxByID($boxID)['sshPort'];
// Get Box :: Username
$boxUser    = $Box->boxByID($boxID)['Username'];
// Get Box :: Password
$boxPass    = $Box->boxByID($boxID)['Password'];
if (isset($GET['info'])) {
    // PHP seclib
    set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
    include('Net/SSH2.php');
    // Connect
    $SSH2 = new Net_SSH2($boxHost, $sshPort);
    if (!$SSH2->login($boxUser, $boxPass)) {
        die('Login Failed');
    }

    // Create FTP user
    $cpuUsed        = 'n/a';
    $memoryUsed     = 'n/a';
    $storageUsed    = @$SSH2->exec('du -sh -- /var/www/html/fdl/user/'.$Server->fdlInfo($serverID)['Username']);
    $storageUsed    = str_replace('/var/www/html/fdl/user/'.$Server->fdlInfo($serverID)['Username'], '', $storageUsed);

    /* FTP connection */
    if(isset($GET['p'])) {
        $Path           = $Secure->SecureTxt($GET['p']);
        $back_link      = dirname($Path);

        $ftp_path       = substr($Path, 1);
        $breadcrumbs    = preg_split('/[\/]+/', $ftp_path, 9); 
        $breadcrumbs    = str_replace("/", "", $breadcrumbs);

        $ftp_pth = '';
        if(($bsize = sizeof($breadcrumbs)) > 0) {
            $sofar = '';
            for($bi=0;$bi<$bsize;$bi++) {
                if($breadcrumbs[$bi]) {
                    $sofar = $sofar . $breadcrumbs[$bi].'/';

                    $ftp_pth .= '<i class="fa fa-chevron-right"></i>
                                    <a href="/'.$admDir.'/fdl_view?id='.$serverID.'&p=/'.$sofar.'&f=&info">
                                    <i class="fa fa-folder-open folder"></i> '.$breadcrumbs[$bi].'</a>';
                }
            }
        }
    } else {
        $Path = '';
        header('Location: /'.$admDir.'/fdl_view?id='.$serverID.'&p=/&f=&info');
        die();
    }
    if(isset($GET['p'])) {
        $old_path = $Secure->SecureTxt($GET['p']).'/';
        $old_path = str_ireplace('//', '/', $old_path);
    }
    if (!isset($GET['f'])) {
        $Path = '';
        header('Location: /'.$admDir.'/fdl_view?id='.$serverID.'&p=/&f=&info');
        die();
    }
    // Allow Ext
    $allowed_ext    = @$webFTP->allowExtView();
    // Get FTP files/dirs
    $ftpList        = @$webFTP->serverFiles($serverID, $Path, '1')['List'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>

    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/head.php'); ?>
</head>
<body>
    <!-- [HEADER] -->
	<header class="header">
		<nav class="navbar navbar-expand-lg">
			<div class="search-panel">
				<div class="search-inner d-flex align-items-center justify-content-center">
					<div class="close-btn">Close <i class="fa fa-close"></i></div>
					<form id="searchForm" action="/admin/search" method="GET">
						<div class="form-group">
							<input type="search" name="q" placeholder="What are you searching for...">
							<button class="submit">Search</button>
						</div>
					</form>
				</div>
			</div>
			<div class="container-fluid d-flex align-items-center justify-content-between">
				<div class="navbar-header">
				<a href="/admin/home" class="navbar-brand">
					<div class="brand-text brand-big visible text-uppercase">
						<strong class="text-primary"><?php echo $Site->SiteConfig()['site_name']; ?></strong>
					</div>
					<div class="brand-text brand-sm">
						<strong class="text-primary"><?php echo $Secure->LimitText($Site->SiteConfig()['site_name'], 3); ?></strong>
					</div></a>
					<button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
				</div>
				<div class="right-menu list-inline no-margin-bottom">    
					<div class="list-inline-item logout">
						<a id="logout" href="logout.php" class="nav-link"> <span class="d-none d-sm-inline">Logout </span>
							<i class="icon-logout"></i>
						</a>
					</div>
				</div>
			</div>
		</nav>
	</header>
	<!-- [CONTAINER] -->
	<div class="d-flex align-items-stretch">
		<!-- [NAVIGATION] -->
		<?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/nav.php'); ?>
		<!-- [CONTENT] -->
		<div class="page-content">
			<div class="page-header">
				<div class="container-fluid">
					<h2 class="h5 no-margin-bottom">Fast Download :: Server Info</h2>
				</div>
			</div>
			<section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong>Server Navigation</strong></div>
                                <div class="srvNavigation">
                                    <a id="l_server" href="/<?php echo $admDir; ?>/fdl_view?id=<?php echo $serverID; ?>&info">Server info</a>
                                    <a id="l_settings" href="/<?php echo $admDir; ?>/fdl_view?id=<?php echo $serverID; ?>&settings">Settings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <?php if (isset($GET['info'])) { ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="block">
                                            <div class="title"><strong>FDL used</strong></div>
                                            <div class="table-responsive"> 
                                                <table class="table table-striped table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td style="widows:100px;">CPU used:</td>
                                                            <td><strong><?php echo $cpuUsed; ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="widows:100px;">Memory used:</td>
                                                            <td><strong><?php echo $memoryUsed; ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="widows:100px;">Storage used:</td>
                                                            <td><strong><?php echo $storageUsed; ?></strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="block">
                                            <div class="title"><strong>FTP Informations</strong></div>
                                            <div class="table-responsive"> 
                                                <table class="table table-striped table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td style="widows:100px;">Host:Port</td>
                                                            <td><strong><?php echo $boxHost; ?>:21</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="widows:100px;">Username:</td>
                                                            <td><strong><?php echo $Server->fdlInfo($serverID)['Username']; ?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="widows:100px;">Password:</td>
                                                            <td><strong><?php echo $Server->fdlInfo($serverID)['Password']; ?></strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FDL Link -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="block">
                                            <div class="title"><strong>FDL link</strong></div>
                                            <div class="table-responsive"> 
                                                <table class="table table-striped table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td style="widows:100px;">Link:</td>
                                                            <td><strong style="float:right;"><a href="//fdl.mrv-hosting.com/fdl/user/<?php echo $Server->fdlInfo($serverID)['Username']; ?>/cstrike" target="_blank">https://fdl.mrv-hosting.com/fdl/user/<?php echo $Server->fdlInfo($serverID)['Username']; ?>/cstrike</a></strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Web FTP -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <div class="block">
                                                <div class="title"><strong>Web FTP</strong></div>
                                                    <div class="row justify-content-between">
                                                        <div class="col-md-8">
                                                            <?php if(isset($GET['p'])) { ?>
                                                                <div class="ftpShortcuts">
                                                                    <a href="/<?php echo $admDir; ?>/fdl_view?id=<?php echo $serverID; ?>&p=/&f=&info">
                                                                        <i class="fa fa-home folder"></i> root
                                                                    </a>
                                                                    <?php echo $ftp_pth; if(isset($GET['f']) && !empty($GET['f'])) { ?>
                                                                        <i class="fa fa-caret-right"></i>
                                                                        <i class="fa fa-file"></i> 
                                                                    <?php echo $Secure->SecureTxt($GET['f']); } ?>
                                                                </div>
                                                            <?php } ?>
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
                                                                            <th scope="col">User</th>
                                                                            <th scope="col">Group</th>
                                                                            <th scope="col">Permissions</th>
                                                                            <th scope="col" style="text-align:right;">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="webFTPtable">
                                                                        <?php $back_link = str_replace("\\", '/', $back_link);
                                                                        if(isset($GET['p']) && $Secure->SecureTxt($GET['p']) != '/') { ?>
                                                                            <tr>
                                                                                <td colspan="7" style="cursor:pointer;" onClick="window.location='?id=<?php echo $serverID; ?><?php if ($back_link != '/') { ?>&p=<?php echo $back_link; } ?>&f=&info'">
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
                                                                                            <a href="/<?php echo $admDir; ?>/fdl_view?id=<?php echo $serverID; ?>&p=<?php echo $old_path.urlencode($dir_val['8']); ?>&f=&info">
                                                                                                <i class="fa fa-folder folder"></i> <?php echo $Secure->SecureTxt($dir_val['8']); ?>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>-</td>
                                                                                        <td><?php echo $Secure->SecureTxt($dir_val['2']); ?></td>
                                                                                        <td><?php echo $Secure->SecureTxt($dir_val['3']); ?></td>
                                                                                        <td><?php echo $Secure->SecureTxt($dir_val['0']); ?></td>
                                                                                        <td style="text-align:right;">
<!--[DELETE FOLDER]-->
<a href="javascript:;" data-toggle="modal" data-target="#<?php echo $Secure->SecureTxt('delFolder'.$dir_key); ?>" class="btn btn-sm btn-danger" title="<?php echo $Secure->SecureTxt('Remove dir: '.$dir_val['8']); ?>"><i class="fa fa-trash"></i></a>

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
                <form action="" method="POST" autocomplete="off">
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
                                                                                            <a href="/<?php echo $admDir; ?>/fdl_view?id=<?php echo $serverID; ?>&p=<?php echo $Path; ?>&f=<?php echo urlencode($file_val['8']); ?>&info">
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
                                                                                    <td><?php echo $Secure->SecureTxt($file_val['2']); ?></td>
                                                                                    <td><?php echo $Secure->SecureTxt($file_val['3']); ?></td>
                                                                                    <td><?php echo $Secure->SecureTxt($file_val['0']); ?></td>
                                                                                    <td style="text-align:right;">
<a href="javascript:;" data-toggle="modal" data-target="#<?php echo $Secure->SecureTxt('delFile'.$file_key); ?>" class="btn btn-sm btn-danger" title="<?php echo $Secure->SecureTxt('Remove file: '.$file_val['8']); ?>"><i class="fa fa-trash"></i></a>

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
                <form action="" method="POST" autocomplete="off">
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
                                                                                    </td>
                                                                                </tr>
                                                                        <?php } } ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php } else { ?>
                                                                <div id="ftpSaveFile" style="margin:15px 0;">
                                                                    <form action="/<?php echo $admDir; ?>/process?saveFtpFile" method="POST">
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
                            <?php } else if(isset($GET['settings'])) { ?>
                                <!--Install FDL from my server;
                                Default FDL installation;
                                Detele all from my FDL;-->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="block">
                                            <div class="title"><strong>FDL Installation</strong></div>
                                            <div class="table-responsive"> 
                                                <table class="table table-striped table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td style="widows:100px;">Files from my server:</td>
                                                            <td>
                                                                <form id="installFDLform" action="/admin/process?installFDL" method="POST" autocomplete="off" style="float:right;">
                                                                    <input type="text" name="fdlID" value="<?php echo $serverID; ?>" style="display:none;">
                                                                    <input type="text" name="default" value="false" style="display:none;">
                                                                    <select name="serverID" class="js-example-basic-single form-control" style="width:300px;" onchange="$('#installFDLform').submit();">
                                                                        <option value="" selected="" disabled="">- select server -</option>
                                                                        <?php foreach ($Servers->serversByUserAndGame($fdlInfo['userID'], $Games->gameBySmName('cs16')['id'])['Response'] as $s_k => $s_v) { 
                                                                            if (empty($fdlInfo['Install']) || $fdlInfo['Install'] == '' || $fdlInfo['Install'] == 'default') { $S = ''; } else { $S = 'selected=""'; }
                                                                            ?>
                                                                            <option value="<?php echo $s_v['id']; ?>" <?php echo $S; ?>><?php echo $Secure->SecureTxt($s_v['Name'].' - '.$Box->boxByID($s_v['boxID'])['Host'].':'.$s_v['Port']); ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="widows:100px;">Default instalaltion:</td>
                                                            <td><span style="float:right;" class="btn btn-success" onclick="installFDL('<?php echo $serverID; ?>', 'true')"><i class="fa fa-upload"></i> Default</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="block">
                                            <div class="title"><strong>FDL Action</strong></div>
                                            <div class="table-responsive"> 
                                                <table class="table table-striped table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <span class="btn btn-success"><i class="fa fa-key"></i> Change FTP password</span>
                                                            </td>
                                                            <td>
                                                                <span class="btn btn-danger" style="float:right;"><i class="fa fa-trash"></i> Remove FDL</span> 
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
		</div>
	</div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/footer.php'); ?>
</body>
</html>