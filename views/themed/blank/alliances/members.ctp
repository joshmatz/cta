
	<h1><?php echo $alliance['Alliance']['name']?> Members</h1>
	<?php foreach($members as $member): ?>
		<?php echo $member['User']['ruler'] . " of " . $member['User']['kingdom'] ?><br />
	<?php endforeach;?>
