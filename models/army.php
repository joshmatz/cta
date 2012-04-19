<?php
class Army extends AppModel {    
	var $name = 'Army';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id'
		)
	);
	
	
	/*
	 * $s == word
	 * $c == count
	 */
	function pluralize($s) {
        return Inflector::pluralize($s);
    }
    
    function getDefaultStats() {
    	$this->Attack = Classregistry::init('Attack');
    	
    	$default['User']['Technology']['racial'] = 0;
        $default['User']['Technology']['tree_1'] = 0;
        $default['User']['Technology']['tree_2'] = 0;
        $default['User']['Technology']['tree_3'] = 0;
        $default['User']['Technology']['tree_4'] = 0;
        $default['User']['Technology']['light_armor'] = 0;
        $default['User']['Technology']['heavy_armor'] = 0;
        $default['User']['Technology']['blade'] = 0;
        $default['User']['Technology']['range'] = 0;
        $default['User']['Army']['strategy'] = "None";
        $default['User']['Spell']['firewall'] = 0;
        $default['User']['Spell']['fiery_arrows'] = 0;
        $default['User']['Spell']['enchanted_weapons'] = 0;
        $default['User']['Spell']['hardened_armor'] = 0;
        $default['User']['race'] = "all";
        $default['Technology']['racial'] = 0;
        $default['Technology']['tree_1'] = 0;
        $default['Technology']['tree_2'] = 0;
        $default['Technology']['tree_3'] = 0;
        $default['Technology']['tree_4'] = 0;
        $default['Technology']['light_armor'] = 0;
        $default['Technology']['heavy_armor'] = 0;
        $default['Technology']['blade'] = 0;
        $default['Technology']['range'] = 0;
        $default['Army']['strategy'] = "None";
        $default['Spell']['firewall'] = 0;
        $default['Spell']['fiery_arrows'] = 0;
        $default['Spell']['enchanted_weapons'] = 0;
        $default['Spell']['hardened_armor'] = 0;
        $default['race'] = "all";
    	$sentUnits['Attack']['standard'] = 0;
        $sentUnits['Attack']['weak'] = 0;
        $sentUnits['Attack']['small_elite'] = 0;
        $sentUnits['Attack']['big_elite'] = 0;
        $sentUnits['Attack']['range'] = 0;
        $sentUnits['Attack']['mount'] = 0;
        
		return $this->Attack->getUnitArray(0, $sentUnits, 0, 1, 100, false, $default, false);
    }
        
    function healCenterUnits($user = null, $default = null, $save = true) {
   		$Center = Classregistry::init('Center');
		if($Center->checkCenter($user['User']['id'])) {
			$center = $Center->getCenterUnits($user['User']['id']);
			
			// Having to do this to calc units healed for the army screen. Unsset when done.
			if(!isset($user['Technology'])) {
				$user['Technology']['tree_1'] = $user['User']['Technology']['tree_1'];
				$user['Building']['medical_center'] = $user['User']['Building']['medical_center'];
				$user['Building']['guard_house'] = $user['User']['Building']['medical_center'];
			}
			
			// random numbers for now, using the med center tech
			$medCenterRate = 15;
			$guardHouseRate = 8;
			if($user['Technology']['tree_1'] > 3) {
				$medCenterRate = 25;
				$guardHouseRate = 15;
			}
			
			$totalDefenseHeals = ($medCenterRate * $user['Building']['medical_center']) + ($guardHouseRate * $user['Building']['guard_house']);
			$totalDefenseHeals += ($totalDefenseHeals * ($user['User']['loyalty'] / 100));
			$healsLeft = $totalDefenseHeals;
			
			$bigEliteHeals = 0;
			$smallEliteHeals = 0;
			$weakHeals = 0;
			$standardHeals = 0;
			$rangeHeals = 0;
			$mountHeals = 0;
			
			$totalCenterUnits = $Center->getTotalCenterUnits($center, $user['User']['id']);
			$totalCenterDefense = $Center->getTotalCenterDefense($center, $default, $user['User']['id']);
			
			/*
			 * This is wrong. But I still want it.
			if($totalCenterDefense > 0) {
				$bigElitePercent = ($center['Center']['big_elite'] * $default[1]['defense']) / $totalCenterDefense;
				$smallElitePercent = ($center['Center']['small_elite'] * $default[0]['defense']) / $totalCenterDefense;
				$weakPercent = ($center['Center']['weak'] * $default[2]['defense']) / $totalCenterDefense;
				$standardPercent = ($center['Center']['standard'] * $default[3]['defense']) / $totalCenterDefense;
				$rangePercent = ($center['Center']['range'] * $default[4]['defense']) / $totalCenterDefense;
				$mountPercent = ($center['Center']['mount'] * $default[5]['defense']) / $totalCenterDefense;
				if($user['User']['id'] == 1008){
					echo $bigElitePercent ."<br />";
					echo $smallElitePercent ."<br />";
					echo $weakPercent ."<br />";
					echo $standardPercent ."<br />";
					echo $rangePercent ."<br />";
					echo $mountPercent ."<br />";
				}
			} else {
				return $user;
			} */
			if($totalCenterUnits > 0) {
				$bigElitePercent = $center['Center']['big_elite'] / $totalCenterUnits;
				$smallElitePercent = $center['Center']['small_elite'] / $totalCenterUnits;
				$weakPercent = $center['Center']['weak'] / $totalCenterUnits;
				$standardPercent = $center['Center']['standard'] / $totalCenterUnits;
				$rangePercent = $center['Center']['range'] / $totalCenterUnits;
				$mountPercent = $center['Center']['mount'] / $totalCenterUnits;
				if($user['User']['id'] == 1008){
					echo $bigElitePercent ."<br />";
					echo $smallElitePercent ."<br />";
					echo $weakPercent ."<br />";
					echo $standardPercent ."<br />";
					echo $rangePercent ."<br />";
					echo $mountPercent ."<br />";
				}
			} else {
				return $user;
			}
			
			$bigEliteHeals = round(($bigElitePercent * $totalDefenseHeals) / $default[1]['defense']);
			$smallEliteHeals = round(($smallElitePercent * $totalDefenseHeals) / $default[0]['defense']);
			$weakHeals = round(($weakPercent * $totalDefenseHeals) / $default[2]['defense']);
			$standardHeals = round(($standardPercent * $totalDefenseHeals) / $default[3]['defense']);
			$rangeHeals = round(($rangePercent * $totalDefenseHeals) / $default[4]['defense']);
			$mountHeals = round(($mountPercent * $totalDefenseHeals) / $default[5]['defense']);
			
			if($bigEliteHeals > $center['Center']['big_elite']){
				$bigEliteHeals = $center['Center']['big_elite'];
			}
			if($smallEliteHeals > $center['Center']['small_elite']){
				$smallEliteHeals = $center['Center']['small_elite'];
			}
			if($weakHeals > $center['Center']['weak']){
				$weakHeals = $center['Center']['weak'];
			}
			if($standardHeals > $center['Center']['standard']){
				$standardHeals = $center['Center']['standard'];
			}
			if($rangeHeals > $center['Center']['range']){
				$rangeHeals = $center['Center']['range'];
			}
			if($mountHeals > $center['Center']['mount']){
				$mountHeals = $center['Center']['mount'];
			}
			
			if($save){
				$center = $Center->subtractCenterUnits($user['User']['id'], $bigEliteHeals, $smallEliteHeals, $weakHeals, $standardHeals, $rangeHeals, $mountHeals);
			}
			if(!empty($center)) {
				$user['Army']['big_elite'] += $bigEliteHeals;
				$user['Army']['small_elite'] += $smallEliteHeals;
				$user['Army']['weak'] += $weakHeals;
				$user['Army']['standard'] += $standardHeals;
				$user['Army']['range'] += $rangeHeals;
				$user['Army']['mount'] += $mountHeals;
			}
			if(!isset($user['Technology']['tree_2'])) {
				unset($user['Technology']['tree_1']);
				unset($user['Building']['medical_center']);
				unset($user['Building']['guard_house']);
			}
			
		} else {
			// error
		}
		
		return $user;
    }
    
    function killUnitsNoRoom($user = null){
    	$totalArmyHousing = $this->getTotalArmyHousing($user);
    	
    	$totalUnits = 0;
   		if($user['User']['race'] != "Gnome") {
			$totalUnits += $user['Army']['weak'] + $user['Army']['standard'] + $user['Army']['small_elite'] + $user['Army']['big_elite']
			+ $user['Army']['range'] + $user['Army']['mount'];
			 
			 // Training
			 // $totalUnits += $user['Army']['weak_training'] + $user['Army']['standard_training'] + ($user['Army']['small_elite_training']) + ($user['Army']['big_elite_training'])
			 // + $user['Army']['range_training'] + $user['Army']['mount_training'];
		}
		else {
			 $totalUnits += $user['Army']['weak'] + $user['Army']['standard'] + ($user['Army']['small_elite']) + ($user['Army']['big_elite'])
			 + $user['Army']['range'] + $user['Army']['mount'];
			 // Training
			 // $totalUnits += $user['Army']['weak_training'] + $user['Army']['standard_training'] + ($user['Army']['small_elite_training'] * 4) + ($user['Army']['big_elite_training'] * 5)
			 // + $user['Army']['range_training'] + $user['Army']['mount_training'];
		}
		
		if($totalUnits > 0){
			$weakPercent = $user['Army']['weak'] / $totalUnits;
			$standardPercent = $user['Army']['standard'] / $totalUnits;
			$mountPercent = $user['Army']['mount'] / $totalUnits;
			$rangePercent = $user['Army']['range'] / $totalUnits;
	    	
			if($user['User']['race'] != "Gnome") {
				$smallElitePercent = $user['Army']['small_elite'] / $totalUnits;
				$bigElitePercent = $user['Army']['big_elite'] / $totalUnits;
			} else {
				$smallElitePercent = ($user['Army']['small_elite'] * 8) / $totalUnits;
				$bigElitePercent = ($user['Army']['big_elite'] * 10) / $totalUnits;
			}
		}
		
		if($totalUnits > $totalArmyHousing){
			$user['Army']['weak'] = floor($user['Army']['weak'] * ($totalArmyHousing / $totalUnits));
			$user['Army']['standard'] = floor($user['Army']['standard'] * ($totalArmyHousing / $totalUnits));
			$user['Army']['mount'] = floor($user['Army']['mount'] * ($totalArmyHousing / $totalUnits));
			$user['Army']['range'] = floor($user['Army']['range'] * ($totalArmyHousing / $totalUnits));
			$user['Army']['small_elite'] = floor($user['Army']['small_elite'] * ($totalArmyHousing / $totalUnits));
			$user['Army']['big_elite'] = floor($user['Army']['big_elite'] * ($totalArmyHousing / $totalUnits));
		}
		
		$totalWizardHousing = $this->getTotalWizardHousing($user);
		$totalWizards = $user['Army']['wizard'] + $user['Army']['wizard_resting'];
		
		if($totalWizards > $totalWizardHousing){
			$user['Army']['wizard'] = floor($user['Army']['wizard'] * ($totalWizardHousing / $totalWizards));
			$user['Army']['wizard_resting'] = floor($user['Army']['wizard_resting'] * ($totalWizardHousing / $totalWizards));
		}
    	
    	return $user;
    }
    
    function getTotalUnitsAway($user = null){
    	$awayUnits = 0;
		if($units = $this->User->Attack->find('all', array('conditions' => array('Attack.user_id' => $user['User']['id'])))) {
			foreach($units as $unit):
				$awayUnits += $unit['Attack']['weak'];
				$awayUnits += $unit['Attack']['standard'];
				$awayUnits += $unit['Attack']['range'];
				$awayUnits += $unit['Attack']['mount'];
				if($user['User']['race'] != 'Gnome') {
					$awayUnits += $unit['Attack']['small_elite'];
					$awayUnits += $unit['Attack']['big_elite'];
				}
				else {
					$awayUnits += $unit['Attack']['small_elite'] * 8;
					$awayUnits += $unit['Attack']['big_elite'] * 10;
				}
			endforeach;
		}
		return $awayUnits;
    }
    
    function getWizardPrice($race = null){
    		switch($race){
			case "Orc":
				$wizardCost = 300;
				break;
			case "Gnome":
				$wizardCost = 450;
				break;
			case "Dwarf":
				$wizardCost = 500;
				break;
			case "Elf":
				$wizardCost = 225;
				break;
			case "Human":
				$wizardCost = 275;
				break;
			default:
				echo "Error accessing database.";
				break;
		}
		
		return $wizardCost;
    }
    
    function getUnitPrices($user = null){
    	switch($user['User']['race']){
			case "Orc":
				// Price is array that holds each unit as an array
				// Second array holds a number corresponding to each weapon and gold
				// Example:
				// $price = array(array(1, 0, 1, 0, 0, 0, 45))
				// Accessing $price[0][0] for an Orc tells you it costs 1 Wolf, damn this is complicated
				// Accessing $price[6][0] for an Orc tells you how many Wolves the user currently has.
				// Arrays are in the same order as the units/weapons listed above.
		    	$price = array(array(1, 0, 1, 0, 0, 0, 45), array(1, 1, 0, 1, 0, 0, 30), array(0,0,0,0,0,1,60), 
		    				   array(0,3,0,0,1,0,150),array(1,1,0,1,0,0,400), array(0,1,0,0,1,0,900),
		    				   array($user['User']['Weapon']['vehicle'],$user['User']['Weapon']['big_melee'],$user['User']['Weapon']['small_melee'],
		    				   		 $user['User']['Weapon']['big_armor'],$user['User']['Weapon']['small_armor'],$user['User']['Weapon']['range'],
		    				   		 $user['User']['Resource']['gold']));
				break;
			case "Gnome":
		    	$price = array(array(0, 0, 1, 0, 0, 0, 90), array(0, 0, 1, 0, 1, 0, 75), array(0, 0, 0, 0, 0, 1, 100), 
		    				   array(1,0,1,1,0,0,450),array(0,7,0,0,0,0,1900), array(0,10,0,0,0,0,2500),
		    				   array($user['User']['Weapon']['vehicle'],$user['User']['Weapon']['big_melee'],$user['User']['Weapon']['small_melee'],
		    				   		 $user['User']['Weapon']['big_armor'],$user['User']['Weapon']['small_armor'],$user['User']['Weapon']['range'],
		    				   		 $user['User']['Resource']['gold']));
				break;
			case "Dwarf":
		    	$price = array(array(0, 0, 1, 1, 0, 0, 375), array(0, 1, 0, 1, 0, 0, 775), array(0,0,0,0,1,1,275), 
		    				   array(1,0,0,0,1,0,525),array(0,2,0,0,1,0,375), array(0,0,1,1,0,0,675),
		    				   array($user['User']['Weapon']['vehicle'],$user['User']['Weapon']['big_melee'],$user['User']['Weapon']['small_melee'],
		    				   		 $user['User']['Weapon']['big_armor'],$user['User']['Weapon']['small_armor'],$user['User']['Weapon']['range'],
		    				   		 $user['User']['Resource']['gold']));
				break;
			case "Elf":
		    	$price = array(array(0, 0, 1, 0, 1, 0, 100), array(0, 1, 0, 0, 1, 0, 100), array(0,0,0,0,0,1,350), 
		    				   array(0,0,0,0,0,2,600),array(1,1,1,1,0,0,450), array(0,1,0,1,0,0,800),
		    				   array($user['User']['Weapon']['vehicle'],$user['User']['Weapon']['big_melee'],$user['User']['Weapon']['small_melee'],
		    				   		 $user['User']['Weapon']['big_armor'],$user['User']['Weapon']['small_armor'],$user['User']['Weapon']['range'],
		    				   		 $user['User']['Resource']['gold']));
				break;
			case "Human":
		    	$price = array(array(0, 0, 1, 0, 1, 0, 150), array(0, 1, 0, 0, 1, 0, 100), array(0,0,0,0,1,1,100), 
		    				   array(1,1,0,0,1,1,300),array(0,2,0,1,0,0,450), array(1,2,0,1,0,0,500),
		    				   array($user['User']['Weapon']['vehicle'],$user['User']['Weapon']['big_melee'],$user['User']['Weapon']['small_melee'],
		    				   		 $user['User']['Weapon']['big_armor'],$user['User']['Weapon']['small_armor'],$user['User']['Weapon']['range'],
		    				   		 $user['User']['Resource']['gold']));
				break;
			default:
				echo "Error accessing database.";
				break;
		}
		
		return $price;
    }
    
    function getWeaponNames($race = null){
    		switch($race){
			case "Orc":
				$weapons = array("Wolf", "Axe", "Spear", "Enforced Leather Armor", "Leather Armor", "Sling", "Gold");
				break;
			case "Gnome":
				$weapons = array("Pony", "Plank and Nails", "Spear", "Chainmail", "Leather Armor", "Sling", "Gold");
				break;
			case "Dwarf":
				$weapons = array("Battle Axe", "Axe", "Hammer", "Plate Armor", "Chainmail", "Crossbow", "Gold");
				break;
			case "Elf":
				$weapons = array("Horse", "Sword", "Lance", "Courageous Armor", "Light Armor", "Bow", "Gold");
				break;
			case "Human":
				$weapons = array("Horse", "Sword", "Spear", "Plate Armor", "Light Armor", "Bow", "Gold");
				break;
			default:
				echo "Error accessing database.";
				break;
		}
		
		return $weapons;
    }
    
	function getUnitsTrained($user = null){
		$units = $this->getFullArmyData($user);
		
		$tech = 1;
		if($user['Technology']['tree_2'] > 5)
			$tech = 1.25;
		
		foreach($units as $unit):
			$type = $unit['type'];
			$typeTraining = $type . "_training";
			if($unit['type'] == "wizard") {
				$trained = floor($user['Building']['school'] / $unit['training_centers_needed']) + $user['Building']['castle'];
				$unitsTrained['Army'][$type] = $trained;
			}
			elseif($unit['training_center']) {
				$trained = floor(($user['Building']['training_center'] * $tech) / $unit['training_centers_needed']) + $user['Building']['castle'];
				$unitsTrained['Army'][$type] = $trained;
			}
			else {
				$trained = floor(($user['Building']['advanced_training_center'] * $tech) / $unit['training_centers_needed']) + $user['Building']['castle'];
				$unitsTrained['Army'][$type] = $trained;	
			}
		endforeach;
		
		return $unitsTrained;
	}
    
    function getArmyPoints($user = null){
    	$units = $this->getFullArmyData($user);
    	$Center = Classregistry::init('Center');
    	$center = $Center->getCenterUnits($user['User']['id']);
    	
    	$points = 0;
    	switch($user['User']['race']) {
    	case "Orc":
			$points += ($units[0]['amount'] * 1.4); // weak unit
	    	$points += ($units[1]['amount'] * 2.1); // standard unit
	    	$points += ($units[2]['amount'] * 3.8); // small elite
	    	$points += ($units[3]['amount'] * 5.3); // big elite
	    	$points += ($units[4]['amount'] * 1.8); // range
	    	$points += ($units[5]['amount'] * 3.3); // mount	
	    	$points += ($units[0]['amount_away'] * 1.4); // weak unit
	    	$points += ($units[1]['amount_away'] * 2.1); // standard unit
	    	$points += ($units[2]['amount_away'] * 3.8); // range unit -- small elite
	    	$points += ($units[3]['amount_away'] * 5.3); // big elite
	    	$points += ($units[4]['amount_away'] * 1.8); // range
	    	$points += ($units[5]['amount_away'] * 3.3); // mount	
	    	
	    	break;
		case "Gnome":
			$points += ($units[0]['amount'] * .7); // weak unit
	    	$points += ($units[1]['amount'] * 1.4); // standard unit
	    	$points += ($units[2]['amount'] * 12); // small elite
	    	$points += ($units[3]['amount'] * 16.5); // big elite
	    	$points += ($units[4]['amount'] * .9); // range
	    	$points += ($units[5]['amount'] * 3.6); // mount	
			$points += ($units[0]['amount_away'] * .7); // weak unit
	    	$points += ($units[1]['amount_away'] * 1.4); // standard unit
	    	$points += ($units[2]['amount_away'] * 12); // small elite
	    	$points += ($units[3]['amount_away'] * 16.5); // big elite
	    	$points += ($units[4]['amount_away'] * .9); // range
	    	$points += ($units[5]['amount_away'] * 3.6); // mount	
	    	
			break;
		case "Dwarf":
			$points += ($units[0]['amount'] * 4.2); // weak unit
	    	$points += ($units[1]['amount'] * 6); // standard unit
	    	$points += ($units[2]['amount'] * 3.5); // small elite
	    	$points += ($units[3]['amount'] * 8); // big elite
	    	$points += ($units[4]['amount'] * 2.7); // range
	    	$points += ($units[5]['amount'] * 3.6); // mount		
			$points += ($units[0]['amount_away'] * 4.2); // weak unit
	    	$points += ($units[1]['amount_away'] * 6); // standard unit
	    	$points += ($units[2]['amount_away'] * 3.5); // small elite
	    	$points += ($units[3]['amount_away'] * 8); // big elite
	    	$points += ($units[4]['amount_away'] * 2.7); // range
	    	$points += ($units[5]['amount_away'] * 3.6); // mount	

			break;
		case "Elf":
			$points += ($units[0]['amount'] * 2.1); // weak unit
	    	$points += ($units[1]['amount'] * 2.1); // standard unit
	    	$points += ($units[2]['amount'] * 9); // small elite
	    	$points += ($units[3]['amount'] * 7.7); // big elite
	    	$points += ($units[4]['amount'] * 2.4); // range
	    	$points += ($units[5]['amount'] * 3.9); // mount	
			$points += ($units[0]['amount_away'] * 2.1); // weak unit
	    	$points += ($units[1]['amount_away'] * 2.1); // standard unit
	    	$points += ($units[2]['amount_away'] * 9); // small elite
	    	$points += ($units[3]['amount_away'] * 7.7); // big elite
	    	$points += ($units[4]['amount_away'] * 2.4); // range
	    	$points += ($units[5]['amount_away'] * 3.9); // mount

			break;
		case "Human":
			$points += ($units[0]['amount'] * 2.5); // weak unit
	    	$points += ($units[1]['amount'] * 2.4); // standard unit
	    	$points += ($units[2]['amount'] * 4.5); // small elite
	    	$points += ($units[3]['amount'] * 6.3); // big elite
	    	$points += ($units[4]['amount'] * 2.1); // range
	    	$points += ($units[5]['amount'] * 4.2); // mount	
			$points += ($units[0]['amount_away'] * 2.5); // weak unit
	    	$points += ($units[1]['amount_away'] * 2.4); // standard unit
	    	$points += ($units[2]['amount_away'] * 4.5); // small elite
	    	$points += ($units[3]['amount_away'] * 6.3); // big elite
	    	$points += ($units[4]['amount_away'] * 2.1); // range
	    	$points += ($units[5]['amount_away'] * 4.2); // mount
	
			break;
		default:
			echo "Error accessing database.";
			break;
		}
    	$points += ($user['Army']['wizard'] * .5) + ($user['Army']['wizard_resting'] * .5);
    	$points *= ($user['Army']['bas'] / 100);
    	$points = round($points / 5);
    	return $points;
    }
	
	function getUnitNames($user = null){
		switch($user['User']['race']){
		case "Orc":
			$units = array("Slother", "Rusher", "Slinger", "Axe Thrower", "Wolf Master", "Shadow Warrior");
			break;
		case "Gnome":
			$units = array("Militia", "Infantry", "Rock Thrower", "Rider", "Ballista", "Catapult");
			break;
		case "Dwarf":
			$units = array("Hammer wielder", "Axeman", "Light Crossbowman", "Berserker", "Runner", "Shieldbearer");
			break;
		case "Elf":
			$units = array("Lanceman", "Swordsman", "Archer", "Elite Archer", "Courageous", "Mage");
			break;
		case "Human":
			$units = array("Pikeman", "Infantry", "Archer", "Mounted Archer", "Heavy Infantry", "Knight");
			break;
		default:
			echo "Error accessing database.";
			break;
		}
		
		return $units;
	}
	
	function getUnitInfo($user = null){
    switch($user['User']['race']){
      case "Orc":
         $units = array(
            "Type: Pike/Mounted
Upkeep: 2 Gold, 2 Food
Stats: 2 Defense, 2 Melee, X Short Range
Upgrade: Sharper Blade (+0.5 Melee, +0.5 Short Range)
Special: 1.5x damage versus infantry
Special2: 1.5x damage versus mounted
Special3: Bonus short range from Wolf Master. Up to 10 slother per wolf master for +2 short range.", 
            "Type: Infantry/Mounted
Upkeep: 4 Gold, 2 Food
Stats: 3 Defense, 2 Melee, X Short Range
Upgrade: Heavy Armor (+1 defense), Sharper Blade (+0.5 Melee, +0.5 Short Range)
Special: 1.5x damage versus infantry
Special2: Bonus short range from Wolf Master. Up to 5 rusher per wolf master for +4 short range.", 
            "Type: Archer
Upkeep: 2 Gold, 1 Food
Stats: 2 Defense, 3 Long Range
Upgrade: Range (+1 Long Range)", 
            "Type: None
Upkeep: 8 Gold, 3 Food
Stats: 4 Defense, 2 Melee, 2 Short range, 3 Long Range
Upgrade: Light Armor (+1 Defense), Sharper Blade (+1 Melee, +1 Short range, +1 Long Range)", 
            "Type: Mounted
Upkeep: 11 Gold, 4 Food
Stats: 6 Defense, 10 Melee
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+1 Melee)
Special: 1.5x damage versus infantry", 
            "Type: None
Upkeep: 12 Gold, 4 Food
Stats: 4 Defense, 3 Melee, 7 Short range
Upgrade: Light Armor (+1 Defense), Sharper Blade (+1 Melee, +1 short range), Cloaking (+0.15 Hide)
Special: Hides in melee and short range phase, thus evading 70% attacks minus 1% for each 1% of SW in the army.",
         "Upkeep: 3 Gold, 1 Food");
         break;
      case "Gnome":
         $units = array(
            "Type: Pike
Upkeep: 1 Gold, 1 Food
Stats: 1 Defense, 1 Melee
Upgrade: Sharper Blade (+1 Melee)
Special: 1.5x Damage vs Mounted", 
            "Type: Pike/Infantry
Upkeep: 4 Gold, 1 Food
Stats: 2 Defense, 2 Melee
Upgrade: Sharper Blade (+1 melee), Light Armor (+1 defense)
Special: 1.5x Damage vs Mounted", 
            "Type: Archer
Upkeep 1 Gold, 1 Food
Stats: 1 Defense, 1 Long Range
Upgrade: Range (+1 Long Range)", 
            "Type: Pike/Mounted
Upkeep: 8 Gold, 4 Food
Stats: 7 Defense, 2 Short Ranged, 5 Melee
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+0.5 Melee, +0.5 Short Range)
Special: 1.5x Damage vs Mounted", 
            "Type: None
Upkeep: 35 Gold, 8 Food
Stats: 15 Defense, 8 Melee, 5 Short Range, 0 Long Range
Upgrade: receives blade (+1x4 melee), range (+1x4 short range, +.5x4 long range), and explosive techs (+4 melee, +2 short range, +1 long range)",
            "Type: None
Upkeep: 40 Gold, 10 Food
Stats: 18 Defense, 9 Long Range
Upgrade: receives range (+3x4 range) and explosive tech (+9 range)",
            "Upkeep: 3 Gold, 1 Food");
         break;
      case "Dwarf":
         $units = array(
            "Type: Infantry
Upkeep: 7 Gold, 3 Food
Stats: 7 Defense, 7 Melee
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+1 Melee)", 
            "Type: Infantry
