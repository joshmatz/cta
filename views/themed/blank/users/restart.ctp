<p align="center">Please enter the new information you would like to restart with. You cannot use the same kingdom and ruler name.<br />
	<?php 
		echo $form->create('User', array('action' => 'restart'));
		echo $form->input('ruler');
		echo $form->input('kingdom');
		echo $form->input('race', array('options' => array(
		    'Orc'=>'Orc',
		    'Elf'=>'Elf',
		    'Human'=>'Human',
		    'Dwarf'=>'Dwarf',
		    'Gnome'=>'Gnome' )));
		echo $form->input('province_number', array('type' => 'text', 'size' => 2));
		echo $form->input('Random Province', array('type' => 'checkbox'));
		echo $form->end('Create Kingdom');
		
	?>
</p>