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

$serverID = $GET['id'];

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
	header('Location: /'.$admDir.'/');
	die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>

    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/'.$admDir.'/public/head.php'); ?>
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
					<h2 class="h5 no-margin-bottom">Edit server</h2>
				</div>
			</div>
			<section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <?php include('public/servernav.php'); ?>
						<div class="col-lg-12">
							<div class="block">
                                <div class="block-body">
                                    <form class="form-horizontal" method="POST" autocomplete="off" action="/admin/process?editServer">
                                        <input hidden name="serverID" value="<?php echo $Secure->SecureTxt($GET['id']); ?>">
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="serverName" class="form-control" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Name']); ?>" required="">
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Port</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="serverPort" class="form-control" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Port']); ?>" required="">
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Map</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="serverMap" class="form-control" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Map']); ?>" required="">
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Slots</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="serverSlots" class="form-control" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Slot']); ?>" required="">
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">FPS</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="serverFPS" class="form-control" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['fps']); ?>" required="">
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Expires</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="serverExpires" class="form-control" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['expiresFor']); ?>" required="">
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Username</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="serverUsername" class="form-control" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Username']); ?>" required="">
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Passowrd</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="serverPassword" class="form-control" value="<?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Password']); ?>" required="">
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Status</label>
                                            <div class="col-sm-9">
                                                <select name="serverStatus" class="form-control mb-3 mb-3" required="">
                                                    <option <?php if($Secure->SecureTxt($Server->serverByID($serverID)['Status']) == 1) { echo 'selected'; } ?> value="0">Active</option>
                                                    <option <?php if($Secure->SecureTxt($Server->serverByID($serverID)['Status']) == 2) { echo 'selected'; } ?> value="1">Inactive</option>
                                                    <option <?php if($Secure->SecureTxt($Server->serverByID($serverID)['Status']) == 3) { echo 'selected'; } ?> value="2">Suspended</option>
                                                </select>
                                            </div>
                                        </div> <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Is Free</label>
                                            <div class="col-sm-9">
                                                <select name="serverStatus" class="form-control mb-3 mb-3" required="">
                                                    <option <?php if($Secure->SecureTxt($Server->serverByID($serverID)['Status']) == 0) { echo 'selected'; } ?> value="0">No</option>
                                                    <option <?php if($Secure->SecureTxt($Server->serverByID($serverID)['Status']) == 1) { echo 'selected'; } ?> value="1">Yes</option>
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
                        <?php include('public/serveroptions.php'); ?>
                    </div>
                </div>
            </section>
		</div>
	</div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/footer.php'); ?>
</body>
</html>