<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  login.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/admin/includes.php');

//////////////////////////

// If do not login;
if (!($Admin->IsLoged()) == false) {
    header('Location: /admin/home');
    die();
}

//Login
if(isset($GET['log'])) {
    if(empty($POST['Email'])) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Email field must be filled in', 'info');
    }

    if(empty($POST['Password'])) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Password field must be filled', 'info'); 
    }

    if (isset($POST['zapamtiME'])) {
        $ZapamtiME  = $Secure->SecureTxt($POST['zapamtiME']);
    } else {
        $ZapamtiME = '0';
    }
    if (isset($ZapamtiME) && $ZapamtiME == '1') {
        $ZapamtiME = '1';
    } else {
        $ZapamtiME = '0';
    }

    if(empty($error)) {
        $Admin->LogIn($Secure->SecureTxt($POST['Email']), $Secure->SecureTxt($POST['Password']), false, $ZapamtiME);
    } else {
        $Alert->SaveAlert('An unknown error has occurred! Please try again later.', 'error'); 
    }
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
    <!-- Login Page by BrakLine & TheTwicher <3 -->
	<div class="login-page">
		<div class="container d-flex align-items-center">
			<div class="form-holder has-shadow">
				<div class="row">
					<div class="col-lg-6">
						<div class="info d-flex align-items-center">
							<div class="content">
								<div class="logo">
									<h1><?php echo $Site->SiteConfig()['site_name']; ?></h1>
									<h3>Admin Panel</h3>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form d-flex align-items-center">
							<div class="content">
								<form action="/admin/login?log" method="POST" class="form-validate mb-4">
									<div class="form-group">
										<input id="login-email" type="text" name="Email" required data-msg="Please enter your username" class="input-material">
										<label for="login-email" class="label-material">Email</label>
									</div>
									<div class="form-group">
										<input id="login-password" type="password" name="Password" required data-msg="Please enter your password" class="input-material">
										<label for="login-password" class="label-material">Password</label>
									</div>
									<button type="submit" class="btn btn-primary">Login</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/public/footer.php'); ?>
</body>
</html>