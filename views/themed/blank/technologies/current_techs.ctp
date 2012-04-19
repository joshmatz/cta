<h1>Your researched technologies</h1>
<table width="100%">
<tr>
	<td colspan="2"><h3>Military Tree</h3></td>
</tr>
<?php if($tree1) {?>
<?php foreach($tree1 as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><h3>Production Tree</h3></td>
</tr>
<?php if($tree2) {?>
<?php foreach($tree2 as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><h3>Economy Tree</h3></td>
</tr>
<?php if($tree3) {?>
<?php foreach($tree3 as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><h3>Magic Tree</h3></td>
</tr>
<?php if($tree4) {?>
<?php foreach($tree4 as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><h3>Racial Tree</h3></td>
</tr>
<?php if($racial) {?>
<?php foreach($racial as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><h3>Blade Tree</h3></td>
</tr>
<?php if($blade) {?>
<?php foreach($blade as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><h3>Light Armor Tree</h3></td>
</tr>
<?php if($lightArmor) {?>
<?php foreach($lightArmor as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><h3>Heavy Armor Tree</h3></td>
</tr>
<?php if($heavyArmor) {?>
<?php foreach($heavyArmor as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2"><h3>Range Tree</h3></td>
</tr>
<?php if($range) {?>
<?php foreach($range as $branch): ?>
<tr>
	<td><center><img src="<?php echo "/img/technologies/" .$branch['img'].".png" ?>" alt="<?php echo $branch['name'] ?>" height="50"><br /><?php echo $branch['name'] ?></center></td>
	<td><?php echo $branch['description'] ?></td>
</tr> 
<?php endforeach; ?>
<?php } else { ?>
<tr>
	<td colspan="2">You have nothing researched in this tree.</td>
</tr>
<?php } ?>
</table>