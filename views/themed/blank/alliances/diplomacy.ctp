<div id="diplomacy">
	<div id="allies">
	<div id="allies_spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
	<table>
		<tr>
			<th>Allies</th>
		</tr>
		<?php $one = false; foreach($allies as $ally): ?>
		<?php if($ally['AllianceFriend']['type'] == "ally") { $one = true;?>
		<tr>
			<td><a href="profile/<?php echo $ally['AllianceFriend']['friend_id']; ?>"><?php echo $ally['AllianceFriend']['Alliance']['Alliance']['short_name']; ?></a></td>
			<?php if($user['User']['alliance_rank'] == "admin") {?> <td><?php echo $ajax->link($html->image('x.png', array('alt' => 'remove')), array( 'controller' => 'alliances', 'action' => 'removeFriend', $ally['AllianceFriend']['friend_id'] ), array( 'update' => 'diplomacy', 'indicator' => 'allies_spinner', 'escape' => false ), null, false); ?></td> <?php } ?>
		</tr>
		<?php } ?>
		<?php endforeach; ?>
		<?php if(!$one) { ?>
		<tr>
			<td>You have no alliances.</td>
		</tr>
		<?php } ?>
		<?php if($user['User']['alliance_rank'] == "admin") {?>
		<tr>
			<td>
				<?php echo $form->create('Alliance'); ?> 
				<?php echo $form->input('short_name', array('label' => 'Add: ')); ?>
				<?php echo $ajax->submit('Submit', array('url'=> array('controller'=>'alliances', 'action'=>'addAlly'), 'update' => 'allies', 'indicator' => 'allies_spinner')); ?>
				<?php echo $form->end(); ?>
			</td>
		</tr>
		<?php } ?>
	</table>
	* You cannot un-ally an Alliance for 7 days. A request will be sent to confirm the alliance with their leader.
	</div>
	<br /><br />
	<div id="naps">
	<div id="naps_spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
	<table>
		<tr>
			<th>Non-Aggression-Pact (NAP)</th>
		</tr>
		<?php $one = false; foreach($allies as $ally): ?>
		<?php if($ally['AllianceFriend']['type'] == "nap") { $one = true; ?>
		<tr>
			<td><a href="profile/<?php echo $ally['AllianceFriend']['friend_id']; ?>"><?php echo $ally['AllianceFriend']['Alliance']['Alliance']['short_name']; ?></a></td>
			<?php if($user['User']['alliance_rank'] == "admin") {?> <td><?php echo $ajax->link($html->image('x.png', array('alt' => 'remove')), array( 'controller' => 'alliances', 'action' => 'removeFriend', $ally['AllianceFriend']['friend_id'] ), array( 'update' => 'diplomacy', 'indicator' => 'allies_spinner', 'escape' => false ), null, false); ?></td> <?php } ?>
		</tr>
		<?php } ?>
		<?php endforeach; ?>
		<?php if(!$one) { ?>
		<tr>
			<td>You have no NAPs.</td>
		</tr>
		<?php } ?>
		<?php if($user['User']['alliance_rank'] == "admin") {?>
		<tr>
			<td>
				<?php echo $form->create('Alliance'); ?> 
				<?php echo $form->input('short_name', array('label' => 'Add: ')); ?>
				<?php echo $ajax->submit('Submit', array('url'=> array('controller'=>'alliances', 'action'=>'addNap'), 'update' => 'naps', 'indicator' => 'naps_spinner')); ?>
				<?php echo $form->end(); ?>
			</td>
		</tr>
		<?php } ?>
	</table>
	* This is non-binding and the other alliance will not be alerted.
	</div>
	<br /><br />
	<div id="enemies">
	<div id="enemies_spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
	<table>
		<tr>
			<th>Enemies</th>
		</tr>
		<?php $one = false; foreach($allies as $ally): ?>
		<?php if($ally['AllianceFriend']['type'] == "enemy") { $one = true; ?>
		<tr>
			<td><a href="profile/<?php echo $ally['AllianceFriend']['friend_id']; ?>"><?php echo $ally['AllianceFriend']['Alliance']['Alliance']['short_name']; ?></a></td>
			<?php if($user['User']['alliance_rank'] == "admin") {?> <td><?php echo $ajax->link($html->image('x.png', array('alt' => 'remove')), array( 'controller' => 'alliances', 'action' => 'removeFriend', $ally['AllianceFriend']['friend_id'] ), array( 'update' => 'diplomacy', 'indicator' => 'allies_spinner', 'escape' => false ), null, false); ?></td> <?php } ?>
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
	* The Alliance will be alerted. Spoils between the two alliances will increase 10%. Cannot be undone for 7 days.
	</div>
</div>