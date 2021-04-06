<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  online.php
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

/* GameQ Online Server information */
$srvIP = $Server->ipAddress($serverID);

$smGameName = @$Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'];

if(empty($smGameName)) {
    die('Game?');
} else {
    if ($smGameName == 'fivem') {
         $smGameName = 'gta5m';
    } else if ($smGameName == 'mc') {
        $smGameName = 'minecraft';
    } else {
        $smGameName = $Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'];
    }
}
$GameQ->addServer([
    'type' => $smGameName,
    'host' => $srvIP,
]);
$sInfo      = $GameQ->process();
$serverInfo = $sInfo[$srvIP];

// Return all value (Array)
// pre_r($serverInfo);

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
                                <p><strong class="pHeadTitle">Online Information</strong></p>
                            </div>
                            <div class="col-md-6">                                
                                <p><span>Game</span> <strong><?php echo @$Secure->SecureTxt($serverInfo['gq_name']); ?></strong></p>
                                <p><span>Name</span> <strong><?php echo @$Secure->SecureTxt($serverInfo['gq_hostname']); ?></strong></p>
                                <p><span>IP address</span> <strong><?php echo @$Server->ipAddress($serverID); ?></strong></p>
                                <p style="border:none;"><span>Players</span> <strong><?php echo @$Secure->SecureTxt($serverInfo['gq_numplayers']).'/'.$Server->serverByID($serverID)['Slot']; ?></strong></p> <br>
                            </div>
                            <div class="col-md-6">
                                <p><span>Online</span> <strong><?php echo @$Server->serverOnlineStatus($serverInfo['gq_online']); ?></strong></p>
                                <p><span>Map</span> <strong><?php echo @$Secure->SecureTxt($serverInfo['gq_mapname']); ?></strong></p>
                                <?php if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'cs16') { ?>
                                    <p><span>Next Map</span> <strong><?php echo @$Secure->SecureTxt($serverInfo['amx_nextmap']); ?></strong></p> <br>
                                <?php } ?>
                            </div>
                        </div><hr>
                        <div class="row srvInfor">
                            <div class="col-md-8">
                                <table class="table table-hover" id="sPlOnlineTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Score</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($serverInfo['players'] as $p_k => $p_v) { ?>
                                            <tr>
                                                <th scope="row"><?php echo ($p_k+1); ?></th>
                                                <td><?php echo $Secure->SecureTxt($p_v['name']); ?></td>
                                                <td><?php echo $Secure->SecureTxt($p_v['score']); ?></td>
                                                <td><?php echo date('H:i:s', $Secure->SecureTxt($p_v['time'])); ?></td>
                                                <td>
                                                    <div class="plAction">
                                                        <a href="">View</a> |
                                                        <a href="">Kick</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                grafikoni
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