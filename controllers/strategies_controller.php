<?php
class StrategiesController extends AppController {    
	var $name = 'Strategies';
	var $uses = array();
	var $layout = 'redesign';
	
	function beforeFilter() {  
        //$this->facebook_id = $this->facebookId;
        parent::beforeFilter();
        $this->loadModel('User'); 
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->User->id = $this->user['User']['id'];
        //$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		$menu = array(
			array(
				'name' => 'War Room',
				'link' => '/attacks'
			),
			array(
				'name' => 'Strategy',
				'link' => '/strategies'
			),
			array(
				'name' => 'Retaliations',
				'link' => '/retaliations'
			),
			array(
				'name' => 'Simulator',
				'link' => '/attacks/attackSimulator'
			)
		);
		$this->set('menu', $menu);
    }  
    
	
	function index() {        
		if($this->user['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed'));
	    } 		
	    $this->set('selected', 'Strategy');
		$this->set('user', $this->user);
		$this->set('strategy', $this->user['Army']['strategy']);
	}
	
	function setStrategy() {
		if($this->user['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed'));
	    } 		
		if (isset($this->data)){
			if ($this->data['Strategy']['strategy'] == 'Archer Protection')
				$strategy['Army']['strategy'] = "Archer Protection";
			if ($this->data['Strategy']['strategy'] == 'Reckless Abandon')
				$strategy['Army']['strategy'] = "Reckless Abandon";
			if ($this->data['Strategy']['strategy'] == 'Quick Retreat')
				$strategy['Army']['strategy'] = "Quick Retreat";
			if ($this->data['Strategy']['strategy'] == 'Anti-Cavalry')
				$strategy['Army']['strategy'] = "Anti-Cavalry";
			if ($this->data['Strategy']['strategy'] == 'Horde')
				$strategy['Army']['strategy'] = "Horde";
			if ($this->data['Strategy']['strategy'] == 'Energy Gathering')
				$strategy['Army']['strategy'] = "Energy Gathering";
			if ($this->data['Strategy']['strategy'] == 'Shield Line')
				$strategy['Army']['strategy'] = "Shield Line";
			if ($this->data['Strategy']['strategy'] == 'Far Fighting')
				$strategy['Army']['strategy'] = "Far Fighting";
			if ($this->data['Strategy']['strategy'] == 'Charging')
				$strategy['Army']['strategy'] = "Charging";
			if ($this->data['Strategy']['strategy'] == 'None')
				$strategy['Army']['strategy'] = "None";
			if ($this->data['Strategy']['strategy'] == 'Mystics') {
				$strategy['Army']['strategy'] = "Mystics";
				
				$max = max($this->user['Spell']['fiery_arrows'], $this->user['Spell']['hardened_armor'], $this->user['Spell']['enchanted_weapons']);
				if($this->user['Spell']['enchanted_weapons'] == $max){
					$this->user['Spell']['fiery_arrows'] = 0;
					$this->user['Spell']['hardened_armor'] = 0;
				} else if ($this->user['Spell']['hardened_armor'] == $max) {
					$this->user['Spell']['fiery_arrows'] = 0;
					$this->user['Spell']['enchanted_weapons'] = 0;
				} else {
					$this->user['Spell']['hardened_armor'] = 0;
					$this->user['Spell']['enchanted_weapons'] = 0;
				}
				$this->User->Spell->save($this->user['Spell']);
			}
				
				
			//$this->user['Army']['strategy'] = $strategy['Army']['strategy'];
			if($this->user['Army']['strategy'] == 'Mystics'){
				$strategy['Army']['strategy'] = 'Mystics';
				$strategy['Army']['next_strategy'] = $this->data['Strategy']['strategy'];
				$this->Session->setFlash('Your strategy will change to '.$strategy['Army']['next_strategy']. ' at the tick.' );
			} else { 
				$this->Session->setFlash('Your strategy has been set to ' . $strategy['Army']['strategy']);
			}
			
			$this->User->Army->id = $this->user['Army']['id'];
			$this->User->Army->save($strategy['Army']);
			
			$this->set('user', $this->user);
			
			$this->redirect(array('action'=>'index'));
		}
		$this->redirect(array('action'=>'index'));
	}
	
}
?>