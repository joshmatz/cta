
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
	    		<td><a href="/users/profile/<?php echo $player['User']['id']?>"><?php if($player['User']['premium']) echo "*";?><?php echo $player['User']['ruler']; ?></a> | <a href="/alliances/profile/<?php echo $player['Alliance']['id'] ?>"><?php echo $player['Alliance']['short_name'] ?></a></td>
	    		<td><?php echo $player['User']['race'] ?></td>
	    		<td><?php echo number_format($player['Resource']['land']) ?></td>
	    		<td><?php echo number_format($player['Building']['castle']) ?></td>
	    		<td><?php echo number_format($player['User']['score']) ?></td>
			</tr>
			<?php } else { ?>
			<tr>
				<td><?php echo $player['User']['rank'] ?></td>
	    		<td><a href="/users/profile/<?php echo $player['User']['id']?>"><?php if($player['User']['premium']) echo "*";?><?php echo $player['User']['ruler']; ?></a> | <a href="/alliances/profile/<?php echo $player['Alliance']['id'] ?>"><?php echo $player['Alliance']['short_name'] ?></a></td>
	    		<td><?php echo $player['User']['race'] ?></td>
	    		<td><?php echo number_format($player['Resource']['land']) ?></td>
	    		<td><?php echo number_format($player['Building']['castle']) ?></td>
	    		<td><?php echo number_format($player['User']['score']) ?></td>
			</tr>
			<?php } ?>
		<?php }else { $found = true ?>
			<tr style="background-color: #CCCCCC;">
				<td style="color:#222222;"><?php echo $player['User']['rank'] ?></td>
	    		<td style="color:#222222;"><a href="/users/profile/<?php echo $player['User']['id']?>" style="color:black;"><?php if($player['User']['premium']) echo "*";?><?php echo $player['User']['ruler']; ?></a> | <a href="/alliances/profile/<?php echo $player['Alliance']['id'] ?>" style="color:black;"><?php echo $player['Alliance']['short_name'] ?></a></td>
	    		<td style="color:#222222;"><?php echo $player['User']['race'] ?></td>
	    		<td style="color:#222222;"><?php echo number_format($player['Resource']['land']) ?></td>
	    		<td style="color:#222222;"><?php echo number_format($player['Building']['castle']) ?></td>
	    		<td style="color:#222222;"><?php echo number_format($player['User']['score']) ?></td>
	    	</tr>
		<?php } ?>
	<?php endforeach; ?>
	<?php if(!$found) {  ?>
			<tr style="background-color: #CCCCCC;">
				<td style="color:#222222;"><?php echo $user['User']['rank'] ?></td>
	    		<td style="color:#222222;"><a href="/users/profile/<?php echo $user['User']['id']?>" style="color:black;"><?php if($user['User']['premium']) echo "*";?><?php echo $user['User']['ruler']; ?></a> | <a href="/alliances/profile/<?php echo $user['Alliance']['id'] ?>" style="color:black;"><?php echo $user['Alliance']['short_name'] ?></a></td>
	    		<td style="color:#222222;"><?php echo $user['User']['race'] ?></td>
	    		<td style="color:#222222;"><?php echo number_format($user['Resource']['land']) ?></td>
	    		<td style="color:#222222;"><?php echo number_format($user['Building']['castle']) ?></td>
	    		<td style="color:#222222;"><?php echo number_format($user['User']['score']) ?></td>
	    	</tr>
	<?php } ?>
	<tr>
		<td colspan="6">
			<center>
				<?php echo $paginator->prev('« Previous', array('class' => 'PrevPg'), null, array('class' => 'PrevPg DisabledPgLk')) ?>
				<?php echo $paginator->numbers(); ?>
				<?php echo $paginator->next(' Next »', array('class' => 'NextPg'), null, array('class' => 'NextPg DisabledPgLk')); ?> 
			</center>
		</td>
	</tr>
</table>

<style type="text/css">
    .auto_complete { width:200px; position:absolute; z-index:100; background-color:#ffffff; color: #000000; text-align:left; border:1px solid #bdc7d8; display: block;}
    .auto_complete ul { list-style-type:none; margin:0px; padding:0px; background-color:transparent; }
    .selected { background-color:#3b5998; color:#ffffff; }
    .auto_complete li a { background-color:#ffffff; color:#3b5998; text-decoration:none; padding:2px 6px; display:block; }
    .auto_complete li:hover{ background-color:#3b5998; color:#ffffff; }
</style>

<table width="100%">
	<tr>
		<td>
			<form id="LeaderBoardRanks" method="post" action="/leaderboards/indexRedirect">
				Rank: 	<input name="data[Leaderboard][rank]" type="text" size="2" value="" id="LeaderboardRank" />
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
		<td>
			<form id="LeaderBoardRanks" method="post" action="/leaderboards/indexSearchRedirect">
				Search: 	<?php echo $ajax->autoComplete('Leaderboard.name', '/leaderboards/autoComplete')?>
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
	</tr>
</table>







