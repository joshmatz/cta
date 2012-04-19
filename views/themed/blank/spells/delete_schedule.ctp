<div id="schedulelist">
<?php if(!empty($schedules)) { ?>

<ol style="width:60%; list-style-type:upper-roman">
	<div id="schedules" style="margin-top: 5px; margin-bottom: 15px;">
	<?php foreach($schedules as $schedule): ?>
		<li id="schedule_<?php echo $schedule['MagicSchedule']['id'] ?>" style="text-align: left;">
			<strong>Target: </strong><?php echo $schedule['MagicSchedule']['target_name']?> | <strong>Spell: </strong><?php $spell = $schedule['MagicSchedule']['spell']; echo $spellOptions[$spell] ?><br />
			<strong>Wizards: </strong><?php
					echo $inplaceUpdater->input
					(
					'MagicSchedule',
					'wizards_' . $schedule['MagicSchedule']['id'],
					$schedule['MagicSchedule']['id'],
					array
					(
					'value'         =>  $schedule['MagicSchedule']['wizards'],
					'actionName'    => 'spells/updateSchedule/wizards',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit wizards',
					'containerType' => 'span',
					),
					true
					);
				?> | <strong>Frequency: </strong><?php echo $schedule['MagicSchedule']['frequency']?> | <strong>Next cast: </strong> <?php echo $schedule['MagicSchedule']['current']?><br />
			<strong><?php echo $ajax->link('Delete', array('controller' => 'spells', 'action' => 'deleteSchedule', $schedule['MagicSchedule']['id']), array('update' => 'schedulelist'), 'Do you want to delete this schedule?'); ?></strong>
		</li>
	<?php endforeach; ?>
	</div>
</ol>

<?php } else { ?>
<center>You have no schedules.</center>
<?php } ?>
</div>
<?php echo $ajax->sortable('schedules', array('url'=> array('controller'=>'spells', 'action'=>'reorder'))); ?>