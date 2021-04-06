<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  servers.php
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

// Call All
$getCall 		= 'All';
// Online status
if (isset($GET['online'])) {
	$OnlineStatus 	= '1'; $getCall 	= 'Online';
} else if(isset($GET['offline'])) {
	$OnlineStatus 	= '0'; $getCall 	= 'Offline';
} else {
	$OnlineStatus 	= '';
}
// Paid Status
if (isset($GET['paid'])) {
	$PaidStatus 	= '1'; $getCall 	= 'Paid';
} else if(isset($GET['free'])) {
	$PaidStatus 	= '0'; $getCall 	= 'Free';
} else {
	$PaidStatus 	= '';
}
// Game
if (isset($GET['game'])) {
	$gameID = $Secure->SecureTxt($GET['game']);
} else {
	$gameID = '';
}

/*pre_r(@unserialize($Admin->AdminData('8')['AdminSupp']));
$AdminSupp = [1, 2, 3, 4, 5, 6, 7];
pre_r(serialize($AdminSupp));*/ 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>

    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="/assets/plugins/table/datatables.min.css?<?php echo time(); ?>">
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
					<h2 class="h5 no-margin-bottom"><?php echo $getCall; ?> Servers</h2>
				</div>
			</div>
			<section class="no-padding-top">
				<div class="container-fluid">
					<div class="row">
						<form action="/admin/servers" method="GET" autocomplete="off">
							<div class="form-group row">
								<label class="col-sm-3 form-control-label">Igra:</label>
								<div class="col-sm-9">
									<select name="game" onchange="this.form.submit()" class="form-control mb-3 mb-3" data-live-search="true">
										<option value="0" disabled selected>--Izaberite igru--</option>
										<?php foreach ($Games->gameList()['Response'] as $g_k => $g_v) { ?>
											<?php if (isset($GET['game']) && $gameID == $g_v['id']) {
												$selected = 'selected=""';
											} else {
												$selected = '';
											} ?>
											<option value="<?php echo $g_v['id']; ?>" <?php echo $selected; ?>><?php echo $Secure->SecureTxt($g_v['Name']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</form>

						<!-- Form Elements -->
						<div class="col-lg-12">
							<div class="block">
								<table id="serverList" class="table table-striped table-sm">
									<thead>
										<tr>
											<th>Name</th>
											<th>Ip:port</th>
											<th>Owner</th>
											<th>Mod</th>
											<th>Slots</th>
											<th>Expires</th>
											<th>Is Free</th>
										</tr>
									</thead>
									<tbody>
										<?php if(isset($gameID) && empty($gameID)) {
											foreach ($Servers->ServerList()['Response'] as $b_k => $b_v) { ?>
												<tr>
													<th><a href="/admin/server?id=<?php echo $b_v['id'] ?>"><?php echo $Secure->SecureTxt($b_v['Name']); ?></a></th>
													<th><?php echo $Box->boxByID($b_v['boxID'])['Host'].':'.$b_v['Port']; ?></th>
													<th><a href="/admin/user?id=<?php echo $b_v['userID']; ?>"><?php echo $User->UserFL($b_v['userID']); ?></a></th>
													<th><?php echo $Mods->getModByID($Server->serverByID($b_v['id'])['modID'])['Name']; ?></th>
													<th><?php echo $Secure->SecureTxt($b_v['Slot']); ?></th>
													<th><?php echo $Secure->SecureTxt($b_v['expiresFor']); ?></th>
													<th><?php echo $Server->isFree($b_v['id']); ?></th>
												</tr>
											<?php }
										} else {
											if ($Games->gameByID($gameID)['smName'] == 'fdl') {
												foreach ($Servers->ServerListFDL()['Response'] as $fdl_k => $fdl_v) { ?>
													<tr>
														<th><a href="/admin/fdl_view?id=<?php echo $fdl_v['id'] ?>&info"><?php echo $Secure->SecureTxt($fdl_v['Username'].':'.$fdl_v['Password']); ?></a></th>
														<th><?php echo $Box->boxByID($fdl_v['boxID'])['Host'].':21'; ?></th>
														<th><a href="/admin/user?id=<?php echo $fdl_v['userID']; ?>"><?php echo $User->UserFL($fdl_v['userID']); ?></a></th>
														<th>Fast Download</th>
														<th>n/a</th>
														<th><?php echo $Secure->SecureTxt($fdl_v['expiresFor']); ?></th>
														<th><?php echo $fdl_v['isFree']; ?></th>
													</tr>
												<?php }
											} else {
												foreach ($Servers->ServerListByGame($gameID)['Response'] as $b_k => $b_v) { ?>
													<tr>
														<th><a href="/admin/server?id=<?php echo $b_v['id'] ?>"><?php echo $Secure->SecureTxt($b_v['Name']); ?></a></th>
														<th><?php echo $Box->boxByID($b_v['boxID'])['Host'].':'.$b_v['Port']; ?></th>
														<th><a href="/admin/user?id=<?php echo $b_v['userID']; ?>"><?php echo $User->UserFL($b_v['userID']); ?></a></th>
														<th><?php echo $Mods->getModByID($Server->serverByID($b_v['id'])['modID'])['Name']; ?></th>
														<th><?php echo $Secure->SecureTxt($b_v['Slot']); ?></th>
														<th><?php echo $Secure->SecureTxt($b_v['expiresFor']); ?></th>
														<th><?php echo $Server->isFree($b_v['id']); ?></th>
													</tr>
												<?php }
											}
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/footer.php'); ?>
    <script type="text/javascript" charset="utf8" src="/assets/plugins/table/datatables.min.js"></script>
    <script type="text/javascript">
        /* Table */
        $(document).ready(function(){
            $('#serverList').DataTable();
        });
    </script>
</body>
</html>