<?php

App::import('Vendor', 'facebook/facebook');

class timer
	{
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
}

class UpdateThardferrShell extends Shell {
	var $uses = array('Army', 'Fame', 'AllianceFame', 'Reset', 'AllianceTopic', 'Trade', 'AlliancePost', 'Building', 'Population', 'Resource', 'Spell', 'User', 'Weapon', 'Technology', 'Attack', 'Producer', 'Retaliation', 'Trade', 'Report', 'MagicSchedule', 'Message', 'KillStat', 'Alliance', 'Premium', 'Account');
	var $facebook;  
    var $__fbApiKey = 'a095a0102375506b9b7d22ef45e5266a';  
    var $__fbSecret = '8269280af6c696d1872295cbb2d81d6b'; 

    
	function main() {
		$t = new timer(); //start timer
		// Prevent the 'Undefined index: facebook_config' notice from being thrown.  
        //$GLOBALS['facebook_config']['debug'] = NULL;  
  
        // Create a Facebook client API object.  
        //$this->facebook = new Facebook($this->__fbApiKey, $this->__fbSecret);

        
        
		// do stuff
		//echo 'Facebook stuff took '.$t->parse().' to do so far<br />
		//';
		// do more stuff
		 //'conditions' => array('User.score !=' => 0),
		 
        // Have to set variables in order to get default unit defenses for healing:
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
        $default['User']['race'] ="all";
        $sentUnits['Attack']['standard'] = 0;
        $sentUnits['Attack']['weak'] = 0;
        $sentUnits['Attack']['small_elite'] = 0;
        $sentUnits['Attack']['big_elite'] = 0;
        $sentUnits['Attack']['range'] = 0;
        $sentUnits['Attack']['mount'] = 0;
        
        $totalEnemy = 0;
        
        
        $fields = "User.id";
        if(false){
        	$users = array();
        	$inactiveWeed = time() - (14 * 24 * 60 * 60);
        } else {
        	$inactiveWeed = time() - (14 * 24 * 60 * 60);
			$users = $this->User->find('all', array(
					'conditions' => array(
						'OR' => array(
							array(
								'User.last_login >' => $inactiveWeed, 
								'User.suspended !=' => 1
							), 
							array(
								'User.premium >' => 0
							)
						)
					),
					'fields' => $fields, 
					'recursive' => -1, 
					'order' => array(
						'User.last_login' => 'desc'
					)
				)
			);
        }
		// Go through each user
		//debug($users);
		foreach($users as $user):
			$player = $this->User->find('first', array('conditions' => array('User.id' => $user['User']['id'])));
		//	echo 'After player find: '.$t->parse().' <br />
		//';
			if($player['Building']['castle'] != 0 || $player['User']['suspended'] == 0) {
				

				
				// echo "User id: " . $player['User']['id'] . " <br />";
				$this->User->id = $player['User']['id']; 
				$this->Army->id = $player['Army']['id'];
				$this->Building->id = $player['Building']['id'];
				$this->Resource->id = $player['Resource']['id'];
				$this->Spell->id = $player['Spell']['id'];
				$this->Weapon->id = $player['Weapon']['id'];
				$this->Population->id = $player['Population']['id'];
				$this->Technology->id = $player['Technology']['id'];
				
				$default['User']['race'] = $player['User']['race'];
				$defaultStats = $this->Attack->getUnitArray(0, $sentUnits, 0, 1, 100, false, $default, false);
								
				$player = $this->Technology->updateResearch($player);
				/*
				echo "Debug before population update 
				";
				debug($player['Population']);
				*/
				$player = $this->Population->updatePopulation($player);	
				
				/*
				echo "Debug after population update but before eating
				";
				debug($player['Population']);
				*/
				$player = $this->User->updateCrimeRate($player);
	
				$player = $this->Resource->updateIron($player);
				
				$player = $this->Resource->updateWood($player);
				
				$player = $this->Resource->updateLand($player);
				
				$player = $this->Resource->updateGold($player);
				
				$player = $this->Resource->updateFood($player);
				
				$player = $this->Building->updateBuildings($player);
				
				$player = $this->Army->updateTrainingSoldiers($player);
				
				$player = $this->Army->updateRestingWizards($player);
				
				$player = $this->Spell->updateSpells($player);
				
				$player = $this->Weapon->updateWeapons($player);
				
				
				
				$player = $this->Attack->updateAttacks($player);
				
				$player = $this->User->updateProtection($player);
				
				$player = $this->User->updatePremium($player);
				
				$player = $this->Retaliation->reduceRetaliations($player);
				
				$player = $this->Army->healCenterUnits($player, $defaultStats);

				$player = $this->Army->killUnitsNoRoom($player);
				
				$trades = $this->Trade->find('all', array('conditions' => array('Trade.user_id' => $player['User']['id'])));
				if(!empty($trades) && $player['User']['score'] == 0) {
					foreach($trades as $trade):
						$this->Trade->delete($trade['Trade']['id']);
					endforeach;
				}
				//echo 'After updates: '.$t->parse().' <br />
				//';

				//$this->User->sendNotification($player, $this->facebook);
				
				/*
				$announcement = 'Hello again, players! There\'s been a whoopsie. Please see <a href="http://www.bojoto.com/ctagameforum/viewtopic.php?p=10986#p10986">this thread on the forum</a> for more details. <br /><br />
				~ Josh';
				$this->Report->makeNewReport($this->User->id, $announcement, "Announcement", 0, 0);
				*/
				
				
				/*
				 * Calculate unit deaths if not enough food or gold
				 */
				
				
				$totalEaten = 0;
				$goldSpent = 0;
				$eaters = $this->Army->getFullArmyData($player);
				if(!empty($player['Army']['next_strategy'])){
					$player['Army']['strategy'] = $player['Army']['next_strategy'];
					$player['Army']['next_strategy'] = "";
				}
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
				$eaters[7]['amount'] = $player['Population']['lumbers'];
				$eaters[7]['amount_away'] = 0;
				$eaters[7]['gold_consumed'] = 0;
				$eaters[7]['food_consumed'] = .1667;
				$eaters[7]['percent_eating'] = 0;
				
				$eaters[8]['type'] = "miners";
				$eaters[8]['amount'] = $player['Population']['miners'];
				$eaters[8]['amount_away'] = 0;
				$eaters[8]['gold_consumed'] = 0;
				$eaters[8]['food_consumed'] = .1667;
				$eaters[8]['percent_eating'] = 0;
				
				$eaters[9]['type'] = "agriculture";
				$eaters[9]['amount'] = $player['Population']['agriculture'];
				$eaters[9]['amount_away'] = 0;
				$eaters[9]['gold_consumed'] = 0;
				$eaters[9]['food_consumed'] = .1667;
				$eaters[9]['percent_eating'] = 0;
				
				$eaters[10]['type'] = "building";
				$eaters[10]['amount'] = $player['Population']['building'];
				$eaters[10]['amount_away'] = 0;
				$eaters[10]['gold_consumed'] = 0;
				$eaters[10]['food_consumed'] = .1667;
				$eaters[10]['percent_eating'] = 0;
				
				$eaters[11]['type'] = "blacksmiths";
				$eaters[11]['amount'] = $player['Population']['blacksmiths'];
				$eaters[11]['amount_away'] = 0;
				$eaters[11]['gold_consumed'] = 0;
				$eaters[11]['food_consumed'] = .1667;
				$eaters[11]['percent_eating'] = 0;
				
				$eaters[12]['type'] = "explorers";
				$eaters[12]['amount'] = $player['Population']['explorers'];
				$eaters[12]['amount_away'] = 0;
				$eaters[12]['gold_consumed'] = 0;
				$eaters[12]['food_consumed'] = .1667;
				$eaters[12]['percent_eating'] = 0;
				
				$eaters[13]['type'] = "training";
				$eaters[13]['amount'] = $player['Population']['training'];
				$eaters[13]['amount_away'] = 0;
				$eaters[13]['gold_consumed'] = 0;
				$eaters[13]['food_consumed'] = .1667;
				$eaters[13]['percent_eating'] = 0;
				
				$eaters[14]['type'] = "wizard_resting";
				$eaters[14]['amount'] = $player['Army']['wizard_resting'];
				$eaters[14]['amount_away'] = 0;
				$eaters[14]['gold_consumed'] = $eaters[6]['gold_consumed'];
				$eaters[14]['food_consumed'] = $eaters[6]['food_consumed'];
				$eaters[14]['percent_eating'] = 0;
				
				// This foreach adds in all the away units.
				$i = 15;
				$attackCount = 0;

				/*
				 * Food Consumed while away
				 */
				switch($player['User']['race']){
				case "Dwarf":
					$foodAway = 1.7;
					break;
				case "Gnome":
					$foodAway = 1.8;
					break;
				case "Human":
					$foodAway = 2;
					break;
				case "Elf":
					$foodAway = 2.2;
					break;
				case "Orc":
					$foodAway = 1.5;
					break;
				default:
					break;
				}
				
				/*
				 * Gold Consumed while away
				 */
				switch($player['User']['race']){
				case "Dwarf":
					$goldAway = 1.7;
					break;
				case "Gnome":
					$goldAway = 2.2;
					break;
				case "Human":
					$goldAway = 2;
					break;
				case "Elf":
					$goldAway = 1.8;
					break;
				case "Orc":
					$goldAway = 2;
					break;
				default:
					break;
				}
				foreach($player['Attack'] as $attack):
					$eaters[$i]['type'] = 'weak';
					$eaters[$i]['amount'] = $attack['weak'];
					$eaters[$i]['amount_away'] = 0;
					$eaters[$i]['food_consumed'] = $eaters[0]['food_consumed'] * $foodAway;
					$eaters[$i]['gold_consumed'] = $eaters[0]['gold_consumed'] * $goldAway;
					$eaters[$i]['percent_eating'] = 0;
					$eaters[$i]['amount_dieing'] = 0;
					$i++;
					$eaters[$i]['type'] = 'standard';
					$eaters[$i]['amount'] = $attack['standard'];
					$eaters[$i]['amount_away'] = 0;
					$eaters[$i]['food_consumed'] = $eaters[1]['food_consumed'] * $foodAway;
					$eaters[$i]['gold_consumed'] = $eaters[1]['gold_consumed'] * $goldAway;
					$eaters[$i]['percent_eating'] = 0;
					$eaters[$i]['amount_dieing'] = 0;
					$i++;
					$eaters[$i]['type'] = 'small_elite';
					$eaters[$i]['amount'] = $attack['small_elite'];
					$eaters[$i]['amount_away'] = 0;
					$eaters[$i]['food_consumed'] = $eaters[2]['food_consumed'] * $foodAway;
					$eaters[$i]['gold_consumed'] = $eaters[2]['gold_consumed'] * $goldAway;
					$eaters[$i]['percent_eating'] = 0;
					$eaters[$i]['amount_dieing'] = 0;
					$i++;
					$eaters[$i]['type'] = 'big_elite';
					$eaters[$i]['amount'] = $attack['big_elite'];
					$eaters[$i]['amount_away'] = 0;
					$eaters[$i]['food_consumed'] = $eaters[3]['food_consumed'] * $foodAway;
					$eaters[$i]['gold_consumed'] = $eaters[3]['gold_consumed'] * $goldAway;
					$eaters[$i]['percent_eating'] = 0;
					$eaters[$i]['amount_dieing'] = 0;
					$i++;
					$eaters[$i]['type'] = 'range';
					$eaters[$i]['amount'] = $attack['range'];
					$eaters[$i]['amount_away'] = 0;
					$eaters[$i]['food_consumed'] = $eaters[4]['food_consumed'] * $foodAway;
					$eaters[$i]['gold_consumed'] = $eaters[4]['gold_consumed'] * $goldAway;
					$eaters[$i]['percent_eating'] = 0;
					$eaters[$i]['amount_dieing'] = 0;
					$i++;
					$eaters[$i]['type'] = 'mount';
					$eaters[$i]['amount'] = $attack['mount'];
					$eaters[$i]['amount_away'] = 0;
					$eaters[$i]['food_consumed'] = $eaters[4]['food_consumed'] * $foodAway;
					$eaters[$i]['gold_consumed'] = $eaters[4]['gold_consumed'] * $goldAway;
					$eaters[$i]['percent_eating'] = 0;
					$eaters[$i]['amount_dieing'] = 0;
					$i++;
					$attackCount++;
				endforeach;
				
				// This calculates gold spent and food eaten
				foreach($eaters as $eater):
					$totalEaten += ($eater['amount'] * $eater['food_consumed'] * (100/(100+$player['User']['loyalty'])));
				endforeach;
				
				$player['Resource']['food'] -= round($totalEaten);
				if($player['User']['id'] == 1554780717) {
					debug($eaters);
					debug($totalEaten);
					debug($player);
				}
				// Kill units because user has no food.
				if($player['Resource']['food'] < 0) {
					$negFood = abs($player['Resource']['food']);
					$announcement = 'Your kingdom ran out of food recently and was unable to feed your current peasants and army. People and soldiers have died horrible, hungry deaths due to your mismanagement. <br />';
					$this->Report->makeNewReport($this->User->id, $announcement, "Insufficient Funds", 0, 0, "Your kingdom ran out of food!");
					foreach($eaters as &$eater):
						$eater['percent_eating'] = ($eater['amount'] * $eater['food_consumed']) / $totalEaten;
						// echo $eater['type'] . " has a total of " . $eater['amount'] . " and has a ";
						// echo "Percent eating of " . $eater['percent_eating'] . ", and should eat " . ($eater['food_consumed']*$eater['amount']) . " total.
						//";
					endforeach;
					foreach($eaters as &$eater):
						if($eater['amount'] != 0 && $eater['food_consumed'] != 0) {
							$eater['amount_dieing'] = round($eater['percent_eating'] * $negFood / $eater['food_consumed']);		
							if($eater['amount_dieing'] < 0)
								$eater['amount_dieing'] = 0;					
						}
						else
							$eater['amount_dieing'] = 0;
					endforeach;
					for($i = 0; $i < 15; $i++) {
						$name = $eaters[$i]['type'];
						if($i < 7 || $i == 14) {
							$player['Army'][$name] -=  $eaters[$i]['amount_dieing'];
							$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
							if($player['Army'][$name] < 0) {
								$player['Army'][$name] = 0;
								$eaters[$i]['amount'] = 0;
							}
						}
						else {
							$player['Population'][$name] -= $eaters[$i]['amount_dieing'];
							$player['Population']['total_population'] -= $eaters[$i]['amount_dieing'];
							$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
							if($player['Population'][$name] < 0) {
								$player['Population'][$name] = 0;
								$eaters[$i]['amount'] = 0;
							}
						}
						
					}
					$attackIndex = 0;
					for($i = 15; $i < $attackCount + 15; $i++) {
						for($j = 0; $j < 6; $j++) {
							$name = $eaters[$i]['type'];
							$player['Attack'][$attackIndex][$name] -=  $eaters[$i]['amount_dieing'];
							$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
							if($player['Attack'][$attackIndex][$name] < 0) {
								$player['Attack'][$attackIndex][$name] = 0;
								$eaters[$i]['amount'] = 0;
							}
							$i++;
						}
						$attackIndex++;
					}
					
					$player['Resource']['food'] = 0;
				}	
				
				foreach($eaters as $eater):
					$goldSpent += ($eater['amount'] * $eater['gold_consumed'] * (100/(100+$player['User']['loyalty'])));
				endforeach;
				
				$player['Resource']['gold'] -= round($goldSpent);
	
				// Kill military because user has no gold
				if($player['Resource']['gold'] < 0 && $goldSpent != 0) {
					$announcement = 'Your kingdom ran out of gold recently and was unable to pay some of your current army. Many soldiers have abanoned you due to your mismanagement. <br />';
					$this->Report->makeNewReport($this->User->id, $announcement, "Insufficient Funds", 0, 0, "Your kingdom ran out of gold!");
					$negGold = abs($player['Resource']['gold']);
					// echo "Negative gold: $negGold
					//";
					foreach($eaters as &$eater):
						$eater['percent_eating'] = ($eater['amount'] * $eater['gold_consumed']) / $goldSpent;
						// echo $eater['type'] . " has a total of " . $eater['amount'] . " and has a ";
						// echo "Percent eating of " . $eater['percent_eating'] . " 
						//";
					endforeach;
					foreach($eaters as &$eater):
						if($eater['amount'] != 0 && $eater['gold_consumed'] != 0) {
							$eater['amount_dieing'] = round($eater['percent_eating'] * $negGold / $eater['gold_consumed']);		
							if($eater['amount_dieing'] < 0)
								$eater['amount_dieing'] = 0;					
						}
						else
							$eater['amount_dieing'] = 0;
					endforeach;
					for($i = 0; $i < 15; $i++) {
						$name = $eaters[$i]['type'];
						if($i < 7 || $i == 14) {
							$player['Army'][$name] -=  $eaters[$i]['amount_dieing'];
							$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
							if($player['Army'][$name] < 0) {
								$player['Army'][$name] = 0;
								$eaters[$i]['amount'] = 0;
							}
						}
						else {
							$player['Population'][$name] -= $eaters[$i]['amount_dieing'];
							$player['Population']['total_population'] -= $eaters[$i]['amount_dieing'];
							$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
							if($player['Population'][$name] < 0) {
								$player['Population'][$name] = 0;
								$eaters[$i]['amount'] = 0;
							}
							if($player['Population']['total_population'] < 0) {
								$player['Population']['total_population'] = 0;
							}
						}
						
					}
					$attackIndex = 0;
					for($i = 15; $i < $attackCount + 15; $i++) {
						for($j = 0; $j < 6; $j++) {
							$name = $eaters[$i]['type'];
							$player['Attack'][$attackIndex][$name] -=  $eaters[$i]['amount_dieing'];
							$eaters[$i]['amount'] -= $eaters[$i]['amount_dieing'];
							if($player['Attack'][$attackIndex][$name] < 0) {
								$player['Attack'][$attackIndex][$name] = 0;
								$eaters[$i]['amount'] = 0;
							}
							$i++;
						}
						$attackIndex++;
					}
					$player['Resource']['gold'] = 0;
				}	
				
				$player = $this->User->updateScore($player);
				
				if($player['Population']['total_population'] < 0)
					$player['Population']['total_population'] = 0;
					
				
				//echo 'After eating: '.$t->parse().' <br />
				//';
				
				
				// Update user's information
				
				if($this->User->save($player['User'], array('validate' => true, 'fieldList' => array('premium', 'score', 'protection', 'crime_rate', 'beginner_protection', 'victim_time', 'victim_bonus', 'province')))) {
					//echo "User saved. User id: " . $player['User']['id'] . " Kingdom: " . $player['User']['kingdom'] . "
					//";
				}
				if($this->Army->save($player['Army'])){
					//echo "Army saved
					//";
				}
				else {
					//echo "Army not saved
					//";
				}
				if($this->Building->save($player['Building'])){
					//echo "Building saved
					//";
				}
				else {
					//echo "Building not saved
					//";
				}
				if($this->Resource->save($player['Resource'])){
					//echo "Resource saved
					//";
				}
				else {
					//echo "Resource not saved
					//";
				}
				if($this->Spell->save($player['Spell'])) {
					//echo "Spell saved
					//";
				}
				else{
					//echo "Spell not saved
					//";
				}
				if($this->Weapon->save($player['Weapon'])) {
					//echo "Weapon saved
					//";
				}
				else {
					//echo "Weapon not saved
					//";
				}
				
				if($this->Population->save($player['Population'])) {
					//echo "Debug at save
					//";
					//debug($player['Population']);
	
				}
				else {
					//echo "Population not saved
					//"; 
				}
				if($this->Technology->save($player['Technology'])) {
					//echo "Technology saved
					//"; 
				}
				else {
					//echo "Tech not saved
					//";
				}
				if($this->Attack->saveAll($player['Attack'])) {
					if($player['User']['id'] == 1554780717) {
						
					}
				}
				else {
					if($player['User']['id'] == 1554780717) {
					
					}
				}
				if(!empty($player['Retaliation'])){
					if($this->Retaliation->saveAll($player['Retaliation'])) {
						//echo "Retaliations saved
						//";
					}
					else {
						//echo "Retals not saved
						//";
						
					}
				}
				
				if($player['User']['premium']){
					$this->Spell->castSchedules($player);
				}
				
				//echo 'After saves '.$t->parse().' <br />
				//';
			} // if score not 0 or if player isn't suspended
			
		endforeach; // for each user
		unset($users);
		//echo 'Before ranks: '.$t->parse().' <br />
		//';
		$this->User->rankPlayers();
		$this->User->rankHonorPlayers();
		//echo 'After ranks:  '.$t->parse().' <br />
		//';
		
		if(date('g') == 6 || date('g') == 12)
			$this->KillStat->updateAllStats();
			
	    
        
        $users = $this->User->find('all', array('conditions' => array('User.last_login <' => $inactiveWeed, 'User.suspended !=' => 1, 'User.premium' => 0), 'recursive' => -1));
        foreach($users as $user):
        	$trades = $this->Trade->find('all', array('conditions' => array('Trade.user_id' => $user['User']['id'])));
			if(!empty($trades)) {
				foreach($trades as $trade):
					$this->Trade->delete($trade['Trade']['id']);
				endforeach;
			}
			$schedules = $this->MagicSchedule->find('all', array('conditions' => array('MagicSchedule.user_id' => $user['User']['id'])));
			if(!empty($schedules)) {
				foreach($schedules as $schedule):
					$this->MagicSchedule->delete($schedule['MagicSchedule']['id']);
				endforeach;
			} 
			$OKillStat = $this->KillStat->find('first', 
				array(
					'conditions' => array(
						'KillStat.type LIKE' => 'Offense', 
						'KillStat.user_id' => $user['User']['id']
					)
				)
			);
			
			$DKillStat = $this->KillStat->find('first', 
				array(
					'conditions' => array(
						'KillStat.type LIKE' => 'Defense', 
						'KillStat.user_id' => $user['User']['id']
					)
				)
			);
			
			$TKillStat = $this->KillStat->find('first', 
				array(
					'conditions' => array(
						'KillStat.type LIKE' => 'Total', 
						'KillStat.user_id' => $user['User']['id']
					)
				)
			);
			$this->KillStat->delete($TKillStat['KillStat']['id']);
			$this->KillStat->delete($DKillStat['KillStat']['id']);
			$this->KillStat->delete($OKillStat['KillStat']['id']);
	        $this->User->delete($user['User']['id']);
	        echo $user['User']['id'] . " deleted. 
	        ";
        endforeach;
        
        $this->Alliance->updateAllianceStats();
        
		$time = date("r"); 
		$strToTime = strtotime($time);
		$resetTime = strtotime("Fri, 25 Mar 2011 15:00:00 -0600"); 
		echo " " . $strToTime;
		echo " " . $resetTime;
		$strToTime = 1;
		$resetTime = 0;
		// $strToTime >= $resetTime is giving not wanted results.
        if(false) { 
        	$round = 11;
        	$this->KillStat->updateAllStats();
        	$this->Alliance->updateAllianceStats();
        	$this->User->bindModel(array('hasOne'=>array('Alliance'=>array('foreignKey'=>false,
                        'conditions'=>array('Alliance.id = User.alliance_id')))), false); 
        	$fields = "User.id, User.account_id, User.premium, User.premium_points, User.premium, User.rank, User.score, User.ruler, User.province, User.province_number, User.honor, User.honor_rank, Building.castle, Resource.land, Alliance.short_name";
        	$users = $this->User->find('all', array('fields' => $fields, 'recursive' => 2, 'order' => array('User.last_login' => 'desc')));
			// Go through each user
			//debug($users);
			foreach($users as $user):	
				if($user['Building']['castle'] != 0){      
					$trades = $this->Trade->find('all', array('conditions' => array('Trade.user_id' => $user['User']['id'])));
					if(!empty($trades)) {
						foreach($trades as $trade):
							$this->Trade->delete($trade['Trade']['id']);
						endforeach;
					}
					
					$topics = $this->AllianceTopic->find('all', array('conditions' => array('AllianceTopic.user_id' => $user['User']['id'])));
					if(!empty($topics)) {
						foreach($topics as $topic):
							$this->AllianceTopic->delete($topic['AllianceTopic']['id']);
						endforeach;
					}
					
					$posts = $this->AlliancePost->find('all', array('conditions' => array('AlliancePost.user_id' => $user['User']['id'])));
					if(!empty($posts)) {
						foreach($posts as $post):
							$this->AlliancePost->delete($post['AlliancePost']['id']);
						endforeach;
					}
					
					$schedules = $this->MagicSchedule->find('all', array('conditions' => array('MagicSchedule.user_id' => $user['User']['id'])));
					if(!empty($schedules)) {
						foreach($schedules as $schedule):
							$this->MagicSchedule->delete($schedule['MagicSchedule']['id']);
						endforeach;
					}
					
					$reports = $this->Report->find('all', array('conditions' => array('Report.user_id' => $user['User']['id'])));
					if(!empty($reports)) {
						foreach($reports as $report):
							$this->Report->delete($report['Report']['id']);
						endforeach;
					}
					
					$messages = $this->Message->find('all', array('conditions' => array('Message.user_id' => $user['User']['id'])));
					if(!empty($messages)) {
						foreach($messages as $message):
							$this->Message->delete($message['Message']['id']);
						endforeach;
					}
					
					$OKillStat = $this->KillStat->find('first', 
						array(
							'conditions' => array(
								'KillStat.type LIKE' => 'Offense', 
								'KillStat.user_id' => $user['User']['id']
							)
						)
					);
					
					$DKillStat = $this->KillStat->find('first', 
						array(
							'conditions' => array(
								'KillStat.type LIKE' => 'Defense', 
								'KillStat.user_id' => $user['User']['id']
							)
						)
					);
					
					$TKillStat = $this->KillStat->find('first', 
						array(
							'conditions' => array(
								'KillStat.type LIKE' => 'Total', 
								'KillStat.user_id' => $user['User']['id']
							)
						)
					);
					
					$hofUser['Fame']['ruler'] = $user['User']['ruler'];
					$hofUser['Fame']['province'] = $user['User']['province'] . " " . $user['User']['province_number'];
					$hofUser['Fame']['alliance'] = $user['Alliance']['short_name'];
					$hofUser['Fame']['castle'] = $user['Building']['castle'];
					$hofUser['Fame']['land'] = $user['Resource']['land'];
					$hofUser['Fame']['score'] = $user['User']['score'];
					$hofUser['Fame']['rank'] = $user['User']['rank'];
					$hofUser['Fame']['honor'] = $user['User']['honor'];
					$hofUser['Fame']['honor_rank'] = $user['User']['honor_rank'];
					$hofUser['Fame']['oks'] = $OKillStat['KillStat']['points'];
					$hofUser['Fame']['okrank'] = $OKillStat['KillStat']['rank'];
					$hofUser['Fame']['dks'] = $DKillStat['KillStat']['points'];
					$hofUser['Fame']['dkrank'] = $DKillStat['KillStat']['rank'];
					$hofUser['Fame']['tks'] = $TKillStat['KillStat']['points'];
					$hofUser['Fame']['tkrank'] = $TKillStat['KillStat']['rank'];
					
					$hofUser['Fame']['round'] = $round;
					
					$this->Fame->create();
					$this->Fame->save($hofUser);
					
					$stats = $this->KillStat->find('all', array('conditions' => array('KillStat.user_id' => $user['User']['id'])));
					if(!empty($stats)) {
						foreach($stats as $stat):
							$this->KillStat->delete($stat['KillStat']['id']);
						endforeach;
					}
					
					$account = $this->Account->find('first', array('conditions' => array('Account.id' => $user['User']['account_id'])));
					$this->Premium->addNewTransaction($account['Account']['id'], "End of Round Credit", round($user['User']['premium'] / 48), "Hours converted: " . $user['User']['premium']);
					unset($hofUser);
					unset($reset);
					if(!empty($posts))
						unset($posts);
					if(!empty($topics))
						unset($topics);
					if(!empty($trades))
						unset($trades);
					if(!empty($messages))
						unset($messages);
					if(!empty($reports))
						unset($reports);
					if(!empty($stats))
						unset($stats);
					if(!empty($OKillStat))
						unset($OKillStat);
					if(!empty($DKillStat))
						unset($DKillStat);
					if(!empty($TKillStat))
						unset($TKillStat);
					unset($account);
					$this->User->delete($user['User']['id']);
				}
        	endforeach;	
        	unset($users);
        	
        	
        	$alliances = $this->Alliance->find('all');
        	
        	foreach($alliances as $alliance):

				$hofAlliance['AllianceFame']['name'] = $alliance['Alliance']['short_name'];
				$hofAlliance['AllianceFame']['score'] = $alliance['Alliance']['score'];
				$hofAlliance['AllianceFame']['score_rank'] = $alliance['Alliance']['score_rank'];
				$hofAlliance['AllianceFame']['oks'] = $alliance['Alliance']['oks'];
				$hofAlliance['AllianceFame']['oks_rank'] = $alliance['Alliance']['oks_rank'];
				$hofAlliance['AllianceFame']['dks'] = $alliance['Alliance']['dks'];
				$hofAlliance['AllianceFame']['dks_rank'] = $alliance['Alliance']['dks_rank'];
				$hofAlliance['AllianceFame']['tks'] = $alliance['Alliance']['ks'];
				$hofAlliance['AllianceFame']['tks_rank'] = $alliance['Alliance']['ks_rank'];
				$hofAlliance['AllianceFame']['honor'] = $alliance['Alliance']['honor'];
				$hofAlliance['AllianceFame']['honor_rank'] = $alliance['Alliance']['honor_rank'];
				$hofAlliance['AllianceFame']['members'] = $alliance['Alliance']['members'];
				$hofAlliance['AllianceFame']['round'] = $round;
				
				$this->AllianceFame->create();
				$this->AllianceFame->save($hofAlliance);
				unset($hofAlliance);
        	endforeach;
        }
		
		echo 'Everything took '.$t->parse().' to do in total.
		';
	}// end main function
}
?>
