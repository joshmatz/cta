Target: <?php echo $name ?><br />
<?php if($targetUser['User']['protection'] && empty($retals) && empty($allianceRetaliations)) { ?>
This user is currently under protection.

<?php } else {  ?>
<form id="AttackAttack" method="post" action="/attacks/attack">
<input name="data[Attack][target]" value="<?php echo $target ?>" type="hidden" id="target" />

You currently have <?php if($user['User']['Army']['generals'] == 1) echo $user['User']['Army']['generals'] . " general available."; else echo $user['User']['Army']['generals'] . " generals available."; ?> 
<br></br>How many units do you want to send?</br>
<table>
	<tr>
		<th>Units</th>
		<th>Total</th>
		<th>Max</th>
		<th>Send</th>
	</tr>
	<tr>
    	<th><?php echo "$unitNames[0]";?></th>
    	<th><?php echo "$unitNumbers[0]"?></th>
    	<th id="weakMax"></th>
    	<th><input name="data[Attack][weak]" type="text" size="8" value="" id="AttackWeak" /></th>   
    </tr>
    <tr>
    	<th><?php echo "$unitNames[1]";?></th>
    	<th><?php echo "$unitNumbers[1]"?></th>
    	<th id="standardMax"></th>
    	<th><input name="data[Attack][standard]" type="text" size="8" value="" id="AttackStandard" /></th>   
    </tr>
    <tr>
    	<th><?php echo "$unitNames[2]";?></th>
    	<th><?php echo "$unitNumbers[2]"?></th>
    	<th id="rangeMax"></th>
    	<th><input name="data[Attack][range]" type="text" size="8" value="" id="AttackRange" /></th>   
    </tr>
    <tr>
    	<th><?php echo "$unitNames[3]";?></th>
    	<th><?php echo "$unitNumbers[3]"?></th>
    	<th id="mountMax"></th>
    	<th><input name="data[Attack][mount]" type="text" size="8" value="" id="AttackMount" /></th>   
    </tr>
    <tr>
    	<th><?php echo "$unitNames[4]";?></th>
    	<th><?php echo "$unitNumbers[4]"?></th>
    	<th id="smallEliteMax"></th>
    	<th><input name="data[Attack][small_elite]" type="text" size="8" value="" id="AttackSmallElite" /></th>   
    </tr>
    <tr>
    	<th><?php echo "$unitNames[5]";?></th>
    	<th><?php echo "$unitNumbers[5]"?></th>
    	<th id="bigEliteMax"></th>
    	<th><input name="data[Attack][big_elite]" type="text" size="8" value="" id="AttackBigElite" /></th>   
    </tr>

</table>
<?php echo $form->end('Attack'); ?>
			<?php $strategy = $user['User']['Army']['strategy'];
			if ($strategy == 'None') { ?>
				<strong>Strategy: None</strong><br />
				You ask your army to fight as they normally would, employing no special tactics.
			<?php } 
			if ($strategy == 'Archer Protection') { ?>
				<strong>Strategy: Archer Protection</strong><br />
				In Archer protection, you will ask your Infantry to stand back in the battle and protect your vulnerable archers. 
		Infantry attack will be divided by 2 and any damages sustained by archers will be decreased by infantry normal attack.
			<?php } 
			if ($strategy == 'Reckless Abandon') { ?>
				 <strong>Strategy: Reckless Abandon</strong><br />
				 Reckless Abandon isn't just for careless generals, some of the most well known generals have used it in times of desperation.
		Your troops will fight with 150% attack power, but in an attempt to deal more damage, they will also leave themselves open and receive 150% damage.
			<?php }
			if ($strategy == 'Quick Retreat') { ?>
				<strong>Strategy: Quick Retreat</strong><br />
				With this strategy you will ask your units to be ready to retreat when you give the word. Using Quick Retreat, your troops will be 
		more focused on staying alive and will receive 50% damages and will dish out 50% their normal attack. Victory chances for your kingdom will also
		be reduced by 10%.
			<?php } 
			if ($strategy == 'Anti-Cavalry') { ?>
				<strong>Strategy: Anti-Cavalry</strong><br />
				Asking your Pike units to stand in a line in front of a charging army is no small favor to ask of your men. 
			However, with the risk of making your army composition less efficient (your other units will only deal 90% of their attack value), 
			comes a high reward of 350% pike damage versus enemy mounts, rather than 200%. 
			<?php } 
			if ($strategy == 'Horde') { ?>
				<strong>Strategy: Horde</strong><br />
				Orcs are well known for their insatiable lust for battle, especially when allowed to bask in the frenzy of a mighty horde as it
				 faces an opponent of lesser numbers. Adrenaline and a lust for battle helps the Orcish horde reach their enemy sooner than 
				 normal, thus evading many ranged attacks (25% reduced range damage, 50% if you overwhelm your opponent). 
				 Unfortunately, this hasty exertion makes orcs more vulnerable to damage taken by short ranged and melee attacks 
				 (an addition 10% short range and melee damage).
			<?php }
			if ($strategy == 'Shield Line') { ?>
				<strong>Strategy: Shield Line</strong><br />
				Dwarven armies are renowned around the world for the strength of their irons and what better way to accomodate this advantage than to place 
		Shield Bearers on the front lines of battle? With this strategy, Shield Bearers will become more defense oriented and block twice the percentage 
		of shield bearers in your army, up 65% of the enemy's range attack. However, with this defensive mindset, 
		Shield Bearers will have their attack reduced to 0.
			<?php }
			if ($strategy == 'Mystics') { ?>
				<strong>Strategy: Mystics</strong><br />
				Long have Elves used wizards for purely magical purposes. In dire need, wizards have also been known to inflict a great deal of harm to enemies. This strategy allows wizards within the kingdom to defend your castles with 1 (+.25 per Spell tree tech level) Long Range Attack each. 
		Unfortunately, to give them the strength to train, fight and cast magical spells, their upkeep is increased to 5 gold, 3 food.
			<?php }
			if ($strategy == 'Far Fighting') { ?>
				<strong>Strategy: Far Fighting</strong><br />
				Far Fighting is perhaps the most used strategy by Gnomes because it doubles their own range attack, which is usually their phase of choice.
		However, when not considering the opponent's army composition, it can be lethal because it also doubles the enemy's range attack power as well.
			<?php }
			if ($strategy == 'Charging') { ?>
				<strong>Strategy: Charging</strong><br />
				Knights are an integral part of a Human's army, and with the Clerical Magic they get even more use. With Charging, you will ask your Knights to be the first 
		into a battle. Because of the speed of their horses, this allows them to evade 50% range damage and deal 150% of their own attack. However, due to the rest 
		of the Human army's quickness and lack of support, Knights also receive 150% melee and short range damage.
			<?php } ?>
			
<b>To change your strategy for battle, <a href="/strategies<?php echo $parms?>">meet with your generals</a><br /> to tell them how you'd like your army to fight!</b>
<?php } ?>
