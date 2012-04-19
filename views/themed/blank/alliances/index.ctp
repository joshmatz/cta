<?php if(empty($alliance)) { ?>
<h1>Alliances </h1>
You may join a prexisting alliance or create your own.<br />

<?php if(true) {
	echo $form->create('Alliance', array('action' => 'create'));
	echo $form->input('name');
	echo $form->input('short_name', array('label' => 'Abbreviation'));
	echo $form->end('Create'); 
} else {
?>
To create an alliance you must be <a href="/premiums">a premium member</a>.
<?php } ?>
<br /><br />
<table>
	<tr colspan="3"> 
		<th>Invitations</th>
	</tr>
	<?php if(!empty($invites)) { ?>
	<?php foreach($invites as $invite):?>
		<tr>
			<td><?php echo $invite['AllianceInvite']['name']['Alliance']['name']; ?></td>
			<td><a href="/alliances/acceptInvite/<?php echo $invite['AllianceInvite']['id'] ?>">Accept</a></td>
			<td><a href="/alliances/denyInvite/<?php echo $invite['AllianceInvite']['id'] ?>">Deny</a></td>
		</tr>
	<?php endforeach; ?>
	<?php } else { ?>
	<tr>
		<td>You have no invitations</td>
	</tr>
	<?php } ?>
</table>


<?php } else { ?>

<div style="height:23px; margin-bottom:0px; margin-top:10px; margin-left: 1px; margin-right: 1px; float: left;">
	<a href="/alliances/allianceReports"><?php echo $html->image("/img/layout/reportstab.png", array("alt" => "Reports", "style" => "float:left;")); ?></a>
	<div class="kingdomInfo"><b>Gametime:</b> <?php echo date('j M, Y, g:i', $_SERVER['REQUEST_TIME']) ?> | <b>Ruler:</b> <?php echo $user['User']['ruler'] ?> | <b>Alliance:</b> <?php echo $alliance['Alliance']['name']?></div>
</div>
<div style="margin-top:20px; margin-left: 15px; float:left;">
<?php 
	foreach($reports as $report):
?>	
	<div class="report">
		<div class="date"><b><?php echo $report['Report']['ruler']; ?> | <?php echo date('j M, Y, H:i', $report['Report']['time']) ?></b></div>
		<div class="reportBody"><?php echo $report['Report']['body'] ?></div>
	</div>
	<div class="separator">&nbsp;</div>
<?php
	endforeach;
?>
</div>

<?php } ?>