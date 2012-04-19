<table width="100%">
	<tr>
		<th>ID</th>
		<th>Date</th>
		<th>Type</th>
		<th>Change</th>
		<th>New Ammount</th>
		<th>More Information</th>
	</tr>
<?php foreach($premiums as $premium): ?>
	<tr>
		<td><?php echo $premium['Premium']['id'] ?></td>
		<td><?php echo $premium['Premium']['date'] ?></td>
		<td><?php echo $premium['Premium']['type'] ?></td>
		<td><?php echo $premium['Premium']['change'] ?></td>
		<td><?php echo $premium['Premium']['new_amount'] ?></td>
		<td><?php echo $premium['Premium']['information'] ?></td>
	</tr>
<?php endforeach;?>
</table>