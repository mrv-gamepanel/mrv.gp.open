<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  order.php
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

// Get Order ID
$orderID = @$Secure->SecureTxt($GET['id']);
if(!isset($orderID) || empty($orderID) || !is_numeric($orderID)) {
    header('Location: /billing');
    die('Insert order ID');
}
// Is valid order?
if (empty($Billing->orderByID($orderID)['id'])) {
    header('Location: /billing');
    die('Insert order ID');
}
// Remove this Order
if (isset($GET['removeOrder']) && is_numeric($Secure->SecureTxt($GET['removeOrder'])) && $Secure->SecureTxt($GET['removeOrder']) == '1') {
    // Remove this
    if (!($Billing->removeOrder($orderID, $User->UserData()['id'])) == false) {
        header('Location: /billing');
        die();
    } else {
        header('Location: /billing');
        die();
    }
}
// My MRV Cash
$myCash = (int) @$User->getUserCash($User->UserData()['id'], 'db');
// Order price
$Price  = (int) str_replace('&euro;', '', @$Billing->orderByID($orderID)['Price']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order #<?php echo $orderID; ?> | <?php echo $Site->SiteConfig()['site_name']; ?></title>
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
                        <i class="fa fa-list"></i> Order #<?php echo $orderID; ?>
                    </h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p>Game: <b><?php echo $Secure->SecureTxt($Games->gameByID($Billing->orderByID($orderID)['gameID'])['Name']); ?></b> | Mod: <b><?php echo $Secure->SecureTxt($Mods->getModByID($Billing->orderByID($orderID)['modID'])['Name']); ?></b></p>
                                                <p>Server Name: <b><?php echo $Secure->SecureTxt($Billing->orderByID($orderID)['serverName']); ?></b></p>
                                                <p>Slots: <b><?php echo $Secure->SecureTxt($Billing->orderByID($orderID)['Slots']); ?></b></p>
                                                <?php if(isset($Games->gameByID($Billing->orderByID($orderID)['gameID'])['smName']) && $Games->gameByID($Billing->orderByID($orderID)['gameID'])['smName'] == 'mc' || $Games->gameByID($Billing->orderByID($orderID)['gameID'])['smName'] == 'fivem') { ?>
                                                    <p>Ram: <b><?php empty($Billing->orderByID($orderID)['gb']) ? $r = '--auto--' : $r = (int) $Billing->orderByID($orderID)['gb'].' GB'; echo $r; ?></b></p>
                                                <?php } ?>
                                                <p>Location: <b><?php echo $Secure->SecureTxt($Billing->orderByID($orderID)['Location']); ?></b></p>
                                                <p>Month<small>/s</small>: <b><?php echo $Secure->SecureTxt($Billing->orderByID($orderID)['Months']); ?></b></p>
                                                <p>Status: <b><?php echo $Billing->orderStatus($Billing->orderByID($orderID)['orderStatus']); ?></b> | Total: <b><?php echo $Secure->SecureTxt($Price); ?></b> &euro;</p>
                                                <p>Created by: <b><?php echo $Secure->SecureTxt($Billing->orderByID($orderID)['orderDate']); ?></b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php if($myCash >= $Price) {
                                                    if ($Billing->orderByID($orderID)['orderStatus'] == '1') { ?>
                                                        <a class="btn btn-success btn-lg btn-block" href="/install?id=<?php echo $orderID; ?>">
                                                            <i class="fa fa-cog"></i> Instaliraj server
                                                        </a>
                                                    <?php } else if ($Billing->orderByID($orderID)['orderStatus'] == '2') { ?>
                                                        <a class="btn btn-info btn-lg btn-block" href="javascript:;">
                                                            <i class="fa fa-play"></i> Server installed!
                                                        </a>
                                                    <?php }
                                                } else {
                                                    if ($Billing->orderByID($orderID)['orderStatus'] == '1') { ?>
                                                        <a class="btn btn-success btn-lg btn-block" href="/_buy/paypal/paypal.php?oid=<?php echo $orderID; ?>&action=process">
                                                            <i class="fa fa-play"></i> Pay with PayPal
                                                        </a> <br>
                                                        <div class="buy_sms" style="text-align:center;">
                                                            <a id="fmp-button" href="javascript:;" rel="641a84c12db273e1952bd6240d378ca9/<?php echo $User->UserData()['id']; ?>">
                                                                <img src="//assets.fortumo.com/fmp/fortumopay_150x50_red.png" alt="Mobile Payments by Fortumo" style="width:50%;" />
                                                            </a>
                                                        </div> <br>
                                                        <a class="btn btn-danger btn-lg btn-block" href="/order?id=<?php echo $orderID; ?>&removeOrder=1">
                                                            <i class="fa fa-trash"></i> Remove Order
                                                        </a>
                                                    <?php }
                                                } ?>
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
    <!--[fortumo]-->
    <script src='//assets.fortumo.com/fmp/fortumopay.js' type='text/javascript'></script>
</body>
</html>