<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  login.php
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

if (isset($GET['rdr'])) {
    $naProf = $Secure->SecureTxt($GET['rdr']); 
} else {
    $naProf = '';
}

//Login
if(isset($GET['log'])) {
    $POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

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
    
    /*function post_captcha($user_response) {
        $fields_string = '';
        $fields = array(
            'secret' => '6LcLdOEUAAAAAMM7B5onUXowfPnre5ASBR5wpnmy',
            'response' => $user_response
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    // Call the function post_captcha
    $res = post_captcha($_POST['g-recaptcha-response']);

    if(!$res['success']) {
        $error = 'Greska!';
        $Alert->SaveAlert('The #Captcha field must be verified', 'info'); 
    }*/

    if(empty($error)) {
        $User->LogIn($Secure->SecureTxt($POST['Email']), $Secure->SecureTxt($POST['Password']), false, $ZapamtiME, $Secure->SecureTxt($POST['naProf']));
    } else {
        $Alert->SaveAlert('An unknown error has occurred! Please try again later.', 'error'); 
    }
}

if (isset($GET['autologin'])) {
    $UserID     = $Secure->SecureTxt($GET['id']);
    $TokenKey   = $Secure->SecureTxt($GET['key']);
    $naProf     = $Secure->SecureTxt($GET['rdr']);

    // Proveri poklapaanje Tokenaaa i UserID-a
    if (!($User->RegTokenKey($TokenKey, $UserID)) == false) {
        // Proveri jeli aktivan nalog
        if (!($User->RegTokenKeyStatus($TokenKey, $UserID)['reg_status']) == false) {
            // Tacan key i user id = AutoLogin
            $User->LogIn($User->UserDataByID($UserID)['Email'], $User->UserDataByID($UserID)['Password'], true, '1', $naProf);
        }
    } else {
        // Netacan key i user id
        die('Brate/Sestro, prastaj problem cemo otkloniti u sto kracem roku.. Hvala na razumevanju. Volim vas sve! <3');
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
            <div class="col-md-6">
                <form action="/login?log" method="POST" autocomplete="On">
                    <input type="text" name="naProf" value="<?php echo $naProf; ?>" style="display:none;">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Email</label>
                            <input type="email" name="Email" value="<?php isset($_SESSION['Email']) ? $Email = $Secure->SecureTxt($_SESSION['Email']) : $Email = ''; echo $Email; ?>" class="form-control" required="">
                        </div>
                    </div> <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label>password</label>
                            <input type="password" name="Password" class="form-control" required="">
                        </div>
                    </div> <br>
                    <!--<div class="row">
                        <div class="col-md-12">
                            <div class="g-recaptcha" data-sitekey="6LcLdOEUAAAAAHc_s924QR4vYtrN6qlyYEMfdea6"></div>
                        </div>
                    </div> <br>-->
                    <div class="row">
                        <div class="col-md-12">
                            <input style="display:none;" id="zapamtiME" type="checkbox" name="zapamtiME" value="1" <?php if(isset($_COOKIE['member_login'])) { ?> checked <?php } ?>>
                            <label for="zapamtiME">
                                Remember me
                            </label>

                            <label for="zapamtiME" style="float:right;">
                                <a href="/login?forgot">
                                    <i class="fa fa-key"></i> I'am forgot password
                                </a>
                            </label>
                        </div>
                    </div> <br>
                    <div class="row justify-content-center">
                        <div class="col-6">
                            <span class="btn btn-primary" style="width:100%;color:#fff;cursor:pointer;" onclick="demoLogin()">
                                <i class="fa fa-sign-in"></i> Demo Login
                            </span>  
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success" style="width:100%;">
                                <i class="fa fa-sign-in"></i> Login
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