Upkeep: 10 Gold, 4 Food
Stats: 11 Defense, 9 Melee
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+1 Melee)", 
            "Type: Archer
Upkeep: 8 Gold, 2 Food
Stats: 5 Defense, 1 Short Range, 2 Long Range
Upgrade: Light Armor (+1 Defense), Range (+2 Long Range)", 
            "Type: Infantry
Upkeep: 13 Gold, 2 Food
Stats: 8 Defense, 11 Melee, X Short Range
Upgrade: Light Armor (+1 Defense), Sharper Blade (+1 Melee)
Special: Bonus short range damage based on % of Berserkers killed during long range.", 
            "Type: Infantry
Upkeep: 15 Gold, 4 Food
Stats: 3 Defense, 7 Melee, 8 Short Range
Upgrade: Light Armor (+1 Defense), Sharper Blade (+0.5 Melee, +0.5 Short Range)", 
            "Type: Infantry
Upkeep: 13 Gold, 4 Food
Stats: 20 Defense, 3 Melee
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+1 Melee), True Silver (+7 Defense)",
            "Upkeep: 3 Gold, 1 Food");
         break;
      case "Elf":
         $units = array(
            "Type: Pike
Upkeep: 6 Gold, 3 Food
Stats: 2 Defense, 4 Melee
Upgrade: Light Armor (+1 Defense), Sharper Blade (+1 Melee)
Special: 1.5x Damage vs Mounted", 
            "Type: Infantry
Upkeep: 6 Gold, 2 Food
Stats: 4 Defense, 2 Melee
Upgrade: Light Armor (+1 Defense), Sharper Blade (+1 Melee)", 
            "Type: Archer
Upkeep: 4 Gold, 2 Food
Stats: 3 Defense, 3 Long Range
Upgrade: Range (+3 Long Range)
Special: Bonus defense from Guard Towers (on defense only). Up to 1 Guard Tower per Land for +2 Defense.", 
            "Type: Archer
Upkeep: 8 Gold, 3 Food
Stats: 5 Defense, 5 Long Range
Upgrade: Range (+5 Long Range)
Special: 1.5x Damage vs Archers
Special2: Bonus defense from Guard Towers (on defense only). Up to 1 Guard Tower per Land for +2 Defense.", 
            "Type: Pike/Mounted
Upkeep: 15 gold, 4 food
Stats: 7 Defense, 9 Melee, X Short Range
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+1 Melee)
Special: 1.5x Damage vs Mounted
Special2: Bonus short range when your army is outnumbered", 
            "Type: None
