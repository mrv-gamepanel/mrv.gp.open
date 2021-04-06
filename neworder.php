<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  neworder.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//////////////////////////

// If do not login;
if (!($User->IsLoged()) == true) {
    $Alert->SaveAlert('You must log in to continue.', 'info');
    header('Location: /login');
    die();
}

// Order Game
if (isset($GET['orderGame'])) {
    $orderGame = $Secure->SecureTxt($GET['orderGame']);

    // Valid game
    if (isset($orderGame) && empty($orderGame) || $orderGame == '' || !is_numeric($orderGame)) {
        $Alert->SaveAlert('Game is not a valid!', 'error');
        header('Location: /neworder');
        die();
    }
    if (empty($Games->gameByID($orderGame)['id'])) {
        $Alert->SaveAlert('Game is not a valid!', 'error');
        header('Location: /neworder');
        die();
    }
} else {
    $orderGame = '';
}

// Game Slots
$GameSlot = Array(
    'cs16' => Array(
        '12'    => '12',
        '14'    => '14',
        '16'    => '16',
        '18'    => '18',
        '20'    => '20',
        '22'    => '22',
        '24'    => '24',
        '26'    => '26',
        '28'    => '28',
        '30'    => '30',
        '32'    => '32'
    ),
    'csgo' => Array(
        '12'    => '12',
        '14'    => '14',
        '16'    => '16',
        '18'    => '18',
        '20'    => '20',
        '22'    => '22',
        '24'    => '24',
        '26'    => '26',
        '28'    => '28',
        '30'    => '30',
        '32'    => '32',
        '34'    => '34',
        '36'    => '36',
        '38'    => '38',
        '40'    => '40',
        '42'    => '42',
        '44'    => '44',
        '46'    => '46',
        '48'    => '48',
        '50'    => '50',
        '52'    => '52',
        '54'    => '54',
        '56'    => '56',
        '58'    => '58',
        '60'    => '60',
        '62'    => '62',
        '64'    => '64'
    ),
    'samp' => Array(
        '50'    => '50',
        '100'   => '100',
        '150'   => '150',
        '200'   => '200',
        '250'   => '250',
        '300'   => '300',
        '350'   => '350',
        '400'   => '400',
        '450'   => '450',
        '500'   => '500'
    ),
    'fivem' => Array(
        '10'    => '10',
        '12'    => '12',
        '14'    => '14',
        '16'    => '16',
        '18'    => '18',
        '20'    => '20',
        '22'    => '22',
        '24'    => '24',
        '26'    => '26',
        '28'    => '28',
        '30'    => '30',
        '32'    => '32',
        '34'    => '34',
        '36'    => '36',
        '38'    => '38',
        '40'    => '40',
        '42'    => '42',
        '44'    => '44',
        '46'    => '46',
        '48'    => '48',
        '50'    => '50',
        '52'    => '52',
        '54'    => '54',
        '56'    => '56',
        '58'    => '58',
        '60'    => '60',
        '62'    => '62',
        '64'    => '64',
        '68'    => '68',
        '70'    => '70',
        '128'   => '128',
    ),
    'fdl' => Array(
        '1'     => '1',
    ),
    'mc' => Array(
        '1'      => '1',
        '2'      => '2',
        '3'      => '3',
        '4'      => '4',
        '5'      => '5',
        '6'      => '6',
        '7'      => '7',
        '8'      => '8',
        '9'      => '9',
        '10'     => '10',
    )
);
// GB
$gameGB = Array(
    'fivem' => Array(
        '1'      => '1 GB',
        '2'      => '2 GB',
        '3'      => '3 GB',
        '4'      => '4 GB',
        '5'      => '5 GB',
        '6'      => '6 GB',
        '7'      => '7 GB',
        '8'      => '8 GB',
        '9'      => '9 GB',
        '10'     => '10 GB',
        '11'     => '11 GB',
        '12'     => '12 GB',
        '13'     => '13 GB',
        '14'     => '14 GB',
        '15'     => '15 GB',
        '16'     => '16 GB',
    ),
    'mc' => Array(
        '1'      => '1 GB',
        '2'      => '2 GB',
        '3'      => '3 GB',
        '4'      => '4 GB',
        '5'      => '5 GB',
        '6'      => '6 GB',
        '7'      => '7 GB',
        '8'      => '8 GB',
        '9'      => '9 GB',
        '10'     => '10 GB',
        '11'     => '11 GB',
        '12'     => '12 GB',
        '13'     => '13 GB',
        '14'     => '14 GB',
        '15'     => '15 GB',
        '16'     => '16 GB',
    )
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $Site->SiteConfig()['site_name']; ?></title>

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
                        <i class="fa fa-list"></i> New Order
                    </h5>
                    <section class="no-padding-top no-padding-bottom">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="block">
                                    <div class="block-body">
                                    <div style="margin-top:30px;"></div>
                                    <form class="form-horizontal" action="/neworder" method="GET">
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Game</label>
                                            <div class="col-sm-9">
                                                <select id="orderGame" name="orderGame" class="form-control mb-3 mb-3" required="" onchange="this.form.submit()">
                                                    <option value="" selected="" disabled="">--select--</option>
                                                    <?php foreach ($Games->GamesList()['Response'] as $k => $v) { ?>
                                                        <option value="<?php echo $Secure->SecureTxt($v['id']); ?>" <?php if(isset($orderGame) && !empty($orderGame) && $Secure->SecureTxt($orderGame) == $v['id']) { echo 'selected="selected"'; } ?>><?php echo $v['Name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                    <?php if(isset($GET['orderGame'])) { ?>
                                        <form id="orderGameServer" name="orderGameServer" class="form-horizontal" method="POST" autocomplete="off" action="/process?orderServer">
                                            <input name="orderGame" value="<?php echo $Secure->SecureTxt($orderGame); ?>" style="display:none;">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Location</label>
                                                <div class="col-sm-9">
                                                    <select id="orderLocation" name="orderLocation" class="form-control mb-3 mb-3" required="" onchange="ChangePrice()">
                                                        <option value="" selected disabled="">--select--</option>
                                                        <option value="Germany" id="sL_">Germany</option>
                                                        <option value="France" disabled="">France (Coming soon)</option>
                                                        <option value="Serbia" disabled="">Serbia (Coming soon)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php if(isset($Games->gameByID($GET['orderGame'])['smName']) && $Games->gameByID($GET['orderGame'])['smName'] == 'fivem') { ?>
                                                <div class="form-group row">
                                                    <div class="col-md-3"><label class="form-control-label">Setup</label></div>
                                                    <div class="col-md-3">
                                                        <input type="text" id="orderSlots" name="orderSlots" class="form-control mb-3 mb-3" required="" onkeyup="return ChangePrice()" onchange="return ChangePrice()" maxlength="3" placeholder="100">
                                                    </div>
                                                    <label class="col-md-2 form-control-label">
                                                        <span>0.46&euro; <small>/slot</small></span>
                                                    </label>
                                                    <div class="col-md-3">
                                                        <select id="orderGB" name="orderGB" class="form-control mb-3 mb-3" onchange="ChangePrice()">
                                                            <option value="" selected>--auto--</option>
                                                            <?php foreach ($gameGB[$Games->gameByID($GET['orderGame'])['smName']] as $s_k => $s_v) { ?>
                                                                <option value="<?php echo $Secure->SecureTxt($s_k); ?>"><?php echo $Secure->SecureTxt($s_v); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-md-1 form-control-label">
                                                        <span>1&euro; <small>/GB</small></span>
                                                    </label>
                                                </div>
                                            <?php } else if(isset($Games->gameByID($GET['orderGame'])['smName']) && $Games->gameByID($GET['orderGame'])['smName'] == 'mc') { ?>
                                                <div class="form-group row">
                                                    <div class="col-md-3"><label class="form-control-label">Setup</label></div>
                                                    <div class="col-md-3">
                                                        <input type="text" id="orderSlots" name="orderSlots" class="form-control mb-3 mb-3" required="" onkeyup="return ChangePrice()" onchange="return ChangePrice()" maxlength="3" placeholder="100">
                                                    </div>
                                                    <label class="col-md-2 form-control-label">
                                                        <span>0.65&euro; <small>/slot</small></span>
                                                    </label>
                                                    <div class="col-md-3">
                                                        <select id="orderGB" name="orderGB" class="form-control mb-3 mb-3" onchange="ChangePrice()">
                                                            <option value="" selected>--auto--</option>
                                                            <?php foreach ($gameGB[$Games->gameByID($GET['orderGame'])['smName']] as $s_k => $s_v) { ?>
                                                                <option value="<?php echo $Secure->SecureTxt($s_k); ?>"><?php echo $Secure->SecureTxt($s_v); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-md-1 form-control-label">
                                                        <span>1&euro; <small>/GB</small></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 form-control-label">Slots</label>
                                                    <div class="col-sm-9">
                                                        <select id="orderSlots" name="orderSlots" class="form-control mb-3 mb-3" required="" onchange="ChangePrice()">
                                                            <option value="" selected disabled="">--select--</option>
                                                            <?php foreach ($GameSlot[$Games->gameByID($GET['orderGame'])['smName']] as $s_k => $s_v) { ?>
                                                                <option value="<?php echo $Secure->SecureTxt($s_v); ?>"><?php echo $Secure->SecureTxt($s_v); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Months</label>
                                                <div class="col-sm-9">
                                                    <select id="orderMonths" name="orderMonths" class="form-control mb-3 mb-3" required="" onchange="ChangePrice()">
                                                        <option value="" selected disabled="">--select--</option>
                                                        <option value="1" id="sM_">1 Month</option>
                                                        <option value="2">2 Months (5% discount)</option>
                                                        <option value="3">3 Months (10% discount)</option>
                                                        <option value="4">4 Months (10% discount)</option>
                                                        <option value="6">6 Months (15% discount)</option>
                                                        <option value="12">1 Year (20% discount)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Mod</label>
                                                <div class="col-sm-9">
                                                    <select name="orderMod" class="form-control mb-3 mb-3">
                                                        <option value="" selected disabled="">--select--</option>
                                                        <?php foreach ($Mods->modsList($Secure->SecureTxt($GET['orderGame']))['Response'] as $m_k => $m_v) { ?>
                                                            <option value="<?php echo $Secure->SecureTxt($m_v['id']); ?>"><?php echo $Secure->SecureTxt($m_v['Name']); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Server Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="orderName" class="form-control" required="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    Price: <span id="orderPrice"><span style='color:#4b8cdc'>0</span>€</span>
                                                </div>
                                                <div class="col-sm-9">
                                                    <button class="btn btn-primary" style="float:right;">Order</button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/public/gp/footer.php'); ?>
    <script type="text/javascript" charset="utf8" src="/assets/plugins/table/datatables.min.js"></script>
    <script type="text/javascript">
        /* Table */
        $(document).ready(function(){
            $('#table_id').DataTable();
        });
        // Calculate Price
        function ChangePrice() {
            var orderSlots      = $('#orderSlots').val();
            var orderLocation   = $('#orderLocation').val();
            if(orderLocation == null) {
                document.getElementById('sL_').selected = true;
            }
            var orderGame       = $('#orderGame').val();
            var orderMonths     = $('#orderMonths').val();
            if(orderMonths == null) {
                document.getElementById('sM_').selected = true;
            }
            if(orderLocation == 'Germany') {
                if (orderGame == 1) {
                    var SlotPrice = '0.375';
                } else if (orderGame == 2) {
                    var SlotPrice = '0.09';
                } else if (orderGame == 3) {
                    <?php if(isset($Games->gameByID($GET['orderGame'])['smName']) && $Games->gameByID($GET['orderGame'])['smName'] == 'fivem') { ?>
                        if(orderSlots > 128) {
                            $('#orderSlots').val('').val('128');
                            ChangePrice();
                        }
                    <?php } ?>
                    // Cena slota;
                    var SlotPrice      = '0.46'; 
                } else if (orderGame == 4) {
                    var SlotPrice = '0.5';
                } else if (orderGame == 5) {
                    var SlotPrice = '2';
                } else if (orderGame == 7) {
                    <?php if(isset($Games->gameByID($GET['orderGame'])['smName']) && $Games->gameByID($GET['orderGame'])['smName'] == 'mc') { ?>
                        if(orderSlots > 500) {
                            $('#orderSlots').val('').val('500');
                            ChangePrice();
                        }
                    <?php } ?>
                    // Cena slota;
                    var SlotPrice      = '0.65'; 
                }
            }
            if (orderLocation == '') {
                $('#orderPrice').html("<span style='color:#4b8cdc'>Choose Location</span>");
                return false;
            } else if(orderSlots == '') {
                $('#orderPrice').html("<span style='color:#4b8cdc'>Choose Slots</span>");
                return false;
            } else if(orderMonths == '') {
                $('#orderPrice').html("<span style='color:#4b8cdc'>Choose Months</span>");
                return false;
            }

            var orderDiscount = 0;
            var Location = '100';

            if(orderMonths == '2')
                var orderDiscount = (5/100).toFixed(2);
            else if(orderMonths == '3')
                var orderDiscount = (10/100).toFixed(2);
            else if(orderMonths == '4')
                var orderDiscount = (10/100).toFixed(2);
            else if(orderMonths == '6')
                var orderDiscount = (15/100).toFixed(2);
            else if(orderMonths == '12')
                var orderDiscount = (20/100).toFixed(2);

                var o_ = (orderSlots * SlotPrice<?php 
                if(isset($Games->gameByID($GET['orderGame'])['smName']) && $Games->gameByID($GET['orderGame'])['smName'] == 'fivem') { ?>
                    + Number($('#orderGB').val())
                <?php } else if(isset($Games->gameByID($GET['orderGame'])['smName']) && $Games->gameByID($GET['orderGame'])['smName'] == 'mc') { ?>
                    + Number($('#orderGB').val())
                <?php } ?>);

            var LocationPrice = Location.split('-', 1);
            var orderPrice = (((o_) - ((o_) * orderDiscount)) * orderMonths * LocationPrice/100).toFixed(2);

            $('#orderPrice').html("<span style='color:#4b8cdc'>"+orderPrice+"</span> &euro;");
        }
    </script>
</body>
</html>