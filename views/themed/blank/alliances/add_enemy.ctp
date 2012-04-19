	<div id="enemies_spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
	<?php if(isset($error)) { echo $error; } ?>
	<table>
		<tr>
			<th>Enemies</th>
		</tr>
		<?php $one = false; foreach($allies as $ally): ?>
		<?php if($ally['AllianceFriend']['type'] == "enemy") { $one = true; ?>
		<tr>
			<td><a href="profile/<?php echo $ally['AllianceFriend']['friend_id']; ?>"><?php echo $ally['AllianceFriend']['Alliance']['Alliance']['short_name']; ?></a></td>
			<td><?php echo $ajax->link($html->image('x.png', array('alt' => 'remove')), array( 'controller' => 'alliances', 'action' => 'removeFriend', $ally['AllianceFriend']['friend_id'] ), array( 'update' => 'diplomacy', 'indicator' => 'allies_spinner', 'escape' => false), null, false); ?></td>
		</tr>
		<?php } ?>
		<?php endforeach; ?>
		<?php if(!$one) { ?>
		<tr>
			<td>You have no enemies.</td>
		</tr>
		<?php } ?>
		<?php if($user['User']['alliance_rank'] == "admin") {?>
		<tr>
			<td>
				<?php echo $form->create('Alliance'); ?> 
				<?php echo $form->input('short_name', array('label' => 'Add: ')); ?>
				<?php echo $ajax->submit('Submit', array('url'=> array('controller'=>'alliances', 'action'=>'addEnemy'), 'update' => 'enemies', 'indicator' => 'enemies_spinner')); ?>
				<?php echo $form->end(); ?>
			</td>
		</tr>
		<?php } ?>
	</table>