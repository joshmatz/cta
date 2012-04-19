
	Current Invations:<br />

	<?php foreach($invites as $invite): ?>
	<a href="/users/profile/<?php echo $invite['AllianceInvite']['name']['User']['id']; ?>"><?php echo $invite['AllianceInvite']['name']['User']['ruler'] ; ?></a> <br />
	
	<?php endforeach; ?>
