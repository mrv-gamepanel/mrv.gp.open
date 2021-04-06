<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  contact.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

if(isset($GET['contact'])) {
    function post_captcha($user_response) {
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
    $res = post_captcha($POST['g-recaptcha-response']);

    if(!$res['success']) {
        die('capta bato treba da se odradi haha');
    } else {
        $Alert->SaveAlert('Success!', 'success');
        header('Location: /home');
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
            <div class="col-md-6">
                <form action="/contact?contact" method="POST" autocomplete="On">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Name:</label>
                            <input type="text" name="Ime" class="form-control" required="">
                        </div>
                    </div> <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Lastname:</label>
                            <input type="text" name="Prezime" class="form-control" required="">
                        </div>
                    </div> <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control" required="">
                        </div>
                    </div> <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Message:</label>
                            <textarea type="text" name="body" class="form-control" required="" rows="5"></textarea>
                        </div>
                    </div> <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="g-recaptcha" data-sitekey="6LcLdOEUAAAAAHc_s924QR4vYtrN6qlyYEMfdea6"></div>
                        </div>
                    </div> <br>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <button class="btn btn-success" style="width:100%;">
                                <i class="fa fa-sign-in"></i> Send
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