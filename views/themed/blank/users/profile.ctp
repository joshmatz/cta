<?php if($account['Account']['role'] == 'admin' || $account['Account']['role'] == 'council') { ?><div style="text-align:left; float:left; margin-left: 15px;">
<h6>Admin Control Panel</h6>

<?php if($account['Account']['role'] == 'admin') { ?><a href="/admins/getRockYou/<?php echo $id ?>">Get RockYou ID</a><?php } ?>
</div>
<?php } ?>

<div style="text-align:left; float:left; clear:left; margin-left: 15px;">
<h1><?php echo $name?></h1>
<?php // image will go here. ?>
<table>
	<tr>
		<td><strong>Race:</strong></td>
		<td><?php echo $profile['User']['race']; ?></td>
	</tr>
	<tr>
		<td><strong>Kingdom Strength:</strong></td>
		<td><?php echo number_format($profile['User']['score']) ?></td>
	</tr>
	<tr>
		<td><strong>Land:</strong></td>
		<td><?php echo number_format($profile['Resource']['land']) ?></td>
	</tr>
	<tr>
		<td><strong>Castles:</strong></td>
		<td><?php echo number_format($profile['Building']['castle']) ?></td>
	</tr>
	<tr>
		<td><strong>Rank:</strong></td>
		<td><?php echo number_format($profile['User']['rank']) ?></td>
	</tr>
	<tr>
		<td><strong>Total Kill Score:</strong></td>
		<td><?php echo number_format($userKillStats['KillStat']['points']) . " (" . number_format($userKillStats['KillStat']['rank']) . ")"; ?></td>
	</tr>
	<tr>
		<td><strong>Alliance:</strong></td>
		<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>"><?php echo $alliance['Alliance']['name']; ?></a></td>
	</tr>
</table>
<ul style="width:250px; ">
	<?php if($id != $user['User']['id']) { ?>
	<li><a href="/trades/sendTo/<?php echo $id ?>">Trade</li></a>
	<li><a href="/attacks/selectUnits/<?php echo $id ?>">Attack</a></li>
	<?php } // end trade/attack if ?>
	<li><a href="/messages/reply/<?php echo $profileRuler['User']['ruler'] ?>">Send a message</a>
	<li><a href="/spells/selectSpell/<?php echo $id ?>">Cast a spell</a></li>
	<?php if($id != $user['User']['id'] && $user['User']['alliance_rank'] == 'admin') { ?><li><a href="/alliances/invite/<?php echo $id ?>">Invite to alliance</a></li><?php } ?>
</ul>
</div>
<div style="text-align:left; float:left; clear:left; border-left: 1px solid #aaa; padding-left: 30px; margin-left: 30px;">
<h2>About Me</h2>
<?php echo $about; ?>
<?php if($id == $user['User']['id']) { ?> <br /><br />
<form id="ProfileEdit" method="post" action="/users/profile/<?php echo $id?>">
<label for="ProfileProfile">Profile: </label>
<textarea name="data[About][about]" value="" cols="40" rows="10" id="ProfileProfile"></textarea><br />
<div style="text-align:right;"><input type="submit" value="Update" /></div>

</form>
<?php } // end profile edit?>
</div>
<div style="clear:both;"></div>