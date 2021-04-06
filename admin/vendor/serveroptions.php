<div class="col-lg-12">
    <div class="block">
        <div class="title"><strong>Server Commands</strong></div>
        <div class="srvAction">
            <?php if ($Server->serverByID($serverID)['isStart'] == '1') { ?>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-warning" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'restart')" class="btn btn-sm btn-info">
                        <i class="fa fa-refresh"></i> Restart
                    </a>
                </li>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-danger" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'stop')" class="btn btn-sm btn-danger">
                        <i class="fa fa-power-off"></i> Stop
                    </a>
                </li>
            <?php } else { ?>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-success" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'restart')" class="btn btn-sm btn-success">
                        <i class="fa fa-play"></i> Start
                    </a>
                </li>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-danger" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'reinstall')" class="btn btn-sm btn-danger">
                        <i class="fa fa-refresh"></i> Reinstall
                    </a>
                </li>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-danger" href="javascript:;" onclick="actionServer('<?php echo $serverID; ?>', 'remove')" class="btn btn-sm btn-danger">
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
                    <a class="btn btn-success" class="btn btn-sm btn-success">
                        <i class="fa fa-play"></i> Block Start/Stop/Restart/Reinstall
                    </a>
                </li>
                <li style="display:inline-block;padding:5px;">
                    <a class="btn btn-success" class="btn btn-sm btn-success">
                        <i class="fa fa-play"></i> Block FTP
                    </a>
                </li>
        </div>
    </div>
</div>