<?php if (!empty($message['Message']['body'])) { ?>
<div class="report">
<div class="date"><b><?php echo date('j M, Y, g:i T', $message['Message']['time']) ?> | Title: <?php echo $message['Message']['title'] ?></b></div>
<div class="reportBody"><?php echo $message['Message']['body']; ?><p /> ~ <?php echo $message['Message']['sender'] ?><br /> <br /></div></div>
<div class="separator">&nbsp;</div>
<?php } ?>
<div style="text-align:left;">
<form id="MessagesSendForm" method="post" action="/messages/sendMessage<?php echo $parms ?>">
<table>
	<tr>
		<td valign="top">To: </td>
		<td><input name="data[Message][receiver]" value="<?php echo $receiver ?>" type="hidden" id="MessagesReceiver" /><?php echo $message['Message']['sender'] ?><br /></td>
	</tr>
	<tr>
		<td valign="top">Title: </td>
		<td><input name="data[Message][title]" value="" type="text" size="20" id="MessagesTitle" /><br /></td>
	</tr>
	<tr>
		<td valign="top">Body:</td>
		<td><textarea name="data[Message][body]" value="" cols=60 rows=10 id="MessagesBody" /></textarea><br /></td>
	</tr>
	<tr>
		<td valign="top"></td>
		<td><div style="text-align:right;"><input type="submit" value="Send" /></div></td>
	</tr>
</table><br />

</form>
</div>