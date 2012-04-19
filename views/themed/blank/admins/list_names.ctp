<div class="instructions"><center><h3>Instructions</h3></center>
Pages take a while to load because it's pulling each player's name from Facebook's server.<br /><br />
Below you'll find the list of real names of every player in the game. 
Clicking an ID number will bring up the player's profile. Shift+click to bring it up in a new tab.</div>

<center>
<?php if($user['User']['admin_status'] == "admin") { echo $form->create('User', array('action' => 'suspend')); } ?>
<table>
	<th><?php echo $paginator->sort('Facebook ID', 'facebook_id'); ?></th>
	<th>Facebook Name </th>
	<?php if($user['User']['admin_status'] == "admin") { ?><th>Suspend?</th><?php } ?>
<?php foreach($data as $name): ?>
<?php $name['data'] = $facebook->api('/' . $name['User']['facebook_id'] . '?fields=name'); ?>
	<tr>
		<td><a href="http://www.facebook.com/profile.php?id=<?php echo $name['User']['facebook_id']; ?>"><?php echo $name['User']['facebook_id']; ?></a>
		<td><a href="/users/profile/<?php echo $name['User']['id']; ?>"><?php echo $name['data']['name']; ?></a></td>
		<td><?php if($user['User']['admin_status'] == "admin") { echo $form->checkbox('Users.id.'.$name['User']['id'].'', array('value' => $name['User']['id']));  } ?></td>
	</tr>
<?php endforeach; ?>
</table>
<?php if($user['User']['admin_status'] == "admin") { echo $form->end('Suspend'); } ?>
<?php echo $paginator->numbers(); ?>
<!-- Shows the next and previous links -->
<?php
	echo $paginator->prev('Ç Previous ', null, null, array('class' => 'disabled'));
	echo $paginator->next(' Next È', null, null, array('class' => 'disabled'));
?> 
<!-- prints X of Y, where X is current page and Y is number of pages -->
<?php echo $paginator->counter(); ?>

</center>