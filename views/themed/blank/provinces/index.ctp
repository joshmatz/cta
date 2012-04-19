<a href="/provinces/<?php echo $parms ?>">Province</a> | <a href="/forums<?php echo $parms ?>">Forum</a><br /><br />

<div class="instructions"><center><h3>Instructions</h3></center>The provincial viewer will let you look at the various provinces in Call to Arms in order to 
size up the competition or just check out how your own province is growing. If you're looking for more information about a specific kingdom, try casting a 
<a href="/spells/">Truesight</a> spell on them.</div>
<h3>Provincial Viewer</h3>
<?php /*echo $form->create('Province', array('action' => 'findRedirect', 'type' => 'post')); ?>
Province name:<?php echo $form->input('name', 
	  	array(
	  		'options' => array(
			  	'Tuth'=>'Tuth',
			    'Edilar'=>'Edilar',
			    'Ruch'=>'Ruch',
			    'Khardfee'=>'Khardfee',
			    'Alderd'=>'Alderd' 
	  		), 	  		
	  		'default'=> $user['User']['province'], 
	  		'label' => false, 
	  		'div' => false
	  	)
	  );
?>
 Number: <?php echo $form->input('provinceNumber', array('type' => 'text', 'size' => 2, 'default' => $members[0]['User']['province_number'], 'div' => false, 'label' => false)); ?>

<?php echo $form->end(array('Go', 'div' => false, 'label' => false)); */?>
<form id="ProvinceFindRedirect" method="post" action="/provinces/findRedirect<?php echo $parms?>">
Province: <select name="data[Province][name]" id="ProvinceName">
<option value="Thorninn">Thorninn</option>
<option value="Endaris">Endaris</option>
<option value="Mur'gug">Mur'gug</option>
<option value="Kaloria">Kaloria</option>
<option value="Knute">Knute</option>
</select>
Number: <input name="data[Province][provinceNumber]" type="text" size="2" id="ProvinceNumber" />
</select><input type="submit" value="Go" /></form>
<table align="center">
	<tr>
		<th colspan="5" align="center"><?php echo $members[0]['User']['province'] . " " . $members[0]['User']['province_number']?></th>
	</tr>
	<tr>
		<th align="center">Ruler / Kingdom</th>
		<th align="center">Race</th>
		<th align="center">Land</th>
		<th align="center">Castles</th>
		<th align="center">Kingdom Strength</th>
	</tr>
	<?php foreach($members as $member): ?>
		<tr>
			<td><a href="/users/profile/<?php echo $member['User']['id'] . $parms?>"><?php echo $member['User']['ruler'] . " of " . $member['User']['kingdom'] ?></a></td>
			<td><?php echo $member['User']['race'] ?></td>
			<td><?php echo number_format($member['Resource']['land']) ?></td>
			<td><?php echo number_format($member['Building']['castle']) ?></td>
			<td><?php echo number_format($member['User']['score']) ?></td>
		</tr>
	<?php endforeach; ?>
</table>