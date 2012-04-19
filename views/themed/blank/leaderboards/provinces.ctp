
	<?php echo $form->create('Leaderboard', array('action' => 'findProvincesRedirect', 'type' => 'post')); ?>

	Rank: <?php echo $form->input('rank', array('type' => 'text', 'size' => 2, 'div' => false, 'label' => false)); ?>

	<?php echo $form->end(array('Go', 'div' => false, 'label' => false)); ?>
<table border="1" bordercolor="gray" style="border-collapse:collapse;" cellpadding="5" align="center">
	<tr>
		<th>Rank</th>
		<th>Province</th>
		<th>Players</th>
		<th>Land</th>
		<th>Castles</th>
		<th>Kingdom Strength</th>
	</tr>
	<?php foreach($provinces as $province): 
		  	  $playerProvince = $user['User']['province'] ." ". $user['User']['province_number'];
		  	  $link = explode(" ", $province['Province']['name']);
		  	  if($playerProvince != $province['Province']['name']) { 
	?>
	<tr>
		<td><?php echo number_format($rank++) ?></td>
		<td><a href="/provinces/find/<?php echo $link[0] ."/". $link[1] ; ?>"><?php echo $province['Province']['name'] ?></a></td>
		<td><?php echo $province['Province']['players'] ?></td>
		<td><?php echo number_format($province['Province']['land']) ?></td>
		<td><?php echo number_format($province['Province']['castle']) ?></td>
		<td><?php echo number_format($province['Province']['score']) ?></td>
	</tr>
	<?php } else { ?>
	<tr style="background-color: #CCCCCC;">
		<td style="color:#222222;"><?php echo number_format($rank++) ?></td>
		<td style="color:#222222;"><a href="/provinces/find/<?php echo $link[0] ."/". $link[1] ; ?>"><?php echo $province['Province']['name'] ?></a></td>
		<td style="color:#222222;"><?php echo $province['Province']['players'] ?></td>
		<td style="color:#222222;"><?php echo number_format($province['Province']['land']) ?></td>
		<td style="color:#222222;"><?php echo number_format($province['Province']['castle']) ?></td>
		<td style="color:#222222;"><?php echo number_format($province['Province']['score']) ?></td>
	</tr>
	<?php } ?>
	<?php endforeach; ?>
</table>
