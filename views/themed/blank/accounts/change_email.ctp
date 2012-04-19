<?php echo $this->Form->create('Account', array('controller' => 'accounts', 'action' => 'changeEmail')); ?>
 
    <?php
        echo $this->Form->input('email', array('label' => 'New Email Address'));
        echo $this->Form->input('confirm_email', array('label' => 'Confirm Email Address'));
        echo $this->Form->input('password2', array('label' => 'Password', 'type' => 'password'));
    ?>
 
<?php echo $this->Form->end('Send Activation Email'); ?>
