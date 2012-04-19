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
			<select name="data[User][Defending Race: ]" id="UserDefendingRace">
				<option value="Human" <?php if($race == "Human") echo 'selected'?>>Human</option>
				<option value="Elf" <?php if($race == "Elf") echo 'selected'?>>Elf</option>
				<option value="Dwarf" <?php if($race == "Dwarf") echo 'selected'?>>Dwarf</option>
				<option value="Gnome" <?php if($race == "Gnome") echo 'selected'?>>Gnome</option>
				<option value="Orc" <?php if($race == "Orc") echo 'selected'?>>Orc</option>
			</select>
		</td>
		<td></td>
	</tr>
	<tr valign="top">
		<th rowspan="2">Score:</th>
		
	</tr>
	<tr>
		<td><input name="data[User][Defending Score: ]" type="text" value="" size="5" id="UserDefendingScore:" /></td>
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
		<th rowspan="2"><label for="DefendStrategy">Strategy: </label></th>
		
	</tr> 
	<tr>
		<td>
			<select name="data[defend][Army][strategy]" class="dropDown" id="DefendStrategy">
				<option value="None">None</option>
				<option value="Archer Protection">Archer Protection</option>
				<option value="Reckless Abandon">Reckless Abandon</option>
				<option value="Quick Retreat">Quick Retreat</option>
				<?php if($race != "Dwarf") { ?><option value="Anti-Cavalry">Anti-Cavalry</option><?php } ?>
				<?php if($race == "Human") { ?><option value="Charging">Charging</option><?php } ?>
				<?php if($race == "Elf") { ?><option value="Mystics">Mystics</option><?php } ?>
				<?php if($race == "Orc") { ?><option value="Horde">For the Horde</option><?php } ?>
				<?php if($race == "Dwarf") { ?><option value="Shield Line">Shield Line</option><?php } ?>
				<?php if($race == "Gnome") { ?><option value="Far Fighting">Far Fighting</option><?php } ?>
			</select>
		</td>
		<td></td>
	</tr><!-- end strategy -->
	
		<tr valign="top"><!-- start spells -->
			<th rowspan="<?php if($race == "Elf") { ?>5<?php } else {?>2<?php }?>">Spells:</th>
		</tr>
		<tr>
			<td>Firewall:</td>
			<td><input type="hidden" name="data[defend][Spell][firewall]" id="DefendFirewall" value="0" /><input type="checkbox" name="data[defend][Spell][firewall]" class="checkbox" value="7" id="DefendFirewall" /></td>
		</tr>
	<?php if($race == "Elf") { ?>
		<tr>
			<td>Fiery Arrows:</td>
			<td><input type="hidden" name="data[defend][Spell][fiery_arrows]" id="DefendFieryArrows" value="0" /><input type="checkbox" name="data[defend][Spell][fiery_arrows]" class="checkbox" value="7" id="DefendFieryArrows" /></td>
		</tr>
		<tr>
			<td>Enchanted Weapons:</td>
			<td><input type="hidden" name="data[defend][Spell][enchanted_weapons]" id="DefendEnchantedWeapons" value="0" /><input type="checkbox" name="data[defend][Spell][enchanted_weapons]" class="checkbox" value="7" id="DefendEnchantedWeapons" /></td>
		</tr>
		<tr>
			<td>Hardened Armor:</td>
			<td><input type="hidden" name="data[defend][Spell][hardened_armor]" id="DefendHardenedArmor" value="0" /><input type="checkbox" name="data[defend][Spell][hardened_armor]" class="checkbox" value="7" id="DefendHardenedArmor" /></td>
		</tr>
	<?php } ?>
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
		<th rowspan="<?php if($race == "Elf") { ?>9<?php } else {?>8<?php }?>">Techs:</th>
	</tr>
	<tr>
		<td><?php echo $racialTech ?>:</td>
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
	</tr>
	<?php if($race == "Elf") { ?>
	<tr>
		<td>Spells:</td>
		<td>
			<select name="data[defend][Technology][tree_4]" class="dropDown" id="DefendSpell">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</td>
	</tr>
	<?php } ?>
	</tr> <!-- end techs -->
	<tr valign="top"> <!-- start units -->
		<th rowspan="<?php if($race == "Elf"){ echo "9"; } else { echo "7"; } ?>">Units:</th>
	</tr>
	<tr>
		<td><label for="DefendArmyWeak"><?php echo $units[0]?> </label></td>
		<td><input type="text" name="data[defend][Army][weak]" value="" size="4" id="DefendArmyWeak" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyStandard"><?php echo $units[1]?> </label></td>
		<td><input type="text" name="data[defend][Army][standard]" value="" size="4" id="DefendArmyStandard" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyRange"><?php echo $units[2]?> </label></td>
		<td><input type="text" name="data[defend][Army][range]" value="" size="4" id="DefendArmyRange" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyMount"><?php echo $units[3]?> </label></td>
		<td><input type="text" name="data[defend][Army][mount]" value="" size="4" id="DefendArmyMount" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmySmallElite"><?php echo $units[4]?> </label></td>
		<td><input type="text" name="data[defend][Army][small_elite]" value="" size="4" id="DefendArmySmallElite" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyBigElite"><?php echo $units[5]?> </label></td>
		<td><input type="text" name="data[defend][Army][big_elite]" value="" size="4" id="DefendArmyBigElite" /></td>
	</tr>
	<?php if($race == "Elf") { ?>
	<tr>
		<td><label for="DefendArmyWizard">Wizards</label></td>
		<td><input type="text" name="data[defend][Army][wizard]" value="" size="4" id="DefendArmyWizard" /></td>
	</tr>
	<tr>
		<td><label for="DefendArmyWizardResting">Resting Wiz</label></td>
		<td><input type="text" name="data[defend][Army][wizard_resting]" value="" size="4" id="DefendArmyWizardResting" /></td>
	</tr>
	<?php } ?>
	<!-- end units -->
</table>


<?php 
echo $ajax->observeField('UserDefendingRace', 
    array(
        'url' => array( 'action' => 'defendRaces', '?' => $simpleParms),
        'frequency' => 0.2,
		'update' => 'DefendUnits',
    	'indicator' => 'defend_spinner' 
    )
); 
?>