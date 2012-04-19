
<table align="center" width="100%">
	<tr>
		<th><?php echo $paginator->sort('Rank', 'honor_rank') ?> </th>
		<th>Player</th>
		<th>Honor</th>
	</tr>
	<?php if($user['User']['honor_rank'] < $honor[0]['User']['honor_rank']) {?>
			<tr style="background-color: #CCCCCC;">
				<td style="color: #AAA;"><?php echo number_format($user['User']['honor_rank']) ?></td>
	    		<td style="color: #AAA;"><a href="/users/profile/<?php echo $user['User']['id'] . $parms?>" style="color:black;"><?php echo $user['User']['ruler']; ?></a></td>
	    		<td style="color: #AAA;"><?php echo number_format($user['User']['honor']) ?></td>
	    	</tr>
	<?php } ?>
	<?php foreach($honor as $player): ?>
		<?php if($player['User']['id'] != $user['User']['id']) {?>
			<tr>
				<td><?php echo number_format($player['User']['honor_rank']) ?></td>
	    		<td><a href="/users/profile/<?php echo $player['User']['id'] . $parms?>"><?php echo $player['User']['ruler']; ?></a></td>
	    		<td><?php echo number_format($player['User']['honor']) ?></td>
			</tr>
		<?php }else { $found = true ?>
			<tr>
				<td style="color: #AAA;"><?php echo number_format($player['User']['honor_rank']) ?></td>
	    		<td style="color: #AAA;" style="color:black;"><?php echo $player['User']['ruler']; ?></td>
	    		<td style="color: #AAA;"><?php echo number_format($player['User']['honor']) ?></td>
	    	</tr>
		<?php } $lastRank = $player['User']['honor_rank']; ?>
	<?php endforeach; ?>
	<?php if($user['User']['honor_rank'] > $lastRank) {?>
			<tr style="background-color: #CCCCCC;">
				<td style="color: #AAA;"><?php echo number_format($user['User']['honor_rank']) ?></td>
	    		<td style="color: #AAA;"><a href="/users/profile/<?php echo $user['User']['id'] . $parms?>" style="color:black;"><?php echo $user['User']['ruler']; ?></a></td>
	    		<td style="color: #AAA;"><?php echo number_format($user['User']['honor']) ?></td>
	    	</tr>
	<?php } ?>
	<tr>
		<td colspan="3">
			<center><?php echo $paginator->numbers(); ?></center>
		</td>
	</tr>
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
			<form id="LeaderBoardRanks" method="post" action="/leaderboards/honorRedirect">
				Rank: 	<input name="data[Leaderboard][rank]" type="text" size="2" value="" id="LeaderboardRank" />
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
		<td>
			<form id="LeaderBoardRanks" method="post" action="/leaderboards/honorSearchRedirect">
				Search: 	<?php echo $ajax->autoComplete('Leaderboard.name', '/leaderboards/autoComplete')?>
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
	</tr>
</table>
