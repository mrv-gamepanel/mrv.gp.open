<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  plugins.php
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
/*if(!($Admin->AdminPermValid($Admin->AdminData()['id'], '2')) == true) {
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
					<h2 class="h5 no-margin-bottom">Plugins</h2>
				</div>
			</div>
			<section class="no-padding-top">
				<div class="container-fluid">
					<div class="row">
						<!-- Form Elements -->
							<div class="col-lg-12">
								<div class="block">
									<table id="serverList" class="table table-striped table-sm">
										<thead>
											<tr>
			                                    <th>Server name</th>
												<th>Game</th>
												<th>Client</th>
												<th>Date&Time</th>
												<th>Status</th>
			                                </tr>
										</thead>
										<tbody>
											<?php foreach ($Billing->BillingList()['Response'] as $k => $v) { ?>
                                                <tr id="tr-id-<?php echo $v['id']; ?>" class="tr-class-<?php echo $v['id']; ?>" data-title="bootstrap table" data-object='{"key": "value"}' onclick="document.location.href = '/<?php echo $admDir; ?>/order?id=<?php echo $v['id']; ?>'" style="cursor:pointer;">
                                                    <td><a href="/<?php echo $admDir; ?>/order?id=<?php echo $v['id']; ?>"><?php echo $Secure->SecureTxt($v['serverName']); ?></a></td>
                                                    <td><?php echo $Secure->SecureTxt($Games->gameByID($v['gameID'])['Name']); ?></td>
                                                    <td><a href="/admin/user?id=<?php echo $v['userID']; ?>"><?php echo $Secure->SecureTxt($User->UserDataID($v['userID'])['Name']).' '.$Secure->SecureTxt($User->UserDataID($v['userID'])['Lastname']); ?></a></td>
                                                    <td><?php echo $Secure->SecureTxt($v['orderDate']); ?></td>
                                                    <td><?php echo $Billing->orderStatus($v['orderStatus']); ?></td>
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
            // $('#serverList').DataTable();
        });
    </script>
</body>
</html> 