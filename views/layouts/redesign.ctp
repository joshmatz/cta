<?php echo $this->Facebook->html(); ?>
	<head>
		<link type="text/css" href="/cometchat/cometchatcss.php" rel="stylesheet" charset="utf-8">
		<script type="text/javascript" src="/cometchat/cometchatjs.php" charset="utf-8"></script>
		<meta charset="utf-8"/>
		<title>
			<?php echo $title_for_layout; ?>
		</title>
		<?php echo $this->Html->meta('favicon.ico', '/img/favicon.ico', array('type' => 'icon'));?>
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<?php echo $this->Html->css('less'); ?>
		<?php echo $this->Html->css('jquery-ui'); ?>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> 
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script> 
		<script src="http://cdn.jquerytools.org/1.2.5/tiny/jquery.tools.min.js"></script>
		<?php echo $this->Html->script('hoverIntent'); ?>
		<?php echo $this->Html->script('superfish'); ?>
		<?php echo $this->Html->script('jquery.jeditable'); ?>
		<?php echo $this->Html->script('jquery.autocomplete.min'); ?>
		<?php echo $this->Html->script('jquery.jclock'); ?>
		<script type="text/javascript"> 
		$.fx.speeds._default = 500;
		$(document).ready(function() {
			<?php if(!$account): ?>
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
			<?php endif; ?>
			
			var options = {
		        utc: true,
		        utcOffset: -5,
		        // seedTime must be a number representing time in milliseconds
		        seedTime: <?php echo (time() * 1000); ?>
			}
		    $('.jclock').jclock(options);
		      
			$("ul.sf-menu").superfish({ 
	            pathClass:  'current' 
	        }); 

		});
		
		
		</script> 
			
		<?php //echo $this->Html->script('less-grid-4'); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<!-- Adding "maximum-scale=1" fixes the Mobile Safari auto-zoom bug: http://filamentgroup.com/examples/iosScaleBug/ -->
	</head>
	
	<body lang="en">
	<script type='text/javascript'>
