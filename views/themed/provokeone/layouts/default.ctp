<?php echo $this->Facebook->html(); ?>
	<head>
		<meta charset="utf-8"/>
		<title>
			<?php __('CakePHP: the rapid development php framework:'); ?>
			<?php echo $title_for_layout; ?>
		</title>
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<?php echo $this->Html->css('less'); ?>
		<?php echo $this->Html->css('jquery-ui'); ?>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> 
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script> 
		
		<script type="text/javascript"> 
		$.fx.speeds._default = 500;
		$(document).ready(function() {
			
			var $dialog = $( "#login" )
				.dialog({
					autoOpen: false,
					title: 'Login',
					draggable: false,
					resizable: false,
					height: 350,
					width: 560,
					modal: true,
					show: "fadein",
					hide: "fadeout",
				});
		
			$('#opener').click(function() {
				$dialog.dialog('open');
				// prevent the default action, e.g., following a link
				return false;
			});
			
			$(".ui-widget-overlay").live("click", function() {  $("#login").dialog("close"); } );
			
			
		    $("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)  
		  
		    $("ul.topnav li span").click(function() { //When trigger is clicked...  
		  
		        //Following events are applied to the subnav itself (moving subnav up and down)  
		        $(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click  
		  
		        $(this).parent().hover(function() {  
		        }, function(){  
		            $(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up  
		        });  
		  
		        //Following events are applied to the trigger (Hover events for the trigger)  
		        }).hover(function() {  
		            $(this).addClass("subhover"); //On hover over, add class "subhover"  
		        }, function(){  //On Hover Out  
		            $(this).removeClass("subhover"); //On hover out, remove class "subhover"  
		    });  

		});
		
		
		</script> 
			
		<?php //echo $this->Html->script('less-grid-4'); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<!-- Adding "maximum-scale=1" fixes the Mobile Safari auto-zoom bug: http://filamentgroup.com/examples/iosScaleBug/ -->
	</head>
	
	<body lang="en">
		<header> 
			<!-- logo -->
			<h1><?php echo $this->Html->link(__('Provoke One', true), array('controller' => 'pages', 'action' => 'display')); ?></h1> 
			
			<!-- Absolute top nav -->
			<nav class="account">
				<ul class="account-bar">
					<li class="account-cell account-login">
						<?php // Currently name isn't stored in DB, so we can only use name if they're logged in through FB
							if($account): ?>
							<?php if(isset($facebook_user)): ?>
								Hi, <?php echo $facebook_user['first_name']; ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $this->Facebook->logout(array('redirect' => array('controller' => 'accounts', 'action' => 'logout'))); ?>
							<?php else: ?>
								Welcome!&nbsp;&nbsp;|&nbsp;&nbsp;<?php if(isset($facebook_user)): echo $this->Facebook->logout(array('redirect' => array('controller' => 'accounts', 'action' => 'logout'))); else: echo '<a href="/accounts/logout/">logout</a>'; endif; ?>
							<?php endif; ?>
						<?php else: ?>
							
							<?php echo $this->Html->link(__('Login', true), '#', array('id' => 'opener')); ?> or <?php echo $this->Html->link(__('Create an Account', true), array('controller' => 'Accounts', 'action' => 'register')); ?>
						<?php endif; ?>
						</li>
					<li class="account-cell account-view"><?php echo $this->Html->link(__('Account', true), array('controller' => 'accounts', 'action' => 'myaccount'), array('class' => 'account-link')); ?></li>
					<li class="account-cell account-support"><?php echo $this->Html->link(__('Support', true), 'http://provokeone.mojohelpdesk.com', array('class' => 'account-link', 'target' => '_NEW')); ?></li>
					<li class="account-cell account-browse"><?php echo $this->Html->link(__('Browse', true), array('#'), array('class' => 'account-dropdown')); ?></li>
				</ul>
			</nav>
			
			<nav>
				<ul id="topnav">
					<li><a href="/ctagame">Home</a></li>
					<li>
						<a href="/sd_cta/reports" class="drops" id="link_one">News</a>
						<ul class="subnav" id="box_one" style="display:none;">
							<li><a href="/sd_cta/reports">Reports</a></li>
							<li><a href="/sd_cta/messages">Mail</a></li>
							<li><a href="/sd_cta/users/profile">Profile</a></li>
						</ul>
					</li>
					<li>
						<a href="/sd_cta/buildings" class="drops" id="link_two">Economy</a>
						<ul class="subnav" id="box_two" style="display:none;">
							<li><a href="/sd_cta/buildings">Building</a></li>
							<li><a href="/sd_cta/populations">Population</a></li>
							<li><a href="/sd_cta/trades">Trade</a></li>
							<li><a href="/sd_cta/technologies">Technology</a></li>
							<li><a href="/sd_cta/statistics">Kingdom Stats</a></li>
						</ul>
					</li>
					<li>
						<a href="/sd_cta/armies" class="drops" id="link_three">Military</a>
						<ul class="subnav" id="box_three" style="display:none;">
							<li><a href="/sd_cta/weapons">Weapons</a></li>
							<li><a href="/sd_cta/armies">Units</a></li>
							<li><a href="/sd_cta/strategies">Strategy</a></li>
							<li><a href="/sd_cta/attacks">Attack</a></li>
							<li><a href="/sd_cta/spells">Spell</a></li>
							<li><a href="/sd_cta/retaliations">Retaliations</a></li>
						</ul>
					</li>
					<li>
						<a href="/sd_cta/leaderboards" class="drops" id="link_three">Community</a>
						<ul class="subnav" id="box_three" style="display:none;">
							<li><a href="/sd_cta/alliances">Alliance</a></li>
							<li><a href="/sd_cta/leaderboards/findFriends">Friends</a></li>
							<li><a href="/sd_cta/leaderboards">Rankings</a></li>
							<li><a href="/sd_cta/fames">Hall of Fames</a></li>
						</ul>
					</li>
					<li>
						<a href="/sd_cta/forums" class="drops" id="link_three">Forums</a>
						<ul class="subnav" id="box_three" style="display:none;">
							<li><a href="/ctagameforum/" target="_NEW">Public Forum</a></li>
							<li><a href="/sd_cta/forums">Alliance Forum</a></li>
						</ul>
					</li>
					<li>
						<a href="/ctagameforum/viewtopic.php?f=9&t=221" class="drops" id="link_three">Help</a>
						<ul class="subnav" id="box_three" style="display:none;">
							<li><a href="/ctagameforum/viewtopic.php?f=9&t=221" target="_NEW">Game Guide</a></li>
							<li><a href="/sd_cta/settings">Settings</a></li>
							<li><a href="/sd_cta/premiums">Premium</a></li>
							<?php if($account['Account']['role'] == 'admin' || $account['Account']['role'] == 'council') { ?><li><a href="/sd_cta/admins">Admin</a></li><?php } ?>
						</ul>
					</li>
				</ul>	
			
			</nav>
			
			<div style="clear:both;"></div>
		</header> 
		<section id="main">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->Session->flash('auth'); ?>
			<?php echo $content_for_layout; ?>
		</section>
		<!-- Start login dialog --> 
		<div style="display: none;" id="login" title="Login">
		
			<div style="float:left; width:50%;">
			<?php echo $this->Form->create('Account', array('controller' => 'accounts', 'action' => 'login')); ?>
			 
			    <?php
			        echo $this->Form->input('email');
			        echo $this->Form->input('password');
			        echo $this->Form->input('keep me logged in', array('type' => 'checkbox'))
			    ?>
			 
			<?php echo $this->Form->end('Login'); ?>
			</div>
			<?php //debug($Account); ?>
			<div style="float:left;width:50%;">
			<?php if(isset($facebook_user)): ?>
				<?php if(isset($facebook_user)): echo $this->Facebook->logout(array('redirect' => array('controller' => 'accounts', 'action' => 'logout'))); else: echo '<a href="/accounts/logout/">logout</a>'; endif; ?>
				<?php //debug($this->facebook_user); ?>
			<?php else: ?>
				You can also use your Facebook:
				<?php echo $this->Facebook->login(array('perms' => 'email', 'size' => 'xlarge')); ?>
			<?php endif; ?>
			</div>
		</div>
		<!-- End login Dialog --> 
		
		<?php echo $this->element('sql_dump'); ?>
		<?php echo $this->Facebook->init(); ?>
	</body>
	
</html>