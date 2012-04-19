
<h1><?php echo $alliance['Alliance']['name'] ?></h1>
<?php echo $form->create('Alliance', array('action' => 'settings')); ?>

<table>
		<?php echo $form->input('name', array('before' => '<tr><td>', 'between' => '</td><td>', 'after' => '</td></tr>')); ?>
		<?php echo $form->input('short_name', array('before' => '<tr><td>', 'between' => '</td><td>', 'after' => '</td></tr>')); ?>
		<?php echo $form->input('homepage', array('before' => '<tr><td>', 'between' => '</td><td>', 'after' => '</td></tr>')); ?>
		<?php echo $form->input('about', array('before' => '<tr><td>', 'between' => '</td><td>', 'after' => '</td></tr>')); ?>
</table>
<?php 	echo $form->end('Update'); ?>
