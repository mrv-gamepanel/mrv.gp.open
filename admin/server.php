<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  add_server.php
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

/* -Server graph- */
$playersmax = $Server->serverByID($serverID)['Slot'];
// Graph 12h
$graph12h = @unserialize($Server->serverByID($serverID)['Graph12']);

// pre_r($graph12h);

// Fix Json response;
$jsonResp = json_encode($graph12h, JSON_NUMERIC_CHECK);
$jsonResp = str_replace('[', '', $jsonResp);
$jsonResp = str_replace(']', '', $jsonResp);
$jsonResp = '['.$jsonResp.']';


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
					<form id="searchForm" action="/admin/search" method="GET">
						<div class="form-group">
							<input type="search" name="q" placeholder="What are you searching for...">
							<button class="submit">Search</button>
						</div>
					</form>
				</div>
			</div>
			<div class="container-fluid d-flex align-items-center justify-content-between">
				<div class="navbar-header">
				<a href="/admin/home" class="navbar-brand">
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
					<h2 class="h5 no-margin-bottom">Server Info</h2>
				</div>
			</div>
			<section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <?php include('public/servernav.php'); ?>
                        <div class="col-lg-6">
                            <div class="block">
                                <div class="title"><strong>Server Info</strong></div>
                                <div class="table-responsive"> 
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <td>Server Name:</td>
                                                <td><strong><?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Name']); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>IP Adress:</td>
                                                <td><strong><?php echo $srvIP; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Game</td>
                                                <td><strong><?php echo $Secure->SecureTxt($Games->gameByID($Server->serverByID($serverID)['gameID'])['Name']); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td><strong><?php echo $Server->serverStatus($serverID); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="block">
                                <div class="title"><strong>FTP Informations</strong></div>
                                <div class="table-responsive"> 
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <td>Host:</td>
                                                <td><strong><?php echo $Server->ipOnly($serverID); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Username</td>
                                                <td><strong><?php echo $Server->serverByID($serverID)['Username']; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Password</td>
                                                <td><strong><?php echo $Server->serverByID($serverID)['Password']; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Port:</td>
                                                <td><strong>21</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong>Online Informations</strong></div>
                                <div class="table-responsive"> 
                                <table class="table table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <td>Server Name:</td>
                                            <td><strong><?php echo @$Secure->SecureTxt($serverInfo['gq_hostname']); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>Online Status:</td>
                                            <td><strong><?php echo @$Server->isOnline($serverInfo['gq_online']); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>Map:</td>
                                            <td><strong><?php echo @$Secure->SecureTxt($serverInfo['gq_mapname']); ?></strong></td>
                                        </tr>
                                        <?php if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'cs16') { ?>
                                            <tr>
                                                <td>Next Map:</td>
                                                <td><strong><?php echo @$Secure->SecureTxt($serverInfo['amx_nextmap']); ?></strong></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td>Players:</td>
                                            <td><strong><?php echo @$Secure->SecureTxt($serverInfo['gq_numplayers']).'/'.$Server->serverByID($serverID)['Slot']; ?></strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong>Server graph</strong></div>
                                <div class="table-responsive"> 
                                    <div id="chartContainer" style="height:35vh;width:100%;"></div>
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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart('chartContainer', {
                backgroundColor: '#1a1c24',
                title: {
                    text: 'Graph',
                    fontColor: '#fff',
                },
                axisX: {
                    labelFontColor: '#fff',
                },
                axisY: {
                    title: 'Players',
                    minimum: 0,
                    maximum: <?php echo $playersmax; ?>,
                    titleFontColor: '#fff',
                    labelFontColor: '#fff',
                },
                data: [{
                    type: 'spline',
                    markerSize: 5,
                    dataPoints: <?php echo $jsonResp; ?>
                }]
            });
            chart.render();
        }
    </script>
</body>
</html>