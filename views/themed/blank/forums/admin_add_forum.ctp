<?php 
	echo $form->create('Forum', array('action' => 'addForum'));
	echo $form->input('name');
	echo $form->input('description');
	echo $form->input('access');
	echo $form->end('Submit'); 
?>