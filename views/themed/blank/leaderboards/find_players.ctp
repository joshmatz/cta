<div style="width:150px; float: left;">
	<ul>
		<?php foreach($menu as $item): ?>
			<li><a href="<?php echo $item['link'] ?>" <?php if($item['name'] == 'Disband Alliance') { ?> onclick="return confirm('Are you sure you wish to delete <?php echo $alliance['Alliance']['name'] ?>?');" <?php } ?>><?php echo $item['name'] ?></a>
		<?php endforeach; ?>
	</ul>
</div>

<center>
	<?php echo $html->link('Individual Rankings', "/leaderboards/index" . $parms)?> | 
<?php echo $html->link('Honor Rankings', "/leaderboards/honor" . $parms)?> | 
<?php echo $html->link('Province Rankings', "/leaderboards/provinces" . $parms)?> |
<?php echo $html->link('Friend Rankings', "/leaderboards/findFriends" . $parms)?> | 
<?php echo $html->link('Offensive Kill Scores', "/leaderboards/killStats" . $parms)?> | 
<?php echo $html->link('Defensive Kill Scores', "/leaderboards/killStats/Defense" . $parms)?>
<p>
	<?php echo $form->create('Leaderboard', array('action' => 'findPlayersRedirect', 'type' => 'post')); ?>
Total Players: <?php echo $totalPlayers ?><br />
Rank: 	<?php echo $form->input('rank', array('type' => 'text', 'size' => 2, 'div' => false, 'label' => false)); ?>

	<?php echo $form->end(array('Go', 'div' => false, 'label' => false)); ?>
</p>
</center>
<table align="center" width="100%">
	<tr>
		<th>Rank</th>
		<th>Player</th>
		<th>Race</th>
		<th>Land</th>
		<th>Castles</th>
		<th>Kingdom Strength</th>
	</tr>
	<?php $found = false ?>
	<?php foreach($players as $player): ?>
		<?php if($player['User']['ruler'] != $user['User']['ruler']) {?>
			<?php if(!$player['User']['suspended']) {?><tr>
				<td><?php echo $player['User']['rank'] ?></td>
	    		<td><a href="/users/profile/<?php echo $player['User']['id'] . $parms?>"><?php if($player['User']['premium']) echo "*";?><?php echo $player['User']['ruler']; ?></a></td>
	    		<td><?php echo $player['User']['race'] ?></td>
	    		<td><?php echo number_format($player['Resource']['land']) ?></td>
	    		<td><?php echo number_format($player['Building']['castle']) ?></td>
	    		<td><?php echo number_format($player['User']['score']) ?></td>
			</tr>
			<?php } else { ?>
			<tr>
				<td style="background-color: #CCCCCC;"><?php echo $player['User']['rank'] ?></td>
	    		<td><a href="/users/profile/<?php echo $player['User']['id'] . $parms?>" style="color:maroon;"><?php if($player['User']['premium']) echo "*";?><?php echo $player['User']['ruler']; ?></a></td>
	    		<td><?php echo $player['User']['race'] ?></td>
	    		<td><?php echo number_format($player['Resource']['land']) ?></td>
	    		<td><?php echo number_format($player['Building']['castle']) ?></td>
	    		<td><?php echo number_format($player['User']['score']) ?></td>
			</tr>
			<?php } ?>
		<?php }else { $found = true ?>
			<tr style="background-color: #CCCCCC;">
				<td style="color:#222222;"><?php echo $player['User']['rank'] ?></td>
	    		<td style="color:#222222;"><a href="/users/profile/<?php echo $player['User']['id'] . $parms?>" style="color:black;"><?php if($player['User']['premium']) echo "*";?><?php echo $player['User']['ruler']; ?></a></td>
	    		<td style="color:#222222;"><?php echo $player['User']['race'] ?></td>
	    		<td style="color:#222222;"><?php echo number_format($player['Resource']['land']) ?></td>
	    		<td style="color:#222222;"><?php echo number_format($player['Building']['castle']) ?></td>
	    		<td style="color:#222222;"><?php echo number_format($player['User']['score']) ?></td>
	    	</tr>
		<?php } ?>
	<?php endforeach; ?>
</table>
* Premium User
<br /><br />