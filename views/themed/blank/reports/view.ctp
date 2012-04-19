<strong>This report belongs to <?php echo $name ?></strong>
<table width="100%">
<tr>
	<td>
		<strong><?php echo $report['Report']['title']; ?></strong><br /><br />
		<?php echo $report['Report']['body']; ?>
	</td>
	<td valign="top">
		<b><?php echo date('j M, Y, H:i', $report['Report']['time']) ?></b>
	</td>
</tr>
</table>