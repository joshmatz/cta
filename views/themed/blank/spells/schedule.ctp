
<style type="text/css">
    .auto_complete { width:200px; position:absolute; z-index:100; background-color:#ffffff; color: #000000; text-align:left; border:1px solid #bdc7d8; display: block;}
    .auto_complete ul { list-style-type:none; margin:0px; padding:0px; background-color:transparent; }
    .selected { background-color:#3b5998; color:#ffffff; }
    .auto_complete li a { background-color:#ffffff; color:#3b5998; text-decoration:none; padding:2px 6px; display:block; }
    .auto_complete li:hover{ background-color:#3b5998; color:#ffffff; }
</style>

<?php echo $form->create('User', array('url' => '/spells/addSchedule')); ?>

<?php echo $ajax->autoComplete('target', '/spells/autoComplete') ?><br />

<br />
Wizards: <?php echo $form->input('wizards', array('div' => false, 'label' => false, 'size' => 4)) ?> &nbsp; &nbsp;
Spell: <?php echo $form->input('spell', array('div' => false, 'label' => false, 'options' => $spellOptions)) ?> &nbsp; &nbsp;
Frequency: <?php echo $form->input('frequency', array('div' => false, 'label' => false, 'size' => 2)) ?> (Ticks in between each cast)
<?php 
echo $ajax->submit('Create', array('url'=> array('controller'=>'spells', 'action'=>'addSchedule'), 'update' => 'schedulelist', 'indicator' => 'spinner'));
echo $form->end();
?><br /><br />
<?php //$this->Js->get('#schedules')->sortable(array('complete'=> '/spells/reorder', 'serialize' => "{key: 'pictures_0[]'}")); ?>
<script type="text/javascript">
$(document).ready(function() {
$("#schedules").sortable({
update: function() {
$.post('/spells/reorder', $('#schedules').sortable("serialize"))
}
});
});
</script>
<div id="spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
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