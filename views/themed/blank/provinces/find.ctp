<a href="/provinces/<?php echo $parms ?>">Province</a> | <a href="/forums<?php echo $parms ?>">Forum</a><br /><br />

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
	
	<?php if(isset($members[0]['User']['kingdom'])) {
	foreach($members as $member): ?>
		<tr>
			<td><a href="/users/profile/<?php echo $member['User']['id'] . $parms?>"><?php echo $member['User']['ruler'] . " of " . $member['User']['kingdom'] ?></a></td>
			<td><?php echo $member['User']['race'] ?></td>
			<td><?php echo number_format($member['Resource']['land']) ?></td>
			<td><?php echo number_format($member['Building']['castle']) ?></td>
			<td><?php echo number_format($member['User']['score']) ?></td>
		</tr>
	<?php endforeach; }?>
</table>