<div class="resources form">
<?php echo $this->Form->create('GameRace');?>
	<fieldset>
 		<legend><?php printf(__('Admin Edit %s', true), __('Race', true)); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('GameRace.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('GameRace.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Races', true)), array('action' => 'index'));?></li>

	</ul>
</div>