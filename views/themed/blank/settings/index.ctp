<h2><?php echo $selected ?></h2>
<?php echo $form->create('Setting', array('url' => 'index')) ?>
<table>
	<tr>
		<th colspan="2">Settings</th>
	</tr>
	<tr>
		<td>Hide Self in other's friends list: </td>
		<td><?php echo $form->input('hide_self', array('label' => '', 'type' => 'checkbox')); ?></td>
	</tr>
	<tr>
		<td>Enable Debug Mode in Simulator:</td>
		<td><?php if($user['User']['premium']) { echo $form->input('simulator_debug_on', array('label' => '', 'type' => 'checkbox')); } else { echo "Premium Only"; }?></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo $form->submit('OK') ?></td>
	</tr>
</table>
<?php echo $form->end(); ?>