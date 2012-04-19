<?php
class SettingsController extends AppController {    
	var $name = 'Settings';
	var $uses = array('Setting');
	var $layout = 'redesign';
	
	function beforeFilter() {  
		parent::beforeFilter();
        $this->loadModel('User'); 
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->User->id = $this->user['User']['id'];
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		
		$this->menu = array(
			array(
				'name' => 'Settings',
				'link' => '/settings/'
			),
			array(
				'name' => 'Profile',
				'link' => '/users/profile'
			),
			//array(
			//	'name' => 'Notifications',
			//	'link' => '/settings/notifications'
			//),
			array(
				'name' => 'Restart?',
				'link' => '/users/confirmRestart'
			),
			array(
				'name' => 'IP Addresses',
				'link' => '/settings/logins'
			)
		);
		$this->set('menu', $this->menu);
    }  
    
    function logins() {
    	$this->loadModel('IpAddress');
    	$this->set('addresses', $this->IpAddress->getAll($this->user['User']['id']));
    	$this->set('selected', 'IP Addresses');
		$this->set('user', $this->user);
    }
    
	function index() {
		if(!empty($this->data)){
			$settings = $this->Setting->find('first', array('conditions' => array('Setting.user_id' => $this->user['User']['id'])));
			if(empty($settings)) {
				$this->data['Setting']['user_id'] = $this->user['User']['id'];
			} else {
				$this->data['Setting']['id'] = $settings['Setting']['id'];
			}
			$this->Setting->save($this->data);
		} else {
			$this->data = $this->Setting->find('first', array('conditions' => array('Setting.user_id' => $this->user['User']['id'])));
		}
		
		$this->set('selected', 'Settings');
		$this->set('user', $this->user);
	}
	
	function editSettings() {
		$settings = $this->Setting->find('first', array('conditions' => array('Setting.user_id')));
		
		
		$this->redirect('/settings');
	}
	
}