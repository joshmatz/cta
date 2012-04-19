<?php 
	$paginator->options(array(
	'update' => '#messageList', 
    'before' => $js->get('#messageList')->effect('fadeOut', array('buffer' => false)),
    'success' => $js->get('#messageList')->effect('fadeIn', array('buffer' => false)),
    'url'=>array('controller'=>'messages', 'action'=>'sentList'), 
    'buffer' => false));
?>


<div id="spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>

<div id="messageList">
<?php if(!empty($messages)) { ?>
<table width="100%">
	<tr>
		<th width="20%">Receiver</th>
		<th width="65%">Title</th>
		<th width="15%">Date</th>
	</tr>
<?php 
	foreach($messages as $message):
?>
	<tr>
	<?php if($message['Message']['is_read']) {?>
	<td><?php echo $message['Message']['user'] ?></td>
	<td><a href="/messages/sentView/<?php echo $message['Message']['id'] ?>/<?php echo $parms ?>"><?php echo $message['Message']['title'] ?></td>
	<td><?php echo date('j M, Y, g:i T', $message['Message']['time']) ?></td>
	<?php } else { ?>
	<td><b><?php echo $message['Message']['user'] ?></b></td>
	<td><b><a href="/messages/sentView/<?php echo $message['Message']['id'] ?>/<?php echo $parms ?>"><?php echo $message['Message']['title'] ?></b></td>
	<td><b><?php echo date('j M, Y, g:i T', $message['Message']['time']) ?></b></td>
	<?php }?>
	</tr>
<?php
	endforeach;
?>
	<tr>
		<td colspan="3"><?php echo $paginator->numbers(); ?></td>
	</tr>
</table>
</div>
<?php } else { ?>
No sent messages exist.
<?php } ?>
