<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  online.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//////////////////////////

// If do not login;
if (!($User->IsLoged()) == true) {
    header('Location: /login');
    die();
}

/* Server ID */
if (isset($GET['id'])) {
    $serverID = $Secure->SecureTxt($GET['id']);
    // IS empty id
    if (empty($serverID) || !is_numeric($serverID)) {
        $Alert->SaveAlert('Server?', 'error');
        header('Location: /servers');
        die();
    }
    // Is Valid server
    if (empty($Server->serverByID($serverID)['id'])) {
        $Alert->SaveAlert('Server?', 'error');
        header('Location: /servers');
        die();
    }
    // It's my server?
    if(!($Server->isMyServer($serverID)) == true) {
        $Alert->SaveAlert('This is not Owner!', 'error');
        header('Location: /servers');
        die();
    }
} else {
    $Alert->SaveAlert('Server?', 'error');
    header('Location: /servers');
    die();
}

// Only for Cs 1.6
if($Games->gameByID($Server->serverByID($serverID)['gameID'])['smName'] !== 'cs16') {
    $Alert->SaveAlert('Option is not valid for this Game!', 'info');
    header('Location: /server?id='.$serverID);
    die();
}

$filePath = '/cstrike/addons/amxmodx/configs/';
$fileName = 'users.ini';
$fileContent = $webFTP->getFileContent($serverID, $filePath, $fileName);
$loadUserFile = explode("\n;", $fileContent);
$admNum=1;

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
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header"><i class="fa fa-angle-right"></i> <img src="<?php echo $Games->gameByID($Server->serverByID($serverID)['gameID'])['Icon']; ?>" alt="<?php echo $Games->gameByID($Server->serverByID($serverID)['gameID'])['Name']; ?>" title="<?php echo $Games->gameByID($Server->serverByID($serverID)['gameID'])['Name']; ?>" style="width:20px;"> <?php echo $Secure->SecureTxt($Server->serverByID($serverID)['Name']); ?></h5>

                    <!--[SERVER NAVIGATION]-->
                    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/server_nav.php'); ?>
                    
                    <!--[SERVER CONTENT]-->
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                
                            </div>
                            <div class="col-md-3">
                                <div class="newBtn" style="text-align:right;">
                                    <a href="javascript:;" data-toggle="modal" data-target="#newAdmin" class="btn btn-sm btn-primary"><i class="fa fa-user-plus"></i> New admin</a>
                                </div>
                                <div class="modal fade" id="newAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">
                                                    <i class="fa fa-user-plus"></i> New admin
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/process?newAdmin" method="POST" autocomplete="off">
                                                    <input type="text" name="srvID" value="<?php echo $serverID ?>" style="display:none;">
                                                    <input type="text" name="Path" value="<?php echo $filePath ?>" style="display:none;">
                                                    <input type="text" name="File" value="<?php echo $fileName ?>" style="display:none;">
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Admin Type</label>
                                                            <select name="adminType" id="adminType" class="form-control">
                                                                <option value="Nick">Nick</option>
                                                                <option value="steamID">SteamID</option>
                                                                <option value="IPaddr">IP address</option>
                                                            </select> <br>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>Admin</label>
                                                            <input type="text" name="Admin" class="form-control" required=""> <br>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>Password</label>
                                                            <input type="text" name="Password" class="form-control"> <br>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>Permissions</label>
                                                            <select name="Permissions" id="Permissions" class="form-control" onchange="newAdmSelectType(this)">
                                                                <option value="" selected="" disabled="">--select--</option>
                                                                <option value="slot">Slot</option>
                                                                <option value="slot_i">Slot+Imunitet</option>
                                                                <option value="low_admin">Admin</option>
                                                                <option value="full_admin">Full admin</option>
                                                                <option value="head_admin">Head admin</option>
                                                                <option value="custom">Custom</option>
                                                            </select> <br>
                                                        </div>
                                                        
                                                        <div id="customPerms" class="col-md-12" style="display:none;">
                                                            <label>Custom Permissions</label> <br>
                                                            
                                                            <div class="cPerms">
                                                                <label for="a">
                                                                    "a" Imunity
                                                                    <input type="checkbox" name="customPerm[]" value="a" class="form-control">
                                                                </label>
                                                                <label for="b">
                                                                    "b" Slot
                                                                    <input type="checkbox" name="customPerm[]" value="b" class="form-control">
                                                                </label>
                                                                <label for="c">
                                                                    "c" amx_kick
                                                                    <input type="checkbox" name="customPerm[]" value="c" class="form-control">
                                                                </label>
                                                                <label for="d">
                                                                    "d" amx_ban i amx_unban
                                                                    <input type="checkbox" name="customPerm[]" value="d" class="form-control">
                                                                </label>
                                                                <label for="e">
                                                                    "f" amx_map
                                                                    <input type="checkbox" name="customPerm[]" value="f" class="form-control">
                                                                </label>
                                                                <label for="g">
                                                                    "g" amx_cvar
                                                                    <input type="checkbox" name="customPerm[]" value="g" class="form-control">
                                                                </label>
                                                                <label for="h">
                                                                    "h" amx_cfg
                                                                    <input type="checkbox" name="customPerm[]" value="h" class="form-control">
                                                                </label>
                                                                <label for="i">
                                                                    "i" amx_chat i bela slova
                                                                    <input type="checkbox" name="customPerm[]" value="i" class="form-control">
                                                                </label>
                                                                <label for="j">
                                                                    "j" amx_vote i amx_votemap
                                                                    <input type="checkbox" name="customPerm[]" value="j" class="form-control">
                                                                </label>
                                                                <label for="k">
                                                                    "k" amx_cvar & sv_password
                                                                    <input type="checkbox" name="customPerm[]" value="k" class="form-control">
                                                                </label>
                                                                <label for="l">
                                                                    "l" head admin
                                                                    <input type="checkbox" name="customPerm[]" value="l" class="form-control">
                                                                </label>
                                                            </div> <br>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>Comment</label>
                                                            <input type="text" name="Comment" class="form-control" required=""> <br>
                                                        </div>
                                                    </div> <br>

                                                    <div class="text-right">
                                                        <button class="btn btn-sm btn-primary">
                                                            <i class="fa fa-check"></i> Create!
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <hr>
                        <div class="row srvInfor">
                            <div class="col-md-12">
                                <div class="table-responsive"> 
                                    <table class="table table-hover" id="sPlOnlineTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name/Steam/IP</th>
                                                <th scope="col">Password</th>
                                                <th scope="col">Permissions</th>
                                                <th scope="col">Comment</th>
                                                <th scope="col" style="text-align:right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($loadUserFile as $fLine) {
                                                $Line = explode("\n", $fLine); array_shift($Line);
                                                foreach($Line as $lVal) {
                                                    $adminInfo = explode('"', $lVal);

                                                    if(!empty($adminInfo['1']) && !empty($adminInfo['5'])) { ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $admNum++; ?></th>
                                                            <td><?php echo $Secure->SecureTxt($adminInfo['1']); ?></td>
                                                            <td>
                                                                <span id="showAdmPw_<?php echo $admNum; ?>">
                                                                    <b style="display:none;font-size:13px;"><?php echo $Secure->SecureTxt($adminInfo['3']); ?></b><i style="opacity:1;">*******</i>
                                                                </span>
                                                                <div class="pwAction" style="float:right;">
                                                                    <small>[<a href="javascript:;" onclick="showAdmPw(this, '<?php echo $admNum; ?>')">show</a>]</small>
                                                                </div>
                                                            </td>
                                                            <td><?php echo $Secure->SecureTxt($adminInfo['5']); ?></td>
                                                            <?php $admComment = str_ireplace('/', '', $adminInfo['8']); ?>
                                                            <td><?php echo $Secure->LimitText($Secure->SecureTxt($admComment), 20); ?></td>
                                                            <td style="text-align:right;">
                                                                <div class="plAction">
    <!-- Edit Admin -->
    <a href="javascript:;" data-toggle="modal" data-target="#<?php echo $Secure->SecureTxt('admView'.$admNum); ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>

    <div class="modal fade" id="<?php echo $Secure->SecureTxt('admView'.$admNum); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Edit Admin <small>(<?php echo $Secure->SecureTxt($adminInfo['1']); ?>)</small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/process?editAdmin" method="POST" autocomplete="off">
                        

                        <div class="text-right">
                            <button class="btn btn-sm btn-primary">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete admin -->
    <a href="javascript:;" data-toggle="modal" data-target="#<?php echo $Secure->SecureTxt('admDelete'.$admNum); ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
    <div class="modal fade" id="<?php echo $Secure->SecureTxt('admDelete'.$admNum); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        <small>Delete Admin</small> (<?php echo $Secure->SecureTxt($adminInfo['1']); ?>)?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/process?deleteAdmin" method="POST" autocomplete="off">
                        

                        <div class="text-right">
                            <button class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                }   
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
</body>
</html>