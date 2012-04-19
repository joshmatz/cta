<div class="instructions"><h3>Instructions</h3>Here you'll find a breakdown of the information of your whole kingdom. Topics include resource 
production, how many military you currently have returning to your kingdom, the spells affecting your kingdom, weapons produced, unit training speed, 
and your current population growth rate.</div>
<h3>Kingdom Overview</h3>

<h3>Economics</h3>
<table>
	<tr>
		<th>Resource</th>
		<th>Produced per tick</th>
		<th>Tick Upkeep</th>
		<th>Tick Total</th>
		<?php if($user['User']['premium']) { ?><th>Produced Daily</th>
		<th>Daily Upkeep</th>
		<th>Daily Total</th><?php } ?>
	</tr>
	<tr>
		<td>Food</td>
		<td><?php echo number_format($hour['Resource']['food'] - $user['Resource']['food']) ?></td>
		<td><?php echo "-".number_format($hour['Resource']['foodUpkeep']) ?>
		<td><?php echo number_format(($hour['Resource']['food'] - $user['Resource']['food']) - $hour['Resource']['foodUpkeep']) ?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($day['Resource']['food'] - $user['Resource']['food']) ?></td>
		<td><?php echo "-".number_format($day['Resource']['foodUpkeep']) ?></td>
		<td><?php echo number_format($day['Resource']['food'] - $user['Resource']['food'] - $day['Resource']['foodUpkeep']) ?></td><?php } ?>
	</tr>
	<tr>
		<td>Gold</td>
		<td><?php echo number_format($hour['Resource']['gold'] - $user['Resource']['gold']) ?></td>
		<td><?php echo "-".number_format($hour['Resource']['goldUpkeep']) ?></td>
		<td><?php echo number_format(($hour['Resource']['gold'] - $user['Resource']['gold']) - $hour['Resource']['goldUpkeep']) ?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($day['Resource']['gold'] - $user['Resource']['gold']) ?></td>
		<td><?php echo "-".number_format($day['Resource']['goldUpkeep'])?></td>
		<td><?php echo number_format($day['Resource']['gold'] - $user['Resource']['gold'] - $day['Resource']['goldUpkeep']) ?></td><?php }  ?>
	</tr>
	<tr>
		<td>Wood</td>
		<td><?php echo number_format($hour['Resource']['wood'] - $user['Resource']['wood']) ?></td>
		<td>0</td>
		<td><?php echo number_format($hour['Resource']['wood'] - $user['Resource']['wood']) ?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($day['Resource']['wood'] - $user['Resource']['wood']) ?></td>
		<td>0</td>
		<td><?php echo number_format($day['Resource']['wood'] - $user['Resource']['wood']) ?></td><?php } ?>
	</tr>
	<tr>
		<td>Iron</td>
		<td><?php echo number_format($hour['Resource']['iron'] - $user['Resource']['iron']) ?></td>
		<td>0</td>
		<td><?php echo number_format($hour['Resource']['iron'] - $user['Resource']['iron']) ?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($day['Resource']['iron'] - $user['Resource']['iron']) ?></td>
		<td>0</td>
		<td><?php echo number_format($day['Resource']['iron'] - $user['Resource']['iron']) ?></td><?php }?>
	</tr>
</table>
Your population will grow <?php echo number_format($hour['Population']['total_population'] - $user['Population']['total_population']) ?> in the next hour<?php if($user['User']['premium']) { ?>, 
<?php echo number_format($day['Population']['total_population'] - $user['Population']['total_population']) ?> in the next day <?php } ?>.
<br />Your population has a <?php echo $user['User']['crime_rate'] ?>% crime rate and is <?php echo round($user['User']['loyalty'] + 50) ?>% loyal to you.
<?php if($user['User']['victim_time']) {?><br /><b>You were attacked recently and are experiencing a <?php echo $user['User']['victim_bonus'] ?>% increase in production due to rebuilding efforts.</b><?php }?>  <br /><br />
<h3>Military</h3>
You currently have <?php if($user['Army']['generals'] == 1) echo $user['Army']['generals'] . " general available."; else echo $user['Army']['generals'] . " generals available."; ?> <br /><br />
<b>Armies Away</b>
<table>
	<tr>
		<th><?php echo $unitNames[0]?></th>
		<th><?php echo $unitNames[1]?></th>
		<th><?php echo $unitNames[2]?></th>
		<th><?php echo $unitNames[3]?></th>
		<th><?php echo $unitNames[4]?></th>
		<th><?php echo $unitNames[5]?></th>
		<th>General</th>
		<th>Days until Return</th>
	</tr>
	<?php 
	if($user['Attack']) {
		foreach($user['Attack'] as $attack): ?>
		<?php if($attack['rounds'] > 0){ ?>
			<tr>
				<td><?php echo number_format($attack['weak'])?></td>
				<td><?php echo number_format($attack['standard'])?></td>
				<td><?php echo number_format($attack['range'])?></td>
				<td><?php echo number_format($attack['mount'])?></td>
				<td><?php echo number_format($attack['small_elite'])?></td>
				<td><?php echo number_format($attack['big_elite'])?></td>
				<td>1</td>
				<td><?php echo $attack['rounds']?></td>
			</tr>
			<?php } ?>
		<?php endforeach;
	}
	else { ?>
		You have no units away.
	<?php }
	?>
