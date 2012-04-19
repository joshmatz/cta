<?php
class Report extends AppModel {    
	var $name = 'Report';
	
	function getNewReports($user = null) {
		return $this->find('all', array('conditions' => array('Report.user_id' => $user['User']['id'], 'Report.time >' => $user['User']['last_login']), 'order' => array('Report.time DESC')));
	}
	
	function getAllReports($user = null){
		return $this->find('all', array('conditions' => array('Report.user_id' => $user['User']['id']), 'order' => array('Report.time DESC')));
	}
	
	function getFiveReports($user = null){
		return $this->find('all', array('conditions' => array('Report.user_id' => $user['User']['id']), 'order' => array('Report.time DESC'), 'limit' => 5));
	}
	
	function countNewReports($user = null){
		return $this->find('count', array('conditions' => array('Report.user_id' => $user['User']['id'], 'Report.time >' => $user['User']['last_login']), 'order' => array('Report.time DESC')));
	}
	
	function checkNewReports($userID = null){
		return $this->find('count', array('conditions' => array('Report.user_id' => $userID, 'Report.is_read' => 0)));
	}
	
	function makeNewReport($user_id = null, $body = null, $type = null, $ipAddress = null, $makerId = null, $title = "[No Report Title]"){
		$this->User = Classregistry::init('User');
		$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'fields' => 'User.facebook_id'));
		if(!empty($user) && false){
			global $facebookAPI;
			if(!empty($facebookAPI)) { 
				//$facebookAPI->api_client->dashboard_incrementCount($user['User']['facebook_id']);
			}
			else {
				//$this->facebook = new Facebook('a095a0102375506b9b7d22ef45e5266a', '8269280af6c696d1872295cbb2d81d6b');  		
				//global $facebookAPI;		
				//$facebookAPI = $this->facebook;
				//$facebookAPI->api_client->dashboard_incrementCount($user['User']['facebook_id']);
			}
		}
		
		$report['user_id'] = $user_id;
		$report['body'] = $body;
		$report['time'] = strtotime(date('r', time()));
		$report['ip'] = $ipAddress;
		$report['type'] = $type;
		$report['maker_id'] = $makerId;
		$report['title'] = $title;
		
		$this->create();
		$this->save($report);
		
		return 1;
	}
}
?>