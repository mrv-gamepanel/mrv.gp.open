<!--[ALERTS]-->
<div id="msg_alert"><?php echo $Alert->PrintAlert(); ?></div>
<script type="text/javascript">
    setTimeout(function() {
        document.getElementById('msg_alert').innerHTML = '<?php echo $Alert->RemoveAlert(); ?>';
    }, 5000);
</script>

<div class="loader" style="display:none;">
    <div class="loader-container">
        <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
            <circle fill="#f5f5f5" stroke="none" cx="6" cy="50" r="6">
              <animateTransform attributeName="transform" dur="1s" type="translate" values="0 15 ; 0 -15; 0 15" repeatCount="indefinite" begin="0.1" />
            </circle>
            <circle fill="#f5f5f5" stroke="none" cx="30" cy="50" r="6">
                <animateTransform attributeName="transform" dur="1s" type="translate" values="0 10 ; 0 -10; 0 10" repeatCount="indefinite" begin="0.2" />
            </circle>
            <circle fill="#f5f5f5" stroke="none" cx="54" cy="50" r="6">
                <animateTransform attributeName="transform" dur="1s" type="translate" values="0 5 ; 0 -5; 0 5" repeatCount="indefinite" begin="0.3" />
            </circle>
        </svg>
        <span>loading</span>
    </div>
</div>

<!--[NAVIGATION]-->
<div id="r-header" class="d-flex mx-auto flex-column">
    <nav id="navbar-header-r" class="navbar navbar-expand-md fixed-header-layout">
        <div class="container main-header-coodiv-s">
            <a class="navbar-brand" href="/home">
                <img class="w-logo" src="/assets/img/mrv-logo.png?<?php echo time(); ?>" alt="MRV-Hosting.com ~ Logo" style="max-height:40px;margin-left:-60px;"/>
                <img class="b-logo" src="/assets/img/mrv-logo.png?<?php echo time(); ?>" alt="MRV-Hosting.com ~ Logo" style="max-height:35px;"/>
            </a>
            <button class="navbar-toggle offcanvas-toggle menu-btn-span-bar ml-auto" data-toggle="offcanvas" data-target="#menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="collapse navbar-collapse navbar-offcanvas" id="menu">
                <ul class="navbar-nav">
                    <!--<li class="nav-item">
                        <a class="nav-link" href="//cloud.mrv-hosting.com"><i class="fa fa-cloud"></i> MRV Cloud</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="//forum.mrv-hosting.com"><i class="fa fa-comments-o"></i> MRV Forum</a>
                    </li>-->
                    <?php if (!($User->IsLoged()) == false) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/gp-home"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/servers"><i class="fa fa-gamepad"></i> Servers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/support"><i class="fa fa-support"></i> Support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/billing"><i class="fa fa-shopping-cart"></i> Billing</a>
                    </li>
                    <?php } ?>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php if (!($User->IsLoged()) == false) { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Hello, <?php echo $Secure->SecureTxt($User->UserDataID($User->UserData()['id'])['Name']); ?>!</a>
                            <ul class="dropdown-menu" aria-labelledby="">
                                <li><a class="dropdown-item" href="/gp-home"><i class="fa fa-sign-in"></i> Switch to Game Panel</a></li>
                                <li><a class="dropdown-item" href="/profile"><i class="fa fa-edit"></i> Edit account details</a></li>
                                <li><a class="dropdown-item" href="/profile#changePW"><i class="fa fa-key"></i> Change password</a></li>
                                <li><a class="dropdown-item" href="/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item" style="text-align:center;padding:5px 5px;">
                            <a href="/login#l" class="btn btn-outline-light">Sign in</a>
                        </li>
                        <li class="nav-item" style="text-align:center;padding:5px 5px;">
                            <a href="/register" class="btn btn-primary" style="color:#fff;"><i class="fa fa-lock-open"></i> Sign up</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</div>

<!--SIMPLE - SPACE :)-->
<div id="space" style="margin:80px;"></div>