<?php 
	$ajax->options['url']['?'] = $simpleParms; 

?>
<table>
	<tr>
		<th colspan="4"><div style="text-align:center;">Attacker</div></th>
	</tr>
	<tr valign="top">
		<th rowspan="2">Race:</th>
		
	</tr>
	<tr>
		<td>
			<select name="data[User][Attacking Race: ]" id="UserAttackingRace">
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
		<td><input name="data[User][Attacking Score: ]" type="text" value="" size="5" id="UserAttackingScore:" /></td>
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
				<?php if($race != "Dwarf") { ?><option value="Anti-Cavalry">Anti-Cavalry</option><?php } ?>
				<?php if($race == "Human") { ?><option value="Charging">Charging</option><?php } ?>
				<?php if($race == "Elf") { ?><option value="Mytics">Mystics</option><?php } ?>
				<?php if($race == "Orc") { ?><option value="Horde">For the Horde</option><?php } ?>
				<?php if($race == "Dwarf") { ?><option value="Shield Line">Shield Line</option><?php } ?>
				<?php if($race == "Gnome") { ?><option value="Far Fighting">Far Fighting</option><?php } ?>
			</select>
		</td>
		<td></td>
	</tr><!-- end strategy -->
	<?php if($race == "Elf") { ?>
		<tr valign="top"><!-- start spells -->
			<th rowspan="4">Spells:</th>
		</tr>
		<tr>
			<td>Fiery Arrows:</td>
			<td><input type="hidden" name="data[User][Spell][fiery_arrows]" id="UserFieryArrows" value="0" /><input type="checkbox" name="data[User][Spell][fiery_arrows]" class="checkbox" value="7" id="UserFieryArrows" /></td>
		</tr>
		<tr>
			<td>Enchanted Weapons:</td>
			<td><input type="hidden" name="data[User][Spell][enchanted_weapons]" id="UserEnchantedWeapons" value="0" /><input type="checkbox" name="data[User][Spell][enchanted_weapons]" class="checkbox" value="7" id="UserEnchantedWeapons" /></td>
		</tr>
		<tr>
			<td>Hardened Armor:</td>
			<td><input type="hidden" name="data[User][Spell][hardened_armor]" id="UserHardenedArmor" value="0" /><input type="checkbox" name="data[User][Spell][hardened_armor]" class="checkbox" value="7" id="UserHardenedArmor" /></td>
		</tr>
	<?php } ?>
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
		<td><?php echo $racialTech ?>:</td>
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
	<?php if($race == "Elf") { ?>
	<tr>
		<td>Spells:</td>
		<td>
			<select name="data[User][Technology][tree_4]" class="dropDown" id="AttackSpell">
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
		<td><label for="AttackArmyWeak"><?php echo $units[0]?> </label></td>
		<td><input type="text" name="data[attack][Attack][weak]" value="" size="4" id="AttackArmyWeak" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmyStandard"><?php echo $units[1]?> </label></td>
		<td><input type="text" name="data[attack][Attack][standard]" value="" size="4" id="AttackArmyStandard" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmyRange"><?php echo $units[2]?> </label></td>
		<td><input type="text" name="data[attack][Attack][range]" value="" size="4" id="AttackArmyRange" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmyMount"><?php echo $units[3]?> </label></td>
		<td><input type="text" name="data[attack][Attack][mount]" value="" size="4" id="AttackArmyMount" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmySmallElite"><?php echo $units[4]?> </label></td>
		<td><input type="text" name="data[attack][Attack][small_elite]" value="" size="4" id="AttackArmySmallElite" /></td>
	</tr>
	<tr>
		<td><label for="AttackArmyBigElite"><?php echo $units[5]?> </label></td>
		<td><input type="text" name="data[attack][Attack][big_elite]" value="" size="4" id="AttackArmyBigElite" /></td>
	</tr>
	<!-- end units -->
</table>
<?php 
echo $ajax->observeField('UserAttackingRace', 
    array(
        'url' => array( 'action' => 'attackRaces'),
     	'update' => 'AttackUnits:',
    	'indicator' => 'attack_spinner'
    )
); 
?>