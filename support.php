<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  support.php
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
                        <i class="fa fa-list"></i> Support
                        <span style="float:right;">
                            <a href="" class="btn btn-sm btn-danger active" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Open ticket</a>
                        </span>
                    </h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Server</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Date&Time</th>
                                    </tr>
                                </thead>
                                <tbody id="accList">
                                    <?php foreach ($Support->ticketsList($User->UserData()['id'])['Response'] as $k => $v) { ?>
                                        <tr id="tr-id-<?php echo $v['id']; ?>" class="tr-class-<?php echo $v['id']; ?>" data-title="bootstrap table" data-object='{"key": "value"}' onclick="document.location.href = '/ticket?id=<?php echo $v['id']; ?>'" style="cursor:pointer;">
                                            <td><?php echo @$Secure->LimitText($Secure->SecureTxt($v['Title']), 30) ?></td>
                                            <?php if (isset($v['serverID']) && $Secure->SecureTxt($v['serverID']) == 'all') { ?>
                                                <td>All servers</td>
                                            <?php } else { ?>
                                                <td><img src="<?php echo @$Games->gameByID($Server->serverByID($v['serverID'])['gameID'])['Icon']; ?>" alt="" style="width:20px;">
                                                    <?php echo @$Secure->SecureTxt($Server->serverByID($v['serverID'])['Name']); ?>
                                                    <small>(<?php echo @$Server->ipAddress($v['serverID']); ?>)</small>
                                                </td>
                                            <?php } ?>
                                            <td>
                                                <span style="color:<?php echo @$Secure->SecureTxt($Support->ticketPriority($v['Priority'])['0']); ?>;"><?php echo $Secure->SecureTxt($Support->ticketPriority($v['Priority'])['1']); ?></span>
                                            </td>
                                            <td><span style="color:<?php echo @$Secure->SecureTxt($Support->ticketStatus($v['Status'])['0']); ?>;"><?php echo @$Secure->SecureTxt($Support->ticketStatus($v['Status'])['1']); ?></span></td>
                                            <td><?php echo $v['Date']; ?></td>
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
    <!--[NEW TICKET]-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Open ticekt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="openTicketForm" action="#" method="POST" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Title</label>
                                <input id="Title" type="text" name="Title" required="" class="form-control" placeholder="Start server problem">
                            </div>
                            <div class="col-md-6">
                                <label>Priority Type</label>
                                <select id="Type" name="Type" required="" class="form-control">
                                    <option value="1" selected="">Normal</option>
                                    <option value="2">Medium</option>
                                    <option value="3">Urgent</option>
                                </select>
                            </div>
                        </div> <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Select server</label>
                                <select id="serverID" name="serverID" required="" class="form-control">
                                    <option value="">--all servers--</option>
                                    <?php foreach ($Servers->serversByUser($User->UserData()['id'])['Response'] as $k => $v) { ?>
                                        <option value="<?php echo $v['id'] ?>">[<?php echo $Games->gameByID($Server->serverByID($v['id'])['gameID'])['smName']; ?>] # <?php echo $Secure->SecureTxt($v['Name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> <br>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Please describe the problem to us in order to deal with it as well as possible</label>
                                <textarea id="Problem" name="Problem" required="" class="form-control" rows="5" placeholder="No I can start the server .."></textarea>
                            </div>
                        </div> <hr>
                        <span class="btn btn-sm btn-primary" style="float:right;" onclick="openTicket()"><i class="fa fa-send"></i> Send</span>
                    </form>
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
            $('#table_id').DataTable();
        });
    </script>
</body>
</html>