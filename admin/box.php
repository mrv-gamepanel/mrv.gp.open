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

$boxID = $Secure->SecureTxt($GET['id']);

if(!(isset($boxID) || is_numeric($boxID))) {
    die('Molimo upisite ID Boxa');
}

if($Box->boxList($boxID)['Count'] == 0) {
    die('Ovaj Box ne postoji');
}

// If not permission die me
if(!($Admin->AdminPermValid($Admin->AdminData()['id'], '3')) == true) {
	$Alert->SaveAlert('You have no acces.', 'error');
	header('Location: /admin/');
	die();
}

// Only by breaK. and Ratko
if ($Admin->AdminData()['id'] === '8') { // breaK.
    echo 'Ovo samo moze da vidi breaK.';
} else {
    echo 'Ovo samo moze da vidi breaK.';
    die();
    // $Alert->SaveAlert('You have no acces.', 'error');
    // header('Location: /admin/');
    // die();
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
					<h2 class="h5 no-margin-bottom">Edit box</h2>
				</div>
			</div>
			<section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="block">
                                <div class="title"><strong>Box Info</strong></div>
                                <div class="table-responsive"> 
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <td>Box Name:</td>
                                                <td><strong><?php echo $Box->boxByID($boxID)['Name']; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>IP Adress:</td>
                                                <td><strong><?php echo $Box->boxByID($boxID)['Host']; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>SSH2 Port</td>
                                                <td><strong><?php echo $Box->boxByID($boxID)['sshPort']; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>FTP Port</td>
                                                <td><strong><?php echo $Box->boxByID($boxID)['ftpPort']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="block">
                                <div class="title"><strong>FTP Informacije</strong></div>
                                <div class="table-responsive"> 
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <td>Username:</td>
                                                <td><strong><?php echo $Box->boxByID($boxID)['Username']; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Password:</td>
                                                <td><strong><?php echo $Box->boxByID($boxID)['Password']; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Note</td>
                                                <td><strong><?php echo $Box->boxByID($boxID)['Note']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Created Date:</td>
                                                <td><strong><?php echo $Box->boxByID($boxID)['createdDate']; ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong>Komande Servera</strong></div>
                                <div class="srvAction">
                                    <?php if ($Box->boxByID($boxID)['isStart'] == '1') { ?>
                                        <li style="display:inline-block;padding:5px;">
                                            <a class="btn btn-warning" href="javascript:;" onclick="actionBox('<?php echo $boxID; ?>', 'restart')" class="btn btn-sm btn-info">
                                                <i class="fa fa-refresh"></i> Restart
                                            </a>
                                        </li>
                                    <?php } else { ?>
                                        <li style="display:inline-block;padding:5px;">
                                            <a class="btn btn-success" href="javascript:;" onclick="actionBox('<?php echo $boxID; ?>', 'restart')" class="btn btn-sm btn-success">
                                                <i class="fa fa-play"></i> Start
                                            </a>
                                        </li>
                                    <?php } ?>
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