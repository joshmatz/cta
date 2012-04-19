<?php
class Building extends AppModel {    
	var $name = 'Building';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id'
		)
	);
	
	function getPopPerBuilding($race = null){
		switch($race){
			case "Orc":
				$popPerBuilding = 110;
				break;
			case "Human":
				$popPerBuilding = 150;
				break;
			case "Dwarf":
				$popPerBuilding = 150;
				break;
			case "Gnome":
				$popPerBuilding = 110;
				break;
			case "Elf":
				$popPerBuilding = 150;
				break;
			default: 
				$popPerBuilding = 150;
				break;
		}
		
		return $popPerBuilding;
	}
	
	function getBuildingGold($currentGold = null, $castles = null, $queuedCastles = null) {
		if(($castles + $queuedCastles) <= 9)
			$castlePrice = 3750 * ($castles + $queuedCastles);
		else
			$castlePrice = 37500;
			
		/*
		if($queuedCastles && $castlePrice != 37500) {
			for($i = 0; $i < $queuedCastles; $i++) {
				$castlePrice += 3750 * ($castles + $i + 1);
			}
		}
		*/
			
		return array(250, 615, 560, 345, $castlePrice, 700, 950, 1800, 425, 535, 1275, 1200, 1550, $currentGold);
	}
	
	function getBuildingIron($currentIron = null, $castles = null, $queuedCastles = null) {
		if(($castles + $queuedCastles) <= 9)
			$castlePrice = 250 * ($castles + $queuedCastles);
		else
			$castlePrice = 2500;

		/*
		if($queuedCastles && $castlePrice != 2500) {
			for($i = 0; $i < $queuedCastles; $i++) {
				$castlePrice += 3750 * ($castles + $i + 1);
			}
		}
		*/
			
		return array(20, 20, 75, 10, $castlePrice, 115, 35, 35, 15, 10, 60, 100, 125, $currentIron);
	}
	
	function getBuildingWood($currentWood = null, $castles = null, $queuedCastles = null) {
		if(($castles + $queuedCastles) <= 9)
			$castlePrice = 350 * ($castles + $queuedCastles);
		else
			$castlePrice = 3500;
			
		/*
		if($queuedCastles && $castlePrice != 3500) {
			for($i = 0; $i < $queuedCastles; $i++) {
				$castlePrice += 3750 * ($castles + $i + 1);
			}
		}
		*/
			
		return array(40, 60, 30, 50, $castlePrice, 50, 75, 75, 165, 95, 250, 100, 125, $currentWood);
	}
	
	/***********
	 * Return total available land (land * 10) - buildings
	 */
	function getAvailableLand($user = null) {
		$buildingSpots = $user['User']['Resource']['land'] * 10;
		$buildingSpots -= $user['Building']['farm'];
		$buildingSpots -= $user['Building']['farmsBuilding'];
		
		$buildingSpots -= $user['Building']['house'];
		$buildingSpots -= $user['Building']['housesBuilding'];

		$buildingSpots -= $user['Building']['market'];
		$buildingSpots -= $user['Building']['marketsBuilding'];

		$buildingSpots -= $user['Building']['mill'];
		$buildingSpots -= $user['Building']['millsBuilding'];

		$buildingSpots -= $user['Building']['mine'];
		$buildingSpots -= $user['Building']['minesBuilding'];

		$buildingSpots -= $user['Building']['school'];
		$buildingSpots -= $user['Building']['schoolsBuilding'];

		$buildingSpots -= $user['Building']['forge'];
		$buildingSpots -= $user['Building']['forgesBuilding'];

		$buildingSpots -= $user['Building']['guard_house'];
		$buildingSpots -= $user['Building']['guard_housesBuilding'];

		$buildingSpots -= $user['Building']['guard_tower'];
		$buildingSpots -= $user['Building']['guard_towersBuilding'];

		$buildingSpots -= $user['Building']['medical_center'];
		$buildingSpots -= $user['Building']['medical_centersBuilding'];

		$buildingSpots -= $user['Building']['training_center'];
		$buildingSpots -= $user['Building']['training_centersBuilding'];

		$buildingSpots -= $user['Building']['advanced_training_center'];
		$buildingSpots -= $user['Building']['advanced_training_centersBuilding'];

		return $buildingSpots;
	}
	
	function getTotalBuildings($user = null){
		$total = 0;
		
		$total += $user['Building']['farm'];
		$total += $user['Building']['farmsBuilding'];
		
		$total += $user['Building']['house'];
		$total += $user['Building']['housesBuilding'];

		$total += $user['Building']['market'];
		$total += $user['Building']['marketsBuilding'];

		$total += $user['Building']['mill'];
		$total += $user['Building']['millsBuilding'];

		$total += $user['Building']['mine'];
		$total += $user['Building']['minesBuilding'];

		$total += $user['Building']['school'];
		$total += $user['Building']['schoolsBuilding'];

		$total += $user['Building']['forge'];
		$total += $user['Building']['forgesBuilding'];

		$total += $user['Building']['guard_house'];
		$total += $user['Building']['guard_housesBuilding'];

		$total += $user['Building']['guard_tower'];
		$total += $user['Building']['guard_towersBuilding'];

		$total += $user['Building']['medical_center'];
		$total += $user['Building']['medical_centersBuilding'];

		$total += $user['Building']['training_center'];
		$total += $user['Building']['training_centersBuilding'];

		$total += $user['Building']['advanced_training_center'];
		$total += $user['Building']['advanced_training_centersBuilding'];
		
		return $total;
	}

	function updateBuildings($user = null) {
		
		switch($user['User']['race']){
			case "Orc":
				$popPerBuilding = 110;
				break;
			case "Human":
				$popPerBuilding = 150;
				break;
			case "Dwarf":
				$popPerBuilding = 150;
				break;
			case "Gnome":
				$popPerBuilding = 110;
				break;
			case "Elf":
				$popPerBuilding = 150;
				break;
			default: 
				$popPerBuilding = 150;
				break;
		}
		$buildingMultiplier = 110/$popPerBuilding;
		
		if($user['Technology']['tree_2'] > 3)
			$tech = 2;
		else
			$tech = 1;
		
			// Buildings with castles
			//$numBuildings = round($buildingMultiplier*$tech*$user['Building']['castle']*5);
			
			// Buildings with population
			$numBuildings = round(floor($user['Population']['building'] / ($popPerBuilding/$tech)) + $user['Building']['castle']);
			if ($user['Spell']['freeze'] > 0)
				$numBuildings = floor(.33*$numBuildings);
			while($numBuildings > 0){
				if(($user['Building']['farmsBuilding']) && ($numBuildings > 0)) {
					$user['Building']['farm']++;
					$user['Building']['farmsBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['housesBuilding']) && ($numBuildings > 0)) {
					$user['Building']['house']++;
					$user['Building']['housesBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['marketsBuilding']) && ($numBuildings > 0)) {
					$user['Building']['market']++;
					$user['Building']['marketsBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['millsBuilding']) && ($numBuildings > 0)) {
					$user['Building']['mill']++;
					$user['Building']['millsBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['minesBuilding']) && ($numBuildings > 0)) {
					$user['Building']['mine']++;
					$user['Building']['minesBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['schoolsBuilding']) && ($numBuildings > 0)) {
					$user['Building']['school']++;
					$user['Building']['schoolsBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['forgesBuilding']) && ($numBuildings > 0)) {
					$user['Building']['forge']++;
					$user['Building']['forgesBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['guard_housesBuilding']) && ($numBuildings > 0)) {
					$user['User']['crime_rate']--;
					if($user['User']['crime_rate'] < $user['Building']['castle'])
						$user['User']['crime_rate'] = $user['Building']['castle'];
					
					// Set maximum
					if($user['User']['crime_rate'] > 45)
						$user['User']['crime_rate'] = 45;
					if($user['Technology']['tree_1'] > 4) {
						if($user['User']['crime_rate'] > 25)
							$user['User']['crime_rate'] = 25;
					}
					$user['Building']['guard_house']++;
					$user['Building']['guard_housesBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['guard_towersBuilding']) && ($numBuildings > 0)) {
					$user['Building']['guard_tower']++;
					$user['Building']['guard_towersBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['medical_centersBuilding']) && ($numBuildings > 0)) {
					$user['Building']['medical_center']++;
					$user['Building']['medical_centersBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['training_centersBuilding']) && ($numBuildings > 0)) {
					$user['Building']['training_center']++;
					$user['Building']['training_centersBuilding']--;
					$numBuildings--;	
				}
				if(($user['Building']['advanced_training_centersBuilding']) && ($numBuildings > 0)) {
					$user['Building']['advanced_training_center']++;
					$user['Building']['advanced_training_centersBuilding']--;
					$numBuildings--;	
				}
				if($user['Building']['housesBuilding'] + $user['Building']['farmsBuilding'] + $user['Building']['advanced_training_centersBuilding'] + $user['Building']['training_centersBuilding']
					+ $user['Building']['medical_centersBuilding'] + $user['Building']['guard_towersBuilding'] + $user['Building']['guard_housesBuilding'] + $user['Building']['forgesBuilding'] + $user['Building']['schoolsBuilding']
					+ $user['Building']['minesBuilding'] + $user['Building']['millsBuilding'] + $user['Building']['marketsBuilding'] == 0)
					break;	
			}// end while loop
		if($user['Building']['castleTime'] > 0) {
			$user['Building']['castleTime']--;
			if($user['Building']['castleTime'] == 0) {
				$user['User']['crime_rate']++;
		
				// Set maximum
				if($user['User']['crime_rate'] > 45)
					$user['User']['crime_rate'] = 45;
				if($user['Technology']['tree_1'] > 4) {
					if($user['User']['crime_rate'] > 25)
						$user['User']['crime_rate'] = 25;
				}
				$user['Building']['castle']++;
				$user['Building']['castlesBuilding']--;
				
				if($user['Building']['castlesBuilding'] > 0)
					$user['Building']['castleTime'] = 21;
			}
			
		}
		else {
			if($user['Building']['castlesBuilding'] > 0){
				$user['Building']['castleTime'] = 21;
			}
		}
		
		return $user;
	}

}
?>