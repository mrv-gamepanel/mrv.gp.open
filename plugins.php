<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  plugins.php
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
} else {
    $Alert->SaveAlert('Server?', 'error');
    header('Location: /servers');
    die();
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
                        <div class="row srvInfor">
                            <div class="col-md-12">
                                <div class="table-responsive"> 
                                    <table class="table table-hover" id="sPlOnlineTable">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width:15%;">Name</th>
                                                <th scope="col">Description</th>
                                                <th style="width:15%;text-align:right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($Plugins->pluginList($Server->serverByID($serverID)['gameID'])['Response'] as $p_k => $p_v) { 
                                                // File Name
                                                $pluginFile     = $Secure->SecureTxt(basename($p_v['pluginDir']));
                                                // Plugin Path
                                                $Path = urldecode('cstrike/addons/amxmodx/plugins/'.$pluginFile);
                                                // Box: Host | Server IP
                                                $boxID          = $Server->serverByID($serverID)['boxID'];
                                                $serverHost     = $Box->boxByID($boxID)['Host'];
                                                $serverUser     = $Server->serverByID($serverID)['Username'];
                                                $serverPass     = $Server->serverByID($serverID)['Password'];
                                                // If exists
                                                $pluginPath = 'ftp://'.urlencode($serverUser).':'.urlencode($serverPass).'@'.$serverHost.':21/'.$Path;
                                                @file_exists($pluginPath) ? $pluginLocated = true : $pluginLocated = false;
                                                // Get Parameter
                                                $isPluginEnable = @$Server->getParamByFile($serverID, '/cstrike/addons/amxmodx/configs/', 'mrv-plugins.ini', $pluginFile);
                                                // If Installed & Enabled;
                                                if ($pluginLocated == true && $isPluginEnable == true) {
                                                    $pluginInstalled = true;    // Plugin je instaliran;
                                                } else {
                                                    $pluginInstalled = false;
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $Secure->SecureTxt($p_v['Name']); ?></td>
                                                    <td><?php echo nl2br($Secure->SecureTxt($p_v['Note'])); ?></td>
                                                    <td style="text-align:right;">
                                                        <?php if ($pluginInstalled == false) { ?>
                                                            <a href="javascript:;" onclick="installPlugin('<?php echo $serverID; ?>', '<?php echo $p_v['id']; ?>')" class="btn btn-sm btn-success" style="padding:5px 15px;margin-right:5px;" title="Install this plugin"><i class="fa fa-download"></i></a>
                                                        <?php } else { ?>
                                                            <a href="javascript:;" onclick="removePlugin('<?php echo $serverID; ?>', '<?php echo $p_v['id']; ?>')" class="btn btn-sm btn-danger" style="padding:5px 15px;margin-right:5px;" title="Remove this plugin"><i class="fa fa-trash"></i></a>
                                                        <?php } ?>
                                                        <!-- <a href="javascript:;" class="btn btn-sm btn-warning" style="padding:5px 10px;"><i class="fa fa-info-circle"></i></a> -->
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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