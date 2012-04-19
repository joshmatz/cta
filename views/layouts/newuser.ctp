<?php echo $this->Facebook->html(); ?>
	<head>
		<link type="text/css" href="/cometchat/cometchatcss.php" rel="stylesheet" charset="utf-8">
		<script type="text/javascript" src="/cometchat/cometchatjs.php" charset="utf-8"></script>
		<meta charset="utf-8"/>
		<title>
			<?php __('CakePHP: the rapid development php framework:'); ?>
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

	<div class="metalbar">&nbsp;</div>
		<header> 
			<!-- logo -->
			<div  style="float:left;">
			<h1 style="margin:0px;"><?php echo $this->Html->link(__('Provoke One', true), array('controller' => 'users', 'action' => 'index'), array('style' => 'display:block;text-indent:-9999px;width:320px;height:120px;')); ?></h1> 
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
			
			<div style="clear:both;"></div>
		</header> 
		<section id="main">
			
			<!-- Start content -->
			<div style="position: relative; <?php if(isset($menu)) { echo "left: 145px; width:746px;"; } else { echo "left: auto;"; } ?> top: auto; margin-bottom: 135px;">		
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
				<p>Provoke One</p>
			</section> 
		</footer> 
		<!-- End Footer -->
		<?php echo $this->Js->writeBuffer(); ?>
		<?php echo $this->element('sql_dump'); ?>
		<?php echo $this->Facebook->init(); ?>
	</body>
	
</html>