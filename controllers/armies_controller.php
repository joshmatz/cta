<?php
class ArmiesController extends AppController {    
	var $name = 'Armies';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		parent::beforeFilter();
		// This needs to be set for everything. No longer requires setting id of each thing we want :D
		// Also letting us only pass in one set ($this->Model->read()), as $user
		// So we can then go $user['User']['Resources']['iron'];
		$this->Army->recursive = 2;
		//$this->facebook_id = $this->facebookId;
		$this->user = $this->Army->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->Army->id = $this->user['Army']['id'];
		$this->Army->User->id = $this->user['User']['id'];
		$this->Army->User->Resource->id = $this->user['User']['Resource']['id'];
		$this->Army->User->Weapon->id = $this->user['User']['Weapon']['id'];
		$this->Army->User->Population->id = $this->user['User']['Population']['id'];
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->set('parms', $this->parms);
    }  
    
	/******
	 * Give the view basic data to make the table
	 */	
	function index() {  
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed' . $this->parms));
	    }
	    $this->loadModel('Center');
		
		if($this->data)
			$this->data = array();
		$weapons = $this->Army->getWeaponNames($this->user['User']['race']);
		$units = $this->Army->getUnitNames($this->user);
		$price = $this->Army->getUnitPrices($this->user);
		$wizardCost = $this->Army->getWizardPrice($this->user['User']['race']);
		
		
		$this->Center->checkCenter($this->user['User']['id']);
		$this->set('centerUnits', $this->Center->getCenterUnits($this->user['User']['id']));
		$this->set('healUnits', $this->Army->healCenterUnits($this->user, $this->Army->getDefaultStats(), $save = false));
		$this->set('availableArmyHousing', $this->Army->getAvailableArmyHousing($this->user));
		$this->set('unitsTrained', $this->Army->getUnitsTrained($this->user['User']));
		$this->set('weapons', $weapons);
		$this->set('units', $units);
		$unitInfo = $this->Army->getUnitInfo($this->user);
		foreach($unitInfo as &$unit):
			$unit = nl2br($unit);
		endforeach;
		$this->set('unitInfo', $unitInfo);
		$this->set('price', $price);
		$this->set('wizards', ($this->user['Army']['wizard'] + $this->user['Army']['wizard_resting']));
		$this->set('availableWizardHousing', $this->Army->getAvailableWizardHousing($this->user));
		$this->set('wizardPrice', $wizardCost);
		$this->set('user', $this->user);
	}
	
	function upkeep() {
		$fakeUser['User']['id'] = 1;
		$fakeUser['User']['race'] = 'Orc';
		$data = $this->Army->getFullArmyData($fakeUser);
		echo "<br />Orc: <br />";
		foreach($data as $unit):
			echo $unit['name'] . ' upkeep: [*]' . $unit['gold_consumed'] . ' gold and ' . $unit['food_consumed'] . ' food consumed hourly <br />';
		endforeach;
		
		
		$fakeUser['User']['id'] = 1;
		$fakeUser['User']['race'] = 'Elf';
		$data = $this->Army->getFullArmyData($fakeUser);
		echo "<br />Elf: <br />";
		foreach($data as $unit):
			echo $unit['name'] . ' upkeep: [*]' . $unit['gold_consumed'] . ' gold and ' . $unit['food_consumed'] . ' food consumed hourly <br />';
		endforeach;
		
		$fakeUser['User']['id'] = 1;
		$fakeUser['User']['race'] = 'Gnome';
		$data = $this->Army->getFullArmyData($fakeUser);
		echo "<br />Gnome: <br />";
		foreach($data as $unit):
			echo $unit['name'] . ' upkeep: [*]' . $unit['gold_consumed'] . ' gold and ' . $unit['food_consumed'] . ' food consumed hourly <br />';
		endforeach;
		
		$fakeUser['User']['id'] = 1;
		$fakeUser['User']['race'] = 'Human';
		$data = $this->Army->getFullArmyData($fakeUser);
		echo "<br />Human: <br />";
		foreach($data as $unit):
			echo $unit['name'] . ' upkeep: [*]' . $unit['gold_consumed'] . ' gold and ' . $unit['food_consumed'] . ' food consumed hourly <br />';
		endforeach;
		
		$fakeUser['User']['id'] = 1;
		$fakeUser['User']['race'] = 'Dwarf';
		$data = $this->Army->getFullArmyData($fakeUser);
		echo "<br />Dwarf: <br />";
		foreach($data as $unit):
			echo $unit['name'] . ' upkeep: [*]' . $unit['gold_consumed'] . ' gold and ' . $unit['food_consumed'] . ' food consumed hourly <br />';
		endforeach;
		
		
		$this->redirect(array('action' => '/index' . $this->parms));
	}
	
	function train() {
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->Session->setFlash('Your kingdom has been conquered, you may restart by creating a new kingdom.');
	      		$this->redirect(array('action' => '/users/destroyed' . $this->parms));
	    }
	    if((!ctype_digit($this->data['Army']['weak']) && $this->data['Army']['weak'] != NULL) || 
	    (!ctype_digit($this->data['Army']['standard']) && $this->data['Army']['standard'] != NULL) || 
	    (!ctype_digit($this->data['Army']['mount']) && $this->data['Army']['mount'] != NULL) || 
	    (!ctype_digit($this->data['Army']['range']) && $this->data['Army']['range'] != NULL) || 
	    (!ctype_digit($this->data['Army']['small_elite']) && $this->data['Army']['small_elite'] != NULL) || 
	    (!ctype_digit($this->data['Army']['big_elite']) && $this->data['Army']['big_elite'] != NULL)){
	    	$this->Session->setFlash('You must use only numbers when training units.');
	    	$this->redirect(array('action' => '/index' . $this->parms));
	    }
	    
	    $weapons = $this->Army->getWeaponNames($this->user['User']['race']);
		$units = $this->Army->getUnitNames($this->user);
		$price = $this->Army->getUnitPrices($this->user);
		$wizardCost = $this->Army->getWizardPrice($this->user['User']['race']);
		$housingBonus = $this->Army->getHousingBonus($this->user);
		
		// Data user entered
		$error = "";
		
    	// convert data into array
    	$queuedUnits = array($this->data['Army']['weak'], $this->data['Army']['standard'], $this->data['Army']['range'], $this->data['Army']['mount'], 
    		$this->data['Army']['small_elite'], $this->data['Army']['big_elite']);
    	$currentQueuedUnits = array($this->user['Army']['weak_training'], $this->user['Army']['standard_training'], $this->user['Army']['range_training'], 
    		$this->user['Army']['mount_training'], $this->user['Army']['small_elite_training'], $this->user['Army']['big_elite_training']);

		$wizards = $this->data['Army']['wizard'];
    	if(($wizards * $wizardCost) >= $this->user['User']['Resource']['gold'])
			$error .= "You do not have enough gold for your wizards. </br >";
		if($this->user['User']['Population']['training'] < $wizards) 
			$error .= "You do not have enough population to train that many units. <br />";
		if($this->Army->getAvailableWizardHousing($this->user) < $wizards && $wizards != 0)
			$error .= "You do not have enough available wizard housing. <br />";
    	

		$availableHousing = $this->Army->getAvailableArmyHousing($this->user);
    	for($i = 0; $i < 6; $i++) {	
    	   if($queuedUnits[$i]) {
	    		if((($price[$i][0] * $queuedUnits[$i]) > $price[6][0]))
	    			$error .= "You do not have enough ". $this->Army->pluralize($weapons[0]) . " for your " . $this->Army->pluralize($units[$i]) . ".<br />";
	    		if((($price[$i][1] * $queuedUnits[$i]) > $price[6][1]))
	    			$error .= "You do not have enough ". $this->Army->pluralize($weapons[1]) . " for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    		if((($price[$i][2] * $queuedUnits[$i]) > $price[6][2]))
	    			$error .= "You do not have enough ". $this->Army->pluralize($weapons[2]) . " for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    		if((($price[$i][3] * $queuedUnits[$i]) > $price[6][3]))
	    			$error .= "You do not have enough ". $this->Army->pluralize($weapons[3]) . " for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    		if((($price[$i][4] * $queuedUnits[$i]) > $price[6][4]))
	    			$error .= "You do not have enough ". $this->Army->pluralize($weapons[4]) . " for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    		if((($price[$i][5] * $queuedUnits[$i]) > $price[6][5]))
	    			$error .= "You do not have enough ". $this->Army->pluralize($weapons[5]) . " for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    		if((($price[$i][6] * $queuedUnits[$i]) > $price[6][6]))
	    			$error .= "You do not have enough ". $this->Army->pluralize($weapons[6]) . " for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    		if($this->user['User']['race'] != "Gnome") {
	    			if($queuedUnits[$i]) {
		    			if($this->user['User']['Population']['training'] < $queuedUnits[$i])
		    				$error .= "You do not have enough population training for your " . $this->Army->pluralize($units[$i]) . ". <br />";
		    			if($availableHousing < $queuedUnits[$i])
		    				$error .= "You do not have enough available housing for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    			}
	    		}
	    		else {
	    			if($units[$i] != "Ballista" && $units[$i] != "Catapult") {
		    			if($this->user['User']['Population']['training'] < $queuedUnits[$i])
		    				$error .= "You do not have enough population training for your " . $this->Army->pluralize($units[$i]) . ". <br />";
		    			if($availableHousing < $queuedUnits[$i])
		    				$error .= "You do not have enough available housing for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    			}
	    			elseif($units[$i] == "Ballista") {
			    			if($this->user['User']['Population']['training'] < $queuedUnits[$i] * 8)
			    				$error .= "You do not have enough population training for your " . $this->Army->pluralize($units[$i]) . ". <br />";
			    			if($availableHousing < $queuedUnits[$i] * 8)
			    				$error .= "You do not have enough available housing for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    			}
					elseif($units[$i] == "Catapult"){
			    			if($this->user['User']['Population']['training'] < $queuedUnits[$i] * 10)
			    				$error .= "You do not have enough population training for your " . $this->Army->pluralize($units[$i]) . ". <br />";
			    			if($availableHousing < $queuedUnits[$i] * 10)
			    				$error .= "You do not have enough available housing for your " . $this->Army->pluralize($units[$i]) . ". <br />";
	    			}
	    		}
	    		// Subtract resources and weapons
	    		$price[6][0] -= ($price[$i][0] * $queuedUnits[$i]);
	    		$price[6][1] -= ($price[$i][1] * $queuedUnits[$i]);
	    		$price[6][2] -= ($price[$i][2] * $queuedUnits[$i]);
	    		$price[6][3] -= ($price[$i][3] * $queuedUnits[$i]);
	    		$price[6][4] -= ($price[$i][4] * $queuedUnits[$i]);
	    		$price[6][5] -= ($price[$i][5] * $queuedUnits[$i]);
	    		$price[6][6] -= ($price[$i][6] * $queuedUnits[$i]);
	    		if($this->user['User']['race'] != "Gnome") {
	    			// subtract population
	    			$this->user['User']['Population']['training'] -= $queuedUnits[$i];
	    			$this->user['User']['Population']['total_population'] -= $queuedUnits[$i];
	    			$availableHousing -= $queuedUnits[$i];
	    			// add to queued units
	    			$currentQueuedUnits[$i] += $queuedUnits[$i];
	    		}
	    		else {
	    			if($units[$i] != "Ballista" && $units[$i] != "Catapult") {
	  	    			// subtract population
		    			$this->user['User']['Population']['training'] -= $queuedUnits[$i];
		    			$this->user['User']['Population']['total_population'] -= $queuedUnits[$i];
		    			$availableHousing -= $queuedUnits[$i];
		    			// add to queued units
		    			$currentQueuedUnits[$i] += $queuedUnits[$i];
	    			}
	    			elseif($units[$i] == "Ballista") {
		    			// subtract population
		    			$this->user['User']['Population']['training'] -= ($queuedUnits[$i] * 8);
		    			$this->user['User']['Population']['total_population'] -= ($queuedUnits[$i] * 8);
		    			$availableHousing -= $queuedUnits[$i] * 8;
		    			// add to queued units
		    			$currentQueuedUnits[$i] += $queuedUnits[$i];
	    			}
					elseif($units[$i] == "Catapult"){
		    			// subtract population
		    			$this->user['User']['Population']['training'] -= ($queuedUnits[$i] * 10);
		    			$this->user['User']['Population']['total_population'] -= ($queuedUnits[$i] * 10);
		    			$availableHousing -= $queuedUnits[$i] * 10;
		    			// add to queued units
		    			$currentQueuedUnits[$i] += $queuedUnits[$i];
	    			}
	    		} // gnome differences
			} // queued units
    	} // for loop
    	
		if($error != "") {
			$this->Session->setFlash($error);
			$this->redirect(array('action'=>'index' . $this->parms));
		}
    	
		$wizardsTraining = $this->user['Army']['wizard_training'];			
		$wizardsTraining += $wizards;
		$this->user['User']['Population']['training'] -= $wizards;
		$this->user['User']['Population']['total_population'] -= $wizards;
		$price[6][6] -= ($wizards * $wizardCost);
	
		
		$resource = array(
    		'Resource' => array(
    			'gold' => $price[6][6]
    		)
    	);
    	
    	$weapon = array(
    		'Weapon' => array(
    			'vehicle' => $price[6][0],
    			'big_melee' => $price[6][1],
    			'small_melee' => $price[6][2],
    			'big_armor' => $price[6][3],
    			'small_armor' => $price[6][4],
    			'range' => $price[6][5]
    		)
    	);
    	
    	$unitsTraining = array(
    		'Army' => array(
    			'weak_training' => $currentQueuedUnits[0],
    			'standard_training' => $currentQueuedUnits[1],
    			'range_training' => $currentQueuedUnits[2],
    			'mount_training' => $currentQueuedUnits[3],
    			'small_elite_training' => $currentQueuedUnits[4],
    			'big_elite_training' => $currentQueuedUnits[5],
    			'wizard_training' => $wizardsTraining
    		)
    	);
    	
    	$population = array(
    		'Population' => array(
    			'total_population' => $this->user['User']['Population']['total_population'],
    			'training' => $this->user['User']['Population']['training']
    		)
    	);
    	
    	$this->Army->User->Population->save($population);
		$this->Army->User->Resource->save($resource);
		$this->Army->User->Weapon->save($weapon);
    	$this->Army->save($unitsTraining);
    	
    	$this->redirect(array('action'=>'index' . $this->parms));
	}
	
	
	function disband(){
		if($this->user['User']['Building']['castle'] == 0){
      		$this->Session->setFlash('Your kingdom has been conquered, you may restart by creating a new kingdom.');
      		$this->redirect(array('action' => '/users/destroyed' . $this->parms));
	    }
		$weapons = $this->Army->getWeaponNames($this->user['User']['race']);
		$units = $this->Army->getUnitNames($this->user);
		$price = $this->Army->getUnitPrices($this->user);
		$wizardCost = $this->Army->getWizardPrice($this->user['User']['race']);
	    if(!empty($this->data)){
		    if((!ctype_digit($this->data['Army']['weak']) && $this->data['Army']['weak'] != NULL) || 
		    (!ctype_digit($this->data['Army']['standard']) && $this->data['Army']['standard'] != NULL) || 
		    (!ctype_digit($this->data['Army']['mount']) && $this->data['Army']['mount'] != NULL) || 
		    (!ctype_digit($this->data['Army']['range']) && $this->data['Army']['range'] != NULL) || 
		    (!ctype_digit($this->data['Army']['small_elite']) && $this->data['Army']['small_elite'] != NULL) || 
		    (!ctype_digit($this->data['Army']['big_elite']) && $this->data['Army']['big_elite'] != NULL)){
		    	$this->Session->setFlash('You must use only numbers when training units.');
		    	$this->redirect(array('action' => '/disband' . $this->parms));
		    }
			
			// Data user entered
			$error = "";
			
	    	// convert data into array
	    	$queuedUnits = array($this->data['Army']['weak'], $this->data['Army']['standard'], $this->data['Army']['range'], $this->data['Army']['mount'], 
	    		$this->data['Army']['small_elite'], $this->data['Army']['big_elite']);
	    	$currentUnits = array($this->user['Army']['weak'], $this->user['Army']['standard'], $this->user['Army']['range'], 
	    		$this->user['Army']['mount'], $this->user['Army']['small_elite'], $this->user['Army']['big_elite']);
	
			$wizards = $this->data['Army']['wizard'];	
	
	    	for($i = 0; $i < 6; $i++) {	
	    	   if($queuedUnits[$i]) {
	    			if($queuedUnits[$i]) {
		    			if($currentUnits[$i] < $queuedUnits[$i])
		    				$error .= "You do not have enough " . $this->Army->pluralize($units[$i]) . " trained to disband that many. <br />";
	    			}
	
		    		// Subtract resources and weapons
		    		$price[6][0] += ($price[$i][0] * $queuedUnits[$i]);
		    		$price[6][1] += ($price[$i][1] * $queuedUnits[$i]);
		    		$price[6][2] += ($price[$i][2] * $queuedUnits[$i]);
		    		$price[6][3] += ($price[$i][3] * $queuedUnits[$i]);
		    		$price[6][4] += ($price[$i][4] * $queuedUnits[$i]);
		    		$price[6][5] += ($price[$i][5] * $queuedUnits[$i]);
		    		if($this->user['User']['race'] != "Gnome") {
		    			// subtract population
		    			$this->user['User']['Population']['training'] += $queuedUnits[$i];
		    			$this->user['User']['Population']['total_population'] += $queuedUnits[$i];
		    			// add to queued units
		    			$currentUnits[$i] -= $queuedUnits[$i];
		    		}
		    		else {
		    			if($units[$i] != "Ballista" && $units[$i] != "Catapult") {
		  	    			// subtract population
			    			$this->user['User']['Population']['training'] += $queuedUnits[$i];
			    			$this->user['User']['Population']['total_population'] += $queuedUnits[$i];
			    			// add to queued units
			    			$currentUnits[$i] -= $queuedUnits[$i];
		    			}
		    			elseif($units[$i] == "Ballista") {
			    			// subtract population
			    			$this->user['User']['Population']['training'] += ($queuedUnits[$i] * 4);
			    			$this->user['User']['Population']['total_population'] += ($queuedUnits[$i] * 4);
			    			// add to queued units
			    			$currentUnits[$i] -= $queuedUnits[$i];
		    			}
						elseif($units[$i] == "Catapult"){
			    			// subtract population
			    			$this->user['User']['Population']['training'] += ($queuedUnits[$i] * 5);
			    			$this->user['User']['Population']['total_population'] += ($queuedUnits[$i] * 5);
			    			// add to queued units
			    			$currentUnits[$i] -= $queuedUnits[$i];
		    			}
		    		} // gnome differences
				} // if queued units > 0
	    	} // for loop for every unit
	    	
			
	    	
			if($wizards <= $this->user['Army']['wizard']) {
				$this->user['User']['Population']['training'] += $wizards;
				$this->user['User']['Population']['total_population'] += $wizards;
				$this->user['User']['Army']['wizard'] -= $wizards;
			}
			else {
				$error .= "You do not have enough wizards to disband that many.";
			}
		
			if($error != "") {
				$this->Session->setFlash($error);
				$this->redirect(array('action'=>'disband' . $this->parms));
			}
			   	
	    	$weapon = array(
	    		'Weapon' => array(
	    			'vehicle' => $price[6][0],
	    			'big_melee' => $price[6][1],
	    			'small_melee' => $price[6][2],
	    			'big_armor' => $price[6][3],
	    			'small_armor' => $price[6][4],
	    			'range' => $price[6][5]
	    		)
	    	);
	    	
	    	$unitsTraining = array(
	    		'Army' => array(
	    			'weak' => $currentUnits[0],
	    			'standard' => $currentUnits[1],
	    			'range' => $currentUnits[2],
	    			'mount' => $currentUnits[3],
	    			'small_elite' => $currentUnits[4],
	    			'big_elite' => $currentUnits[5],
	    			'wizard' => $this->user['User']['Army']['wizard']
	    		)
	    	);
	    	
	    	$population = array(
	    		'Population' => array(
	    			'total_population' => $this->user['User']['Population']['total_population'],
	    			'training' => $this->user['User']['Population']['training']
	    		)
	    	);
	    	
	    	if($this->Army->User->Population->save($population)) {
	    		//echo "Population saved";
	    	}
			if($this->Army->User->Weapon->save($weapon)){
				//echo "Weapons saved";
			}
	    	if($this->Army->save($unitsTraining)){
	    		//echo "Army saved";
	    	}
	    	$this->user = $this->Army->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
	    }
    	if($this->data)
			$this->data = array();
    	$this->set('availableArmyHousing', $this->Army->getAvailableArmyHousing($this->user));
		$this->set('unitsTrained', $this->Army->getUnitsTrained($this->user['User']));
		$this->set('weapons', $weapons);
		$this->set('units', $units);
		$this->set('price', $price);
		$this->set('wizards', ($this->user['Army']['wizard'] + $this->user['Army']['wizard_resting']));
		$this->set('availableWizardHousing', $this->Army->getAvailableWizardHousing($this->user));
		$this->set('wizardPrice', $wizardCost);
		$this->set('user', $this->user);	
	}
}
?>