<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  install.php
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
if (empty(@$Billing->orderByID($orderID)['id'])) {
    header('Location: /billing');
    die('Insert order ID');
}
// Order info
$orderInfo = @$Billing->orderByID($orderID);

/* Get Server information */
$boxInfo        = @$Box->getRandomBoxByLocation($orderInfo['Location']);
$userID         = @$User->UserData()['id'];
$boxID          = @$boxInfo['id'];
$gameID         = @$Secure->SecureTxt($orderInfo['gameID']);
$modID          = @$Secure->SecureTxt($orderInfo['modID']);
$serverName     = @$Secure->SecureTxt($orderInfo['serverName']);
$fdlServer      = false; // Defautl for Not FDL;
if ($Games->gameByID($gameID)['smName'] == 'cs16') {
    for($Port = 27015; $Port <= 29999; $Port++) {
        if (empty($Server->isServerValid($boxID, $Port)['id'])) { // CS 1.6
            $sPort = $Port; // Port je slobodan!
            break;
        }
    }
} else if ($Games->gameByID($gameID)['smName'] == 'samp') {
    for($Port = 7777; $Port <= 9999; $Port++) {
        if (empty($Server->isServerValid($boxID, $Port)['id'])) { // SAMP
            $sPort = $Port; // Port je slobodan!
            break;
        }
    }
} else if ($Games->gameByID($gameID)['smName'] == 'fivem') { // fiveM
    for($Port = 30120; $Port <= 39120; $Port++) {
        if (empty($Server->isServerValid($boxID, $Port)['id'])) {
            $sPort = $Port; // Port je slobodan!
            break;
        }
    }
} else if ($Games->gameByID($gameID)['smName'] == 'csgo') { // CS:GO
    for($Port = 27015; $Port <= 29999; $Port++) {
        if (empty($Server->isServerValid($boxID, $Port)['id'])) {
            $sPort = $Port; // Port je slobodan!
            break;
        }
    }
} else if ($Games->gameByID($gameID)['smName'] == 'mc') { // Minecraft
    for($Port = 25565; $Port <= 25999; $Port++) {
        if (empty($Server->isServerValid($boxID, $Port)['id'])) {
            $sPort = $Port; // Port je slobodan!
            break;
        }
    }
} else if ($Games->gameByID($gameID)['smName'] == 'fdl') { // Fast Download
    $fdlServer = true;
} else {
    header('Location: /billing');
    die('Insert order ID');
}
$serverSlot     = @$Secure->SecureTxt($orderInfo['Slots']);
// Server istice
$expiresFor     = date('Y-m-d',  strtotime('+1 month'));
// Rand_
$rLfUser        = @$Secure->randKeyForLink(5); // 5 exp: (fAf2g)
// Username
if (empty($Server->myLastSrvID($userID)['Username'])) {
    $serverUsername = 'srv_'.$userID.'_1_'.$rLfUser;
} else {
    $gSv = explode('_', $Server->myLastSrvID($userID)['Username']);
    $serverUsername = $gSv[0].'_'.$userID.'_'.($gSv[2]+1).'_'.$rLfUser;
    if (!empty($Server->findServerUname($serverUsername)['id'])) {
        $serverUsername = $gSv[0].'_'.$userID.'_'.($gSv[2]+2).'_'.$rLfUser;
    }
}
// Password
$serverPassword = $Secure->RandKey(8);
$Note           = 'Auto install by Client';
$createdBy      = 'auto_install';
/////////////////////////////////
if(empty($boxID) || empty($gameID) || empty($userID) || empty($modID) || empty($serverName) || empty($serverSlot) || empty($Port) || empty($expiresFor)) {
    die('Sva polja moraju biti popunjena!');
}
/* Add server */
if (!($Server->createServer($boxID, $gameID, $modID, $serverSlot, $Port, $serverUsername, $serverPassword, $userID, $serverName, $expiresFor)) == false) {
    // Update MRV Cash to my profile;
    $nMRVcash = $User->getUserCash($User->UserData()['id'], 'db') - $Secure->SecureTxt($orderInfo['Price']); // MRV Cash + New Price order;
    if (!($User->updateUserCash($nMRVcash, $User->UserData()['id'])) == false) {
        // Update Status;
        $Order->upOrderStatus('2', $User->UserData()['id'], $orderID); // Update Status   
    }
    $Alert->SaveAlert('Success!', 'success');
    header('Location: /servers');
    die();
} else {
    $Alert->SaveAlert('Wrong :(', 'error');
    header('Location: /servers');
    die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order #<?php echo $orderID; ?> | <?php echo $Site->SiteConfig()['site_name']; ?></title>
    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/head.php'); ?>
</head>
<body>
    <h3><i class="fa fa-cog"></i> in progress, please wait..</h3>
</body>
</html>