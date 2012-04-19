<?php
class Spell extends AppModel {    
	var $name = 'Spell';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id'
		)
	);
	
	function pluralize($s, $c) {
		if ($c != 1 && $s != "Heavy Crossbowman" && $s != "Light Crossbowman" && $s != "Courageous" && $s != "Axe Man") {
			return $c . ' ' . Inflector::pluralize($s);
		}
		elseif($s == "Heavy Crossbowman"){
	      	if($c != 1)
	      		$s = "Heavy Crossbowmen";      		
      	}
		elseif($s == "Light Crossbowman"){
	      	if($c != 1)
	      		$s = "Light Crossbowmen";   
      	}
		elseif($s == "Courageous"){
	      	if($c != 1)
	      		$s = "Courageouses";   
     	}
     	elseif($s == "Axe Man"){
     		if($c != 1)
     			$s = "Axe Men";
     	}
    	return $c . ' ' . $s;
    }
	
	function updateSpells($user = null){
		$types = $this->getSpellTypes();
		foreach($types as $type):
			if($user['Spell'][$type] > 0)
				$user['Spell'][$type]--;
		endforeach;
		return $user;
	}
	
	function isReflected($user = null){
		$reflected = (0.1*$user['User']['Building']['school']/$user['User']['Resource']['land'])+(0.01*($user['User']['Army']['wizard'] + $user['User']['Army']['wizard_resting'] * .5) / $user['User']['Resource']['land'] / 75) - $this->getMagicResistance($user);
		if($reflected > 2.5)
			$reflected = 2.5;
		if(($reflected * 100) > mt_rand(0,500))
			return true;
		else
			return false;
	}
	
	function getOffensiveSpellSuccess($user = null, $target = null, $wizardsUsed = null, $spellCost = 5){
		$bonus = $this->getMagicBonus($user);
		if ($target['User']['score']/$user['User']['score'] >= 0.6)
			$spellMultiplier = 1;	
		else if ($target['User']['score']/$user['User']['score'] < 0.6)
			$spellMultiplier = pow($target['User']['score']/$user['User']['score'], 1.7);
			
		$counter = 0.3*pow($target['User']['Building']['school']/$target['User']['Resource']['land'], 0.7) + (0.01*$target['User']['Army']['wizard']/$target['User']['Resource']['land']) + (0.005*$target['User']['Army']['wizard_resting']/$target['User']['Resource']['land']);
			
		$wizardRatio = $wizardsUsed/($target['User']['Resource']['land']*$spellCost);
		$schoolRatio = $user['User']['Building']['school'] / $target['User']['Resource']['land'];
		
		return (($wizardRatio * pow($schoolRatio, 0.7)) * $bonus *$spellMultiplier)*max(1-($this->getMagicResistance($target) + $counter), 0.25);
	}
	
	function createSpellRetal($targetId = null, $userId = null){
		Classregistry::init('Retaliation')->createRetaliation($targetId, $userId, 'Spell');
	}
	
	function getSpellList($user = null){
		// array key is the name, value is the value displayed to the user
		$options['growing_crop'] = 'Growing Crop';
		$options['natural_growth'] = 'Natural Growth';
		$options['gold_to_iron'] = 'Gold to Iron';
		$options['fireball'] = 'Fireball';
		$options['blessing'] = 'Blessing';
		$options['truesight'] = 'Truesight';
		
		if($user['User']['Technology']['tree_4'] > 0){
			$options['wood_to_food'] = 'Wood to Food';
			$options['farseeing'] = 'Farseeing';
		}
		if($user['User']['Technology']['tree_4'] > 1){
			$options['freeze'] = 'Freeze';
			$options['famine'] = 'Famine';
		}
		if($user['User']['Technology']['tree_4'] > 2){
			$options['earthquake'] = 'Earthquake';
			$options['firewall'] = 'Firewall';
		}
		if($user['User']['Technology']['tree_4'] > 3){
			$options['gate'] = 'Gate';
			$options['god_blessing'] = 'God Blessing';
		}
		if($user['User']['race'] == 'Elf') {
			$options['enchanted_weapons'] = "Enchanted Weapons";
			$options['fiery_arrows'] = "Fiery Arrows";
			$options['hardened_armor'] = "Hardened Armor";
			if($user['User']['Technology']['racial'] > 0){
				$options['shadow_hall'] = 'Shadow Hall';
				$options['ice_storm'] = 'Ice Storm';
			}
		}
			
		return $options;
	}
	
	function getSpellTypes(){
		return array('growing_crop', 'natural_growth', 'gold_to_iron', 'fireball', 'blessing', 'truesight', 'wood_to_food', 'farseeing', 'freeze', 'famine', 'earthquake', 'firewall', 'gate', 'god_blessing', 'shadow_hall', 'ice_storm', 'enchanted_weapons', 'fiery_arrows', 'hardened_armor');
	}
	
	function getTargetName($targetId = null, $link = true){
		$target = $this->find('first', array('conditions' => array('User.id' => $targetId), 'fields' => 'User.ruler, User.id'));
		if($link){
			$name = '<a href="/users/profile/' . $targetId . '">' . $target['User']['ruler']/* . " of " . $target['User']['kingdom']*/ . "</a>";
		} else {
			$name = $target['User']['ruler']/* . " of " . $target['User']['kingdom']*/;
		}
		return $name;
	}
	
	function getPlayerLevel($user = null, $target = null){
		//$target = $this->User->find('first', array('conditions' => array('User.id' => $target['User']['id']), 'fields' => array('score')));
	    $level = 1;
		if ($target['User']['score']/$user['User']['score'] >= .9)
			$level = 1; 
		else if (.9 > $target['User']['score']/$user['User']['score'] && $target['User']['score']/$user['User']['score'] >= .5)
			$level = pow(max(($victim['User']['score']/$user['User']['score'])-(.3666), 0)*(30/17), .29);
		else if ($target['User']['score']/$user['User']['score'] < .5)
			$level = max(($target['User']['score']/$user['User']['score'])-(.3666), 0)*4.2;
		
		//if($target['User']['score'] < $user['User']['score'] * .9)
	    //	$level = ($target['User']['score']/($user['User']['score']);
		
		return $level;
	}
	
	function getMagicBonus($user = null, $self = false) {
		if($self && $user['User']['race'] != "Elf"){
			$user['User']['race'] = "Human";
		}
		switch($user['User']['race']){
			case "Orc":
				$bonus = .85;
				break;
			case "Dwarf":
				$bonus = .5;
				break;
			case "Elf":
				$bonus = 1.15;
				break;
			case "Human":
				$bonus = 1;
				break;
			case "Gnome":
				$bonus = .625;
				break;
			default:
				$bonus = 1;
				break;
		}
		//Magical Instruments Tech
		if ($user['User']['Technology']['tree_4'] > 5)
			$bonus += .2;
		return $bonus;
	}
	
	function getMagicResistance($user = null) {
		switch($user['User']['race']){
			case "Orc":
				$resistance = 0;
				break;
			case "Dwarf":
				$resistance = .4;
				break;
			case "Elf":
				$resistance = 0;
				break;
			case "Human":
				$resistance = 0;
				break;
			case "Gnome":
				$resistance = .35;
				break;
			default:
				$resistance = 0;
				break;
		}
		return $resistance;
	}
	
	function getMagicMultiplier(){
		return 1;
	}
	
	
	///Spells
	
	function castGrowingCrop($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user, $self);
		//you should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/5;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Growing Crop spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['Spell']['growing_crop'] = 6;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report = $this->getTargetName($user['User']['id']) . 
				" successfully cast Growing Crop on your kingdom. Your land will see better crop growth for 6 days.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Growing Crop spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) . 
				" was unsuccessful in casting Growing Crop spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castNaturalGrowth($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user, $self);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/3;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Natural Growth spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['Spell']['natural_growth'] = 8;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast Natural Growth spell on your kingdom. Your population will increase faster for 8 days.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Natural Growth spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting Natural Growth spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castGoldToIron($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user, $self);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/5;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		if($successChance < 0)
			$successChance = 0;
		if($successChance > 4)
			$successChance = 4;
		$gold = round($wizards * (mt_rand(10,40)));
		if($gold > $target['User']['Resource']['gold'])
			$gold = $target['User']['Resource']['gold'];
		$iron = round($gold / (mt_rand(50,200)) * $successChance);
		
		$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Gold to Iron spell, converting " . number_format($gold) . " gold into " . number_format($iron) . " iron for " . $this->getTargetName($target['User']['id']) . ". <br />";
		
		$target['User']['Resource']['iron'] += $iron;
		$target['User']['Resource']['gold'] -= $gold;
		$this->User->Resource->id = $target['User']['Resource']['id'];
		$this->User->Resource->save($target['User']['Resource']);
								
		$report = $this->getTargetName($user['User']['id']) .  
			" successfully cast a Gold to Iron spell on your kingdom, converting " . number_format($gold) . " gold into " . number_format($iron) . " iron.";
		if($user['User']['id'] != $target['User']['id'])
			Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
		
		$user['User']['Army']['wizard_resting'] += $wizards;
		$user['User']['Army']['wizard'] -= $wizards;
		$this->User->Army->id = $user['User']['Army']['id'];
		$this->User->Army->save($user['User']['Army']);
		
		if($successChance < .5)
			$result .= "<br />Your gold to iron conversion rate was very low, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}

	function castFireball($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		//echo "Bonus: $bonus <br />";
		$resistance = $this->getMagicResistance($target);
		//echo "Resistance: $resistance <br />";
		$schoolRatio = $user['User']['Building']['school'] / $target['User']['Resource']['land'];
		//echo "SchoolRatio: $schoolRatio <br />";
		if(($target['User']['Army']['wizard'] > 0) || ($target['User']['Army']['wizard_resting'] > 0)) {
			$wizardRatio = $wizards / ($target['User']['Army']['wizard'] + ($target['User']['Army']['wizard_resting'] / 2));
			if($wizardRatio > 2)
				$wizardRatio = 2;
		}
		else
			$wizardRatio = 2;
		//echo "WizardRatio: $wizardRatio <br />";
		//echo "Player level: " . $this->getPlayerLevel($user, $target) . "<br />";
		$successChance = $this->getMagicMultiplier() * $wizardRatio * $schoolRatio * $this->getPlayerLevel($user, $target) * $bonus - $resistance;
		$successChance = $this->getOffensiveSpellSuccess($user, $target, $wizards, 7);
		//echo "Success Chance: $successChance<br />";
		$randomChance = mt_rand(1, 100);
		$deathMultiplier = mt_rand(100,300);
		if(($successChance * 100) > $randomChance) {
			$populationToKill = round(($wizards * $successChance * ($deathMultiplier/100)));
			
			$population[0]['type'] = "lumbers";
			$population[0]['amount'] = $target['User']['Population']['lumbers'];
			$population[0]['percent_dieing'] = 0;
			$population[1]['type'] = "miners";
			$population[1]['amount'] = $target['User']['Population']['miners'];
			$population[1]['percent_dieing'] = 0;
			$population[2]['type'] = "agriculture";
			$population[2]['amount'] = $target['User']['Population']['agriculture'];
			$population[2]['percent_dieing'] = 0;
			$population[3]['type'] = "building";
			$population[3]['amount'] = $target['User']['Population']['building'];
			$population[3]['percent_dieing'] = 0;
			$population[4]['type'] = "blacksmiths";
			$population[4]['amount'] = $target['User']['Population']['blacksmiths'];
			$population[4]['percent_dieing'] = 0;
			$population[5]['type'] = "explorers";
			$population[5]['amount'] = $target['User']['Population']['explorers'];
			$population[5]['percent_dieing'] = 0;
			$population[6]['type'] = "training";
			$population[6]['amount'] = $target['User']['Population']['training'];
			$population[6]['percent_dieing'] = 0;
			

			$total = $target['User']['Population']['total_population'];
			
			if($total < $populationToKill)
				$populationToKill = $total;
			foreach($population as $type){
				$category = $type['type'];
				if($total > 0)
					$type['percent_dieing'] = $type['amount'] / $total;
				else
					$type['percent_dieing'] = 0;
				$type['amount'] -= round($populationToKill * $type['percent_dieing']);
				if($type['amount'] < 0)
					$type['amount'] = 0;
				$target['User']['Population'][$category] = $type['amount'];
			}

			$target['User']['Population']['total_population'] -= $populationToKill;
			
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Fireball spell, brutally killing " . number_format($populationToKill) . " of " . $this->getTargetName($target['User']['id']) . "'s total peasants. <br />";

			$this->User->Population->id = $target['User']['Population']['id'];
			$this->User->Population->save($target['User']['Population']);
									
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast a Fireball spell on your kingdom, killing " . number_format($populationToKill) . " of your peasants";
			Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			// Spell Retaliation
			$this->createSpellRetal($target['User']['id'], $user['User']['id']);
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Fireball spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting a Fireball spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;	
	}
	
	function castBlessing($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user, $self);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/7;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		//$successChance = $this->getOffensiveSpellSuccess($user, $target, $wizards, 7);
		$randomChance = mt_rand(1, 100);
		//echo "Success chance: " . ($successChance*100) . "% <br />";
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Blessing spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['Spell']['blessing'] = 12;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report =  $this->getTargetName($user['User']['id']) .  
				" successfully cast Blessing spell on your kingdom. Your population will see an increase in productivity, thereby generating more income, for the next 12 days.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Blessing spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting Blessing spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castTruesight($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		$resistance = $this->getMagicResistance($target);
		
		if($target['User']['Building']['school'] > 0)
			$schoolRatio = $user['User']['Building']['school'] / $target['User']['Resource']['land'];
		else
			$schoolRatio = 2;
		if(($target['User']['Army']['wizard'] > 0) || ($target['User']['Army']['wizard_resting'] > 0)) { 
			$wizardRatio = $wizards / ($target['User']['Army']['wizard'] + $target['User']['Army']['wizard_resting'] / 2);
			if($wizardRatio > 2)
				$wizardRatio = 2;
		} else {
			$wizardRatio = 1;
		}
		
		$wizardRatio *= ($wizards / $target['User']['Resource']['land'] / 6);
		
		//If you have the same amount of wizards and schools, the chance is 75%
		$successChance = $this->getMagicMultiplier() * $wizardRatio * $schoolRatio * $this->getPlayerLevel($user, $target) * $bonus - $resistance;
		//$successChance = $this->getOffensiveSpellSuccess($user, $target, $wizards, 3);
		$randomChance = mt_rand(1,100);
		if(($successChance * 100) > $randomChance) {
			// I need to do this to get the names of the units
			// Maybe in the future make different Army functions >.<
			$army = $this->User->Army->getFullArmyData($target['User']);
			$seed = 6;
			$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
			
			$result = "Your " . $this->pluralize('wizards', $wizards) . " successfully cast the Truesight spell, revealing info about " . $this->getTargetName($target['User']['id']) . ". <br /> They revealed: 
			 <div class=\"truesight_army\"> <b>Army: </b>";
			 foreach($army as $unit):
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
			 	if($unit['type'] != "wizard"){
				 	$result .= $this->pluralize($unit['name'], round($unit['amount'] * $variance)) . ", ";
			 	}
			 	else {
				 	if($unit['amount'] != 1)
				 		$result .= "and " . $this->pluralize($unit['name'], round($unit['amount'] * $variance)) . ". ";
			 	}	
			 endforeach;
			 $result .= "</div> <br />";
			if($user['User']['Technology']['tree_4'] > 0){
				$result .= "<div class=\"truesight_building\"> <b>Buildings: </b>";
				$result .= number_format(round($target['User']['Building']['house']*$variance)) . " houses, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['farm']*$variance)) . " farms, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['mill']*$variance)) . " mills, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['mine']*$variance)) . " mines, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['school']*$variance)) . " schools, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['forge']*$variance)) . " forges, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['market']*$variance)) . " markets, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['medical_center']*$variance)) . " medical centers, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['guard_house']*$variance)) . " guard houses, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['guard_tower']*$variance)) . " guard towers, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['training_center']*$variance)) . " training centers, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Building']['advanced_training_center']*$variance)) . " advanced training centers.";
				
				$result .= "</div> <br />";
			}
			if($user['User']['Technology']['tree_4'] > 1){
				$result .= "<div class=\"truesight_resource\"> <b>Resources: </b>";
				$result .= number_format(round($target['User']['Resource']['gold']*$variance)) . " gold, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Resource']['iron']*$variance)) . " iron, ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= number_format(round($target['User']['Resource']['wood']*$variance)) . " wood, and ";
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .=  number_format(round($target['User']['Resource']['food']*$variance)) . " food."; 
				$result .= "</div> <br />";
			}
			if($user['User']['Technology']['tree_4'] > 2){
				$result .= "<div class=\"truesight_unitsAway\"> <b>Units away: </b>";
				foreach($army as $unit):
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
			 	if($unit['type'] != "wizard"){
				 		$result .= $this->pluralize($unit['name'], round($unit['amount_away']*$variance)) . ", ";
			 	}
			 	else {
				 		$result .= "and " . $this->pluralize($unit['name'], round($unit['amount_away']*$variance)) . ". ";
			 	}	
				endforeach;
				$result .= "</div> <br />";
			}
			
			
			if($user['User']['Technology']['tree_4'] < 4)
					$result .= "Upgrade your New Spells technology to reveal more information.<br />";

			

			$this->User->Population->id = $target['User']['Population']['id'];
			$this->User->Population->save($target['User']['Population']);
									
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast a Truesight spell on your kingdom, revealing some information about your kingdom.";
			Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your $wizards wizards were unable to successfully reveal any information about the kingdom " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			if($successChance > .1){
				$report = $this->getTargetName($user['User']['id']) .  
					" was unsuccessful in casting a Truesight spell on your kingdom.";
				if($user['User']['id'] != $target['User']['id']) {
					Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
				}
				
			} else {
				/*
				 * Reflect spell if horrible casting.
				 */
				$army = $this->User->Army->getFullArmyData($user['User']);
				$seed = 3;
				$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($target['User']['Resource']['land']*$seed)))));
				$result .= "<br /> Your wizards also did a horrible job while mixing the ingredients for the spell and the opposing kingdom's wizards saw info about our kingdom! Death to our wizards in charge!<br />";
				$reflect = "Your wizards successfully defended a Truesight spell from " . $this->getTargetName($user['User']['id']) . ". <br />Luckily they were able to reflect the spell and catch a glimpse of the opposing kingdom: 
				 <div class=\"truesight_army\"> <b>Army: </b>";
				 foreach($army as $unit):
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
				 	if($unit['type'] != "wizard"){
					 	$reflect .= $this->pluralize($unit['name'], round($unit['amount'] * $variance)) . ", ";
				 	}
				 	else {
					 	if($unit['amount'] != 1)
					 		$reflect .= "and " . $this->pluralize($unit['name'], round($unit['amount'] * $variance)) . ". ";
				 	}	
				 endforeach;
				 $reflect .= "</div> <br />";
				if($user['User']['Technology']['tree_4'] > 0){
					$reflect .= "<div class=\"truesight_building\"> <b>Buildings: </b>";
					$reflect .= number_format(round($user['User']['Building']['house']*$variance)) . " houses, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['farm']*$variance)) . " farms, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['mill']*$variance)) . " mills, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['mine']*$variance)) . " mines, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['school']*$variance)) . " schools, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['forge']*$variance)) . " forges, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['market']*$variance)) . " markets, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['medical_center']*$variance)) . " medical centers, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['guard_house']*$variance)) . " guard houses, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['guard_tower']*$variance)) . " guard towers, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['training_center']*$variance)) . " training centers, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Building']['advanced_training_center']*$variance)) . " advanced training centers.";
					
					$reflect .= "</div> <br />";
				}
				if($user['User']['Technology']['tree_4'] > 1){
					$reflect .= "<div class=\"truesight_resource\"> <b>Resources: </b>";
					$reflect .= number_format(round($user['User']['Resource']['gold']*$variance)) . " gold, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Resource']['iron']*$variance)) . " iron, ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .= number_format(round($user['User']['Resource']['wood']*$variance)) . " wood, and ";
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
					$reflect .=  number_format(round($user['User']['Resource']['food']*$variance)) . " food."; 
					$reflect .= "</div> <br />";
				}
				if($user['User']['Technology']['tree_4'] > 2){
					$reflect .= "<div class=\"truesight_unitsAway\"> <b>Units away: </b>";
					foreach($army as $unit):
					$variance = (1+(((mt_rand(0,160)-80)*0.01)/(1+($wizards/($user['User']['Resource']['land']*$seed)))));
				 	if($unit['type'] != "wizard"){
					 		$reflect .= $this->pluralize($unit['name'], round($unit['amount_away']*$variance)) . ", ";
				 	}
				 	else {
					 		$reflect .= "and " . $this->pluralize($unit['name'], round($unit['amount_away']*$variance)) . ". ";
				 	}	
					endforeach;
					$reflect .= "</div> <br />";
				}
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $reflect, "Reflect Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			} // End reflection
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;	
	}

	function castWoodToFood($user = null, $target = null, $wizards = null, $self = null){
		
		$bonus = $this->getMagicBonus($user, $self);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/5;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$wood = round(pow($wizards, 2) / ($wizards / mt_rand(4,6)));
			if($wood > $target['User']['Resource']['wood'])
				$wood = $target['User']['Resource']['wood'];
			$food = round($wood * (mt_rand(750,1350) / 15));
			
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Wood to Food spell, converting " . number_format($wood) . " wood into " . number_format($food) . " food for " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['User']['Resource']['wood'] -= $wood;
			$target['User']['Resource']['food'] += $food;
			$this->User->Resource->id = $target['User']['Resource']['id'];
			$this->User->Resource->save($target['User']['Resource']);
									
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast a Wood to Food spell on your kingdom, converting " . number_format($wood) . " wood into " . number_format($food) . " food.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Wood to Food spell for " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting a Wood to Food spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castFarseeing($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user, $self);
		//you should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/5;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Farseeing spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['Spell']['farseeing'] = 12;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast Farseeing on your kingdom. Your explorers will be twice as effective for 12 days.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " unable to successfully cast the Farseeing spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting a Farseeing spell on your kingdom.";
			
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castFreeze($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user, $self);
		$resistance = $this->getMagicResistance($target);
		
		$schoolRatio = $user['User']['Building']['school'] / $target['User']['Resource']['land'];
		if($schoolRatio > 2)
			$schoolRatio = 2;
		if(($target['User']['Army']['wizard'] > 0) || ($target['User']['Army']['wizard_resting'] > 0)) {
			$wizardRatio = $wizards / $target['User']['Resource']['land'] / 8;
			if($wizardRatio > 2)
				$wizardRatio = 2;
		}
		else
			$wizardRatio = 2;
		$successChance = $this->getMagicMultiplier() * $wizardRatio * $schoolRatio * $this->getPlayerLevel($user, $target) * $bonus - $resistance;
		$successChance = $this->getOffensiveSpellSuccess($user, $target, $wizards, 8);
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Freeze spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['Spell']['freeze'] = 8;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast Freeze on your kingdom. Your builders will be 1/3 as effective for 8 days, and your production is reduced 10%.";
			Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			// Spell Retaliation
			$this->createSpellRetal($target['User']['id'], $user['User']['id']);
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " unable to successfully cast the Freeze spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting a Freeze spell on your kingdom.";
			if($this->isReflected($target)){
				$result .= "<br />Unfortunately the kingdom we attempted to cast Freeze on was able to reflect the spell into our territory. Our kingdom will now suffer its unfortunate effects. <br />";
				$user['Spell']['freeze'] = 4;
				$report .= "<br /><br />Fortunately, our wizards were able to reflect the spell back onto their lands.<br />";
				$this->id = $target['Spell']['id'];
				$this->save($target);
			}
			
			
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castFamine($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		$resistance = $this->getMagicResistance($target);
		
		$schoolRatio = $user['User']['Building']['school'] / $target['User']['Resource']['land'];
		if($schoolRatio > 2)
			$schoolRatio = 2;
		if(($target['User']['Army']['wizard'] > 0) || ($target['User']['Army']['wizard_resting'] > 0)) {
			$wizardRatio = $wizards / $target['User']['Resource']['land'] / 8;
			if($wizardRatio > 2)
				$wizardRatio = 2;
		}
		else
			$wizardRatio = 2;
		$successChance = $this->getMagicMultiplier() * $wizardRatio * $schoolRatio * $this->getPlayerLevel($user, $target) * $bonus - $resistance;
		$successChance = $this->getOffensiveSpellSuccess($user, $target, $wizards, 8);
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Famine spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['Spell']['famine'] = 15;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast Famine on your kingdom. Your population will decrease for 15 hours.";
			Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			// Spell Retaliation
			$this->createSpellRetal($target['User']['id'], $user['User']['id']);
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " unable to successfully cast the Famine spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting a Famine spell on your kingdom.";
			if($this->isReflected($target)){
				$result .= "<br />Unfortunately the kingdom we attempted to cast Famine on was able to reflect the spell into our territory. Our kingdom will now suffer its unfortunate effects. <br />";
				$user['Spell']['famine'] = 8;
				$report .= "<br /><br />Fortunately, our wizards were able to reflect the spell back onto their lands.<br />";
				$this->id = $user['Spell']['id'];
				$this->save($user);
			}
			
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;	
	}
	
	function castEarthquake($user = null, $target = null, $wizards = null, $self = null){
		
		$bonus = $this->getMagicBonus($user);
		$resistance = $this->getMagicResistance($target);
		
		$schoolRatio = $user['User']['Building']['school'] / $target['User']['Resource']['land'];
		if(($target['User']['Army']['wizard'] > 0) || ($target['User']['Army']['wizard_resting'] > 0)) {
			$wizardRatio = $wizards / ($target['User']['Army']['wizard'] + ($target['User']['Army']['wizard_resting'] / 2));
			if($wizardRatio > 2)
				$wizardRatio = 2;
		}
		else
			$wizardRatio = 2;
		$successChance = $this->getMagicMultiplier() * $wizardRatio * $schoolRatio * $this->getPlayerLevel($user, $target) * $bonus - $resistance;
		$successChance = $this->getOffensiveSpellSuccess($user, $target, $wizards, 1);
		$randomChance = mt_rand(1, 100);
		if ($successChance > 100)
			$successChance = 100;
		if(($successChance * 100) > $randomChance) {
			if($successChance > 1)
				$successChance = 1;
			$buildingsToKill = floor($successChance * $wizards/mt_rand(20, 200) );
			
			$building[0]['type'] = "farm";
			$building[0]['amount'] = $target['User']['Building']['farm'];
			$building[0]['percent_dieing'] = 0;
			$building[1]['type'] = "mill";
			$building[1]['amount'] = $target['User']['Building']['mill'];
			$building[1]['percent_dieing'] = 0;
			$building[2]['type'] = "mine";
			$building[2]['amount'] = $target['User']['Building']['mine'];
			$building[2]['percent_dieing'] = 0;
			$building[3]['type'] = "house";
			$building[3]['amount'] = $target['User']['Building']['house'];
			$building[3]['percent_dieing'] = 0;
			$building[4]['type'] = "forge";
			$building[4]['amount'] = $target['User']['Building']['forge'];
			$building[4]['percent_dieing'] = 0;
			$building[5]['type'] = "market";
			$building[5]['amount'] = $target['User']['Building']['market'];
			$building[5]['percent_dieing'] = 0;
			$building[6]['type'] = "school";
			$building[6]['amount'] = $target['User']['Building']['school'];
			$building[6]['percent_dieing'] = 0;
			$building[7]['type'] = "medical_center";
			$building[7]['amount'] = $target['User']['Building']['medical_center'];
			$building[7]['percent_dieing'] = 0;
			$building[8]['type'] = "training_center";
			$building[8]['amount'] = $target['User']['Building']['training_center'];
			$building[8]['percent_dieing'] = 0;
			$building[9]['type'] = "advanced_training_center";
			$building[9]['amount'] = $target['User']['Building']['advanced_training_center'];
			$building[9]['percent_dieing'] = 0;
			$building[10]['type'] = "guard_house";
			$building[10]['amount'] = $target['User']['Building']['guard_house'];
			$building[10]['percent_dieing'] = 0;
			$building[11]['type'] = "guard_tower";
			$building[11]['amount'] = $target['User']['Building']['guard_tower'];
			$building[11]['percent_dieing'] = 0;
			
			$total = 0;
			for ($i = 0; $i < 12; $i++){
				$total += $building[$i]['amount'];
			}
				
			if(.3*$total < $buildingsToKill)
				$buildingsToKill = floor(.3*$total);
			$buildingsDead = 0;
			foreach($building as $type){
				$category = $type['type'];
				if($total > 0)
					$type['percent_dieing'] = $type['amount'] / $total;
				else
					$type['percent_dieing'] = 0;
				//echo "Percent dieing: " . $type['percent_dieing'] . "<br />";
				$type['amount'] -= floor($buildingsToKill * $type['percent_dieing']);
				$buildingsDead += floor($buildingsToKill * $type['percent_dieing']);
				if($type['amount'] < 0)
					$type['amount'] = 0;
				$target['User']['Building'][$category] = $type['amount'];
			}

			//$target['User']['Building']['total_population'] -= $buildingsToKill;
			
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Earthquake spell, brutally destroying " . number_format($buildingsDead) . " of " . $this->getTargetName($target['User']['id']) . "'s total buildings. <br />";

			$this->User->Building->id = $target['User']['Building']['id'];
			$this->User->Building->save($target['User']['Building']);
									
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast an Earthquake spell on your kingdom, destroying " . number_format($buildingsDead) . " of your buildings";
			Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			// Spell Retaliation
			$this->createSpellRetal($target['User']['id'], $user['User']['id']);
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Earthquake spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting a Earthquake spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;	
	}
	
	function castFirewall($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		//you should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/10;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Firewall spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['Spell']['firewall'] = 15;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast Firewall on your kingdom. Your land will be protected by a wall of fire for 15 days.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " unable to successfully cast the Firewall spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting Firewall spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function selectGate($user = null){
		
	}
	
	function castGate($user = null, $wizards = null, $attackId = null){
		$bonus = $this->getMagicBonus($user, $self);
		//You should have 10 wizards per land
		
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/10;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		$successChance = ($wizardsPerLand * $schoolsPerLand) + $bonus;
		
		$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Gate spell. <br />";
		
	}
		
	function castGodBlessing($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user, $self);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/15;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the God Blessing spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$target['Spell']['god_blessing'] = 12;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast God Blessing spell on your kingdom. Your population will see an increase in productivity, thereby generating more income, for the next 12 days.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the God Blessing spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting God Blessing spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castIceStorm($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		$resistance = $this->getMagicResistance($target);
		
		$schoolRatio = $user['User']['Building']['school'] / $target['User']['Resource']['land'];
		if($schoolRatio > 2)
			$schoolRatio = 2;
		if(($target['User']['Army']['wizard'] > 0) || ($target['User']['Army']['wizard_resting'] > 0)) {
			$wizardRatio = $wizards / $target['User']['Resource']['land'] / 8;
			if($wizardRatio > 2)
				$wizardRatio = 2;
		}
		else
			$wizardRatio = 2;
		//if($wizardRatio > 1)
		//	$wizardRatio = 1;
		$successChance = $this->getMagicMultiplier()*$wizardRatio * $schoolRatio * $this->getPlayerLevel($user, $target) * $bonus - $resistance;
		$successChance = $this->getOffensiveSpellSuccess($user, $target, $wizards, 1);
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			
			
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Ice Storm spell, creating intense blizzard that will rest over " 
			. $this->getTargetName($target['User']['id']) . "'s entire kingdom. They will see no food production during this time! <br />";

			$target['Spell']['ice_storm'] = 5;
			$this->id = $target['Spell']['id'];
			$this->save($target);
									
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast an Ice Storm spell on your kingdom. Your food production has dropped to 0 due to a huge blizzard resting over your kingdom.";
			Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			// Spell Retaliation
			$this->createSpellRetal($target['User']['id'], $user['User']['id']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Ice Storm spell on " . $this->getTargetName($target['User']['id']) . ". <br />";
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting a Ice Storm spell on your kingdom.";
			if($this->isReflected($target)){
				$result .= "<br />Unfortunately the kingdom we attempted to cast Ice Storm on was able to reflect the spell into our territory. Our kingdom will now suffer its unfortunate effects. <br />";
				$user['Spell']['ice_storm'] = 3;
				$report .= "<br /><br />Fortunately, our wizards were able to reflect the spell back onto their lands.<br />";
				$this->id = $user['Spell']['id'];
				$this->save($user);
			}
			
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			
		}
		
		$user['User']['Army']['wizard_resting'] += $wizards;
		$user['User']['Army']['wizard'] -= $wizards;
		$this->User->Army->id = $user['User']['Army']['id'];
		$this->User->Army->save($user['User']['Army']);
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;	
	}
	
	function castShadowHall($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/15;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Shadow Hall spell. Your kingdom will be hidden from your enemies for 12 days.<br />";
			
			$target['Spell']['shadow_hall'] = 12;
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$report = $this->getTargetName($user['User']['id']) .  
				" successfully cast the Shadow Hall spell on your kingdom. Your kingdom will be hidden from your enemies for the next 12 days.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Success Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Shadow Hall spell. <br />";
			
			$report = $this->getTargetName($user['User']['id']) .  
				" was unsuccessful in casting Shadow Hall spell on your kingdom.";
			if($user['User']['id'] != $target['User']['id'])
				Classregistry::init('Report')->makeNewReport($target['User']['id'], $report, "Fail Spell", $this->ipAddress, $user['User']['id'], $this->getTargetName($user['User']['id'], false) . " cast a spell on you.");
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castEnchantedWeapons($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/3;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Weapon Enchantment spell. Your soldiers that use close range weapons will receive an extra benefit from the enchanted blades.<br />";
			
			$target['Spell']['enchanted_weapons'] = 12;
			if($target['Spell']['fiery_arrows'] > $target['Spell']['hardened_armor']){
				$target['Spell']['hardened_armor'] = 0;
			} else {
				$target['Spell']['fiery_arrows'] = 0;
			}
			if($target['User']['Army']['strategy'] == 'Mystics'){
				$target['Spell']['hardened_armor'] = 0;
				$target['Spell']['fiery_arrows'] = 0;
			}
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Weapon Enchantment spell. <br />";
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castFieryArrows($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/3;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Fiery Arrows spell, igniting your arrows with magical flames.<br />";
			
			$target['Spell']['fiery_arrows'] = 12;
			if($target['Spell']['enchanted_weapons'] > $target['Spell']['hardened_armor']){
				$target['Spell']['hardened_armor'] = 0;
			} else {
				$target['Spell']['enchanted_weapons'] = 0;
			}
			if($target['User']['Army']['strategy'] == 'Mystics'){
				$target['Spell']['hardened_armor'] = 0;
				$target['Spell']['enchanted_weapons'] = 0;
			}
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Fiery Arrows spell. <br />";
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	function castHardenedArmor($user = null, $target = null, $wizards = null, $self = null){
		$bonus = $this->getMagicBonus($user);
		//You should have 10 wizards per land
		$wizardsPerLand = ($wizards / ($target['User']['Resource']['land']))/3;
		//you should have 1 school per land
		$schoolsPerLand = (($user['User']['Building']['school'] / $target['User']['Resource']['land']));
		//PS this would give you 10 wizards per school, so no need to calc wizards per school
		
		$successChance = $this->getMagicMultiplier() * ($wizardsPerLand * $schoolsPerLand) * $bonus;
		$randomChance = mt_rand(1, 100);
		if(($successChance * 100) > $randomChance) {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " successfully cast the Hardened Armor spell. Your soldiers will hopefully live longer and thank you for bestowing the blessing upon them.<br />";
			
			$target['Spell']['hardened_armor'] = 12;
			if($target['Spell']['enchanted_weapons'] > $target['Spell']['fiery_arrows']){
				$target['Spell']['fiery_arrows'] = 0;
			} else {
				$target['Spell']['enchanted_weapons'] = 0;
			}
			if($target['User']['Army']['strategy'] == 'Mystics'){
				$target['Spell']['fiery_arrows'] = 0;
				$target['Spell']['enchanted_weapons'] = 0;
			}
			$this->id = $target['Spell']['id'];
			$this->save($target);
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		else {
			$result = "Your " . $this->pluralize('wizard', $wizards) . " were unable to successfully cast the Hardened Armor spell. <br />";
			
			$user['User']['Army']['wizard_resting'] += $wizards;
			$user['User']['Army']['wizard'] -= $wizards;
			$this->User->Army->id = $user['User']['Army']['id'];
			$this->User->Army->save($user['User']['Army']);
		}
		
		if($successChance < .5)
			$result .= "<br />Your chance of casting the spell was below 50%, try using more wizards or building more schools.<br />";
		Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, $self, $this->ipAddress, $user['User']['id'], "You cast a spell on " . $this->getTargetName($target['User']['id'], false) . ".");
		return $result;
	}
	
	/*
	 * Casts a $spell on $target and redirects to the results page with parameter $result, and $spell
	 */
	function castSpell($user = null, $target = null, $spell = null, $wizards = null, $ipAddress = null) {
		$target = $this->find('first', array('conditions' => array('User.id' => $target)));
		if($target['User']['id'] == $user['User']['id']){
			$self = "Self Spell";
		} else {
			$self = "Spell";
		}
		$this->ipAddress = $ipAddress;
		if($ipAddress == null){
			$this->ipAddress = 0;
		}
		switch($spell) {
			case "growing_crop":
				$result = $this->castGrowingCrop($user, $target, $wizards, $self);
				break;
			case "natural_growth":
				$result = $this->castNaturalGrowth($user, $target, $wizards, $self);
				break;
			case "gold_to_iron":
				if($user['User']['id'] == $target['User']['id'])
					$result = $this->castGoldToIron($user, $target, $wizards, $self);
				else 
					$result = "You cannot cast this type of spell on other kingdoms.";
				break;
			case "fireball":
				if($user['User']['id'] == $target['User']['id'] || $user['User']['alliance_id'] == $target['User']['alliance_id'])
					$result = "You cannot cast this type of spell on yourself or your alliance.";
				else
					$result = $this->castFireball($user, $target, $wizards, $self);
				break;
			case "blessing":
				$result = $this->castBlessing($user, $target, $wizards, $self);
				break;
			case "truesight":
				if($user['User']['id'] == $target['User']['id'] || $user['User']['alliance_id'] == $target['User']['alliance_id'])
					$result = "You cannot cast this type of spell on yourself.";
				else
					$result = $this->castTruesight($user, $target, $wizards, $self);
				break;
			///New Spells
			case "wood_to_food":
				if($user['User']['id'] == $target['User']['id'])
					$result = $this->castWoodToFood($user, $target, $wizards, $self);
				else 
					$result = "You cannot cast this type of spell on other kingdoms.";
				break;
			case "farseeing":
				$result = $this->castFarseeing($user, $target, $wizards, $self);
				break;
			case "freeze":
				if($user['User']['id'] != $target['User']['id'] || $user['User']['alliance_id'] != $target['User']['alliance_id'])
					$result = $this->castFreeze($user, $target, $wizards);
				else
					$result = "You cannot cast this type of spell on yourself or your alliance.";
				break;
			case "famine":
				if($user['User']['id'] == $target['User']['id'] || $user['User']['alliance_id'] == $target['User']['alliance_id'])
					$result = "You cannot cast this type of spell on yourself or your alliance.";
				else
					$result = $this->castFamine($user, $target, $wizards, $self);
				break;
			case "earthquake":
				if($user['User']['id'] == $target['User']['id'] || $user['User']['alliance_id'] == $target['User']['alliance_id'])
					$result = "You cannot cast this type of spell on yourself or your alliance.";
				else
					$result = $this->castEarthquake($user, $target, $wizards, $self);
				break;
			case "firewall":
				$result = $this->castFirewall($user, $target, $wizards, $self);
				break;
			case "gate":
				if($user['User']['id'] == $target['User']['id'])
					$result = $this->selectGate($user, $target, $wizards, $self);
				else
					$result = "You cannot cast this type of spell on other kingdoms.";
				break;
			case "ice_storm":
				if($user['User']['id'] == $target['User']['id'] || $user['User']['alliance_id'] == $target['User']['alliance_id'])
					$result = "You cannot cast this type of spell on yourself or your alliance.";
				else
					$result = $this->castIceStorm($user, $target, $wizards, $self);
				break;
			case "shadow_hall":
				if($user['User']['id'] == $target['User']['id'])
					$result = $this->castShadowHall($user, $target, $wizards, $self);
				else
					$result = "You cannot cast this type of spell on other kingdoms.";
				break;
			case "god_blessing":
				if($user['User']['id'] == $target['User']['id'])
					$result = $this->castGodBlessing($user, $target, $wizards, $self);
				else
					$result = "You cannot cast this type of spell on other kingdoms.";
				break;
			case "enchanted_weapons":
				if($user['User']['id'] == $target['User']['id'])
					$result = $this->castEnchantedWeapons($user, $target, $wizards, $self);
				else
					$result = "You cannot cast this type of spell on other kingdoms.";
				break;
			case "fiery_arrows":
				if($user['User']['id'] == $target['User']['id'])
					$result = $this->castFieryArrows($user, $target, $wizards, $self);
				else
					$result = "You cannot cast this type of spell on other kingdoms.";
				break;
			case "hardened_armor":
				if($user['User']['id'] == $target['User']['id'])
					$result = $this->castHardenedArmor($user, $target, $wizards, $self);
				else
					$result = "You cannot cast this type of spell on other kingdoms.";
				break;
			default:
				break;
		}

		return $result;
	}

	function castSchedules($user) {
		$schedules = Classregistry::init('MagicSchedule')->find(
			'all', 
			array(
				'conditions' => array(
					'MagicSchedule.user_id' => $user['User']['id']
				), 
				'order' => 'MagicSchedule.order ASC'
			)
		);
		if(!empty($schedules)){
			$newUser['User'] = $user['User'];
			$newUser['User']['Army'] = $user['Army'];
			$newUser['User']['Building'] = $user['Building'];
			$newUser['User']['Resource'] = $user['Resource'];
			$newUser['User']['Technology'] = $user['Technology'];
			$error = "";
			$this->recursive = 2;
			$spellList = $this->getSpellList($newUser);
			foreach($schedules as &$schedule):
				if(--$schedule['MagicSchedule']['current'] == 0){
					if($schedule['MagicSchedule']['wizards'] <= $newUser['User']['Army']['wizard']) {
						$this->castSpell($newUser, $schedule['MagicSchedule']['target_id'], $schedule['MagicSchedule']['spell'], $schedule['MagicSchedule']['wizards']);
						$newUser['User']['Army']['wizard'] -= $schedule['MagicSchedule']['wizards'];
						$newUser['User']['Army']['wizard_resting'] += $schedule['MagicSchedule']['wizards'];
					}
					else {
						$spell = $schedule['MagicSchedule']['spell'];
						$error .= "You did not have enough wizards to cast " . $spellList[$spell] . " on " . Classregistry::init('User')->getName($schedule['MagicSchedule']['target_id']) . ". <br />";
					}
					$schedule['MagicSchedule']['current'] = $schedule['MagicSchedule']['frequency'];
				}
			endforeach;
			
			if($error != "")
				Classregistry::init('Report')->makeNewReport($user['User']['id'], $error, "Self Spell", 0, $user['User']['id'], "There was an error casting a spell on schedule.");
				
			Classregistry::init('MagicSchedule')->saveAll($schedules);
		}
		
	}

}
?>