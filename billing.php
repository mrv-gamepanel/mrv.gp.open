<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  billing.php
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Billing | <?php echo $Site->SiteConfig()['site_name']; ?></title>

    <!--[HEAD]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="/assets/plugins/table/datatables.min.css?<?php echo time(); ?>">
</head>
<body>
    <!--[HEADER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/header.php'); ?>

    <!--[CONTENT]-->
    <div class="container mt150">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">
                        <i class="fa fa-list"></i> Billing
                        <span style="float:right;">
                            <a href="/neworder" class="btn btn-sm btn-danger active" ><i class="fa fa-plus"></i> New Order</a>
                        </span>
                    </h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Game</th>
                                        <th>Location</th>
                                        <th>Slots</th>
                                        <th>Price</th>
                                        <th>Months</th>
                                        <th>Gamemode</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="accList">
                                    <?php foreach ($Billing->BillingsByUser($User->UserData()['id'])['Response'] as $k => $v) { ?>
                                        <tr id="tr-id-<?php echo $v['id']; ?>" class="tr-class-<?php echo $v['id']; ?>" data-title="bootstrap table" data-object='{"key": "value"}' onclick="document.location.href = '/order?id=<?php echo $v['id']; ?>'" style="cursor:pointer;">
                                            <td><a href="/order?id=<?php echo $Secure->SecureTxt($v['id']); ?>"><img src="<?php echo $Secure->SecureTxt($Games->gameByID($v['gameID'])['Icon']); ?>" alt="<?php echo $Secure->SecureTxt($Games->gameByID($v['gameID'])['Name']); ?>" title="<?php echo $Secure->SecureTxt($Games->gameByID($v['gameID'])['Name']); ?>" style="width:20px;"> <?php echo $Secure->SecureTxt($Games->gameByID($v['gameID'])['Name']); ?></a></td>
                                            <td><?php echo $Secure->SecureTxt($v['Location']); ?></td>
                                            <td><?php echo $Secure->SecureTxt($v['Slots']); ?></td>
                                            <td><?php echo @$Secure->pNormalMoney($v['Price'], 'EUR', '1'); ?></td>
                                            <td><?php echo $Secure->SecureTxt($v['Months']); ?></td>
                                            <td><?php echo $Secure->SecureTxt($Mods->getModByID($v['modID'])['Name']); ?></td>
                                            <td><?php echo $Secure->SecureTxt($v['orderDate']); ?></td>
                                            <td><?php echo $Billing->orderStatus($Secure->SecureTxt($v['orderStatus'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--[FOOTER]-->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
    <script type="text/javascript" charset="utf8" src="/assets/plugins/table/datatables.min.js"></script>
    <script type="text/javascript">
        /* Table */
        $(document).ready(function(){
            // $('#table_id').DataTable();
        });
    </script>
</body>
</html>