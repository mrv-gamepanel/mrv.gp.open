<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  index.php
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
    <div class="container mt150">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <h5 class="card-header"><i class="fa fa-info-circle"></i> News</h5>
                            <div class="card-body">
                                <?php foreach ($News->getAllNews() as $n_k => $n_v) { ?>
                                    <strong><i class="fa fa-angle-double-right"></i> <?php echo $Secure->SecureTxt($n_v['Title']); ?></strong>
                                    <blockquote class="blockquote mb-0">
                                        <p><?php echo nl2br($Secure->SecureTxt($n_v['Text'])); ?></p>
                                        <footer class="blockquote-footer"><i class="fa fa-user"></i> <?php echo $Admin->getFullName($n_v['userID']); ?> | <cite title="TimeAndDate"><i class="fa fa-clock-o"></i> <?php echo $Secure->SecureTxt($n_v['Date']); ?></cite></footer>
                                    </blockquote> <hr>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <h5 class="card-header"><i class="fa fa-pie-chart"></i> Stats</h5>
                            <div class="card-body">
                                <p>MRV Cash: <b style="float:right"><?php echo $Secure->pNormalMoney($User->UserData()['mrvCash'], 'EUR'); ?></b></p>
                                <p>MRV Ticket<small>/s</small>: <b style="float:right"><?php echo $Support->ticketsList($User->UserData()['id'])['Count']; ?></b></p>
                                <p>MRV Server<small>/s</small>: <b style="float:right"><?php echo $Servers->serversByUser($User->UserData()['id'])['Count']; ?></b></p>
                                <p>MRV Order<small>/s</small>: <b style="float:right"><?php echo $Billing->BillingsByUser($User->UserData()['id'])['Count']; ?></b></p>
                                <!-- <p>My best server: <b>test</b></p> -->
                            </div>
                        </div> <br>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <h5 class="card-header"><i class="fa fa-info"></i> Notes</h5>
                            <div class="card-body">
                                <p>MRV Cash: <b style="float:right"><?php echo $Secure->pNormalMoney($User->UserData()['mrvCash'], 'EUR'); ?></b></p>
                                <p>MRV Ticket<small>/s</small>: <b style="float:right"><?php echo $Support->ticketsList($User->UserData()['id'])['Count']; ?></b></p>
                                <p>MRV Server<small>/s</small>: <b style="float:right"><?php echo $Servers->serversByUser($User->UserData()['id'])['Count']; ?></b></p>
                                <p>MRV Order<small>/s</small>: <b style="float:right"><?php echo $Billing->BillingsByUser($User->UserData()['id'])['Count']; ?></b></p>
                                <!-- <p>My best server: <b>test</b></p> -->
                            </div>
                        </div> <br>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
</body>
</html>