<nav id="sidebar">
	<div class="sidebar-header d-flex align-items-center">
		<div class="avatar">
			<img src="/admin/img/default.png" alt="..." class="img-fluid rounded-circle">
		</div>
		<div class="title">
			<h1 class="h5"><?php echo $Admin->getFullName($Admin->AdminData()['id']); ?></h1>
			<p style="color:<?php echo $Admin->adminRank($Admin->AdminData()['Rank'])[0]; ?>"><?php echo $Admin->adminRank($Admin->AdminData()['Rank'])[1]; ?></p>
		</div>
	</div> <span class="heading">Main</span>
	<ul class="list-unstyled">
		<li id="l_home">
			<a href="/<?php echo $admDir; ?>/home#p=home"><i class="fa fa-dashboard"></i> Dashboard</a>
		</li>
		<li id="l_servers">
			<a href="#servers" aria-expanded="false" data-toggle="collapse"><i class="fa fa-gamepad"></i> Servers</a>
			<ul id="servers" class="collapse list-unstyled ">
				<!--<li><a href="/<?php echo $admDir; ?>/servers?online"><i class="fa fa-wifi"></i> Online</a></li>
				<li><a href="/<?php echo $admDir; ?>/servers?offline"><i class="fa fa-stop-circle"></i> Offline</a></li>
				<li><a href="/<?php echo $admDir; ?>/servers?paid"><i class="fa fa-money"></i> Paid servers</a></li>
				<li><a href="/<?php echo $admDir; ?>/servers?free"><i class="fa fa-handshake-o"></i> Free servers</a></li>-->
				<li><a href="/<?php echo $admDir; ?>/servers#p=servers"><i class="fa fa-list"></i> All servers</a></li>
				<li><a href="/<?php echo $admDir; ?>/add_server#p=servers"><i class="fa fa-plus"></i> Add server</a></li>
			</ul>
		</li>
		<li id="l_support">
			<a href="#support" aria-expanded="false" data-toggle="collapse"><i class="fa fa-comments"></i> Support</a>
			<ul id="support" class="collapse list-unstyled ">
				<li><a href="/<?php echo $admDir; ?>/support?t=1#p=support"><i class="fa fa-comment"></i> Open</a></li>
				<li><a href="/<?php echo $admDir; ?>/support?t=2#p=support"><i class="fa fa-comments-o"></i> Responsible</a></li>
				<li><a href="/<?php echo $admDir; ?>/support?t=3#p=support"><i class="fa fa-remove"></i> Closed</a></li>
				<li><a href="/<?php echo $admDir; ?>/support#p=support"><i class="fa fa-list"></i> All tickets</a></li>
			</ul>
		</li>
		<li id="l_users">
			<a href="#clients" aria-expanded="false" data-toggle="collapse"><i class="fa fa-users"></i> Users</a>
			<ul id="clients" class="collapse list-unstyled ">
				<li><a href="/<?php echo $admDir; ?>/users#p=users"><i class="fa fa-list"></i> All users</a></li>
				<li><a href="/<?php echo $admDir; ?>/add_user#p=users"><i class="fa fa-plus"></i> Add User</a></li>
			</ul>
		</li>
		<li id="l_box">
			<a href="#boxes" aria-expanded="false" data-toggle="collapse"><i class="fa fa-server"></i> Box</a>
			<ul id="boxes" class="collapse list-unstyled ">
				<li><a href="/<?php echo $admDir; ?>/boxes#p=box"><i class="fa fa-list"></i> All box</a></li>
				<li><a href="/<?php echo $admDir; ?>/add_box#p=box"><i class="fa fa-plus"></i> Add Box</a></li>
			</ul>
		</li>
		<li id="l_orders">
			<a href="/<?php echo $admDir; ?>/orders#p=orders"><i class="fa fa-handshake-o"></i> Orders</a>
		</li>
		<li id="l_games">
			<a href="#games" aria-expanded="false" data-toggle="collapse"><i class="fa fa-gamepad"></i> Games</a>
			<ul id="games" class="collapse list-unstyled ">
				<li><a href="/<?php echo $admDir; ?>/games#p=games"><i class="fa fa-list"></i> All Games</a></li>
				<li><a href="/<?php echo $admDir; ?>/add_game#p=games"><i class="fa fa-plus"></i> Add Game</a></li>
			</ul>
		</li>
		<!--<li id="l_plugins">
			<a href="#plugins" aria-expanded="false" data-toggle="collapse"><i class="fa fa-gamepad"></i> Plugins</a>
			<ul id="plugins" class="collapse list-unstyled ">
				<li><a href="/<?php echo $admDir; ?>/plugins?public">Public Plugins</a></li>
				<li><a href="/<?php echo $admDir; ?>/plugins?private">Private Plugins</a></li>
				<li><a href="/<?php echo $admDir; ?>/plugins">All Plugins</a></li>
				<li><a href="/<?php echo $admDir; ?>/add_plugin"><i class="fa fa-plus"></i> Add Plugin</a></li>
			</ul>
		</li>-->
		<li>
			<a id="l_mods" href="#mods" aria-expanded="false" data-toggle="collapse"><i class="fa fa-gamepad"></i> Mods</a>
			<ul id="mods" class="collapse list-unstyled ">
				<!--<li><a href="/<?php echo $admDir; ?>/mods?public">Public Mods</a></li>
				<li><a href="/<?php echo $admDir; ?>/mods?private">Private Mods</a></li>-->
				<li><a href="/<?php echo $admDir; ?>/mods#p=mods"><i class="fa fa-list"></i> All Mods</a></li>
				<li><a href="/<?php echo $admDir; ?>/add_mod#p=mods"><i class="fa fa-plus"></i> Add Mod</a></li>
			</ul>
		</li>
	</ul> <span class="heading">Extras</span>
	<ul class="list-unstyled">
		<li id="l_settings"><a href="/<?php echo $admDir; ?>/#p=settings"><i class="fa fa-cog"></i> Settings</a></li>
		<li id="l_workers">
			<a href="#workers" aria-expanded="false" data-toggle="collapse"><i class="fa fa-users"></i> Workers</a>
			<ul id="workers" class="collapse list-unstyled ">
				<!--<li><a href="/<?php echo $admDir; ?>/admins?online">Online Workers</a></li>-->
				<li><a href="/<?php echo $admDir; ?>/admins#p=workers"><i class="fa fa-list"></i> All Workers</a></li>
				<li><a href="/<?php echo $admDir; ?>/add_admin#p=workers"><i class="fa fa-plus"></i> Add Worker</a></li>
			</ul>
		</li>
	</ul>
</nav>