Stats: 5 Defense, 4 Melee, 7 Long Range
Upkeep: 20 gold, 4 food
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+1 Melee), Spell (+1 Magic, Max at 4)
Special: Melee damage taken reduced by 25-75% (random)",
            "Upkeep: 3 Gold, 1 Food");
         break;
      case "Human":
         $units = array(
            "Type: Pike
Upkeep: 4 Gold, 2 Food
Stats: 3 Defense, 5 Melee
Upgrade: Light Armor (+1 Defense), Sharper Blade (+1 Melee)
Special: 1.5x Damage vs Mounted", 
            "Type: Infantry
Upkeep: 4 Gold, 2 Food
Stats: 4 Defense, 3 Melee
Upgrade: Light Armor (+1 Defense), Sharper Blade (+1 Melee)", 
            "Type: Archer
Upkeep: 3 Gold, 3 Food
Stats: 3 Defense, 1 Short Range, 2 Long Range
Upgrade: Light armor (+1 Defense), Range (+.25 Short Range, +.5 Long Range)", 
            "Type: Mounted
Upkeep: 11 Gold, 3 Food
Stats: 7 Defense, 3 Melee, 1 Short Range, 3 Long Range
Upgrade: Light Armor (+1 Defense), Sharper Blade (+1 Melee), Range (+.25 Short Range, +.75 Long Range)
Special: Melee damage taken reduced by 10%", 
            "Type: Infantry
Upkeep: 7 Gold, 3 Food
Stats: 8 Defense, 7 Melee
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+1 Melee)
Special: Long range damage taken reduced by 10%", 
            "Type: Mounted
