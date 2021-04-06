<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  autorestart.php
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
    <title>Auto restart | <?php echo $Site->SiteConfig()['site_name']; ?></title>

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
                                <form action="/process?autoRs" method="POST" autocomplete="off">
                                    <input type="text" name="srvID" value="<?php echo $serverID; ?>" style="display:none;">
    
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <h3>Select time</h3>
                                            <div class="autoRsItem">
                                                <div class="row justify-content-between">
                                                    <div class="col-md-3">
                                                        <label for="rs_0">
                                                            <?php if ($Server->serverByID($serverID)['autoRestart'] == '0') { ?>
                                                                <input id="rs_0" type="checkbox" name="autoRS[]" value="0" class="form-control" checked=""> Disabled
                                                            <?php } else { ?>
                                                                <input id="rs_0" type="checkbox" name="autoRS[]" value="0" class="form-control"> Disabled
                                                            <?php } ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                                                    </div>
                                                </div> <hr>
                                                <div class="row">
                                                    <?php if ($Server->serverByID($serverID)['autoRestart'] !== '0') {
                                                        $autoRsInBase = unserialize($Server->serverByID($serverID)['autoRestart']);
                                                    } ?>
                                                    <?php $od0=-1; foreach ($Secure->get_hours_range() as $auto_rs_k => $auto_rs_v) { $od0++;
                                                        if (isset($autoRsInBase[$od0]) && $autoRsInBase[$od0] == $auto_rs_k) { ?>
                                                            <div class="col-md-3">
                                                                <label for="rs_<?php echo $auto_rs_k; ?>">
                                                                    <input id="rs_<?php echo $auto_rs_k; ?>" type="checkbox" name="autoRS[]" value="<?php echo $auto_rs_k; ?>" class="form-control" checked=""> <?php echo $auto_rs_v; ?>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="col-md-3">
                                                                <label for="rs_<?php echo $auto_rs_k; ?>">
                                                                    <input id="rs_<?php echo $auto_rs_k; ?>" type="checkbox" name="autoRS[]" value="<?php echo $auto_rs_k; ?>" class="form-control"> <?php echo $auto_rs_v; ?>
                                                                </label>
                                                            </div>
                                                        <?php }
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space" style="margin:80px;"></div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
</body>
</html>