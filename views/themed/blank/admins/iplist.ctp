<table>
<tr>
	<th>Ip Address</th>
	<th>Users</th>
</tr>
<?php foreach($addresses as $address): ?>
	<tr>
		<td><?php echo $address['IpAddress']['ip_address']?></a>
		<td><?php echo $address['IpAddress']['count']?></td>
	</tr>
<?php endforeach; ?>
</table>
<?php echo $paginator->numbers(); ?>
<!-- Shows the next and previous links -->
<?php
	echo $paginator->prev('Ç Previous ', null, null, array('class' => 'disabled'));
	echo $paginator->next(' Next È', null, null, array('class' => 'disabled'));
?> 
<!-- prints X of Y, where X is current page and Y is number of pages -->
<?php echo $paginator->counter(); ?>