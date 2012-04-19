<?php
class StatisticsController extends AppController {    
	var $name = 'Statistics';
	var $uses = array();
	var $layout = 'redesign';
	
	/*
	 * A nice little timer system to test how fast code is executed
	 * 
	 * $this->timer();
	 * // Code code code
	 * echo 'Stuff took '.$this->parse().' to do in total <br />';
	 */
	var $start = '';
	function timer() {
		$timer_tmp = explode(' ',microtime());
		$this->start = (float)$timer_tmp[1] + $timer_tmp[0];
	}
         
	function parse($mode=0) {
		$timer_tmp = explode(' ',microtime());
		$now = (float)$timer_tmp[1] + $timer_tmp[0];
           
		switch($mode) {
		case 1: // Full, in seconds
			return $now - $this->start;
            break;
               
       	case 0:
        default: // Format: 'x.xxx seconds'
			return sprintf('%01.3f',$now - $this->start).' seconds';
			break;
		}
	}
         
	function display($mode=0) {
		//echo $this->parse($mode);
	}
	
	function beforeFilter() { 
		parent::beforeFilter(); 
        //$this->facebook_id = $this->facebookId; 
        $this->loadModel('User'); 
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->User->id = $this->user['User']['id'];
        //$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
    }  
    
    function support() {
    	$this->set('user', $this->user);
    }
    
	function index() {
		
		$this->user['Resource']['foodUpkeep'] = 0;
		$this->user['Resource']['goldUpkeep'] = 0;
		$this->user['Resource']['foodBefore'] = $this->user['Resource']['food'];
		$this->user['Resource']['goldBefore'] = $this->user['Resource']['gold'];	
		$this->user['Resource']['goldAfter'] = $this->user['Resource']['gold'];
		$this->user['Resource']['foodAfter'] = $this->user['Resource']['food'];	
		$this->set('user', $this->user);
		
		$weaponsProduced = $this->User->Weapon->getWeaponsProduced($this->user);
		$restedWizards = $this->getRestingWizards($this->user);
		// Get hourly production
		$this->user = $this->getGoldAndFoodStatistics($this->user);
		$this->set('hour', $this->user);
		// Get daily production
		for($i = 0; $i < 23; $i++) {
			$this->user = $this->getGoldAndFoodStatistics($this->user);
		}
		$this->set('day', $this->user);
		
		// Get weekly production 
		/*
		for($i = 0; $i < 144; $i++) {
			$this->user = $this->getGoldAndFoodStatistics($this->user);
		}
		$this->set('week', $this->user);
		*/
		
		
		$unitNames = $this->getUnitNames($this->user);
		$units = $this->getUnitsTrained($this->user);
		$unitsTrained = $units['Army'];
		
		
		$this->set('unitNames', $unitNames);
		$this->set('weapons', $weaponsProduced);
		$this->set('unitsTrained', $unitsTrained);
		$this->set('restedWizards', $restedWizards);
	}
	
