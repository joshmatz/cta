<?php
class Resource extends AppModel {    
	var $name = 'Resource';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id'
		)
	);
	
	function getResouceTypes($race = null){
		// Default array is for all races.
		$resources = array(
			array(
				'name' => "Wood",
				'ratePerHour' => 5,
				'building' => 'Mill'
			),
			array(
				'name' => "Iron",
				'ratePerHour' => 4,
				'building' => 'Mill'
			),
			array(
				'name' => "Gold",
				'ratePerHour' => null,
				'building' => 'Mill'
			),
			array(
				'name' => "Food",
				'ratePerHour' => 120,
				'building' => 'Mill'
			)
		);
		
		// Add to array for race specific resources
		switch($race){
			case "Elf":
			break;
			case "Orc":
			break;
			case "Human":
			break;
			case "Dwarf":
			break;
			case "Gnome":
			break;
		}
		
		return $resources;
	}

	function updateFood($user = null) {
		
		if($user['Population']['agriculture'] > 0 && $user['Building']['farm']) {
			if(isset($user['User'])){
				
			}
			else {
				$user['User']['race'] = $user['race'];
				$user['User']['crime_rate'] = $user['crime_rate'];
				$user['User']['loyalty'] = $user['loyalty'];
				//$user['User']['victim_bonus'] = $user['victim_bonus'];
			}
			switch($user['User']['race']) {
				case "Orc":
					$bonus = 15;
					break;
				default:
					$bonus = 0;
					break;
			}
			// irrigation implemented here
			if ($user['Technology']['tree_3'] > 0)
				$bonus += 15;
			$optimalLevel = 60;
			$workersPerFarm = $user['Population']['agriculture'] / $user['Building']['farm'];
			
			$growingCrop = 1;
			if($user['Spell']['growing_crop'] > 0)
				$growingCrop = 1.25;
			if ($user['Spell']['freeze'] > 0)
				$growingCrop = .9;
			if($user['Spell']['god_blessing'] > 0)
				$growingCrop = 1.30;
			if($user['Spell']['god_blessing'] && $user['Spell']['freeze'])
				$growingCrop = 1.2;
			if($user['Spell']['ice_storm'])
				$growingCrop = 0;
			if($user['Spell']['ice_storm'] && $user['Spell']['growing_crop'])
				$growingCrop = 1;
			if($user['Spell']['ice_storm'] && $user['Spell']['growing_crop'] && $user['Spell']['god_blessing'])
				$growingCrop = 1.3;
			else if($user['Spell']['ice_storm'] && !$user['Spell']['growing_crop'] && $user['Spell']['god_blessing'])
				$growingCrop = .3;
			if($user['Spell']['ice_storm'] && $user['Spell']['growing_crop'] && $user['Spell']['god_blessing'] && $user['Spell']['freeze'])
				$growingCrop = 1.2;
				
			$x = ($workersPerFarm/$optimalLevel);
			//echo "X: $x<br />";
			// This pow($x-1, 1.5) is the problem, returns NaN when $x-1 is negative.
			// Brought in a different function that should allow for negative bases, not sure if it works. 
			// Numbers appear to be slightly off of what they should be. When $x == 0, the function algorithm returns .15
			
			//echo "algorithm: " . (-.3 * pow($x-1, 1.5) + .55 * ($x-1) + 1) ." <br /> <br />";
			if($x <= 1)
				$foodProduced = round(pow($x, 1.5) * ($bonus + 150) * $user['Building']['farm'] * $growingCrop);
			else
				$foodProduced = round(((-.3 * pow($x-1, 1.5)) + .55 * ($x-1) + 1) * ($bonus + 150) * $user['Building']['farm'] * $growingCrop);
			if($foodProduced < 0)
				$foodProduced = 0;
				//echo "Food produced without crimerate: $foodProduced <br />";
			//$foodProduced = round(($bonus + 100) * $user['Building']['farm'] * $growingCrop * $x);
			$foodProduced = $foodProduced + round($foodProduced * ($user['User']['loyalty'] / 100)) + round($foodProduced /** ($user['User']['victim_bonus'] / 100)*/);
			
			if($user['User']['crime_rate'] > 0 && !$user['Spell']['god_blessing']){
				$foodProduced = $foodProduced - round($foodProduced * ($user['User']['crime_rate'] / 100));
				//echo "Food production affected by God Blessing <br />";
			}
			//echo "Population on food: " . $user['Population']['agriculture'] . "<Br />";
			//echo "Food produced: $foodProduced <br />";
			
		}
		else {
			$foodProduced = 0;
		}
		
		$user['Resource']['food'] += $foodProduced;
		
		return $user;
	}
	
	function updateGold($user = null){
		
		if(isset($user['User'])){
				
		}
		else {
			$user['User']['race'] = $user['race'];
			$user['User']['crime_rate'] = $user['crime_rate'];
			$user['User']['loyalty'] = $user['loyalty'];
				//$user['User']['victim_bonus'] = $user['victim_bonus'];
		}
		$blessing = 1;
		if($user['Spell']['blessing'] > 0)
			$blessing = 1.3;
		if ($user['Spell']['freeze'] > 0)
			$blessing = .9;
		if($user['Spell']['god_blessing'] > 0)
			$blessing = 1.7;
		if($user['Spell']['god_blessing'] && $user['Spell']['freeze'])
			$blessing = 1.4;
			
		$tech = 1;
		if($user['Technology']['tree_2'] > 1)
			$tech = 1.25;
			
		
		$marketRatio = $user['Building']['market'] / $user['Resource']['land'];
		if($marketRatio > 1)
			$marketRatio = 1;
		// These numbers were all positive
		//	echo "<br />Gold reducer: $goldReducer <br /> Tech: $tech <br /> Blessing: $blessing: <br /> Market Ratio: $marketRatio <br />";
		$marketMultiplier = ($blessing * $tech * (1+ .02*$user['Building']['castle']));
		// This number was a 0 when rounded. Maybe it doesn't need to be. Not sure.
		//echo "Market multiplier: $marketMultiplier";
		$goldProduced = round($marketRatio * $user['Population']['total_population'] * $marketMultiplier) + 2500;
		$goldProduced = $goldProduced + round($goldProduced * ($user['User']['loyalty'] / 100)) + round($goldProduced/* * ($user['User']['victim_bonus'] / 100)*/);
		if($user['User']['crime_rate'] > 0 && !$user['Spell']['god_blessing'])
			$goldProduced = $goldProduced - round($goldProduced * ($user['User']['crime_rate'] / 100));
		$user['Resource']['gold'] += $goldProduced;
		
		
		return $user;
	}

	function updateIron($user = null){
		$blessing = 1;
		if($user['Spell']['god_blessing'] > 0)
			$blessing = 1.1;
		if ($user['Spell']['freeze'] > 0)
			$blessing = .9;
		if($user['Spell']['god_blessing'] && $user['Spell']['freeze'])
			$blessing = 1;
		if($user['Population']['miners'] > 0)
		{
			if(isset($user['User'])){
				
			}
			else {
				$user['User']['race'] = $user['race'];
				$user['User']['crime_rate'] = $user['crime_rate'];
				$user['User']['loyalty'] = $user['loyalty'];
				//$user['User']['victim_bonus'] = $user['victim_bonus'];
			}
			switch($user['User']['race']) {
				case "Dwarf":
					$bonus = 3;
					break;
				case "Orc":
					$bonus = 2;
					break;
				case "Human":
					$bonus = 2;
					break;
				default:
					$bonus = 0;
					break;
			}
			$minPerMine = 2;
			$optimalLevel = 100;
			//machinery tech
			if ($user['Technology']['tree_3'] >= 2)
				$bonus += 1.5;
			
			$minersPerMine = $user['Population']['miners'] / $user['Building']['mine'];
			$x = ($minersPerMine/$optimalLevel);
			// echo "Miners per / optimal: $x <br />";
			// This next line isn't working correctly. Look at the stats page to see the problem.
			if($x <= 1)
				$ironMined = round($blessing*pow($x, 1.5) * ($bonus + 4) * $user['Building']['mine']);
			else
				$ironMined = round($blessing*((-.3 * pow($x-1, 1.5)) + .55 * ($x-1) + 1) * ($bonus + 4) * $user['Building']['mine']);
			if($ironMined < 0)
				$ironMined = 0;
			$ironMined = $ironMined + round($ironMined * ($user['User']['loyalty'] / 100)) + round($ironMined/* * ($user['User']['victim_bonus'] / 100)*/);
			if($user['User']['crime_rate'] > 0 && !$user['Spell']['god_blessing'])
				$ironMined = $ironMined - round($ironMined * ($user['User']['crime_rate'] / 100));	
				
			$user['Resource']['iron'] += $ironMined;
		}
		
		return $user;
	}
	
	function updateWood($user = null) {
		$blessing = 1;
		if($user['Spell']['god_blessing'] > 0)
			$blessing = 1.1;
		if ($user['Spell']['freeze'] > 0)
			$blessing = .9;
		if($user['Spell']['god_blessing'] && $user['Spell']['freeze'])
			$blessing = 1;
		if($user['Population']['lumbers'] > 0) {
			if(isset($user['User'])){
				
			}
			else {
				$user['User']['race'] = $user['race'];
				$user['User']['crime_rate'] = $user['crime_rate'];
				$user['User']['loyalty'] = $user['loyalty'];
				//$user['User']['victim_bonus'] = $user['victim_bonus'];
			}
			switch($user['User']['race']) {
				case "Elf":
					$bonus = 3;
					break;
				case "Orc":
					$bonus = 2;
					break;
				default:
					$bonus = 0;
					break;
			}
			if ($user['Technology']['tree_3'] >= 3)
				$bonus += 1.5;
			$minPerMill = 2;
			$optimalLevel = 85;
			$lumbersPerMill = $user['Population']['lumbers'] / $user['Building']['mill'];
			$x = $lumbersPerMill / $optimalLevel;
			// this is a similar piecewise function as used in mining
			if($x <= 1)
				$woodChopped = round($blessing*pow($x, 1.5) * ($bonus + 5) * $user['Building']['mill']);
			else
				$woodChopped = round($blessing*((-.3 * pow($x-1, 1.5)) + .55 * ($x-1) + 1) * ($bonus + 5) * $user['Building']['mill']);
			if($woodChopped < 0)
				$woodChopped = 0;
			
			$woodChopped = $woodChopped + round($woodChopped * ($user['User']['loyalty'] / 100)) + round($woodChopped/* * ($user['User']['victim_bonus'] / 100)*/);
			if($user['User']['crime_rate'] > 0 && !$user['Spell']['god_blessing'])
				$woodChopped = $woodChopped - round($woodChopped * ($user['User']['crime_rate'] / 100));
				
			$user['Resource']['wood'] += $woodChopped;
		}
			
		return $user;
	}
	
	function updateLand($user = null) {
		if($user['Population']['explorers'] > 0)
		{
			if(isset($user['User'])){
				
			}
			else {
				$user['User']['race'] = $user['race'];
				$user['User']['crime_rate'] = $user['crime_rate'];
				$user['User']['loyalty'] = $user['loyalty'];
				//$user['User']['victim_bonus'] = $user['victim_bonus'];
			}
			$farseeing = 0;
			if ($user['Spell']['farseeing'] > 0)
				$farseeing = 3;
				
			$percentExploring = $user['Population']['explorers'] / $user['Population']['total_population'];
			$landExplored = 0;
			// some chance between 1-100
			if($user['Technology']['tree_1'] > 0)
				$bonus = 2;
			else 
				$bonus = 1;
			$chanceToFind = ($percentExploring*80) * $bonus/pow($user['Resource']['land'], .15);
			//echo "<br>Percent Exploring: " . $percentExploring;
			//echo "<br>Chance to Find: " . $chanceToFind;
			// tests whether they find 1 land, 3 times.
			for ($i = 0; $i < (3 + $farseeing); $i++){
				$rand = mt_rand(1, 100);
				if ($rand < $chanceToFind)
					$landExplored += 1;
			} // max land they can find is 3 per hour
			
			$landExplored = $landExplored + round($landExplored * ($user['User']['loyalty'] / 100)) + round($landExplored/* * ($user['User']['victim_bonus'] / 100)*/);
			if($user['User']['crime_rate'] > 0 && !$user['Spell']['god_blessing'])
				$landExplored = $landExplored - round($landExplored * ($user['User']['crime_rate'] / 100));
			$user['Resource']['land'] += $landExplored;
		}
		
		return $user;
	}
}
?>