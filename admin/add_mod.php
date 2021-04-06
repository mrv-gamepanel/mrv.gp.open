<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  mod.php
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

/*// If not permission die me
if(!($Admin->AdminPermValid($Admin->AdminData()['id'], '1')) == true) {
	$Alert->SaveAlert('You have no acces.', 'error');
	header('Location: /admin/');
	die();
}*/

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
					<h2 class="h5 no-margin-bottom">Add Mod</h2>
				</div>
			</div>
			<section class="no-padding-top no-padding-bottom">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="block">
							<div class="block-body">
                            <form class="form-horizontal" method="POST" autocomplete="off" action="/admin/process?addMod">
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Name</label>
									<div class="col-sm-9">
										<input type="text" name="modName" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Game</label>
									<div class="col-sm-9">
                                        <select name="modGame" class="form-control mb-3 mb-3" required="">
											<option value="" selected="" disabled="">--select--</option>
											<?php foreach ($Games->gameList()['Response'] as $g_k => $g_v) { ?>
												<option value="<?php echo $g_v['id']; ?>"><?php echo $Secure->SecureTxt($g_v['Name']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Directory</label>
									<div class="col-sm-9">
										<input type="text" name="modDir" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Map</label>
									<div class="col-sm-9">
										<input type="text" name="modMap" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Start Command</label>
									<div class="col-sm-9">
										<input type="text" name="modCommand" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Status</label>
									<div class="col-sm-9">
                                        <select name="modStatus" class="form-control mb-3 mb-3" required="">
											<option value="" selected="" disabled="">--select--</option>
                                            <option value="1">Public</option>
                                            <option value="0">Private</option>
                                        </select>
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<div class="col-sm-9 ml-auto">
										<button type="submit" class="btn btn-primary" style="float:right;"><i class="fa fa-save"></i> Save</button>
									</div>
								</div>
							</form>
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