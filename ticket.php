<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  ticket.php
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

// Ticket ID
if (isset($GET['id'])) {
    $tID = $Secure->SecureTxt($GET['id']);
} else {
    header('Location: /support');
    die();
}
////////////////////
if (isset($tID)) {
    if (empty($tID) || !is_numeric($tID) || $tID == '' || $tID <= 0) {
        die('dje\'s poso?');
    }
} else {
    header('Location: /support');
    die();
}

// Is valid Ticket ID
if (empty($Support->ticketByID($tID, $User->UserData()['id'])['id'])) {
    $Alert->SaveAlert('This ticket does not exist or is not linked to your account!', 'error');
    header('Location: /support');
    die();
} else {
    $tInfo = $Support->ticketByID($tID, $User->UserData()['id']);
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
                    <h5 class="card-header">
                        <span class="btn btn-sm btn-primary" style="float:right;cursor:pointer;"><i class="fa fa-lock"></i> Lock ticket</span>
                        <i class="fa fa-info"></i> <?php echo $Secure->SecureTxt($tInfo['Title']); ?> #<?php echo $GET['id']; ?>
                    </h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2" style="text-align:center;">
                                                <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid" style="width:100px;border-radius:50%;">
                                                <div class="clearfix" style="margin-top:10px;"></div>
                                                <p class="text-secondary text-center" style="font-size:10px;margin:0px -14px;"><?php echo $Secure->SecureTxt($tInfo['Date']); ?></p>
                                            </div>
                                            <div class="col-md-10">
                                                <p><strong><?php echo $User->getFullName($tInfo['userID']); ?></strong><br>
                                                    <small style="color:#4987d4;">(Client)</small></p>
                                                <p><?php echo nl2br($Secure->SecureTxt($tInfo['Message'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <?php foreach ($Support->answOnTicketList($tInfo['id']) as $t_k => $t_v) { ?>
                                    <?php if (empty($t_v['supportID'])) { ?>
                                        <div class="card card-inner">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-2" style="text-align:center;">
                                                        <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid" style="width:100px;border-radius:50%;">
                                                        <div class="clearfix" style="margin-top:10px;"></div>
                                                        <p class="text-secondary text-center" style="font-size:10px;margin:0px -14px;"><?php echo $Secure->SecureTxt($t_v['Date']); ?></p>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <p><strong><?php echo $User->getFullName($t_v['userID']); ?></strong><br>
                                                            <small style="color:#4987d4;">(Client)</small></p>
                                                        <p><?php echo nl2br($Secure->SecureTxt($t_v['Message'])); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br>
                                    <?php } else { ?>
                                        <div class="card card-inner">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-2" style="text-align:center;">
                                                        <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid" style="width:100px;border-radius:50%;">
                                                        <div class="clearfix" style="margin-top:10px;"></div>
                                                        <p class="text-secondary text-center" style="font-size:10px;margin:0px -14px;"><?php echo $Secure->SecureTxt($t_v['Date']); ?></p>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <p><strong style="color:red;"><?php echo $Admin->getFullName($t_v['supportID']); ?></strong><br>
                                                            <small style="color:#4987d4;">(Support)</small></p>
                                                        <p><?php echo nl2br($Secure->SecureTxt($t_v['Message'])); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br>
                                    <?php } ?>
                                <?php } ?> 
                                <p style="position:absolute;right:0;bottom:0;">
                                    <a class="float-right btn btn-sm btn-info" onclick="replyOnTicket(this)" style="cursor:pointer;">
                                        <i class="fa fa-reply"></i> Reply
                                    </a>
                                </p>
                                <form id="AnswOnTicket" action="#" method="POST" autocomplete="off" style="display:none;">
                                    <input type="text" name="tID" value="<?php echo $Secure->SecureTxt($tInfo['id']); ?>" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Reply</label>
                                            <textarea id="Message" name="Message" required="" class="form-control" rows="5" placeholder="Reply .."></textarea>
                                        </div>
                                    </div> <br>
                                    <span class="btn btn-sm btn-primary" style="float:right;" onclick="AnswOnTicket()"><i class="fa fa-send"></i> Send</span>
                                </form>
                            </div>
                            <div class="col-md-5">
                                <div class="card">
                                    <h5 class="card-header">
                                        <i class="fa fa-info"></i> Ticket Informations
                                    </h5>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><small>Title:</small> <?php echo $Secure->SecureTxt($tInfo['Title']); ?><br>
                                                <p><small>Server Name:</small> <?php if($Secure->SecureTxt($tInfo['serverID']) == 'all') { echo 'All'; } else { echo @$Server->serverName($Secure->SecureTxt($tInfo['serverID'])); } ?><br>
                                                <p><small>Priority:</small> <span style="color:<?php echo $Secure->SecureTxt($Support->ticketPriority($tInfo['Priority'])['0']); ?>"><?php echo $Secure->SecureTxt($Support->ticketPriority($tInfo['Priority'])['1']); ?></span><br>
                                                <p><small>Status:</small> <span style="color:<?php echo $Secure->SecureTxt($Support->ticketStatus($tInfo['Status'])['0']); ?>"><?php echo $Secure->SecureTxt($Support->ticketStatus($tInfo['Status'])['1']); ?></span><br>
                                            </div>
                                        </div>
                                    </div>
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