var mojo_form_widget_params = {};
mojo_form_widget_params.tab_name='get_support';
mojo_form_widget_params.tab_bg_color='#292929';
mojo_form_widget_params.tab_top_offset='400';
mojo_form_widget_params.title='How can we help?';
mojo_form_widget_params.description='Please tell us about your request, please include your kingdom name.';
mojo_form_widget_params.token='9387_28817';
mojo_form_widget_params.site_url='provokeone.mojohelpdesk.com';
</script>
<style type='text/css'>@import url('//asset.mojohelpdesk.com/stylesheets/form_widget.css');</style>
<script type='text/javascript' src='//asset.mojohelpdesk.com/javascripts/form_widget.js'></script>

		<header> 
			<!-- logo -->
			<div  style="float:left;">
			<h1 style="margin:0px;"><?php echo $this->Html->link(__('Provoke One', true), '/play', array('style' => 'display:block;text-indent:-9999px;width:320px;height:120px;')); ?></h1> 
			</div> 
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
					<li class="account-cell account-view"><?php echo $this->Html->link(__('Account', true), 'http://provokeone.com/accounts/myaccount', array('class' => 'account-link', 'target' => '_NEW')); ?></li>
					<li class="account-cell account-support"><?php echo $this->Html->link(__('Support', true), 'http://provokeone.mojohelpdesk.com', array('class' => 'account-link', 'target' => '_NEW')); ?></li>
					<li class="account-cell account-browse"><?php echo $this->Html->link(__('Browse', true), array('#'), array('class' => 'account-dropdown')); ?></li>
				</ul>
			</nav>
			
			<div class="premiumBox">
				<div class="premiumDisplay"><a href="/premiums"><img src="/img/layout/premium_points.png" /> <?php echo number_format($points); ?></a></div>
				<div id="countdown"></div>
			</div>
			
			<?php 
				// apex voting only display if they can get resources
				$time = date("Y-m-d H:m:s"); 
				$strToTime = strtotime($time);
				$apexTime = strtotime($user['User']['apex']);
		    	if($user['User']['apex'] == 0 || $apexTime <= ($strToTime - 60*60*24)){ 	
		    ?>
				<div class="votenow">
					<a href='/users/apex' title='MMORPGs' target="_NEW"><img src='http://apexwebgaming.com/images/vote_1.gif' title='MMORPGs' alt='MMORPGs' /></a>
					<br />Earn free resources for voting! <?php if(!isset($user['User']['Building'])) echo number_format($user['Building']['castle'] * 350) . " wood, " . number_format($user['Building']['castle'] * 250) . " iron and " . number_format($user['Building']['castle'] * 3750) . " gold!"; else echo number_format($user['User']['Building']['castle'] * 350) . " wood, " . number_format($user['User']['Building']['castle'] * 250) . " iron and " . number_format($user['User']['Building']['castle'] * 3750) . " gold!"; ?>	
				</div>
			<?php }?>
			
			<div class="resources">
				<div class="land"><?php echo $html->image("/img/land.png", array("alt" => "Land", "height" => 18)) ?><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['land']); else echo number_format($user['User']['Resource']['land']) ?></b></div>
				<div class="castle"><?php echo $html->image("/img/castle.png", array("alt" => "Castle", "height" => 16)) ?><b><?php if(!isset($user['User']['Building'])) echo number_format($user['Building']['castle']); else echo number_format($user['User']['Building']['castle']) ?></b></div>
				<div class="gold"><?php echo $html->image("/img/gold.png", array("alt" => "Gold", "height" => 18)) ?><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['gold']); else echo number_format($user['User']['Resource']['gold']) ?></b></div>
				<div class="food"><?php echo $html->image("/img/food.png", array("alt" => "Food", "height" => 18)) ?><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['food']); else echo number_format($user['User']['Resource']['food']) ?></b></div>
				<div class="iron"><?php echo $html->image("/img/iron.png", array("alt" => "Iron", "height" => 18)) ?><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['iron']); else echo number_format($user['User']['Resource']['iron']) ?></b></div>
				<div class="wood"><?php echo $html->image("/img/wood.png", array("alt" => "Wood", "height" => 18)) ?><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['wood']); else echo number_format($user['User']['Resource']['wood']) ?></b></div>
				<!--  <div class="energy"><?php // echo $html->image("http://www.provokeone.com/img/energy.png", array("alt" => "Energy", "height" => 18)) ?>Energy:<br /><b><?php //if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['energy']) . " / " . number_format($user['Resource']['max_energy']); else echo number_format($user['User']['Resource']['energy']) . " / " . number_format($user['User']['Resource']['max_energy']) ?></b></div> -->
				<?php if($newMessages != NULL) { echo "<a href=\"/messages/\"><img src=\"/img/layout/mail.png\" alt=\"New Mail!\" style=\"float:right;margin-right:3px;\"></a>"; } ?>
				<?php if($newReport) { echo "<a href=\"/reports/\"><img src=\"/img/reports_new.png\" alt=\"New Report!\" style=\"float:right;margin-right:3px;\"></a>"; } ?>
			</div>
			
			<nav>
				<ul class="sf-menu"> 
					<li class="current"> 
						<a href="/play">Home</a> 
					</li> 
					<li> 
						<a href="#" class="drops" id="link_one">Kingdom<span style="float:right; margin-top: -8px; margin-right: -5px;"></span></a>
						<ul class="subnav" id="box_one" style="display:none;">
							<li><a href="/reports">Reports</a></li>
							<li><a href="/messages">Mail</a></li>
							<li><a href="/users/profile">Profile</a></li>
						</ul>
					</li> 
					<li> 
						<a href="#" class="drops" id="link_two">Economy<span style="float:right; margin-top: -8px; margin-right: -5px;"></span></a>
						<ul class="subnav" id="box_two" style="display:none;">
							<li><a href="/buildings">Building</a></li>
							<li><a href="/populations">Population</a></li>
							<li><a href="/trades">Trade</a></li>
							<li><a href="/technologies">Technology</a></li>
							<li><a href="/statistics">Kingdom Stats</a></li>
						</ul>
					</li> 
					<li>
						<a href="#" class="drops" id="link_three">Military<span style="float:right; margin-top: -8px; margin-right: -5px;"></span></a>
						<ul class="subnav" id="box_three" style="display:none;">
							<li><a href="/weapons">Weapons</a></li>
							<li><a href="/armies">Units</a></li>
							<li><a href="/strategies">Strategy</a></li>
							<li><a href="/attacks">Attack</a></li>
							<li><a href="/spells">Spell</a></li>
							<li><a href="/retaliations">Retaliations</a></li>
						</ul>
					</li>
					<li>
						<a href="#" class="drops" id="link_three">Community<span style="float:right; margin-top: -8px; margin-right: -5px;"></span></a>
						<ul class="subnav" id="box_three" style="display:none;">
							<li><a href="/alliances">Alliance</a></li>
							<li><a href="/forums">Alliance Forum</a></li>
							<li><a href="/leaderboards">Rankings</a></li>
							<li><a href="/fames">Hall of Fames</a></li>
						</ul>
					</li>
					<li>
						<a href="#" class="drops" id="link_three">Help<span style="float:right; margin-top: -8px; margin-right: -5px;"></span></a>
						<ul class="subnav" id="box_three" style="display:none;">
							<li><a href="http://www.provokeone.com/ctagamewiki" target="_NEW">Game Guide</a></li>
							<li><a href="http://www.provokeone.com/ctagameforum/" target="_NEW">Public Forum</a></li>
							<li><a href="/settings">Settings</a></li>
							<li><a href="/premiums">Premium</a></li>
							<?php if($account['Account']['role'] == 'admin' || $account['Account']['role'] == 'council') { ?><li><a href="/admins">Admin</a></li><?php } ?>
						</ul>
					</li>
	
				</ul> 
			</nav>
			<div id="jclock"></div>
			<?php if(!$user['User']['premium']) { ?>
				<!-- begin ryad tag -->
				<div style="clear:both;"></div>
				<div id='_ryad_7641154393' style="margin-left:auto; margin-right: auto; width:728px;"></div>
				<script type='text/javascript'>
				  _ryadConfig = new Object();
				  _ryadConfig.placeguid='7641154393';
				  _ryadConfig.type='Leaderboard';
				  _ryadConfig.popup=1;
				  _ryadConfig.thirdPartyId='';
				</script>
				<script type='text/javascript' src='http://cdn.rockyou.com/apps/ams/tag_os.js'></script>
				<!-- end ryad tag -->
				<?php } ?>
			<div style="clear:both;"></div>
		</header> 
		<section id="main">
			<!-- Start Submenu -->
			<?php if(isset($menu)) {?>
				<div style="width:160px; margin-right: 15px; float: left;">
					<ul class="submenu">
						<?php foreach($menu as $item): ?>
							<li class="submenu_item <?php if(isset($selected)) { if($item['name'] == $selected) { echo "selected_item"; } } ?>"><a href="<?php echo $item['link'] ?>" <?php if($item['name'] == 'Disband Alliance') { ?> onclick="return confirm('Are you sure you wish to delete <?php echo $alliance['Alliance']['name'] ?>?');" <?php } ?>><?php echo $item['name'] ?></a>
						<?php endforeach; ?>
					</ul>
				<?php if(!$user['User']['premium']) { ?>
					<div class="wide-skyscraper-ad" style="width: 160px; height: 600px; margin-top: 15px;">
						<script type="text/javascript"><!--
						google_ad_client = "ca-pub-4469043209736151";
						/* CTA Submenu */
						google_ad_slot = "7557583777";
						google_ad_width = 160;
						google_ad_height = 600;
						//-->
						</script>
						<script type="text/javascript"
						src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
						</script>
					</div>
				<?php } ?>
				</div>

			<?php } ?>
			<!-- End submenu -->
			<!-- Start content -->
			<div style="position: relative; <?php if(isset($menu)) { echo "width:721px;"; } else { echo ""; } ?> float: left; margin-bottom: 135px; ">		
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->Session->flash('auth'); ?>
				<?php echo $content_for_layout; ?>
				<div style="clear:both;"></div>
			</div>	
			<!-- End content -->
			<div style="clear:both;"></div>
		</section>
		<?php if(!empty($account)): ?>
		<!-- Start login dialog --> 
		<div style="display: none;" id="login" title="Login">
		
			<div style="float:left; width:50%;">
			<?php echo $this->Form->create('Account', array('controller' => 'accounts', 'action' => 'login')); ?>
			 
			    <?php
			    	echo $this->Form->input('username');
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
				<div style="color: red;">If you have purchased PP on Facebook before, please use this option.</div>
				You can also use your Facebook:
				<?php echo $this->Facebook->login(array('perms' => 'email', 'size' => 'xlarge')); ?>
			<?php endif; ?>
			</div>
		</div>
		<!-- End login Dialog --> 
		<?php endif; ?>
		<div style="clear:both;"></div>
		<!-- Footer -->
		<footer> 
		
			<section id="copyright"> 
				<p>
					<b><a href="/policies/privacy">Privacy Policy</a> | <a href="/policies/terms">Terms of Service</a> | <a href="http://provokeone.mojohelpdesk.com/" target="_new">Support</a> | <a href="http://provokeone.com/ctagameforum" target="_new">Forum</a></b>
				</p>
			</section> 
		
			<section id="footer-logo"> 
				<p>Provoke One | CTA is developed soley by <a href="http://joshmatz.com" target="_blank">Josh Matz</a> with support from the CTA Community.</p>
			</section> 
		</footer> 
		<!-- End Footer -->
		<?php echo $this->Js->writeBuffer(); ?>
		<?php echo $this->Facebook->init(); ?>
	</body>
	
</html>