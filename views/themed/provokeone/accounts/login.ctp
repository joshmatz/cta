


<?php if(isset($facebook_user)): ?>
	 <?php echo $this->Facebook->logout(array('redirect' => array('controller' => 'accounts', 'action' => 'logout'))); ?>
	<?php //debug($this->Facebook_user); ?>
<?php else: ?>
<h2>Login</h2>
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('Account', array('controller' => 'accounts', 'action' => 'login')); ?>
 
    <?php
        echo $this->Form->input('email');
        echo $this->Form->input('password');
        echo $this->Form->input('keep me logged in', array('type' => 'checkbox'))
    ?>
 
<?php echo $this->Form->end('Login'); ?>
	<?php echo $this->Facebook->login(array('perms' => 'email')); ?>
<?php endif; ?>
<?php //debug($user); ?>