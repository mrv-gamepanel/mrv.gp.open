<div class="loader">
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
<!-- Navigation -->
<div id="r-header" class="d-flex mx-auto flex-column">
    <nav id="navbar-header-r" class="navbar navbar-expand-md fixed-header-layout">
        <div class="container main-header-coodiv-s">
            <a class="navbar-brand" href="/home">
                <img class="w-logo" src="/assets/img/mrv-logo.png?<?php echo time(); ?>" alt="MRV-Hosting.com ~ Logo" style="max-height:40px;margin-left:-60px;"/>
                <img class="b-logo" src="/assets/img/mrv-logo.png?<?php echo time(); ?>" alt="MRV-Hosting.com ~ Logo" style="max-height:35px;"/>
                <!-- <h3 style="color:#fff;">MRV-<em style="color:#4b8cdc;font-style:normal;">Hosting</em>.com</h3> -->
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
                        <a class="nav-link" href="/gp-home"><i class="fa fa-dashboard"></i> GamePanel</a>
                    </li>
                    <?php } ?>
                    <!--<li class="nav-item">
                        <a class="nav-link" href="/contact"><i class="fa fa-phone"></i> Contact</a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" href="/billing"><i class="fa fa-shopping-cart"></i> Billing</a>
                    </li>
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
    </nav><br><br>
    <section id="r-slider">
        <div id="owl-main" class="owl-carousel height-md owl-inner-nav owl-ui-lg">                   
            <div class="item" style="background-image: url('/assets/images/slider-1.png');">
                <div class="container">
                    <div class="caption vertical-center">                              
                        <h4 class="fadeInDown-1" style="margin-top:100px;">High Tickrate and Performance</h4>
                        <p class="fadeInDown-2">High-performance Counter-Strike: Global Offensive servers with maximum uptime.Low latency networks and high-end enterprise server hardware offers near-perfect bullet registration.</p>
                        <p class="fadeInDown-2">Free voice server included!</p><br/>
                        <div class="fadeInDown-3">
                            <a href="/neworder" class="btn btn-danger" style="color:#fff;">Order now <i class="fa fa-chevron-right"></i></a>
                        </div>                           
                    </div>
                </div>
            </div>
            
            <!--<div class="item" style="background-image: url('/assets/images/slider-6.png');">
                <div class="container">
                    <div class="caption vertical-center">                              
                        <h4 class="fadeInDown-1">ARK Survival Of The Fittest Standalone</h4>
                        <p class="fadeInDown-2">The mode was first available as a Total Conversion game system and debuted worldwide with a 70-person slaughter fest of a tournament. The game mode consists of a large-scale battle which takes place on a modified version of the primal ARK, with the objective being to eliminate all other survivors before they can eliminate you.</p><br/>
                        <div class="fadeInDown-3">
                            <a href="#" class="btn btn-primary">Configure and Order Now <i class="fa fa-chevron-right"></i></a>
                        </div>                           
                    </div>
                </div>
            </div>
            
            <div class="item" style="background-image: url('/assets/images/slider-3.png');">
                <div class="container">
                    <div class="caption vertical-center">                              
                        <h4 class="fadeInDown-1">High Tickrate and Performance</h4>
                        <p class="fadeInDown-2">High-performance Counter-Strike: Global Offensive servers with maximum uptime.Low latency networks and high-end enterprise server hardware offers near-perfect bullet registration.</p>
                        <p class="fadeInDown-2">Free voice server included!</p><br/>
                        <div class="fadeInDown-3">
                            <a href="#" class="btn btn-warning">Order now <i class="fa fa-chevron-right"></i></a>
                        </div>                           
                    </div>
                </div>
            </div>
            
            <div class="item" style="background-image: url('/assets/images/slider-4.png');">
                <div class="container">
                    <div class="caption vertical-center">                              
                        <h4 class="fadeInDown-1">GTA 5: FiveM Server Rental</h4>
                        <p class="fadeInDown-2">You are looking for an GTA 5: FiveM server with an excellent price-performance ratio, which has no contract obligation and offers you maximum flexibility? Then use our independent GTA 5: FiveM Server comparison now and find the right GTA 5: FiveM Game server for you in just a few minutes. The GTA 5: FiveM Server comparison was created by our renowned gameserver experts and is completely independent and transparent, so that you will find one hundred percent the right one for you</p><br/>
                        <div class="fadeInDown-3">
                            <a href="#" class="btn btn-success">Order now <i class="fa fa-chevron-right"></i></a>
                        </div>                           
                    </div>
                </div>
            </div>
            
            <div class="item" style="background-image: url('/assets/images/slider-5.png');">
                <div class="container">
                    <div class="caption vertical-center">                              
                        <h4 class="fadeInDown-1">Left 4 Dead 2 Server</h4>
                        <p class="fadeInDown-2">Fight for your life against huge hordes of zombies while you play Left 4 Dead 2 with your friends!</p>
                        <p class="fadeInDown-2">Lawr is here to give you affordable access to the best Left 4 Dead 2 dedicated server hosting out there, with none of the hassle. And with our new Game Panel interface, it’s easier than ever to get your private server started!</p><br/>
                        <div class="fadeInDown-3">
                            <a href="#" class="btn btn-info">Order now <i class="fa fa-chevron-right"></i></a>
                        </div>                           
                    </div>
                </div>
            </div>-->                  
        </div>
    </section>
</div>