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
					<h2 class="h5 no-margin-bottom">Install Mod</h2>
				</div>
			</div>
			<section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <?php include('public/servernav.php'); ?>
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="table-responsive"> 
                                    <table class="table table-hover" id="sPlOnlineTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Description</th>
                                                <th style="width:20%;text-align:right;">Action</th>
                                            </tr>
                                        </thead>
                                        <?php foreach ($Mods->modsListbyID($Server->serverByID($serverID)['gameID'])['Response'] as $m_k => $m_v) { ?>
                                                <tr>
                                                    <td><?php echo $Secure->SecureTxt($m_v['Name']); ?></td>
                                                    <td><?php echo $Secure->SecureTxt($m_v['Note']); ?></td>
                                                    <td style="text-align:right;">
                                                        <?php if (!($Server->serverByID($serverID)['modID'] == $m_v['id'])) { ?>
                                                            <a href="javascript:;" onclick="installMod('<?php echo $serverID; ?>', '<?php echo $m_v['id']; ?>')" class="btn btn-sm btn-success">Install</a>
                                                        <?php } else { ?>
                                                            <a href="javascript:;" class="btn btn-sm btn-warning disabled">Installed</a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </thead>
                                    </table>
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