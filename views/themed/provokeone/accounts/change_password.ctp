<?php echo $this->Form->create('Account', array('controller' => 'accounts', 'action' => 'changePassword')); ?>
 
    <?php
        echo $this->Form->input('password', array('label' => 'Current Password', 'type' => 'password'));
        echo $this->Form->input('password2', array('label' => 'New Password', 'type' => 'password'));
        echo $this->Form->input('password3', array('label' => 'Confirm New Password', 'type' => 'password'));
    ?>
 
<?php echo $this->Form->end('Change Password'); ?>
