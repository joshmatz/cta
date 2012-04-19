<?php
class PopulationsController extends AppController {    
	var $name = 'Populations';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		parent::beforeFilter();
		$this->Population->recursive = 2;
        //$this->facebook_id = $this->facebookId;
        if($this->facebook_id == 1554780008){
        	//$this->facebook_id = 1672808111;
        }
        $this->user = $this->Population->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->Population->id = $this->user['Population']['id'];
		$this->Population->User->id = $this->user['User']['id'];
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
    }  
    
	function index() {     
		if($this->user['User']['Building']['castle'] == 0){
	      	$this->redirect(array('controller' => 'users', 'action' => 'destroyed' . $this->parms));
	    } 		
		$this->set('population', $this->user['User']);
		$this->set('perBuilding', $this->Population->getPerBuilding($this->user['User']));
		$this->set('buildings', $this->user['User']);
		$this->loadModel('Resource');
		$updatedUser = $this->Resource->updateWood($this->user['User']);
		$updatedUser = $this->Resource->updateIron($updatedUser);
		$updatedUser = $this->Resource->updateFood($updatedUser);
		$updatedUser = $this->Resource->updateLand($updatedUser);
		$this->loadModel('Army');
		$this->set('availableHousing', $this->Army->getAvailableArmyHousing($this->user));
		//debug($updatedUser['Resource']);
		//debug($this->user['User']['Resource']);
		$this->loadModel('Weapon');
		$this->set('weaponsProduced', $this->Weapon->getWeaponsProduced($this->user['User']));
		$this->set('updatedUser', $updatedUser);
		$this->set('user', $this->user);
	}
	
	// Sends you here after hitting "submit"
	function change() {
		$this->layout = 'ajax';
		if((!ctype_digit($this->data['Population']['lumbersPer']) && $this->data['Population']['lumbersPer'] != NULL) || 
		    (!ctype_digit($this->data['Population']['lumbersTotal']) && $this->data['Population']['lumbersTotal'] != NULL) || 
			(!ctype_digit($this->data['Population']['minersPer']) && $this->data['Population']['minersPer'] != NULL) || 
			(!ctype_digit($this->data['Population']['minersTotal']) && $this->data['Population']['minersTotal'] != NULL) || 
			(!ctype_digit($this->data['Population']['farmersPer']) && $this->data['Population']['farmersPer'] != NULL) || 
			(!ctype_digit($this->data['Population']['farmersTotal']) && $this->data['Population']['farmersTotal'] != NULL) || 
			(!ctype_digit($this->data['Population']['blacksmithsPer']) && $this->data['Population']['blacksmithsPer'] != NULL) || 
			(!ctype_digit($this->data['Population']['blacksmithsTotal']) && $this->data['Population']['blacksmithsTotal'] != NULL) || 
			(!ctype_digit($this->data['Population']['trainingPer']) && $this->data['Population']['trainingPer'] != NULL) || 
			(!ctype_digit($this->data['Population']['trainingTotal']) && $this->data['Population']['trainingTotal'] != NULL) || 
			(!ctype_digit($this->data['Population']['exploringPer']) && $this->data['Population']['exploringPer'] != NULL) || 
			(!ctype_digit($this->data['Population']['exploringTotal']) && $this->data['Population']['exploringTotal'] != NULL))
		{
	    	$this->Session->setFlash('You must use only numbers when setting your population.');
	    	$this->set('population', $this->user['User']);
			$this->set('perBuilding', $this->Population->getPerBuilding($this->user['User']));
			$this->set('buildings', $this->user['User']);
			$this->loadModel('Resource');
			$updatedUser = $this->Resource->updateWood($this->user['User']);
			$updatedUser = $this->Resource->updateIron($updatedUser);
			$updatedUser = $this->Resource->updateFood($updatedUser);
			$updatedUser = $this->Resource->updateLand($updatedUser);
			$this->loadModel('Army');
			$this->set('availableHousing', $this->Army->getAvailableArmyHousing($this->user));
			//debug($updatedUser['Resource']);
			//debug($this->user['User']['Resource']);
			$this->loadModel('Weapon');
			$this->set('weaponsProduced', $this->Weapon->getWeaponsProduced($this->user['User']));
			$this->set('updatedUser', $updatedUser);
			$this->set('user', $this->user);
	    } else {
			$this->Population->setPopulation($this->data, $this->user);
			$this->user = $this->Population->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
			$this->set('perBuilding', $this->Population->getPerBuilding($this->user['User']));
			$this->set('buildings', $this->user['User']);
			$this->loadModel('Resource');
			$updatedUser = $this->Resource->updateWood($this->user['User']);
			$updatedUser = $this->Resource->updateIron($updatedUser);
			$updatedUser = $this->Resource->updateFood($updatedUser);
			$updatedUser = $this->Resource->updateLand($updatedUser);
			$this->loadModel('Army');
			$this->set('availableHousing', $this->Army->getAvailableArmyHousing($this->user));
			$this->loadModel('Weapon');
			$this->set('weaponsProduced', $this->Weapon->getWeaponsProduced($this->user['User']));
			$this->set('updatedUser', $updatedUser);
			$this->set('population', $this->user['User']);
			$this->set('user', $this->user);
	    }
		
		
	}
	
	function slider(){
		$this->set('perBuilding', $this->Population->getPerBuilding($this->user['User']));
		$this->set('user', $this->user);
	}
	
}
?>