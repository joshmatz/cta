<table>
<?php foreach($scores as $score): ?>
<tr>
	<td><?php echo $score['score']; ?></td>
	<td><?php echo $score['count']; ?></td>
</tr>
<?php endforeach; ?>
</table>