<?php
	$paginator->options['url']['?'] = $simpleParms;
?>
<table width="100%">
	<tr> 
		<th><?php echo $paginator->sort('Date', 'date'); ?></th> 
		<th><?php echo $paginator->sort('Kingdom', 'kingdom') . " | " . $paginator->sort('Province', 'province')?></th>
		<th><?php echo $paginator->sort('For Amount', 'request_amount') . " | " . $paginator->sort('For Type', 'request_type'); ?></th> 
		<th><?php echo $paginator->sort('Offer Amount', 'amount') . " | " . $paginator->sort('Offer Type', 'type');; ?></th> 
		<th>Options</th>
	</tr> 
	<?php if(empty($trades)) { ?> <tr><td colspan="5" style="text-align:center;">You currently have no offers on the market. <a href="/trades/create<?php echo $parms ?>">Create a trade offer</a>. </td></tr><?php } else {?>
	   <?php foreach($trades as $trade): ?> 
		<tr> 
			<td><div style="text-align:center;"><?php echo date('j M, Y', $trade['Trade']['date']); ?></div></td> 
			<td><div style="text-align:center;"><?php echo $trade['Trade']['kingdom']?></div></td>
			<td><div style="text-align:center;"><?php echo number_format($trade['Trade']['request_amount']) . " " ?> <img src="/img/<?php echo $trade['Trade']['request_type'] ?>.png" style="vertical-align:middle;" /></div></td> 
			<td><div style="text-align:center;"><?php echo number_format($trade['Trade']['amount']) . " " ?> <img src="/img/<?php echo $trade['Trade']['type'] ?>.png" style="vertical-align:middle;" /></div></td> 
			<td><div style="text-align:center;"><?php if($trade['Trade']['user_id'] != $user['User']['id']){ ?><a href="/trades/acceptOffer/<?php echo $trade['Trade']['id']?>">Accept</a><?php } else { ?><a href="/trades/remove/<?php echo $trade['Trade']['id']?>">Remove</a><?php } ?></div></td>
		</tr> 
		<?php endforeach; ?> 
		<tr>
			<td colspan="5">
				<center><?php echo $paginator->numbers(); ?><br /><a href="/trades/removeAll<?php echo $parms ?>">Remove all offers</a></center>
			</td>
		</tr>
	<?php } ?>
</table> 
<?php
	echo $paginator->prev('« Previous ', null, null, array('class' => 'disabled'));
	echo $paginator->next(' Next »', null, null, array('class' => 'disabled'));
?> 
