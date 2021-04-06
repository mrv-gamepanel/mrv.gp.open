<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  games.php
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
					<h2 class="h5 no-margin-bottom">Games</h2>
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
			                                    <th>name</th>
												<th>created by</th>
												<th>created date</th>
			                                </tr>
										</thead>
										<tbody>
											<?php foreach ($Games->gameList()['Response'] as $k => $v) { ?>
                                                <tr id="tr-id-<?php echo $v['id']; ?>" class="tr-class-<?php echo $v['id']; ?>" data-title="bootstrap table" data-object='{"key": "value"}' onclick="document.location.href = '/<?php echo $admDir; ?>/game?id=<?php echo $v['id']; ?>'" style="cursor:pointer;">
                                                    <td><a href="/<?php echo $admDir; ?>/game?id=<?php echo $v['id']; ?>"><img src="<?php echo $Games->gameByID($v['id'])['Icon']; ?>" style="padding-right:5px;" alt="<?php echo $Games->gameByID($v['id'])['Name']; ?>" width="20px"><?php echo $Secure->SecureTxt($v['Name']); ?></a></td>
													<td><?php echo @$Admin->getFullName($Games->gameByID($v['id'])['createdBy']); ?></td>
													<td><?php echo date('d.m.Y H:i a', $v['createdDate']); ?></td>
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
            $('#serverList').DataTable();
        });
    </script>
</body>
</html>