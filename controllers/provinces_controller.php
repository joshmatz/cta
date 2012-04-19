<?php
class ProvincesController extends AppController {    
	var $name = 'Provinces';
	var $uses = array();
	var $layout = 'redesign';
	var $facebook_id;
	var $user;	
	
	/********
	 * Execute this before every action
	 */
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
    
	/******
	 * Give the view basic data to make the table
	 */	
	function index() {  
		$this->set('members', $this->User->getProvinceMembers($this->user['User']['province'], $this->user['User']['province_number']));
		$this->set('user', $this->user);
	}
	
	/*
	 * Get specfic province, should let user use the url to do this too
	 */
	function find($name = null, $provinceNumber = null) {
		//debug($this->User->find('all'));
		$members = $this->User->getProvinceMembers($name, $provinceNumber);
		if(!isset($members[0])){
			$members[0]['User']['province_number'] = $provinceNumber;
			$members[0]['User']['province'] = $name;
			$members[0]['Spell'] = 0;
			$this->Session->setFlash('This province has no members!');
		}
		foreach($members as &$member):
			if($member['Spell']['shadow_hall'] > 0){
				unset($member);
			}
		endforeach;

		$this->set('members', $members);
		$this->set('user', $this->user);
	}
	
	function findRedirect() {
		if(!empty($this->data))          
			$this->redirect(array('action'=>'find/' . $this->data['Province']['name'] . '/' . $this->data['Province']['provinceNumber'] . $this->parms)); 
		else
			$this->redirect(array('action'=>'index' . $this->parms));
	}

}
?>