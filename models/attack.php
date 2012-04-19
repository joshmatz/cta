<?php
class Attack extends AppModel {    
	var $name = 'Attack';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id',
			'counterCache' => true,
		)
	);
	

	/*
	 * $s == word
	 * $c == count
	 */
	function pluralize($s, $c) {
		if ($c != 1 && $s != "Heavy Crossbowman" && $s != "Light Crossbowman" && $s != "Courageous" && $s != "Axe Man") {
			return number_format($c) . ' ' . Inflector::pluralize($s);
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
    	return number_format($c) . ' ' . $s;
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

	function invade($user = null, $data = null, $facebook = null, $retal = false, $simulator = array(), $retalType = null, $ipAddress = null){
		//debug($user);
		if(!empty($simulator)) {
			$setting = Classregistry::init('Setting')->find('first', array('conditions' => array('Setting.user_id' => $user['User']['id'])));
			if($setting['Setting']['simulator_debug_on']){
				$debug = true; // simulator
			}
			else {
				$debug = false;
			}
			$victim = $simulator;
		} else {
			$victim = $this->getVictimData($data['Attack']['target']);
			$debug = false;
			$Alliance = Classregistry::init('Alliance');
			if($victim['User']['alliance_id']){
				$victimAlliance = $Alliance->find('first', array('conditions' => array('Alliance.id' => $victim['User']['alliance_id'])));
				$victim['User']['alliance'] = $victimAlliance['Alliance']['short_name'];
				unset($victimAlliance);
			} else {
				$victim['User']['alliance'] = null;
			}
			if($user['User']['alliance_id']){
				$userAlliance = $Alliance->find('first', array('conditions' => array('Alliance.id' => $user['User']['alliance_id'])));
				$user['User']['alliance'] = $userAlliance['Alliance']['short_name'];
				unset($userAlliance);
			} else {
				$victim['User']['alliance'] = null;
			}
			unset($Alliance);
			
		}
		
		//Make a victim unit array that will be compatable with 
	    //The users unit array, so it can be sent to "getUnitArray()"
	    $shadowHall = false;
	    if($victim['User']['Spell']['shadow_hall'] > 0 && $retal == false){
	    	$random = mt_rand(1,2);
	    	if($random == 1)
	    		$shadowHall = true;
	    }
	    
	    if($shadowHall == false){
			$victimUnits = array('Attack' => array('small_elite' => $victim['User']['Army']['small_elite'], 'big_elite' => $victim['User']['Army']['big_elite'],
		         'weak' => $victim['User']['Army']['weak'], 'standard' => $victim['User']['Army']['standard'],
		         'range' => $victim['User']['Army']['range'], 'mount' => $victim['User']['Army']['mount']));
			$totalVictimsUnits = $this->getTotalVictimUnits($victim, $victim['User']['race']);
			$totalUserUnits = $this->getTotalVictimUnits($data, $user['User']['race']);
			if($totalVictimsUnits == 0)
				$totalVictimsUnits = 1;
			if($totalUserUnits == 0)
				$totalUserUnits = 1;
			
			if(empty($simulator)){
				if ($retal == true)
					$BASMultiplier = 1;
				else if ($victim['User']['score']/$user['User']['score'] >= .9)
					$BASMultiplier = 1; 
				else if (.9 > $victim['User']['score']/$user['User']['score'] && $victim['User']['score']/$user['User']['score'] >= .5)
					$BASMultiplier = pow(max(($victim['User']['score']/$user['User']['score'])-(.3666), 0)*(30/17), .29);
				else if ($victim['User']['score']/$user['User']['score'] < .5)
					$BASMultiplier = max(($victim['User']['score']/$user['User']['score'])-(.3666), 0)*4.2;
				
				if($retal == true)
					$victimBASMultiplier = 1;
				else if ($victim['User']['score']/$user['User']['score'] >= .9)
					$victimBASMultiplier = 1; 
				else if (.9 > $victim['User']['score']/$user['User']['score'])
					$victimBASMultiplier = pow(max(($user['User']['score']/$victim['User']['score'])-(.3666), 0)*(22/17), .8);
				
					
					/*
					 * Old Multiplier 
					 * 
				else if($victim['User']['score']/$user['User']['score'] >= .8)
					$BASMultiplier = 1;
				else 
					$BASMultiplier = pow(($victim['User']['score']/$user['User']['score']+.2), 2.5);
				*/
					
				$userBas = $user['User']['Army']['bas'] * $BASMultiplier;
				$victimBas = $victim['User']['Army']['bas'] * $victimBASMultiplier;
			} else {
				if ($retal == true)
					$BASMultiplier = 1;
					
				else if ($victim['User']['score']/$user['User']['score'] >= .9)
					$BASMultiplier = 1;
				else if (.9 > $victim['User']['score']/$user['User']['score'] && $victim['User']['score']/$user['User']['score'] >= .5)
					$BASMultiplier = pow(max(($victim['User']['score']/$user['User']['score'])-(.3666), 0)*(30/17), .29);
				else if ($victim['User']['score']/$user['User']['score'] < .5)
					$BASMultiplier = max(($victim['User']['score']/$user['User']['score'])-(.3666), 0)*4.2;
				
				if($retal == true)
					$victimBASMultiplier = 1;
				else if ($victim['User']['score']/$user['User']['score'] >= .9)
					$victimBASMultiplier = 1; 
				else if (.9 > $victim['User']['score']/$user['User']['score'])
					$victimBASMultiplier = pow(max(($user['User']['score']/$victim['User']['score'])-(.3666), 0)*(22/17), .8);
				/*
					 * Old multiplier
					 *
				else if($victim['User']['score']/$user['User']['score'] <= 1)
					$BASMultiplier = 1;
				else 
					$BASMultiplier = pow(($victim['User']['score']/$user['User']['score']+.2), 2.5);
				*/
				
				if($debug){
					echo "Bas multiplier: $BASMultiplier <br />";
					echo "User bas before: " . $user['User']['Army']['bas'] . "<br />";
				}
				$userBas = $user['User']['Army']['bas'] * $BASMultiplier;
				$victimBas = $victim['User']['Army']['bas'];
				if($debug){
					echo "User bas after: " . $userBas . "<br />";
				}
			}
				
			
			if(empty($simulator)){
				$userBas += (mt_rand(-5, 5));
				$victimBas += (mt_rand(-5, 5));
			} else {
				if($debug) {
					echo "Attacker luck: " . $user['User']['luck'] . "<br />";
					echo "Defender luck: " . $victim['User']['luck'] . "<br />";
					echo "Attacker Bas before luck: " . $userBas . "<br />";
					echo "Defender Bas before luck: " . $victimBas . "<br />";
				}
				$userBas += $user['User']['luck'];
				$victimBas += $victim['User']['luck'];
				if($debug) {
					echo "Attacker Bas after luck: " . $userBas . "<br />";
					echo "Defender Bas after luck: " . $victimBas . "<br />";
				}
			}
			
			/*
			 * Upperfeeding removed.
			elseif($user['User']['score'] < .6 * $victim['User']['score']){
				$userBas = $user['User']['Army']['bas'] - 25*($user['User']['score']/$victim['User']['score']);
				$victimBas = $victim['User']['Army']['bas'];
			}*/
			
			//if ($userBas < 10)
			//	$userBas = 10;
			//echo "BAS: " . $userBas;
			//echo "<br><br>Victim BAS outside array: " . $victimBas;
			//echo "<br><br>Total User Troops " . $totalUserUnits;
			
			$dummyUnits = 10*$victim['User']['Building']['guard_tower'];
			if ($dummyUnits > 2*$totalUserUnits)
				$dummyUnits = 2*$totalUserUnits;
			
			//$dummyUnits += 100;
			$totalVictimsUnits += $dummyUnits;
			//echo "<br>Dummy Units: " . $dummyUnits;
			
			$baseAttack = 20*$victim['User']['Building']['castle'];
			//echo "<br>Base Attack: " . $baseAttack;
			
			$attackersUnits = $this->getUnitArray($user['User']['id'], $data, $totalVictimsUnits, 1, $userBas, false, $user, $debug);
			$victimsUnits = $this->getUnitArray($victim['User']['id'], $victimUnits, $totalUserUnits, $victim['User']['Building']['castle'], $victimBas, true, $simulator, $debug);
	
			$attackerAttackCoefficients = $this->getAttackCoefficients($user['User']['Army']['strategy'], $attackersUnits, $debug);
			$victimAttackCoefficients = $this->getAttackCoefficients($victim['User']['Army']['strategy'], $victimsUnits, $debug);
			
			
		
			$tech = 1;
			if($victim['User']['Technology']['tree_2'] > 6) {
				$tech = .95;
				//echo "Teched. <br />";
			}
				
			$victimGuardTowers = $victim['User']['Building']['guard_tower'];
			
			//range attack
			$rangeResult = $this->rangeAttack($tech, $attackersUnits, $victimsUnits, $user['User']['race'], $victim['User']['race'], $attackerAttackCoefficients, $victimAttackCoefficients, $baseAttack, $dummyUnits, $victimGuardTowers, $debug);
			$tempVictimsUnits = $rangeResult[0];
			$tempAttackersUnits = $rangeResult[1];
			
			// Wolf totals calculated after range, before short range. Used in melee.
			$wolves['attacker'] = $attackersUnits[0]['number'] - $tempAttackersUnits[0]['number']; // WM
			//$wolves['attacker'] += $attackersUnits[2]['number'] - $tempAttackersUnits[2]['number']; 
			//$wolves['attacker'] += $attackersUnits[3]['number'] - $tempAttackersUnits[3]['number'];

			$wolves['defender'] = $victimsUnits[0]['number'] - $tempVictimsUnits[0]['number']; // WM
			//$wolves['defender'] += $victimsUnits[2]['number'] - $tempVictimsUnits[2]['number'];
			//$wolves['defender'] += $victimsUnits[3]['number'] - $tempVictimsUnits[3]['number'];
			
			//short range attack
			$shortRangeResult = $this->shortRangeAttack($tech, $tempAttackersUnits, $tempVictimsUnits, $user['User']['race'], $victim['User']['race'], $attackerAttackCoefficients, $victimAttackCoefficients, $debug);
			$tempVictimsUnits = $shortRangeResult[0];
			$tempAttackersUnits = $shortRangeResult[1];
			
			// melee attack
			$meleeResult = $this->meleeAttack($tech, $tempAttackersUnits, $tempVictimsUnits, $user['User']['race'], $victim['User']['race'], $attackerAttackCoefficients, $victimAttackCoefficients, $baseAttack, $dummyUnits, $debug, $wolves);
			$tempVictimsUnits = $meleeResult[0];
			$tempAttackersUnits = $meleeResult[1];
			
			$totalAttackersUnits = $totalUserUnits;
			$tempTotalAttackersUnits = 0;
			$totalVictimsUnits = $totalVictimsUnits;
			$worth = 1;
			$tempTotalVictimsUnits = 0;
			for ($i=0; $i<6; $i++){
				if($tempAttackersUnits[$i]['name'] == "Catapult"){
					$worth = 10;
				} elseif($tempAttackersUnits[$i]['name'] == "Ballista"){
					$worth = 7;
				} else {
					$worth = 1;
				}
				$tempTotalAttackersUnits += $tempAttackersUnits[$i]['number'] * $worth;
				//$totalAttackersUnits += $attackersUnits[$i]['number'];
			}
			for ($i=0; $i<6; $i++){
				//$totalVictimsUnits += $victimsUnits[$i]['number'];
				if($tempVictimsUnits[$i]['name'] == "Catapult"){
					$worth = 10;
				} elseif($tempVictimsUnits[$i]['name'] == "Ballista"){
					$worth = 7;
				} else {
					$worth = 1;
				}
				$tempTotalVictimsUnits += $tempVictimsUnits[$i]['number'];
				//echo "<br>The victim lost ". ($victimsUnits[$i]['number'] - $tempVictimsUnits[$i]['number']) . " " . $victimsUnits[$i]['name'];
			}
			$victimsLost = $totalVictimsUnits - $tempTotalVictimsUnits;
			$attackersLost = $totalAttackersUnits - $tempTotalAttackersUnits;
			
			//echo "<br>Victim lost: " . $victimsLost . ". Out of: " . $totalVictimsUnits;
			//echo "<br>Attacker lost: " . $attackersLost . ". Out of: " . $totalAttackersUnits ."<br /><br />";
			
			$userUnitsDead = $this->healUnits($user, $attackersUnits, $tempAttackersUnits, $totalAttackersUnits, $tempTotalAttackersUnits, false, $debug, $simulator);
			$victimUnitsDead = $this->healUnits($victim, $victimsUnits, $tempVictimsUnits, $totalVictimsUnits, $tempTotalVictimsUnits, true, $debug, $simulator);
				
				
			if($totalAttackersUnits != 0)
				$battleData['attackerLossPercent'] = ($totalAttackersUnits - $tempTotalAttackersUnits) / $totalAttackersUnits;
			else 
				$battleData['attackerLossPercent'] = 1.1;
			if ($totalVictimsUnits > 0)
				$battleData['victimLossPercent'] = ($totalVictimsUnits - $tempTotalVictimsUnits) / $totalVictimsUnits;
			else 
				$battleData['victimLossPercent'] = 1;
				
			//For quick retreat
			$victimLossChance = 0;
			$userLossChance = 0;
			if ($victim['User']['Army']['strategy'] == 'Quick Retreat')
				$victimLossChance += .1;	
			if ($user['User']['Army']['strategy'] == 'Quick Retreat')
				$userLossChance += .1;
			
			//For Tactical knowledge tech
			if ($victim['User']['Technology']['tree_1'] >= 6)
				$victimLossChance -= .1;
			if ($user['User']['Technology']['tree_1'] >= 6)
				$userLossChance -= .1;	
			
				
				
				
			$battleData['victimLossPercent'] += $victimLossChance;
			$battleData['attackerLossPercent'] += $userLossChance;
			if($battleData['victimLossPercent'] <= $battleData['attackerLossPercent'])
				$win = FALSE;
			else
				$win = TRUE;
			
			
			//kill victims units
			$victim['User']['Army']['small_elite'] -= $victimUnitsDead[0]['number'];
			$victim['User']['Army']['big_elite'] -= $victimUnitsDead[1]['number'];
			$victim['User']['Army']['weak'] -= $victimUnitsDead[2]['number'];
			$victim['User']['Army']['standard'] -= $victimUnitsDead[3]['number'];
			$victim['User']['Army']['range'] -= $victimUnitsDead[4]['number'];
			$victim['User']['Army']['mount'] -= $victimUnitsDead[5]['number'];
				
				
				
				
				
			if($win == FALSE){
				$user['User']['Army']['bas'] -= round(3*$user['User']['score']/$victim['User']['score']);
				
				if ($user['User']['Army']['bas'] < 75)
					$user['User']['Army']['bas'] = 75;
				if ($victim['User']['Army']['bas'] > 125) {
					
				} else {
					$victim['User']['Army']['bas'] += round(3*$user['User']['score']/$victim['User']['score']);
					if ($victim['User']['Army']['bas'] > 125) {
						$victim['User']['Army']['bas'] = 125;
					}
				}
					
					
				//leadership tech
				if (($user['User']['Technology']['tree_1'] > 4) && ($user['User']['Army']['bas'] < 100))
					$user['User']['Army']['bas'] = 100;
				
				if(empty($simulator)) {
					if($victim['User']['alliance']){
						$result = 'Your soldiers failed in their attack on <a href="/users/profile/' . $victim['User']['id'] . '">';
						$result .= $victim['User']['ruler'] . '</a> | <a href="/alliances/profile/' . $victim['User']['alliance_id'] . '">' . $victim['User']['alliance'] . '</a> <p />However, you killed ';
					} else {
						$result = 'Your soldiers failed in their attack on <a href="/users/profile/' . $victim['User']['id'] . '">';
						$result .= $victim['User']['ruler'] . "</a>. <p />However, you killed ";
					}
					for ($i=0; $i<6; $i++){
						if($i != 5)
							$result .= $this->pluralize($victimsUnits[$i]['name'], $victimUnitsDead[$i]['number']) . ", ";
						else
							$result .= $this->pluralize($victimsUnits[$i]['name'], $victimUnitsDead[$i]['number']);
					}
				}
				else {
					$result = "<table><tr><th></th><th></th>";
					for($i=0; $i<6; $i++) {
						$result .= "<th>" . $victimsUnits[$i]['name'] . "</th>";
					}
					$result .= "</tr>";
					$result .= "<tr><td rowspan=\"3\">Defender:</td><td>Units Sent</td>";
					for($i=0; $i<6; $i++){
						$result .= "<td>" . $victimsUnits[$i]['number'] . "</td>";
					}
					$result .= "</tr><tr><td>Units Lost</td>";
					for ($i=0; $i<6; $i++){
							$result .= "<td> ". ($victimUnitsDead[$i]['number'] - $victimUnitsDead[$i]['heal']) . "</td>";
					}
					$result .= "</tr><tr><td>Units Healed</td>";
					for ($i=0; $i<6; $i++){
							$result .= "<td> ". $victimUnitsDead[$i]['heal'] . "</td>";
					}
				}
				
				if(empty($simulator)) {
					if($user['User']['Building']['medical_center'] / $user['User']['Resource']['land'] > 0)
						$medCenterDisplay = "Our very overworked medical centers saved";
					if($user['User']['Building']['medical_center'] / $user['User']['Resource']['land'] > .25)
						$medCenterDisplay = "We were able to save";
					if($user['User']['Building']['medical_center'] / $user['User']['Resource']['land'] > .5)
						$medCenterDisplay = "Peasants praise your name due to the medical centers saving";
					
					$result .= ". <p />";
					if(isset($medCenterDisplay)) {
						$result .= "$medCenterDisplay ";
						for ($i=0; $i<6; $i++){
							if($i != 5)
								$result .= $this->pluralize($attackersUnits[$i]['name'], ($userUnitsDead[$i]['heal']) ). ", ";
								//$result .= $this->pluralize($attackersUnits[$i]['name'], ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number'])) . ", ";
							else
								$result .= $this->pluralize($attackersUnits[$i]['name'], ($userUnitsDead[$i]['heal']));
								//$result .= $this->pluralize($attackersUnits[$i]['name'], ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number']));
						}
						$result .= ". <br /><br />";
					}
					$result .= "Unfortunately, you also lost ";
					for ($i=0; $i<6; $i++){
						if($i != 5)
							$result .= $this->pluralize($attackersUnits[$i]['name'], ($userUnitsDead[$i]['number'] - $userUnitsDead[$i]['heal'])) . ", ";
							//$result .= $this->pluralize($attackersUnits[$i]['name'], ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number'])) . ", ";
						else
							$result .= $this->pluralize($attackersUnits[$i]['name'], ($userUnitsDead[$i]['number'] - $userUnitsDead[$i]['heal']));
							//$result .= $this->pluralize($attackersUnits[$i]['name'], ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number']));
					}
					$result .= ". ";
				}
				else {
					$result .= "<tr><th></th><th></th>";
					for($i=0; $i<6; $i++) {
						$result .= "<th>" . $attackersUnits[$i]['name'] . "</th>";
					}
					$result .= "</tr>";
					$result .= "<tr><td rowspan=\"3\">Attacker:</td><td>Units Sent</td>";
					for ($i=0; $i<6; $i++){
							$result .= "<td>". $attackersUnits[$i]['number'] . "</td>";
					}
					$result .= "</tr><tr><td>Units Lost</td>";
					for ($i=0; $i<6; $i++){
							$result .= "<td> ". ($userUnitsDead[$i]['number'] - $userUnitsDead[$i]['heal']) . "</td>";
					}
					$result .= "</tr><tr><td>Units Healed</td>";
					for ($i=0; $i<6; $i++){
							$result .= "<td> ". $userUnitsDead[$i]['heal'] . "</td>";
					}
					$result .= "</tr></table>";
				}
				
				
				
				if(empty($simulator)) {
					if($user['User']['alliance_id']){
						Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, "Fail Attack", $ipAddress, $user['User']['id'], "You attacked " . $victim['User']['ruler'] . " | " . $victim['User']['alliance'] . ".");
						$victimResult = '<a href="/users/profile/' . $user['User']['id'] . '">'. $user['User']['ruler'] .  
							'</a> | <a href="/alliances/profile/' . $user['User']['alliance_id'] . '">' . $user['User']['alliance'] . '</a> failed to invade your kingdom. <p />'
						 . "Your troops were able to kill ";
					} else {
						Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, "Fail Attack", $ipAddress, $user['User']['id'], "You attacked " . $victim['User']['ruler'] . ".");
						$victimResult = '<a href="/users/profile/' . $user['User']['id'] . '">'. $user['User']['ruler'] .  
							"</a> failed to invade your kingdom. <p />"
						 . "Your troops were able to kill ";
					}
					for ($i=0; $i<6; $i++){
						if($i != 5)
							$victimResult .= $this->pluralize($attackersUnits[$i]['name'], $userUnitsDead[$i]['number']) . ", ";
							//$victimResult .= ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number']) . " " . $attackersUnits[$i]['name'] . "s, ";
						else
							$victimResult .= $this->pluralize($attackersUnits[$i]['name'], $userUnitsDead[$i]['number']);
							//$victimResult .= ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number']) . " " . $attackersUnits[$i]['name'];
					}
					$victimResult .= ".<p /> Your medical centers saved the lives of ";
					for ($i=0; $i<6; $i++){
						if($i != 5)
							$victimResult .= $this->pluralize($victimsUnits[$i]['name'], ($victimUnitsDead[$i]['heal'])) . ", ";
						else
							$victimResult .= $this->pluralize($victimsUnits[$i]['name'], ($victimUnitsDead[$i]['heal']));
					}
					$victimResult .= ". <br /><br />";
					
					$victimResult .= "Unfortunately, you also lost ";
					for ($i=0; $i<6; $i++){
						if($i != 5)
							$victimResult .= $this->pluralize($victimsUnits[$i]['name'], ($victimUnitsDead[$i]['number'] - $victimUnitsDead[$i]['heal'])) . ", ";
						else
							$victimResult .= $this->pluralize($victimsUnits[$i]['name'], ($victimUnitsDead[$i]['number'] - $victimUnitsDead[$i]['heal']));
					}
					$victimResult .= ".";
					
					if($user['User']['alliance_id']) {
						Classregistry::init('Report')->makeNewReport($victim['User']['id'], $victimResult, "Success Defend", $ipAddress, $user['User']['id'], "You were attacked by " . $user['User']['ruler'] . " | " . $user['User']['alliance'] . ".");
					} else {
						Classregistry::init('Report')->makeNewReport($victim['User']['id'], $victimResult, "Success Defend", $ipAddress, $user['User']['id'], "You were attacked by " . $user['User']['ruler'] . ".");
					}
					
					$newBas['Army']['id'] = $user['User']['Army']['id'];
					$newBas['Army']['bas'] = $user['User']['Army']['bas']; 
				
					$this->User->Army->save($newBas['Army']);
				}
			}
			else {
				$result = $this->victory($user, $victim, $victimsUnits, $tempVictimsUnits, $attackersUnits, $tempAttackersUnits, $battleData['victimLossPercent'], $battleData['attackerLossPercent'], $simulator, $debug, $BASMultiplier, $userUnitsDead, $victimUnitsDead, $ipAddress);			
			}
			
			// $facebook->api_client->notifications_send($victim['User']['facebook_id'], 'Your kingdom was attacked, return to <a href="http://apps.facebook.com/ctagame">Call to Arms</a> to protect your kingdom!', 'app_to_user');
			
			if(empty($simulator)){
				$UnitStats = Classregistry::init('DeadUnit');
				$UnitStats->addOffensiveKills($victimUnitsDead, $victim['User']['race'], $user);
				$UnitStats->addDefensiveKills($userUnitsDead, $user['User']['race'], $victim);
			}
			
			
			$attacking['user_id'] = $user['User']['id'];
			$attacking['big_elite'] = $attackersUnits[1]['number'] - $userUnitsDead[1]['number'];
			$attacking['small_elite'] = $attackersUnits[0]['number'] - $userUnitsDead[0]['number'];
			$attacking['weak'] = $attackersUnits[2]['number'] - $userUnitsDead[2]['number'];
			$attacking['standard'] = $attackersUnits[3]['number'] - $userUnitsDead[3]['number'];
			$attacking['mount'] = $attackersUnits[5]['number'] - $userUnitsDead[5]['number'];
			$attacking['range'] = $attackersUnits[4]['number'] - $userUnitsDead[4]['number'];
			if ($user['User']['Technology']['tree_1'] > 2)
				$attacking['rounds'] = 18;
			
			if(empty($simulator))
				$this->save($attacking);
			if(empty($simulator) && !$retal) {
				$type = null;
				if($victim['User']['score'] < .6 * $user['User']['score']) {
					$type = "Bottomfeed";
				}
				$this->User->Retaliation->createRetaliation($victim['User']['id'], $user['User']['id'], $type);
				if($user['User']['alliance_id'] && $victim['User']['alliance_id']){
					$ARetaliation = Classregistry::init('AllianceRetaliation');
					$allianceRetaliation['AllianceRetaliation']['alliance_id'] = $victim['User']['alliance_id'];
					$allianceRetaliation['AllianceRetaliation']['attacker_id'] = $user['User']['alliance_id'];
					$allianceRetaliation['AllianceRetaliation']['datetime'] = date( 'Y-m-d H:i:s', time() );
					$allianceRetaliation['AllianceRetaliation']['player_id'] = $user['User']['id'];
					$ARetaliation->save($allianceRetaliation);
				}
			}
			
			if(empty($simulator)){
				unset($victim['User']['Army']['wizard']);
				unset($victim['User']['Army']['wizard_resting']);
				$this->User->Army->id = $victim['User']['Army']['id'];
				$this->User->Army->save($victim['User']['Army']);
			}
			
	    	if(empty($simulator)){
		    	// Find it because saving BAS was complicated last time I tried, this makes it so it's fresh already.
		    	$victimBas = $this->User->Army->find('first', array('conditions' => array('Army.user_id' => $victim['User']['id']), 'fields' => 'Army.id, Army.bas'));
		    	$userBas = $this->User->Army->find('first', array('conditions' => array('Army.user_id' => $user['User']['id']), 'fields' => 'Army.id, Army.bas'));
		    	
		    	// Cause I'm lazy and don't want to change the following code...
		    	$prevUserHonor = $user['User']['honor'];
		    	$prevVictimHonor = $victim['User']['honor'];
		    	
		    	$honorHalf = 1;
			    if($retal){
			    	$honorHalf = .5;
			    }	

	    	
			    if($win == TRUE){ // begin if attack succeeds
					if($victim['User']['score']/$user['User']['score'] >= .85) {
						$victim['User']['honor'] -= round($honorHalf * .25 * pow(abs(($victim['User']['score']/$user['User']['score']) * 100 - 85) * 13, 1.05));
						$user['User']['honor'] += round($honorHalf * pow(abs(($victim['User']['score']/$user['User']['score']) * 100 - 85) * 13, 1.05));
					} else {
						if(!$retal)
							$user['User']['honor'] += 29 + round($honorHalf * -abs(pow(100 - ($victim['User']['score']/$user['User']['score']) * 100, 3.5) / 150));
					}
					
					
					/*
					 * Bas
					 */
					if($victimBas['Army']['bas'] > 75) {
						$victimBas['Army']['bas'] -= round(.85*sqrt(abs($victim['User']['honor'] - $prevVictimHonor))/5);
						if($victimBas['Army']['bas'] < 75){
							$victimBas['Army']['bas'] = 75;
						}
					} 
					
					if(($user['User']['honor'] - $prevUserHonor) < 0) {
						if($userBas['Army']['bas'] < 150)
							$userBas['Army']['bas'] += round(sqrt(abs($user['User']['honor'] - $prevUserHonor))/5);
					} else {
						if($userBas['Army']['bas'] < 125)
							$userBas['Army']['bas'] += round(sqrt(abs($user['User']['honor'] - $prevUserHonor))/5);
					}
				} else {  // begin if attack fails
					if($victim['User']['score']/$user['User']['score'] >= .85) {
						if(!$retal)
							$user['User']['honor'] -= round($honorHalf * 1000/(($victim['User']['score']/$user['User']['score'])*100));
					} else {
						if(!$retal)
							$user['User']['honor'] += 29 + round($honorHalf * -abs(pow(100 - ($victim['User']['score']/$user['User']['score']) * 100, 3.1) / 150));
					}
					$victim['User']['honor'] += round($honorHalf * 20000/(($victim['User']['score']/$user['User']['score'])*100));
					
					
					/*
					 * BAS
					 */
					$userBas['Army']['bas'] -= round(sqrt(abs($user['User']['honor'] - $prevUserHonor))/5);
					
					if($victimBas['Army']['bas'] < 125) {
						$victimBas['Army']['bas'] += round(.85*sqrt(abs($victim['User']['honor'] - $prevVictimHonor))/5);
					}
					if($userBas['Army']['bas'] < 75) {
						$userBas['Army']['bas'] = 75;
					}
				} // End BAS / Honor change
			
				if($debug){
			    	echo "Victim change: " . ($victim['User']['honor'] - $prevVictimHonor) . "<br />";
			    	echo "User change: " . ($user['User']['honor'] - $prevUserHonor) . "<br />";
				}
			
				// Victim Bonus
		    	/* $victimBonus = round(pow(abs(100-(($victim['User']['score']/$user['User']['score'])*100)),1.4) + 100);
		    	if($victim['User']['score'] / $user['User']['score'] < .85 && $victim['User']['victim_bonus'] < $victimBonus) {
		    		$victim['User']['victim_bonus'] = $victimBonus;
		    		$victim['User']['victim_time'] = 6;
		    	} else if ($victim['User']['score'] / $user['User']['score'] < .85 && $victim['User']['victim_time'] < 6){
		    		$victim['User']['victim_time'] = 6;
		    	}*/
		    	
		    	if(empty($simulator)){
			    	$this->User->Army->save($userBas);
			    	$this->User->Army->save($victimBas);
			    	$this->User->save($user['User'], array('validate' => true, 'fieldList' => array('honor')));
			    	$this->User->save($victim['User'], array('validate' => true, 'fieldList' => array('honor', 'victim_bonus', 'victim_time')));
		    	}
	    	}
	    } else {
	    	$attacking['Attack']['user_id'] = $user['User']['id'];
			$attacking['Attack']['big_elite'] = $data['Attack']['big_elite'];
			$attacking['Attack']['small_elite'] = $data['Attack']['small_elite'];
			$attacking['Attack']['weak'] = $data['Attack']['weak'];
			$attacking['Attack']['standard'] = $data['Attack']['standard'];
			$attacking['Attack']['mount'] = $data['Attack']['mount'];
			$attacking['Attack']['range'] = $data['Attack']['range'];
			$attacking['Attack']['rounds'] = mt_rand(2,4);
			if(empty($simulator)) {
	    		$this->save($attacking);
			}
	    	$result = "Your forces have lost their way in what appears to be a thick, magical fog. It will be " . $attacking['Attack']['rounds'] . " hours before they will be able to find their way home.";
	    	if(empty($simulator))
	    		Classregistry::init('Report')->makeNewReport($data['Attack']['target'] , 'The scouts at the edge of your kingdom reported seeing an army wondering around. Luckily your Shadow Hall\'s fog was enough to keep them away. They may be back soon, you should prepare for battle.', "Missed Defend", $ipAddress, $user['User']['id'], "You were attacked by a mysterious kingdom.");
	    }
	    

		
		return $result;
	}
	
	function killUnits ($user = null, $units = null, $deadUnits = null){
		
	}
	
	function victory($user = null, $victim = null, $victimsUnits = null,$tempVictimsUnits = null, $attackersUnits = null, $tempAttackersUnits = null, $victimLossPercent = null, $attackersLossPercent = null, $simulator = array(), $debug = false, $spoilsMultiplier = 1, $userUnitsDead, $victimUnitsDead, $ipAddress = null){
		if(empty($simulator)){
			$newUserBas = round(3*$victim['User']['score']/$user['User']['score']);
			$victim['User']['Army']['bas'] -= round(3*$victim['User']['score']/$user['User']['score']);
			
			if ($user['User']['Army']['bas'] > 125) {
				// do nothing b/c only honor can push it above 125
			} else {
				$user['User']['Army']['bas'] += $newUserBas;
				if($user['User']['Army']['bas'] > 125) {
					$user['User']['Army']['bas'] = 125;
				}
			}
			if ($victim['User']['Army']['bas'] < 75)
				$victim['User']['Army']['bas'] = 75;
			
			//leadership tech
			if (($victim['User']['Technology']['tree_1'] >= 3) && ($victim['User']['Army']['bas'] < 100))
				$victim['User']['Army']['bas'] = 100;
			$AllianceFriend = Classregistry::init('AllianceFriend');
			$relation = $AllianceFriend->find('first', array('conditions' => array('AllianceFriend.alliance_id' => $user['User']['alliance_id'], 'AllianceFriend.friend_id' => $victim['User']['alliance_id'])));
			$spoilsAddition = 0;
			if(!empty($relation)) {
				if($relation['AllianceFriend']['type'] == "enemy"){
					$spoilsAddition = .05;
				}
			}
			$spoilPercent = (.1 + $spoilsAddition) * $spoilsMultiplier;
			
			if($victim['User']['Building']['castle'] > 1 && $victim['User']['Building']['castle'] < 20 && $spoilsMultiplier > .7)
				$castlesTaken = 1;
			else
				$castlesTaken = floor($spoilPercent * $victim['User']['Building']['castle']);
			
			$landTaken = ceil($spoilPercent * $victim['User']['Resource']['land']);
			if($landTaken < 5 && $spoilsMultiplier > .7)
				$landTaken = 5;
				
			if($victim['User']['Building']['castle'] < $castlesTaken) {
				$castlesTaken = $victim['User']['Building']['castle'] - 1;
			}
			
			if(($victim['User']['Resource']['land'] - $landTaken) < 10){
				$landTaken -= (10 - ($victim['User']['Resource']['land'] - $landTaken));		
			}
			
			$spoilPercent = $landTaken / $victim['User']['Resource']['land'];
			
			
			$victoryMagnitude = round(100*($victimLossPercent - $attackersLossPercent));
			$luck = mt_rand(4, 10);
			$victoryMagnitude += $luck;
			if ($victoryMagnitude > 100)
				$victoryMagnitude = 100;
			
			/*
			$spoilPercent = $victoryMagnitude/1000;
			
			if(($victim['User']['score'] / $user['User']['score']) < .6){
				$spoilPercent = $spoilPercent * ($victim['User']['score'] / $user['User']['score']);
				echo "Spoil percent after adjusted for smaller victim: $spoilPercent<br />";
			}
			elseif(($victim['User']['score'] / $user['User']['score']) > 2){
				$spoilPercent = $spoilPercent * 2;
				echo "Spoil percent after adjusted for larger victim: $spoilPercent<br />";
			}
			else{
				$spoilPercent = $spoilPercent;
				echo "Spoil percent not adjusted for size: $spoilPercent<br />";
			}
				
			
			
			$castlesTaken = round($spoilPercent*$victim['User']['Building']['castle']);
			if ($victim['User']['Building']['castle'] < 11 && $spoilPercent > .06 && $victim['User']['Building']['castle'] > 1) {
				$castlesTaken = 1;
				echo "Victim has less than 11 castles and has 1 taken. <br />";
			}
			if ($victim['User']['Building']['castle'] < 20 && $spoilPercent > .06 && $victim['User']['Building']['castle'] > 10){
				$castlesTaken = 1;
				echo "Victim has 11-19 castles and has 1 taken. <br />";
			}
			if($victim['User']['Building']['castle'] < 20 && $spoilPercent > .08 && $victim['User']['Building']['castle'] > 10){
				$castlesTaken = 2;
				echo "Victim has 11-19 castles and has 2 taken. <br />";
			}
			if($victim['User']['Building']['castle'] == 1){
				echo "Victim has 1 castle and cannot have it taken. <br />";
				$castlesTaken = 0;
			}
			if($victim['User']['Building']['castle'] > 19 && $castlesTaken > 0){
				echo "Victim castles above 19. <br />";
				//$castlesTaken = round($castlesTaken * 1.7);
			}
			if($castlesTaken > 0){
				echo "Castles taken is above 0: $castlesTaken <br />";
				$spoilPercent = $castlesTaken/$victim['User']['Building']['castle'];
			}
			if($victim['User']['Building']['castle'] < $castlesTaken) {
				echo "Castles were reduced to leave the victim 1. <br />";
				$castlesTaken = $victim['User']['Building']['castle'] - 1;
			}
				
			
				
			if($castlesTaken <= 0){
				echo "No castles were taken. <br />";
				$landTaken = round(($victim['User']['Resource']['land'] / $victim['User']['Building']['castle']) * $spoilPercent * 10);
				$spoilPercent = $landTaken / $victim['User']['Resource']['land'];
			}
			else {
				$landTaken = round($victim['User']['Resource']['land'] / $victim['User']['Building']['castle']) * $castlesTaken;
				$spoilPercent = $landTaken / $victim['User']['Resource']['land'];
			}
			
			if(($victim['User']['Resource']['land'] - $landTaken) < 10){
				$landTaken -= (10 - ($victim['User']['Resource']['land'] - $landTaken));
				$spoilPercent = $landTaken / $victim['User']['Resource']['land'];
			}
			*/
			/*
			$victimHousingBonus = $this->Army->getHousingBonus($user);
			$victimHousingTech = 0;
			if($victim['User']['Technology']['tree_2'] > 4)
				$victimHousingTech = 25;
			
			$victimSchoolHousing = $user['User']['Building']['school'] * (40+$housingBonus+$tech);
			*/
				
			//$peasantSpoilPercent = ($victim['User']['Building']['house'] * 100) / $victim['User']['Population']['total_population'];
			$peasantsTaken = round($spoilPercent*$victim['User']['Population']['total_population']/2);
			// conversion technology
			if ($user['User']['Technology']['tree_1'] >= 2)
				$peasantsTaken = round(1.5*$spoilPercent*$victim['User']['Population']['total_population']/2);
			
			$farmsTaken = round($spoilPercent*$victim['User']['Building']['farm']);
			$housesTaken = round($spoilPercent*$victim['User']['Building']['house']);
			$marketsTaken = round($spoilPercent*$victim['User']['Building']['market']);
			$millsTaken = round($spoilPercent*$victim['User']['Building']['mill']);
			$minesTaken = round($spoilPercent*$victim['User']['Building']['mine']);
			$trainingCentersTaken = round($spoilPercent*$victim['User']['Building']['training_center']);
			$advancedTrainingCentersTaken = round($spoilPercent*$victim['User']['Building']['advanced_training_center']);
			$schoolsTaken = round($spoilPercent*$victim['User']['Building']['school']);
			$forgesTaken = round($spoilPercent*$victim['User']['Building']['forge']);
			$guardHousesTaken = round($spoilPercent*$victim['User']['Building']['guard_house']);
			$guardTowersTaken = round($spoilPercent*$victim['User']['Building']['guard_tower']);
			$medicalCentersTaken = round($spoilPercent*$victim['User']['Building']['medical_center']);
			
			
			
			$victim['User']['Building']['farm'] -= $farmsTaken;
			$victim['User']['Building']['house'] -= $housesTaken;
			$victim['User']['Building']['mill'] -= $millsTaken;
			$victim['User']['Building']['mine'] -= $minesTaken;
			$victim['User']['Building']['medical_center'] -= $medicalCentersTaken;
			$victim['User']['Building']['guard_house'] -= $guardHousesTaken;
			$victim['User']['Building']['guard_tower'] -= $guardTowersTaken;
			$victim['User']['Building']['market'] -= $marketsTaken;
			$victim['User']['Building']['school'] -= $schoolsTaken;
			$victim['User']['Building']['forge'] -= $forgesTaken;
			$victim['User']['Building']['training_center'] -= $trainingCentersTaken;
			$victim['User']['Building']['advanced_training_center'] -= $advancedTrainingCentersTaken;
			
			// Kill non-resting wizards
			$wizardsKilled = floor($spoilPercent*$victim['User']['Army']['wizard']);
			
			$basArmy['User']['Army']['wizard'] = $victim['User']['Army']['wizard'] - $wizardsKilled;
			if($basArmy['User']['Army']['wizard'] < 0)
				$basArmy['User']['Army']['wizard'] = 0;
			
			// Kill resting wizards
			$wizardsKilled += floor($spoilPercent*$victim['User']['Army']['wizard_resting']);
			$basArmy['User']['Army']['wizard_resting'] =  $victim['User']['Army']['wizard_resting'] - floor($spoilPercent*$victim['User']['Army']['wizard_resting']);
			if($basArmy['User']['Army']['wizard_resting'] < 0)
				$basArmy['User']['Army']['wizard_resting'] = 0;
		
			if($user['User']['alliance']){
				$victimResult = '<a href="/users/profile/' . $user['User']['id'] . '">' . $user['User']['ruler'] . 
					'</a> | <a href="/alliances/profile/' . $user['User']['alliance_id'] . '">' . $user['User']['alliance'] . '</a> ';
			} else {
				$victimResult = '<a href="/users/profile/' . $user['User']['id'] . '">' . $user['User']['ruler'] . 
					"</a> ";
			}
			$victimResult .= "successfully invaded your kingdom, taking $castlesTaken castles, enslaving $peasantsTaken of your peasants and taken $landTaken land, on which was built 
			$farmsTaken farms, $housesTaken houses,
			 $marketsTaken markets, $millsTaken mills, $minesTaken mines, $trainingCentersTaken training centers, 
			$advancedTrainingCentersTaken advanced training centers, $schoolsTaken schools, $forgesTaken forges, $guardHousesTaken guard houses, 
			$guardTowersTaken guard towers, and $medicalCentersTaken medical centers. The invaders also ruthlessly slaughtered " . $this->pluralize('wizard', $wizardsKilled) . ". <p />"
			 . "Your troops were able to kill ";
			for ($i=0; $i<6; $i++){
				if($i != 5)
					$victimResult .= $this->pluralize($attackersUnits[$i]['name'], round($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number'])) . ", ";
				else
					$victimResult .= $this->pluralize($attackersUnits[$i]['name'], round($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number']));
			}
			$victimResult .= ". <p />";
			
			$victimResult .= "Your medical centers were able to keep ";
			for ($i=0; $i<6; $i++){
				if($i != 5)
					$victimResult .= $this->pluralize($victimsUnits[$i]['name'], round($victimUnitsDead[$i]['heal'])) . ", ";
				else
					$victimResult .= $this->pluralize($victimsUnits[$i]['name'], round($victimUnitsDead[$i]['heal']));
			}
			$victimResult .= " alive. <br /><br />";
			
			$victimResult .= "Unfortunately, you also lost ";
			for ($i=0; $i<6; $i++){
				if($i != 5)
					$victimResult .= $this->pluralize($victimsUnits[$i]['name'], round($victimsUnits[$i]['number'] - $tempVictimsUnits[$i]['number'] - $victimUnitsDead[$i]['heal'])) . ", ";
				else
					$victimResult .= $this->pluralize($victimsUnits[$i]['name'], round($victimsUnits[$i]['number'] - $tempVictimsUnits[$i]['number'] - $victimUnitsDead[$i]['heal']));
			}
			$victimResult .= ". ";
			
			if($user['User']['alliance']){
				Classregistry::init('Report')->makeNewReport($victim['User']['id'], $victimResult, "Fail Defend", $ipAddress, $user['User']['id'], "You were attacked by " . $user['User']['ruler'] . " | " . $user['User']['alliance'] . ".");
			} else {
				Classregistry::init('Report')->makeNewReport($victim['User']['id'], $victimResult, "Fail Defend", $ipAddress, $user['User']['id'], "You were attacked by " . $user['User']['ruler'] . ".");
			}
			
			$spoilPercent *= 8;
			if($spoilPercent > 1)
				$spoilPercent = 1;
			$farmsTaken = round($farmsTaken * $spoilPercent);
			$housesTaken = round($housesTaken * $spoilPercent);
			$marketsTaken = round($marketsTaken * $spoilPercent);
			$millsTaken = round($millsTaken * $spoilPercent);
			$minesTaken = round($minesTaken * $spoilPercent);
			$trainingCentersTaken = round($trainingCentersTaken * $spoilPercent);
			$advancedTrainingCentersTaken = round($advancedTrainingCentersTaken * $spoilPercent);
			$schoolsTaken = round($schoolsTaken * $spoilPercent);
			$forgesTaken = round($forgesTaken * $spoilPercent);
			$guardHousesTaken = round($guardHousesTaken * $spoilPercent);
			$guardTowersTaken = round($guardTowersTaken * $spoilPercent);
			$medicalCentersTaken = round($medicalCentersTaken * $spoilPercent);
		}
		
		
		
		if(empty($simulator)) {
			if($victim['User']['alliance']) {
				$result = 'Your soldiers successfully invaded <a href="/users/profile/' . $victim['User']['id'] . '">'. $victim['User']['ruler'] . "'s kingdom"
				 . '</a> | <a href="/alliances/profile/' . $victim['User']['alliance_id'] . '">' . $victim['User']['alliance'] . '</a>.';
			} else {
				$result = 'Your soldiers successfully invaded <a href="/users/profile/' . $victim['User']['id'] . '">'. $victim['User']['ruler'] . "'s kingdom"
				 . "</a>.";
			}
			$result .= " <p />You have taken $castlesTaken castles, enslaved $peasantsTaken peasants, and taken $landTaken land on which was built $farmsTaken farms, $housesTaken houses,
			 $marketsTaken markets, $millsTaken mills, $minesTaken mines, $trainingCentersTaken training centers, 
			 $advancedTrainingCentersTaken advanced training centers, $schoolsTaken schools, $forgesTaken forges, $guardHousesTaken guard houses, 
			$guardTowersTaken guard towers, and $medicalCentersTaken medical centers. You also ruthlessly slaughtered " . $this->pluralize('wizard', $wizardsKilled) . ". <p />"
			. "Additionally, you killed ";
			
			for ($i=0; $i<6; $i++){
				if($i != 5)
					$result .= $this->pluralize($victimsUnits[$i]['name'], round($victimsUnits[$i]['number'] - $tempVictimsUnits[$i]['number'])) . ", ";
				else
					$result .= $this->pluralize($victimsUnits[$i]['name'], round($victimsUnits[$i]['number'] - $tempVictimsUnits[$i]['number']));
			}
		}
		else {
			$result = "<table><tr><th></th><th></th>";
			for($i=0; $i<6; $i++) {
				$result .= "<th>" . $victimsUnits[$i]['name'] . "</th>";
			}
			$result .= "</tr>";
			$result .= "<tr><td rowspan=\"3\">Defender:</td><td>Units Sent</td>";
			for($i=0; $i<6; $i++){
				$result .= "<td>" . $victimsUnits[$i]['number'] . "</td>";
			}
			$result .= "</tr><tr><td>Units Lost</td>";
			for ($i=0; $i<6; $i++){
					$result .= "<td> ". ($victimsUnits[$i]['number'] - $tempVictimsUnits[$i]['number'] - $victimUnitsDead[$i]['heal']) . "</td>";
			}
			$result .= "</tr><tr><td>Units Healed</td>";
			for ($i=0; $i<6; $i++){
					$result .= "<td> ". ($victimUnitsDead[$i]['heal']) . "</td>";
			}
		}
		
		
		if(empty($simulator)) {
			$result .= ". <p />";
				
			if($user['User']['Building']['medical_center'] / $user['User']['Resource']['land'] >= 0)
				$medCenterDisplay = "Our very overworked medical centers saved";
			if($user['User']['Building']['medical_center'] / $user['User']['Resource']['land'] > .25)
				$medCenterDisplay = "We were able to save";
			if($user['User']['Building']['medical_center'] / $user['User']['Resource']['land'] > .5)
				$medCenterDisplay = "Peasants praise your name due to the medical centers saving";
			
			if(isset($medCenterDisplay)) {
				$result .= "$medCenterDisplay ";
				for ($i=0; $i<6; $i++){
					if($i != 5)
						$result .= $this->pluralize($attackersUnits[$i]['name'], ($userUnitsDead[$i]['heal']) ). ", ";
						//$result .= $this->pluralize($attackersUnits[$i]['name'], ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number'])) . ", ";
					else
						$result .= $this->pluralize($attackersUnits[$i]['name'], ($userUnitsDead[$i]['heal']));
						//$result .= $this->pluralize($attackersUnits[$i]['name'], ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number']));
				}
				$result .= ". <br /><br />";
			}
			
			
			$result .= "Unfortunately, you also lost ";
			for ($i=0; $i<6; $i++){
			if($i != 5)
				$result .= $this->pluralize($attackersUnits[$i]['name'], round($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number'] - $userUnitsDead[$i]['heal'])) . ", ";
			else
				$result .= $this->pluralize($attackersUnits[$i]['name'], round($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number'] - $userUnitsDead[$i]['heal']));
			}
		$result .= ". ";
		}
		else {
			$result .= "<tr><th></th><th></th>";
			for($i=0; $i<6; $i++) {
				$result .= "<th>" . $attackersUnits[$i]['name'] . "</th>";
			}
			$result .= "</tr>";
			$result .= "<tr><td rowspan=\"3\">Attacker:</td><td>Units Sent</td>";
			for ($i=0; $i<6; $i++){
					$result .= "<td>". $attackersUnits[$i]['number'] . "</td>";
			}
			$result .= "</tr><tr><td>Units Lost</td>";
			for ($i=0; $i<6; $i++){
					$result .= "<td> ". ($attackersUnits[$i]['number'] - $tempAttackersUnits[$i]['number'] - $userUnitsDead[$i]['heal']) . "</td>";
			}
			$result .= "</tr><tr><td>Units Healed</td>";
			for ($i=0; $i<6; $i++){
					$result .= "<td> ". ($userUnitsDead[$i]['heal']) . "</td>";
			}
			$result .= "</tr></table>";
		}
		
		
		if(empty($simulator)) {
			if($victim['User']['alliance']){
				Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, "Success Attack", $ipAddress, $user['User']['id'], "You attacked " . $victim['User']['ruler'] . " | " . $victim['User']['alliance'] . ".");
			} else {
				Classregistry::init('Report')->makeNewReport($user['User']['id'], $result, "Success Attack", $ipAddress, $user['User']['id'], "You attacked " . $victim['User']['ruler'] . ".");
			}
			
			
			$user['User']['crime_rate'] += $castlesTaken * 5;
			$user['User']['Building']['castle'] += $castlesTaken;
			$user['User']['Resource']['land'] += $landTaken;
			$user['User']['Population']['total_population'] += $peasantsTaken;
			$user['User']['Population']['agriculture'] += $peasantsTaken;
			$user['User']['Building']['farm'] += $farmsTaken;
			$user['User']['Building']['house'] += $housesTaken;
			$user['User']['Building']['mill'] += $millsTaken;
			$user['User']['Building']['mine'] += $minesTaken;
			$user['User']['Building']['medical_center'] += $medicalCentersTaken;
			$user['User']['Building']['guard_house'] += $guardHousesTaken;
			$user['User']['Building']['guard_tower'] += $guardTowersTaken;
			$user['User']['Building']['market'] += $marketsTaken;
			$user['User']['Building']['school'] += $schoolsTaken;
			$user['User']['Building']['forge'] += $forgesTaken;
			$user['User']['Building']['training_center'] += $trainingCentersTaken;
			$user['User']['Building']['advanced_training_center'] += $advancedTrainingCentersTaken;
			
	
			$victim['User']['Building']['castle'] -= $castlesTaken;
			$victim['User']['Resource']['land'] -= $landTaken;
			$victim['User']['Population']['total_population'] -= $peasantsTaken;
			
			$population[0]['type'] = "lumbers";
			$population[0]['amount'] = $victim['User']['Population']['lumbers'];
			$population[0]['percent_dieing'] = 0;
			$population[1]['type'] = "miners";
			$population[1]['amount'] = $victim['User']['Population']['miners'];
			$population[1]['percent_dieing'] = 0;
			$population[2]['type'] = "agriculture";
			$population[2]['amount'] = $victim['User']['Population']['agriculture'];
			$population[2]['percent_dieing'] = 0;
			$population[3]['type'] = "building";
			$population[3]['amount'] = $victim['User']['Population']['building'];
			$population[3]['percent_dieing'] = 0;
			$population[4]['type'] = "blacksmiths";
			$population[4]['amount'] = $victim['User']['Population']['blacksmiths'];
			$population[4]['percent_dieing'] = 0;
			$population[5]['type'] = "explorers";
			$population[5]['amount'] = $victim['User']['Population']['explorers'];
			$population[5]['percent_dieing'] = 0;
			$population[6]['type'] = "training";
			$population[6]['amount'] = $victim['User']['Population']['training'];
			$population[6]['percent_dieing'] = 0;
			
			$total = $victim['User']['Population']['total_population'];
			//echo "<br />Total population: " . number_format($total) . " <br />";
			
			if($total < $peasantsTaken)
				$peasantsTaken = $total;
			//echo "Peasants Taken: " . number_format($peasantsTaken) . " <br />";
			foreach($population as $type){
				$category = $type['type'];
				if($total > 0)
					$type['percent_dieing'] = $type['amount'] / $total;
				else
					$type['percent_dieing'] = 0;
				//echo "$category is " . ($type['percent_dieing']*100) . "% of population at " . number_format($type['amount']) . ". <br />";
				//echo "$category has " . number_format(round($peasantsTaken * $type['percent_dieing'])) . " being taken. <br />";
				$type['amount'] -= round($peasantsTaken * $type['percent_dieing']);
				if($type['amount'] < 0)
					$type['amount'] = 0;
				$victim['User']['Population'][$category] = $type['amount'];
				
			}
			
			if(empty($simulator)){ 
				//echo "Victim's Population: <br />";
				//debug($victim['User']['Population']);
				$this->User->id = $user['User']['id'];
				$this->User->Building->id =$user['User']['id'];
				$this->User->Resource->id =$user['User']['id'];
				$this->User->Population->id =$user['User']['id'];
				$this->User->Army->id = $user['User']['Army']['id'];
				$this->User->save($user['User']['crime_rate']);
				$this->User->Building->save($user['User']['Building']);
				$this->User->Resource->save($user['User']['Resource']);
				$this->User->Population->save($user['User']['Population']);
				$basArmy['User']['Army']['id'] = $user['User']['Army']['id'];
				$basArmy['User']['Army']['bas'] = $user['User']['Army']['bas'];
				$this->User->Army->save($user['User']['Army']);
				
				
				$this->User->id = $victim['User']['id'];
				$this->User->Building->id =$victim['User']['id'];
				$this->User->Resource->id =$victim['User']['id'];
				$this->User->Population->id =$victim['User']['id'];
				$this->User->Army->id = $victim['User']['Army']['id'];
				$this->User->Building->save($victim['User']['Building']);
				$this->User->Resource->save($victim['User']['Resource']);
				$this->User->Population->save($victim['User']['Population']);
				$basArmy['User']['Army']['id'] = $victim['User']['Army']['id'];
				$basArmy['User']['Army']['bas'] = $victim['User']['Army']['bas'];
				$this->User->Army->save($basArmy['User']['Army']);
				
				if($castlesTaken < 1){
					$protection = 18;
					if($victim['User']['protection'] < 18)
						$victim['User']['protection'] += ($protection - $victim['User']['protection']);
				}
				else {
					$protection = 24;
					if($victim['User']['protection'] < 24)
						$victim['User']['protection'] += ($protection - $victim['User']['protection']);
				}
					
				
				
				$this->User->save($victim['User'], array('validate' => true, 'fieldList' => array('protection')));
			}
		}
		
		return $result;
	}
	
	function getVictimData($target = null){
		return $this->find('first', array('conditions' => array('User.id' => $target)));
	}
	
	function getTotalVictimUnits($victim = null, $race = null){
		$cat = 1;
		$bal = 1;
		$sb = 1;
		$mil = 1;
		if($race == "Dwarf")
			$sb = 4;
		if($race == "Gnome") {
			$bal = 7;
			$cat = 10;
			$mil = .75;
		}
		if(isset($victim['User']['Army']))
			return $victim['User']['Army']['small_elite'] * $bal +  $victim['User']['Army']['big_elite'] * $sb * $cat
	         +  round($victim['User']['Army']['weak'] * $mil) +  $victim['User']['Army']['standard']
	         +  round($victim['User']['Army']['range'] * $mil) +  $victim['User']['Army']['mount'];
	    else if (isset($victim['Army']))
	   		return $victim['Army']['small_elite'] * $bal +  $victim['Army']['big_elite'] * $sb * $cat
	         +  round($victim['Army']['weak'] * $mil) +  $victim['Army']['standard']
	         +  round($victim['Army']['range'] * $mil) +  $victim['Army']['mount'];
	    else
	    	return $victim['Attack']['small_elite'] * $bal + $victim['Attack']['big_elite'] * $sb * $cat + 
	    	round($victim['Attack']['weak'] * $mil) + $victim['Attack']['standard'] + 
	    	round($victim['Attack']['range'] * $mil) + $victim['Attack']['mount'];
	}
	
	function getUnitArray($userId = null, $sentUnits = null, $totalEnemyUnits = null, $castles = 1, $bas = 100, $defender = false, $user = array(), $debug = false){
		if(empty($user))
			$user = $this->find('first', array('conditions' => array('User.id' => $userId)));
		
		$knightPercent = 0;
		//$unitDefenseMultiplier = (100 + .5*$castles)/100;
		
		$unitAttackMultiplier = ($bas/100)*.5;
		// twice BAS advantage to defender, as specified in the game guide
		if ($defender && ($bas > 100)) {
			//$unitAttackMultiplier = (.04*($bas - 100) + 1)*.5;
		}
		//Dwarf True Silver Tech
		if (($user['User']['Technology']['racial'] == 1) && ($user['User']['race'] == 'Dwarf'))
			$shieldBearerDefense = 27;
		else
			$shieldBearerDefense = 20;
			
		$rangeTech = 0;
		if($user['User']['Technology']['range'] > 0)
			$rangeTech = $user['User']['Technology']['range'];
			
		$magicTech = $user['User']['Technology']['tree_4'];
		if($magicTech > 4)
			$magicTech = 4;
			
		$bladeTech = $user['User']['Technology']['blade'];
		$lightArmorTech = $user['User']['Technology']['light_armor'];
		$heavyArmorTech = $user['User']['Technology']['heavy_armor'];
			
		$shieldBearerStrat = 1;
		if($user['User']['Army']['strategy'] == 'Shield Line')
			$shieldBearerStrat = 0;
		//echo "<br>Unit Attack Multiplier: " . $unitAttackMultiplier;
		//echo "<br>Bas inside unit array: " . $bas;
		
			
		if (($user['User']['Spell']['firewall'] > 0) && $defender)
			$unitAttackMultiplier *= 1.08;
	
		if($user['User']['race'] == "Orc" || $user['User']['race'] == "all"){
			$shadowShort = $unitAttackMultiplier * (7 + $bladeTech);
			$shadowAttack = $unitAttackMultiplier * (3 + $bladeTech);
			$shadowDefense = 0;
			
			if($user['User']['Army']['strategy'] == 'Surrounding'){
				$shadowShort = $unitAttackMultiplier * (10 + $bladeTech);
				$shadowAttack = 0;
				$shadowDefense = 0;
			}
			if ($sentUnits['Attack']['standard'] > 0)
				$rusherShortRange = (20*$sentUnits['Attack']['small_elite']/$sentUnits['Attack']['standard']);
			else
				$rusherShortRange = 0;
			if ($sentUnits['Attack']['weak'] > 0)
				$slotherShortRange = (20*$sentUnits['Attack']['small_elite']/$sentUnits['Attack']['weak']);
			else
				$slotherShortRange = 0;
				
			if($slotherShortRange > 2)
				$slotherShortRange = 2;
			if($rusherShortRange > 4)
				$rusherShortRange = 4;
			
			$unitsData = array(
				0 => array(
					'type' => "small_elite",
					'name' => "Wolf Master",
					'attack' => $unitAttackMultiplier * (3 + $bladeTech),
					'defense' =>   6 + $heavyArmorTech,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => TRUE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['small_elite']
				),
				1 => array(
					'type' => "big_elite",
					'name' => "Shadow Warrior",
					'attack' => $shadowAttack,
					'defense' =>   4 + $lightArmorTech + $shadowDefense,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => $shadowShort,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['big_elite']
				),
				2 => array(
				 	'type' => "weak",
					'name' => "Slother",
					'attack' => $unitAttackMultiplier * (2 + $bladeTech * .5),
					'defense' =>   2,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => $unitAttackMultiplier * ($slotherShortRange + $bladeTech * .5),
					'isSpear' => TRUE,
					'isMount' => TRUE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['weak']
				),
				3 => array(
					'type' => "standard",
					'name' => "Rusher",
					'attack' => $unitAttackMultiplier * (2 + $bladeTech * .5),
					'defense' =>   3 + $heavyArmorTech,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => $unitAttackMultiplier * ($rusherShortRange + $bladeTech * .5),
					'isSpear' => FALSE,
					'isMount' => TRUE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['standard']
				),
				4 => array(
					'type' => "range",
					'name' => "Slinger",
					'attack' => $unitAttackMultiplier * 0,
					'defense' =>   2,
					'range' => $unitAttackMultiplier * (3 + $rangeTech),
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => TRUE,
					'number' =>   $sentUnits['Attack']['range']
				),
				5 => array(
					'type' => "mount",
					'name' => "Axe Thrower",
					'attack' => $unitAttackMultiplier * (2 + $bladeTech),
					'defense' =>   4 + $lightArmorTech,
					'range' => $unitAttackMultiplier * (3 + $bladeTech),
					'shortRange' => $unitAttackMultiplier * (2 + $bladeTech),
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['mount']
				)
			);
			
			
			if($user['User']['Technology']['racial']) {
				$unitsData['cloaking'] = 0;
				$unitsData['wolfAttack'] = (3 + ($bladeTech * .5)) * $unitAttackMultiplier;
			}
			else {
				$unitsData['cloaking'] = 0;
			}
			$allUnits['orc'] = $unitsData;
		}
		else if($user['User']['race'] == "Gnome" || $user['User']['race'] == "all"){
			//Gnome explosive power tech
			if (($user['User']['Technology']['racial'] == 1) && ($user['User']['race'] == 'Gnome')){
				$catapultExplosives = 9;
				$ballistaExplosiveMelee = 4;
				$ballistaExplosiveShort = 2;
				$ballistaExplosiveLong = 1;
			}
			else{
				$catapultExplosives = 0;
				$ballistaExplosiveMelee = 0;
				$ballistaExplosiveShort = 0;
				$ballistaExplosiveLong = 0;
			}
			if($rangeTech){
				$ballistaRange = .5;
				$ballistaShortRange = 1;
				$catapultRange = 3;
			} else{
				$ballistaRange = 0; 
				$ballistaShortRange = 0;
				$catapultRange = 0;
			}
			
			
			$unitsData = array(
				0 => array(
					'type' => "small_elite",
					'name' => "Ballista",
					'attack' => $unitAttackMultiplier * (8 + $bladeTech + $ballistaExplosiveMelee),
					'defense' =>   15,
					'range' => $unitAttackMultiplier * ($ballistaRange * $rangeTech + $ballistaExplosiveLong),
					'shortRange' => $unitAttackMultiplier * (5 + $ballistaShortRange * $rangeTech + $ballistaExplosiveShort),
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['small_elite']
				),
				1 => array(
					'type' => "big_elite",
					'name' => "Catapult",
					'attack' => $unitAttackMultiplier * 0,
					'defense' =>   18,
					'range' => $unitAttackMultiplier * (9 + $catapultExplosives + $catapultRange * $rangeTech),
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['big_elite']
				),
				2 => array(
				 	'type' => "weak",
					'name' => "Militia",
					'attack' => $unitAttackMultiplier * (1 + $bladeTech),
					'defense' =>   1,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => TRUE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['weak']
				),
				3 => array(
					'type' => "standard",
					'name' => "Infantry",
					'attack' => $unitAttackMultiplier * (2 + $bladeTech),
					'defense' =>   2 + $lightArmorTech,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => TRUE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['standard']
				),
				4 => array(
					'type' => "range",
					'name' => "Rock Thrower",
					'attack' => $unitAttackMultiplier * 0,
					'defense' =>   1,
					'range' => $unitAttackMultiplier * 1 + ($rangeTech / 4),
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => TRUE,
					'number' =>   $sentUnits['Attack']['range']
				),
				5 => array(
					'type' => "mount",
					'name' => "Rider",
					'attack' => $unitAttackMultiplier * (5 + $bladeTech * .5),
					'defense' =>   7 + $heavyArmorTech,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => $unitAttackMultiplier * (2 + $bladeTech *.5),
					'isSpear' => TRUE,
					'isMount' => TRUE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['mount']
				)
			);
		}
		else if($user['User']['race'] == "Dwarf" || $user['User']['race'] == "all"){
			
			
			$unitsData = array(
				0 => array(
					'type' => "small_elite",
					'name' => "Runner",
					'attack' => $unitAttackMultiplier * (7 + $bladeTech * .5),
					'defense' =>   3 + $lightArmorTech,
					'range' => 0,
					'shortRange' => $unitAttackMultiplier * (8 + $bladeTech * .5),
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['small_elite']
				),
				1 => array(
					'type' => "big_elite",
					'name' => "Shield Bearer",
					'attack' => $unitAttackMultiplier * (3 + $bladeTech) * $shieldBearerStrat,
					'defense' =>   $shieldBearerDefense + $heavyArmorTech,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['big_elite']
				),
				2 => array(
				 	'type' => "weak",
					'name' => "Hammer Wielder",
					'attack' => $unitAttackMultiplier * (7 + $bladeTech),
					'defense' =>   7 + $heavyArmorTech,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['weak']
				),
				3 => array(
					'type' => "standard",
					'name' => "Axeman",
					'attack' => $unitAttackMultiplier * (9 + $bladeTech),
					'defense' =>   11 + $heavyArmorTech,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['standard']
				),
				4 => array(
					'type' => "range",
					'name' => "Light Crossbowman",
					'attack' => $unitAttackMultiplier * 0,
					'defense' =>   5 + $lightArmorTech,
					'range' => $unitAttackMultiplier * 2 + ($rangeTech / 4) * 2,
					'shortRange' => $unitAttackMultiplier * 1 + ($rangeTech / 4),
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => TRUE,
					'number' =>   $sentUnits['Attack']['range']
				),
				5 => array(
					'type' => "mount",
					'name' => "Berserker",
					'attack' => $unitAttackMultiplier * (11 + $bladeTech),
					'defense' =>   8 + $lightArmorTech,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['mount']
				)
			);
		}
		else if($user['User']['race'] == "Elf" || $user['User']['race'] == "all"){
			if ($totalEnemyUnits > 0 && ($sentUnits['Attack']['small_elite'] + $sentUnits['Attack']['big_elite'] + $sentUnits['Attack']['range'] + $sentUnits['Attack']['mount'] + $sentUnits['Attack']['standard'] + $sentUnits['Attack']['weak']) > 0){
				$courageousShortBonus = (($totalEnemyUnits / ($sentUnits['Attack']['small_elite'] + $sentUnits['Attack']['big_elite'] + $sentUnits['Attack']['range'] + $sentUnits['Attack']['mount'] + $sentUnits['Attack']['standard'] + $sentUnits['Attack']['weak']) - 1))*((9+$bladeTech)/2);
				if($courageousShortBonus < 1)
					$courageousShortBonus = 1;
				if($courageousShortBonus > 5)
					$courageousShortBonus = 5;
			}
			else{
				$courageousShortBonus = 5;
			}
			
			if($user['User']['Spell']['fiery_arrows']){
				$fieryArrows = 1 + $magicTech * .5;
				$fieryDefense = .25 * $magicTech;
			}
			else{
				$fieryArrows = 0;
				$fieryDefense = 0;
			}
			if($user['User']['Spell']['enchanted_weapons'])
				$enchantedWeapons = 1 + $magicTech * .5;
			else
				$enchantedWeapons = 0;
			if($user['User']['Spell']['hardened_armor'])
				$hardenedArmor = .8 + $magicTech * .3;
			else
				$hardenedArmor = 0;
				
			if($user['User']['Army']['strategy'] == "Energy Gathering")
				$energyGathering = (0.3+(mt_rand(0,170)*0.01));
			else
				$energyGathering = 1;
				
			if($defender){
				$archerDefenseBonus = (2*$user['User']['Building']['guard_tower'])/$user['User']['Resource']['land'];
				if($archerDefenseBonus > 2)
					$archerDefenseBonus = 2;
			}
			else
				$archerDefenseBonus = 0;
				
				
			$unitsData = array(
				0 => array(
					'type' => "small_elite",
					'name' => "Courageous",
					'attack' => $unitAttackMultiplier * (9 + $bladeTech + $enchantedWeapons),
					'defense' =>   7 + $heavyArmorTech + $hardenedArmor,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => $unitAttackMultiplier * ($courageousShortBonus + $enchantedWeapons),
					'isSpear' => TRUE,
					'isMount' => TRUE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['small_elite']
				),
				1 => array(
					'type' => "big_elite",
					'name' => "Mage",
					'attack' => $unitAttackMultiplier * (4 + $enchantedWeapons),
					'defense' =>   5 + $hardenedArmor + $heavyArmorTech,
					'range' => $unitAttackMultiplier * (7 + $magicTech) * $energyGathering,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['big_elite']
				),
				2 => array(
				 	'type' => "weak",
					'name' => "Lanceman",
					'attack' => $unitAttackMultiplier * (4 + $bladeTech + $enchantedWeapons),
					'defense' =>   2 + $lightArmorTech + $hardenedArmor,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => TRUE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['weak']
				),
				3 => array(
					'type' => "standard",
					'name' => "Swordman",
					'attack' => $unitAttackMultiplier * (2 + $bladeTech + $enchantedWeapons),
					'defense' =>   4 + $lightArmorTech + $hardenedArmor,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['standard']
				), 
				4 => array(
					'type' => "range",
					'name' => "Archer",
					'attack' => $unitAttackMultiplier * 0,
					'defense' =>   3 + $hardenedArmor + $fieryDefense + $archerDefenseBonus,
					'range' => $unitAttackMultiplier * (3+$fieryArrows) + ($rangeTech / 4) * 3,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => TRUE,
					'number' =>   $sentUnits['Attack']['range']
				),
				5 => array(
					'type' => "mount",
					'name' => "Elite Archer",
					'attack' => $unitAttackMultiplier * 0,
					'defense' =>   5 + $hardenedArmor + $fieryDefense + $archerDefenseBonus,
					'range' => $unitAttackMultiplier * (5+$fieryArrows) + ($rangeTech / 4) * 5,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => TRUE,
					'number' =>   $sentUnits['Attack']['mount']
				)
			);
		}
		else if($user['User']['race'] == "Human" || $user['User']['race'] == "all"){
			$totalHumanUnits = 0;
			$totalHumanUnits += $sentUnits['Attack']['small_elite'];
			$totalHumanUnits += $sentUnits['Attack']['big_elite'];
			$totalHumanUnits += $sentUnits['Attack']['range'];
			$totalHumanUnits += $sentUnits['Attack']['standard'];
			$totalHumanUnits += $sentUnits['Attack']['mount'];
			$totalHumanUnits += $sentUnits['Attack']['weak'];
			
			// This used to be human racial tech
			$extraClericDefense = 1;
			//if(($user['User']['Technology']['racial'] == 1) && $totalHumanUnits){
			if($totalHumanUnits > 0)
				$extraClericDefense += ($sentUnits['Attack']['big_elite'] / $totalHumanUnits) * .5;
			//}
			if($extraClericDefense > 1.25)
				$extraClericDefense = 1.25;
			
			if($user['User']['Army']['strategy'] == 'Charging')
				$knightCharging = 1.5;
			else
				$knightCharging = 1;
			
			if(($user['User']['Technology']['racial'] == 1) && $totalHumanUnits){
				$knightPercent = ($sentUnits['Attack']['big_elite'] / $totalHumanUnits);
				if($user['User']['Army']['strategy'] != "Quick Retreat"){
					$charisma = $knightPercent * mt_rand(45, 55);
					$unitAttackMultiplier = ($bas + $charisma) * .005; 
					//echo "Charisma is $charisma <br />";
					
				}
			}
			//echo "Unit Mulitplier is $unitAttackMultiplier <br />";
			
			
			
			$unitsData = array(
				0 => array(
					'type' => "small_elite",
					'name' => "Heavy Infantry",
					'attack' => $unitAttackMultiplier * (7 + $bladeTech),
					'defense' =>   (8 + $heavyArmorTech) * $extraClericDefense,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['small_elite']
				),
				1 => array(
					'type' => "big_elite",
					'name' => "Knight",
					'attack' => $unitAttackMultiplier * (8 + $bladeTech* .5) * $knightCharging,
					'defense' =>   (11 + $heavyArmorTech) * $extraClericDefense,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => (1 + $bladeTech * .5) * $unitAttackMultiplier,
					'isSpear' => FALSE,
					'isMount' => TRUE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['big_elite']
				),
				2 => array(
				 	'type' => "weak",
					'name' => "Pikeman",
					'attack' => $unitAttackMultiplier * (5 + $bladeTech),
					'defense' =>   (3 + $lightArmorTech) * $extraClericDefense,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => TRUE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['weak']
				),
				3 => array(
					'type' => "standard",
					'name' => "Infantry",
					'attack' => $unitAttackMultiplier * (3 + $bladeTech),
					'defense' =>   (4 + $lightArmorTech) * $extraClericDefense,
					'range' => $unitAttackMultiplier * 0,
					'shortRange' => 0,
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => TRUE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['standard']
				),
				4 => array(
					'type' => "range",
					'name' => "Archer",
					'attack' => $unitAttackMultiplier * 0,
					'defense' =>   (3 + $lightArmorTech) * $extraClericDefense,
					'range' => $unitAttackMultiplier * 2 + ($rangeTech / 4) * 2,
					'shortRange' => $unitAttackMultiplier * 1 + ($rangeTech / 4),
					'isSpear' => FALSE,
					'isMount' => FALSE,
					'isInfantry' => FALSE,
					'isArcher' => TRUE,
					'number' =>   $sentUnits['Attack']['range']
				),
				5 => array(
					'type' => "mount",
					'name' => "Mounted Archer",
					'attack' => $unitAttackMultiplier * (3 + $bladeTech),
					'defense' =>   (7 + $lightArmorTech) * $extraClericDefense,
					'range' => $unitAttackMultiplier * 3 + ($rangeTech / 4) * 3,
					'shortRange' => $unitAttackMultiplier * 1 + ($rangeTech / 4),
					'isSpear' => FALSE,
					'isMount' => TRUE,
					'isInfantry' => FALSE,
					'isArcher' => FALSE,
					'number' =>   $sentUnits['Attack']['mount']
				)
			);
		}
		else {
			echo "Error accessing database.";

		}
		$unitsData['strategy'] = $user['User']['Army']['strategy'];
		$unitsData['unitAttackMultiplier'] = $unitAttackMultiplier;
		$unitsData['bladeTech'] = $bladeTech;
		if($unitsData['strategy'] == 'Mystics'){
			$bonus = .25;
			
			$wizardTech =  $user['User']['Technology']['tree_4'] * $bonus;
			if($wizardTech > 1.5)
				$wizardTech = 1.5;
			if($debug){
				$unitsData['wizardPower'] = $unitAttackMultiplier * ($user['User']['Army']['wizard'] * (1 + $wizardTech) + $user['User']['Army']['wizard_resting'] * (1 + $wizardTech) / 2);
			} else {
				$unitsData['wizardPower'] = $unitAttackMultiplier * ($user['User']['Army']['wizard'] * (1 + $wizardTech) + $user['User']['Army']['wizard_resting'] * (1 + $wizardTech) / 2);
			}
		} else {
			$unitsData['wizardPower'] = 0;
		}
		
		
		
		
		return $unitsData;
	}
	
	function getAttackCoefficients($strategy, $units, $debug = false) {
		//echo "Strategy: $strategy <br />";
		$totalUnits = $units[0]['number']+
				$units[1]['number'] +
				$units[2]['number'] +
				$units[3]['number'] +
				$units[4]['number'] +
				$units[5]['number'];
		if (!isset($strategy))
			$strategy = "None";
		$attackCoefficients = array(
			'infantryReducer'=> 1,
			'archerSubtraction'=> 0,
			'recklessAbandon'=> 1,
			'attackReducer' => 1,
			'chanceReducer' => 0,
			'meleeReducer' => 1, 
			'pikeMountAttack' => .5,
			'allMeleeReducer' => 1,
			'enemyRangeReducer' => 1,
			'shieldbearerMeleeDamage' => 1,
			'dwarfShieldProtection' => 0, 
			'mageDefense' => 0, 
			'mageRangeAttack' => 1, 
			'mageMeleeDamage' => True,
			'rangeAttackMultiplier' => 1,
			'knightAttackDamageMultiplier' => 1,
			'shadowWarriorDefense' => 0,
			'shadowWarriorRangeDamage' => 1,
			'shadowWarriorMeleeDeath' => 1,
			'shadowWarriorShortRangeDamage' => 1,
			'hordeRange' => 1,
			'hordeShortRange' => 1,
			'hordeMelee' => 1
		);
		
		switch($strategy){
			case "None":
				break;
			case "Archer Protection":
				// multiply the infantries attack by the infantry reducer in the attack function
				$attackCoefficients['infantryReducer'] = .5;
				$attackCoefficients['archerSubtraction'] = 0;
				// reduce damage done to infantry by infantry's normal damage
				// adding up all the infantries damage
				
				break;
			case "Reckless Abandon":
				// multiply damage done to infantry
				$attackCoefficients['recklessAbandon'] = 1.5;
				//subtract damage to infantry from other units
				//this must be done in the battle sequence
				break;
			case "Quick Retreat":
				// reduce both armies attack by multiplying by the reducer
				$attackCoefficients['attackReducer'] = .5;
				// "chance reducer" subtracts a percentage from your armies kills, and reduces your comparative
				//chance of victory
				$attackCoefficients['chanceReducer'] = 10;
				break;
			case "Anti-Cavalry":
				$attackCoefficients['allMeleeReducer'] = .9;
				$attackCoefficients['pikeMountAttack'] = 2.5;
				break;
			case "Shield Line":
				$attackCoefficients['meleeReducer'] = .9;
				if($totalUnits < 1) 
					$totalUnits = 1;
				$attackCoefficients['enemyRangeReducer'] = 1 - (2*$units[1]['number']/$totalUnits);
				if ($attackCoefficients['enemyRangeReducer'] < .35)
					$attackCoefficients['enemyRangeReducer'] = .35;
				$attackCoefficients['shieldbearerMeleeDamage'] = 2;
				break;
			case "Energy Gathering":
				$attackCoefficients['mageDefense'] = 0;
				$attackCoefficients['mageRangeAttack'] = 1;
				$attackCoefficients['mageMeleeDamage'] = True;
				break;
			case "Far Fighting":
				$attackCoefficients['rangeAttackMultiplier'] = 2;
				break;
			case "Charging":
				$attackCoefficients['knightAttackDamageMultiplier'] = 1.5;
				break;
			case "Surrounding":
				$attackCoefficients['shadowWarriorDefense'] = 2;
				$attackCoefficients['shadowWarriorRangeDamage'] = 0;
				$attackCoefficients['shadowWarriorShortRangeDamage'] = 1.25;
				$attackCoefficients['shadowWarriorMeleeDeath'] = 1;
				break;
			case "Horde":
				$attackCoefficients['hordeRange'] = .75;
				$attackCoefficients['hordeShortRange'] = 1.1;
				$attackCoefficients['hordeMelee'] = 1.1;
				break;
		}
		return $attackCoefficients;
	}
	
	/////////Now for the battle sequence functions ///////////////
	// Range
	function rangeAttack($tech = null, $userUnitsData = null, $victimUnitsData = null, $userRace = null, $victimRace = null, $userAttackCoefficients = null, $victimAttackCoefficients = null, $baseAttack = null, $dummyUnits = null, $victimGuardTowers = null, $debug = false, $wizards = null){
		
		$attackMultiplier =  ($userAttackCoefficients['recklessAbandon']*$victimAttackCoefficients['recklessAbandon']*$userAttackCoefficients['attackReducer']*$victimAttackCoefficients['attackReducer']*$userAttackCoefficients['rangeAttackMultiplier']*$victimAttackCoefficients['rangeAttackMultiplier']);
		//echo "Attack multiplier: $attackMultiplier <br />";
		
		// calc ranged offensive power and total number of victim troops
		$rangePower = 0;
		$totalVictimTroops = 0;
		$totalVictimDefense = 0;
		$totalUserTroops = 0;
		for ($i=0; $i<6; $i++){
			if (($userRace != "Elf") || ($i != 1))
				$rangePower += $tech*$attackMultiplier*$victimAttackCoefficients['enemyRangeReducer']*($userUnitsData[$i]['number']*$userUnitsData[$i]['range']);
			if (($userRace == "Elf") && ($i == 1))
				$rangePower += $tech*$attackMultiplier*$victimAttackCoefficients['enemyRangeReducer']*($userUnitsData[$i]['number']*$userUnitsData[$i]['range'])*$userAttackCoefficients['mageRangeAttack'];
				
			//echo "Range power: $rangePower<br />";
			$victimUnitsData[$i]['previous'] = $victimUnitsData[$i]['number'];
			$userUnitsData[$i]['previous'] = $userUnitsData[$i]['number'];
			$totalVictimTroops += $victimUnitsData[$i]['number'];
			$totalVictimDefense += $victimUnitsData[$i]['defense']*$victimUnitsData[$i]['number'];
			$totalUserTroops += $userUnitsData[$i]['number'];
		}
		if($userRace == "Elf"){
			$userEliteRangePower = $tech*$attackMultiplier*$victimAttackCoefficients['enemyRangeReducer']*($userUnitsData[5]['number']*$userUnitsData[5]['range']);
		} else {
			$userEliteRangePower = 0;
		}
		
		if($debug)
			echo "<br />Guard tower defense: " . ($victimGuardTowers * 20) . "<br />";
		
		$totalVictimTroops += $dummyUnits;
		
		
		if($totalUserTroops < $totalVictimTroops && $victimUnitsData['strategy'] = "Horde") {
			$victimAttackCoefficients['hordeRange'] = .5;
		}
		$rangePower *= $victimAttackCoefficients['hordeRange'];
		$userEliteRangePower *= $victimAttackCoefficients['hordeRange'];
		
		/*
		if($victimUnitsData['knightCharisma']){ //Victim Charisma is OP still
			if($victimUnitsData['knightCharisma'] >  $totalVictimTroops / $totalUserTroops){
				$victimUnitsData['knightCharisma'] =  $totalVictimTroops / $totalUserTroops;
			}
			$subtractedRangePower = $rangePower * $victimUnitsData['knightCharisma'];
			$subtractedEliteRangePower = $userEliteRangePower * $victimUnitsData['knightCharisma'];
			$userEliteRangePower -= $subtractedEliteRangePower;
			$rangePower -= $subtractedRangePower;
		}*/
		
		if($debug) {
			echo "<br /> Total victim troops: " . $totalVictimTroops;
			echo "<br /> Total victim defense: " . $totalVictimDefense;
			echo "<br /> User Ranged offensive Power: ". $rangePower;
		}
		
		$blockedDamage = ($victimGuardTowers * 20);
		$blockedDamage -= $rangePower;
		$rangePower -= ($victimGuardTowers * 20);
		if($blockedDamage > 0){
			$userEliteRangePower -= $blockedDamage;
		}
		
		
		 
		if($rangePower < 0)
			$rangePower = 0;
		if($debug) {
			echo "<br />Ranged offensive power after GT: " . $rangePower;
		}

		
		///// Now reverse attacker and defender /////
		
		// calc ranged offensive power and total number of victim troops
		$victimRangePower = 0;
		
		$totalUserDefense = 0;
		for ($i=0; $i<6; $i++){
			$victimRangePower += $attackMultiplier*$userAttackCoefficients['enemyRangeReducer']*($victimUnitsData[$i]['number']*$victimUnitsData[$i]['range']);
			
			$totalUserDefense += $userUnitsData[$i]['defense']*$userUnitsData[$i]['number'];
		}
		if($victimRace == "Elf"){
			$victimRangePower += $attackMultiplier*$victimAttackCoefficients['enemyRangeReducer']*$victimUnitsData['wizardPower'];
			$victimEliteRangePower = $tech*$attackMultiplier*$victimAttackCoefficients['enemyRangeReducer']*($victimUnitsData[5]['number']*$victimUnitsData[5]['range']);
		}
		if($totalVictimTroops < $totalUserTroops && $userUnitsData['strategy'] = "Horde") {
			$userAttackCoefficients['hordeRange'] = .5;
		}
		
		$victimRangePower *= $userAttackCoefficients['hordeRange'];
		if($victimRace == "Elf"){
			$victimEliteRangePower *= $userAttackCoefficients['hordeRange'];
		}
		/*
		if($userUnitsData['knightCharisma']){
			if($userUnitsData['knightCharisma'] > $totalUserTroops / $totalVictimTroops){
				$userUnitsData['knightCharisma'] = $totalUserTroops / $totalVictimTroops;
			}
			$subtractedVictimRangePower = $victimRangePower * $userUnitsData['knightCharisma'];
			if($victimRace == "Elf"){
				$subtractedVictimEliteRangePower = $victimEliteRangePower * $userUnitsData['knightCharisma'];
				$victimEliteRangePower -= $subtractedVictimEliteRangePower;
			}
			$victimRangePower -= $subtractedVictimRangePower;
		}*/
		
		if($debug)
			echo "<br>Victim Range Power: " . $victimRangePower;
		$victimRangePower += $baseAttack;
		if($debug){
			echo "<br>Victim Range Power (with base attack): " . $victimRangePower;
			echo "<br> Total user troops: " . $totalUserTroops;
			echo "<br> Total user defense: " . $totalUserDefense;
			echo "<br> Victim Ranged offensive Power: ". $victimRangePower . "<br />";
		}
		/*
		if($userUnitsData['knightCharisma']){
			
			if($victimRace == "Elf")
				$userEliteRangePower += $subtractedVictimEliteRangePower;
			$rangePower += $subtractedVictimRangePower;
		}
		if($victimUnitsData['knightCharisma']){
			
			if($victimRace == "Elf")
				$victimEliteRangePower += $subtractedEliteRangePower;
			$victimRangePower += $subtractedRangePower;
		}*/
		if($debug) {
			echo "<br /> User Ranged offensive Power After Knights: ". $rangePower;
			echo "<br>Victim Range Power After Knights: " . $victimRangePower;
		}
		

		//dish out the damage XD
		//echo "<br>You attack:";
		for ($i=0; $i<6; $i++){
			if($debug)
				echo $victimUnitsData[$i]['name'] . " before: " . $victimUnitsData[$i]['number'] . "<br />";
			if ($totalVictimTroops > 0) {
				if($victimUnitsData[$i]['name'] != 'Heavy Infantry')
					$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$rangePower/$victimUnitsData[$i]['defense']);
				else
					$victimUnitsData[$i]['number'] -= round(.9*($victimUnitsData[$i]['number']/$totalVictimTroops)*$rangePower/$victimUnitsData[$i]['defense']);
				if($victimUnitsData[$i]['name'] == "Knight" && $victimUnitsData['strategy'] == "Charging"){
					$victimUnitsData[$i]['number'] += round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$rangePower*.5/$victimUnitsData[$i]['defense']);
				}
				if($victimUnitsData[$i]['name'] == "Shadow Warrior" && $victimUnitsData['strategy'] == 'Surrounding')
				{
					$damage = mt_rand(40,60) / 100;
					$victimUnitsData[$i]['number'] += round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$rangePower*$damage/$victimUnitsData[$i]['defense']);
				} 
				if($victimUnitsData[$i]['isArcher']&& isset($userEliteRangePower)){
					$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*($userEliteRangePower*.5)/$victimUnitsData[$i]['defense']);
				}
				if($victimUnitsData[$i]['number'] > $victimUnitsData[$i]['previous'])
					$victimUnitsData[$i]['number'] = $victimUnitsData[$i]['previous'];
					
			}
			if ($victimUnitsData[$i]['number']<0) {
				$victimUnitsData[$i]['number'] = 0;
			}
			if($debug)
				echo $victimUnitsData[$i]['name'] . " after: " .  $victimUnitsData[$i]['number'] . " <br />";
		}
		$deadDummyUnits = 0;
		if($totalVictimTroops != 0) {
			$deadDummyUnits += round(($dummyUnits/$totalVictimTroops)*$rangePower*2);
		}
		else {
			$deadDummyUnits += round(($dummyUnits/1)*$rangePower*2);
		}
		if ($deadDummyUnits > $dummyUnits)
			$deadDummyUnits= $dummyUnits;
		$totalVictimTroops -= $deadDummyUnits;
		if($totalVictimTroops < 0){
			$totalVictimTroops = 0;
		}
		if($debug)
			echo "<br><br>Enemy attacks: <br />";
		//dish out the damage XD
		for ($i=0; $i<6; $i++){
			if($debug)
				echo $userUnitsData[$i]['name'] . " before: " . $userUnitsData[$i]['number'] . "<br />";
			if ($totalUserTroops > 0) {
				if($userUnitsData[$i]['name'] != 'Heavy Infantry')
					$userUnitsData[$i]['number'] -= round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimRangePower/$userUnitsData[$i]['defense']);
				else
					$userUnitsData[$i]['number'] -= round(.9*($userUnitsData[$i]['previous']/$totalUserTroops)*$victimRangePower/$userUnitsData[$i]['defense']);
				if($userUnitsData[$i]['name'] == "Knight" && $userUnitsData['strategy'] == "Charging"){
					$userUnitsData[$i]['number'] += round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimRangePower*.5/$userUnitsData[$i]['defense']);
				}
				if($userUnitsData[$i]['name'] == "Shadow Warrior" && $userUnitsData['strategy'] == 'Surrounding')
				{
					$damage = mt_rand(40,60) / 100;
					$userUnitsData[$i]['number'] += round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimRangePower*$damage/$userUnitsData[$i]['defense']);
				} 
				if($userUnitsData[$i]['isArcher']&& isset($victimEliteRangePower)){
					$userUnitsData[$i]['number'] -= round(($userUnitsData[$i]['previous']/$totalUserTroops)*($victimEliteRangePower*.5)/$userUnitsData[$i]['defense']);
				}
				if($userUnitsData[$i]['number'] > $userUnitsData[$i]['previous'])
					$userUnitsData[$i]['number'] = $userUnitsData[$i]['previous'];
					
			}
			if ($userUnitsData[$i]['number']<0) {
				$userUnitsData[$i]['number'] = 0;
			}
			if($debug)
				echo $userUnitsData[$i]['name'] . " after: " .  $userUnitsData[$i]['number'] . " <br />";
			
		}
		if($userRace == "Dwarf" && $userUnitsData[5]['previous'])
			$userUnitsData[5]['shortRange'] += ((11 + $userUnitsData['bladeTech']) * (($userUnitsData[5]['previous'] - $userUnitsData[5]['number']) / $userUnitsData[5]['previous'])) * $userUnitsData['unitAttackMultiplier'];
		if($victimRace == "Dwarf" && $victimUnitsData[5]['previous'])
			$victimUnitsData[5]['shortRange'] += ((11 + $victimUnitsData['bladeTech']) * ($victimUnitsData[5]['previous'] - $victimUnitsData[5]['number']) / $victimUnitsData[5]['previous']) * $victimUnitsData['unitAttackMultiplier'];
		
		$result = array(
		0 => $victimUnitsData,
		1 => $userUnitsData
		);
		//echo "<br>End of Range portion. <br>";
		return $result;
		
	}// end range
	
	//Short Range Attack
	function shortRangeAttack($tech = null, $userUnitsData = null, $victimUnitsData = null, $userRace = null, $victimRace = null, $userAttackCoefficients = null, $victimAttackCoefficients = null, $debug = false){
		
		$attackMultiplier =  $userAttackCoefficients['recklessAbandon']*$victimAttackCoefficients['recklessAbandon']*$userAttackCoefficients['attackReducer']*$victimAttackCoefficients['attackReducer'];
		// calc ranged offensive power and total number of victim troops
		$totalVictimTroops = 0;
		$totalVictimDefense = 0;
		$victimShortRangePower = 0;
		$victimOrcInfantryPower = 0;
		for ($i=0; $i<6; $i++){
			$victimShortRangePower += $attackMultiplier*$victimUnitsData[$i]['number']*$victimUnitsData[$i]['shortRange'];
			if($victimUnitsData[$i]['name'] == "Slother" || $victimUnitsData[$i]['name'] == "Rusher" || $victimUnitsData[$i]['name'] == "Wolf Master") 
				$victimOrcInfantryPower += .5*$tech*$attackMultiplier*$victimUnitsData[$i]['number']*$victimUnitsData[$i]['shortRange'];
			$totalVictimTroops += $victimUnitsData[$i]['number'];
			$totalVictimDefense += $victimUnitsData[$i]['defense']*$victimUnitsData[$i]['number'];
			$victimUnitsData[$i]['previous'] = $victimUnitsData[$i]['number'];
		}
		$victimShortRangePower *= $userAttackCoefficients['hordeShortRange'];
		if($debug){
			echo "<br> Total victim troops: " . $totalVictimTroops;
			echo "<br> Total victim defense: " . $totalVictimDefense;
			echo "<br> Victim short ranged offensive Power: ". $victimShortRangePower;
		}
		

		
		///// Now reverse attacker and defender /////<
		
		// calc ranged offensive power and total number of victim troops
		$totalUserTroops = 0;
		$totalUserDefense = 0;
		$shortRangePower = 0;
		$userOrcInfantryPower = 0;
		for ($i=0; $i<6; $i++){
			$shortRangePower += $tech*$attackMultiplier*$userUnitsData[$i]['number']*$userUnitsData[$i]['shortRange'];
			if($userUnitsData[$i]['name'] == "Slother" || $userUnitsData[$i]['name'] == "Rusher" || $userUnitsData[$i]['name'] == "Wolf Master") 
				$userOrcInfantryPower += .5*$tech*$attackMultiplier*$userUnitsData[$i]['number']*$userUnitsData[$i]['shortRange'];
			$totalUserTroops += $userUnitsData[$i]['number'];
			$totalUserDefense += $userUnitsData[$i]['defense']*$userUnitsData[$i]['number'];
			$userUnitsData[$i]['previous'] = $userUnitsData[$i]['number'];
		}
		$shortRangePower *= $victimAttackCoefficients['hordeShortRange'];
		if($debug){
			echo "<br> Total user troops: " . $totalUserTroops;
		 	echo "<br> Total user defense: " . $totalUserDefense;
			echo "<br> User short ranged offensive Power: ". $shortRangePower . "<br />";
		}
		/*
		if($victimUnitsData['knightCharisma']){
		
			$subtractedShortRangePower = $shortRangePower * $victimUnitsData['knightCharisma'];
			$shortRangePower -= $subtractedShortRangePower;
			$victimShortRangePower += $subtractedShortRangePower;
		}
		if($userUnitsData['knightCharisma']){
			
			$subtractedVictimShortRangePower = $victimShortRangePower * $userUnitsData['knightCharisma'];
			$victimShortRangePower -= $subtractedVictimShortRangePower;
		
			$shortRangePower += $subtractedVictimShortRangePower;
		}*/
		if($debug) {
			echo "<br /> User Ranged offensive Power After Knights: ". $shortRangePower;
			echo "<br>Victim Range Power After Knights: " . $victimShortRangePower;
		} 
		
		
		//dish out the damage XD
		//echo "<br>You attack:";
		for ($i=0; $i<6; $i++){
			if($debug)
				echo "Number of " .$victimUnitsData[$i]['name'] ." before: ". $victimUnitsData[$i]['number'] ."<br />"; 
			if ($totalVictimTroops > 0) {
				$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['number']/$totalVictimTroops)*$shortRangePower/$victimUnitsData[$i]['defense']);
				if($victimUnitsData[$i]['isInfantry'])
					$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$userOrcInfantryPower/$victimUnitsData[$i]['defense']);
				if($victimUnitsData[$i]['name'] == "Knight" && $victimUnitsData['strategy'] == "Charging"){
					$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$shortRangePower*.5/$victimUnitsData[$i]['defense']);
				}
				if($victimUnitsData[$i]['name'] == "Shadow Warrior"){
					//if ($victimUnitsData[$i]['number']<0)
					//	$victimUnitsData[$i]['number'] = 0;
					if($debug){
						echo "Victim Cloaking power: " . $victimUnitsData['cloaking'] . "<br />";
						echo "% of SW in army: " . ($victimUnitsData[$i]['number']/$totalVictimTroops) . "<br />";
					}
					$shadowWarriorHiding = .7 + $victimUnitsData['cloaking'] - $victimUnitsData[$i]['previous']/$totalVictimTroops;
					if($victimUnitsData['strategy'] == 'Surrounding'){
						$shadowWarriorHiding -= .35;
						if($debug)
							echo "Surrounding active for the defender. <br />";
					}
					if($shadowWarriorHiding < 0)
						$shadowWarriorHiding = 0;
					if($debug)
						echo "Shadow warriors hiding: " . $shadowWarriorHiding * 100 . "%<br />"; 
					$victimUnitsData[$i]['number'] += round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$shortRangePower*$shadowWarriorHiding/$victimUnitsData[$i]['defense']);
				}
				if($victimUnitsData[$i]['number'] > $victimUnitsData[$i]['previous']){
					$victimUnitsData[$i]['number'] = $victimUnitsData[$i]['previous'];
				}
				if ($victimUnitsData[$i]['number']<0)
					$victimUnitsData[$i]['number'] = 0;
			}
			if($debug)
				echo "Number of " .$victimUnitsData[$i]['name'] ." after: ". $victimUnitsData[$i]['number'] . "<br />";
		}
		
		if($debug)
			echo "<br><br>Enemy attacks: ";
		//dish out the damage XD
		for ($i=0; $i<6; $i++){
			if($debug)
				echo "<br>Number of " .$userUnitsData[$i]['name'] ." before: ". $userUnitsData[$i]['number']; 
			if ($totalUserTroops > 0){
				$userUnitsData[$i]['number'] -= round(($userUnitsData[$i]['number']/$totalUserTroops)*$victimShortRangePower/$userUnitsData[$i]['defense']);
				if($userUnitsData[$i]['isInfantry'])
					$userUnitsData[$i]['number'] -= round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimOrcInfantryPower/$userUnitsData[$i]['defense']);
				if($userUnitsData[$i]['name'] == "Knight" && $userUnitsData['strategy'] == "Charging"){
					$userUnitsData[$i]['number'] -= round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimShortRangePower*.5/$userUnitsData[$i]['defense']);
				}
				if($userUnitsData[$i]['name'] == "Shadow Warrior"){
					$shadowWarriorHiding = .7 + $userUnitsData['cloaking'] - $userUnitsData[$i]['previous']/$totalUserTroops;
					if($userUnitsData['strategy'] == 'Surrounding'){
						$shadowWarriorHiding -= .35;
						if($debug)
							echo "Surrounding active for the attacker. <br />";
					}
					if($shadowWarriorHiding < 0)
						$shadowWarriorHiding = 0;
					if($debug)
						echo "Shadow warriors hiding: " . $shadowWarriorHiding * 100 . "%<br />"; 
					$userUnitsData[$i]['number'] += round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimShortRangePower*$shadowWarriorHiding/$userUnitsData[$i]['defense']);
				}
				if($userUnitsData[$i]['number'] > $userUnitsData[$i]['previous'])
					$userUnitsData[$i]['number'] = $userUnitsData[$i]['previous'];
				if ($userUnitsData[$i]['number']<0)
					$userUnitsData[$i]['number'] = 0;
			}
			if($debug)
				echo "<br>Number of " .$userUnitsData[$i]['name'] ." after: ". $userUnitsData[$i]['number'];
		}
		$result = array(
		0 => $victimUnitsData,
		1 => $userUnitsData
		);
		
		if($debug)
			echo "<br>End of short range portion. <br>";

		return $result;
	}// end short range
	
	// Melee Attack
	function meleeAttack($tech = null, $userUnitsData = null, $victimUnitsData = null, $userRace = null, $victimRace = null, $userAttackCoefficients = null, $victimAttackCoefficients = null, $baseAttack = null, $dummyUnits = null, $debug = false, $wolves){

		$attackMultiplier =  $userAttackCoefficients['recklessAbandon']*$victimAttackCoefficients['recklessAbandon']*$userAttackCoefficients['attackReducer']*$victimAttackCoefficients['attackReducer']*$userAttackCoefficients['allMeleeReducer']*$victimAttackCoefficients['allMeleeReducer'];
		
		$meleePower = 0;
		$totalVictimTroops = 0;
		$totalVictimDefense = 0;
		$userOrcInfantryPower = 0;
		$userPikePower = 0;
		for ($i=0; $i<6; $i++){
			$meleePower += $tech*$attackMultiplier*$userUnitsData[$i]['number']*$userUnitsData[$i]['attack'];
			if ($userUnitsData[$i]['isInfantry'] == TRUE)
				$meleePower -= $tech*(1 - $userAttackCoefficients['infantryReducer'])*$attackMultiplier*$userUnitsData[$i]['number']*$userUnitsData[$i]['attack'];
			if (($userUnitsData[$i]['name'] == 'Knight') && ($userUnitsData['strategy'] == 'Charging'))
				$meleePower += $tech*.5*$attackMultiplier*$userUnitsData[$i]['number']*$userUnitsData[$i]['attack'];
			if($userUnitsData[$i]['name'] == "Slother" || $userUnitsData[$i]['name'] == "Rusher" || $userUnitsData[$i]['name'] == "Wolf Master") 
				$userOrcInfantryPower += .5*$tech*$attackMultiplier*$userUnitsData[$i]['number']*$userUnitsData[$i]['shortRange'];
			if($userUnitsData[$i]['isSpear'])
				$userPikePower += $tech*$attackMultiplier*$userUnitsData[$i]['number']*$userUnitsData[$i]['attack'];
			$totalVictimTroops += $victimUnitsData[$i]['number'];
			$totalVictimDefense += $victimUnitsData[$i]['defense']*$victimUnitsData[$i]['number'];
		}
		$totalVictimTroops += $dummyUnits;
		$deadDummyUnits = 0;
		if($dummyUnits > 0)
			$deadDummyUnits += round(($dummyUnits/$totalVictimTroops)*$meleePower*1.1);
		if ($deadDummyUnits > $dummyUnits)
			$deadDummyUnits = $dummyUnits;
		$totalVictimTroops -= $deadDummyUnits;
		$meleePower *= $victimAttackCoefficients['hordeMelee'];
		if($debug){
			echo "<br> Total victim troops: " . $totalVictimTroops;
			echo "<br> Total victim defense: " . $totalVictimDefense;
			echo "<br> User Melee offensive Power: ". $meleePower;
		}
		
		///// Now reverse attacker and defender /////<
		
		// calc melee offensive power and total number of victim troops
		$victimMeleePower = 0;
		$totalUserTroops = 0;
		$totalUserDefense = 0;
		$victimOrcInfantryPower = 0;
		$victimPikePower = 0;
		for ($i=0; $i<6; $i++){
			$victimMeleePower += $attackMultiplier*$victimUnitsData[$i]['number']*$victimUnitsData[$i]['attack'];
			if ($victimUnitsData[$i]['isInfantry'] == TRUE)
				$victimMeleePower -= (1 - $victimAttackCoefficients['infantryReducer'])*$attackMultiplier*$victimUnitsData[$i]['number']*$victimUnitsData[$i]['attack'];
			if (($victimUnitsData[$i]['name'] == 'Knight') && ($victimUnitsData['strategy'] == 'Charging'))
				$victimMeleePower += .5*$attackMultiplier*$victimUnitsData[$i]['number']*$victimUnitsData[$i]['attack'];
			if($victimUnitsData[$i]['name'] == "Slother" || $victimUnitsData[$i]['name'] == "Rusher" || $victimUnitsData[$i]['name'] == "Wolf Master") 
				$victimOrcInfantryPower += .5*$attackMultiplier*$victimUnitsData[$i]['number']*$victimUnitsData[$i]['shortRange'];
			if($victimUnitsData[$i]['isSpear'])
				$victimPikePower += $attackMultiplier*$victimUnitsData[$i]['number']*$victimUnitsData[$i]['attack'];
			$totalUserTroops += $userUnitsData[$i]['number'];
			$totalUserDefense += $userUnitsData[$i]['defense']*$userUnitsData[$i]['number'];
			$userUnitsData[$i]['previous'] = $userUnitsData[$i]['number'];
			$victimUnitsData[$i]['previous'] = $victimUnitsData[$i]['number'];
		}
		$victimMeleePower *= $userAttackCoefficients['hordeMelee'];
		if($debug) {
			echo "<br> Total user troops: " . $totalUserTroops;
			echo "<br> Total user defense: " . $totalUserDefense;
			echo "<br> Victim Melee offensive Power: ". $victimMeleePower;
		}
		/*
		if($victimUnitsData['knightCharisma']){
			
			$subtractedMeleePower = $meleePower * $victimUnitsData['knightCharisma'];
			$meleePower -= $subtractedMeleePower;
			$victimMeleePower += $subtractedMeleePower;
		}
		if($userUnitsData['knightCharisma']){
			
			$subtractedVictimMeleePower = $victimMeleePower * $userUnitsData['knightCharisma'];
			$victimMeleePower -= $subtractedVictimMeleePower;
		
			$meleePower += $subtractedVictimMeleePower;
		}*/
		if($debug) {
			echo "<br /> User Melee  Power After Knights: ". $meleePower;
			echo "<br>Victim Melee Power After Knights: " . $victimMeleePower;
		} 
		
		if(isset($victimUnitsData['wolfAttack'])){
			$victimMeleePower += $wolves['defender'] * $victimUnitsData['wolfAttack'];
			if($debug){
				echo "<br /> Victim Wolf Power: " . ($wolves['defender'] * $victimUnitsData['wolfAttack']);
			}
		}
		if(isset($userUnitsData['wolfAttack'])){
			$meleePower += $wolves['attacker'] * $userUnitsData['wolfAttack'];
			if($debug){
				echo "<br /> Attacker Wolf Power: " . ($wolves['attacker'] * $userUnitsData['wolfAttack']) . "<br />";
			}
		}
		
		
		
		
		//dish out the damage XD
		//echo "<br>You attack:";
		$numArchers = 0;
		if ($userUnitsData['strategy'] == 'Archer Protection'){
			$numArchers = 0;
			$infantryAttack = 0;
			for ($i=0; $i<6; $i++){
				if ($userUnitsData[$i]['isInfantry'] == TRUE)
					$infantryAttack += $tech*$attackMultiplier*$userUnitsData[$i]['number']*$userUnitsData[$i]['attack'];
				if ($userUnitsData[$i]['isArcher'] == TRUE) {
					$numArchers += $userUnitsData[$i]['number'];
				}
			}
		}
		$damageToShieldBearers = 0;
		
		$mageProtection = mt_rand(25,75) / 100;
		
		
		for ($i=0; $i<6; $i++){
			if($debug)
				echo $victimUnitsData[$i]['name'] . " before: " . $victimUnitsData[$i]['number'] . "<br />";
			if($totalVictimTroops == 0)
					$totalVictimTroops = 1;
			if ($victimUnitsData[$i]['name'] == 'Shield Bearer')
				$damageToShieldBearers += round(($victimUnitsData[$i]['number']/$totalVictimTroops)*$meleePower/$victimUnitsData[$i]['defense']);
			if ((($victimRace == "Elf") && ($i == 1)) || (($victimRace == "Orc") && ($i == 1))){
				//No Melee damage to Mages or Shadow Warriors	
				if ($victimRace == "Orc")
					$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*($meleePower/$victimUnitsData[$i]['defense']))*$victimAttackCoefficients['shadowWarriorMeleeDeath'];
				if($victimUnitsData[$i]['name'] == "Shadow Warrior"){
					$shadowWarriorHiding = .7 + $victimUnitsData['cloaking'] - $victimUnitsData[$i]['previous']/$totalVictimTroops;
					if($victimUnitsData['strategy'] == 'Surrounding'){
						$shadowWarriorHiding -= .35;
						if($debug)
							echo "Surrounding active for the defender. <br />";
					}
					if($shadowWarriorHiding < .25)
						$shadowWarriorHiding = .25;
					if($debug)
						echo "Shadow warriors hiding: " . $shadowWarriorHiding * 100 . "%<br />"; 
					$victimUnitsData[$i]['number'] += round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$meleePower*$shadowWarriorHiding/$victimUnitsData[$i]['defense']);
				}
				if ($victimRace == "Elf"){ 
					$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*($meleePower*$mageProtection/$victimUnitsData[$i]['defense']));
					//echo "Mage defense: " . $victimUnitsData[$i]['defense'] . "<br />";
					//echo "Extra Mage defense: " . $victimAttackCoefficients['mageDefense']. "<br />";
				}
				if($userUnitsData[$i]['number'] > $userUnitsData[$i]['previous'])
					$userUnitsData[$i]['number'] = $userUnitsData[$i]['previous'];
				if ($victimUnitsData[$i]['number']<0)
					$victimUnitsData[$i]['number'] = 0;
			}
			else if ($totalVictimTroops > 0){
				$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$meleePower/$victimUnitsData[$i]['defense']);
				if($victimUnitsData[$i]['isInfantry'])
					$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$userOrcInfantryPower/$victimUnitsData[$i]['defense']);
				if($victimUnitsData[$i]['isMount']){
					$victimUnitsData[$i]['number'] -= round($userAttackCoefficients['pikeMountAttack'] * ($victimUnitsData[$i]['previous']/$totalVictimTroops)*$userPikePower/$victimUnitsData[$i]['defense']);
					if($debug){
						echo "Pike attack: " . $victimAttackCoefficients['pikeMountAttack'] . "<br />";
						echo "Pike damage to " . $victimUnitsData[$i]['name'] . ": " . round($victimAttackCoefficients['pikeMountAttack'] * ($victimUnitsData[$i]['previous']/$totalVictimTroops)*$userPikePower/$victimUnitsData[$i]['defense']) . "<br />";
					}
				}
				if ($userUnitsData[$i]['number']<0) {
					$userUnitsData[$i]['number'] = 0;
				}
				if (($victimUnitsData[$i]['name'] == 'Knight') && ($victimUnitsData['strategy'] == 'Charging') && ($totalVictimTroops > 0)) {
					if($debug){
						echo "Knights on Charging: " . $victimUnitsData[$i]['number'] . "<br />";
					}
					$victimUnitsData[$i]['number'] -= round(.5*($victimUnitsData[$i]['previous']/$totalVictimTroops)*$meleePower/$victimUnitsData[$i]['defense']);
					if($debug){
						echo "Knights on Charging: " . $victimUnitsData[$i]['number'] . "<br />";
					}
				}
				if (($victimUnitsData[$i]['name'] == 'Mounted Archer') && ($totalVictimTroops > 0)) {
					$victimUnitsData[$i]['number'] += round(.1*($victimUnitsData[$i]['previous']/$totalVictimTroops)*$meleePower/$victimUnitsData[$i]['defense']);
				}
				if($userUnitsData[$i]['number'] > $userUnitsData[$i]['previous'])
					$userUnitsData[$i]['number'] = $userUnitsData[$i]['previous'];
				if ($victimUnitsData[$i]['number']<0) {
					$victimUnitsData[$i]['number'] = 0;
				}
			}
			if($debug)
				echo $victimUnitsData[$i]['name'] . " after: " . $victimUnitsData[$i]['number'] . "<br />";
		}
		if ($victimUnitsData['strategy'] == 'Shield Line'){
			
			for ($i=0; $i<6; $i++){
				if ($victimUnitsData[$i]['name'] != 'Shield Bearer') {
					if (($totalVictimTroops - $victimUnitsData[1]['number']) > 0){
						$victimUnitsData[$i]['number'] += round(($victimUnitsData[$i]['previous']/($totalVictimTroops - $victimUnitsData[1]['number']))*$damageToShieldBearers);
					}
				}
				else {
					if ($totalVictimTroops > 0){
						$victimUnitsData[$i]['number'] -= round(($victimUnitsData[$i]['previous']/$totalVictimTroops)*$meleePower/$victimUnitsData[$i]['defense']);
					}
					if ($victimUnitsData[$i]['number']<0) {
						$victimUnitsData[$i]['number'] = 0;
					}
				}
				if($victimUnitsData[$i]['number'] > $victimUnitsData[$i]['previous'])
					$victimUnitsData[$i]['number'] = $victimUnitsData[$i]['previous'];
			}
		}
		
	 	
		if($debug)
			echo "<br><br>Enemy attacks: ";
		//dish out the damage XD
		$infantryVictimAttack = 0;
		
		if ($victimUnitsData['strategy'] == 'Archer Protection'){
			$numVictimArchers = 0;
			for ($i=0; $i<6; $i++){
				if ($victimUnitsData[$i]['isInfantry'] == TRUE)
					$infantryVictimAttack += $attackMultiplier*$victimUnitsData[$i]['number']*$victimUnitsData[$i]['attack'];
				if ($victimUnitsData[$i]['isArcher'] == TRUE) {
					$numVictimArchers += $victimUnitsData[$i]['number'];
				}
			}
		}
		$damageToShieldBearers = 0;

		for ($i=0; $i<6; $i++){
			if($debug)
				echo $userUnitsData[$i]['name'] . " before: " . $userUnitsData[$i]['number'] . "<br />";
			if (($victimUnitsData[$i]['isInfantry'] == TRUE) && ($totalVictimTroops > 0))
				$damageToShieldBearers += round(($victimUnitsData[$i]['number']/$totalVictimTroops)*$meleePower/$victimUnitsData[$i]['defense']);
			if ((($userRace == "Elf") && ($i == 1)) || (($userRace == "Orc") && ($i == 1)) && $totalUserTroops > 0){
				//No Melee damage to Mages or Shadow Warriors
				if($totalUserTroops > 0)	{
					if ($userRace == "Orc" )
						$userUnitsData[$i]['number'] -= round((/*$shadowProtection*/$userUnitsData[$i]['previous']/$totalUserTroops)*($victimMeleePower/$userUnitsData[$i]['defense'])*$userAttackCoefficients['shadowWarriorMeleeDeath']);
					if($userUnitsData[$i]['name'] == "Shadow Warrior"){
						$shadowWarriorHiding = .7 + $userUnitsData['cloaking'] - $userUnitsData[$i]['previous']/$totalUserTroops;
						if($userUnitsData['strategy'] == 'Surrounding'){
							$shadowWarriorHiding -= .35;
							if($debug)
								echo "Surrounding active for the attacker. <br />";
						}
						if($shadowWarriorHiding < 25)
							$shadowWarriorHiding = .25;
						if($debug)
							echo "Shadow warriors hiding: " . $shadowWarriorHiding * 100 . "%<br />"; 
						$userUnitsData[$i]['number'] += round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimMeleePower*$shadowWarriorHiding/$userUnitsData[$i]['defense']);
					}
					if ($userRace == "Elf"){
						//echo "Mages hurt<br />";
						$userUnitsData[$i]['number'] -= round(($userUnitsData[$i]['previous']/$totalUserTroops)*($mageProtection*$victimMeleePower/($userUnitsData[$i]['defense'])));
						//echo "Total User troops: $totalUserTroops <br />";
						//echo "Mage defense: " . $userUnitsData[$i]['defense'] . "<br />";
					}
					if($userUnitsData[$i]['number'] > $userUnitsData[$i]['previous'])
						$userUnitsData[$i]['number'] = $userUnitsData[$i]['previous'];
					if ($userUnitsData[$i]['number']<0)
						$userUnitsData[$i]['number'] = 0;
				}
			}
			else if ($totalUserTroops > 0) {
				$userUnitsData[$i]['number'] -= round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimMeleePower/$userUnitsData[$i]['defense']);
				
				if($userUnitsData[$i]['isInfantry'])
					$userUnitsData[$i]['number'] -= round(($userUnitsData[$i]['previous']/$totalUserTroops)*$victimOrcInfantryPower/$userUnitsData[$i]['defense']);
				if($userUnitsData[$i]['isMount'])
					$userUnitsData[$i]['number'] -= round($victimAttackCoefficients['pikeMountAttack'] * ($userUnitsData[$i]['previous']/$totalUserTroops)*$victimPikePower/$userUnitsData[$i]['defense']);
				
				if ($userUnitsData[$i]['number']<0) {
					$userUnitsData[$i]['number'] = 0;
				}
				if (($userUnitsData[$i]['name'] == 'Knight') && ($userUnitsData['strategy'] == 'Charging')) {
					$userUnitsData[$i]['number'] -= round(.5*($userUnitsData[$i]['previous']/$totalUserTroops)*$victimMeleePower/$userUnitsData[$i]['defense']);
					if($debug){
						echo "Knights on Charging: " . $userUnitsData[$i]['number'] . "<br />";
					}
				}
				if (($userUnitsData[$i]['name'] == 'Mounted Archer') && ($totalUserTroops > 0)) {
					$userUnitsData[$i]['number'] += round(.1*($userUnitsData[$i]['previous']/$totalUserTroops)*$victimMeleePower/$userUnitsData[$i]['defense']);
				}
				if($userUnitsData[$i]['number'] > $userUnitsData[$i]['previous'])
					$userUnitsData[$i]['number'] = $userUnitsData[$i]['previous'];
				if ($userUnitsData[$i]['number']<0) {
					$userUnitsData[$i]['number'] = 0;
				}
			}
			if($debug)
				echo $userUnitsData[$i]['name'] . " after: " . $userUnitsData[$i]['number'] . "<br />";
		}
		// For shield line strat
		if ($userUnitsData['strategy'] == 'Shield Line'){
			for ($i=0; $i<6; $i++){
				if ($userUnitsData[$i]['name'] != 'Shield Bearer' && ($totalUserTroops - $userUnitsData[2]['number'] != 0)){
					$userUnitsData[$i]['number'] += round(($userUnitsData[$i]['previous']/($totalUserTroops - $userUnitsData[2]['number']))*$damageToShieldBearers);
				}
				if($userUnitsData[$i]['number'] > $userUnitsData[$i]['previous'])
						$userUnitsData[$i]['number'] = $userUnitsData[$i]['previous'];
			}
		}
		if ($userUnitsData['strategy'] == 'Archer Protection'){
			for($i = 0; $i < 6; $i++){
				if ($userUnitsData[$i]['isArcher'] == TRUE && $userUnitsData[$i] > 0 && $numArchers > 0) {
					$saved = round(($userUnitsData[$i]['number']/$numArchers)*$infantryAttack / 5);
					if(($saved + $userUnitsData[$i]['number']) > $userUnitsData[$i]['previous']){
						$saved -= $userUnitsData[$i]['previous'];
					}
					if($debug)
						echo $saved . " " . $userUnitsData[$i]['name'] . " saved by Archer Protection. <br />";
					$userUnitsData[$i]['number'] += ($userUnitsData[$i]['previous']/$numArchers)*$infantryAttack / 5;
					if($userUnitsData[$i]['number'] > $userUnitsData[$i]['previous']) {
						$userUnitsData[$i]['number'] = $userUnitsData[$i]['previous'];
					}
				}
			}
		}
		if ($victimUnitsData['strategy'] == 'Archer Protection'){
			for($i = 0; $i < 6; $i++){
				if ($victimUnitsData[$i]['isArcher'] == TRUE && $victimUnitsData[$i] > 0 && $numVictimArchers > 0) {
					$saved = round(($victimUnitsData[$i]['number']/$numVictimArchers)*$infantryVictimAttack / 5);
					if($debug)
						echo $victimUnitsData[$i]['number'] . " " . $victimUnitsData[$i]['name'] . " before saved <br />";
					if(($saved + $victimUnitsData[$i]['number']) < $victimUnitsData[$i]['previous']){
						$victimUnitsData[$i]['number'] += $saved;
					}
					else{
						$victimUnitsData[$i]['number'] = $victimUnitsData[$i]['previous'];
					}
					
					if($debug){
						echo $victimUnitsData[$i]['number'] . " " . $victimUnitsData[$i]['name'] . " after saved <br />";
						echo $saved . " " . $victimUnitsData[$i]['name'] . " saved by Archer Protection. <br />";
					}
					if($victimUnitsData[$i]['number'] > $victimUnitsData[$i]['previous'])
						$victimUnitsData[$i]['number'] = $victimUnitsData[$i]['previous'];
				}
			}
		}
		
		$result = array(
			0 => $victimUnitsData,
			1 => $userUnitsData
		);
		return $result;
		
	}// end melee
	
	function healUnits ($user = null, $oldUnits = null, $newUnits = null, $totalOldUnits = null, $totalNewUnits, $defender = null, $debug = false, $simulator){
		
		
		//Paladin Tech
		$deathMultiplier = 1;
		$naturalHeal = .1;
		if($user['User']['Technology']['tree_1'] > 3)
			$naturalHeal = .2;
		if ($user['User']['Building']['medical_center'] > 0 && $totalOldUnits > 0){
			
			$medCenterHeal = .15;
			if($user['User']['Technology']['tree_1'] > 3)
				$medCenterHeal = .3;
			$medCenters = 2*($user['User']['Building']['medical_center']/$user['User']['Resource']['land']);
			if($medCenters > 1){
				$medCenters = 1;
			}
			
			$medCenterHeal *= $medCenters;
			
			
		} else {
			$medCenterHeal = 0;
		}
		
		$medCenterHeal += $naturalHeal;
		$medCenterHeal = $medCenterHeal * (($user['User']['loyalty'] + 100) / 100);

		
		//$deathMultiplier -= $medCenterHeal;
		
		for($i = 0; $i < 6; $i++){
			if($newUnits[$i]['number'] > $oldUnits[$i]['number'])
				$newUnits[$i]['number'] = $oldUnits[$i]['number'];
		}
		if($defender){
			if($debug)
				echo "Defender: <br />";
		}
		else {
			if($debug)
				echo "Attacker: <br /><br /><br />";
		}
		
		$Center = Classregistry::init('Center');
		if(empty($simulator)){
			if($Center->checkCenter($user['User']['id'])) {
				$Center->addCenterUnits(
					$user['User']['id'], 
					round($medCenterHeal * ($oldUnits[1]['number'] - $newUnits[1]['number'])), 
					round($medCenterHeal * ($oldUnits[0]['number'] - $newUnits[0]['number'])),
					round($medCenterHeal * ($oldUnits[2]['number'] - $newUnits[2]['number'])),
					round($medCenterHeal * ($oldUnits[3]['number'] - $newUnits[3]['number'])),
					round($medCenterHeal * ($oldUnits[4]['number'] - $newUnits[4]['number'])),
					round($medCenterHeal * ($oldUnits[5]['number'] - $newUnits[5]['number']))
					);
			} else {
				// error
			}
		}
		
		$unitsDead[0]['number'] = round($deathMultiplier * ($oldUnits[0]['number'] - $newUnits[0]['number']));
		$unitsDead[1]['number'] = round($deathMultiplier * ($oldUnits[1]['number'] - $newUnits[1]['number']));
		$unitsDead[2]['number'] = round($deathMultiplier * ($oldUnits[2]['number'] - $newUnits[2]['number']));
		$unitsDead[3]['number'] = round($deathMultiplier * ($oldUnits[3]['number'] - $newUnits[3]['number']));
		$unitsDead[4]['number'] = round($deathMultiplier * ($oldUnits[4]['number'] - $newUnits[4]['number']));
		$unitsDead[5]['number'] = round($deathMultiplier * ($oldUnits[5]['number'] - $newUnits[5]['number']));
		
		$unitsDead[1]['heal'] = round($medCenterHeal * ($oldUnits[1]['number'] - $newUnits[1]['number']));
		$unitsDead[0]['heal'] = round($medCenterHeal * ($oldUnits[0]['number'] - $newUnits[0]['number']));
		$unitsDead[2]['heal'] = round($medCenterHeal * ($oldUnits[2]['number'] - $newUnits[2]['number']));
		$unitsDead[3]['heal'] = round($medCenterHeal * ($oldUnits[3]['number'] - $newUnits[3]['number']));
		$unitsDead[4]['heal'] = round($medCenterHeal * ($oldUnits[4]['number'] - $newUnits[4]['number']));
		$unitsDead[5]['heal'] = round($medCenterHeal * ($oldUnits[5]['number'] - $newUnits[5]['number']));
		
		for($i = 0; $i < 6; $i++){
			if($unitsDead[$i]['number'] > $oldUnits[$i]['number'])
				$unitsDead[$i]['number'] = $oldUnits[$i]['number'];
		}
		
		return $unitsDead;
	}
	
	function getAllAttacks($user = null){
		return $this->find('all', array('conditions' => array('Attack.user_id' => $user['User']['id'])));
	}
	
	function updateAttacks($user = null){
		$i = 0;
		foreach($user['Attack'] as &$attack):
			if($attack['rounds'] > 1){
				$attack['rounds']--;
			}
			elseif($attack['rounds'] == 1){
				$user['Army']['small_elite'] += $attack['small_elite'];
				$user['Army']['big_elite'] += $attack['big_elite'];
				$user['Army']['standard'] += $attack['standard'];
				$user['Army']['weak'] += $attack['weak'];
				$user['Army']['mount'] += $attack['mount'];
				$user['Army']['range'] += $attack['range'];
				$user['Army']['generals'] += 1;
				
				switch($user['User']['race'])
				{
					case("Human"):
						$province = "Tuth";
						$maxGenerals = 5;
						break;
					case("Dwarf"):
						$province = "Khardfee";
						$maxGenerals = 6;
						break;					
					case("Elf"):
						$province = "Edilar";
						$maxGenerals = 4;
						break;					
					case("Gnome"):
						$province = "Alderd";
						$maxGenerals = 4;
						break;
					case("Orc"):
						$province = "Ruch";
						$maxGenerals = 7;
						break;	
				}
				if ($user['Army']['generals'] > $maxGenerals)
					$user['Army']['generals'] = $maxGenerals;
				
				$this->delete($attack['id']);
				unset($user['Attack'][$i]);
			}
			$i++;
		endforeach;
		//leadership tech gives a chance to increase bas each hour
		if($user['Spell']['god_blessing'] > 1){
			$chance = 1;
			$large = 10;
			
			$rand = mt_rand(1,$large);
			if ($rand <= $chance) {
				if($user['Army']['bas'] < 125)
					$user['Army']['bas'] += 1;
			}
		}
		if ($user['Technology']['tree_1'] >= 5){
			$chance = 1;
			$large = 10;
			
			$rand = mt_rand(1,$large);
			if ($rand <= $chance) {
				if($user['Army']['bas'] < 125)
					$user['Army']['bas'] += 1;
			}
		}
		
		return $user;
	}
	
}
?>