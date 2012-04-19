<?php 
	//$paginator->options(array('update' => '#messageList', 'indicator' => 'spinner', 'url'=>array('controller'=>'messages', 'action'=>'messageList')));
?>


<?php 
	$paginator->options(array(
	'update' => '#messageList', 
    'before' => $js->get('#messageList')->effect('fadeOut', array('buffer' => false)),
    'success' => $js->get('#messageList')->effect('fadeIn', array('buffer' => false)),
    'url'=>array('controller'=>'messages', 'action'=>'messageList')));
?>

<div id="spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
<div id="messageList">
<?php if(!empty($messages)) { ?>
<div style="text-align:left">
<form id="MessageDeleteForm" method="post" action="/messages/delete">
<table width="100%">
	<tr>
		<th width="20%">Sender</th>
		<th width="55%">Title</th>
		<th width="20%">Date</th>
		<th width="5%">Delete</th>
	</tr>
<?php 
	foreach($messages as $message):
?>
	<tr>
	<?php if($message['Message']['is_read']) {?>
	<td><?php echo $message['Message']['sender'] ?></td>
	<td><a href="/messages/view/<?php echo $message['Message']['id'] ?>/<?php echo $parms ?>"><?php echo $message['Message']['title'] ?></td>
	<td><?php echo date('j M, Y, g:i T', $message['Message']['time']) ?></td>
	<td><?php echo $form->checkbox('Messages.id.'.$message['Message']['id'].'', array('value' => $message['Message']['id'])); ?></td>
	<?php } else { ?>
	<td><b><?php echo $message['Message']['sender'] ?></b></td>
	<td><b><a href="/messages/view/<?php echo $message['Message']['id'] ?>/<?php echo $parms ?>"><?php echo $message['Message']['title'] ?></b></td>
	<td><b><?php echo date('j M, Y, g:i T', $message['Message']['time']) ?></b></td>
	<td><?php echo $form->checkbox('Messages.id.'.$message['Message']['id'].'', array('value' => $message['Message']['id'])); ?></td>
	<?php }?>
	</tr>
<?php
	endforeach;
?>
<script>
	function selectAll(form,checked){for(var i=0;i<form.length;i++)form.elements[i].checked=checked}
</script>
	<tr>
		<td colspan="4"><div style="text-align:right;">select all <input name="all" type="checkbox" class="selectAll" onclick="selectAll(this.form, this.checked)"/></div></td>
	</tr>
	<tr>
		<td colspan="4"><div style="text-align:right;"><input type="submit" value="Delete" /></div></td>
	</tr>
	<tr>
		<td colspan="4"><?php echo $paginator->numbers(); ?></td>
	</tr>
</table>
</form>
</center>
</div>
<?php } else { ?>
<center>You have no messages!</center>
<?php } ?>
</div>