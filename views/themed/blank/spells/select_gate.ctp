<div class="instructions"><center><h3>Instructions</h3></center>To cast gate, you must pick an away army to bring home, then specify the amount of wizards to be used.</div>
<h3>Gate selector</h3>
<?php echo $form->create('Gate', array('action' => 'gate', 'type' => 'post')) ?>
<?php ?>
<table>
	<tr>
		<?php for($i = 0; $i < 6; $i++){
			echo "<th>$units[$i]</th>";
			}
			echo "<th>Time Away</th>"?>
	</tr>
	<?php
		foreach($attacks as $attack):
		if ($attack['id'] != 51){
			echo "<tr><td>" . $attack['small_elite'] . "</td>";
			echo "<td>" . $attack['big_elite'] . "</td>";
			echo "<td>" . $attack['weak'] . "</td>";
			echo "<td>" . $attack['standard'] . "</td>";
			echo "<td>" . $attack['small_elite'] . "</td>";
			echo "<td>" . $attack['small_elite'] . "</td>";
			echo "<td>" . $attack['rounds'] . "</td>";?>
			<td><input name='attackId' id='<?php $attack['id'] ?>' value='None' type='radio'></td></tr>;
		
		<?php }endforeach;
	
	?>
	<tr>
		<td>Wizards to use:</td>
		<td><?php echo $form->input('wizards', array('label' => false))?></td>
		<td><?php echo $form->end('Cast', array('label' => false, 'div' => false)); ?></td>
		<td>You have <?php echo $wizards?> wizards.</td>
	</tr>
</table>