<?php $paginator->options(array('url'=>$this->passedArgs)); ?>

<center>
<a href="/fames/index/<?php echo $round ?>">Players</a> | <a href="/fames/alliance/<?php echo $round ?>">Alliances</a> <br />
<a href="/fames/alliance/4">Round 4</a> | <a href="/fames/alliance/5">Round 5</a> | <a href="/fames/alliance/6">Round 6</a> <br />
<a href="/fames/alliance/7">Round 7</a> | <a href="/fames/alliance/8">Round 8</a> | <a href="/fames/alliance/9">Round 9</a> | <a href="/fames/alliance/10">Round 10</a> | <a href="/fames/alliance/11">Round 11</a>
<table cellpadding="5">
	<tr>
		<th colspan="7"><center>Round <?php echo $round; ?></center></th>
	</tr>
	<tr> 
		<th><?php echo $paginator->sort('Rank', 'score_rank'); ?></th> 
		<th><?php echo $paginator->sort('Name', 'name'); ?></th> 
		<th><?php echo $paginator->sort('Members', 'members'); ?></th> 
		<th><?php echo $paginator->sort('Score', 'score'); ?></th> 
		<th><?php echo $paginator->sort('Honor', 'honor'); ?></th> 
		<th><?php echo $paginator->sort('OKS', 'oks'); ?></th>
		<th><?php echo $paginator->sort('DKS', 'dks'); ?></th>
		<th><?php echo $paginator->sort('TKS', 'tks'); ?></th>
	</tr> 
	   <?php $i = 0; foreach($data as $player): ?> 
	<tr <?php if($i % 2) { ?>bgcolor="#252525"<?php } else { ?>bgcolor="#000000"<?php } $i++;?>> 
		<td><?php echo $player['AllianceFame']['score_rank']; ?> </td> 
		<td><?php echo $player['AllianceFame']['name']; ?></td> 
		<td><?php echo number_format($player['AllianceFame']['members']); ?></td>
		<td><?php echo number_format($player['AllianceFame']['score']); ?></td>
		<td><?php echo number_format($player['AllianceFame']['honor']); ?> (<?php echo $player['AllianceFame']['honor_rank']; ?>)</td>
		<td><?php echo number_format($player['AllianceFame']['oks']); ?> (<?php echo $player['AllianceFame']['oks_rank']; ?>)</td>
		<td><?php echo number_format($player['AllianceFame']['dks']); ?> (<?php echo $player['AllianceFame']['dks_rank']; ?>)</td>
		<td><?php echo number_format($player['AllianceFame']['tks']); ?> (<?php echo $player['AllianceFame']['tks_rank']; ?>)</td>
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