</table>
<br />
<b>Weapons per hour:</b> <?php echo $weapons ?>
<br />
<br />
<b>Unit Training Speed</b>
<table>
	<tr>
		<th>Units</th>
		<th>Trained per tick</th>
		<?php if($user['User']['premium']) { ?><th>Trained Daily</th>
		<th>Trained Weekly</th><?php } ?>
	</tr>
	<tr>
		<td><?php echo $unitNames[0]?></td>
		<td><?php echo number_format($unitsTrained['weak'])?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($unitsTrained['weak']*24) ?></td>
		<td><?php echo number_format($unitsTrained['weak']*168) ?></td><?php } ?>
	</tr>
	<tr>
		<td><?php echo $unitNames[1]?></td>
		<td><?php echo number_format($unitsTrained['standard'])?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($unitsTrained['standard']*24) ?></td>
		<td><?php echo number_format($unitsTrained['standard']*168) ?></td><?php } ?>
	</tr>
	<tr>
		<td><?php echo $unitNames[2]?></td>
		<td><?php echo number_format($unitsTrained['range'])?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($unitsTrained['range']*24) ?></td>
		<td><?php echo number_format($unitsTrained['range']*168) ?></td><?php } ?>
	</tr>
	<tr>
		<td><?php echo $unitNames[3]?></td>
		<td><?php echo number_format($unitsTrained['mount'])?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($unitsTrained['mount']*24) ?></td>
		<td><?php echo number_format($unitsTrained['mount']*168) ?></td><?php } ?>
	</tr>
	<tr>
		<td><?php echo $unitNames[4]?></td>
		<td><?php echo number_format($unitsTrained['small_elite'])?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($unitsTrained['small_elite']*24) ?></td>
		<td><?php echo number_format($unitsTrained['small_elite']*168) ?></td><?php } ?>
	</tr>
	<tr>
		<td><?php echo $unitNames[5]?></td>
		<td><?php echo number_format($unitsTrained['big_elite'])?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($unitsTrained['big_elite']*24)?></td>
		<td><?php echo number_format($unitsTrained['big_elite']*168) ?></td><?php }?>
	</tr>
	<tr>
		<td><?php echo $unitNames[6]?></td>
		<td><?php echo number_format($unitsTrained['wizard'])?></td>
		<?php if($user['User']['premium']) { ?><td><?php echo number_format($unitsTrained['wizard']*24) ?></td>
		<td><?php echo number_format($unitsTrained['wizard']*168) ?></td><?php }?>
	</tr>

	
</table>
<br />
<h3>Magic</h3>
Your wizards rest at a rate of <?php echo $restedWizards ?> per ticks.<br /> <br />
<b>Spells in Effect</b>
<table>
	<tr>
		<th>Spell</th>
		<th>Duration</th>
	</tr>
	<?php if($user['Spell']['natural_growth'] > 0){ ?>
	<tr>
		<td>Natural Growth</td>
		<td><?php echo $user['Spell']['natural_growth']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['blessing'] > 0){ ?>
	<tr>
		<td>Blessing</td>
		<td><?php echo $user['Spell']['blessing']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['god_blessing'] > 0){ ?>
	<tr>
		<td>God Blessing</td>
		<td><?php echo $user['Spell']['god_blessing']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['shadow_hall'] > 0){ ?>
	<tr>
		<td>Shadow Hall</td>
		<td><?php echo $user['Spell']['shadow_hall']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['growing_crop'] > 0){ ?>
	<tr>
		<td>Growing Crop</td>
		<td><?php echo $user['Spell']['growing_crop']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['farseeing'] > 0){ ?>
	<tr>
		<td>Farseeing</td>
		<td><?php echo $user['Spell']['farseeing']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['freeze'] > 0){ ?>
	<tr>
		<td>Freeze</td>
		<td><?php echo $user['Spell']['freeze']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['famine'] > 0){ ?>
	<tr>
		<td>Famine</td>
		<td><?php echo $user['Spell']['famine']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['ice_storm'] > 0){ ?>
	<tr>
		<td>Ice Storm</td>
		<td><?php echo $user['Spell']['ice_storm']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['fiery_arrows'] > 0){ ?>
	<tr>
		<td>Fiery Arrows</td>
		<td><?php echo $user['Spell']['fiery_arrows']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['enchanted_weapons'] > 0){ ?>
	<tr>
		<td>Enchanted Weapons</td>
		<td><?php echo $user['Spell']['enchanted_weapons']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['hardened_armor'] > 0){ ?>
	<tr>
		<td>Hardened Armor</td>
		<td><?php echo $user['Spell']['hardened_armor']?> ticks</td>
	</tr>
	<?php } if($user['Spell']['firewall'] > 0){ ?>
	<tr>
		<td>Firewall</td>
		<td><?php echo $user['Spell']['firewall']?> ticks</td>
	</tr>
	<?php } ?>

</table>

<br />

<h3>Premium</h3>
<?php 
	if($user['User']['premium']) { 
		$time = time();
		$secondsUntilHour = (60*60) - $time % (60*60); 
?>YES (Expires <?php echo date('j M, Y, g:i', ($_SERVER['REQUEST_TIME'] - $_SERVER['REQUEST_TIME'] % (60*60) + round($user['User']['premium'])*60*60)) ?>)
<?php } else { ?>
NO <a href="/premiums/>">Buy premium points here.</a>
<?php } ?>
<br /><br />