	function getGoldAndFoodStatistics($user = null){
		$user = $this->User->Population->updatePopulation($user);	
		$user = $this->User->Resource->updateGold($user);
		$user = $this->User->Resource->updateFood($user);
		$user = $this->User->Resource->updateWood($user);
		$user = $this->User->Resource->updateIron($user);	
		
		
			/*
			 * Calculate unit deaths if not enough food or gold
			 */
			$totalEaten = 0;
			$goldSpent = 0;
			$eaters = $this->User->Army->getFullArmyData($user);
			
			
			// These first two sets are because getFullArmyData does not include this, and I was lazy in the second one to not place 7-13 with their 
			// respective groups.
			// $food_consumed is how much one unit eats of that type. 
			$eaters[0]['percent_eating'] = 0;
			$eaters[1]['percent_eating'] = 0;
			$eaters[2]['percent_eating'] = 0;
			$eaters[3]['percent_eating'] = 0;
			$eaters[4]['percent_eating'] = 0;
			$eaters[5]['percent_eating'] = 0;
			$eaters[6]['percent_eating'] = 0;
			
			$eaters[0]['amount_dieing'] = 0;
			$eaters[1]['amount_dieing'] = 0;
			$eaters[2]['amount_dieing'] = 0;
			$eaters[3]['amount_dieing'] = 0;
			$eaters[4]['amount_dieing'] = 0;
			$eaters[5]['amount_dieing'] = 0;
			$eaters[6]['amount_dieing'] = 0;
			$eaters[7]['amount_dieing'] = 0;
			$eaters[8]['amount_dieing'] = 0;
			$eaters[9]['amount_dieing'] = 0;
			$eaters[10]['amount_dieing'] = 0;
			$eaters[11]['amount_dieing'] = 0;
			$eaters[12]['amount_dieing'] = 0;
			$eaters[13]['amount_dieing'] = 0;
			
			$eaters[7]['type'] = "lumbers";
			$eaters[7]['amount'] = $user['Population']['lumbers'];
			$eaters[7]['amount_away'] = 0;
			$eaters[7]['gold_consumed'] = 0;
			$eaters[7]['food_consumed'] = .1667;
			$eaters[7]['percent_eating'] = 0;
			
			$eaters[8]['type'] = "miners";
			$eaters[8]['amount'] = $user['Population']['miners'];
			$eaters[8]['amount_away'] = 0;
			$eaters[8]['gold_consumed'] = 0;
			$eaters[8]['food_consumed'] = .1667;
			$eaters[8]['percent_eating'] = 0;
			
			$eaters[9]['type'] = "agriculture";
			$eaters[9]['amount'] = $user['Population']['agriculture'];
			$eaters[9]['amount_away'] = 0;
			$eaters[9]['gold_consumed'] = 0;
			$eaters[9]['food_consumed'] = .1667;
			$eaters[9]['percent_eating'] = 0;
			
			$eaters[10]['type'] = "building";
			$eaters[10]['amount'] = $user['Population']['building'];
			$eaters[10]['amount_away'] = 0;
			$eaters[10]['gold_consumed'] = 0;
			$eaters[10]['food_consumed'] = .1667;
			$eaters[10]['percent_eating'] = 0;
			
			$eaters[11]['type'] = "blacksmiths";
			$eaters[11]['amount'] = $user['Population']['blacksmiths'];
			$eaters[11]['amount_away'] = 0;
			$eaters[11]['gold_consumed'] = 0;
			$eaters[11]['food_consumed'] = .1667;
			$eaters[11]['percent_eating'] = 0;
			
			$eaters[12]['type'] = "explorers";
			$eaters[12]['amount'] = $user['Population']['explorers'];
			$eaters[12]['amount_away'] = 0;
			$eaters[12]['gold_consumed'] = 0;
			$eaters[12]['food_consumed'] = .1667;
			$eaters[12]['percent_eating'] = 0;
			
			$eaters[13]['type'] = "training";
			$eaters[13]['amount'] = $user['Population']['training'];
			$eaters[13]['amount_away'] = 0;
			$eaters[13]['gold_consumed'] = 0;
			$eaters[13]['food_consumed'] = .1667;
			$eaters[13]['percent_eating'] = 0;
			
			$eaters[14]['type'] = "wizard_resting";
			$eaters[14]['amount'] = $user['Army']['wizard_resting'];
			$eaters[14]['amount_away'] = 0;
			$eaters[14]['gold_consumed'] = $eaters[6]['gold_consumed'];
			$eaters[14]['food_consumed'] = $eaters[6]['food_consumed'];
			$eaters[14]['percent_eating'] = 0;
			
			
			// This foreach adds in all the away units.
			$i = 15;
			$attackCount = 0;
			foreach($user['Attack'] as $attack):
				$eaters[$i]['type'] = 'weak';
				$eaters[$i]['amount'] = $attack['weak'];
				$eaters[$i]['amount_away'] = 0;
				$eaters[$i]['food_consumed'] = $eaters[0]['food_consumed'] * 2;
				$eaters[$i]['gold_consumed'] = $eaters[0]['gold_consumed'] * 2;
				$eaters[$i]['percent_eating'] = 0;
				$eaters[$i]['amount_dieing'] = 0;
				$i++;
				$eaters[$i]['type'] = 'standard';
				$eaters[$i]['amount'] = $attack['standard'];
				$eaters[$i]['amount_away'] = 0;
				$eaters[$i]['food_consumed'] = $eaters[1]['food_consumed'] * 2;
				$eaters[$i]['gold_consumed'] = $eaters[1]['gold_consumed'] * 2;
				$eaters[$i]['percent_eating'] = 0;
				$eaters[$i]['amount_dieing'] = 0;
				$i++;
				$eaters[$i]['type'] = 'small_elite';
				$eaters[$i]['amount'] = $attack['small_elite'];
				$eaters[$i]['amount_away'] = 0;
				$eaters[$i]['food_consumed'] = $eaters[2]['food_consumed'] * 2;
				$eaters[$i]['gold_consumed'] = $eaters[2]['gold_consumed'] * 2;
				$eaters[$i]['percent_eating'] = 0;
				$eaters[$i]['amount_dieing'] = 0;
				$i++;
				$eaters[$i]['type'] = 'big_elite';
				$eaters[$i]['amount'] = $attack['big_elite'];
				$eaters[$i]['amount_away'] = 0;
				$eaters[$i]['food_consumed'] = $eaters[3]['food_consumed'] * 2;
				$eaters[$i]['gold_consumed'] = $eaters[3]['gold_consumed'] * 2;
				$eaters[$i]['percent_eating'] = 0;
				$eaters[$i]['amount_dieing'] = 0;
				$i++;
				$eaters[$i]['type'] = 'range';
				$eaters[$i]['amount'] = $attack['range'];
				$eaters[$i]['amount_away'] = 0;
				$eaters[$i]['food_consumed'] = $eaters[4]['food_consumed'] * 2;
				$eaters[$i]['gold_consumed'] = $eaters[4]['gold_consumed'] * 2;
				$eaters[$i]['percent_eating'] = 0;
				$eaters[$i]['amount_dieing'] = 0;
				$i++;
				$eaters[$i]['type'] = 'mount';
				$eaters[$i]['amount'] = $attack['mount'];
				$eaters[$i]['amount_away'] = 0;
				$eaters[$i]['food_consumed'] = $eaters[4]['food_consumed'] * 2;
				$eaters[$i]['gold_consumed'] = $eaters[4]['gold_consumed'] * 2;
				$eaters[$i]['percent_eating'] = 0;
				$eaters[$i]['amount_dieing'] = 0;
				$i++;
				$attackCount++;
			endforeach;
			/*
			echo "Total Population: <br />";
			echo $eaters[7]['type'] . ": " . $eaters[7]['amount'] . "<br />";
			echo $eaters[8]['type'] . ": " . $eaters[8]['amount'] . "<br />";
			echo $eaters[9]['type'] . ": " . $eaters[9]['amount'] . "<br />";
			echo $eaters[10]['type'] . ": " . $eaters[10]['amount'] . "<br />";
			echo $eaters[11]['type'] . ": " . $eaters[11]['amount'] . "<br />";
			echo $eaters[12]['type'] . ": " . $eaters[12]['amount'] . "<br />";
			echo $eaters[13]['type'] . ": " . $eaters[13]['amount'] . "<br />";
			*/
			// This calculates gold spent and food eaten
			foreach($eaters as $eater):
				$totalEaten += ($eater['amount'] * $eater['food_consumed']);
				//echo $eater['type'] . " adding: " . ($eater['amount'] * $eater['food_consumed']) . "<br />";
				//echo "New total: $totalEaten <br />";
			endforeach;
			
			$user['Resource']['foodUpkeep'] += round($totalEaten);
			
			// Kill units because user has no food.
			if($user['Resource']['food'] < 0) {
				$negFood = abs($user['Resource']['food']);
				//echo "Negative food: $negFood <br />";
				foreach($eaters as &$eater):
					$eater['percent_eating'] = ($eater['amount'] * $eater['food_consumed']) / $totalEaten;
					//echo $eater['type'] . " has a total of " . $eater['amount'] . " and has a ";
					//echo "Percent eating of " . $eater['percent_eating'] . ", and should eat " . ($eater['food_consumed']*$eater['amount']) . " total. <br />";
				endforeach;
				foreach($eaters as &$eater):
					if($eater['amount'] != 0 && $eater['food_consumed'] != 0) {
						$eater['amount_dieing'] = round($eater['percent_eating'] * $negFood / $eater['food_consumed']);						
					}
					else
						$eater['amount_dieing'] = 0;
				endforeach;
				for($i = 0; $i < 15; $i++) {
					$name = $eaters[$i]['type'];
					if($i < 7 || $i == 14) {
						$user['Army'][$name] -=  $eaters[$i]['amount_dieing'];
						$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
						//echo "$name dieing: " . $eaters[$i]['amount_dieing'] . "<br />";
					}
					else {
						$user['Population'][$name] -= $eaters[$i]['amount_dieing'];
						$user['Population']['total_population'] -= $eaters[$i]['amount_dieing'];
						$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
						//echo "$name dieing: " . $eaters[$i]['amount_dieing'] . "<br />";
					}
				}
				$attackIndex = 0;
				for($i = 15; $i < $attackCount + 15; $i++) {
					for($j = 0; $j < 6; $j++) {
						$name = $eaters[$i]['type'];
						$user['Attack'][$attackIndex][$name] -=  $eaters[$i]['amount_dieing'];
						$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
						$i++;
					}
					$attackIndex++;
				}
				$user['Resource']['food'] = 0;
			}	
			
			foreach($eaters as $eater):
				$goldSpent += ($eater['amount'] * $eater['gold_consumed']);
				
			endforeach;
			
			$user['Resource']['goldUpkeep'] += round($goldSpent);
			
			// Kill military because user has no gold
			if($user['Resource']['gold'] < 0 && $goldSpent != 0) {
				$negGold = abs($user['Resource']['gold']);
				//echo "Negative gold: $negGold <br />";
				foreach($eaters as &$eater):
					$eater['percent_eating'] = ($eater['amount'] * $eater['gold_consumed']) / $goldSpent;
					////echo $eater['type'] . " has a total of " . $eater['amount'] . " and has a ";
					////echo "Percent eating of " . $eater['percent_eating'] . "  <br />";
				endforeach;
				foreach($eaters as &$eater):
					if($eater['amount'] != 0 && $eater['gold_consumed'] != 0) {
						$eater['amount_dieing'] = round(($eater['percent_eating'] * $negGold) / $eater['gold_consumed']);		
						////echo "Percent: " . $eater['percent_eating'] . ". Amount dieing of " . $eater['type'] . " is " . $eater['amount_dieing'] . ". <br />";	
						if($eater['amount_dieing'] < 0)
							$eater['amount_dieing'] = 0;	
					}
					else
						$eater['amount_dieing'] = 0;
				endforeach;
				for($i = 0; $i < 14; $i++) {
					$name = $eaters[$i]['type'];
					if($i < 7 || $i == 14) {
						$user['Army'][$name] -=  $eaters[$i]['amount_dieing'];
						$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
					}
					else {
						$user['Population'][$name] -= $eaters[$i]['amount_dieing'];
						$user['Population']['total_population'] -= $eaters[$i]['amount_dieing'];
						$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
					}
				}
				$attackIndex = 0;
				for($i = 14; $i < $attackCount + 14; $i++) {
					for($j = 0; $j < 6; $j++) {
						$name = $eaters[$i]['type'];
						$user['Attack'][$attackIndex][$name] -=  $eaters[$i]['amount_dieing'];
						$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
						$i++;
					}
					$attackIndex++;
				}
				$user['Resource']['gold'] = 0;
			}	
			
			if($user['Resource']['gold'] < 0)
				$user['Resource']['gold'] = 0;
		
		return $user;
	}
	
