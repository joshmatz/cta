<?php
class Weapon extends AppModel {    
	var $name = 'Weapon';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id'
		)
	);
	
	function getWeaponGold($race = null, $resource = null){
		switch($race){
			// Prices are set in the array as follows:
			// array(1 => vehicle, 2 => big_melee, 3 => small_melee, 4 => big_armor, 5 => small_armor, 6 => range, 7 => current resources)
			case "Orc":
				$gold = array(70, 20, 10, 125, 100, 5, $resource['gold']);
				break;
			case "Gnome":
				$gold = array(250, 70, 10, 175, 125, 15, $resource['gold']);
				break;
			case "Dwarf":
		    	$gold = array(100, 15, 10, 175, 125, 30, $resource['gold']);
				break;
			case "Elf":
		    	$gold = array(300, 25, 20, 200, 100, 20, $resource['gold']);
				break;
			case "Human":
				$gold = array(350, 15, 5, 200, 125, 10, $resource['gold']);
				break;
			default:
				echo "Error accessing database.";
				break;
		}
		
		return $gold;
	}
	
	function getWeaponWood($race = null, $resource = null){
		switch($race){
			// Prices are set in the array as follows:
			// array(1 => vehicle, 2 => big_melee, 3 => small_melee, 4 => big_armor, 5 => small_armor, 6 => range, 7 => current resources)
			case "Orc":
		    	$wood = array(0, 1, 2, 1, 1, 1, $resource['wood']);
				break;
			case "Gnome":
		    	$wood = array(0, 1, 2, 0, 1, 1, $resource['wood']);
				break;
			case "Dwarf":
		    	$wood = array(1, 1, 1, 0, 0, 3, $resource['wood']);
				break;
			case "Elf":
		    	$wood = array(0, 2, 3, 2, 2, 3, $resource['wood']);
				break;
			case "Human":
		    	$wood = array(0, 0, 3, 0, 0, 3, $resource['wood']);
				break;
			default:
				echo "Error accessing database.";
				break;
		}
		
		return $wood;
	}
	
	
	function getWeaponIron($race = null, $resource = null){
		switch($race){
			// Prices are set in the array as follows:
			// array(1 => vehicle, 2 => big_melee, 3 => small_melee, 4 => big_armor, 5 => small_armor, 6 => range, 7 => current resources)
			case "Orc":
		    	$iron = array(0, 2, 1, 2, 1, 2, $resource['iron']);
				break;
			case "Gnome":
		    	$iron = array(1, 1, 1, 6, 2, 1, $resource['iron']);
				break;
			case "Dwarf":
		    	$iron = array(4, 2, 2, 6, 3, 0, $resource['iron']);
				break;
			case "Elf":
		    	$iron = array(1, 1, 1, 4, 2, 0, $resource['iron']);
				break;
			case "Human":
		    	$iron = array(1, 3, 2, 7, 3, 0, $resource['iron']);
				break;
			default:
				echo "Error accessing database.";
				break;
		}
		return $iron;
	}
	
	function getWeaponNames($race = null, $resource = null){
		switch($race){
			// Prices are set in the array as follows:
			// array(1 => vehicle, 2 => big_melee, 3 => small_melee, 4 => big_armor, 5 => small_armor, 6 => range, 7 => current resources)
			case "Orc":
				$weapons = array("Wolf", "Axe", "Spear", "Enforced Leather Armor", "Leather Armor", "Sling");
				break;
			case "Gnome":
				$weapons = array("Pony", "Plank and Nails", "Spear", "Chainmail", "Leather Armor", "Sling");
				break;
			case "Dwarf":
				$weapons = array("Battle Axe", "Axe", "Hammer", "Plate Armor", "Chainmail", "Crossbow");
				break;
			case "Elf":
				$weapons = array("Horse", "Sword", "Lance", "Courageous Armor", "Light Armor", "Bow");
				break;
			case "Human":
				$weapons = array("Horse", "Sword", "Spear", "Plate Armor", "Light Armor", "Bow");
				break;
			default:
				echo "Error accessing database.";
				break;
		}
		return $weapons;
	}
	
	function getWeaponsProduced($user = null) {
		if(isset($user['User'])){
			
		}else {
			$user['User']['loyalty'] = $user['loyalty'];
			$user['User']['race'] = $user['race'];
		}
		switch($user['User']['race']) {
				case "Dwarf":
					$bonus = 2;
					break;
				case "Gnome":
					$bonus = 2.5;
					break;
				case "Human":
					$bonus = 1;
					break;
				case "Orc":
					$bonus = 2;
				default:
					$bonus = 0;
					break;
			}
			$optimalLevel = 80;
			if($user['Building']['forge'] != 0)
				$workersPerForge = $user['Population']['blacksmiths'] / $user['Building']['forge'];
			else
				$workersPerForge = 0;
				
			if($user['Technology']['tree_2'] > 2)
				$bonus = $bonus + round(($bonus + 4) * .25);
			
			$x = ($workersPerForge/$optimalLevel);
			//echo "X: $x<br />";
			// This pow($x-1, 1.5) is the problem, returns NaN when $x-1 is negative.
			// Brought in a different function that should allow for negative bases, not sure if it works. 
			// Numbers appear to be slightly off of what they should be. When $x == 0, the function algorithm returns .15
			
			//echo "algorithm: " . (-.3 * pow($x-1, 1.5) + .55 * ($x-1) + 1) ." <br /> <br />";
			if($x <= 1)
				$weaponsProduced = round(pow($x, 1.5) * ($bonus + 4) * $user['Building']['forge']);
			else
				$weaponsProduced = round(((-.3 * pow($x-1, 1.5)) + .55 * ($x-1) + 1) * ($bonus + 4) * $user['Building']['forge']);
			if($weaponsProduced < 0)
				$weaponsProduced = 0;
				
			if($user['Technology']['tree_2'] > 2)
				$weaponsProduced = round($weaponsProduced * 1.2);
			
			$weaponTypes = 0;
			if($user['Weapon']['vehicle_building'] > 0)
				$weaponTypes++;
			if($user['Weapon']['big_melee_building'] > 0)
				$weaponTypes++;
			if($user['Weapon']['small_melee_building'] > 0)
				$weaponTypes++;
			if($user['Weapon']['big_armor_building'] > 0)
				$weaponTypes++;
			if($user['Weapon']['small_armor_building'] > 0)
				$weaponTypes++;
			if($user['Weapon']['range_building'] > 0)
				$weaponTypes++;
				
			// loyalty affects weapons produced
			$weaponsProduced += ($weaponsProduced * ($user['User']['loyalty'] / 100));
		
			if($weaponTypes != 0){
				$mod = $weaponsProduced % $weaponTypes;
				if($mod != 0)
					$weaponsProduced -= $mod;
			}
				
				
		return $weaponsProduced;
	}

	function updateWeapons($user = null){
		if($user['Population']['blacksmiths'] > 0) {
			$weaponsProduced = $this->getWeaponsProduced($user);
					
			while($weaponsProduced > 0){
				if(($user['Weapon']['vehicle_building']) && ($weaponsProduced > 0)) {
					$user['Weapon']['vehicle']++;
					$user['Weapon']['vehicle_building']--;
					$weaponsProduced--;	
				}
				if(($user['Weapon']['big_melee_building']) && ($weaponsProduced > 0)) {
					$user['Weapon']['big_melee']++;
					$user['Weapon']['big_melee_building']--;
					$weaponsProduced--;	
				}
				if(($user['Weapon']['small_melee_building']) && ($weaponsProduced > 0)) {
					$user['Weapon']['small_melee']++;
					$user['Weapon']['small_melee_building']--;
					$weaponsProduced--;	
				}
				if(($user['Weapon']['big_armor_building']) && ($weaponsProduced > 0)) {
					$user['Weapon']['big_armor']++;
					$user['Weapon']['big_armor_building']--;
					$weaponsProduced--;	
				}
				if(($user['Weapon']['small_armor_building']) && ($weaponsProduced > 0)) {
					$user['Weapon']['small_armor']++;
					$user['Weapon']['small_armor_building']--;
					$weaponsProduced--;	
				}
				if(($user['Weapon']['range_building']) && ($weaponsProduced > 0)) {
					$user['Weapon']['range']++;
					$user['Weapon']['range_building']--;
					$weaponsProduced--;	
				}
				if ($user['Weapon']['range_building'] + $user['Weapon']['small_armor_building'] + $user['Weapon']['big_armor_building'] + $user['Weapon']['small_melee_building']
				+ $user['Weapon']['big_melee_building'] + $user['Weapon']['vehicle_building'] == 0)
					break;
			}// end of while
		}
			
		return $user;
	}

}
?>