<?php
class ReportsController extends AppController {    
	var $name = 'Reports';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	var $paginate = array(
		'limit' => 20,
		'order' => array(
			'Report.time' => 'desc'
		)    
	);
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		parent::beforeFilter();
		$this->Report->recursive = 2;
		$this->loadModel('Message');
		$this->loadModel('User');
        //$this->facebook_id = $this->facebookId; 
        parent::beforeFilter();
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account['Account']['id']))); 
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		
		$this->menu = array(
			array(
				'name' => 'All Reports',
				'link' => '/reports/index'
			),
			array(
				'name' => 'Attacks',
				'link' => '/reports/index/attacks'
			),
			array(
				'name' => 'Defends',
				'link' => '/reports/index/defends'
			),
			array(
				'name' => 'Trades',
				'link' => '/reports/index/trades'
			),
			array(
				'name' => 'Spells',
				'link' => '/reports/index/spells'
			),
			array(
				'name' => 'Miscellaneous',
				'link' => '/reports/index/misc'
			)
		);
		$this->set('menu', $this->menu);
    }  
    
    function view($id = null, $substr = null){
    	if($substr != substr( md5($id . '123489svk123xfjo4965oinlk1098345klj' ), 0, 15 )){
    		$this->setFlash('You do not have access to that report.');
    		$this->redirect(array('action'=>'index'));
    	} else {
    		$report = $this->Report->find('first', array('conditions' => array('Report.id' => $id)));
    		$this->set('report', $report);
    		$this->set('name', $this->User->getName($report['Report']['user_id']));
    	}
    	$this->set('user', $this->user);
    }
    
    function markAllRead(){
    	$reports = $this->Report->find('all', array('conditions' => array('Report.user_id' => $this->user['User']['id'], 'Report.is_Read' => 0)));
    	foreach($reports as $report){
    		$report['Report']['is_read'] = 1;
			$this->Report->save($report);
    	}
    	$this->Session->setFlash('All unread reports marked as read.');
    	$this->redirect(array('action'=>'index'));
    }
	
	function index($type = 'all') {        
		if($this->user['User']['id'] == 5){
			$this->user['User']['id'] = 5;
		}
		if($type != "all" && !$this->user['User']['premium']){
			$this->Session->setFlash('That is reserved for premium members only.');
			$type = "all";
		}
		
		if($type == "all"){
			$reports = $this->paginate('Report', array('Report.user_id' => $this->user['User']['id'], 'Report.is_deleted' => 0));
			$this->set('selected', "All Reports");
		} elseif($type == "attacks") {
			$type = array('Fail Attack', 'Success Attack');
			$reports = $this->paginate('Report', array('Report.user_id' => $this->user['User']['id'], 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('selected', "Attacks");
		} elseif($type == "defends") {
			$type = array('Fail Defend', 'Success Defend');
			$reports = $this->paginate('Report', array('Report.user_id' => $this->user['User']['id'], 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('selected', "Defends");
		} elseif($type == "trades") {
			$type = array('Accept Trade', 'Make Trade', 'Sent Trade', 'Received Trade');
			$reports = $this->paginate('Report', array('Report.user_id' => $this->user['User']['id'], 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('selected', "Trades");
		} elseif($type == "spells") {
			$type = array('Spell', 'Success Spell', 'Fail Spell');
			$reports = $this->paginate('Report', array('Report.user_id' => $this->user['User']['id'], 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('selected', "Spells");
		} elseif($type == "misc") {
			$type = array('Fail Defend', 'Success Defend', 'Spell', 'Success Spell', 'Fail Spell', 'Accept Trade', 'Make Trade', 'Sent Trade', 'Received Trade', 'Fail Attack', 'Success Attack');
			$reports = $this->paginate('Report', array('Report.user_id' => $this->user['User']['id'], 'Report.is_deleted' => 0, 'Report.type NOT' => $type));
			$this->set('selected', "Miscellaneous");
		}
		$this->set('reports', $reports);
		$this->set('user', $this->user);
		
		$this->user['User']['last_login'] = strtotime(date('r', time()));
	    $this->User->save($this->user, array('validate' => true, 'fieldList' => array('last_login')));
	}
	
	function ajaxView($id = null) {
		$report = $this->Report->find('first', array('conditions' => array('Report.user_id' => $this->user['User']['id'], 'Report.id' => $id)));
		if(!$report['Report']['is_read']){
			$report['Report']['is_read'] = 1;
			$this->Report->save($report);
		}
		$this->set('report', $report);
		$this->set('user', $this->user);
	}
	
	function markRead($id = null) {
		$report = $this->Report->find('first', array('conditions' => array('Report.user_id' => $this->user['User']['id'], 'Report.id' => $id)));
		if(!$report['Report']['is_read']){
			$report['Report']['is_read'] = 1;
			$this->Report->save($report);
		}
	}
}
?>