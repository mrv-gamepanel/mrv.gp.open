<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  login.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//////////////////////////

// If  login;
if (!($User->IsLoged()) == true) {
    header('Location: /home');
    die();
}


/* 
Idjea je dodati 'grafik-banner' ispod avatara i imena.

Sluzice da klijent moze da proveri koliko je njegov (banner - tj. nasa reklama) pogledana..  

10 views = Jedan cs 1.6 server (32 slot) traje jedan dan;
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>

    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/head.php'); ?>
</head>
<body>
    <!--[HEADER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/header.php'); ?>
    
    <!--[CONTENT]-->
    <div class="container mt150">
        <div class="row">
            <div class="col-md-4">
                <div class="pImg" style="text-align:center;">
                    <img src="/admin/img/default.png" alt="Default image" style="width:50%;" class="text-center">
                </div>
                <div class="pInfName" style="text-align:center;margin-top:30px;">
                    <h3><?php echo $User->getFullName($User->UserData()['id']); ?></h3>
                </div> <br><br><br>
            </div>
            <div class="col-md-8">
                <h3>Account information</h3>
                <form action="/process?editprofile" method="POST" autocomplete="off">
                    <div class="row">
                        <div class="col-md-6">
                            <label>First name</label>
                            <input type="text" name="fName" value="<?php echo $Secure->SecureTxt($User->UserData()['Name']); ?>" class="form-control" required=""> <br>
                        </div>
                        <div class="col-md-6">
                            <label>Last name</label>
                            <input type="text" name="lName" value="<?php echo $Secure->SecureTxt($User->UserData()['Lastname']); ?>" class="form-control" required=""> <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Username</label>
                            <input type="text" name="Username" value="<?php echo $Secure->SecureTxt($User->UserData()['Username']); ?>" class="form-control" required="" readonly="" style="background:#1f222b;"> <br>
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="Email" value="<?php echo $Secure->SecureTxt($User->UserData()['Email']); ?>" class="form-control" required="" readonly="" style="background:#1f222b;"> <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <label>
                                <span style="float:left;">Account token</span>
                                <span style="position:absolute;right:20px;"><small onclick="reqForChToken()">[change]</small></span>
                            </label>
                            <input id="acToken" type="text" name="Token" value="<?php echo $Secure->SecureTxt($User->UserData()['Token']); ?>" class="form-control" required="" readonly="" style="background:#1f222b;"> <br>
                        </div>
                        <div class="col-md-2">
                            <label>
                                <span>Pin Code</span>
                            </label>
                            <input id="pppCode" type="text" name="pinCode" maxlength="5" minlength="5" class="form-control" style="margin-top:6px;" required="">
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <button class="btn btn-success" style="width:100%;">
                                <i class="fa fa-save"></i> Save
                            </button>  
                        </div>
                    </div>
                </form> <br><br><br>
                <h3 id="changePW">Change password</h3>
                <form action="/process?changepw" method="POST" autocomplete="off">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Current password</label>
                            <input type="password" name="cPass" value="" class="form-control" required="" minlength="6"> <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>New password</label>
                            <input type="password" name="nPass" value="" class="form-control" required="" minlength="6"> <br>
                        </div>
                        <div class="col-md-6">
                            <label>Repeat the new password</label>
                            <input type="password" name="rnPass" value="" class="form-control" required="" minlength="6">
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-3">
                            <label>
                                <span>Pin Code</span>
                            </label>
                            <input id="pppCode" type="text" name="pinCode" maxlength="5" minlength="5" class="form-control" style="margin-top:6px;" required="">
                        </div>
                        <div class="col-md-4"><br>
                            <button class="btn btn-danger" style="width:100%;margin-top:16px;">
                                <i class="fa fa-save"></i> Change
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