	function getFoodProduced($user = null) {
		if($user['Population']['agriculture'] > 0) {
			switch($user['User']['race']) {
				case "Orc":
					$bonus = 15;
					break;
				default:
					$bonus = 0;
					break;
			}
			$minPerFarm = 45;
			$optimalLevel = 60;
			$workersPerFarm = $user['Population']['agriculture'] / $user['Building']['farm'];
			
			$growingCrop = 1;
			if($user['Spell']['growing_crop'] > 0)
				$growing_crop = 1.25;
			if ($workersPerFarm < 60)
				$foodProduced = round((8/(7 + pow(1.1, ($optimalLevel-$workersPerFarm)))) * ($bonus + 100) * $user['Building']['farm'] * $growingCrop);				
			if ($workersPerFarm >= 60)
				$foodProduced = round((5*2.7^(-((($optimalLevel - $workersPerFarm)-15.4)^2)/800)) * ($bonus + 100) * $user['Building']['farm'] * $growingCrop);
			
		}
		else
			$foodProduced = 0;

			
		return $foodProduced;
	}

	function getGoldProduced($user = null) {
		if($user['Spell']['blessing'] > 0)
			$blessing = 1.1;
		else
			$blessing = 1;
		$marketRatio = $user['Building']['market'] / $user['Resource']['land'];
		if($marketRatio > 1)
			$marketRatio = 1;
		$marketMultiplier = round(((80 + .5*$user['Building']['market'])/100) * $blessing);
		$goldProduced = round($marketRatio * $user['Population']['total_population'] * $marketMultiplier);
	
		
		return $goldProduced;
	}
	
