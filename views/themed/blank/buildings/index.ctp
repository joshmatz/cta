<div class="instructions"><h3>Instructions</h3>Queue your buildings here. <a href="/populations/">Set population</a> to construct them. </div>
<h3>Buildings Manager</h3>
<?php if($castleTime > 0) { ?><b>Castle will be completed in:</b> <?php echo $plural->ize('hour', $castleTime); ?><br /> <?php }?>
<b>Available building locations: </b><?php echo number_format($totalAvailable) ?><br />
<b>Buildings constructed per hour: </b><?php echo $buildingsBuilt ?><br />
<b>Buildings per castle: </b><?php echo number_format(round($totalBuildings / $user['Building']['castle'])) ?><br />
<form id="BuildingsBuildForm" method="post" action="/buildings/build"><?php //echo $form->create('Building', array('action' => 'build')); ?>
	<table align="center">
		<tr><?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th colspan="7"><?php } else { ?><th colspan="5"><?php } ?><h3>Economic Buildings</h3></th></tr>
		<tr>
		  <th>Building</th>
		  <th>Cost</th>
		  <th>Owned</th>
		  <th>Queued</th>
		  <th>To build</th>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th>Desired Ratio</th><?php } ?>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th width="20px;"></th><?php } ?>
	  	</tr>
	  	<tr>
		    <td><div class="tips"><span class="tip" title="Castles are very important buildings. Castles increase your resource production, unit and building production (1 per castle), and your maximum population. Castles have a base defense that increases your defending strength. However, too many castles will divide your army, not allowing them to defend properly. Castles will also increase your minimum crime rate by 1%.">Castle</span></div></td>  
		    <td><?php echo number_format($wood[4]) ." wood, " . number_format($iron[4]) . " iron, " . number_format($gold[4]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['castle']) ?></td>
		    <td><?php echo number_format($user['Building']['castlesBuilding']) ?></td>
		    <td><input name="data[Building][castlesBuilding]" value="0" type="text" size="2" id="BuildingsCastle" /><?php //echo $form->input('castlesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
				<?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_castle',
					$user['Building']['desired_castle'],
					array
					(
					'value'         => $user['Building']['desired_castle'],
					'actionName'    => 'buildings/updateRatio/castle',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?>
				
				</td>
			<?php } ?>
		  	<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_castle']) - $user['Building']['castle'] - $user['Building']['castlesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td><div class="tips"><span class="tip" title="Houses house your population. 100 peasants have the ability to sleep in one house and this number can be upgraded by 15 with the Habitation technology in the second technology tree.">House</span></div></td>
		    <td><?php echo number_format($wood[3]) ." wood, " . number_format($iron[3]) . " iron, " . number_format($gold[3]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['house']) ?></td>
		    <td><?php echo number_format($user['Building']['housesBuilding']) ?></td>
		    <td><input name="data[Building][housesBuilding]" value="0" type="text" size="2" id="BuildingsHouse" /><?php //echo $form->input('housesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_house',
					$user['Building']['desired_house'],
					array
					(
					'value'         => $user['Building']['desired_house'],
					'actionName'    => 'buildings/updateRatio/house',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_house']) - $user['Building']['house'] - $user['Building']['housesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td><div class="tips"><span class="tip" title="Farms produce 150 (165 for Orcs) food per hour at the optimal level, which is 60 workers per mill. Peasants and your army require food to stay alive. Your army requires double the food when on attack.">Farms</span></div></td>
		    <td><?php echo number_format($wood[0]) ." wood, " . number_format($iron[0]) . " iron, " . number_format($gold[0]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['farm']) ?></td>
		    <td><?php echo number_format($user['Building']['farmsBuilding']) ?></td>
		    <td><input name="data[Building][farmsBuilding]" value="0" type="text" size="2" id="BuildingsFarm" /><?php //echo $form->input('farmsBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			     <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_farm',
					$user['Building']['desired_farm'],
					array
					(
					'value'         => $user['Building']['desired_farm'],
					'actionName'    => 'buildings/updateRatio/farm',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_farm']) - $user['Building']['farm'] - $user['Building']['farmsBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td><div class="tips"><span class="tip" title="Mills produce wood at a rate of 5 (8 for Elves, 7 for Orcs) per mill at optimal level.">Mill</span></div></td>
		    <td><?php echo number_format($wood[1]) ." wood, " . number_format($iron[1]) . " iron, " . number_format($gold[1]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['mill']) ?></td>
		    <td><?php echo number_format($user['Building']['millsBuilding']) ?></td>
		    <td><input name="data[Building][millsBuilding]" value="0" type="text" size="2" id="BuildingsMill" /><?php //echo $form->input('millsBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_mill',
					$user['Building']['desired_mill'],
					array
					(
					'value'         => $user['Building']['desired_mill'],
					'actionName'    => 'buildings/updateRatio/mill',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_mill']) - $user['Building']['mill'] - $user['Building']['millsBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td><div class="tips"><span class="tip" title="Mines produce 4 (7 for Dwarves, 6 for Orcs and 6 for Humans) iron per hour at the optimal level, which is 100 workers per mine.">Mine</span></div></td>
		    <td><?php echo number_format($wood[2]) ." wood, " . number_format($iron[2]) . " iron, " . number_format($gold[2]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['mine']) ?></td>
		    <td><?php echo number_format($user['Building']['minesBuilding']) ?></td>
		    <td><input name="data[Building][minesBuilding]" value="0" type="text" size="2" id="BuildingsMine" /><?php //echo $form->input('minesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			     <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_mine',
					$user['Building']['desired_mine'],
					array
					(
					'value'         => $user['Building']['desired_mine'],
					'actionName'    => 'buildings/updateRatio/mine',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_mine']) - $user['Building']['mine'] - $user['Building']['minesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td><div class="tips"><span class="tip" title="Markets provide gold for your kingdom at a variable rate (factoring in population, land and total markets). Benefits from adding markets to your kingdom will stop at 1 market per 1 land. Your army will require you to pay them gold, double when they're on attack.">Market</span></div></td>
		    <td><?php echo number_format($wood[6]) ." wood, " . number_format($iron[6]) . " iron, " . number_format($gold[6]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['market']) ?></td>
		    <td><?php echo number_format($user['Building']['marketsBuilding']) ?></td>
		    <td><input name="data[Building][marketsBuilding]" value="0" type="text" size="2" id="BuildingsMarket" /><?php //echo $form->input('marketsBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_market',
					$user['Building']['desired_market'],
					array
					(
					'value'         => $user['Building']['desired_market'],
					'actionName'    => 'buildings/updateRatio/market',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_market']) - $user['Building']['market'] - $user['Building']['marketsBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr><?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th colspan="7"><?php } else { ?><th colspan="5"><?php } ?><h3>Military Buildings</h3></th></tr>
		<tr>
		  <th>Building</th>
		  <th>Cost</th>
		  <th>Owned</th>
		  <th>Constructing</th>
		  <th>To build</th>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th>Desired Ratio</th><?php } ?>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th width="20px;"></th><?php } ?>
	  	</tr>
		<tr>
		    <td><div class="tips"><span class="tip" title="Training centers train your weak units. They may be weak, but they're cheaper and train quicker.<br />
<strong>Dwarf:</strong> 1 Hammer Wielder per 4 centers, 1 Axeman per 5 centers, 1 Light Crossbowman per 3 centers<br />
<strong>Elf:</strong> 1 Swordman per 2 centers, 1 Lanceman 1 per 3 centers, 1 Archer 1 per 1 center<br />
<strong>Gnome:</strong> 2 Infantry per 1 center, 7 Militias per 1 center, 7 Rock Throwers per 1 center<br />
<strong>Human:</strong> 1 Infantry per 2 centers, 1 Pikeman per 3 centers, 1 Archer per 3 centers<br />
<strong>Orc:</strong> 1 Rusher per 1 center, 2 Slothers per 1 center, 2 Slinger per 1 center<br />">Training Center</span></div></td>
		    <td><?php echo number_format($wood[11]) ." wood, " . number_format($iron[11]) . " iron, " . number_format($gold[11]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['training_center']) ?></td>
		    <td><?php echo number_format($user['Building']['training_centersBuilding']) ?></td>
		    <td><input name="data[Building][training_centersBuilding]" value="0" type="text" size="2" id="BuildingsTrainingCenter333333" /><?php //echo $form->input('training_centersBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_training_center',
					$user['Building']['desired_training_center'],
					array
					(
					'value'         => $user['Building']['desired_training_center'],
					'actionName'    => 'buildings/updateRatio/training_center',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_training_center']) - $user['Building']['training_center'] - $user['Building']['training_centersBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
			<td><div class="tips"><span class="tip" title="Advanced Training Centers train your stronger units. They may be strong, but they're more expensive and train slower. <br />
<strong>Dwarf:</strong> 1 Shieldbearer per 4 centers, 1 Runner per 3 centers, 1 Berserker per 5 centers<br />
<strong>Elf:</strong>	1 Mage per 4 centers, 1 Courageous per 4 centers, 1 Elite Archers per 4 centers<br />
<strong>Gnome:</strong> 1 Catapult per 7 centers, 1 Rider per 3 centers, 1 Ballista per 5 centers<br />
<strong>Human:</strong> 1 Knight per 5 centers, 1 Heavy Infantry per 4 centers, 1 Mounted Archers per 4 centers<br />
<strong>Orc:</strong> 1 Shadow Warrior per 4 centers, 1 Wolf Master per 3 centers, 1 Axe thrower per 3 centers<br />">Advanced Training Center</span></div></td>
		    <td><?php echo number_format($wood[12]) ." wood, " . number_format($iron[12]) . " iron, " . number_format($gold[12]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['advanced_training_center']) ?></td>
		    <td><?php echo number_format($user['Building']['advanced_training_centersBuilding']) ?></td>
		    <td><input name="data[Building][advanced_training_centersBuilding]" value="0" type="text" size="2" id="BuildingsAdvancedTrainingCenters" /><?php //echo $form->input('advanced_training_centersBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
    		<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_advanced_training_center',
					$user['Building']['desired_advanced_training_center'],
					array
					(
					'value'         => $user['Building']['desired_advanced_training_center'],
					'actionName'    => 'buildings/updateRatio/advanced_training_center',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_advanced_training_center']) - $user['Building']['advanced_training_center'] - $user['Building']['advanced_training_centersBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
    	</tr>
    	<tr>
		    <td><div class="tips"><span class="tip" title="Schools are used to train and provide housing for wizards. They also help wizards when casting spells. Five schools can train one wizard per hour. One school provides 40 housing (60 for Gnomes).">School</span></div></td>
		    <td><?php echo number_format($wood[7]) ." wood, " . number_format($iron[7]) . " iron, " . number_format($gold[7]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['school']) ?></td>
		    <td><?php echo number_format($user['Building']['schoolsBuilding']) ?></td>
		    <td><input name="data[Building][schoolsBuilding]" value="0" type="text" size="2" id="BuildingsSchools" /><?php //echo $form->input('schoolsBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_school',
					$user['Building']['desired_school'],
					array
					(
					'value'         => $user['Building']['desired_school'],
					'actionName'    => 'buildings/updateRatio/school',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_school']) - $user['Building']['school'] - $user['Building']['schoolsBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td><div class="tips"><span class="tip" title="Guard houses provide a place to stay for your army. They can house 40 soldiers per house, 60 for gnomes, and can be increased by 25 with the Barracks technology.">Guard House</span></div></td>
		    <td><?php echo number_format($wood[9]) ." wood, " . number_format($iron[9]) . " iron, " . number_format($gold[9]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['guard_house']) ?></td>
		    <td><?php echo number_format($user['Building']['guard_housesBuilding']) ?></td>
		    <td><input name="data[Building][guard_housesBuilding]" value="0" type="text" size="2" id="BuildingsGuardHouses" /><?php //echo $form->input('guard_housesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_guard_house',
					$user['Building']['desired_guard_house'],
					array
					(
					'value'         => $user['Building']['desired_guard_house'],
					'actionName'    => 'buildings/updateRatio/guard_house',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_guard_house']) - $user['Building']['guard_house'] - $user['Building']['guard_housesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td><div class="tips"><span class="tip" title="Forges are what you need to build your weapons. The optimal level for forges is 80.">Forge</span></div></td>
		    <td><?php echo number_format($wood[5]) ." wood, " . number_format($iron[5]) . " iron, " . number_format($gold[5]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['forge']) ?></td>
		    <td><?php echo number_format($user['Building']['forgesBuilding']) ?></td>
		    <td><input name="data[Building][forgesBuilding]" value="0" type="text" size="2" id="BuildingsForges" /><?php //echo $form->input('forgesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			   <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_forge',
					$user['Building']['desired_forge'],
					array
					(
					'value'         => $user['Building']['desired_forge'],
					'actionName'    => 'buildings/updateRatio/forge',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_forge']) - $user['Building']['forge'] - $user['Building']['forgesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr><?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th colspan="7"><?php } else { ?><th colspan="5"><?php } ?><h3>Defensive Buildings</h3></th></tr>
		<tr>
		  <th>Building</th>
		  <th>Cost</th>
		  <th>Owned</th>
		  <th>Constructing</th>
		  <th>To build</th>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th>Desired Ratio</th><?php } ?>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th width="20px;"></th><?php } ?>
	  	</tr>
		
		<tr>
		    <td><div class="tips"><span class="tip" title="Medical centers keep your defensive combat losses to a minimum by healing up to 25% (15% on attack) of your army at the end of a fight. They receive +10% healing from the Improved Medicine technology in the first tree.">Medical Center</span></div></td>
		    <td><?php echo number_format($wood[8]) ." wood, " . number_format($iron[8]) . " iron, " . number_format($gold[8]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['medical_center']) ?></td>
		    <td><?php echo number_format($user['Building']['medical_centersBuilding']) ?></td>
		    <td><input name="data[Building][medical_centersBuilding]" value="0" type="text" size="2" id="BuildingsMedicalCenters" /><?php //echo $form->input('medical_centersBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_medical_center',
					$user['Building']['desired_medical_center'],
					array
					(
					'value'         => $user['Building']['desired_medical_center'],
					'actionName'    => 'buildings/updateRatio/medical_center',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_medical_center']) - $user['Building']['medical_center'] - $user['Building']['medical_centersBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		
		<tr>
		    <td><div class="tips"><span class="tip" title="Guard Towers provide defense for your kingdom and help your units defend better by absorbing 20 range attack each.">Guard Tower</span></div></td>
		    <td><?php echo number_format($wood[10]) ." wood, " . number_format($iron[10]) . " iron, " . number_format($gold[10]) . " gold" ?></td>
		    
		    <td><?php echo number_format($user['Building']['guard_tower']) ?></td>
		    <td><?php echo number_format($user['Building']['guard_towersBuilding']) ?></td>
		    <td><input name="data[Building][guard_towersBuilding]" value="0" type="text" size="2" id="BuildingsGuardTowers" /><?php //echo $form->input('guard_towersBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td>
			    <?php
					echo $inplaceUpdater->input
					(
					'Building',
					'desired_guard_tower',
					$user['Building']['desired_guard_tower'],
					array
					(
					'value'         => $user['Building']['desired_guard_tower'],
					'actionName'    => 'buildings/updateRatio/guard_tower',
					'type'          => 'text',
					'cancelText'    => 'Cancel',
					'submitText'    => 'Save',
					'toolTip'       => 'Click to edit desired ratio',
					'containerType' => 'div',
					),
					true
					);
				?></td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_guard_tower']) - $user['Building']['guard_tower'] - $user['Building']['guard_towersBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span></td>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		
		<tr>
		    <td colspan="4"><div align="right"><a href="/buildings/deconstruction">Destroy Buildings</a></div></td>
		    <td><input type="submit" value="Build" /><?php //echo $form->end('Build') ?></td>
		    <?php 
		    if($user['User']['premium'] || $account['Account']['role'] == 'admin') { 
		    	$ratio = settype($user['Building']['desired_house'], "float") - 
		    		settype($user['Building']['desired_farm'], "float") - settype($user['Building']['desired_mill'], "float") - 
		    		settype($user['Building']['desired_mine'], "float") - settype($user['Building']['desired_market'], "float") - 
		    		settype($user['Building']['desired_training_center'], "float") - 
		    		settype($user['Building']['desired_advanced_training_center'], "float") - 
		    		settype($user['Building']['desired_forge'], "float") - settype($user['Building']['desired_school'], "float") - 
		    		settype($user['Building']['desired_guard_house'], "float") - settype($user['Building']['desired_guard_tower'], "float") - 
		    		settype($user['Building']['desired_medical_center'], "float");
		    	if($ratio > -10) {
				?>
					<td><span style="color:green;"><?php echo $ratio; ?></span></td><td>&nbsp;</td>
				<?php } else if ($ratio < -10) { ?>
					<td><span style="color:red;"><?php echo $ratio; ?></span></td><td>&nbsp;</td>
				<?php } else { ?>
					<td colspan="2">&nbsp;</td>
				<?php } ?>
		    <?php } ?>
    	</tr>
	</table>
</form>