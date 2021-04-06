<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  settings.php
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

// Only for Cs 1.6
if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] !== 'cs16') {
    $Alert->SaveAlert('Option is not valid for this Game!', 'info');
    header('Location: /server?id='.$serverID);
    die();
}

/* GameQ Onlien Server information */
$srvIP = $Server->ipAddress($serverID);
$GameQ->addServer([
    'type' => $Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'],
    'host' => $srvIP,
]);
$sInfo      = $GameQ->process();
$serverInfo = $sInfo[$srvIP];

// Return all value (Array)
// pre_r($serverInfo);

/* Get CPU and MEM info :: ps -U srv_1_4 -o %cpu,%mem */


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
                            <div class="col-md-6">
                                <p><strong class="pHeadTitle"><i class="fa fa-play"></i> Start Server : Settings</strong></p>
                                <p>
                                    <span>Start Map</span> 
                                    <strong>
                                        <input id="mapInput" type="text" name="mapName" class="" required="" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Map']); ?>" style="display:none;">
                                        <em id="mpName"><?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Map']); ?></em>
                                        <a id="mapEditBtn" href="javascript:;" onclick="chMapName()"><i class="fa fa-edit"></i></a>
                                        <a id="mapSaveBtn" href="javascript:;" onclick="saveMapName('<?php echo $serverID; ?>')" style="display:none;"><i class="fa fa-check"></i></a>
                                    </strong>
                                </p>
                                <p><span>Server Config</span> <strong>server.cfg</strong></p>
                                <p><span>FPS Max</span> <strong>333</strong></p>
                                <p><span>Tick Rate</span> <strong>333</strong></p> <br>
                            </div>
                            <div class="col-md-6">
                                <p><strong class="pHeadTitle"><i class="fa fa-cog"></i> Server.cfg : Settings</strong></p>
                                <p>
                                    <span>sv_password</span> 
                                    <strong><?php echo $spw_ = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'sv_password')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('sv_password', '<?php echo $spw_; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>hostname</span> 
                                    <strong><?php echo $h_ = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'hostname')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('hostname', '<?php echo $h_; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>rcon_password</span> 
                                    <strong><?php echo $rpw_ = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'rcon_password')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('rcon_password', '<?php echo $rpw_; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>sv_gravity</span> 
                                    <strong><?php echo $gr = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'sv_gravity')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('sv_gravity', '<?php echo $gr; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>mp_friendlyfire</span> 
                                    <strong><?php echo $mf = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'mp_friendlyfire')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('mp_friendlyfire', '<?php echo $mf; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>mp_freezetime</span> 
                                    <strong><?php echo $mfr = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'mp_freezetime')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('mp_freezetime', '<?php echo $mfr; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>mp_startmoney</span> 
                                    <strong><?php echo $msm = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'mp_startmoney')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('mp_startmoney', '<?php echo $msm; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>mp_timelimit</span> 
                                    <strong><?php echo $mt = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'mp_timelimit')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('mp_timelimit', '<?php echo $mt; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>mp_maxrounds</span> 
                                    <strong><?php echo $mpx = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'mp_maxrounds')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('mp_maxrounds', '<?php echo $mpx; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                                <p>
                                    <span>mp_buytime</span> 
                                    <strong><?php echo $mbt = @$Secure->SecureTxt($Server->loadServerCFG($serverID, 'mp_buytime')); ?>
                                        <a href="javascript:;" onclick="srvSettingsModal('mp_buytime', '<?php echo $mbt; ?>', 'show')"><i class="fa fa-edit"></i></a>
                                    </strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
    <!--Server.cfg : Settings-->
    <div class="modal fade" id="ServerSettings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Server.cfg : Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="sI_" style="text-transform:uppercase;"></h4> <br><br>
                    <form id="serverSettingsForm" action="" method="POST" accept-charset="utf-8">
                        <input id="sf_Type" type="text" name="sfType" style="display:none;">
                        <div class="row">
                            <div class="col-4">
                                <h3 id="sf_Key">loading..</h3>
                            </div>
                            <div class="col-8">
                                <input id="sf_lVal" type="text" name="sfNewValue" class="form-control">
                            </div>
                        </div> <br>
                        <span id="sf_Btn" class="btn btn-primary" style="float:right;cursor:pointer;" onclick="serverSettingsForm('<?php echo $serverID; ?>')"><i class="fa fa-save"></i> Save</span>
                        <span id="sf_loadMsg" class="btn btn-success" style="float:right;display:none;"><i class="fa fa-cog fa-spin"></i> Please wait ..</span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>