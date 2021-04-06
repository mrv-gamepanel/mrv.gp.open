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
        $User->LogIn($Secure->SecureTxt($POST['Email']), $Secure->SecureTxt($POST['Password']), false, $ZapamtiME, '0');
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
    <title>Client Area | <?php echo $Site->SiteConfig()['site_name']; ?></title>
    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/head.php'); ?>
    <!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
    <!-- Styling -->
    <style>
        #form-section > .row {
            position: relative;
        }
        .website-logo {
            position: absolute;
            z-index: 1000;
            top: 60px;
            left: 0px;
            width: 100%;
            max-width: 550px;
            text-align: center;
        }
        .website-logo img {
            width: 350px;
            height: auto;
        }
        .info-slider-holder {
            display: inline-block;
            width: 100%;
            min-height: 700px;
            height: 100vh;
            overflow: hidden;
            background-repeat: repeat-x;
            background-image: -webkit-linear-gradient(100deg, #121419, #262933);
            background-image: -o-linear-gradient(100deg, #121419, #262933);
            background-image: linear-gradient(100deg, #121419, #262933);
            padding: 140px 30px;
            text-align: center;
            z-index: 999;
            -webkit-transform-style: preserve-3d;
            -moz-transform-style: preserve-3d;
            -ms-transform-style: preserve-3d;
            transform-style: preserve-3d;
        }
        .info-slider-holder .info-holder {
            position: relative;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        .info-slider-holder h6 {
            font-size: 16px;
            font-family: "Rubik", sans-serif;
            color: #ffffff;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .info-slider-holder .bold-title {
            font-size: 31px;
            color: #ffffff;
            font-weight: 700;
            line-height: 38px;
            margin-bottom: 70px;
        }
        .info-slider-holder .bold-title span {
            font-size: 31px;
            color: #4b8cdc;
            font-weight: 700;
        }
        .mini-testimonials-slider .details-holder img.photo {
            display: inline-block;
            width: 88px;
            height: 88px;
            border-radius: 200px;
            margin-bottom: 10px;
        }

        .mini-testimonials-slider .details-holder h4 {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }

        .mini-testimonials-slider .details-holder h5 {
            font-size: 15px;
            font-weight: 300;
            color: #ffffff;
            opacity: 0.7;
            margin-bottom: 40px;
        }

        .mini-testimonials-slider .details-holder p {
            font-size: 19px;
            font-weight: 300;
            color: #ffffff;
            margin-bottom: 60px;
        }

        .mini-testimonials-slider .slick-dots li {
            -webkit-box-shadow: none;
            box-shadow: none;
            opacity: 0.2;
        }

        .mini-testimonials-slider .slick-dots li.slick-active {
            opacity: 1;
        }

        .form-holder .menu-holder {
            text-align: right;
            padding: 22px 30px;
        }

        .form-holder .menu-holder ul.main-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .form-holder .menu-holder ul.main-links li {
            display: inline-block;
        }

        .form-holder .menu-holder ul.main-links li a {
            display: inline-block;
        }

        .form-holder .menu-holder ul.main-links li a.normal-link {
            color: #9c9ca3;
            font-size: 14px;
            text-decoration: none;
            -webkit-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }

        .form-holder .menu-holder ul.main-links li a.normal-link:hover, .form-holder .menu-holder ul.main-links li a.normal-link:focus {
            color: #919196;
        }

        .form-holder .menu-holder ul.main-links li a.sign-button {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 8px 20px;
            border-radius: 10px;
            background-color: #3195ff;
            text-decoration: none;
            margin-left: 10px;
            -webkit-box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
            -webkit-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }

        .form-holder .menu-holder ul.main-links li a.sign-button:hover, .form-holder .menu-holder ul.main-links li a.sign-button:focus {
            background-color: #1888ff;
        }

        .form-holder.signup .menu-holder ul.main-links li a.normal-link {
            color: #ffffff;
            -webkit-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }

        .form-holder.signup .menu-holder ul.main-links li a.normal-link:hover, .form-holder.signup .menu-holder ul.main-links li a.normal-link:focus {
            color: #ffffff;
            opacity: 0.7;
        }

        .form-holder.signup .menu-holder ul.main-links li a.sign-button {
            color: #929292;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 100px;
            background-color: #ffffff;
            text-decoration: none;
            margin-left: 10px;
            -webkit-box-shadow: 0 12px 32px 0 rgba(6, 154, 71, 0.43);
            box-shadow: 0 12px 32px 0 rgba(6, 154, 71, 0.43);
            -webkit-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }

        .form-holder.signup .menu-holder ul.main-links li a.sign-button .hno {
            color: #929292;
            font-size: 20px;
            vertical-align: middle;
            margin-top: 1px;
            margin-left: 5px;
        }

        .form-holder.signup .menu-holder ul.main-links li a.sign-button:hover, .form-holder.signup .menu-holder ul.main-links li a.sign-button:focus {
            padding-right: 15px;
            background-color: #ffffff;
            -webkit-box-shadow: 0 14px 42px 0 rgba(6, 154, 71, 0.43);
            box-shadow: 0 14px 42px 0 rgba(6, 154, 71, 0.43);
        }

        .form-holder.signup .menu-holder ul.main-links li a.sign-button:hover .hno, .form-holder.signup .menu-holder ul.main-links li a.sign-button:focus .hno {
            margin-left: 10px;
        }

        .form-holder .signin-signup-form {
            text-align: center;
            padding: 100px 40px 40px;
        }

        .form-holder .signin-signup-form ::-webkit-input-placeholder {
            color: #526489;
        }

        .form-holder .signin-signup-form :-moz-placeholder {
            color: #526489;
        }

        .form-holder .signin-signup-form ::-moz-placeholder {
            color: #526489;
        }

        .form-holder .signin-signup-form :-ms-input-placeholder {
            color: #526489;
        }

        .form-control ::-webkit-input-placeholder {
            color: #526489;
        }

        .form-control :-moz-placeholder {
            color: #526489;
        }

        .form-control ::-moz-placeholder {
            color: #526489;
        }

        .form-control :-ms-input-placeholder {
            color: #526489;
        }

        .signin-signup-form .form-items {
            display: inline-block;
            width: 100%;
            max-width: 410px;
        }

        .signin-signup-form .form-title {
            color: #3195ff;
            font-weight: 300;
            font-size: 19px;
            margin-bottom: 50px;
            text-align: left;
        }

        .signin-signup-form .row {
            margin-right: -10px;
            margin-left: -10px;
        }

        .signin-signup-form .row div[class^="col-"] {
            padding-right: 10px;
            padding-left: 10px;
        }

        .signin-signup-form .row div[class^="col-"]:first-child {
            padding-right: 5px;
        }

        .signin-signup-form .row div[class^="col-"]:last-child {
            padding-left: 5px;
        }

        .signin-signup-form .form-text {
            margin-bottom: 10px;
        }

        .signin-signup-form .form-text input, .signin-signup-form .form-text .dropdown-toggle.btn-default {
            width: 100%;
            padding: 8px 20px;
            text-align: left;
            border: 0;
            outline: 0;
            border-radius: 8px;
            background-color: #f0f4fd;
            font-size: 13px;
            color: #526489;
            -webkit-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }

        .signin-signup-form .form-text input:hover, .signin-signup-form .form-text input:focus, .signin-signup-form .form-text .dropdown-toggle.btn-default:hover, .signin-signup-form .form-text .dropdown-toggle.btn-default:focus {
            background-color: #ebeff8;
        }

        .signin-signup-form .form-text textarea {
            width: 100%;
            padding: 8px 20px;
            border-radius: 8px;
            text-align: left;
            background-color: #ffffff;
            border: 0;
            font-size: 13px;
            color: #526489;
            outline: none;
            -webkit-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }

        .signin-signup-form .form-text textarea:hover, .signin-signup-form .form-text textarea:focus {
            background-color: #ebeff8;
        }

        .signin-signup-form .form-text.text-holder {
            margin-top: 30px;
        }

        .signin-signup-form .form-text .text-only {
            color: #abb4bc;
            font-size: 13px;
            font-weight: 400;
        }

        .signin-signup-form .form-text input[type="checkbox"], .signin-signup-form .form-text input[type="radio"] {
            width: auto;
        }

        .signin-signup-form .form-text input[type="checkbox"]:not(:checked), .signin-signup-form .form-text input[type="checkbox"]:checked, .signin-signup-form .form-text input[type="radio"]:not(:checked), .signin-signup-form .form-text input[type="radio"]:checked {
            position: absolute;
            left: -9999px;
        }

        .signin-signup-form .form-text input[type="checkbox"]:not(:checked) + label, .signin-signup-form .form-text input[type="checkbox"]:checked + label, .signin-signup-form .form-text input[type="radio"]:not(:checked) + label, .signin-signup-form .form-text input[type="radio"]:checked + label {
            position: relative;
            padding-left: 23px;
            cursor: pointer;
            display: inline;
            color: #abb4bc;
            font-size: 13px;
            font-weight: 400;
            margin-left: 10px;
        }

        .signin-signup-form .form-text input[type="checkbox"]:checked + label, .signin-signup-form .form-text input[type="radio"]:checked + label {
            color: #3195ff;
        }

        .signin-signup-form .form-text input[type="checkbox"]:checked + label:before, .signin-signup-form .form-text input[type="radio"]:checked + label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            width: 15px;
            height: 15px;
            background: #3195ff;
            border-radius: 50px;
            border: 0px solid #abb4bc;
            -webkit-transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        .signin-signup-form .form-text input[type="checkbox"]:not(:checked) + label:before, .signin-signup-form .form-text input[type="radio"]:not(:checked) + label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            width: 15px;
            height: 15px;
            background: #f0f4fd;
            border-radius: 50px;
            border: 0px solid #abb4bc;
            -webkit-transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        .signin-signup-form .form-text input[type="checkbox"]:not(:checked) + label:after, .signin-signup-form .form-text input[type="radio"]:not(:checked) + label:after {
            opacity: 0;
            -webkit-transform: scale(0);
            -moz-transform: scale(0);
            -ms-transform: scale(0);
            transform: scale(0);
        }

        .signin-signup-form .form-text input[type="checkbox"]:checked + label:after, .signin-signup-form .form-text input[type="checkbox"]:not(:checked) + label:after {
            content: '\f00c';
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            font-weight: 600;
            position: absolute;
            top: 2px;
            left: 2px;
            font-size: 10px;
            color: #ffffff;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }

        .signin-signup-form .form-text input[type="checkbox"]:checked + label:before {
            border-radius: 4px;
        }

        .signin-signup-form .form-text input[type="checkbox"]:not(:checked) + label:before {
            border-radius: 4px;
        }

        .signin-signup-form .form-text input[type="radio"]:checked + label:after, .signin-signup-form .form-text input[type="radio"]:not(:checked) + label:after {
            content: "";
            position: absolute;
            top: 7px;
            left: 5px;
            width: 5px;
            height: 5px;
            border-radius: 20px;
            background-color: #ffffff;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }

        .signin-signup-form .form-button {
            margin-top: 40px;
            margin-bottom: 25px;
        }

        .signin-signup-form .form-button .ybtn {
            padding: 7px 26px;
        }
    </style>
</head>
<body>
    <!--[ALERTS]-->
    <div id="msg_alert"><?php echo $Alert->PrintAlert(); ?></div>
    <script type="text/javascript">
        setTimeout(function() {
            document.getElementById('msg_alert').innerHTML = '<?php echo $Alert->RemoveAlert(); ?>';
        }, 5000);
    </script>
    <div id="form-section" class="container-fluid signin">
        <div class="website-logo">
            <a href="/home">
                <img src="/assets/img/mrv-logo.png?<?php echo time(); ?>" alt="MRV-Hosting.com">
            </a>
        </div>
        <div class="row">
            <diiv class="col-md-5">
                <div class="info-slider-holder">
                    <div class="info-holder">
                        <div class="bold-title">it’s not that hard to get <br><span>game server</span> at <br><span>MRV-Hosting.com</span></div>
                    </div>
                </div>
            </diiv>
            <div class="col-md-7">
                <div class="form-holder">
                    <div class="menu-holder">
                        <ul class="main-links">
                            <li><a class="normal-link" href="/register">Don’t have an account?</a></li>
                            <li><a class="sign-button" href="/register">Sign up</a></li>
                        </ul>
                    </div>
                    <div class="signin-signup-form" id="l">
                        <div class="form-items">
                            <div class="form-title"><div class="header-lined"><h1>Sign in <br><small>This page is restricted</small></h1></div></div>
                            <div class="providerLinkingFeedback"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <form id="signinform" action="/login?log" method="POST" autocomplete="On">
                                        <div class="form-text">
                                            <input id="inputEmail" type="email" name="Email" name="Email" placeholder="Enter email" value="<?php isset($_SESSION['Email']) ? $Email = $Secure->SecureTxt($_SESSION['Email']) : $Email = ''; echo $Email; ?>" required="">
                                        </div>
                                        <div class="form-text">
                                            <input id="inputPassword" type="password" name="Password" placeholder="Password" autocomplete="off" required="">
                                        </div>
                                        <div class="form-text text-holder">
                                            <input id="chkbox" type="checkbox" class="hno-checkbox" name="zapamtiME" value="1" <?php if(isset($_COOKIE['member_login'])) { ?> checked <?php } ?> /> <label for="chkbox">Remember Me</label>
                                        </div>
                                        <div class="form-button">
                                            <button id="login" class="ybtn ybtn-accent-color btn btn-primary">Login</button>
                                            <a href="#" class="btn btn-link">Forgot Password?</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>