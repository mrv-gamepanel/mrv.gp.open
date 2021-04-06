<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  index.php
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
					<h2 class="h5 no-margin-bottom">Dashboard</h2>
				</div>
			</div>
			<section class="no-padding-top no-padding-bottom">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3 col-sm-6">
							<div class="statistic-block block">
								<div class="progress-details d-flex align-items-end justify-content-between">
									<div class="title">
										<div class="icon"><i class="fa fa-gamepad"></i></div><strong>Servers</strong>
									</div>
									<div class="number dashtext-1"><?php echo $Site->ServersCount()['Count']; ?></div>
								</div>
								<div class="progress progress-template">
									<div role="progressbar" style="width: 5%" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="statistic-block block">
								<div class="progress-details d-flex align-items-end justify-content-between">
									<div class="title">
										<div class="icon"><i class="icon-user-1"></i></div><strong>Clients</strong>
									</div>
									<div class="number dashtext-2"><?php echo $Site->UsersCount()['Count']; ?></div>
								</div>
								<div class="progress progress-template">
									<div role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="statistic-block block">
								<div class="progress-details d-flex align-items-end justify-content-between">
									<div class="title">
										<div class="icon"><i class="fa fa-server"></i></div><strong>Boxes</strong>
									</div>
									<div class="number dashtext-2"><?php echo $Site->BoxesCount()['Count']; ?></div>
								</div>
								<div class="progress progress-template">
									<div role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="statistic-block block">
								<div class="progress-details d-flex align-items-end justify-content-between">
									<div class="title">
										<div class="icon"><i class="fa fa-comments"></i></div><strong>Tickets</strong>
									</div>
									<div class="number dashtext-2"><?php echo $Site->TicketsCount()['Count']; ?></div>
								</div>
								<div class="progress progress-template">
									<div role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
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
</body>
</html>