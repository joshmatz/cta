
<?php 
	$paginator->options(array(
	'update' => '#trades', 
    'before' => $js->get('#trades')->effect('fadeOut', array('buffer' => false)),
    'success' => $js->get('#trades')->effect('fadeIn', array('buffer' => false)),
    'url'=>array('controller'=>'trades', 'action'=>'tradesList')));
?>

<div id="spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
<div id="trades">
<table width="100%">
	<tr> 
		<th><?php echo $paginator->sort('Kingdom', 'kingdom'); ?></th>
		<th><?php echo $paginator->sort('Wants Amount', 'request_amount') . " | " . $paginator->sort('Wants Type', 'request_type'); ?></th> 
		<th><?php echo $paginator->sort('Has Amount', 'amount') . " | " . $paginator->sort('Has Type', 'type');; ?></th> 
		<th><?php echo $paginator->sort('Ratio', 'ratio'); ?></th>
		<th><?php echo $paginator->sort('Offers', 'offers'); ?></th>
		<th>Options</th>
	</tr> 
	<?php if(!empty($trades)) { ?>
	   <?php foreach($trades as $trade): ?> 
	<tr> 
		<td><div style="text-align:center;"><a href="/users/profile/<?php echo $trade['Trade']['user_id']; ?>"><?php echo $trade['Trade']['kingdom']?></a></div></td>
		<td><div style="text-align:center;"><?php echo number_format($trade['Trade']['request_amount']) . " " ?> <img src="/img/<?php echo $trade['Trade']['request_type'] ?>.png" style="vertical-align:middle;" /></div></td> 
		<td><div style="text-align:center;"><?php echo number_format($trade['Trade']['amount']) . " " ?> <img src="/img/<?php echo $trade['Trade']['type'] ?>.png" style="vertical-align:middle;" /></div></td> 
		<td><div style="text-align:center;"><?php echo number_format($trade['Trade']['ratio'], 3, '.', ','); ?></div></td>
		<td><div style="text-align:center;"><?php echo number_format($trade['Trade']['offers']); ?></div></td>
		<td><div style="text-align:center;"><?php if($trade['Trade']['user_id'] != $user['User']['id']){ ?><?php echo $form->create('Trade', array('url' => '/trades/acceptOffer/' . $trade['Trade']['id'])); echo $form->input('offers', array('label' => false, 'div' => false, 'maxLength' => 2, 'size' => 2));?><input type="Submit" value="Accept"></form><?php } else { ?><a href="/trades/remove/<?php echo $trade['Trade']['id']?><?php echo $parms?>">Remove</a><?php } ?></div></td>
	</tr> 
	<?php endforeach; ?> 
	<tr>
		<td colspan="7">
			<center><?php echo $paginator->numbers(); ?></center>
		</td>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="7" style="text-align:center;">There are no offers on the market. <a href="/trades/create<?php echo $parms?>">Create a trade offer</a>. </td>
	</tr>
	<?php } ?>
</table> 
<?php
	echo $paginator->prev('« Previous ', null, null, array('class' => 'disabled'));
	echo $paginator->next(' Next »', null, null, array('class' => 'disabled'));
?> 
</div>

<br />
