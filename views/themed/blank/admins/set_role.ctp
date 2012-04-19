<?php 
	echo $form->create('Account', array('url' => '/admins/setRole'));
	echo $form->input('facebook_id');
	echo $form->input('role');
	echo $form->submit();
	echo $form->end();

?>