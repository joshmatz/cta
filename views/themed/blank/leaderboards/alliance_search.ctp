

<table align="center" width="100%">
	<tr>
		<th>Rank</th>
		<th>Alliance</th>
		
		<?php if($hiddenType == "ks") { ?>
		<th>Total Kill Score</th>
		<?php } else if($hiddenType == "oks") { ?>
		<th>Offensive Kill Score</th>
		<?php } else if($hiddenType == "dks") { ?>
		<th>Defensive Kill Score</th>
		<?php } else if($hiddenType == "honor") { ?>
		<th>Honor</th>
		<?php } else { ?>
		<th>Members</th>
		<th>Score</th>
		<th>Average Score per player</th>
		<th>Land</th>
		<th>Average Land per player</th>
		<?php } ?>
	</tr>
	<?php $found = false; $lastRank = 1; ?>
	<?php foreach($alliances as $alliance): ?>
		<?php if($alliance['Alliance']['id'] != $user['User']['alliance_id']) {?>
			<tr>
				<?php if($hiddenType == "ks") { ?>
				<td><?php echo number_format($alliance['Alliance']['ks_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['ks']) ?></td>
				<?php } else if($hiddenType == "oks") { ?>
				<td><?php echo number_format($alliance['Alliance']['oks_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['oks']) ?></td>
				<?php } else if($hiddenType == "dks") { ?>
				<td><?php echo number_format($alliance['Alliance']['dks_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['dks']) ?></td>
				<?php } else if($hiddenType == "honor") { ?>
				<td><?php echo number_format($alliance['Alliance']['honor_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['honor']) ?></td>
				<?php } else { ?>
				<td><?php echo number_format($alliance['Alliance']['score_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['members']) ?></td>
				<td><?php echo number_format($alliance['Alliance']['score']) ?></td>
				<td><?php echo number_format(round($alliance['Alliance']['score'] / $alliance['Alliance']['members'])) ?></td>
				<td><?php echo number_format($alliance['Alliance']['land']) ?></td>
				<td><?php echo number_format(round($alliance['Alliance']['land'] / $alliance['Alliance']['members'])) ?></td>
				<?php } ?>
			</tr>

		<?php }else { $found = true ?>
			<tr style="background-color: #CCCCCC;">
				<?php if($hiddenType == "ks") { ?>
				<td><?php echo number_format($alliance['Alliance']['ks_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>" style="color:black;"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['ks']) ?></td>
				<?php } else if($hiddenType == "oks") { ?>
				<td><?php echo number_format($alliance['Alliance']['oks_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>" style="color:black;"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['oks']) ?></td>
				<?php } else if($hiddenType == "dks") { ?>
				<td><?php echo number_format($alliance['Alliance']['dks_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>" style="color:black;"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['dks']) ?></td>
				<?php } else if($hiddenType == "honor") { ?>
				<td><?php echo number_format($alliance['Alliance']['honor_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>" style="color:black;"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td><?php echo number_format($alliance['Alliance']['honor']) ?></td>
				<?php } else { ?>
				<td><?php echo number_format($alliance['Alliance']['score_rank']) ?></td>
				<td><a href="/alliances/profile/<?php echo $alliance['Alliance']['id'] ?>" style="color:black;"><?php echo $alliance['Alliance']['short_name'] ?></a></td>
				<td style="color: #222222;"><?php echo number_format($alliance['Alliance']['members']) ?></td>
				<td style="color: #222222;"><?php echo number_format($alliance['Alliance']['score']) ?></td>
				<td style="color: #222222;"><?php echo number_format(round($alliance['Alliance']['score'] / $alliance['Alliance']['members'])) ?></td>
				<td style="color: #222222;"><?php echo number_format($alliance['Alliance']['land']) ?></td>
				<td style="color: #222222;"><?php echo number_format(round($alliance['Alliance']['land'] / $alliance['Alliance']['members'])) ?></td>
				<?php } ?>
			</tr>

		<?php } $lastRank = $alliance['Alliance']['score_rank']; ?>
	<?php endforeach; ?>
	<?php if($paginator->numbers()) { ?>
	<tr>
		<td colspan="6">
			<center><?php echo $paginator->numbers(); ?></center>
		</td>
	</tr>
	<?php } ?>
</table>

<table width="100%">
	<tr>
		<td align="center" width="50%"><?php echo $paginator->prev('« Previous ', null, null, array('class' => 'disabled')); ?></td>
		<td align="center" width="50%"><?php echo $paginator->next(' Next »', null, null, array('class' => 'disabled')); ?> </td>
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
			<form id="LeaderBoardRanks" method="post" action="/leaderboards/allianceRedirect">
				<input name="data[Leaderboard][type]" type="hidden" value="<?php echo $hiddenType ?>"></input>
				Rank: 	<input name="data[Leaderboard][rank]" type="text" size="2" value="" id="LeaderboardRank" />
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
		<td>
			<form id="LeaderBoardRanks" method="post" action="/leaderboards/allianceSearchRedirect">
				<input name="data[Leaderboard][type]" type="hidden" value="<?php echo $hiddenType ?>"></input>
				Search: 	<?php echo $ajax->autoComplete('Leaderboard.short_name', '/leaderboards/allianceAutoComplete')?>
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
	</tr>
</table>	
