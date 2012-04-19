
<table align="center" border="1">
	<tr>
		<th>Overall Rank</th>
		<th>Friend Rank</th>
		<th>Player</th>
		<th>Race</th>
		<th>Land</th>
		<th>Castles</th>
		<th>Kingdom Strength</th>
	</tr>
	<?php foreach($friends as $friend): 
		if($user['User']['facebook_id'] != $friend['User']['facebook_id']) {
		?>
		<tr>
			<td><?php echo $friend['User']['rank']?></td>
			<td><?php echo $rank++ ?></td>
    		<td><fb:name uid="<?php echo $friend['User']['facebook_id'] ?>" capitalize="true" /></fb:name><br /><a href="/users/profile/<?php echo $friend['User']['id'] . $parms?>"><?php echo $friend['User']['ruler'] . " of " . $friend['User']['kingdom']; ?></a></td>
    		<td><?php echo $friend['User']['race'] ?></td>
    		<td><?php echo number_format($friend['Resource']['land']) ?></td>
    		<td><?php echo number_format($friend['Building']['castle']) ?></td>
    		<td><?php echo number_format($friend['User']['score']) ?></td>
		</tr>
		<?php } else { ?>
		<tr style="background: #CCCCCC;">
			<td style="color:#222222;"><?php echo $friend['User']['rank']?></td>
			<td style="color:#222222;"><?php echo $rank++ ?></td>
    		<td style="color:#222222;"><fb:name uid="<?php echo $friend['User']['facebook_id'] ?>" capitalize="true"  /></fb:name><br /><a href="/users/profile/<?php echo $friend['User']['id'] . $parms?>" style="color:black;"><?php echo $friend['User']['ruler'] . " of " . $friend['User']['kingdom']; ?></a></td>
    		<td style="color:#222222;"><?php echo $friend['User']['race'] ?></td>
    		<td style="color:#222222;"><?php echo number_format($friend['Resource']['land']) ?></td>
    		<td style="color:#222222;"><?php echo number_format($friend['Building']['castle']) ?></td>
    		<td style="color:#222222;"><?php echo number_format($friend['User']['score']) ?></td>
		</tr>
		<?php } ?>
	<?php endforeach; ?>
</table><br />
<a href="/users/invite<?php echo $parms ?>">Invite more friends to join you!</a>
