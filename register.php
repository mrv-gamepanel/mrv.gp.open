<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  register.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//////////////////////////

// If  login;
if (!($User->IsLoged()) == false) {
    header('Location: /home');
    die();
}

//Register
if(isset($GET['reg'])) {
    // Username
    if(empty($Secure->SecureTxt($POST['Username']))) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Username field must be filled in', 'info');
    }
    if(strlen($Secure->SecureTxt($POST['Username'])) > 15 || strlen($Secure->SecureTxt($POST['Username'])) < 5) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Username field must have 5-15 letters', 'info'); 
    }
    $Username = @$Secure->SecureTxt($POST['Username']);
    // Password
    if(empty($POST['Password'])) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Password field must be filled', 'info'); 
    }
    if(strlen($Secure->SecureTxt($POST['Password'])) > 15 || strlen($Secure->SecureTxt($POST['Password'])) < 5) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Password field must have 5-15 letters', 'info'); 
    }
    $Password = @$Secure->SecureTxt($POST['Password']);
    // Email
    if(empty($Secure->SecureTxt($POST['Email']))) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Email field must be filled', 'info'); 
    }
    if (!filter_var($POST['Email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Email field must be valid', 'info');
    }
    $Email = @$Secure->SecureTxt($POST['Email']);
    // Firstname
    if(empty($Secure->SecureTxt($POST['FName']))) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #First Name field must be filled', 'info'); 
    }
    if(strlen($Secure->SecureTxt($POST['FName'])) > 15 || strlen($Secure->SecureTxt($POST['FName'])) < 5) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #First Name field must have 5-15 letters', 'info'); 
    }
    $Name = @$Secure->SecureTxt($POST['FName']);
    // Lastname
    if(empty($Secure->SecureTxt($POST['LName']))) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Last Name field must be filled', 'info'); 
    }
    if(strlen($Secure->SecureTxt($POST['LName'])) > 15 || strlen($Secure->SecureTxt($POST['LName'])) < 5) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Last Name field must have 5-15 letters', 'info'); 
    }
    $lName = @$Secure->SecureTxt($POST['LName']);
    // Pin Code
    if(empty($Secure->SecureTxt($POST['pC']))) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #Pin Code field must be filled', 'info'); 
    }
    if(!strlen($Secure->SecureTxt($POST['pC'])) == 4) {
        $error[] = 'Greska!';
        $Alert->SaveAlert('The #PinCode field must have 4 numbers', 'info'); 
    }
    $pC = @$Secure->SecureTxt($POST['pC']);

    // Captcha
    if(isset($POST['g-recaptcha-response'])) {
        $captcha = $POST['g-recaptcha-response'];
    }
    if(!$captcha) {
        $Alert->SaveAlert('Please check the the captcha form.', 'error');
        header('Location: /register');
        die();
    }
    $secretKey  = '6LcLdOEUAAAAAMM7B5onUXowfPnre5ASBR5wpnmy';
    $ip         = $_SERVER['REMOTE_ADDR'];
    // post request to server
    $url    = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response       = file_get_contents($url);
    $responseKeys   = json_decode($response, true);

    if(!$responseKeys['success'] === '1') {
        $Alert->SaveAlert('The #Captcha field must be verified', 'info');
        header('Location: /register');
        die();
    }
    if (!empty($User->UserDataIDemail($Email)['id'])) {
        $Alert->SaveAlert('This Email is already in use.', 'error');
        header('Location: /register');
        die();
    }
    if(empty($error)) {
        if (!($User->Register($Username, md5($Password), $Email, $Name, $lName, '', $pC, $Secure->RandKey(60), '1')) == false) {
            $Alert->SaveAlert('Success!', 'success');
            header('Location: /login');
            die();
        } else {
            $Alert->SaveAlert('An unknown error has occurred! Please try again later.', 'error');
            header('Location: /register');
            die();
        }        
    } else {
        $Alert->SaveAlert('An unknown error has occurred! Please try again later.', 'error');
        header('Location: /register');
        die();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>

    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/head.php'); ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    <!--[HEADER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/header.php'); ?>
    
    <!--[CONTENT]-->
    <div class="container mt150">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="/register?reg" method="POST" autocomplete="On">
                    <div class="row">
                        <div class="col-6">
                            <label>Username</label>
                            <input type="text" name="Username" class="form-control" required="" minlength="5" maxlength="15"> <br>
                        </div>
                        <div class="col-6">
                            <label>Email</label>
                            <input type="email" name="Email" class="form-control" required="" minlength="5"> <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>First Name</label>
                            <input type="text" name="FName" class="form-control" required="" minlength="5" maxlength="15"> <br>
                        </div>
                        <div class="col-6">
                            <label>Last Name</label>
                            <input type="text" name="LName" class="form-control" required="" minlength="5" maxlength="15"> <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Password</label>
                            <input type="password" name="Password" class="form-control" required="" minlength="5" maxlength="15"> <br>
                        </div>
                        <div class="col-6">
                            <label>Pin Code</label>
                            <input type="text" name="pC" class="form-control" required="" maxlength="5" minlength="5" value="<?php echo $Secure->randKeyForPc(5); ?>"> <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="g-recaptcha" data-sitekey="6LcLdOEUAAAAAHc_s924QR4vYtrN6qlyYEMfdea6"></div>
                        </div>
                        <div class="col-3"></div>
                        <div class="col-md-3"><br>
                            <button class="btn btn-success" style="width:100%;">
                                <i class="fa fa-sign-in"></i> Register
                            </button>  
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
</body>
</html>