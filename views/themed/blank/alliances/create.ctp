<div class="instructions"><center><h3>Instructions</h3></center>To get you started with an alliance you'll need a name and tag. Once created you
may edit these and information about the alliance.</div>

<?php 
	echo $form->create('Alliance', array('action' => 'create'));
	echo $form->input('name');
	echo $form->input('short_name');
	echo $form->end('Submit'); 
?>