
<h1><?php echo $alliance['Alliance']['name'] ?></h1>
<table width="100%">
	<tr>
		<td>Tag:</td>
		<td><?php echo $alliance['Alliance']['short_name']?></td>
	</tr>
	<tr>
		<td>About:</td>
		<td><?php echo $alliance['Alliance']['about']?></td>
	</tr>
	<tr>
		<td>Rank:</td>
		<td><?php echo $alliance['Alliance']['score_rank']?></td>
	</tr>
	<tr>
		<td>Number of members:</td>
		<td><?php echo number_format($alliance['Alliance']['members']) ?></td>
	</tr>
	<tr>
		<td>Total Alliance Strength:</td>
		<td><?php echo number_format($alliance['Alliance']['score']) ?></td>
	</tr>
	<tr>
		<td>Average Kingdom Strength:</td>
		<td><?php echo number_format(round($alliance['Alliance']['score'] / $alliance['Alliance']['members'])) ?></td>
	</tr>
	<tr>
		<td>Kill Score:</td>
		<td><?php echo $alliance['Alliance']['ks']?> (<?php echo $alliance['Alliance']['ks_rank']?>)</td>
	</tr>
	<tr>
		<td>Members:</td>
		<td>
			<table>
			<tr>
				<th>Member</th>
				<th>Race</th>
				<th>Score</th>
				<th>Land</th>
				<th>Castles</th>
			</tr>
			<?php foreach($members as $member): ?>
				<tr>
					<td>
						<a href="/users/profile/<?php echo $member['User']['id'] ?>"><?php echo $member['User']['ruler']; ?></a>
					</td>
					<td><?php echo $member['User']['race'] ?></td>
					<td><?php echo $member['User']['score'] ?></td>
					<td><?php echo $member['Resource']['land']?></td>
					<td><?php echo $member['Building']['castle'] ?></td>
					<?php if($user['User']['id'] == $alliance['Alliance']['user_id'] && $member['User']['id'] != $user['User']['id']) { ?>
						<td>
						<?php echo $html->link(
						    $html->image('x.png', array('alt' => 'boot')),
						    array('controller'=>'alliances', 'action'=>'boot', $member['User']['id']),
						    array('escape'=>false),
						    "Are you sure you wish to boot " . $member['User']['ruler'] . "?"
						);?>
						</td>
					<?php } ?>
				</tr>
			<?php endforeach; ?>
			</table>
		</td>
	</tr>
	<tr>
		<td>Homepage:</td>
		<td><?php echo $alliance['Alliance']['homepage']?></td>
	</tr>
	

</table>
