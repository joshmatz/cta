<style type="text/css">
    .auto_complete { width:200px; position:absolute; z-index:100; background-color:#ffffff; color: #000000; text-align:left; border:1px solid #bdc7d8; display: block;}
    .auto_complete ul { list-style-type:none; margin:0px; padding:0px; background-color:transparent; }
    .selected { background-color:#3b5998; color:#ffffff; }
    .auto_complete li a { background-color:#ffffff; color:#3b5998; text-decoration:none; padding:2px 6px; display:block; }
    .auto_complete li:hover{ background-color:#3b5998; color:#ffffff; }
</style>

<h3>Spells</h3>

<br />

<table>
	<tr>
		<td>Cast on yourself:</td>
		<td><a href="/spells/selectSpell/<?php echo $user['User']['id'] ?>">Self Cast</a></td>
	</tr>
	<tr>
		<td>
			Or select a target: 
		</td>
		<td>
			<?php echo $form->create('User', array('url' => 'searchSpell/')); ?>
			<?php echo $ajax->autoComplete('Spell.receiver', '/spells/autoComplete')?>
			<?php echo $form->end(array('div' => false, 'label' => false)); ?>
		</td>
	</tr>
	
	<tr>
		<th colspan="2">Wizards</th>
	</tr>
	<tr>
		<th>Available</th>
		<th>Resting</th>
	</tr>
	<tr>
		<td><center><?php echo number_format($user['User']['Army']['wizard']) ?></center></td>
		<td><center><?php echo number_format($user['User']['Army']['wizard_resting']) ?></center></td>
	</tr>
	<tr>
		<td colspan="2">Your wizards rest at a rate of <?php echo $willRest ?> per hour.</td>
	</tr>
</table>