	function getWoodProduced($user = null) {
		if($user['Population']['lumbers'] > 0) {
			switch($user['User']['race']) {
				case "Elf":
					$bonus = 1;
					break;
				case "Orc":
					$bonus = 2;
					break;
				default:
					$bonus = 0;
					break;
			}
			$minPerMill = 2;
			$optimalLevel = 85;
			$lumbersPerMill = $user['Population']['lumbers'] / $user['Building']['mill'];
			// this is a similar piecewise function as used in mining
			if ($lumbersPerMill < 86)
				$woodChopped = round((($optimalLevel - $lumbersPerMill)/42.5)^2) * ($bonus + 4) * $user['Building']['mill'];
			if ($lumbersPerMill > 85)
				$woodChopped = round(5*2.7^(-((($optimalLevel - $lumbersPerMill)-15.4)^2)/800)) * ($bonus + 4) * $user['Building']['mill'];
			if ($lumbersPerMill < 0)
				$woodChopped = 0;
		}
		else
			$woodChopped = 0;
		return $woodChopped;
	}

	function getIronProduced($user = null) {
	if($user['Population']['miners'] > 0)
		{
			switch($user['User']['race']) {
				case "Dwarf":
					$bonus = 1;
					break;
				case "Orc":
					$bonus = 2;
					break;
				default:
					$bonus = 0;
					break;
			}
			$minPerMine = 2;
			$optimalLevel = 100;
			$minersPerMine = $user['Population']['miners'] / $user['Building']['mine'];
			// this is a piecewise function
			if ($minersPerMine < 101)
				$ironMined = round((($optimalLevel - $minersPerMine)/50)^2) * ($bonus + 4) * $user['Building']['mine'];
			if ($minersPerMine > 100)
				$ironMined = round(5*2.7^(-((($optimalLevel - $minersPerMine)-15.4)^2)/800)) * ($bonus + 4) * $user['Building']['mine'];
			if ($minersPerMine < 0)
				$ironMined = 0;
		}
		else
			$ironMined = 0;
		return $ironMined;
	}
	
