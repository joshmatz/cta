Admin index
<table>
<tr>
	<th>ID</th>
	<th>Forum</th>
	<th>Description</th>
	<th>Access</th>
</tr>
<?php foreach($forums as $forum): ?>
<tr>
	<td><?php echo $forum['Forum']['id'] ?></td>
	<td><?php echo $forum['Forum']['name'] ?></td>
	<td><?php echo $forum['Forum']['description'] ?></td>
	<td><?php echo $forum['Forum']['access'] ?></td>
</tr>
<?php endforeach; ?>
</table>
<a href="/admin/forums/addForum">Add Forum</a>