Upkeep: 23 Gold, 5 Food
Stats: 11 Defense, 8 Melee, 1 Short Range
Upgrade: Heavy Armor (+1 Defense), Sharper Blade (+0.5 Melee, +0.5 Short Range)",
            "Upkeep: 3 Gold, 1 Food");
         break;
		default:
			echo "Error accessing database.";
			break;
		}
		
		return $units;
	}
	
	function getUnitNumbers($user = null){
		if(isset($user['User']['Army']))
			return array($user['User']['Army']['weak'], $user['User']['Army']['standard'], $user['User']['Army']['range'], $user['User']['Army']['mount'], $user['User']['Army']['small_elite'], $user['User']['Army']['big_elite']);
		else
			return array($user['Army']['weak'], $user['Army']['standard'], $user['Army']['range'], $user['Army']['mount'], $user['Army']['small_elite'], $user['Army']['big_elite']);
	}
	
	function getHousingBonus($user = null){
		switch($user['User']['race']){
			case "Orc":
				$housingBonus = 10;
				break;
			case "Human":
				$housingBonus = 0;
				break;
			case "Gnome":
				$housingBonus = 20;
				break;
			case "Elf":
				$housingBonus = 0;
				break;
			case "Dwarf":
				$housingBonus = 0;
				break;
		}
		return $housingBonus;
	}
	
	function getAvailableWizardHousing($user = null){
		$housingBonus = $this->getHousingBonus($user);
		$tech = 0;
		if($user['User']['Technology']['tree_2'] > 4)
			$tech = 25;
					
		return (($user['User']['Building']['school'] * (40+$housingBonus+$tech)) - $user['Army']['wizard'] - $user['Army']['wizard_training'] - $user['Army']['wizard_resting']);
	}
	
	function updateRestingWizards($user = null){
		$restedWizards = $this->getRestingWizards($user);
		if($user['Army']['wizard_resting'] < $restedWizards){
			$restedWizards = $user['Army']['wizard_resting'];
		}
		$user['Army']['wizard_resting'] -= $restedWizards;
		$user['Army']['wizard'] += $restedWizards;
		
		return $user;
	}
	
	function getRestingWizards($user = null){
		$restedWizards = round($user['Building']['school'] * 1.5);
		//Insight Technology
		if ($user['Technology']['tree_4'] > 4)
			$restedWizards = $user['Building']['school'] * 2;
			
		return $restedWizards;
	}
	
	function getAvailableArmyHousing($user = null){
		$totalUnits = 0;
		if(!isset($user['Army'])){
			$user['Army'] = $user['User']['Army'];
		}
		$Center = Classregistry::init('Center');
		if($Center->checkCenter($user['User']['id'])) {
			$center = $Center->getCenterUnits($user['User']['id']);
		}
		if($user['User']['race'] != "Gnome") {
			$totalUnits += $center['Center']['weak'] + $center['Center']['standard'] + $center['Center']['small_elite'] + $center['Center']['big_elite']
			 + $center['Center']['range'] + $center['Center']['mount'];
		}
		else {
			 $totalUnits += $center['Center']['weak'] + $center['Center']['standard'] + ($center['Center']['small_elite'] * 8) + ($center['Center']['big_elite'] * 10)
			 + $center['Center']['range'] + $center['Center']['mount'];
		}
		
		if($units = $this->User->Attack->find('all', array('conditions' => array('Attack.user_id' => $user['User']['id'])))) {
			/*
			 * For some reason this wasn't working, not adding this to totalUnits
			 */
			if($user['User']['race'] != "Gnome") {
				foreach($units as $unit):				
					$totalUnits += $unit['Attack']['weak'];
					$totalUnits += $unit['Attack']['standard'];
					$totalUnits += $unit['Attack']['range'];
					$totalUnits += $unit['Attack']['mount'];
					$totalUnits += $unit['Attack']['small_elite'];
					$totalUnits += $unit['Attack']['big_elite'];
				endforeach;
			}
			else {
				
				foreach($units as $unit):
					$totalUnits += $unit['Attack']['weak'];
					$totalUnits += $unit['Attack']['standard'];
					$totalUnits += $unit['Attack']['range'];
					$totalUnits += $unit['Attack']['mount'];
					$totalUnits += ($unit['Attack']['small_elite'] * 8);
					$totalUnits += ($unit['Attack']['big_elite'] * 10);
				endforeach;
			}
		}
		if($user['User']['race'] != "Gnome") {
			$totalUnits += $user['Army']['weak'] + $user['Army']['standard'] + $user['Army']['small_elite'] + $user['Army']['big_elite']
			 + $user['Army']['range'] + $user['Army']['mount'];
			 $totalUnits += $user['Army']['weak_training'] + $user['Army']['standard_training'] + ($user['Army']['small_elite_training']) + ($user['Army']['big_elite_training'])
			 + $user['Army']['range_training'] + $user['Army']['mount_training'];
		}
		else {
			 $totalUnits += $user['Army']['weak'] + $user['Army']['standard'] + ($user['Army']['small_elite'] * 8) + ($user['Army']['big_elite'] * 10)
			 + $user['Army']['range'] + $user['Army']['mount'];
			 $totalUnits += $user['Army']['weak_training'] + $user['Army']['standard_training'] + ($user['Army']['small_elite_training'] * 8) + ($user['Army']['big_elite_training'] * 10)
			 + $user['Army']['range_training'] + $user['Army']['mount_training'];
		}
		$housingBonus = $this->getHousingBonus($user);
		
		$tech = 0;
		if($user['User']['Technology']['tree_2'] > 4)
			$tech = 25;
			
		
		$availableHousing = (($user['User']['Building']['guard_house'] * (40+$housingBonus+$tech)) - $totalUnits);
		
		return $availableHousing;
	}
	
	function getTotalArmyHousing($user = null){
		if(isset($user['Building'])):
			$user['User']['Building'] = $user['Building'];
			$user['User']['Technology'] = $user['Technology'];
		else:
			$user['Building'] = $user['User']['Building'];
			$user['Technology'] = $user['User']['Technology'];
		endif;
		$housingBonus = $this->getHousingBonus($user);
		
		$tech = 0;
		if($user['Technology']['tree_2'] > 4)
			$tech = 25;
			
		//unset($user['User']['Building']);
		
		return ($user['Building']['guard_house'] * (40+$housingBonus+$tech));
	}
	
	function getTotalWizardHousing($user = null){
		$user['User']['Building'] = $user['Building'];
		$housingBonus = $this->getHousingBonus($user);
		$tech = 0;
		if($user['Technology']['tree_2'] > 4)
			$tech = 25;
					
		return ($user['Building']['school'] * (40+$housingBonus+$tech));
	}
	
	
	function updateTrainingSoldiers($user = null){
		$units = $this->getFullArmyData($user);
		
		$tech = 1;
		if($user['Technology']['tree_2'] > 5)
			$tech = 1.25;
		
		foreach($units as $unit):
			$type = $unit['type'];
			$typeTraining = $type . "_training";
			if($unit['type'] == "wizard") {
				$trained = floor(($user['Building']['school'] * ((100 + $user['User']['loyalty']) / 100)) / $unit['training_centers_needed']) + $user['Building']['castle'];
				if($user['Army'][$typeTraining] < $trained) {
					$trained = $user['Army'][$typeTraining];
				}
				$user['Army'][$typeTraining] -= $trained;
				$user['Army'][$type] += $trained;
			}
			elseif($unit['training_center']) {
				$trained = floor(($user['Building']['training_center'] * $tech * ((100 + $user['User']['loyalty']) / 100)) / $unit['training_centers_needed']) + $user['Building']['castle'];
				if($user['Army'][$typeTraining] < $trained) {
					$trained = $user['Army'][$typeTraining];
				}
				$user['Army'][$typeTraining] -= $trained;
				$user['Army'][$type] += $trained;
			}
			else {
				$trained = floor(($user['Building']['advanced_training_center'] * $tech * ((100 + $user['User']['loyalty']) / 100)) / $unit['training_centers_needed']) + $user['Building']['castle'];
				if($user['Army'][$typeTraining] < $trained) {
					$trained = $user['Army'][$typeTraining];
				}
				$user['Army'][$typeTraining] -= $trained;
				$user['Army'][$type] += $trained;	
			}
		endforeach;
		
		return $user;
	}
	
	function getFullArmyData($user = null){
		// 'fields' => array('Attack.weak', 'Attack.standard', 'Attack.small_elite', 'big_elite', 'Attack.range', 'Attack.mount'), 
		if(!isset($user['User'])){
			$user['User']['id'] = $user['id'];
			$user['User']['race'] = $user['race'];
		}
		$awayUnits['weak'] = 0;
		$awayUnits['standard'] = 0;
		$awayUnits['range'] = 0;
		$awayUnits['mount'] = 0;
		$awayUnits['small_elite'] = 0;
		$awayUnits['big_elite'] = 0;
		if($units = $this->User->Attack->find('all', array('conditions' => array('Attack.user_id' => $user['User']['id'])))) {
			foreach($units as $unit):
				$awayUnits['weak'] += $unit['Attack']['weak'];
				$awayUnits['standard'] += $unit['Attack']['standard'];
				$awayUnits['range'] += $unit['Attack']['range'];
				$awayUnits['mount'] += $unit['Attack']['mount'];
				$awayUnits['small_elite'] += $unit['Attack']['small_elite'];
				$awayUnits['big_elite'] += $unit['Attack']['big_elite'];
			endforeach;
		}
		
		switch($user['User']['race']){
			case "Orc":
				$army = array(
					0 => array(
						'name' => "Slother",
						'type' => "weak",
						'amount' => $user['Army']['weak'],
						'amount_away' => $awayUnits['weak'],
						'amount_training' => $user['Army']['weak_training'],
						'housing' => 1,
						'gold' => 45,
						'vehicle' => 1,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 1,
						'range' => 0,
						'melee_attack' => 2,
						'defense' => 2,
						'long_range' => 0,
						'gold_consumed' => 2,
						'food_consumed' => 2,
						'training_centers_needed' => .5,
						'training_center' => true,
						'special' => array(
							'pike' => true,
							'mount' => true
						)
					),
					1 => array(
						'name' => "Rusher",
						'type' => "standard",
						'amount' => $user['Army']['standard'],
						'amount_away' => $awayUnits['standard'],
						'amount_training' => $user['Army']['standard_training'],
						'housing' => 1,
						'gold' => 40,
						'vehicle' => 1,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 1,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 4,
						'defense' => 3,
						'long_range' => 0,
						'gold_consumed' => 4,
						'food_consumed' => 2,
						'training_centers_needed' => 1,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => true
						)
					),
					2 => array(
						'name' => "Wolf Master",
						'type' => "small_elite",
						'amount' => $user['Army']['small_elite'],
						'amount_away' => $awayUnits['small_elite'],
						'amount_training' => $user['Army']['small_elite_training'],
						'housing' => 1,
						'gold' => 400,
						'vehicle' => 1,
						'big_armor' => 1,
						'small_armor' => 0,
						'big_melee' => 1,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 6,
						'defense' => 3,
						'long_range' => 1,
						'gold_consumed' => 6,
						'food_consumed' => 4,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => true
						)
					),
					3 => array(
						'name' => "Shadow Warrior",
						'type' => "big_elite",
						'amount' => $user['Army']['big_elite'],
						'amount_away' => $awayUnits['big_elite'],
						'amount_training' => $user['Army']['big_elite_training'],
						'housing' => 1,
						'gold' => 700,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 1,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 8,
						'defense' => 3,
						'long_range' => 0,
						'gold_consumed' => 12,
						'food_consumed' => 4,
						'training_centers_needed' => 4,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					4 => array(
						'name' => "Slinger",
						'type' => "range",
						'amount' => $user['Army']['range'],
						'amount_away' => $awayUnits['range'],
						'amount_training' => $user['Army']['range_training'],
						'housing' => 1,
						'gold' => 60,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 1,
						'melee_attack' => 0,
						'defense' => 2,
						'long_range' => 2,
						'gold_consumed' => 2,
						'food_consumed' => 1,
						'training_centers_needed' => .5,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					5 => array(
						'name' => "Axe Thrower",
						'type' => "mount",
						'amount' => $user['Army']['mount'],
						'amount_away' => $awayUnits['mount'],
						'amount_training' => $user['Army']['mount_training'],
						'housing' => 1,
						'gold' => 150,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 3,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 3,
						'defense' => 2,
						'long_range' => 3,
						'gold_consumed' => 8,
						'food_consumed' => 3,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					6 => array(
						'name' => "Wizard",
						'type' => "wizard",
						'amount' => $user['Army']['wizard'],
						'amount_away' => 0,
						'amount_training' => $user['Army']['wizard_training'],
						'housing' => 1,
						'gold' => 300,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 0,
						'defense' => 0,
						'long_range' => 0,
						'gold_consumed' => 3,
						'food_consumed' => 1,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					)
				);
				break;
			case "Human":
				$army = array(
					0 => array(
						'name' => "Pikeman",
						'type' => "weak",
						'amount' => $user['Army']['weak'],
						'amount_away' => $awayUnits['weak'],
						'amount_training' => $user['Army']['weak_training'],
						'housing' => 1,
						'gold' => 150,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 0,
						'small_melee' => 1,
						'range' => 0,
						'melee_attack' => 4,
						'defense' => 3,
						'long_range' => 0,
						'gold_consumed' => 4,
						'food_consumed' => 2,
						'training_centers_needed' => 3,
						'training_center' => true,
						'special' => array(
							'pike' => true,
							'mount' => false
						)
					),
					1 => array(
						'name' => "Infantry",
						'type' => "standard",
						'amount' => $user['Army']['standard'],
						'amount_away' => $awayUnits['standard'],
						'amount_training' => $user['Army']['standard_training'],
						'housing' => 1,
						'gold' => 100,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 1,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 3,
						'defense' => 5,
						'long_range' => 0,
						'gold_consumed' => 4,
						'food_consumed' => 2,
						'training_centers_needed' => 2,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					2 => array(
						'name' => "Heavy Infantry",
						'type' => "small_elite",
						'amount' => $user['Army']['small_elite'],
						'amount_away' => $awayUnits['small_elite'],
						'amount_training' => $user['Army']['small_elite_training'],
						'housing' => 1,
						'gold' => 450,
						'vehicle' => 0,
						'big_armor' => 1,
						'small_armor' => 0,
						'big_melee' => 2,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 7,
						'defense' => 8,
						'long_range' => 0,
						'gold_consumed' => 7,
						'food_consumed' => 3,
						'training_centers_needed' => 4,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					3 => array(
						'name' => "Knight",
						'type' => "big_elite",
						'amount' => $user['Army']['big_elite'],
						'amount_away' => $awayUnits['big_elite'],
						'amount_training' => $user['Army']['big_elite_training'],
						'housing' => 1,
						'gold' => 500,
						'vehicle' => 1,
						'big_armor' => 1,
						'small_armor' => 0,
						'big_melee' => 2,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 8,
						'defense' => 11,
						'long_range' => 1,
						'gold_consumed' => 23,
						'food_consumed' => 5,
						'training_centers_needed' => 5,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => true
						)
					),
					4 => array(
						'name' => "Archer",
						'type' => "range",
						'amount' => $user['Army']['range'],
						'amount_away' => $awayUnits['range'],
						'amount_training' => $user['Army']['range_training'],
						'housing' => 1,
						'gold' => 100,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 1,
						'melee_attack' => 0,
						'defense' => 3,
						'long_range' => 2,
						'gold_consumed' => 3,
						'food_consumed' => 3,
						'training_centers_needed' => 3,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					5 => array(
						'name' => "Mounted Archer",
						'type' => "mount",
						'amount' => $user['Army']['mount'],
						'amount_away' => $awayUnits['mount'],
						'amount_training' => $user['Army']['mount_training'],
						'housing' => 1,
						'gold' => 300,
						'vehicle' => 1,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 1,
						'small_melee' => 0,
						'range' => 1,
						'melee_attack' => 3,
						'defense' => 7,
						'long_range' => 2,
						'gold_consumed' => 11,
						'food_consumed' => 3,
						'training_centers_needed' => 4,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => true
						)
					),
					6 => array(
						'name' => "Wizard",
						'type' => "wizard",
						'amount' => $user['Army']['wizard'],
						'amount_away' => 0,
						'amount_training' => $user['Army']['wizard_training'],
						'housing' => 1,
						'gold' => 275,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 0,
						'defense' => 0,
						'long_range' => 0,
						'gold_consumed' => 3,
						'food_consumed' => 1,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					)
				);


				
				break;
			case "Gnome":
				$army = array(
					0 => array(
						'name' => "Militia",
						'type' => "weak",
						'amount' => $user['Army']['weak'],
						'amount_away' => $awayUnits['weak'],
						'amount_training' => $user['Army']['weak_training'],
						'housing' => 1,
						'gold' => 90,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 1,
						'range' => 0,
						'melee_attack' => 1,
						'defense' => 1,
						'long_range' => 0,
						'gold_consumed' => 1,
						'food_consumed' => 1,
						'training_centers_needed' => .142857,
						'training_center' => true,
						'special' => array(
							'pike' => true,
							'mount' => false
						)
					),
					1 => array(
						'name' => "Infantry",
						'type' => "standard",
						'amount' => $user['Army']['standard'],
						'amount_away' => $awayUnits['standard'],
						'amount_training' => $user['Army']['standard_training'],
						'housing' => 1,
						'gold' => 75,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 0,
						'small_melee' => 1,
						'range' => 0,
						'melee_attack' => 2,
						'defense' => 2,
						'long_range' => 0,
						'gold_consumed' => 4,
						'food_consumed' => 1,
						'training_centers_needed' => .5,
						'training_center' => true,
						'special' => array(
							'pike' => true,
							'mount' => false
						)
					),
					2 => array(
						'name' => "Ballista",
						'type' => "small_elite",
						'amount' => $user['Army']['small_elite'],
						'amount_away' => $awayUnits['small_elite'],
						'amount_training' => $user['Army']['small_elite_training'],
						'housing' => 8,
						'gold' => 1900,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 7,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 5,
						'defense' => 15,
						'long_range' => 10,
						'gold_consumed' => 35,
						'food_consumed' => 8,
						'training_centers_needed' => 5,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					3 => array(
						'name' => "Catapult",
						'type' => "big_elite",
						'amount' => $user['Army']['big_elite'],
						'amount_away' => $awayUnits['big_elite'],
						'amount_training' => $user['Army']['big_elite_training'],
						'housing' => 10,
						'gold' => 2500,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 10,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 0,
						'defense' => 25,
						'long_range' => 15,
						'gold_consumed' => 40,
						'food_consumed' => 10,
						'training_centers_needed' => 7,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					4 => array(
						'name' => "Rock Thrower",
						'type' => "range",
						'amount' => $user['Army']['range'],
						'amount_away' => $awayUnits['range'],
						'amount_training' => $user['Army']['range_training'],
						'housing' => 1,
						'gold' => 100,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 1,
						'melee_attack' => 0,
						'defense' => 1,
						'long_range' => 1,
						'gold_consumed' => 1,
						'food_consumed' => 1,
						'training_centers_needed' => .142857,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					5 => array(
						'name' => "Rider",
						'type' => "mount",
						'amount' => $user['Army']['mount'],
						'amount_away' => $awayUnits['mount'],
						'amount_training' => $user['Army']['mount_training'],
						'housing' => 1,
						'gold' => 450,
						'vehicle' => 1,
						'big_armor' => 1,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 1,
						'range' => 0,
						'melee_attack' => 6,
						'defense' => 6,
						'long_range' => 0,
						'gold_consumed' => 8,
						'food_consumed' => 4,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => true
						)
					),
					6 => array(
						'name' => "Wizard",
						'type' => "wizard",
						'amount' => $user['Army']['wizard'],
						'amount_away' => 0,
						'amount_training' => $user['Army']['wizard_training'],
						'housing' => 1,
						'gold' => 450,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 0,
						'defense' => 0,
						'long_range' => 0,
						'gold_consumed' => 3,
						'food_consumed' => 1,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					)
				);
				break;
			case "Dwarf":
				$army = array(
					0 => array(
						'name' => "Hammer Wielder",
						'type' => "weak",
						'amount' => $user['Army']['weak'],
						'amount_away' => $awayUnits['weak'],
						'amount_training' => $user['Army']['weak_training'],
						'housing' => 1,
						'gold' => 450,
						'vehicle' => 0,
						'big_armor' => 1,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 1,
						'range' => 0,
						'melee_attack' => 7,
						'defense' => 7,
						'long_range' => 0,
						'gold_consumed' => 7,
						'food_consumed' => 2,
						'training_centers_needed' => 4,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					1 => array(
						'name' => "Axe Man",
						'type' => "standard",
						'amount' => $user['Army']['standard'],
						'amount_away' => $awayUnits['standard'],
						'amount_training' => $user['Army']['standard_training'],
						'housing' => 1,
						'gold' => 900,
						'vehicle' => 0,
						'big_armor' => 1,
						'small_armor' => 0,
						'big_melee' => 1,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 9,
						'defense' => 11,
						'long_range' => 0,
						'gold_consumed' => 10,
						'food_consumed' => 3,
						'training_centers_needed' => 5,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					2 => array(
						'name' => "Runner",
						'type' => "small_elite",
						'amount' => $user['Army']['small_elite'],
						'amount_away' => $awayUnits['small_elite'],
						'amount_training' => $user['Army']['small_elite_training'],
						'housing' => 1,
						'gold' => 450,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 4,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 6,
						'defense' => 3,
						'long_range' => 2,
						'gold_consumed' => 15,
						'food_consumed' => 4,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					3 => array(
						'name' => "Shieldbearer",
						'type' => "big_elite",
						'amount' => $user['Army']['big_elite'],
						'amount_away' => $awayUnits['big_elite'],
						'amount_training' => $user['Army']['big_elite_training'],
						'housing' => 1,
						'gold' => 750,
						'vehicle' => 0,
						'big_armor' => 1,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 0,
						'defense' => 20,
						'long_range' => 0,
						'gold_consumed' => 13,
						'food_consumed' => 4,
						'training_centers_needed' => 4,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					4 => array(
						'name' => "Light Crossbowman",
						'type' => "range",
						'amount' => $user['Army']['range'],
						'amount_away' => $awayUnits['range'],
						'amount_training' => $user['Army']['range_training'],
						'housing' => 1,
						'gold' => 325,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 1,
						'melee_attack' => 0,
						'defense' => 5,
						'long_range' => 2,
						'gold_consumed' => 5,
						'food_consumed' => 1,
						'training_centers_needed' => 3,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					5 => array(
						'name' => "Berserker",
						'type' => "mount",
						'amount' => $user['Army']['mount'],
						'amount_away' => $awayUnits['mount'],
						'amount_training' => $user['Army']['mount_training'],
						'housing' => 1,
						'gold' => 525,
						'vehicle' => 1,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 1,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 11, // variable SR
						'defense' => 8,
						'long_range' => 0,
						'gold_consumed' => 13,
						'food_consumed' => 2,
						'training_centers_needed' => 5,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					6 => array(
						'name' => "Wizard",
						'type' => "wizard",
						'amount' => $user['Army']['wizard'],
						'amount_away' => 0,
						'amount_training' => $user['Army']['wizard_training'],
						'housing' => 1,
						'gold' => 500,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 0,
						'defense' => 0,
						'long_range' => 0,
						'gold_consumed' => 3,
						'food_consumed' => 1,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					)
				);
				break;
			case "Elf":
				$army = array(
					0 => array(
						'name' => "Lanceman",
						'type' => "weak",
						'amount' => $user['Army']['weak'],
						'amount_away' => $awayUnits['weak'],
						'amount_training' => $user['Army']['weak_training'],
						'housing' => 1,
						'gold' => 100,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 0,
						'small_melee' => 1,
						'range' => 0,
						'melee_attack' => 3,
						'defense' => 3,
						'long_range' => 0,
						'gold_consumed' => 6,
						'food_consumed' => 3,
						'training_centers_needed' => 3,
						'training_center' => true,
						'special' => array(
							'pike' => true,
							'mount' => false
						)
					),
					1 => array(
						'name' => "Swordman",
						'type' => "standard",
						'amount' => $user['Army']['standard'],
						'amount_away' => $awayUnits['standard'],
						'amount_training' => $user['Army']['standard_training'],
						'housing' => 1,
						'gold' => 100,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 1,
						'big_melee' => 1,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 4,
						'defense' => 3,
						'long_range' => 0,
						'gold_consumed' => 6,
						'food_consumed' => 2,
						'training_centers_needed' => 2,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					2 => array(
						'name' => "Courageous",
						'type' => "small_elite",
						'amount' => $user['Army']['small_elite'],
						'amount_away' => $awayUnits['small_elite'],
						'amount_training' => $user['Army']['small_elite_training'],
						'housing' => 1,
						'gold' => 750,
						'vehicle' => 1,
						'big_armor' => 1,
						'small_armor' => 0,
						'big_melee' => 1,
						'small_melee' => 1,
						'range' => 0,
						'melee_attack' => 4,
						'defense' => 1,
						'long_range' => 0,
						'gold_consumed' => 15,
						'food_consumed' => 4,
						'training_centers_needed' => 4,
						'training_center' => false,
						'special' => array(
							'pike' => true,
							'mount' => true
						)
					),
					3 => array(
						'name' => "Mage",
						'type' => "big_elite",
						'amount' => $user['Army']['big_elite'],
						'amount_away' => $awayUnits['big_elite'],
						'amount_training' => $user['Army']['big_elite_training'],
						'housing' => 1,
						'gold' => 1900,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 3,
						'defense' => 2,
						'long_range' => 7,
						'gold_consumed' => 20,
						'food_consumed' => 4,
						'training_centers_needed' => 4,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					4 => array(
						'name' => "Archer",
						'type' => "range",
						'amount' => $user['Army']['range'],
						'amount_away' => $awayUnits['range'],
						'amount_training' => $user['Army']['range_training'],
						'housing' => 1,
						'gold' => 350,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 1,
						'melee_attack' => 0,
						'defense' => 2,
						'long_range' => 3,
						'gold_consumed' => 4,
						'food_consumed' => 2,
						'training_centers_needed' => 1,
						'training_center' => true,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					5 => array(
						'name' => "Elite Archer",
						'type' => "mount",
						'amount' => $user['Army']['mount'],
						'amount_away' => $awayUnits['mount'],
						'amount_training' => $user['Army']['mount_training'],
						'housing' => 1,
						'gold' => 650,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 2,
						'melee_attack' => 0,
						'defense' => 3,
						'long_range' => 5,
						'gold_consumed' => 8,
						'food_consumed' => 3,
						'training_centers_needed' => 4,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					),
					6 => array(
						'name' => "Wizard",
						'type' => "wizard",
						'amount' => $user['Army']['wizard'],
						'amount_away' => 0,
						'amount_training' => $user['Army']['wizard_training'],
						'housing' => 1,
						'gold' => 225,
						'vehicle' => 0,
						'big_armor' => 0,
						'small_armor' => 0,
						'big_melee' => 0,
						'small_melee' => 0,
						'range' => 0,
						'melee_attack' => 0,
						'defense' => 0,
						'long_range' => 0,
						'gold_consumed' => 3,
						'food_consumed' => 1,
						'training_centers_needed' => 3,
						'training_center' => false,
						'special' => array(
							'pike' => false,
							'mount' => false
						)
					)
				);
				if($user['Army']['strategy'] == 'Mystics'){
					$army[6]['gold_consumed'] = 5;
					$army[6]['food_consumed'] = 3;
				}
				break;
			default:
				break;
		}
		
		return $army;
	}
}
?>