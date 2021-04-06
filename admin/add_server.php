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

// Game
if (isset($GET['gameID'])) {
	$gameID = $Secure->SecureTxt($GET['gameID']);
} else {
	$gameID = '';
}

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
					<h2 class="h5 no-margin-bottom">Add new server</h2>
				</div>
			</div>
			<section class="no-padding-top no-padding-bottom">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="block">
							<div class="block-body">
							<form class="form-horizontal" method="GET" action="/admin/add_server">
								<div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Game</label>
									<div class="col-sm-9">
										<select name="gameID" class="js-example-basic-single form-control mb-3 mb-3" required="" onchange="this.form.submit()">
											<option value="" selected="" disabled="">--select--</option>
											<?php foreach ($Games->gameList()['Response'] as $g_k => $g_v) { ?>
												<option <?php if(isset($gameID) && $gameID == $g_v['id']) { echo 'selected'; } ?> value="<?php echo $g_v['id']; ?>"><?php echo $Secure->SecureTxt($g_v['Name']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div> <div class="line"></div>
							</form>
							<?php if(isset($gameID) && is_numeric($gameID) && !empty($gameID)) { ?>
								<form id="newServerForm" class="form-horizontal" method="POST" autocomplete="off" action="/admin/process?newServer">
									<input hidden name="gameID" value="<?php echo $gameID; ?>">
									<div class="line"></div>
									<div class="form-group row">
										<label class="col-sm-3 form-control-label">Machine</label>
										<div class="col-sm-9">
											<select name="boxID" class="js-example-basic-single form-control mb-3 mb-3" required="" onchange="getServerPort()">
												<option value="" selected="" disabled="">--select--</option>
												<?php foreach ($Box->boxList()['Response'] as $b_k => $b_v) { ?>
													<option value="<?php echo $b_v['id']; ?>"><?php echo $Secure->SecureTxt($b_v['Name']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div> <div class="line"></div>
									<div class="form-group row">
										<label class="col-sm-3 form-control-label">Client</label>
										<div class="col-sm-9">
											<select name="userID" class="js-example-basic-single form-control mb-3 mb-3" required="">
												<option value="" selected="" disabled="">--select--</option>
												<?php foreach ($User->userList()['Response'] as $u_k => $u_v) { ?>
													<option value="<?php echo $u_v['id']; ?>"><?php echo $User->getFullName($u_v['id']).' ('.$Secure->SecureTxt($u_v['Email'].')'); ?></option>
												<?php } ?>
											</select>
										</div>
									</div> <div class="line"></div>
									<?php if(!($Games->gameByID($gameID)['smName'] == 'fdl')) { ?>
										<div class="form-group row">
											<label class="col-sm-3 form-control-label">Mod</label>
											<div class="col-sm-9">
												<select name="modID" class="js-example-basic-single form-control mb-3 mb-3" required="">
													<option value="" selected="" disabled="">--select--</option>
													<?php foreach ($Mods->modsList()['Response'] as $m_k => $m_v) { 
														if($m_v['gameID'] == $gameID) { ?>
														<option value="<?php echo $m_v['id']; ?>"><?php echo $Secure->SecureTxt($m_v['Name']); ?></option>
													<?php } } ?>
												</select>
											</div>
										</div> <div class="line"></div>
										<div class="form-group row">
											<label class="col-sm-3 form-control-label">Server Name</label>
											<div class="col-sm-9">
												<input type="text" name="serverName" class="form-control" placeholder="New Server" required="">
											</div>
										</div> <div class="line"></div>
									<?php } ?>
									<?php if($Games->gameByID($gameID)['smName'] == "fdl") { ?>
										<div class="form-group row">
											<label class="col-sm-3 form-control-label">FDL Directory * (ne menjaj)</label>
											<div class="col-sm-9">
												<input type="text" name="serverDirectory" class="form-control" placeholder="Dir name in /var/www/html/fdl/user/" required="" value="/var/www/html/fdl/user/">
											</div>
										</div> <div class="line"></div>
									<?php } else { ?>
										<div class="form-group row">
											<label class="col-sm-3 form-control-label">Server Slots</label>
											<div class="col-sm-9">
												<input type="text" name="serverSlot" class="form-control" placeholder="32" required="">
											</div>
										</div> <div class="line"></div>
										<div class="form-group row">
											<label class="col-sm-3 form-control-label">Server Port</label>
											<div class="col-sm-9">
												<input id="newServerPort" type="text" name="serverPort" class="form-control" placeholder="27015" required="">
											</div>
										</div> <div class="line"></div>
									<?php } ?>
									<div class="form-group row">
										<label class="col-sm-3 form-control-label">Expires</label>
										<div class="col-sm-9">
											<input type="date" name="serverIstice" class="form-control" required="">
										</div>
									</div> <div class="line"></div>
									<div class="form-group row">
										<div class="col-sm-9 ml-auto">
											<button type="submit" class="btn btn-primary" style="float:right;">Kreiraj</button>
										</div>
									</div>
								</form>
							<?php } ?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/footer.php'); ?>
</body>
</html>