	function getUnitNames($user = null){
		switch($user['User']['race']){
		case "Orc":
			$units = array("Slother", "Rusher", "Slinger", "Axe Thrower", "Wolf Master", "Shadow Warrior", "Wizard");
			break;
		case "Gnome":
			$units = array("Militia", "Infantry", "Rock Thrower", "Rider", "Ballista", "Catapult", "Wizard");
			break;
		case "Dwarf":
			$units = array("Hammer wielder", "Axeman", "Light Crossbowman", "Berserker", "Runner", "Shieldbearer", "Wizard");
			break;
		case "Elf":
			$units = array("Lanceman", "Swordsman", "Archer", "Elite Archer", "Courageous", "Mage", "Wizard");
			break;
		case "Human":
			$units = array("Pikeman", "Infantry", "Archer", "Mounted Archer", "Heavy Infantry", "Knight", "Wizard");
			break;
		default:
			//echo "Error accessing database.";
			break;
		}
		
		return $units;
	}
	
	function getUnitsTrained($user = null){
		$units = $this->User->Army->getFullArmyData($user);
		foreach($units as $unit):
			$type = $unit['type'];
			$typeTraining = $type . "_training";
			if($unit['type'] == "wizard") {
				$trained = floor($user['Building']['school'] / $unit['training_centers_needed']) + $user['Building']['castle'];
				$unitsTrained['Army'][$type] = $trained;
			}
			elseif($unit['training_center']) {
				$trained = floor($user['Building']['training_center'] / $unit['training_centers_needed']) + $user['Building']['castle'];
				$unitsTrained['Army'][$type] = $trained;
			}
			else {
				$trained = floor($user['Building']['advanced_training_center'] / $unit['training_centers_needed']) + $user['Building']['castle'];
				$unitsTrained['Army'][$type] = $trained;	
			}
		endforeach;
		
		return $unitsTrained;
	}
	
