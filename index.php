<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  index.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

/* Game list */
function gameList() {
	global $DataBase;

	$DataBase->Query("SELECT * FROM `games` WHERE `Status` = '1'");
	$DataBase->Execute();

	return $DataBase->ResultSet();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/home/head.php'); ?>
    <link href="/assets/plugins/jqvmap-master/dist/jqvmap.css" media="screen" rel="stylesheet" type="text/css"/>
</head>
<body>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/home/header.php'); ?>
	<div class="choose-games">
		<div class="container">
			<h1 class="text-center">Choose from our popular<br/>game servers</h1>
			<div class="row justify-content-center">
				<?php foreach (gameList() as $g_k => $g_v) { ?>
					<div class="col-sm-6 col-md-4 col-lg-3">
						<div class="hover">
							<div class="card" onclick="goTo('/neworder?orderGame=<?php echo $g_v['id']; ?>')">
	    						<img class="card-img-top" src="<?php echo $Secure->SecureTxt($g_v['bg_img']); ?>" <?php if(isset($g_v['id']) && @$Games->gameByID($g_v['id'])['smName'] == 'fdl') { ?>style="height:158px;"<?php } ?>>
	                            <img class="img-hover" style="width: 20%;" src="<?php echo $Secure->SecureTxt($g_v['Icon']); ?>">
	    						<div class="card-header">
	    							<a href="/neworder?orderGame=<?php echo $g_v['id']; ?>"><i class="fas fa-chevron-double-right"></i> <?php echo $Secure->SecureTxt($g_v['Name']); ?></a>
	    						</div>
	    					</div>
	    				</div>
					</div>
				<?php } ?>
            </div>
		</div>
	</div>
    <div class="features">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="media">
                        <div class="media-icon purple mr-3">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="mt-0">Instant Setup</h4>
                            <p>Absolutely no waiting timer for our game servers.Our dedicaded servers are yours to  use once your payment is processed.What’s more? Set up in as little as 5 minutes and start gaming instanly.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="media">
                        <div class="media-icon green mr-3">
                            <i class="fa fa-support"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="mt-0">24/7 Support</h4>
                            <p>By offering round the clock support , we ensure that your receive tehnical assistance anytime you need it.You’re always welcome to reach out to us with any problems you might have or just questions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="media">
                        <div class="media-icon orange mr-3">
                            <i class="fa fa-rocket"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="mt-0">99% Uptime</h4>
                            <p>We know how vital time is when playing hosted games.This is exactly why we offer top notch server infractucture.Enjoy uninterrupted single or multiplayer games at any time of the day all years round.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="media">
                        <div class="media-icon pink mr-3">
                            <i class="fa fa-server"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="mt-0">Ulimited Storage</h4>
                            <p>Keep your game files safe and easily accessible with our cloud storage.Get no caps on the amount of data you can store.Best of all, you can access your files from any location or device.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
    	<div class="row">
    		<div class="col-12">
    			<h2 class="text-white">OUR AWESOME LOCATION/<small>s</small></h2>
   				<p class="text-white">Click on a blue highlighted location to find out more such as latency/ping & hardware specs.</p>
    		</div>
    	</div>
    </div>

    <div id="vmap" style="width:100%;height:400px;"></div>
      
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/home/footer.php'); ?>
    <div id="target" style="display:none;"></div>
    <script src="/assets/plugins/ping.js-master/dist/ping.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/jqvmap-master/dist/jquery.vmap.js"></script>
    <script type="text/javascript" src="/assets/plugins/jqvmap-master/dist/maps/jquery.vmap.world.js" charset="utf-8"></script>
    <script>
    	// Country code;
    	var sample_data = {"af":"16.63","al":"11.58","dz":"158.97","ao":"85.81","ag":"1.1","ar":"351.02","am":"8.83","au":"1219.72","at":"366.26","az":"52.17","bs":"7.54","bh":"21.73","bd":"105.4","bb":"3.96","by":"52.89","be":"461.33","bz":"1.43","bj":"6.49","bt":"1.4","bo":"19.18","ba":"16.2","bw":"12.5","br":"2023.53","bn":"11.96","bg":"44.84","bf":"8.67","bi":"1.47","kh":"11.36","cm":"21.88","ca":"1563.66","cv":"1.57","cf":"2.11","td":"7.59","cl":"199.18","cn":"5745.13","co":"283.11","km":"0.56","cd":"12.6","cg":"11.88","cr":"35.02","ci":"22.38","hr":"59.92","cy":"22.75","cz":"195.23","dk":"304.56","dj":"1.14","dm":"0.38","do":"50.87","ec":"61.49","eg":"216.83","sv":"21.8","gq":"14.55","er":"2.25","ee":"19.22","et":"30.94","fj":"3.15","fi":"231.98","fr":"2555.44","ga":"12.56","gm":"1.04","ge":"11.23","gh":"18.06","gr":"305.01","gd":"0.65","gt":"40.77","gn":"4.34","gw":"0.83","gy":"2.2","ht":"6.5","hn":"15.34","hk":"226.49","hu":"132.28","is":"12.77","in":"1430.02","id":"695.06","ir":"337.9","iq":"84.14","ie":"204.14","il":"201.25","it":"2036.69","jm":"13.74","jp":"5390.9","jo":"27.13","kz":"129.76","ke":"32.42","ki":"0.15","kr":"986.26","undefined":"5.73","kw":"117.32","kg":"4.44","la":"6.34","lv":"23.39","lb":"39.15","ls":"1.8","lr":"0.98","ly":"77.91","lt":"35.73","lu":"52.43","mk":"9.58","mg":"8.33","mw":"5.04","my":"218.95","mv":"1.43","ml":"9.08","mt":"7.8","mr":"3.49","mu":"9.43","mx":"1004.04","md":"5.36","mn":"5.81","me":"3.88","ma":"91.7","mz":"10.21","mm":"35.65","na":"11.45","np":"15.11","nl":"770.31","nz":"138","ni":"6.38","ne":"5.6","ng":"206.66","no":"413.51","om":"53.78","pk":"174.79","pa":"27.2","pg":"8.81","py":"17.17","pe":"153.55","ph":"189.06","pl":"438.88","pt":"223.7","qa":"126.52","ro":"158.39","ru":"1476.91","rw":"5.69","ws":"0.55","st":"0.19","sa":"434.44","sn":"12.66","rs":"38.92","sc":"0.92","sl":"1.9","sg":"217.38","sk":"86.26","si":"46.44","sb":"0.67","za":"354.41","es":"1374.78","lk":"48.24","kn":"0.56","lc":"1","vc":"0.58","sd":"65.93","sr":"3.3","sz":"3.17","se":"444.59","ch":"522.44","sy":"59.63","tw":"426.98","tj":"5.58","tz":"22.43","th":"312.61","tl":"0.62","tg":"3.07","to":"0.3","tt":"21.2","tn":"43.86","tr":"729.05","tm":0,"ug":"17.12","ua":"136.56","ae":"239.65","gb":"2258.57","us":"14624.18","uy":"40.71","uz":"37.72","vu":"0.72","ve":"285.21","vn":"101.99","ye":"30.02","zm":"15.69","zw":"5.57","gl":0,"br":0,"so":0,"kp":0,"nc":0,"fk":0,"pf":0,"cu":0,"gf":0};
    	// Map
		jQuery(document).ready(function () {
			jQuery('#vmap').vectorMap({
				map: 'world_en',
				backgroundColor: '#1a1c24',
				color: '#4b8cdc',
				hoverOpacity: 0.7,
				selectedColor: '#4b8cdc',
				enableZoom: false,
				showTooltip: true,
				scaleColors: ['#262933'],
				values: sample_data,
				normalizeFunction: 'polynomial'
			});
		});
		/* Show popup */
		$(function($){
			$('#jqvmap1_de').click(function() {
				alert('Uskoro');
		    });
		});
    </script>
</body>
</html>