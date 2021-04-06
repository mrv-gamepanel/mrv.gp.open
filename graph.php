<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  graph.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//////////////////////////

// If do not login;
if (!($User->IsLoged()) == true) {
    die();
}

/* Server ID */
if (isset($GET['id'])) {
    $serverID = $Secure->SecureTxt($GET['id']);
    // IS empty id
    if (empty($serverID) || !is_numeric($serverID)) {
        die();
    }
    // Is Valid server
    if (empty($Server->serverByID($serverID)['id'])) {
        die();
    }
    // It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        die();
    }
} else {
    die();
}
$playersmax = $Server->serverByID($serverID)['Slot'];
if ($playersmax <= 30) {
	$playersmin = '0'; 
	$playersmax = '30';
} else {
	$playersmin = '0'; 
	$playersmax = $playersmax;
}

$fullDay 	= Array(
	'0'  => Array( 'y' => 20, 'label' => date('H:i') ),
	'1'  => Array( 'y' => 30, 'label' => date('H:i') ),
	'2'  => Array( 'y' => 10, 'label' => date('H:i') ),
	'3'  => Array( 'y' => 30, 'label' => date('H:i') ),
	'4'  => Array( 'y' => 30, 'label' => date('H:i') ),
	'5'  => Array( 'y' => 20, 'label' => date('H:i') ),
	'6'  => Array( 'y' => 30, 'label' => date('H:i') ),
	'7'  => Array( 'y' => 7, 'label' => date('H:i') ),
	'8'  => Array( 'y' => 30, 'label' => date('H:i') ),
	'9'  => Array( 'y' => 10, 'label' => date('H:i') ),
	'10' => Array( 'y' => 30, 'label' => date('H:i') ),
	'11' => Array( 'y' => 30, 'label' => date('H:i') ),
);

// pre_r($fullDay);

// Fix Json response;
$jsonResp = json_encode($fullDay, JSON_NUMERIC_CHECK);
$jsonResp = str_replace('[', '', $jsonResp);
$jsonResp = str_replace(']', '', $jsonResp);
$jsonResp = '['.$jsonResp.']';

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Test</title>
</head>
	<body>
		<div id="chartContainer" style="height:370px;width:100%;"></div>
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
		<script>
			window.onload = function () {
				var chart = new CanvasJS.Chart('chartContainer', {
					title: {
						text: 'Graph [12h]'
					},
					axisY: {
						title: 'Players',
						minimum: 0,
						maximum: 32,
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