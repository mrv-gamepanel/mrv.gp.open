<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  console.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/admin/includes.php');

//////////////////////////

// If do not login;
if (!($Admin->IsLoged()) == true) {
    header('Location: /admin/login');
    die();
}

$serverID = @$Secure->SecureTxt($GET['id']);
if(!(isset($serverID) || is_numeric($serverID))) {
    die('Molimo upisite ID servera');
}
$IsCreated = $Server->CountServerByID($serverID)['Count'];
if($IsCreated == 0) {
    die('Ovaj Server ne postoji');
}
// If not permission die me
if(!($Admin->suppPermValid($Admin->AdminData()['id'], $Server->serverByID($serverID)['gameID'])) == true) {
    $Alert->SaveAlert('You have no acces.', 'error');
    header('Location: /admin/');
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
                    <form id="searchForm" action="/'<?php echo '$AdmDir'; ?>/search" method="GET">
                        <div class="form-group">
                            <input type="search" name="q" placeholder="What are you searching for...">
                            <button class="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <div class="navbar-header">
                <a href="/'<?php echo '$AdmDir'; ?>/home" class="navbar-brand">
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
                    <h2 class="h5 no-margin-bottom">Server Console</h2>
                </div>
            </div>
            <section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <?php include('public/servernav.php'); ?>
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="table-responsive"> 
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
                        <?php include('public/serveroptions.php'); ?>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/footer.php'); ?>

    <script type="text/javascript">
        $('#consoleContent').animate({
            scrollTop: $('#consoleContent').get(0).scrollHeight
        }, 300);
    </script>
</body>
</html>