<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  add_admin.php
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

// If not permission die me
if(!($Admin->AdminPermValid($Admin->AdminData()['id'], '4')) == true) {
	$Alert->SaveAlert('You have no acces.', 'error');
	header('Location: /admin/');
	die();
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
					<h2 class="h5 no-margin-bottom">Add new admin</h2>
				</div>
			</div>
			<section class="no-padding-top no-padding-bottom">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="block">
							<div class="block-body">
							<form class="form-horizontal" method="POST" autocomplete="off" action="/admin/process?newAdmin">
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Username *</label>
									<div class="col-sm-9">
										<input type="text" name="Username" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Password *</label>
									<div class="col-sm-9">
										<input type="password" name="Password" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Email *</label>
									<div class="col-sm-9">
										<input type="email" name="Email" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Name *</label>
									<div class="col-sm-9">
										<input type="text" name="Name" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Last Name *</label>
									<div class="col-sm-9">
										<input type="text" name="LastName" class="form-control" required="">
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
									<label class="col-sm-3 form-control-label">Rank *</label>
									<div class="col-sm-9">
										<select name="Rank" class="form-control mb-3 mb-3" required="">
											<option value="" selected="" disabled="">--select--</option>
											<option value="1">Support</option>
											<option value="2">Head Support</option>
											<option value="3">Administrator</option>
											<option value="4">Developer</option>
											<option value="5">Owner</option>
										</select>
									</div>
								</div> <div class="line"></div>
								<div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Permissions For:</label>
                                    <div class="col-sm-9">
                                        <input id="checkboxCustom2" type="checkbox" name="admin_perm[]" value="1" class="checkbox-template"><label for="checkboxCustom2">Mods</label>
                                        <br>
                                        <input id="checkboxCustom2" type="checkbox" name="admin_perm[]" value="2" class="checkbox-template"><label for="checkboxCustom2">Plugins</label>
                                        <br>
                                        <input id="checkboxCustom2" type="checkbox" name="admin_perm[]" value="3" class="checkbox-template"><label for="checkboxCustom2">Games</label>
                                        <br>
                                        <input id="checkboxCustom2" type="checkbox" name="admin_perm[]" value="4" class="checkbox-template"><label for="checkboxCustom2">Machines</label>
                                        <br>
                                        <input id="checkboxCustom2" type="checkbox" name="admin_perm[]" value="5" class="checkbox-template"><label for="checkboxCustom2">Workers</label>
                                    </div>
                                </div> <div class="line"></div>
								<div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Support for:</label>
                                    <div class="col-sm-9">
                                        <?php foreach ($Games->gameList()['Response'] as $g_k => $g_v) { ?>
                                        <input id="checkboxCustom2" type="checkbox" name="admin_supp[]" value="<?php echo $g_v['id']; ?>" class="checkbox-template"><label for="checkboxCustom2"><?php echo $Secure->SecureTxt($g_v['Name']); ?></label>
                                        <br>
                                        <?php } ?>
                                    </div>
                                </div>
								<div class="form-group row">
									<div class="col-sm-9 ml-auto">
										<button type="submit" class="btn btn-primary" style="float:right;">Create</button>
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