	function getPopulationGrowth($user = null){
		switch($user['User']['race']) {
			case "Orc":
				$popPerBuilding = 110;
				break;
			case "Gnome":
				$popPerBuilding = 110;
				break;
			default:
				$popPerBuilding = 150;
				break;
		}
		
		
		// Calc population
		// Ok math :D
		$unitsPerHouse = 100;
		$unitsPerBuilding = 10;
		// First set the difference only using houses
		$difference = (($user['Building']['house'] * $unitsPerHouse ) -  $user['Population']['total_population']);
		$difference += $user['Building']['farm'] * $unitsPerBuilding;
		$difference += $user['Building']['mill'] * $unitsPerBuilding;
		$difference += $user['Building']['mine'] * $unitsPerBuilding;
		$difference += $user['Building']['house'] * $unitsPerBuilding;
		$difference += $user['Building']['castle'] * $unitsPerBuilding;
		$difference += $user['Building']['forge'] * $unitsPerBuilding;
		$difference += $user['Building']['market'] * $unitsPerBuilding;
		$difference += $user['Building']['school'] * $unitsPerBuilding;
		$difference += $user['Building']['medical_center'] * $unitsPerBuilding;
		$difference += $user['Building']['guard_house'] * $unitsPerBuilding; 
		$difference += $user['Building']['guard_tower'] * $unitsPerBuilding;
		$difference += $user['Building']['training_center'] * $unitsPerBuilding;
		$difference += $user['Building']['advanced_training_center'] * $unitsPerBuilding;

		$difference = round($difference / 7);
		if($difference < 0) {
			$naturalGrowth = 1;
		}
		elseif($user['Spell']['natural_growth'] > 0) {
			$naturalGrowth = 1.3;
		}
		else {
			$naturalGrowth = 1;
		}
		
		$populationGrowth = ($difference * $naturalGrowth);
		
		return $populationGrowth;
	}
	
	function getRestingWizards($user = null){
		$restedWizards = round($user['Building']['school'] * 1.5);
		//Insight Technology
		if ($user['Technology']['tree_4'] > 4)
			$restedWizards = $user['Building']['school'] * 2;
			
		return $restedWizards;
	}

	
	
	
	
}