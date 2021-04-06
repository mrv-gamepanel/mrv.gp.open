<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  advertisements.php
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
                                <form action="" method="POST" autocomplete="off">
                                    <input type="text" name="srvID" value="<?php echo $serverID; ?>" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5><i class="fa fa-plus"></i> New note</h5>
                                        </div>
                                    </div> <hr>
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-11">
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="adsColor" id="adsColor" value="all" style="display:none;">
                                                        <div class="input-group-prepend adsBtns">
                                                            <span id="ads_all" class="adsBtnss btn btn-outline-warning active" onclick="adsTextColor('all')">ALL</span>
                                                            <span id="ads_ct" class="adsBtnss btn btn-outline-primary" onclick="adsTextColor('ct')">CT</span>
                                                            <span id="ads_tt" class="adsBtnss btn btn-outline-danger" onclick="adsTextColor('tt')">TT</span>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input id="adsText" type="text" name="Ads[]" class="form-control" onkeyup="adsTxt()" onchange="adsTxt()">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" onclick="" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div id="moreAds"></div>
                                            </div>
                                        </div>
                                    </div> <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img src="//screenshots.gamebanana.com/img/ss/maps/58c8f933de8ce.jpg" style="width:100%;border-radius:3px;">
                                            <p id="adsTxt"></p>
                                        </div>
                                    </div> <hr>
                                    <button class="btn btn-primary" style="float:right;">
                                        <i class="fa fa-save"></i> Save
                                    </button>
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