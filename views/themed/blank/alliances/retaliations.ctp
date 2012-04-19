This feature is currently in development but should be working. Please post in the forums if you are having problems.
<table>
	<tr>
		<th colspan="2" align="center">Retaliations</th>
	</tr>
	<tr>
		<th>
			Alliance
		</th>
		<th>
			Player
		</th>
		<th>
			Expires
		</th>
	</tr>
	<?php foreach($allianceRetaliations as $retal): ?>
		<tr>
			<td>
				<a href="/alliances/profile/<?php echo $retal['AllianceRetaliation']['attacker_id'] ?>"><?php echo $retal['AllianceRetaliation']['name'] ?></a>
			</td>
			<?php 
				$retalTime = strtotime('+ 5 hour', strtotime($retal['AllianceRetaliation']['datetime']));
			?>
			<td>
				<a href="/users/profile/<?php echo $retal['AllianceRetaliation']['player_id'] ?>"><?php echo $retal['AllianceRetaliation']['player_name'] ?></a>
			</td>
			<td>
				<time class="timeago" datetime="<?php echo date("Y-m-d H:i:s", $retalTime) ?>"></time>
			</td>
		</tr>
	<?php endforeach; ?>
</table>