<?php
class BuildingsController extends AppController {    
	var $name = 'Buildings';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	/*
	 * 
	 * Problem with buildings, when you build alot it subtracts more than you built and doesn't always queue them.
	 * Might be only when you don't have the resources
	 * 
	 * Check score checker for buildings
	 * 
	 */
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		parent::beforeFilter();
		// This needs to be set for everything. No longer requires setting id of each thing we want :D
		// Also letting us only pass in one set ($this->Model->read()), as $user
		// So we can then go $user['User']['Resources']['iron'];
		$this->Building->recursive = 2;
        //$this->facebook_id = $this->facebookId; 
        $this->user = $this->Building->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->Building->id = $this->user['Building']['id'];
		$this->Building->User->id = $this->user['User']['id'];
		$this->Building->User->Resource->id = $this->user['User']['Resource']['id'];
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
    }  
    
    function updateRatio($ratioBuilding = null) {
    	if(!empty($ratioBuilding)) {
    		$building['Building'] = $this->user['User']['Building'];
    		$this->Building->id = $this->user['User']['Building']['id'];
    		$newRatio = $this->data['Building'];
    		if($ratioBuilding == "castle"){
    			$building['Building']['desired_castle'] = $newRatio;
    			$this->Building->saveField('desired_castle', $newRatio);
    		} elseif($ratioBuilding == "farm") {
    			$building['Building']['desired_farm'] = $newRatio;
    			$this->Building->saveField('desired_farm', $newRatio);
    		} elseif($ratioBuilding == "house") {
    			$building['Building']['desired_house'] = $newRatio;
    			$this->Building->saveField('desired_house', $newRatio);
    		} elseif($ratioBuilding == "mill") {
    			$building['Building']['desired_mill'] = $newRatio;
    			$this->Building->saveField('desired_mill', $newRatio);
    		} elseif($ratioBuilding == "mine") {
    			$building['Building']['desired_mine'] = $newRatio;
    			$this->Building->saveField('desired_mine', $newRatio);
    		} elseif($ratioBuilding == "market") {
    			$building['Building']['desired_market'] = $newRatio;
    			$this->Building->saveField('desired_market', $newRatio);
    		} elseif($ratioBuilding == "training_center") {
    			$building['Building']['desired_training_center'] = $newRatio;
    			$this->Building->saveField('desired_training_center', $newRatio);
    		} elseif($ratioBuilding == "advanced_training_center") {
    			$building['Building']['desired_advanced_training_center'] = $newRatio;
    			$this->Building->saveField('desired_advanced_training_center', $newRatio);
    		} elseif($ratioBuilding == "school") {
    			$building['Building']['desired_school'] = $newRatio;
    			$this->Building->saveField('desired_school', $newRatio);
    		} elseif($ratioBuilding == "guard_house") {
    			$building['Building']['desired_guard_house'] = $newRatio;
    			$this->Building->saveField('desired_guard_house', $newRatio);
    		} elseif($ratioBuilding == "forge") {
    			$building['Building']['desired_forge'] = $newRatio;
    			$this->Building->saveField('desired_forge', $newRatio);
    		} elseif($ratioBuilding == "medical_center") {
    			$building['Building']['desired_medical_center'] = $newRatio;
    			$this->Building->saveField('desired_medical_center', $newRatio);
    		} elseif($ratioBuilding == "guard_tower") {
    			$building['Building']['desired_guard_tower'] = $newRatio;
    			$this->Building->saveField('desired_guard_tower', $newRatio);
    		} 
    		
    		$this->set('newRatio', $newRatio);
    	}
    }
    
	/******
	 * Give the view basic data to make the table
	 */	
	function index() {  
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed' . $this->parms));
	    }
		if($this->data)
			$this->data = array();
		// Get available land
		$availableLand = $this->Building->getAvailableLand($this->user);
		// Prices
		$gold = $this->Building->getBuildingGold($this->user['User']['Resource']['gold'], ($this->user['User']['Building']['castle'] + $this->user['User']['Building']['castlesBuilding']));
    	$iron = $this->Building->getBuildingIron($this->user['User']['Resource']['iron'], ($this->user['User']['Building']['castle'] + $this->user['User']['Building']['castlesBuilding']));
    	$wood = $this->Building->getBuildingWood($this->user['User']['Resource']['wood'], ($this->user['User']['Building']['castle'] + $this->user['User']['Building']['castlesBuilding']));
    	
    	$popPerBuilding = $this->Building->getPopPerBuilding($this->user['User']['race']);
		
		if($this->user['User']['Technology']['tree_2'] > 3)
			$tech = 2;
		else
			$tech = 1;

		$numBuildings = round(floor($this->user['User']['Population']['building'] / ($popPerBuilding/$tech)) + $this->user['User']['Building']['castle']);
		$this->set('castleTime', $this->user['User']['Building']['castleTime']);
		$this->set('buildingsBuilt', $numBuildings);
    	$this->set('gold', $gold);
    	$this->set('iron', $iron);
    	$this->set('wood', $wood);
		$this->set('totalAvailable', $availableLand);
		$this->set('totalBuildings', $this->Building->getTotalBuildings($this->user));
		$this->set('user', $this->user);
	}
	
	/*
	 * Let the user build Buildings
	 * Has some bugs in it, I'll fix it later.
	 */
	function build() {
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed' . $this->parms));
	    }
		if((!ctype_digit($this->data['Building']['farmsBuilding']) && $this->data['Building']['farmsBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['millsBuilding']) && $this->data['Building']['millsBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['minesBuilding']) && $this->data['Building']['minesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['housesBuilding']) && $this->data['Building']['housesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['castlesBuilding']) && $this->data['Building']['castlesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['forgesBuilding']) && $this->data['Building']['forgesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['marketsBuilding']) && $this->data['Building']['marketsBuilding'] != NULL) ||
		(!ctype_digit($this->data['Building']['schoolsBuilding']) && $this->data['Building']['schoolsBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['medical_centersBuilding']) && $this->data['Building']['medical_centersBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['guard_housesBuilding']) && $this->data['Building']['guard_housesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['guard_towersBuilding']) && $this->data['Building']['guard_towersBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['training_centersBuilding']) && $this->data['Building']['training_centersBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['advanced_training_centersBuilding']) && $this->data['Building']['advanced_training_centersBuilding'] != NULL)){
	    	$this->Session->setFlash('You must use only numbers when building buildings.');
	    	$this->redirect(array('action' => '/index' . $this->parms));
	    }
		$landLeft = $this->Building->getAvailableLand($this->user);
		
		if($this->data['Building']['castlesBuilding'] > 1 && $this->user['Building']['castle'] < 10){
			$this->data['Building']['castlesBuilding'] = 1;
			$castlesReset = true;
		}
		
		$gold = $this->Building->getBuildingGold($this->user['User']['Resource']['gold'], $this->user['User']['Building']['castle'], $this->user['Building']['castlesBuilding']);
    	$iron = $this->Building->getBuildingIron($this->user['User']['Resource']['iron'], $this->user['User']['Building']['castle'], $this->user['Building']['castlesBuilding']);
    	$wood = $this->Building->getBuildingWood($this->user['User']['Resource']['wood'], $this->user['User']['Building']['castle'], $this->user['Building']['castlesBuilding']);
    	   	
    	// Insert into array form for shorter code writing
		$queuedBuildings = array($this->data['Building']['farmsBuilding'], $this->data['Building']['millsBuilding'], $this->data['Building']['minesBuilding'], 
			$this->data['Building']['housesBuilding'], $this->data['Building']['castlesBuilding'], $this->data['Building']['forgesBuilding'], 
			$this->data['Building']['marketsBuilding'],$this->data['Building']['schoolsBuilding'], $this->data['Building']['medical_centersBuilding'], 
			$this->data['Building']['guard_housesBuilding'], $this->data['Building']['guard_towersBuilding'], 
			$this->data['Building']['training_centersBuilding'], $this->data['Building']['advanced_training_centersBuilding']);
		$currentQBuildings = array($this->user['Building']['farmsBuilding'], $this->user['Building']['millsBuilding'], $this->user['Building']['minesBuilding'], 
			$this->user['Building']['housesBuilding'], $this->user['Building']['castlesBuilding'], $this->user['Building']['forgesBuilding'], 
			$this->user['Building']['marketsBuilding'], $this->user['Building']['schoolsBuilding'], $this->user['Building']['medical_centersBuilding'], 
			$this->user['Building']['guard_housesBuilding'], $this->user['Building']['guard_towersBuilding'], $this->user['Building']['training_centersBuilding'],
			$this->user['Building']['advanced_training_centersBuilding']);
		$error = "";

		
		for($i = 0; $i < 13; $i++) {
			if($i != 4){
	    		if(($landLeft >= $queuedBuildings[$i]) && (($this->user['Building']['castle'] * 450) >= $this->Building->getTotalBuildings($this->user) + $queuedBuildings[$i]) && 
	    		($queuedBuildings[$i] > 0) && 
	    		(($gold[$i] * $queuedBuildings[$i]) <= $gold[13]) && 
	    		(($iron[$i] * $queuedBuildings[$i]) <= $iron[13]) && 
	    		(($wood[$i] * $queuedBuildings[$i]) <= $wood[13])) {
					$gold[13] -= ($gold[$i] * $queuedBuildings[$i]);
		    		$iron[13] -= ($iron[$i] * $queuedBuildings[$i]);
		    		$wood[13] -= ($wood[$i] * $queuedBuildings[$i]);
		    		$currentQBuildings[$i] += $queuedBuildings[$i];
		    		$landLeft -= $queuedBuildings[$i];
	    		}
	    		else {
	    			if($queuedBuildings[$i] > 0){
		    			if($landLeft < $queuedBuildings[$i])
		    				$error .= 'You cannot build that many buildings. <br />';
		    			if(($this->user['Building']['castle'] * 450) < $this->Building->getTotalBuildings($this->user) + $queuedBuildings[$i])
		    				$error .= 'You do not have enough castles to control all your buildings. <br />';
		    			if(($gold[$i] * $queuedBuildings[$i]) > $gold[13])
		    				$error .= "You do not have enough gold. <br />";
		    			if(($iron[$i] * $queuedBuildings[$i]) > $iron[13])
		    				$error .= "You do not have enough iron. <br />";
		    			if(($wood[$i] * $queuedBuildings[$i]) > $wood[13])
		    				$error .= "You do not have enough wood. <br />";
		    			$queuedBuildings[$i] = 0;
	    			}
	    		}
			}
			else {
				if(($queuedBuildings[$i] > 0) && 
	    		(($gold[$i] * $queuedBuildings[$i]) <= $gold[13]) && 
	    		(($iron[$i] * $queuedBuildings[$i]) <= $iron[13]) && 
	    		(($wood[$i] * $queuedBuildings[$i]) <= $wood[13])) {
					$gold[13] -= ($gold[$i] * $queuedBuildings[$i]);
		    		$iron[13] -= ($iron[$i] * $queuedBuildings[$i]);
		    		$wood[13] -= ($wood[$i] * $queuedBuildings[$i]);
		    		$currentQBuildings[$i] += $queuedBuildings[$i];
		    		$landLeft -= $queuedBuildings[$i];
	    		}
	    		else {
	    			if($landLeft < $queuedBuildings[$i])
	    				$error .= 'You cannot build that many buildings. <br />';
	    			if(($gold[$i] * $queuedBuildings[$i]) > $gold[13])
	    				$error .= "You do not have enough gold. <br />";
	    			if(($iron[$i] * $queuedBuildings[$i]) > $iron[13])
	    				$error .= "You do not have enough iron. <br />";
	    			if(($wood[$i] * $queuedBuildings[$i]) > $wood[13])
	    				$error .= "You do not have enough wood. <br />";
	    			$queuedBuildings[$i] = 0;
	    		}
			}
    	}
    	
		if($error != ""){
    		$this->Session->setFlash($error);
    		$this->redirect(array('action'=>'index' . $this->parms));
    	}
    	
    	if(isset($castlesReset)){
    		$this->Session->setFlash("You may only build 1 castle at a time while you have below 10 castles.");
    	}
    	
    	$this->data['Building']['farmsBuilding'] = $currentQBuildings[0];
		$this->data['Building']['millsBuilding'] = $currentQBuildings[1];
    	$this->data['Building']['minesBuilding'] = $currentQBuildings[2];
		$this->data['Building']['housesBuilding'] = $currentQBuildings[3];
    	$this->data['Building']['castlesBuilding'] = $currentQBuildings[4];
		$this->data['Building']['forgesBuilding'] = $currentQBuildings[5];
    	$this->data['Building']['marketsBuilding'] = $currentQBuildings[6];
		$this->data['Building']['schoolsBuilding'] = $currentQBuildings[7];
    	$this->data['Building']['medical_centersBuilding'] = $currentQBuildings[8];
		$this->data['Building']['guard_housesBuilding'] = $currentQBuildings[9];
    	$this->data['Building']['guard_towersBuilding'] = $currentQBuildings[10];
		$this->data['Building']['training_centersBuilding'] = $currentQBuildings[11];
    	$this->data['Building']['advanced_training_centersBuilding'] = $currentQBuildings[12];
    	
    	if($currentQBuildings[4] > 0 && $this->user['Building']['castlesBuilding'] == 0) 
    		$this->data['Building']['castleTime'] = 21;
    	
    	
		$this->data['Resource']['gold'] = $gold[13];
    	$this->data['Resource']['iron'] = $iron[13];
    	$this->data['Resource']['wood'] = $wood[13];
    	$resource = array(
    		'Resource' => array(
    			'gold' => $gold[13],
    			'iron' => $iron[13],
    			'wood' => $wood[13]
    		)
    	);
    	
    	
    	$this->Building->save($this->data);
    	$this->Building->User->Resource->save($resource);
		
		$this->redirect(array('action'=>'index' . $this->parms));
	}
	
	function deconstruction() {
		$this->set('totalAvailable', $this->Building->getAvailableLand($this->user));
		$this->set('totalBuildings', $this->Building->getTotalBuildings($this->user));
		$this->set('user', $this->user);

	}
	
	function deconstruct() {
		
		if((!ctype_digit($this->data['Building']['farmsBuilding']) && $this->data['Building']['farmsBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['millsBuilding']) && $this->data['Building']['millsBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['minesBuilding']) && $this->data['Building']['minesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['housesBuilding']) && $this->data['Building']['housesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['castlesBuilding']) && $this->data['Building']['castlesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['forgesBuilding']) && $this->data['Building']['forgesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['marketsBuilding']) && $this->data['Building']['marketsBuilding'] != NULL) ||
		(!ctype_digit($this->data['Building']['schoolsBuilding']) && $this->data['Building']['schoolsBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['medical_centersBuilding']) && $this->data['Building']['medical_centersBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['guard_housesBuilding']) && $this->data['Building']['guard_housesBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['guard_towersBuilding']) && $this->data['Building']['guard_towersBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['training_centersBuilding']) && $this->data['Building']['training_centersBuilding'] != NULL) || 
		(!ctype_digit($this->data['Building']['advanced_training_centersBuilding']) && $this->data['Building']['advanced_training_centersBuilding'] != NULL)){
	    	$this->Session->setFlash('You must use only numbers when destroying buildings.');
	    	$this->redirect(array('action' => 'deconstruction' . $this->parms));
	    }
	    
	    $gold = $this->Building->getBuildingGold($this->user['User']['Resource']['gold'], $this->user['User']['Building']['castle']);
    	$iron = $this->Building->getBuildingIron($this->user['User']['Resource']['iron'], $this->user['User']['Building']['castle']);
    	$wood = $this->Building->getBuildingWood($this->user['User']['Resource']['wood'], $this->user['User']['Building']['castle']);
    	   	
				
		$queuedBuildings = array($this->data['Building']['farmsBuilding'], $this->data['Building']['millsBuilding'], $this->data['Building']['minesBuilding'], 
			$this->data['Building']['housesBuilding'], $this->data['Building']['castlesBuilding'], $this->data['Building']['forgesBuilding'], 
			$this->data['Building']['marketsBuilding'],$this->data['Building']['schoolsBuilding'], $this->data['Building']['medical_centersBuilding'], 
			$this->data['Building']['guard_housesBuilding'], $this->data['Building']['guard_towersBuilding'], 
			$this->data['Building']['training_centersBuilding'], $this->data['Building']['advanced_training_centersBuilding']);
		$currentBuildings = array($this->user['Building']['farm'], $this->user['Building']['mill'], $this->user['Building']['mine'], 
			$this->user['Building']['house'], $this->user['Building']['castle'], $this->user['Building']['forge'], 
			$this->user['Building']['market'], $this->user['Building']['school'], $this->user['Building']['medical_center'], 
			$this->user['Building']['guard_house'], $this->user['Building']['guard_tower'], $this->user['Building']['training_center'],
			$this->user['Building']['advanced_training_center']);
		$error = "";
		
		for($i = 0; $i < 13; $i++) {
    		if(($queuedBuildings[$i] > 0) && $currentBuildings[$i] >= $queuedBuildings[$i]) {
    			if($currentBuildings[4] <= $queuedBuildings[4])
		   			$error .= "You cannot destroy all of your castles. <br />";
    			$currentBuildings[$i] -= $queuedBuildings[$i];
	    		$iron[13] += round($iron[$i] * $queuedBuildings[$i] * .45);
	    		$wood[13] += round($wood[$i] * $queuedBuildings[$i] * .45);
    		}
    		else {
    			if($queuedBuildings[$i] > 0){
    				if($i != 4){
		    			if($currentBuildings[$i] < $queuedBuildings[$i])
		    				$error .= "You do not have that many buildings to deconstruct. <br />";
    				} else {
		   				if($currentBuildings[4] <= $queuedBuildings[4])
		   					$error .= "You cannot destroy all of your castles. <br />";
    				}
	    			$queuedBuildings[$i] = 0;
    			}
    		}
		}
		if($error != ""){
    		$this->Session->setFlash($error);
    		$this->redirect(array('action'=>'index' . $this->parms));
    	}
		$deconstruct = "You deconstructed ";
		if($queuedBuildings[0] > 0){
			$deconstruct .= $this->Building->User->pluralize('farm', $queuedBuildings[0]);
		}
		if($queuedBuildings[1] > 0){
			for($i = 0; $i < 1; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('mill', $queuedBuildings[1]);
		}
		if($queuedBuildings[2] > 0){
			for($i = 0; $i < 2; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('mine', $queuedBuildings[2]);
		}
		if($queuedBuildings[3] > 0){
			for($i = 0; $i < 3; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('house', $queuedBuildings[3]);
		}
		if($queuedBuildings[4] > 0){
			for($i = 0; $i < 4; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('castle', $queuedBuildings[4]);
		}
		if($queuedBuildings[5] > 0){
			for($i = 0; $i < 5; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('forge', $queuedBuildings[5]);
		}
		if($queuedBuildings[6] > 0){
			for($i = 0; $i < 6; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('market', $queuedBuildings[6]);
		}
		if($queuedBuildings[7] > 0){
			for($i = 0; $i < 7; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('school', $queuedBuildings[7]);
		}
		if($queuedBuildings[8] > 0){
			for($i = 0; $i < 8; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('medical center', $queuedBuildings[8]);
		}
		if($queuedBuildings[9] > 0){
			for($i = 0; $i < 9; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('guard house', $queuedBuildings[9]);
		}
		if($queuedBuildings[10] > 0){
			for($i = 0; $i < 10; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('guard tower', $queuedBuildings[10]);
		}
		if($queuedBuildings[11] > 0){
			for($i = 0; $i < 11; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('training center', $queuedBuildings[11]);
		}
		if($queuedBuildings[12] > 0){
			for($i = 0; $i < 12; $i++) {
				if($queuedBuildings[$i] > 0) {
					$deconstruct .= ", ";
					break;
				}
			}
			$deconstruct .= $this->Building->User->pluralize('advanced training center', $queuedBuildings[12]);
		}

		$deconstruct .= ". <br />";
		$this->Session->setFlash($deconstruct);	
			
		$this->user['Building']['farm'] = $currentBuildings[0];
		$this->user['Building']['mill'] = $currentBuildings[1];
    	$this->user['Building']['mine'] = $currentBuildings[2];
		$this->user['Building']['house'] = $currentBuildings[3];
    	$this->user['Building']['castle'] = $currentBuildings[4];
		$this->user['Building']['forge'] = $currentBuildings[5];
    	$this->user['Building']['market'] = $currentBuildings[6];
		$this->user['Building']['school'] = $currentBuildings[7];
    	$this->user['Building']['medical_center'] = $currentBuildings[8];
		$this->user['Building']['guard_house'] = $currentBuildings[9];
    	$this->user['Building']['guard_tower'] = $currentBuildings[10];
		$this->user['Building']['training_center'] = $currentBuildings[11];
    	$this->user['Building']['advanced_training_center'] = $currentBuildings[12];
    	$resource = array(
    		'Resource' => array(
    			'iron' => $iron[13],
    			'wood' => $wood[13]
    		)
    	);
    	
    	$this->Building->User->Resource->save($resource);
		
		$this->Building->save($this->user['Building']);
		$this->redirect(array('action'=>'deconstruction' . $this->parms));
	}

}
?>