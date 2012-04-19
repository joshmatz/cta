<div class="instructions"><h3>Instructions</h3>"Per Building" column specifies amount of workers working in each building. Total specifies total peasants working in that category. 
Use either/or both columns, it will automatically calculate the other. The "Per Building" column for builders represents how many buildings they can build. 
Explorers and Training have no specific buildings, so the numbers are the same.</div>
<div id="spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
<div id="population">
<form id="PopulationChange" method="post" action="/populations/change">
<?php //echo $form->create('Population', array('action' => 'change/' . $parms)); ?>
<table>
    <tr>
		<th>Workers</th>
		<th>Per Building</th>
		<th>Total</th>
		<th>Per Hour*</th>
	</tr>    
	<tr>
 	    <td><div class="tips"><span class="tip" title="Lumberers produce wood and have an optimal level of 85 per building.">Lumberers</span></div></td>
   	 	<td><?php 
    			if($buildings['Building']['mill'] != 0) { ?>
    			<input name="data[Population][lumbersPer]" type="text"  value="<?php echo round($population['Population']['lumbers'] / $buildings['Building']['mill']) ?>" class="textBox" />
    			<?php } else { ?>
    			<input name="data[Population][lumbersPer]" type="text"  value="0" class="textBox" />
    		<?php } ?>
    	</td>
    	<td><input name="data[Population][lumbersTotal]" type="text"  value="<?php echo $population['Population']['lumbers']?>" class="textBox" /></td>
    	<td><?php echo number_format($updatedUser['Resource']['wood'] - $user['User']['Resource']['wood']) . " wood" ?></td>
 	</tr>
	<tr>
 	    <td><div class="tips"><span class="tip" title="Miners produce iron and have an optimal level of 100 per building. This can be reduced to 85 with the Machinery technology in the third technology tree.">Miners</span></div></td>
   	 	<td><?php 
    			if($buildings['Building']['mine'] != 0) { ?>
    			<input name="data[Population][minersPer]" type="text"  value="<?php echo round($population['Population']['miners'] / $buildings['Building']['mine']) ?>" class="textBox" />
    			<?php } else { ?>
    			<input name="data[Population][minersPer]" type="text"  value="0" class="textBox" />
    		<?php } ?>
    	</td>
    	<td><input name="data[Population][minersTotal]" type="text"  value="<?php echo $population['Population']['miners']?>" class="textBox" /></td>
    	<td><?php echo number_format($updatedUser['Resource']['iron'] - $user['User']['Resource']['iron']) . " iron" ?></td>
 	</tr>
  	<tr>
    	<td><div class="tips"><span class="tip" title="Farmers produce food which is vital to sustaining your army and population. They function optimally at 60 per building.">Farmers</span></div></td>
   	 	<td><?php 
    			if($buildings['Building']['farm'] != 0) { ?>
    			<input name="data[Population][farmersPer]" type="text"  value="<?php echo round($population['Population']['agriculture'] / $buildings['Building']['farm']) ?>" class="textBox" />
    			<?php } else { ?>
    			<input name="data[Population][farmersPer]" type="text"  value="0" class="textBox" />
    		<?php } ?>
    	</td>
    	<td><input name="data[Population][farmersTotal]" type="text"  value="<?php echo $population['Population']['agriculture']?>" class="textBox" /></td>
  		<td><?php echo number_format($updatedUser['Resource']['food'] - $user['User']['Resource']['food']) . " food" ?></td>
  	</tr>
  	<tr>
    	<td><div class="tips"><span class="tip" title="Blacksmiths produce weapons that are needed in order to train an army. Weapons are produced optimally at 80 per building. Weapon speed can be increased with the Blacksmithing technology in the second technology tree.">Blacksmiths</span></div></td>
   	 	<td><?php 
    			if($buildings['Building']['forge'] != 0) { ?>
    			<input name="data[Population][blacksmithsPer]" type="text"  value="<?php echo round($population['Population']['blacksmiths'] / $buildings['Building']['forge']) ?>" class="textBox" />
    			<?php } else { ?>
    			<input name="data[Population][blacksmithsPer]" type="text"  value="0" class="textBox" />
    		<?php } ?>
    	</td>
    	<td><input name="data[Population][blacksmithsTotal]" type="text"  value="<?php echo $population['Population']['blacksmiths']?>" class="textBox" /></td>
		<td><?php echo number_format($weaponsProduced) . " weapons" ?></td>
  	</tr>
  	<tr>
    	<td>
    		<div class="tips">
    			<span class="tip" title="In order to build buildings your population has to construct them. 
    				<?php if($user['User']['race'] != "Orc" || $user['User']['race'] != "Gnome") {?>It takes 150 peasants to build 1 building. <?php } else { ?>It takes 110 peasants to build 1 building. <?php } ?> This number can be cut in half with the Carpentry technology in the second tree.">Builders</span></div></td>
    	<td><input name="data[Population][buildersPer]" type="text"  value="<?php echo floor($population['Population']['building'] / $perBuilding) ?>" class="textBox" /></td>
    	<td><input name="data[Population][buildersTotal]" type="text"  value="<?php echo $population['Population']['building'] ?>" class="textBox" />
  		<td><?php echo number_format(floor($population['Population']['building'] / $perBuilding) + $buildings['Building']['castle']) . " buildings**"?></td>
  	</tr>
  	<tr>
   		<td><div class="tips"><span class="tip" title="In order to train units for your army, you must have peasants in the training category.">Training</span></div></td>
    	<td><input name="data[Population][trainingPer]" type="text"  value="<?php echo $population['Population']['training'] ?>" class="textBox" /></td>
    	<td><input name="data[Population][trainingTotal]" type="text"  value="<?php echo $population['Population']['training'] ?>" class="textBox" />
  		<td><?php echo number_format($availableHousing); ?> housing</td>
  	</tr>
  	
  	<tr>
  		<td><div class="tips"><span class="tip" title="One way to gain land is by exploring. Exploring works based on the percent of population you have exploring.">Explorers</span></div></td>
    	<td><input name="data[Population][exploringPer]" type="text"  value="<?php echo $population['Population']['explorers'] ?>" class="textBox" /></td>
    	<td><input name="data[Population][exploringTotal]" type="text"  value="<?php echo $population['Population']['explorers'] ?>" class="textBox" />
 		<td><?php echo number_format($updatedUser['Resource']['land'] - $user['User']['Resource']['land']) . " land***" ?></td>
  	</tr>
  	<tr>
    	<td colspan="4"><center><?php echo "Total: ". number_format($population['Population']['total_population']); 
    	echo $this->Js->submit('Submit', array('url'=> array('controller'=>'populations', 'action'=>'change'), 'update' => '#population', 'before' => $this->Js->get('#spinner')->effect('fadeIn', array('buffer' => false)) . $this->Js->get('.tooltip')->effect('fadeOut', array('buffer' => false)), 'complete' => $this->Js->get('#spinner')->effect('fadeOut', array('buffer' => false)))); ?><center></td>
 	</tr>
 	<tr><td colspan="4">* Not including unit upkeep.<br />** Including Castle build rate <br />*** Estimated</td></tr>
</table>
</form>

</div>
