<?php echo $this->Form->create('Account', array('controller' => 'accounts', 'action' => 'register')); ?>
 
    <?php
        echo $this->Form->input('email');
        echo $this->Form->input('password');
        echo $this->Form->input('password2', array('label' => 'Confirm Password', 'type' => 'password'));
    ?>
 
<?php echo $this->Form->end('Register'); ?>
