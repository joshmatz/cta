<?php 
	echo $form->create('Premium', array('url' => '/admins/addTransaction'));
	echo $form->input('account_id');
	echo $form->input('type');
	echo $form->input('change');
	echo $form->input('information');
	echo $form->submit();
	echo $form->end();

?>