<?php $form->options['url']['?'] = $simpleParms; ?>
<?php 
	$ajax->options['url']['?'] = $simpleParms; 

?>

<div id="result_spinner" style="display: none; clear:left; float:left;"><?php echo $html->image('/img/indicator.gif'); ?></div>
<div id="simulator_result" style="margin-left:5px; margin-top: 15px; text-align:left; clear:left;"></div>
<div id="attack_spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
<?php echo $form->create('User', array('url' => '/attacks/attackSimulatorResult', '?' => $simpleParms)); ?>

<div style="text-align:left; float:left;" id="AttackUnits">
<table>
	<tr>
		<th colspan="4"><div style="text-align:center;">Attacker</div></th>
	</tr>
	<tr valign="top">
		<th rowspan="2">Race:</th>
	</tr>
	<tr>
		<td>
			<?php $races = array('Human' => 'Human', 'Elf' => 'Elf', 'Dwarf' => 'Dwarf', 'Gnome' => 'Gnome', 'Orc' => 'Orc' ); ?>
			<?php echo $form->input('Attacking Race: ', array('options' => $races, 'id' => 'UserAttackingRace', 'div' => false, 'label' => false)) ?>
		</td>
		<td></td>
	</tr>
	<tr valign="top">
		<th rowspan="2">Score:</th>
		
	</tr valign="top">
	<tr>
		<td><input name="data[User][Attacking Score: ]" type="text" value="10000" size="5" id="UserAttackingScore:" /></td>
		<td></td>
	</tr>
	<tr valign="top">
		<th rowspan="2">BAS:</th>
		
	</tr>
	<tr>
		<td><input name="data[User][Army][bas]" type="text" value="100" size="2" id="UserBas" /> (85 to 125)</td>
		<td></td>
	</tr>
	<tr valign="top">
		<th rowspan="2">Luck:</th>
		
	</tr>
	<tr>
		<td><input name="data[User][luck]" type="text" value="0" size="2" id="UserLuck" /> (0 to 10)</td>
		<td></td>
	</tr>
	<tr valign="top"> <!-- start strategy -->
		<th rowspan="2"><label for="AttackStrategy">Strategy: </label></th>
		
	</tr>
	<tr>
		<td>
			<select name="data[User][Army][strategy]" class="dropDown" id="AttackStrategy">
				<option value="None">None</option>
				<option value="Archer Protection">Archer Protection</option>
				<option value="Reckless Abandon">Reckless Abandon</option>
				<option value="Quick Retreat">Quick Retreat</option>
				<option value="Anti-Cavalry">Anti-Cavalry</option>
				<option value="Charging">Charging</option>
			</select>
		</td>
		<td></td>
	</tr> <!-- end strategy -->
	<tr valign="top"> <!-- start buildings -->
		<th rowspan="3">Buildings:</th>
	</tr>
	<tr>
		<td>Land:</td>
		<td><input name="data[User][Resource][land]" type="text" value="10" size="2" id="UserLand" /></td>
	</tr>
	<tr>
		<td>Medical Centers:</td>
		<td><input name="data[User][Building][medical_center]" type="text" value="0" size="2" id="UserMedicalCenter" /></td>
	</tr> <!-- end buildings -->
	<tr valign="top"> <!-- start techs -->
		<th rowspan="7">Techs:</th>
	</tr>
	<tr>
		<td>Charisma:</td>
		<td><input type="hidden" name="data[User][Technology][racial]" id="AttackRacial" value="0" /><input type="checkbox" name="data[User][Technology][racial]" class="checkbox" value="1" id="AttackRacial" /></td>
	</tr>
	<tr>
		<td>Improved Medicine:</td>
		<td><input type="hidden" name="data[User][Technology][tree_1]" id="AttackMedCenters" value="0" /><input type="checkbox" name="data[User][Technology][tree_1]" class="checkbox" value="4" id="AttackMedCenters" /></td>
	</tr>
	<tr>
		<td>Range:</td>
		<td>
			<select name="data[User][Technology][range]" class="dropDown" id="AttackRange">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Blade:</td>
		<td>
			<select name="data[User][Technology][blade]" class="dropDown" id="AttackBlade">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Light Armor:</td>
		<td>
			<select name="data[User][Technology][light_armor]" class="dropDown" id="AttackLightArmor">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Heavy Armor:</td>
		<td>
			<select name="data[User][Technology][heavy_armor]" class="dropDown" id="AttackHeavyArmor">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr>
	</tr> <!-- end techs -->
	<tr valign="top"> <!-- start units -->
		<th rowspan="7">Units:</th>
	</tr>
	<tr>
		<td><label for="AttackArmyWeak">Pikeman </label></td>
		<td><input type="text" name="data[attack][Attack][weak]" value="" size="4" id="AttackArmyWeak" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmyStandard">Infantry </label></td>
		<td><input type="text" name="data[attack][Attack][standard]" value="" size="4" id="AttackArmyStandard" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmyRange">Archer </label></td>
		<td><input type="text" name="data[attack][Attack][range]" value="" size="4" id="AttackArmyRange" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmyMount">Mounted Archer </label></td>
		<td><input type="text" name="data[attack][Attack][mount]" value="" size="4" id="AttackArmyMount" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmySmallElite">Heavy Infantry </label></td>
		<td><input type="text" name="data[attack][Attack][small_elite]" value="" size="4" id="AttackArmySmallElite" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmyBigElite">Knight </label></td>
		<td><input type="text" name="data[attack][Attack][big_elite]" value="" size="4" id="AttackArmyBigElite" /></td>
	</tr><!-- end units -->
