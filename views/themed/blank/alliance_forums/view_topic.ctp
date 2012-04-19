
<table cellpadding="10">
	<tr>
		<td colspan="4">
			<center><?php echo $paginator->numbers(); ?></center>
		</td>
	</tr>
	<tr> 
		<th width="15%"><div style="text-align:left;"><?php echo "Author";  ?></div></th> 
		<th width="75%"><div style="text-align:left;"><?php echo "Body"; ?></div></th>
		<th width="10%"><div style="text-align:left;"><?php echo $paginator->sort('Date', 'date');  ?></div></th> 
	</tr> 
	<?php if(!empty($posts)) { $i = 0; ?>
	   <?php foreach($posts as $post): $i++; ?> 
	<tr> 
		<td valign="top"><div style="text-align:left;"><?php echo $post['AlliancePost']['author'] ?></div></td> 
		<td valign="top"><div style="text-align:left;"><?php echo $post['AlliancePost']['body']; ?></div></td> 
		<td valign="top"><div style="text-align:left;"><?php echo date('G:i, j M, Y', $post['AlliancePost']['date']); ?></div></td> 
	</tr> 
	<?php endforeach; ?>
	<tr>
		<td colspan="4">
			<center><?php echo $paginator->numbers(); ?></center>
		</td>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="4" style="text-align:center;">There are no posts in this topic. <a href="/allianceForums/post">Post something</a>. </td>
	</tr>
	<?php } ?>
</table> 
<?php if(isset($topicId)) { ?>
<div style="text-align:left; margin-left:auto; margin-right: auto; margin-top: 5px;">
<form id="MessagesSendForm" method="post" action="/allianceForums/postPost">
<table>
	<tr>
		<th colspan="2">Reply<input name="data[AlliancePost][topic_id]" value="<?php echo $topicId ?>" type="hidden" id="PostsTopicId" /></th>
	</tr>
	<tr>
		<td valign="top">Body:</td>
		<td><textarea name="data[AlliancePost][body]" value="" cols=60 rows=10 id="PostsBody" /></textarea></td>
	</tr>
	<tr>
		<td valign="top"></td>
		<td><div style="text-align:right;"><input type="submit" value="Send" /></div></td>
	</tr>
</table><br />

</form>
</div>
<?php } ?>