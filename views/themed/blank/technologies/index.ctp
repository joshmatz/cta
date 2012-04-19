
<div class="instructions"><h3>Instructions</h3>This is the technologies page. Here you'll be able to research different technologies that
will help you in various ways across your kingdom. The top four options make up four different technology trees, that is, once you research Field Glass from the first tree,
Habitation will become available to you after it research is completed. The fifth option is race-specific, meaning only your race has access to that technology.</div>
<h3>Technologies</h3><br />
<?php if(isset($currentResearch)) { ?>
	You are currently researching <?php echo $currentResearch['name'] ?>. Your researchers will be done in <?php echo $currentResearch['time'] ?> ticks. <br />
<?php if($user['User']['premium']) { 
	$newResearch = $currentResearch; 
	if(!$user['Technology']['queue']) {
		unset($currentResearch);?>
	<br /><br />Because you are using a premium account, you may queue one additional research to be started at the completion of <?php echo $newResearch['name'] ?>.
<?php } else { // end if queued ?>
	<br /><br />You currently have <?php echo $queuedResearch['name'] ?> in your technology queue. Your researchers will begin work after <?php echo $newResearch['name'] ?> is completed.
<?php } // end if premium ?>

<?php } 
		}; if($tree1['set'] || $tree2['set'] || $tree3['set'] || $tree4['set'] || $racial['set'] || $range['set'] || $blade['set'] || $lightArmor['set'] || $heavyArmor['set']) { ?>
<form id="TechnologyResearch" method="post" action="/technologies/research<?php echo $parms?>"><center>
<input name="data[Technology][tree]" id="TechnologyTree_" value="" type="hidden">
<table cellpadding=3> 
	<tr>
		<?php if(!isset($currentResearch)){ ?><th>Buy</th><?php  }?>
		<th>Technology</th>
		<th>Effect</th>
		<th>Research time</th>
		<th>Cost (in gold)</th>
	</tr> 
	<?php if($tree1['set']) { ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyTree1" value="tree_1" type="radio"> </center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$tree1['img'].".png" ?>" alt="<?php echo $tree1['name'] ?>" height="50"><br /><?php echo $tree1['name'] ?></center></td>
		<td><?php echo $tree1['description'] ?></td>
		<td><?php echo $tree1['time'] ?></td>
		<td><?php echo number_format($tree1['cost']) ?></td>
	</tr> 
	<?php }; if($tree2['set']) { ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyTree2" value="tree_2" type="radio"></center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$tree2['img'].".png" ?>" alt="<?php echo $tree2['name'] ?>" height="50"><br /><?php echo $tree2['name'] ?></center></td>
		<td><?php echo $tree2['description'] ?></td>
		<td><?php echo $tree2['time'] ?></td>
		<td><?php echo number_format($tree2['cost']) ?></td>
	</tr> 
	<?php }; if($tree3['set']) { ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyTree3" value="tree_3" type="radio"></center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$tree3['img'].".png" ?>" alt="<?php echo $tree3['name'] ?>" height="50"><br /><?php echo $tree3['name'] ?></center></td>
		<td><?php echo $tree3['description'] ?></td>
		<td><?php echo $tree3['time'] ?></td>
		<td><?php echo number_format($tree3['cost']) ?></td>
	</tr> 	
	<?php }; if($tree4['set']) { ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyTree4" value="tree_4" type="radio"></center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$tree4['img'].".png" ?>" alt="<?php echo $tree4['name'] ?>" height="50"><br /><?php echo $tree4['name'] ?></center></td>
		<td><?php echo $tree4['description'] ?></td>
		<td><?php echo $tree4['time'] ?></td>
		<td><?php echo number_format($tree4['cost']) ?></td>
	</tr> 
	<?php }; if($racial['set']) { ?>	
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyTreeRacial" value="racial" type="radio"></center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$racial['img'].".png" ?>" alt="<?php echo $racial['name'] ?>" height="50"><br /><?php echo $racial['name'] ?></center></td>
		<td><?php echo $racial['description'] ?></td>
		<td><?php echo $racial['time'] ?></td>
		<td><?php echo number_format($racial['cost']) ?></td>
	</tr> 
	<?php }; if($blade['set']) { ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyBlade" value="blade" type="radio"></center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$blade['img'].".png" ?>" alt="<?php echo $blade['name'] ?>" height="50"><br /><?php echo $blade['name'] ?></center></td>
		<td><?php echo $blade['description'] ?></td>
		<td><?php echo $blade['time'] ?></td>
		<td><?php echo number_format($blade['cost']) ?></td>
	</tr> 
	<?php }; if($lightArmor['set']) { ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyLightArmor" value="light_armor" type="radio"></center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$lightArmor['img'].".png" ?>" alt="<?php echo $lightArmor['name'] ?>" height="50"><br /><?php echo $lightArmor['name'] ?></center></td>
		<td><?php echo $lightArmor['description'] ?></td>
		<td><?php echo $lightArmor['time'] ?></td>
		<td><?php echo number_format($lightArmor['cost']) ?></td>
	</tr> 
	<?php }; if($heavyArmor['set']) { ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyHeavyArmor" value="heavy_armor" type="radio"></center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$heavyArmor['img'].".png" ?>" alt="<?php echo $heavyArmor['name'] ?>" height="50"><br /><?php echo $heavyArmor['name'] ?></center></td>
		<td><?php echo $heavyArmor['description'] ?></td>
		<td><?php echo $heavyArmor['time'] ?></td>
		<td><?php echo number_format($heavyArmor['cost']) ?></td>
	</tr> 
	<?php }; if($range['set']) { ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><center><input name="data[Technology][tree]" id="TechnologyRange" value="range" type="radio"></center></td><?php  }?>
		<td><center><img src="<?php echo "/img/technologies/" .$range['img'].".png" ?>" alt="<?php echo $range['name'] ?>" height="50"><br /><?php echo $range['name'] ?></center></td>
		<td><?php echo $range['description'] ?></td>
		<td><?php echo $range['time'] ?></td>
		<td><?php echo number_format($range['cost']) ?></td>
	</tr> 
	<?php } ?>
	<tr>
		<?php if(!isset($currentResearch)){ ?><td><input type="submit" value="Research" /><?php // echo $form->end('Research', array('label' => false, 'div' => false)); ?></td><?php } ?>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table></form> <?php } else echo "You have researched everything there is to research. You are indeed a leader to be feared.";?> <br />