</table>
</div>

<div style="text-align:left; float:left; margin-left: 30px;" id="DefendUnits">
<div id="defend_spinner" style="display: none;"><?php echo $html->image('/img/indicator.gif'); ?></div>
<table>
	<tr>
		<th colspan="4"><div style="text-align:center;">Defender</div></th>
	</tr>
	<tr valign="top">
		<th rowspan="2">Race:</th>
	</tr>
	<tr>
		<td>
			<?php $races = array('Human' => 'Human', 'Elf' => 'Elf', 'Dwarf' => 'Dwarf', 'Gnome' => 'Gnome', 'Orc' => 'Orc' ); ?>
			<?php echo $form->input('Defending Race: ', array('options' => $races, 'id' => 'UserDefendingRace', 'div' => false, 'label' => false)) ?>
		</td>
		<td></td>
	</tr>
	<tr valign="top">
		<th rowspan="2">Score:</th>
		
	</tr valign="top">
	<tr>
		<td><input name="data[User][Defending Score: ]" type="text" value="10000" size="5" id="DefendingScore:" /></td>
		<td></td>
	</tr>
	<tr valign="top">
		<th rowspan="2">BAS:</th>
		
	</tr>
	<tr>
		<td><input name="data[defend][Army][bas]" type="text" value="100" size="2" id="DefendBas" /> (85 to 125)</td>
		<td></td>
	</tr>
	<tr valign="top">
		<th rowspan="2">Luck:</th>
		
	</tr>
	<tr>
		<td><input name="data[defend][luck]" type="text" value="0" size="2" id="DefendLuck" /> (0 to 10)</td>
		<td></td>
	</tr>
	<tr valign="top"> <!-- start strategy -->
		<th rowspan="2"><label for="AttackStrategy">Strategy: </label></th>
		
	</tr>
	<tr>
		<td>
			<select name="data[defend][Army][strategy]" class="dropDown" id="DefendStrategy">
				<option value="None">None</option>
				<option value="Archer Protection">Archer Protection</option>
				<option value="Reckless Abandon">Reckless Abandon</option>
				<option value="Quick Retreat">Quick Retreat</option>
				<option value="Anti-Cavalry">Anti-Cavalry</option>
				<option value="Charging">Charging</option>
			</select>
		</td>
		<td></td>
	</tr> <!-- end strategy -->
	<tr valign="top"><!-- start spells -->
		<th rowspan="2">Spells:</th>
	</tr>
	<tr>
		<td>Firewall:</td>
		<td><input type="hidden" name="data[defend][Spell][firewall]" id="DefendFirewall" value="0" /><input type="checkbox" name="data[defend][Spell][firewall]" class="checkbox" value="7" id="DefendFirewall" /></td>
	</tr> <!--  end spells -->
	<tr valign="top"> <!-- start buildings -->
		<th rowspan="5">Buildings:</th>
	</tr>
	<tr>
		<td>Castles:</td>
		<td><input name="data[defend][Building][castle]" type="text" value="1" size="2" id="DefendCastle" /></td>
	</tr>
	<tr>
		<td>Land:</td>
		<td><input name="data[defend][Resource][land]" type="text" value="10" size="2" id="DefendLand" /></td>
	</tr>
	<tr>
		<td>Guard Towers:</td>
		<td><input name="data[defend][Building][guard_tower]" type="text" value="0" size="2" id="DefendGuardTower" /></td>
	</tr>
	<tr>
		<td>Medical Centers:</td>
		<td><input name="data[defend][Building][medical_center]" type="text" value="0" size="2" id="DefendMedicalCenter" /></td>
	</tr> <!-- end buildings -->
	<tr valign="top"> <!-- start techs -->
		<th rowspan="8">Techs:</th>
	</tr>
	<tr>
		<td>Charisma:</td>
		<td><input type="hidden" name="data[defend][Technology][racial]" id="DefendRacial" value="0" /><input type="checkbox" name="data[defend][Technology][racial]" class="checkbox" value="1" id="DefendRacialBox" /></td>
	</tr>
	<tr>
		<td>Improved Medicine:</td>
		<td><input type="hidden" name="data[defend][Technology][tree_1]" id="DefendMedCenters" value="0" /><input type="checkbox" name="data[defend][Technology][tree_1]" class="checkbox" value="4" id="DefendMedCenters" /></td>
	</tr>
	<tr>
		<td>Fortification:</td>
		<td><input type="hidden" name="data[defend][Technology][tree_2]" id="DefendFortification" value="0" /><input type="checkbox" name="data[defend][Technology][tree_2]" class="checkbox" value="7" id="DefendFortification" /></td>
	</tr>
	<tr>
		<td>Range:</td>
		<td>
			<select name="data[defend][Technology][range]" class="dropDown" id="DefendRange">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Blade:</td>
		<td>
			<select name="data[defend][Technology][blade]" class="dropDown" id="DefendBlade">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Light Armor:</td>
		<td>
			<select name="data[defend][Technology][light_armor]" class="dropDown" id="DefendLightArmor">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Heavy Armor:</td>
		<td>
			<select name="data[defend][Technology][heavy_armor]" class="dropDown" id="DefendHeavyArmor">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr> <!-- end techs -->
	<tr valign="top"> <!-- start units -->
		<th rowspan="7">Units:</th>
	</tr>
	<tr>
		<td><label for="DefendArmyWeak">Pikeman </label></td>
		<td><input type="text" name="data[defend][Army][weak]" value="" size="4" id="DefendArmyWeak" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyStandard">Infantry </label></td>
		<td><input type="text" name="data[defend][Army][standard]" value="" size="4" id="DefendArmyStandard" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyRange">Archer </label></td>
		<td><input type="text" name="data[defend][Army][range]" value="" size="4" id="DefendArmyRange" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyMount">Mounted Archer </label></td>
		<td><input type="text" name="data[defend][Army][mount]" value="" size="4" id="DefendArmyMount" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmySmallElite">Heavy Infantry </label></td>
		<td><input type="text" name="data[defend][Army][small_elite]" value="" size="4" id="DefendArmySmallElite" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyBigElite">Knight </label></td>
		<td><input type="text" name="data[defend][Army][big_elite]" value="" size="4" id="DefendArmyBigElite" /></td>
	</tr><!-- end units -->
</table>
</div>
	 
	 
	 
	 
	 
	 
	 


<div style="clear:left; float:left; margin-bottom: 50px; margin-top:15px;">
<?php 
echo $ajax->submit('Simulate', array('url'=> array('controller'=>'attacks', 'action'=>'attackSimulatorResult'), 'update' => 'simulator_result', 'indicator' => 'result_spinner'));
echo $form->end();
?>
</div>	 
<?php 
echo $ajax->observeField('UserAttackingRace', 
    array(
        'url' => array('controller'=>'attacks', 'action' => 'attackRaces'),
     	'update' => 'AttackUnits',
    	'indicator' => 'attack_spinner'
    )
); 
?>
<?php 
echo $ajax->observeField('UserDefendingRace', 
    array(
        'url' => array('controller'=>'attacks', 'action' => 'defendRaces'),
		'update' => 'DefendUnits',
    	'indicator' => 'defend_spinner' 
    )
); 
?><br />
