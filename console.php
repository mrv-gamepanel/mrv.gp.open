<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  console.php
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

// PHP seclib
set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
include('Net/SSH2.php');

// Box: Host : (IP)
$boxID  = $Server->serverByID($serverID)['boxID'];
// Get Box  :: Host IP
$boxHost    = $Box->boxByID($boxID)['Host'];
// Get Box  :: SSH2 port
$sshPort    = $Box->boxByID($boxID)['sshPort'];
// Get Box :: Username
$boxUser    = $Box->boxByID($boxID)['Username'];
// Get Box :: Password
$boxPass    = $Box->boxByID($boxID)['Password'];

// Get Server Username
$serverUsername = $Server->serverByID($serverID)['Username'];
$serverPassword = $Server->serverByID($serverID)['Password'];

// Get Server Path
// Connect
$SSH2 = new Net_SSH2($boxHost, $sshPort);
if (!$SSH2->login($boxUser, $boxPass)) {
    die('Login Failed');
}

// Get 1000 line of screenlog.0
if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'cs16') {
    $consoleInfo = $SSH2->exec('tail -n 1000 /home/'.$serverUsername.'/screenlog.0');
    $SSH2->setTimeout(1);
} else if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'samp') {
    $consoleInfo = $SSH2->exec('tail -n 1000 /home/'.$serverUsername.'/screenlog.0');
    $SSH2->setTimeout(1);
} else if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'fivem') {
    $consoleInfo = $SSH2->exec('tail -n 1000 /home/'.$serverUsername.'/server-data/screenlog.0');
    $SSH2->setTimeout(1);
    // $consoleInfo = $webFTP->getFileContent($serverID, '/server-data/', 'screenlog.0');
} else if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'csgo') {
    // $consoleInfo = $webFTP->getFileContent($serverID, '/csgoserver/', 'screenlog.0');
    $consoleInfo = $SSH2->exec('tail -n 1000 /home/'.$serverUsername.'/csgoserver/screenlog.0');
    $SSH2->setTimeout(1);
}
// Block..
$consoleInfo = str_replace('/home', '', $consoleInfo);  
$consoleInfo = str_replace('>', '', $consoleInfo);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Console | <?php echo $Site->SiteConfig()['site_name']; ?></title>

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
                                <pre id="consoleContent" style="height:50vh;max-height:50vh;text-align:left;"><?php echo $Secure->SecureTxt($consoleInfo); ?></pre>
                            </div>
                            <?php if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'cs16') { ?>
                                <div class="col-md-12">
                                    <?php if (!empty($Server->loadServerCFG($serverID, 'rcon_password'))) { ?>
                                        <form action="/process?sCommandByRcon" method="POST">
                                            <input type="text" name="srvID" value="<?php echo $serverID; ?>" required="" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-11">
                                                    <input type="text" name="Command" placeholder="amx_map <mapname>" required="" class="form-control">
                                                </div>
                                                <div class="col-md-1">
                                                    <button class="btn btn btn-primary"><i class="fa fa-send"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php } else { ?>
                                        <h2>Rcon password is not defined. Please set RCON password.</h2>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>

    <script type="text/javascript">
        $('#consoleContent').animate({
            scrollTop: $('#consoleContent').get(0).scrollHeight
        }, 300);
    </script>
</body>
</html>