<div class="col-lg-12">
    <div class="block">
        <div class="title"><strong>Server Commands</strong></div>
        <div class="srvAction">
            <?php if ($Server->serverByID($serverID)['isStart'] === '1') { ?>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-warning" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'restart', true)" class="btn btn-sm btn-info">
                        <i class="fa fa-refresh"></i> Restart
                    </a>
                </li>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-danger" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'stop', true)" class="btn btn-sm btn-danger">
                        <i class="fa fa-power-off"></i> Stop
                    </a>
                </li>
            <?php } else { ?>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-success" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'restart', true)" class="btn btn-sm btn-success">
                        <i class="fa fa-play"></i> Start
                    </a>
                </li>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-danger" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'reinstall', true)" class="btn btn-sm btn-danger">
                        <i class="fa fa-refresh"></i> Reinstall
                    </a>
                </li>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-danger" href="javascript:;" onclick="removeServer('<?php echo $serverID; ?>')" class="btn btn-sm btn-danger">
                        <i class="fa fa-remove"></i> Remove Server
                    </a>
                </li>
            <?php } ?>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="block">
        <div class="title"><strong>Support Time Commands</strong></div>
        <div class="srvAction">
            <li style="display:inline-block;padding:5px;">
                <a class="btn <?php if($Secure->SecureTxt($Server->serverByID($serverID)['serverOption']) == '1') { ?>btn-danger<?php } else { ?>btn-warning<?php } ?>" href="javascript:;" onclick="blockServerAction('<?php echo $serverID; ?>', 'serverOption')" class="btn btn-sm btn-danger">
                    <?php if($Secure->SecureTxt($Server->serverByID($serverID)['serverOption']) == '1') { ?>
                        <i class="fa fa-lock"></i> Block Start Options
                    <?php } else { ?>
                        <i class="fa fa-lock"></i> Allow Start Options
                    <?php } ?>
                </a>
            </li>
            <li style="display:inline-block;padding:5px;">
                <a class="btn <?php if($Secure->SecureTxt($Server->serverByID($serverID)['ftpBlock']) == '0') { ?>btn-danger<?php } else { ?>btn-warning<?php } ?>" href="javascript:;" onclick="blockServerAction('<?php echo $serverID; ?>', 'ftpBlock')" class="btn btn-sm btn-danger">
                    <?php if($Secure->SecureTxt($Server->serverByID($serverID)['ftpBlock']) == '0') { ?>
                        <i class="fa fa-lock"></i> Block FTP
                    <?php } else { ?>
                        <i class="fa fa-unlock"></i> Allow FTP
                    <?php } ?>
                </a>
            </li>
        </div>
    </div>
</div>