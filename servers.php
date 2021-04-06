<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  servers.php
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
    <div class="container">
        <div class="test-server">
            <h1 style="position:absolute;transform:rotate(60deg);"><i class="fa fa-angle-right"></i></h1>
            <h1 style="position:absolute;transform:rotate(120deg);right:12vw;"><i class="fa fa-angle-right"></i></h1>
            <h1>My server<?php ($Servers->serversByUser($User->UserData()['id'])['Count'] > 1) ? $s='s' : $s=''; echo @$s; ?></h1>
            <div class="row justify-content-center">
                <?php foreach ($Servers->serversByUser($User->UserData()['id'])['Response'] as $k => $v) { ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4" onclick="document.location.href = '/server?id=<?php echo $v['id']; ?>'">
                        <div class="card">
                            <div class="card-body srv_card_" style="background:url('<?php echo $Games->gameByID($v['gameID'])['bg_img']; ?>') no-repeat center center;">
                                <?php if($v['Status'] !== '1') { $b='background:red;'; } else { $b=''; } ?>
                                <h4 class="game"><?php echo $Games->gameByID($v['gameID'])['Name']; ?> <span class="pulse" style="<?php echo @$b; ?>"></span></h4>
                                <p class="ip">
                                    <span id="ip1"><?php echo $Server->ipAddress($v['id']); ?></span> 
                                    <span onclick="copyToClipboard('<?php echo $Server->ipAddress($v['id']); ?>')"><i class="fa fa-copy"></i></span>
                                </p>
                                <h4 class="player"><?php echo $Secure->LimitText($Secure->SecureTxt($v['Name']), 25); ?></h4>
                                <span class="player-number">32/32</span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
</body>
</html>
