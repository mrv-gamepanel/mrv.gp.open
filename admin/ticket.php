<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  ticket.php
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

// Ticket ID
if (isset($GET['id'])) {
    $tID = $Secure->SecureTxt($GET['id']);
} else {
    header('Location: /admin/support');
    die();
}
////////////////////
if (isset($tID)) {
    if (empty($tID) || !is_numeric($tID) || $tID == '' || $tID <= 0) {
        die('where are you going?');
    }
} else {
    header('Location: /admin/support');
    die();
}

// Is valid Ticket ID
if (empty($Support->ticketByID($tID)['id'])) {
    $Alert->SaveAlert('This ticket does not exist or is not linked to your account!', 'error');
    header('Location: /admin/support');
    die();
} else {
    $tInfo = $Support->ticketByID($tID);
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
					<h2 class="h5 no-margin-bottom">Ticket</h2>
				</div>
			</div>
			<section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-header"><i class="fa fa-info"></i> <?php echo $Secure->SecureTxt($tInfo['Title']); ?><span class="btn btn-sm btn-primary" style="float:right;" onclick="LockTicket('<?php echo $tInfo['id']; ?>')"><i class="fa fa-send"></i> Lock</span></h5>
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-2" style="text-align:center;">
                                                    <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid" style="width:100px;border-radius:50%;">
                                                    <div class="clearfix" style="margin-top:10px;"></div>
                                                    <p class="text-secondary text-center"><?php echo $Secure->SecureTxt($tInfo['Date']); ?></p>
                                                </div>
                                                <div class="col-md-10">
                                                    <p><strong><?php echo $User->getFullName($tInfo['userID']); ?></strong><br>
                                                        <small>(Client)</small></p>
                                                    <p style="color:#eee;"><?php echo nl2br($Secure->SecureTxt($tInfo['Message'])); ?></p>
                                                </div>
                                            </div>
                                            <?php foreach ($Support->answOnTicketList($tInfo['id']) as $t_k => $t_v) { ?>
                                                <?php if (empty($t_v['supportID'])) { ?>
                                                    <div class="card card-inner">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-2" style="text-align:center;">
                                                                    <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid" style="width:100px;border-radius:50%;">
                                                                    <div class="clearfix" style="margin-top:10px;"></div>
                                                                    <p class="text-secondary text-center"><?php echo $Secure->SecureTxt($t_v['Date']); ?></p>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <p><strong><?php echo $User->getFullName($t_v['userID']); ?></strong><br>
                                                                        <small>(Client)</small></p>
                                                                    <p style="color:#eee;"><?php echo nl2br($Secure->SecureTxt($t_v['Message'])); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="card card-inner">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-2" style="text-align:center;">
                                                                    <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid" style="width:100px;border-radius:50%;">
                                                                    <div class="clearfix" style="margin-top:10px;"></div>
                                                                    <p class="text-secondary text-center"><?php echo $Secure->SecureTxt($t_v['Date']); ?></p>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <p><strong><?php echo $Admin->getFullName($t_v['supportID']); ?></strong><br>
                                                                        <small>(Support)</small></p>
                                                                    <p style="color:#eee;"><?php echo nl2br($Secure->SecureTxt($t_v['Message'])); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?> 
                                            <p style="position:absolute;right:30px;bottom:70px;">
                                                <a class="float-right btn btn-sm btn-danger" onclick="replyOnTicket(this)" style="cursor:pointer;">
                                                    <i class="fa fa-reply"></i> Reply
                                                </a>
                                            </p>
                                            <form id="AnswOnTicket" action="#" method="POST" autocomplete="off" style="display:none;">
                                                <input type="text" name="tID" value="<?php echo $Secure->SecureTxt($tInfo['id']); ?>" style="display:none;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Reply</label>
                                                        <textarea id="Message" name="Message" required="" class="form-control" rows="5" placeholder="Reply .."></textarea>
                                                    </div>
                                                </div> <br>
                                                <span class="btn btn-sm btn-primary" style="float:right;" onclick="AnswOnTicket()"><i class="fa fa-send"></i> Send</span>
                                            </form>
                                        </div>
                                    </div>
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