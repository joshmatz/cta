<h1><?php echo $selected ?></h1>
CTA currently has these addresses saved for your kingdom:<br />
<table width="100%">
	<tr>
		<th>Date</th>
		<th>IP Address</th>
	</tr>
<?php foreach($addresses as $address): ?>
	<tr>
		<td><?php echo $address['IpAddress']['last_used'] ?></td>
		<td><?php echo $address['IpAddress']['ip_address'] ?></td>
	</tr>
<?php endforeach;?>
</table>