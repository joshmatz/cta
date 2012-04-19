<?php $paginator->options(array('url'=>$this->passedArgs)); ?>

<center>
<a href="/fames">Players</a> | <a href="/fames/alliance/<?php echo $round ?>">Alliances</a> <br />
<a href="/fames">Round 1</a> | <a href="/fames/index/2">Round 2 (closed beta)</a> | <a href="/fames/index/3">Round 3 (open beta)</a> | <a href="/fames/index/4">Round 4</a> | <a href="/fames/index/5">Round 5</a> <br />
<a href="/fames/index/6">Round 6</a> | <a href="/fames/index/7">Round 7</a> | <a href="/fames/index/8">Round 8</a> | <a href="/fames/index/9">Round 9</a> | <a href="/fames/index/10">Round 10</a> | <a href="/fames/index/11">Round 11</a>
<table cellpadding="5">
	<tr>
		<th colspan="<?php if($round < 5) { echo "7"; } else { echo "10"; } ?>"><center>Round <?php echo $round; ?></center></th>
	</tr>
	<tr> 
		<th><?php echo $paginator->sort('Rank', 'rank'); ?></th> 
		<th><?php echo $paginator->sort('Ruler', 'ruler'); ?><?php if(!$data[0]['Fame']['alliance'] || $round <= 4) { echo " ("; } else { if($data[0]['Fame']['kingdom']) { echo " of "; } } ?><?php echo $paginator->sort('Kingdom', 'kingdom'); ?><?php if(!$data[0]['Fame']['alliance'] || $round <= 4) { ?>, <?php echo $paginator->sort('Province', 'province'); ?>)<?php } ?></th> 
		<?php if($data[0]['Fame']['alliance'] || $round > 4) { ?><th><?php echo $paginator->sort('Alliance', 'alliance'); ?></th><?php } ?>
		<th><?php echo $paginator->sort('Land', 'land'); ?></th> 
		<th><?php echo $paginator->sort('Castles', 'castle'); ?></th> 
		<th><?php echo $paginator->sort('Score', 'score'); ?></th> 
		<?php if($data[0]['Fame']['honor_rank']) { ?><th><?php echo $paginator->sort('Honor', 'honor'); ?></th><?php } ?>
		<th><?php echo $paginator->sort('OKS', 'oks'); ?></th>
		<th><?php echo $paginator->sort('DKS', 'dks'); ?></th>
		<?php if($data[0]['Fame']['tks']) { ?><th><?php echo $paginator->sort('TKS', 'tkrank'); ?></th><?php } ?>
	</tr> 
	   <?php $i = 0; foreach($data as $player): ?> 
	<tr> 
		<td><?php echo $player['Fame']['rank']; ?> </td> 
		<td><?php echo $player['Fame']['ruler']; ?><?php if(!$data[0]['Fame']['alliance'] || $round <= 4) { echo " ("; } else { if($data[0]['Fame']['kingdom']){echo " of "; }} ?><?php echo $player['Fame']['kingdom']; ?><?php if(!$data[0]['Fame']['alliance'] || $round <= 4) { ?>, <?php echo $player['Fame']['province']; ?>)<?php } ?><?php if($player['Fame']['race']) { echo " | " . $player['Fame']['race']; } ?></td> 
		<?php if($data[0]['Fame']['alliance'] || $round > 6) { ?><td><?php echo $player['Fame']['alliance']; ?></td><?php } ?>
		<td><?php echo number_format($player['Fame']['land']); ?></td>
		<td><?php echo number_format($player['Fame']['castle']); ?></td>
		<td><?php echo number_format($player['Fame']['score']); ?></td>
		<?php if($data[0]['Fame']['honor_rank']) { ?><td><?php echo number_format($player['Fame']['honor']); ?> (<?php echo $player['Fame']['honor_rank']; ?>)</td><?php } ?>
		<td><?php echo number_format($player['Fame']['oks']); ?> (<?php echo $player['Fame']['okrank']; ?>)</td>
		<td><?php echo number_format($player['Fame']['dks']); ?> (<?php echo $player['Fame']['dkrank']; ?>)</td>
		<?php if($data[0]['Fame']['tks']) { ?><td><?php echo number_format($player['Fame']['tks']); ?> (<?php echo $player['Fame']['tkrank']; ?>)</td><?php } ?>
	</tr> 
	<?php endforeach; ?> 
</table> 
<!-- Shows the page numbers -->
<?php echo $paginator->numbers(); ?><br />
<!-- Shows the next and previous links -->
<?php
	echo $paginator->prev('Ç Previous ', null, null, array('class' => 'disabled'));
	echo $paginator->next(' Next È', null, null, array('class' => 'disabled'));
?> 
<!-- prints X of Y, where X is current page and Y is number of pages -->
<?php echo $paginator->counter(); ?>
<br /><br /><br />
</center>