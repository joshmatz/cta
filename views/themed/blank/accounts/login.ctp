


<?php if(isset($facebook_user)): ?>
	<?php echo $this->Facebook->logout(array('redirect' => array('controller' => 'accounts', 'action' => 'logout'))); ?>
<?php else: ?>
	<div id="login-box" title="Login">
		<h2>Login</h2>
		<div style="float:left; width:50%;">
			<?php echo $this->Session->flash('auth'); ?>
			<?php echo $this->Form->create('Account', array('controller' => 'accounts', 'action' => 'login')); ?>
			 
			    <?php
			        echo $this->Form->input('email');
			        echo $this->Form->input('password');
			        echo $this->Form->input('keep me logged in', array('type' => 'checkbox'))
			    ?>
			 
			<?php echo $this->Form->end('Login'); ?>
			<a href="/accounts/register">Create An Account</a>
		</div>
		<div style="float:left;width:50%;">
			<div style="color: red;">
				If you have purchased PP on Facebook before, please use this option.
			</div>
			<a id="fb_login_button" href="#" 
      onclick="FB.login(function(){ /* this is a callback function */ },{scope: 'email,user_about_me,user_interests,user_location,publish_stream,read_stream,offline_access,publish_checkins'});return false;">
    Log in with Facebook
</a>
		</div>
	</div>
<?php endif; ?>
		