<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  server.php
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

/* Get CPU and MEM info :: ps -U srv_1_4 -o %cpu,%mem */
// PHP seclib
set_include_path($_SERVER['DOCUMENT_ROOT'].'/core/inc/libs/phpseclib');
include('Net/SSH2.php');
// Box: Host : (IP)
$boxID      = $Server->serverByID($serverID)['boxID'];
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
// Connect
$SSH2 = new Net_SSH2($boxHost, $sshPort);
if (!$SSH2->login($boxUser, $boxPass)) {
    die('Login Failed');
}
/* View in live box (evry 1s refresh)
    -> watch -n 1 'ps -U srv_1_1_a5jf6 -o pid,uname,cmd,pmem,pcpu --sort=-pmem,-pcpu | head -15'
Send command for return usage; */
// CPU
$cpuUsage       = @$SSH2->exec('ps -U '.$serverUsername.' -o pcpu');
$SSH2->setTimeout(1);
// RAM
$ramUsage       = @$SSH2->exec('ps -U '.$serverUsername.' -o pmem');
$SSH2->setTimeout(1);
// Storage
$storageUsage   = @$SSH2->exec('du -sh -- /home/'.$serverUsername);
$SSH2->setTimeout(1);
$storageUsage_  = @$Secure->SecureTxt(str_replace('/home/'.$serverUsername, '', $storageUsage));
// Clear SSH2
unset($SSH2);
/* Explode returned usage */
// CPU
$cpuUsage = explode("\n", $cpuUsage);
// RAM
$ramUsage = explode("\n", $ramUsage);
/* Clear first line */
unset($cpuUsage[0]); unset($ramUsage[0]);
/* Get normal Usage number */
// CPU
$cpuUsage_  = $Server->getUsage($cpuUsage);
if($cpuUsage_ <= 10) {
    $cpuUsage_s = '0.1';
    $cpuUsage_c = '#3bb85d';
} else if($cpuUsage_ <= 20) {
    $cpuUsage_s = '0.2';
    $cpuUsage_c = '#3bb85d';
} else if($cpuUsage_ <= 30) {
    $cpuUsage_s = '0.3';
    $cpuUsage_c = '#3bb85d';
} else if($cpuUsage_ <= 40) {
    $cpuUsage_s = '0.4';
    $cpuUsage_c = '#3bb85d';
} else if($cpuUsage_ <= 50) {
    $cpuUsage_s = '0.5';
    $cpuUsage_c = '#3bb1d9';
} else if($cpuUsage_ <= 60) {
    $cpuUsage_s = '0.6';
    $cpuUsage_c = '#3bb1d9';
} else if($cpuUsage_ <= 70) {
    $cpuUsage_s = '0.7';
    $cpuUsage_c = '#ffd800';
} else if($cpuUsage_ <= 80) {
    $cpuUsage_s = '0.8';
    $cpuUsage_c = '#ffd800';
} else if($cpuUsage_ <= 90) {
    $cpuUsage_s = '0.9';
    $cpuUsage_c = '#d9434e';
} else if($cpuUsage_ <= 100) {
    $cpuUsage_s = '1';
    $cpuUsage_c = '#bd2130';
} else {
    $cpuUsage_s = '1';
    $cpuUsage_c = '#bd2130';
}
// RAM
$ramUsage_  = $Server->getUsage($ramUsage);
if($ramUsage_ <= 10) {
    $ramUsage_s = '0.1';
    $ramUsage_c = '#3bb85d';
} else if($ramUsage_ <= 20) {
    $ramUsage_s = '0.2';
    $ramUsage_c = '#3bb85d';
} else if($ramUsage_ <= 30) {
    $ramUsage_s = '0.3';
    $ramUsage_c = '#3bb85d';
} else if($ramUsage_ <= 40) {
    $ramUsage_s = '0.4';
    $ramUsage_c = '#3bb85d';
} else if($ramUsage_ <= 50) {
    $ramUsage_s = '0.5';
    $ramUsage_c = '#3bb1d9';
} else if($ramUsage_ <= 60) {
    $ramUsage_s = '0.6';
    $ramUsage_c = '#3bb1d9';
} else if($ramUsage_ <= 70) {
    $ramUsage_s = '0.7';
    $ramUsage_c = '#ffd800';
} else if($ramUsage_ <= 80) {
    $ramUsage_s = '0.8';
    $ramUsage_c = '#ffd800';
} else if($ramUsage_ <= 90) {
    $ramUsage_s = '0.9';
    $ramUsage_c = '#d9434e';
} else if($ramUsage_ <= 100) {
    $ramUsage_s = '1';
    $ramUsage_c = '#bd2130';
} else {
    $ramUsage_s = '1';
    $ramUsage_c = '#bd2130';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>

    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/head.php'); ?>
    <style>
        .canvasjs-chart-canvas {
            border-radius: 10px;
        }
    </style>
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
                            <div class="col-md-6">
                                <div>
                                    <p><strong class="pHeadTitle">Server Information</strong></p>
                                    <p><span>Server name</span> <strong><?php echo $Server->serverName($serverID); ?></strong></p>
                                    <p><span>Game</span> <strong><?php echo $Games->gameByID($Server->serverByID($serverID)['gameID'])['Name']; ?></strong></p>
                                    <p><span>Ip Address</span> <strong onclick="copyToClipboard('<?php echo $Server->ipAddress($serverID); ?>', this)"><?php echo $Server->ipAddress($serverID); ?> <i class="fa fa-copy"></i></strong></p>
                                    <p><span>Expires for</span> <strong><?php echo $Server->serverByID($serverID)['expiresFor']; ?></strong></p> <br>
                                
                                    <p><strong class="pHeadTitle">Server Configuration</strong></p>
                                    <p><span>Mod</span> <strong><?php echo $Secure->SecureTxt($Mods->getModByID($Server->serverByID($serverID)['modID'])['Name']); ?></strong></p>
                                    <p><span>Map</span> <strong><?php echo $Server->serverByID($serverID)['Map']; ?></strong></p>
                                    <p><span>Max Slot</span> <strong><?php echo $Server->serverByID($serverID)['Slot']; ?></strong></p>
                                    <p><span>Max FPS</span> <strong><?php echo $Server->serverByID($serverID)['fps']; ?></strong></p> <br>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="srvAction">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <p><strong class="pHeadTitle">WebFTP Shortcuts</strong></p>
                                            <div class="srvLinks">
                                                <?php if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'cs16') { ?>
                                                    <!-- Folders -->
                                                    <li><i class="fa fa-folder folder"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/cstrike&f=">cstrike</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/cstrike/addons/amxmodx/configs&f=">config</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/cstrike/addons/amxmodx/plugins&f=">plugins</a></li>
                                                    <hr style="margin:0;opacity:0;">
                                                    <!-- Files -->
                                                    <li><i class="fa fa-file file" style="color:#fff;"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/cstrike&f=server.cfg">server.cfg</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/cstrike/addons/amxmodx/configs&f=users.ini">users.ini</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/cstrike/addons/amxmodx/configs&f=plugins.ini">plugins.ini</a></li>
                                                <?php } else if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'samp') { ?>
                                                    <!-- Folders -->
                                                    <li><i class="fa fa-folder folder"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/scriptfiles&f=">scriptfiles</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/npcmodes&f=">npcmodes</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/gamemodes&f=">gamemodes</a></li>
                                                    <hr style="margin:0;opacity:0;">
                                                    <!-- Files -->
                                                    <li><i class="fa fa-file file" style="color:#fff;"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/&f=server.cfg">server.cfg</a></li>
                                                <?php } else if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'fivem') { ?>
                                                    <!-- Folders -->
                                                    <li><i class="fa fa-folder folder"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/alpine&f=">alpine</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/server-data&f=">server-data</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/cache&f=">cache</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/cache/files&f=">files</a></li>
                                                    <hr style="margin:0;opacity:0;">
                                                    <!-- Files -->
                                                    <li><i class="fa fa-file file" style="color:#fff;"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/server-data&f=server.cfg">server.cfg</a></li>
                                                <?php } else if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'mc') { ?>
                                                    <!-- Folders -->
                                                    <li><i class="fa fa-folder folder"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/alpine&f=">logs</a></li>
                                                    <hr style="margin:0;opacity:0;">
                                                    <!-- Files -->
                                                    <li><i class="fa fa-file file" style="color:#fff;"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/&f=server.properties">server.properties</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/&f=banned-ips.json">banned-ips.json</a></li>
                                                    <li><i class="fa fa-file file" style="color:#fff;"></i></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/&f=banned-players.json">banned-players.json</a></li>
                                                    <li><a href="/web_ftp?id=<?php echo $serverID; ?>&p=/&f=ops.json">ops.json</a></li>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <p><strong class="pHeadTitle">Server Action</strong></p>
                                            <div class="srvAction">
                                                <?php if ($Server->serverByID($serverID)['isStart'] == '1') { ?>
                                                    <li>
                                                        <a href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'restart')" class="btn btn-sm btn-info">
                                                            <i class="fa fa-refresh"></i> Restart
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'stop')" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-power-off"></i> Stop
                                                        </a>
                                                    </li>
                                                <?php } else { ?>
                                                    <li>
                                                        <a href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'restart')" class="btn btn-sm btn-success">
                                                            <i class="fa fa-play"></i> Start
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'reinstall')" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-refresh"></i> Reinstall
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--[ftp info]-->
                                <p <?php if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] == 'mc') { echo 'style="margin-top:43px;"'; } else { echo 'style="margin-top:43px;"'; } ?>><strong class="pHeadTitle">FTP Information</strong></p>
                                <p><span>Host</span> <strong onclick="copyToClipboard('<?php echo $Box->boxByID($Server->serverByID($serverID)['boxID'])['Host']; ?>', this)"><?php echo $Box->boxByID($Server->serverByID($serverID)['boxID'])['Host']; ?></strong></p>
                                <p><span>Port</span> <strong onclick="copyToClipboard('<?php echo $Box->boxByID($Server->serverByID($serverID)['boxID'])['ftpPort']; ?>', this)"><?php echo $Box->boxByID($Server->serverByID($serverID)['boxID'])['ftpPort']; ?></strong></p>
                                <p><span>Username</span> <strong onclick="copyToClipboard('<?php echo $Server->serverByID($serverID)['Username']; ?>', this)"><?php echo $Server->serverByID($serverID)['Username']; ?></strong></p>
                                <p><span>Password</span> <strong id="ftpChangePW" onclick="changeFTPpw('<?php echo $serverID; ?>')" title="Change FTP Password!"><i class="fa fa-refresh"></i></strong> <strong id="ftpPwShow" onclick="showFtpPw('<?php echo $serverID; ?>')" style="cursor:pointer;">[show password]</strong><strong id="ftpPwIs"></strong></p> <br>
                            </div>
                        </div>
                        <div class="srvInfor">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="row justify-content-between">
                                        <div class="col-12">
                                            <h3 class="pHeadTitle" style="border:none;text-align:center;">Server Usage</h3> <br><br>
                                        </div>
                                        <div class="col-3">
                                            <div id="ramUsage"></div>
                                            <div class="statsUsage1">
                                                <h4 class="ramUsage"><span id="ramUsage_">loading</span><i>%</i></h4>
                                                <p class="usagetitle">RAM</p>
                                            </div>
                                        </div>
                                        <div class="col-4" style="margin-top:-25px;">
                                            <div id="cpuUsage" style="width:90%;margin:0 auto;"></div>
                                            <div class="statsUsage">
                                                <h2 class="cpuUsage"><span id="cpuUsage_">loading</span><i>%</i></h2>
                                                <p class="usagetitle">CPU</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div id="storageUsage"></div>
                                            <div class="statsUsage1">
                                                <h4 class="storageUsage"><span id="storageUsage_">loading</span></h4>
                                                <p class="usagetitle">STORAGE</p>
                                            </div>
                                        </div>
                                    </div> <br>
                                </div>
                            </div>
                        </div>
                        <div class="srvInfor">
                            <div class="row">
                                <div class="col-md-12" style="border-radius:20px!important;">
                                    <div id="chartContainer" style="height:35vh;width:100%;"></div>
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
    <script src="/assets/js/progressbar.js" type="text/javascript" charset="utf-8"></script>
    <script src="//canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        /* RAM */
        var ramUsage = new ProgressBar.Circle('#ramUsage', {
            color: '#ffffff',
            trailColor: '#eee',
            trailWidth: 1,
            duration: 2000,
            easing: 'bounce',
            strokeWidth: 2,
            from: {color: '<?php echo $ramUsage_c; ?>', a:0},
            to: {color: '<?php echo $ramUsage_c; ?>', a:1},
                step: function(state, circle) {
                circle.path.setAttribute('stroke', state.color);
            }
        });
        ramUsage.animate(<?php echo $ramUsage_s; ?>);
        $('#ramUsage_').html('').html('<?php echo $ramUsage_; ?>');
        /* CPU */
        var cpuUsage = new ProgressBar.Circle('#cpuUsage', {
            color: '#ffffff',
            trailColor: '#eee',
            trailWidth: 1,
            duration: 2000,
            easing: 'bounce',
            strokeWidth: 2,
            from: {color: '<?php echo $cpuUsage_c; ?>', a:0},
            to: {color: '<?php echo $cpuUsage_c; ?>', a:1},
                step: function(state, circle) {
                circle.path.setAttribute('stroke', state.color);
            }
        });
        cpuUsage.animate(<?php echo $cpuUsage_s; ?>);
        $('#cpuUsage_').html('').html('<?php echo $cpuUsage_; ?>');
        /* Storage */
        var storageUsage = new ProgressBar.Circle('#storageUsage', {
            color: '#ffffff',
            trailColor: '#eee',
            trailWidth: 1,
            duration: 2000,
            easing: 'bounce',
            strokeWidth: 2,
            from: {color: '#3bb85d', a:0},
            to: {color: '#3bb85d', a:1},
            step: function(state, circle) {
                circle.path.setAttribute('stroke', state.color);
            }
        });
        storageUsage.animate(0.0);
        $('#storageUsage_').html('').html('<?php echo $storageUsage_; ?>');

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