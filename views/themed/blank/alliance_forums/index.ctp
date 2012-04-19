<table width="100%" cellpadding="10">
	<tr>
		<td colspan="4">
			<center><?php echo $paginator->numbers(); ?></center>
		</td>
	</tr>
	<tr> 
		<th width="65%"><div style="text-align:left;"><?php echo "Title"; ?></div</th>
		<th width="15%"><div style="text-align:left;"><?php echo "Author";  ?></div</th> 
		<th><div style="text-align:left;"><?php echo "Posts"; ?></div</th> 
		<th width="15%"><div style="text-align:left;"><?php echo $paginator->sort('Date', 'date');  ?></div</th> 
	</tr> 
	<?php if(!empty($topics)) { $i = 0; ?>
	   <?php foreach($topics as $topic): $i++; ?> 
	<tr> 
		<td><div style="text-align:left;"><a href="/allianceForums/viewTopic/<?php echo $topic['AllianceTopic']['id'] ?>/"><?php echo $topic['AllianceTopic']['title']; ?></a></div></td> 
		<td><div style="text-align:left;"><?php echo $topic['AllianceTopic']['author'] ?></div></td> 
		<td><div style="text-align:left;"><?php echo $topic['AllianceTopic']['posts']; ?></div></td> 
		<td><div style="text-align:left;"><?php echo date('j M, Y', $topic['AllianceTopic']['date']); ?></div></td> 
	</tr> 
	<?php endforeach; ?>
	<tr>
		<td colspan="4">
			<center><?php echo $paginator->numbers(); ?></center>
		</td>
	</tr>
	<tr>
		<td colspan="4"><a href="/allianceForums/post">Create a new topic</a></td>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="4" style="text-align:center;">There are no topics in your alliance forum. <a href="/allianceForums/post">Post something</a>. </td>
	</tr>
	<?php } ?>
</table> 