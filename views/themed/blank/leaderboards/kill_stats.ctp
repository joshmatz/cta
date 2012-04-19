
<table align="center" width="100%">
	<tr>
		<th>Rank</th>
		<th>Player</th>
		<th><?php echo $hiddenType?> Kill Score</th>
	</tr>
	<?php $found = false ?>
	<?php foreach($killstats as $player): ?>
		<?php if($player['KillStat']['user_id'] != $user['User']['id']) {?>
			<tr>
				<td><?php echo number_format($player['KillStat']['rank']) ?></td>
	    		<td><a href="/users/profile/<?php echo $player['KillStat']['user_id'] . $parms?>"><?php echo $player['KillStat']['name'] ?></a></td>
	    		<td><?php echo number_format($player['KillStat']['points']) ?></td>
			</tr>
		<?php }else { $found = true ?>
			<tr style="background-color: #CCCCCC;">
				<td style="color: #222222;"><?php echo number_format($player['KillStat']['rank']) ?></td>
	    		<td style="color: #222222;" style="color:black;"><?php echo $player['KillStat']['name'] ?></td>
	    		<td style="color: #222222;"><?php echo number_format($player['KillStat']['points']) ?></td>
	    	</tr>
		<?php } $lastRank = $player['KillStat']['rank']; ?>
	<?php endforeach; ?>
	<?php if(!$found && $userKillStats['KillStat']['rank'] > $lastRank) {?>
			<tr style="background-color: #CCCCCC;">
				<td style="color: #222222;"><?php echo number_format($userKillStats['KillStat']['rank']) ?></td>
	    		<td style="color: #222222;"><a href="/users/profile/<?php echo $userKillStats['KillStat']['user_id'] . $parms?>" style="color:black;"><?php echo $userKillStats['KillStat']['name'] ?></a></td>
	    		<td style="color: #222222;"><?php echo number_format($userKillStats['KillStat']['points']) ?></td>
	    	</tr>
	<?php } ?>
	<?php if($paginator->numbers()) { ?>
	<tr>
		<td colspan="3">
			<center>
				<?php echo $paginator->prev('« Previous', array('class' => 'PrevPg'), null, array('class' => 'PrevPg DisabledPgLk')) ?>
				<?php echo $paginator->numbers(); ?>
				<?php echo $paginator->next(' Next »', array('class' => 'NextPg'), null, array('class' => 'NextPg DisabledPgLk')); ?> 
			</center>
		</td>
	</tr>
	<?php } ?>
</table>

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
			<form id="LeaderBoardRanks" method="post" action="/leaderboards/killStatsRedirect">
				<input name="data[Leaderboard][type]" type="hidden" value="<?php echo $hiddenType ?>"></input>
				Rank: 	<input name="data[Leaderboard][rank]" type="text" size="2" value="" id="LeaderboardRank" />
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
		<td>
			<form id="LeaderBoardRanks" method="post" action="/leaderboards/killStatsSearchRedirect">
				<input name="data[Leaderboard][type]" type="hidden" value="<?php echo $hiddenType ?>"></input>
				Search: 	<?php echo $ajax->autoComplete('Leaderboard.name', '/leaderboards/autoComplete')?>
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
	</tr>
</table>
