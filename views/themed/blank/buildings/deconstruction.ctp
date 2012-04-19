<div class="instructions"><h3>Instructions</h3>You may destroy your buildings here. They will be detroyed immediately.</div>
<div style="color:red"><b>You are deconstructing buildings on this page. To build buildings instead, <a href="/buildings">click here</a>.</b></div>
<h3>Deconstruction Manager</h3>
<b>Available building locations: </b><?php echo number_format($totalAvailable) ?><br />
<b>Buildings per castle: </b><?php echo number_format(round($totalBuildings / $user['Building']['castle'])) ?><br />
<form id="BuildingsBuildForm" method="post" action="/buildings/deconstruct">
	<table align="center">
		<tr><?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th colspan="7"><?php } else { ?><th colspan="5"><?php } ?><h3>Economic Buildings</h3></th></tr>
		<tr>
		  <th>Building</th>
		  <th>Owned</th>
		  <th>Queued</th>
		  <th>To destroy</th>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th>Desired Ratio</th><?php } ?>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th width="20px;"></th><?php } ?>
	  	</tr>
	  	<tr>
		    <td>Castle</td>
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
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td>House</td>
		    <td><?php echo number_format($user['Building']['house']) ?></td>
		    <td><?php echo number_format($user['Building']['housesBuilding']) ?></td>
		    <td><input name="data[Building][housesBuilding]" value="0" type="text" size="2" id="BuildingsHouse" /><?php //echo $form->input('housesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_house']) - $user['Building']['house'] - $user['Building']['housesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td>Farm</td>
		    <td><?php echo number_format($user['Building']['farm']) ?></td>
		    <td><?php echo number_format($user['Building']['farmsBuilding']) ?></td>
		    <td><input name="data[Building][farmsBuilding]" value="0" type="text" size="2" id="BuildingsFarm" /><?php //echo $form->input('farmsBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_farm']) - $user['Building']['farm'] - $user['Building']['farmsBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td>Mill</td>
		    <td><?php echo number_format($user['Building']['mill']) ?></td>
		    <td><?php echo number_format($user['Building']['millsBuilding']) ?></td>
		    <td><input name="data[Building][millsBuilding]" value="0" type="text" size="2" id="BuildingsMill" /><?php //echo $form->input('millsBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td> <?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_mill']) - $user['Building']['mill'] - $user['Building']['millsBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td>Mine</td>
		    <td><?php echo number_format($user['Building']['mine']) ?></td>
		    <td><?php echo number_format($user['Building']['minesBuilding']) ?></td>
		    <td><input name="data[Building][minesBuilding]" value="0" type="text" size="2" id="BuildingsMine" /><?php //echo $form->input('minesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_mine']) - $user['Building']['mine'] - $user['Building']['minesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td>Market</td>
		    <td><?php echo number_format($user['Building']['market']) ?></td>
		    <td><?php echo number_format($user['Building']['marketsBuilding']) ?></td>
		    <td><input name="data[Building][marketsBuilding]" value="0" type="text" size="2" id="BuildingsMarket" /><?php //echo $form->input('marketsBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td> <?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_market']) - $user['Building']['market'] - $user['Building']['marketsBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr><?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th colspan="7"><?php } else { ?><th colspan="5"><?php } ?><h3>Economic Buildings</h3></th></tr>
		<tr>
		  <th>Building</th>
		  <th>Owned</th>
		  <th>Queued</th>
		  <th>To destroy</th>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th>Desired Ratio</th><?php } ?>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th width="20px;"></th><?php } ?>
	  	</tr>
		<tr>
		    <td>Training Center</td>
		    <td><?php echo number_format($user['Building']['training_center']) ?></td>
		    <td><?php echo number_format($user['Building']['training_centersBuilding']) ?></td>
		    <td><input name="data[Building][training_centersBuilding]" value="0" type="text" size="2" id="BuildingsTrainingCenter333333" /><?php //echo $form->input('training_centersBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td> <?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_training_center']) - $user['Building']['training_center'] - $user['Building']['training_centersBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
			<td>Advanced Training Center</td>
		    <td><?php echo number_format($user['Building']['advanced_training_center']) ?></td>
		    <td><?php echo number_format($user['Building']['advanced_training_centersBuilding']) ?></td>
		    <td><input name="data[Building][advanced_training_centersBuilding]" value="0" type="text" size="2" id="BuildingsAdvancedTrainingCenters" /><?php //echo $form->input('advanced_training_centersBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
    		<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_advanced_training_center']) - $user['Building']['advanced_training_center'] - $user['Building']['advanced_training_centersBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
    	</tr>
    	<tr>
		    <td>School</td>
		    <td><?php echo number_format($user['Building']['school']) ?></td>
		    <td><?php echo number_format($user['Building']['schoolsBuilding']) ?></td>
		    <td><input name="data[Building][schoolsBuilding]" value="0" type="text" size="2" id="BuildingsSchools" /><?php //echo $form->input('schoolsBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_school']) - $user['Building']['school'] - $user['Building']['schoolsBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td>Guard House</td>
		    <td><?php echo number_format($user['Building']['guard_house']) ?></td>
		    <td><?php echo number_format($user['Building']['guard_housesBuilding']) ?></td>
		    <td><input name="data[Building][guard_housesBuilding]" value="0" type="text" size="2" id="BuildingsGuardHouses" /><?php //echo $form->input('guard_housesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_guard_house']) - $user['Building']['guard_house'] - $user['Building']['guard_housesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr>
		    <td>Forge</td>
		    <td><?php echo number_format($user['Building']['forge']) ?></td>
		    <td><?php echo number_format($user['Building']['forgesBuilding']) ?></td>
		    <td><input name="data[Building][forgesBuilding]" value="0" type="text" size="2" id="BuildingsForges" /><?php //echo $form->input('forgesBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_forge']) - $user['Building']['forge'] - $user['Building']['forgesBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<tr><?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th colspan="7"><?php } else { ?><th colspan="5"><?php } ?><h3>Economic Buildings</h3></th></tr>
		<tr>
		  <th>Building</th>
		  <th>Owned</th>
		  <th>Queued</th>
		  <th>To destroy</th>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th>Desired Ratio</th><?php } ?>
		  <?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?><th width="20px;"></th><?php } ?>
	  	</tr>
		
		<tr>
		    <td>Medical Center</td>
		    <td><?php echo number_format($user['Building']['medical_center']) ?></td>
		    <td><?php echo number_format($user['Building']['medical_centersBuilding']) ?></td>
		    <td><input name="data[Building][medical_centersBuilding]" value="0" type="text" size="2" id="BuildingsMedicalCenters" /><?php //echo $form->input('medical_centersBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_medical_center']) - $user['Building']['medical_center'] - $user['Building']['medical_centersBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		
		<tr>
		    <td>Guard Tower</td>
		    <td><?php echo number_format($user['Building']['guard_tower']) ?></td>
		    <td><?php echo number_format($user['Building']['guard_towersBuilding']) ?></td>
		    <td><input name="data[Building][guard_towersBuilding]" value="0" type="text" size="2" id="BuildingsGuardTowers" /><?php //echo $form->input('guard_towersBuilding', array('type' => 'text', 'size' => 2, 'label' => false, 'default' => 0)) ?></td>
		<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
			    <td><?php
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
				?>
				</td>
			<?php } ?>
			<?php if($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>
				<?php 
					$total = number_format(floor($user['User']['Resource']['land'] * $user['Building']['desired_guard_tower']) - $user['Building']['guard_tower'] - $user['Building']['guard_towersBuilding']);
					if($total > 0) {
				?>
					<td><span style="color:green;"><?php echo $total; ?></span>
				<?php } else if ($total < 0) { ?>
					<td><span style="color:red;"><?php echo $total; ?></span>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
			<?php } ?>
		</tr>
		
		<tr>
		    <td colspan="3"><div align="right"><a href="/buildings">Build Buildings</a></div></td>
		    <td><input type="submit" value="Destroy" /><?php //echo $form->end('Build') ?></td>
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
					<td colspan="2"><span style="color:green;"><?php echo $ratio; ?></span>
				<?php } else if ($ratio < -10) { ?>
					<td colspan="2"><span style="color:red;"><?php echo $ratio; ?></span>
				<?php } else { ?>
					<td colspan="2">&nbsp;</td>
				<?php } ?>
		    <?php } ?>
    	</tr>
	</table>
</form>
