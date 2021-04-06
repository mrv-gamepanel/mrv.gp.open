<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  mysql.php
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
                                <div class="newBtn" style="text-align:right;">
                                    <a href="/process?addMySQL&serverID=<?php echo $serverID; ?>" class="btn btn-sm btn-primary"><i class="fa fa-user-plus"></i> Add MySQL</a>
                                </div> <br>
                                <div class="table-responsive"> 
                                    <table class="table table-hover" id="sPlOnlineTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Host</th>
                                                <th>Port</th>
                                                <th>User</th>
                                                <th>Password</th>
                                                <th>Size</th>
                                                <th style="text-align:right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sMySQL->myMySQLservers($serverID, $User->UserData()['id'])['Response'] as $bK => $bV) { ?>
                                                <tr>
                                                    <th><b><?php echo $bV['id']; ?></b></th>
                                                    <td><b><?php echo $Box->boxByID($bV['boxID'])['Host']; ?></b></td>
                                                    <td><b>3306</b></td>
                                                    <td><b><?php echo $Secure->SecureTxt($bV['mysqlUser']); ?></b></td>
                                                    <td><b><?php echo $Secure->SecureTxt($bV['mysqlPass']); ?></b></td>
                                                    <td><b>n/a</b></td>
                                                    <td style="text-align:right;">
                                                        <span class="btn btn-sm btn-success" onclick="alert('Uskoro!')"><i class="fa fa-download"></i></span>
                                                        <span class="btn btn-sm btn-primary" onclick="alert('Uskoro!')"><i class="fa fa-lock"></i></span>
                                                        <span class="btn btn-sm btn-danger" onclick="alert('Uskoro!')"><i class="fa fa-trash"></i></span>
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