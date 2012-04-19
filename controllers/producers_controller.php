<?php
class ProducersController extends AppController {    
	var $name = 'Producers';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	
	/*
	 * 
	 * Problem with Producers, when you build alot it subtracts more than you built and doesn't always queue them.
	 * Might be only when you don't have the resources
	 * 
	 * Check score checker for Producers
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
		
		$this->Producer->recursive = 2;
        $this->facebook_id = $this->facebookId; 
        if($this->user = $this->Producer->find('first', array('conditions' => array('User.account_id' => $this->account_id))))
        {
        	
        } 
        else{
        	$this->user = $this->Producer->User->find('first', array('conditions' => array('User.account_id' => $this->account_id)));
        	$producer['user_id'] = $this->user['User']['id'];
	   		$this->Producer->save($producer);
	   		$this->user = $this->Producer->find('first', array('conditions' => array('User.account_id' => $this->account_id)));
        }
        $this->Producer->id = $this->user['Producer']['id'];
		$this->Producer->User->id = $this->user['User']['id'];
		$this->Producer->User->Resource->id = $this->user['User']['Resource']['id'];
		$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
    }  
    
	/******
	 * Give the view basic data to make the table
	 */	
	function index() {  
		$this->set('user', $this->user);
		$this->set('producers', $this->Producer->getProducerInformation($this->user));
	}
	
	/*
	 * Let the user build Producers
	 * Has some bugs in it, I'll fix it later.
	 */
	function build($building = null) {
		$producers = $this->Producer->getProducerInformation($this->user);
		
		$found = false;
		$built = false;
		$error = "";
		foreach($producers as $producer):
			if($producer['info']['img'] == $building) {
				$building = $building . "_total";
				$found = true;
				
				// If they can afford it among other things, build it
				if($producer['building']['price']['wood'] <= $this->user['User']['Resource']['wood'] && 
				$producer['building']['price']['iron'] <= $this->user['User']['Resource']['iron'] && 
				$producer['building']['price']['gold'] <= $this->user['User']['Resource']['gold'] && 
				$this->Producer->getAvailableLand($this->user) && $this->Producer->isUnderCastleRatio($this->user)) {
					$this->user['Producer'][$building]++;
					$this->user['User']['Resource']['wood'] -= $producer['building']['price']['wood'];
					$this->user['User']['Resource']['iron'] -= $producer['building']['price']['iron'];
					$this->user['User']['Resource']['gold'] -= $producer['building']['price']['gold'];
					$built = true;
					break;
				}
				else {
					if(!$found)
						$error .= "There was a problem with your building name. <br />";
					else {
						if($producer['building']['price']['wood'] > $this->user['User']['Resource']['wood'])
							$error .= "You do not have enough wood to build that. <br />";
						if($producer['building']['price']['iron'] > $this->user['User']['Resource']['iron'])
							$error .= "You do not have enough wood to build that. <br />";
						if($producer['building']['price']['gold'] > $this->user['User']['Resource']['gold'])
							$error .= "You do not have enough wood to build that. <br />";
						if(!$this->Producer->getAvailableLand($this->user))
							$error .= "You do not have enough land. <br />";
						if(!$this->Producer->isUnderCastleRatio($this->user))
							$error .= "You need more castles. <br />";
					}
				}
			}
		endforeach;
		if($found && $built){ 
	  	   	$this->Producer->save($this->user['Producer']);
	    	$this->Producer->User->Resource->save($this->user['User']['Resource']);
		}
		else {
			$this->Session->setFlash($error);
		}
		
		$this->redirect(array('action'=>'index' . $this->parms));
	}
	
	function upgrade($building = null){
		$producers = $this->Producer->getProducerInformation($this->user);
		
		$found = false;
		$built = false;
		$error = "";
		foreach($producers as $producer):
			if($producer['info']['img'] == $building) {
				$building = $building . "_total";
				$found = true;
				
				// If they can afford it among other things, build it
				if($producer['output']['price']['wood'] <= $this->user['User']['Resource']['wood'] && 
				$producer['output']['price']['iron'] <= $this->user['User']['Resource']['iron'] && 
				$producer['output']['price']['gold'] <= $this->user['User']['Resource']['gold'] && 
				$this->Producer->getAvailableLand($this->user) && $this->Producer->isUnderCastleRatio($this->user)) {
					$this->user['Producer'][$building]++;
					$this->user['User']['Resource']['wood'] -= $producer['building']['price']['wood'];
					$this->user['User']['Resource']['iron'] -= $producer['building']['price']['iron'];
					$this->user['User']['Resource']['gold'] -= $producer['building']['price']['gold'];
					$built = true;
					break;
				}
				else {
					$errors = 0;
				}
			}
		endforeach;
		if($found && $built){ 
	  	   	$this->Producer->save($this->user['Producer']);
	    	$this->Producer->User->Resource->save($this->user['User']['Resource']);
		}
		else {
			$this->Session->setFlash($error);
		}
		
		$this->redirect(array('action'=>'index' . $this->parms));
	}

}
?>