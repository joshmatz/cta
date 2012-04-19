<div class="instructions"><h3>Instructions</h3></center>The strategies on this page all have positive benefits and negative side effects, 
it's up to you to choose which one is best for your current situation. If you don't like any of them, feel free to select "None" as your strategy of choice.</div>
<h3>Strategy Selector</h3>
<form id="StrategySetStrategy" method="post" action="/strategies/setStrategy">
<input name="data[Strategy][strategy]" id="StrategyStrategy_" value="" type="hidden">
<table cellpadding="5px">
	<tr>
		<th>Strategy</th>
		<th>Description</th>
		<th>&nbsp;</th>
	</tr>
	<tr>
		<td style="text-align:left">None</td>
		<td style="text-align:left">You ask your army to fight as they normally would, employing no special tactics.</td>
		<td>
			<?php if ($strategy == 'None') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy9" value="None" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy9" value="None" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<tr>
		<td style="text-align:left">Archer Protection</td>
		<td style="text-align:left">In Archer protection, you will ask your Infantry to stand back in the battle and protect your vulnerable archers. 
		Infantry attack will be divided by 2 and any damages sustained by archers will be decreased by infantry normal attack.</td>
		<td>
			<?php if ($strategy == 'Archer Protection') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy1" value="Archer Protection" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy1" value="Archer Protection" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<tr>
		<td style="text-align:left">Reckless Abandon</td>
		<td style="text-align:left">Reckless Abandon isn't just for careless generals, some of the most well known generals have used it in times of desperation.
		Your troops will fight with 150% attack power, but in an attempt to deal more damage, they will also leave themselves open and receive 150% damage.</td>
		<td>
			<?php if ($strategy == 'Reckless Abandon') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy2" value="Reckless Abandon" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy2" value="Reckless Abandon" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<tr>
		<td style="text-align:left">Quick Retreat</td>
		<td style="text-align:left">With this strategy you will ask your units to be ready to retreat when you give the word. Using Quick Retreat, your troops will be 
		more focused on staying alive and will receive 50% damages and will dish out 50% their normal attack. Victory chances for your kingdom will also
		be reduced by 10%.</td>
		<td>
			<?php if ($strategy == 'Quick Retreat') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy3" value="Quick Retreat" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy3" value="Quick Retreat" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<?php if ($user['User']['race'] != "Dwarf") {?>
		<tr>
			<td style="text-align:left">Anti-Cavalry</td>
			<td style="text-align:left">Asking your Pike units to stand in a line in front of a charging army is no small favor to ask of your men. 
			However, with the risk of making your army composition less efficient (your other units will only deal 90% of their attack value), 
			comes a high reward of 350% pike damage versus enemy mounts, rather than 200%. </td>
			<td>
				<?php if ($strategy == 'Anti-Cavalry') { ?>
					<input name="data[Strategy][strategy]" id="StrategyStrategy4" value="Anti-Cavalry" type="radio" CHECKED>
				<?php } else {?>
					<input name="data[Strategy][strategy]" id="StrategyStrategy4" value="Anti-Cavalry" type="radio">
				<?php } ?>
				
			</td>
		</tr>
	<?php } ?>
<?php 
switch($user['User']['race']){
	case "Orc":?>
	<tr>
		<td style="text-align:left">For the Horde</td>
		<td style="text-align:left">Orcs are well known for their insatiable lust for battle, especially when allowed to bask in the frenzy of a mighty horde as it faces an opponent of lesser numbers. Adrenaline and a lust for battle helps the orcish horde reach their enemy sooner than normal, thus evading many ranged attacks (25% reduced range damage, 50% if you overwhelm your opponent). Unfortunately, this hasty exertion makes orcs more vulnerable to damage taken by short ranged and melee attacks (an addition 10% short range and melee damage).
 </td>
		<td>
			<?php if ($strategy == 'Horde') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy5" value="Horde" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy5" value="Horde" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<?php break;
	case "Dwarf":?>
	<tr>
		<td style="text-align:left">Shield line</td>
		<td style="text-align:left">Dwarven armies are renowned around the world for the strength of their irons and what better way to accomodate this advantage than to place 
		Shield Bearers on the front lines of battle? With this strategy, Shield Bearers will become more defense oriented and block twice the percentage 
		of shield bearers in your army, up 65% of the enemy's range attack. However, with this defensive mindset, 
		Shield Bearers will have their attack reduced to 0.
		</td>
		<td>
			<?php if ($strategy == 'Shield Line') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy6" value="Shield Line" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy6" value="Shield Line" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<?php break;
	case "Elf":?>
	<tr>
		<td style="text-align:left">Mystics</td>
		<td style="text-align:left">Long have Elves used wizards for purely magical purposes. In dire need, wizards have also been known to inflict a great deal of harm to enemies. This strategy allows wizards within the kingdom to defend your castles with 1 (+.25 per Spell tree tech level) Long Range Attack each. 
		Unfortunately, to give them the strength to train, fight and cast magical spells, their upkeep is increased to 5 gold, 3 food. <strong style="color:red;">Warning:</strong> Once you switch to this strategy, your next strategy selection will not be made until the tick.</td>
		<td>
			<?php if ($strategy == 'Mystics') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy7" value="Mystics" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy7" value="Mystics" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<?php break;
	case "Gnome":?>
	<tr>
		<td style="text-align:left">Far Fighting</td>
		<td style="text-align:left">Far Fighting is perhaps the most used strategy by Gnomes because it doubles their own range attack, which is usually their phase of choice.
		However, when not considering the opponent's army composition, it can be lethal because it also doubles the enemy's range attack power as well.</td>
		<td>
			<?php if ($strategy == 'Far Fighting') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy8" value="Far Fighting" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy8" value="Far Fighting" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<?php break;
	case "Human":?>
	<tr>
		<td style="text-align:left">Charging</td>
		<td style="text-align:left">Knights are an integral part of a Human's army, and with the Clerical Magic they get even more use. With Charging, you will ask your Knights to be the first 
		into a battle. Because of the speed of their horses, this allows them to evade 50% range damage and deal 150% of their own attack. However, due to the rest 
		of the Human army's quickness and lack of support, Knights also receive 150% melee and short range damage.
		</td>
		<td>
			<?php if ($strategy == 'Charging') { ?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy9" value="Charging" type="radio" CHECKED>
			<?php } else {?>
				<input name="data[Strategy][strategy]" id="StrategyStrategy9" value="Charging" type="radio">
			<?php } ?>
			
		</td>
	</tr>
	<?php break;
	default: echo "Error accessing database."; break; } ?>
	<tr>
		<td style="text-align:left"></td>
		<td style="text-align:left"></td>
		<td><input type="submit" value="Set Strategy" /><?php //echo $form->end('Set Strategy', array('label' => false, 'div' => false)); ?></td>
	</tr>
</table>
</form>
