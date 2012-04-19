<?php
class RetaliationsController extends AppController {    
	var $name = 'Retaliations';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		parent::beforeFilter();
		$this->loadModel('User');
		$this->User->recursive = 2;
        //$this->facebook_id = $this->facebookId;
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
		if($this->user['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed' . $this->parms));
	    }
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
		$retaliations = $this->Retaliation->getAllRetaliations($this->user['User']['id']);
		foreach($retaliations as &$retaliation):
			$retaliation['Retaliation']['name'] = $this->User->getName($retaliation['Retaliation']['attacker_id']);
		endforeach;
		$this->set('retaliations', $retaliations);
		
		$retaliationsAgainst = $this->Retaliation->getAllRetaliationsAgainst($this->user['User']['id']);
		foreach($retaliationsAgainst as &$retaliation):
			$retaliation['Retaliation']['name'] = $this->User->getName($retaliation['Retaliation']['user_id']);
		endforeach;
		$this->set('retaliationsAgainst', $retaliationsAgainst);
		//debug($retaliationsAgainst);
		$this->set('selected', 'Retaliations');
		$this->set('user', $this->user);
	}
	
}