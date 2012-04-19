<?php
class Population extends AppModel {    
	var $name = 'Population';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id'
		)
	);
	
	function updatePopulation($user = null){
		if($user['Spell']['natural_growth'] > 0) {
			$naturalGrowth = 1.3;
		}
		
		// Calc population
		// Ok math :D
		if($user['Technology']['tree_2'] > 0)
			$bonus = 15;
		else
			$bonus = 0;
		$unitsPerHouse = 100 + $bonus;
		$unitsPerBuilding = 10;
		// First set the difference only using houses
		$difference = (($user['Building']['house'] * $unitsPerHouse ));
		$difference += $user['Building']['farm'] * $unitsPerBuilding;
		$difference += $user['Building']['mill'] * $unitsPerBuilding;
		$difference += $user['Building']['mine'] * $unitsPerBuilding;
		$difference += $user['Building']['castle'] * $unitsPerBuilding;
		$difference += $user['Building']['forge'] * $unitsPerBuilding;
		$difference += $user['Building']['market'] * $unitsPerBuilding;
		$difference += $user['Building']['school'] * $unitsPerBuilding;
		$difference += $user['Building']['medical_center'] * $unitsPerBuilding;
		$difference += $user['Building']['guard_house'] * $unitsPerBuilding; 
		$difference += $user['Building']['guard_tower'] * $unitsPerBuilding;
		$difference += $user['Building']['training_center'] * $unitsPerBuilding;
		$difference += $user['Building']['advanced_training_center'] * $unitsPerBuilding;
		
		$maxPop = $difference;
		
		$difference -= $user['Population']['total_population'];


		
		//$difference -= $user['Population']['total_population'];
		//$difference = round($difference / 20);
		
		$ticksToFill = 6;
		$naturalGrowth = 1;
		$famine = 0;
		if ($user['Spell']['famine'] > 0){
			$naturalGrowth = 0;
			$famine = 1;
		}
		if(($difference < 0) && ($user['Spell']['famine'] == 0)){
			$naturalGrowth = 1;
			$famine = 0;
		}
		elseif($user['Spell']['natural_growth'] > 0) {
			$naturalGrowth = 1.3;
			if($user['Spell']['famine']){
				$famine = 0;
				$naturalGrowth = 1;
			}
		}
		
		if ($famine > 0 && ($user['Population']['total_population'] > .65*$maxPop)){
			$user['Population']['total_population'] = round($user['Population']['total_population']*.98);
			$user['Population']['lumbers'] = round($user['Population']['lumbers']*.98);
			$user['Population']['miners'] = round($user['Population']['miners']*.98);
			$user['Population']['agriculture'] = round($user['Population']['agriculture']*.98);
			$user['Population']['building'] = round($user['Population']['building']*.98);
			$user['Population']['blacksmiths'] = round($user['Population']['blacksmiths']*.98);
			$user['Population']['explorers'] = round($user['Population']['explorers']*.98);
			$user['Population']['training'] = round($user['Population']['training']*.98);
			//echo "New total population: ". $user['Population']['total_population'];
		} else {
			if($user['Population']['total_population'] <= $maxPop) {
				$user['Population']['total_population'] += round(($difference/$ticksToFill) * $naturalGrowth);
				if($user['Building']['farm']){
					if(($user['Population']['agriculture'] / $user['Building']['farm']) < 180) {
						if(round((($difference/$ticksToFill) * $naturalGrowth) / $user['Building']['farm']) < 180) {
							$user['Population']['agriculture'] += round(($difference/$ticksToFill) * $naturalGrowth);
						} else {
							$remaining = round((($difference/$ticksToFill) * $naturalGrowth)
									   - ((180 - ($user['Population']['agriculture'] / $user['Building']['farm'])) * ($user['Building']['farm'])));
							$user['Population']['agriculture'] += ((180 - ($user['Population']['agriculture'] / $user['Building']['farm'])) * ($user['Building']['farm']));
							$user['Population']['training'] += $remaining;
						}
					}
					else
						$user['Population']['training'] += round(($difference/$ticksToFill) * $naturalGrowth);
				}
				else
					$user['Population']['training'] += round(($difference/$ticksToFill) * $naturalGrowth);
			} else { // If they lose population
				$total = $user['Population']['total_population'];
				$percentLoss = abs(round($difference / $ticksToFill)) / $total;
				
				$user['Population']['lumbers'] -= floor($user['Population']['lumbers']*$percentLoss);
				$user['Population']['miners'] -= floor($user['Population']['miners']*$percentLoss);
				$user['Population']['agriculture'] -= floor($user['Population']['agriculture']*$percentLoss);
				$user['Population']['building'] -= floor($user['Population']['building']*$percentLoss);
				$user['Population']['blacksmiths'] -= floor($user['Population']['blacksmiths']*$percentLoss);
				$user['Population']['explorers'] -= floor($user['Population']['explorers']*$percentLoss);
				$user['Population']['training'] -= floor($user['Population']['training']*$percentLoss);
				
				if($user['Population']['lumbers'] < 0) {
					$user['Population']['lumbers'] = 0;
				}
				if($user['Population']['miners'] < 0) {
					$user['Population']['miners'] = 0;
				}
				if($user['Population']['agriculture'] < 0) {
					$user['Population']['agriculture'] = 0;
				}
				if($user['Population']['building'] < 0) {
					$user['Population']['building'] = 0;
				}
				if($user['Population']['blacksmiths'] < 0) {
					$user['Population']['blacksmiths'] = 0;
				}
				if($user['Population']['explorers'] < 0) {
					$user['Population']['explorers'] = 0;
				}
				if($user['Population']['training'] < 0) {
					$user['Population']['training'] = 0;
				}
				
				$user['Population']['total_population'] = $user['Population']['lumbers'] + $user['Population']['miners'] + $user['Population']['agriculture'] +
					$user['Population']['building'] + $user['Population']['blacksmiths'] + $user['Population']['explorers'] + $user['Population']['training'];
			}
		}
		return $user;
	}
	
	function getPerBuilding($user = null) {
		$perBuilding = 0;
		switch($user['race']) {
			case "Orc":
				$perBuilding = 110;					
				break;
			case "Gnome":
				$perBuilding = 110;					
				break;
			default:
				$perBuilding = 150;
				break;
		}
		if($user['Technology']['tree_2'] > 3)
			$perBuilding = round($perBuilding / 2);
					
		return $perBuilding;
	}

	function setPopulation($newPopulation = null, $user = null) {
		$perBuilding = $this->getPerBuilding($user['User']);
		$remainingPopulation = $user['Population']['total_population'];

		$population[0]['name'] = "agriculture";
		$population[0]['currentValue'] = $user['Population']['agriculture'];
		$population[0]['newValue'] = 0;
		$population[0]['newTotalValue'] = $newPopulation['Population']['farmersTotal'];
		$population[0]['newPerValue'] = $newPopulation['Population']['farmersPer'];
		$population[0]['buildings'] = $user['User']['Building']['farm'];
		$population[0]['totalIsChanged'] = false;
		$population[0]['perIsChanged'] = false;
		$population[0]['isSet'] = false;
		$population[1]['name'] = "lumbers";
		$population[1]['currentValue'] = $user['Population']['lumbers'];
		$population[1]['newValue'] = 0;
		$population[1]['newTotalValue'] = $newPopulation['Population']['lumbersTotal'];
		$population[1]['newPerValue'] = $newPopulation['Population']['lumbersPer'];
		$population[1]['buildings'] = $user['User']['Building']['mill'];
		$population[1]['totalIsChanged'] = false;
		$population[1]['perIsChanged'] = false;
		$population[1]['isSet'] = false;
		$population[2]['name'] = "miners";
		$population[2]['currentValue'] = $user['Population']['miners'];
		$population[2]['newValue'] = 0;
		$population[2]['newTotalValue'] = $newPopulation['Population']['minersTotal'];
		$population[2]['newPerValue'] = $newPopulation['Population']['minersPer'];
		$population[2]['buildings'] = $user['User']['Building']['mine'];
		$population[2]['totalIsChanged'] = false;
		$population[2]['perIsChanged'] = false;
		$population[2]['isSet'] = false;
		$population[3]['name'] = "building";
		$population[3]['currentValue'] = $user['Population']['building'];
		$population[3]['newValue'] = 0;
		$population[3]['newTotalValue'] = $newPopulation['Population']['buildersTotal'];
		$population[3]['newPerValue'] = $newPopulation['Population']['buildersPer'];
		$population[3]['buildings'] = $perBuilding;
		$population[3]['totalIsChanged'] = false;
		$population[3]['perIsChanged'] = false;
		$population[3]['isSet'] = false;
		$population[4]['name'] = "training";
		$population[4]['currentValue'] = $user['Population']['training'];
		$population[4]['newValue'] = 0;
		$population[4]['newTotalValue'] = $newPopulation['Population']['trainingTotal'];
		$population[4]['newPerValue'] = $newPopulation['Population']['trainingPer'];
		$population[4]['buildings'] = 1;
		$population[4]['totalIsChanged'] = false;
		$population[4]['perIsChanged'] = false;
		$population[4]['isSet'] = false;
		$population[5]['name'] = "blacksmiths";
		$population[5]['currentValue'] = $user['Population']['blacksmiths'];
		$population[5]['newValue'] = 0;
		$population[5]['newTotalValue'] = $newPopulation['Population']['blacksmithsTotal'];
		$population[5]['newPerValue'] = $newPopulation['Population']['blacksmithsPer'];
		$population[5]['buildings'] = $user['User']['Building']['forge'];
		$population[5]['totalIsChanged'] = false;
		$population[5]['perIsChanged'] = false;
		$population[5]['isSet'] = false;
		$population[6]['name'] = "explorers";
		$population[6]['currentValue'] = $user['Population']['explorers'];
		$population[6]['newValue'] = 0;
		$population[6]['newTotalValue'] = $newPopulation['Population']['exploringTotal'];
		$population[6]['newPerValue'] = $newPopulation['Population']['exploringPer'];
		$population[6]['buildings'] = 1;
		$population[6]['totalIsChanged'] = false;
		$population[6]['perIsChanged'] = false;
		$population[6]['isSet'] = false;
		
					
		// Must be passed as reference. foreach() operates on a copy of the array, not the array itself
		// Check to see if value isChanged
		foreach($population as &$category):
			if($category['name'] != 'building'){
				if($category['buildings'] == 0) {
					$category['newTotalValue'] = 0;
					$category['totalIsChanged'] = true;
				} else {
					if($category['newTotalValue'] != $category['currentValue']) {
						$category['totalIsChanged'] = true;
					}
					elseif(($category['newPerValue'] * $category['buildings']) != $category['currentValue'] && round($category['newTotalValue'] / $category['buildings']) != $category['newPerValue'])
						$category['perIsChanged'] = true;
					else{}
				}
			}
			else {
				if($category['newTotalValue'] != $category['currentValue']) {
					$category['totalIsChanged'] = true;
				}
				elseif(($category['newPerValue'] * $category['buildings']) != $category['currentValue'] && floor($category['newTotalValue'] / $category['buildings']) != $category['newPerValue'])
					$category['perIsChanged'] = true;
				else{}
			}
		endforeach;
				
		// Set the ones that isChanged
		foreach($population as &$category):
			if($category['totalIsChanged'] && ($category['newTotalValue'] <= $remainingPopulation)) {
				if($category['newTotalValue'] < 0)
					$category['newTotalValue'] = 0;
				$remainingPopulation -= $category['newTotalValue'];
				$category['newValue'] = $category['newTotalValue'];
				$category['isSet'] = true;
			}
			elseif($category['totalIsChanged'] && ($category['newTotalValue'] > $remainingPopulation)){
				$category['newValue'] = $remainingPopulation;
				$remainingPopulation = 0;
				$category['isSet'] = true;
			}
			elseif($category['perIsChanged'] && (($category['newPerValue'] * $category['buildings']) <= $remainingPopulation)) {
				if($category['newPerValue'] < 0)
					$category['newPerValue'] = 0;
				$remainingPopulation -= $category['newPerValue'] * $category['buildings'];
				$category['newValue'] = $category['newPerValue'] * $category['buildings'];
				$category['isSet'] = true;
			}
			elseif($category['perIsChanged'] && (($category['newPerValue'] * $category['buildings']) > $remainingPopulation)){
				$category['newValue'] = $remainingPopulation;
				$remainingPopulation = 0;
				$category['isSet'] = true;
			}
		endforeach;
				
		// Set the ones that !isSet
		foreach($population as &$category):
			if(!$category['isSet'] && ($category['currentValue'] <= $remainingPopulation) && $category['name'] != 'training') {
				$remainingPopulation -= $category['currentValue'];
				$category['newValue'] = $category['currentValue'];
				$category['isSet'] = true;
			}
			elseif(!$category['isSet'] && $category['name'] != 'training') {
				$category['newValue'] = $remainingPopulation;
				$remainingPopulation = 0;
				$category['isSet'] = true;
			}
		endforeach;
		
		// If there's remaining population, put it into 'training'
		if($remainingPopulation > 0)
			$population[4]['newValue'] += $remainingPopulation;

		// This loop is messing up the array? wtf. <strike>It's not even passed by reference?</strike>
		// Not passed by reference was the problem
		// Set into array that can be saved.
		foreach($population as &$category):
			$name = $category['name'];
			$user['Population'][$name] = $category['newValue'];
		endforeach;

		$this->save($user);
		return $user;
	}


}
?>