<?php
class WeaponsController extends AppController {    
	var $name = 'Weapons';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		// This needs to be set for everything. No longer requires setting id of each thing we want :D
		// Also letting us only pass in one set ($this->Model->read()), as $user
		// So we can then go $user['User']['Resources']['iron'];
		
		parent::beforeFilter();
		$this->Weapon->recursive = 2;
        //$this->facebook_id = $this->facebookId; 
        $this->user = $this->Weapon->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->Weapon->id = $this->user['Weapon']['id'];
		$this->Weapon->User->id = $this->user['User']['id'];
		$this->Weapon->User->Resource->id = $this->user['User']['Resource']['id'];
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

	    $weapons = $this->Weapon->getWeaponNames($this->user['User']['race'], $this->user['User']['Resource']);
	    $gold = $this->Weapon->getWeaponGold($this->user['User']['race'], $this->user['User']['Resource']);
	    $iron = $this->Weapon->getWeaponIron($this->user['User']['race'], $this->user['User']['Resource']);
	    $wood = $this->Weapon->getWeaponWood($this->user['User']['race'], $this->user['User']['Resource']);
	    

		$this->set('weaponsProduced', $this->Weapon->getWeaponsProduced($this->user['User']));
		$this->set('weapons', $weapons);
		$this->set('gold', $gold);
		$this->set('iron', $iron);
		$this->set('wood', $wood);
		$this->set('user', $this->user);
	}
	
	function destroy() {
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed' . $this->parms));
	    }
	    $weapons = $this->Weapon->getWeaponNames($this->user['User']['race'], $this->user['User']['Resource']);
	    if(!empty($this->data)){
		    if((!ctype_digit($this->data['Weapon']['vehicle_building']) && $this->data['Weapon']['vehicle_building'] != NULL) || 
		    (!ctype_digit($this->data['Weapon']['big_melee_building']) && $this->data['Weapon']['big_melee_building'] != NULL) || 
		  	(!ctype_digit($this->data['Weapon']['small_melee_building']) && $this->data['Weapon']['small_melee_building'] != NULL) || 
		  	(!ctype_digit($this->data['Weapon']['big_armor_building']) && $this->data['Weapon']['big_armor_building'] != NULL) || 
		   	(!ctype_digit($this->data['Weapon']['small_armor_building']) && $this->data['Weapon']['small_armor_building'] != NULL) || 
		   	(!ctype_digit($this->data['Weapon']['range_building']) && $this->data['Weapon']['range_building'] != NULL)){
		    	$this->Session->setFlash('You must use only numbers when building weapons.');
		    	$this->redirect(array('action' => '/index' . $this->parms));
		    } 	
	    	
		    $gold = $this->Weapon->getWeaponGold($this->user['User']['race'], $this->user['User']['Resource']);
		    $iron = $this->Weapon->getWeaponIron($this->user['User']['race'], $this->user['User']['Resource']);
		    $wood = $this->Weapon->getWeaponWood($this->user['User']['race'], $this->user['User']['Resource']);
		    
		    $vehicleBuilding = $this->user['Weapon']['vehicle'];
			$big_meleeBuilding = $this->user['Weapon']['big_melee'];
			$small_meleeBuilding = $this->user['Weapon']['small_melee'];
			$big_armorBuilding = $this->user['Weapon']['big_armor'];
			$small_armorBuilding = $this->user['Weapon']['small_armor'];
			$rangeBuilding = $this->user['Weapon']['range'];
	
	    	
		 	$vehicle = $this->data['Weapon']['vehicle_building'];
			$big_melee = $this->data['Weapon']['big_melee_building'];
			$small_melee = $this->data['Weapon']['small_melee_building'];
			$big_armor = $this->data['Weapon']['big_armor_building'];
			$small_armor = $this->data['Weapon']['small_armor_building'];
			$range = $this->data['Weapon']['range_building'];
	    	
	    	// convert data into array
	    	$queuedWeapons = array($vehicle,$big_melee,$small_melee,$big_armor,$small_armor,$range);
	    	$currentQWeapons = array($vehicleBuilding,$big_meleeBuilding,$small_meleeBuilding,$big_armorBuilding,$small_armorBuilding,$rangeBuilding);
	    	//echo "Deconstructing... <br />";
	    	$error = "";
	    	$goldRecovered = 0;
	    	$ironRecovered = 0;
	    	$woodRecovered = 0;
	    	for($i = 0; $i < 6; $i++) {
	    		if(($queuedWeapons[$i] <= $currentQWeapons[$i])) {
	    			$goldRecovered += floor($gold[$i] * $queuedWeapons[$i] * .45);
	    			$ironRecovered += floor($iron[$i] * $queuedWeapons[$i]);
	    			$woodRecovered += floor($wood[$i] * $queuedWeapons[$i]);
	    			$currentQWeapons[$i] -= $queuedWeapons[$i];
	    		}
	    		else {
	    			if($queuedWeapons[$i] > $currentQWeapons[$i])
	    				$error .= "You do not have that many weapons.";
	    			
	    			$queuedWeapons[$i] = 0;
	    		}
	    	}
	    	
	    	//echo "Deconstruction finished... <br />";
	    	
	    	if($error != "") {
	    		//debug($error);
	    		$this->Session->setFlash($error);
	    		$this->redirect(array('action'=>'destroy' . $this->parms));
	    	}
	    	$gold[6] += $goldRecovered;
    		$iron[6] += $ironRecovered;
    		$wood[6] += $woodRecovered;
    		$this->Session->setFlash("You recovered $goldRecovered gold, $ironRecovered, iron and $woodRecovered wood.");
	    	
	    	$save['Weapon']['vehicle'] = $currentQWeapons[0];
			$save['Weapon']['big_melee'] = $currentQWeapons[1];
	    	$save['Weapon']['small_melee'] = $currentQWeapons[2];
			$save['Weapon']['big_armor'] = $currentQWeapons[3];
	    	$save['Weapon']['small_armor'] = $currentQWeapons[4];
			$save['Weapon']['range'] = $currentQWeapons[5];
	
	
	    	// Save
	    	$resource = array(
	    		'Resource' => array(
	    			'gold' => $gold[6],
	    			'iron' => $iron[6],
	    			'wood' => $wood[6]
	    		)
	    	);
	    
	    	
	    	$this->Weapon->save($save);
	    	$this->Weapon->User->Resource->save($resource);
	    	
	    	//echo "Deconstruction setting... <br />";
		    //$deconstruct = "You destroyed ";
		   
		    
		    //$this->Session->setFlash($deconstruct);
			//echo "Deconstruction flash set. <br />";
			
			//$deconstruct .= ".";
	    }
	   	$this->set('weapons', $weapons);
	    $this->set('user', $this->Weapon->find('first', array('conditions' => array('User.account_id' => $this->account_id))) );
	}
	
	function build() {
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed' . $this->parms));
	    }
	    if((!ctype_digit($this->data['Weapon']['vehicle_building']) && $this->data['Weapon']['vehicle_building'] != NULL) || 
	    (!ctype_digit($this->data['Weapon']['big_melee_building']) && $this->data['Weapon']['big_melee_building'] != NULL) || 
	  	(!ctype_digit($this->data['Weapon']['small_melee_building']) && $this->data['Weapon']['small_melee_building'] != NULL) || 
	  	(!ctype_digit($this->data['Weapon']['big_armor_building']) && $this->data['Weapon']['big_armor_building'] != NULL) || 
	   	(!ctype_digit($this->data['Weapon']['small_armor_building']) && $this->data['Weapon']['small_armor_building'] != NULL) || 
	   	(!ctype_digit($this->data['Weapon']['range_building']) && $this->data['Weapon']['range_building'] != NULL)){
	    	$this->Session->setFlash('You must use only numbers when building weapons.');
	    	$this->redirect(array('action' => '/index' . $this->parms));
	    } 	
	    $weapons = $this->Weapon->getWeaponNames($this->user['User']['race'], $this->user['User']['Resource']);
	    $gold = $this->Weapon->getWeaponGold($this->user['User']['race'], $this->user['User']['Resource']);
	    $iron = $this->Weapon->getWeaponIron($this->user['User']['race'], $this->user['User']['Resource']);
	    $wood = $this->Weapon->getWeaponWood($this->user['User']['race'], $this->user['User']['Resource']);

	
		$vehicleBuilding = $this->user['Weapon']['vehicle_building'];
		$big_meleeBuilding = $this->user['Weapon']['big_melee_building'];
		$small_meleeBuilding = $this->user['Weapon']['small_melee_building'];
		$big_armorBuilding = $this->user['Weapon']['big_armor_building'];
		$small_armorBuilding = $this->user['Weapon']['small_armor_building'];
		$rangeBuilding = $this->user['Weapon']['range_building'];

    	
	 	$vehicle = $this->data['Weapon']['vehicle_building'];
		$big_melee = $this->data['Weapon']['big_melee_building'];
		$small_melee = $this->data['Weapon']['small_melee_building'];
		$big_armor = $this->data['Weapon']['big_armor_building'];
		$small_armor = $this->data['Weapon']['small_armor_building'];
		$range = $this->data['Weapon']['range_building'];
    	
    	// convert data into array
    	$queuedWeapons = array($vehicle,$big_melee,$small_melee,$big_armor,$small_armor,$range);
    	$currentQWeapons = array($vehicleBuilding,$big_meleeBuilding,$small_meleeBuilding,$big_armorBuilding,$small_armorBuilding,$rangeBuilding);
    	
    	$error = "";
    	for($i = 0; $i < 6; $i++) {
    		if(($queuedWeapons[$i] > 0) && (($gold[$i] * $queuedWeapons[$i]) <= $gold[6]) && (($iron[$i] * $queuedWeapons[$i]) <= $iron[6]) && (($wood[$i] * $queuedWeapons[$i]) <= $wood[6])) {
    			$gold[6] -= ($gold[$i] * $queuedWeapons[$i]);
    			$iron[6] -= ($iron[$i] * $queuedWeapons[$i]);
    			$wood[6] -= ($wood[$i] * $queuedWeapons[$i]);
    			$currentQWeapons[$i] += $queuedWeapons[$i];
    		}
    		else {
    			if(($gold[$i] * $queuedWeapons[$i]) > $gold[6])
    				$error .= "You do not have enough gold.";
    			if(($iron[$i] * $queuedWeapons[$i]) > $iron[6])
    				$error .= "You do not have enough iron.";
    			if(($wood[$i] * $queuedWeapons[$i]) > $wood[6])
    				$error .= "You do not have enough wood.";
    			
    			$queuedWeapons[$i] = 0;
    		}
    	}
    	
    	if($error != "") {
    		$this->Session->setFlash($error);
    		$this->redirect(array('action'=>'index' . $this->parms));
    	}
    	
    	$this->data['Weapon']['vehicle_building'] = $currentQWeapons[0];
		$this->data['Weapon']['big_melee_building'] = $currentQWeapons[1];
    	$this->data['Weapon']['small_melee_building'] = $currentQWeapons[2];
		$this->data['Weapon']['big_armor_building'] = $currentQWeapons[3];
    	$this->data['Weapon']['small_armor_building'] = $currentQWeapons[4];
		$this->data['Weapon']['range_building'] = $currentQWeapons[5];


    	// Save
    	$resource = array(
    		'Resource' => array(
    			'gold' => $gold[6],
    			'iron' => $iron[6],
    			'wood' => $wood[6]
    		)
    	);
    	
    	
    	$this->Weapon->save($this->data);
    	$this->Weapon->User->Resource->save($resource);
		
		$this->redirect(array('action'=>'index' . $this->parms));
	
	}
}
?>