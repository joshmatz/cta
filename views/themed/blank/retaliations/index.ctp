<center>
<?php 
if(!empty($retaliations) || !empty($retaliationsAgainst)){ 

	if(!empty($retaliations)) { ?> 
		<table cellpadding="5px">
			<tr><th colspan="2">Current Retaliations</th></tr>
			<tr>
				<th>Name</th>
				<th>Rounds left</th>
			</tr>
			<?php 
			foreach($retaliations as $retaliation): ?>
				<tr>
					<td><center><?php echo $retaliation['Retaliation']['name'] ?></center></td>
					<td><center><?php echo $retaliation['Retaliation']['rounds'] ?></center></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php } 
		if(!empty($retaliationsAgainst) && $user['User']['premium']) { ?>
			<table cellpadding="5px">
				<tr><th colspan="2">Retaliations Against You</th></tr>
				<tr>
					<th>Name</th>
					<th>Rounds left</th>
				</tr>
				<?php 
				foreach($retaliationsAgainst as $retaliation): ?>
					<tr>
						<td><center><?php echo $retaliation['Retaliation']['name'] ?></center></td>
						<td><center><?php echo $retaliation['Retaliation']['rounds'] ?></center></td>
					</tr>
				<?php endforeach; ?>
			</table>
	<?php } elseif(!$user['User']['premium'] && !empty($retaliationsAgainst)) { ?>
	<br />You currently have retaliations against you. <a href="/premiums/">Activate a premium account</a> to view them.
<?php }} else { ?>
You currently have no retaliations for or against you.
<?php } ?>
</center>