<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  boxes.php
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
if(!($Admin->AdminPermValid($Admin->AdminData()['id'], '3')) == true) {
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
					<h2 class="h5 no-margin-bottom">Boxes</h2>
				</div>
			</div>
			<section class="no-padding-top">
				<div class="container-fluid">
					<div class="row">
						<!-- Form Elements -->
							<div class="col-lg-12">
								<div class="block">
									<table id="boxList" class="table table-striped table-sm">
										<thead>
											<tr>
												<th>Name</th>
												<th>Host</th>
												<th>Game</th>
												<th>Location</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($Box->boxList()['Response'] as $b_k => $b_v) { ?>
											<tr>
												<th><a href="box?id=<?php echo $b_v['id'] ?>"><?php echo $Secure->SecureTxt($b_v['Name']); ?></a></th>
												<th><?php echo $Secure->SecureTxt($b_v['Host']); ?></th>
												<th><?php if($Secure->SecureTxt($b_v['gameID']) == 1) {
													echo 'Counter Strike 1.6';
												} else if($Secure->SecureTxt($b_v['gameID']) == 2) {
													echo 'SA:MP';
												} else {
													echo 'Undefined';
												} ?></th>
												<th><?php echo $Secure->SecureTxt($b_v['boxLocation']); ?></th>
												<th>(kevia)</th>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
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
            $('#boxList').DataTable();
        });
    </script>
</body>
</html>