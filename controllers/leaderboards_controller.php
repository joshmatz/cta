<?php
class LeaderboardsController extends AppController {    
	var $name = 'Leaderboards';
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
        $this->loadModel('Alliance'); 
        $this->User->bindModel(array('hasOne'=>array('Alliance'=>array('foreignKey'=>false,
                        'conditions'=>array('Alliance.id = User.alliance_id')))), false); 
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->User->id = $this->user['User']['id'];
        //$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		
		$this->menu = array(
			array(
				'name' => 'Alliances',
				'link' => '/leaderboards/alliance'
			),
			array(
				'name' => 'Players',
				'link' => '/leaderboards/'
			),
			array(
				'name' => 'Player Kill Scores',
				'link' => '/leaderboards/killStats'
			),
			array(
				'name' => 'Player Honor',
				'link' => '/leaderboards/honor'
			)
		);
		$this->set('menu', $this->menu);
    }  
    
	/******
	 * Give the view basic data to make the table
	 */	
	function index($rank = 1, $limit = 20) {  
		
		
		$this->paginate = array('conditions' => array('User.rank >=' => $rank), 'limit' => $limit, 'order' => array('User.rank' => 'ASC'));
		$players = $this->paginate('User');
		$this->set(compact('players'));
		$this->set('selected', 'Players');
		$this->set('user', $this->user);
	}
	
	function indexRedirect() {
		if(!empty($this->data)){
			//debug($this->data);
			$this->redirect(array('action' => 'index/' . $this->data['Leaderboard']['rank']));
		} else {
			$this->Session->setFlash('There was a problem processing your request.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function indexSearch($name = null){
		
		$this->User->bindModel(array('hasOne'=>array('Alliance'=>array('foreignKey'=>false,
                        'conditions'=>array('Alliance.id = User.alliance_id')))), false); 
		$this->paginate = array('conditions' => array('User.ruler' => $name));
		$players = $this->paginate('User');
		$this->set(compact('players'));
		$this->set('selected', 'Players');
		$this->set('user', $this->user);
	}
	
	function indexSearchRedirect() {
		if(!empty($this->data)){
			//debug($this->data);
			$this->redirect(array('action' => 'indexSearch/' . $this->data['Leaderboard']['name']));
		} else {
			$this->Session->setFlash('There was a problem processing your request.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function allianceReports() {
		$submenu = array(
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
		$this->set('submenu', $submenu);
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
		$report = $this->Report->find('first', array('Report.user_id' => $this->user['User']['id'], 'Report.id' => $id));
		if(!$report['Report']['is_read']){
			$report['Report']['is_read'] = 1;
			$this->Report->save($report);
		}
		$this->set('report', $report);
	}
	
	function alliance($type = null, $rank = 1, $limit = 20){
		$submenu = array(
			array(
				'name' => 'Alliance Strength',
				'link' => '/leaderboards/alliance'
			),
			array(
				'name' => 'Offensive',
				'link' => '/leaderboards/alliance/oks'
			),
			array(
				'name' => 'Defensive',
				'link' => '/leaderboards/alliance/dks'
			),
			array(
				'name' => 'Total',
				'link' => '/leaderboards/alliance/ks'
			),
			array(
				'name' => 'Honor',
				'link' => '/leaderboards/alliance/honor'
			)
		);
		$this->set('submenu', $submenu);
		$this->set('selected', 'Alliances');
		
		
		if($type == 'ks') {
			$this->set('subselected', 'Total');
			$this->paginate = array('conditions' => array('Alliance.ks_rank >=' => $rank ), 'limit' => $limit, 'order' => array('Alliance.ks_rank' => 'ASC'));
		} else if($type == 'oks') {
			$this->set('subselected', 'Offensive');
			$this->paginate = array('conditions' => array('Alliance.oks_rank >=' => $rank ), 'limit' => $limit, 'order' => array('Alliance.oks_rank' => 'ASC'));
		} else if($type == 'dks') {
			$this->set('subselected', 'Defensive');
			$this->paginate = array('conditions' => array('Alliance.dks_rank >=' => $rank ), 'limit' => $limit, 'order' => array('Alliance.dks_rank' => 'ASC'));
		} else if($type == 'honor') {
			$this->set('subselected', 'Honor');
			$this->paginate = array('conditions' => array('Alliance.honor_rank >=' => $rank ), 'limit' => $limit, 'order' => array('Alliance.honor_rank' => 'ASC'));
		} else {
			$this->set('subselected', 'Alliance Strength');
			$this->paginate = array('conditions' => array('Alliance.score_rank >=' => $rank ), 'limit' => $limit, 'order' => array('Alliance.score_rank' => 'ASC'));
		}
		$alliances = $this->paginate('Alliance');
		
		$this->set('userAlliance', $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $this->user['User']['alliance_id']))));
		$this->set('hiddenType', $type);
		$this->set(compact('alliances'));
		
		$this->set('user', $this->user);
	}
	
	function allianceSearch($type = null, $name = null, $limit = 20){
		$submenu = array(
			array(
				'name' => 'Alliance Strength',
				'link' => '/leaderboards/alliance'
			),
			array(
				'name' => 'Offensive',
				'link' => '/leaderboards/alliance/oks'
			),
			array(
				'name' => 'Defensive',
				'link' => '/leaderboards/alliance/dks'
			),
			array(
				'name' => 'Total',
				'link' => '/leaderboards/alliance/ks'
			),
			array(
				'name' => 'Honor',
				'link' => '/leaderboards/alliance/honor'
			)
		);
		$this->set('submenu', $submenu);
		$this->set('selected', 'Alliances');
		
		if($type == 'ks') {
			$this->set('subselected', 'Total');
			$this->paginate = array('conditions' => array('Alliance.short_name' => $name), 'limit' => $limit, 'order' => array('Alliance.score_rank' => 'ASC'));
		} else if($type == 'oks') {
			$this->set('subselected', 'Offensive');
			$this->paginate = array('conditions' => array('Alliance.short_name' => $name), 'limit' => $limit, 'order' => array('Alliance.score_rank' => 'ASC'));
		} else if($type == 'dks') {
			$this->set('subselected', 'Defensive');
			$this->paginate = array('conditions' => array('Alliance.short_name' => $name), 'limit' => $limit, 'order' => array('Alliance.score_rank' => 'ASC'));
		} else if($type == 'honor') {
			$this->set('subselected', 'Honor');
			$this->paginate = array('conditions' => array('Alliance.short_name' => $name), 'limit' => $limit, 'order' => array('Alliance.score_rank' => 'ASC'));
		} else {
			$this->set('subselected', 'Alliance Strength');
			$this->paginate = array('conditions' => array('Alliance.short_name' => $name), 'limit' => $limit, 'order' => array('Alliance.score_rank' => 'ASC'));
		}
		$alliances = $this->paginate('Alliance');
		
		$this->set('userAlliance', $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $this->user['User']['alliance_id']))));
		$this->set('hiddenType', $type);
		$this->set(compact('alliances'));
		
		$this->set('user', $this->user);
	}
	
	function allianceRedirect() {
		if(!empty($this->data)){
			//debug($this->data);
			
			$this->redirect(array('action' => 'alliance/' . $this->data['Leaderboard']['type'] . '/' . $this->data['Leaderboard']['rank']));
		} else {
			$this->Session->setFlash('There was a problem processing your request.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function allianceSearchRedirect() {
		if(!empty($this->data)){
			//debug($this->data);
			$this->redirect(array('action' => 'allianceSearch/' . $this->data['Leaderboard']['type'] . '/' . $this->data['Leaderboard']['short_name']));
		} else {
			$this->Session->setFlash('There was a problem processing your request.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function honor($rank = 1, $limit = 20) {
		$this->set('selected', 'Player Honor');
		$this->paginate = array('conditions' => array('User.honor_rank >=' => $rank), 'limit' => $limit, 'order' => array('User.honor_rank' => 'ASC'));
		$honor = $this->paginate('User');
		$this->set(compact('honor'));
		$this->set('selected', 'Player Honor');
		$this->set('user', $this->user);
	}
	
	function honorRedirect() {
		if(!empty($this->data)){
			//debug($this->data);
			$this->redirect(array('action' => 'honor/' . $this->data['Leaderboard']['rank']));
		} else {
			$this->Session->setFlash('There was a problem processing your request.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function honorSearch($name = null){
		$this->paginate = array('conditions' => array('User.ruler' => $name));
		$honor = $this->paginate('User');
		$this->set(compact('honor'));
		$this->set('selected', 'Player Honor');
		$this->set('user', $this->user);
	}
	
	function honorSearchRedirect() {
		if(!empty($this->data)){
			//debug($this->data);
			$this->redirect(array('action' => 'honorSearch/' . $this->data['Leaderboard']['name']));
		} else {
			$this->Session->setFlash('There was a problem processing your request.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	/*
	 * Get specfic province, should let user use the url to do this too
	 */
	function findFriends($rank = 1, $limit = 40){
		App::import('Lib', 'Facebook.FB');
		$friendIds = FB::api('/me/friends');
		foreach($friendIds['data'] as $friendId):
			$friends[] = $friendId['id'];
		endforeach;
		$friends[] = $this->user['User']['facebook_id'];
		$this->set('rank', $rank);
		$this->set('friends', $this->User->getRankedFriends($friends));
		$this->set('user', $this->user);
		$this->set('selected', 'Friends');
	}
	
	function killStats($type = "Offense", $rank = 1, $limit = 20) {
		$submenu = array(
			array(
				'name' => 'Offensive',
				'link' => '/leaderboards/killStats/Offense'
			),
			array(
				'name' => 'Defensive',
				'link' => '/leaderboards/killStats/Defense'
			),
			array(
				'name' => 'Total',
				'link' => '/leaderboards/killStats/Total'
			)
		);
		$this->set('selected', 'Player Kill Scores');
		$this->set('submenu', $submenu);
		$this->loadModel('KillStat');
		if($type == 'Offense') {
			$this->set('subselected', 'Offensive');
			$this->paginate = array('conditions' => array('KillStat.rank >=' => $rank, 'KillStat.type LIKE' => "Offense"), 'limit' => $limit, 'order' => array('KillStat.rank' => 'ASC'));
			$userKillStats = $this->KillStat->find('first', array('conditions' => array('KillStat.user_id' => $this->user['User']['id'], 'KillStat.type' => "Offense")));
			$userKillStats['KillStat']['name'] = $this->User->getName($userKillStats['KillStat']['user_id']);
			$this->set(compact('userKillStats'));
		} else if($type == 'Total') {
			$this->set('subselected', 'Total');
			$this->paginate = array('conditions' => array('KillStat.rank >=' => $rank, 'KillStat.type LIKE' => "Total"), 'limit' => $limit, 'order' => array('KillStat.rank' => 'ASC'));
			$userKillStats = $this->KillStat->find('first', array('conditions' => array('KillStat.user_id' => $this->user['User']['id'], 'KillStat.type' => "Total")));
			$userKillStats['KillStat']['name'] = $this->User->getName($userKillStats['KillStat']['user_id']);
			$this->set(compact('userKillStats'));
		} else {
		$this->set('subselected', 'Defensive');
			$this->paginate = array('conditions' => array('KillStat.rank >=' => $rank, 'KillStat.type LIKE' => "Defense"), 'limit' => $limit, 'order' => array('KillStat.rank' => 'ASC'));
			$userKillStats = $this->KillStat->find('first', array('conditions' => array('KillStat.user_id' => $this->user['User']['id'], 'KillStat.type' => "Defense")));
		$userKillStats['KillStat']['name'] = $this->User->getName($userKillStats['KillStat']['user_id']);
		$this->set(compact('userKillStats'));
		}
		$killstats = $this->paginate('KillStat');
		foreach($killstats as &$stats):
			$stats['KillStat']['name'] = $this->User->getName($stats['KillStat']['user_id']);
		endforeach;
		
		$this->set('hiddenType', $type);
		$this->set(compact('killstats'));
		
		$this->set('user', $this->user);
	}
	
	function killStatsRedirect() {
		if(!empty($this->data)){
			//debug($this->data);
			$this->redirect(array('action' => 'killStats/' . $this->data['Leaderboard']['type'] . '/' . $this->data['Leaderboard']['rank']));
		} else {
			$this->Session->setFlash('There was a problem processing your request.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function killStatsSearch($type = "Offense", $rank = 1, $limit = 20) {
		$submenu = array(
			array(
				'name' => 'Offensive',
				'link' => '/leaderboards/killStats/Offense'
			),
			array(
				'name' => 'Defensive',
				'link' => '/leaderboards/killStats/Defense'
			),
			array(
				'name' => 'Total',
				'link' => '/leaderboards/killStats/Total'
			)
		);
		$this->set('selected', 'Player Kill Scores');
		$this->set('submenu', $submenu);
		$this->loadModel('KillStat');
		if($type == 'Offense') {
			$this->set('subselected', 'Offensive');
		} else if($type == 'Total') {
			$this->set('subselected', 'Total');
			$this->paginate = array('conditions' => array('KillStat.rank >=' => $rank, 'KillStat.type LIKE' => "Total"), 'limit' => $limit, 'order' => array('KillStat.rank' => 'ASC'));
			$userKillStats = $this->KillStat->find('first', array('conditions' => array('KillStat.user_id' => $this->user['User']['id'], 'KillStat.type' => "Total")));
			$userKillStats['KillStat']['name'] = $this->User->getName($userKillStats['KillStat']['user_id']);
			$this->set(compact('userKillStats'));
		}  else {
			$this->set('subselected', 'Defensive');
			$this->paginate = array('conditions' => array('KillStat.rank >=' => $rank, 'KillStat.type LIKE' => "Defense"), 'limit' => $limit, 'order' => array('KillStat.rank' => 'ASC'));
			$userKillStats = $this->KillStat->find('first', array('conditions' => array('KillStat.user_id' => $this->user['User']['id'], 'KillStat.type' => "Defense")));
			$userKillStats['KillStat']['name'] = $this->User->getName($userKillStats['KillStat']['user_id']);
			$this->set(compact('userKillStats'));
		}
		$killstats = $this->paginate('KillStat');
		foreach($killstats as &$stats):
			$stats['KillStat']['name'] = $this->User->getName($stats['KillStat']['user_id']);
		endforeach;
		
		$this->set('hiddenType', $type);
		$this->set(compact('killstats'));
		
		$this->set('user', $this->user);
		
	}
	
	function killStatsSearchRedirect() {
		if(!empty($this->data)){
			//debug($this->data);
			$this->redirect(array('action' => 'killStats/' . $this->data['Leaderboard']['type']));
		} else {
			$this->Session->setFlash('There was a problem processing your request.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	
	function provinces() {
		$this->set('selected', 'Provinces');
		$rank = 1;
		$this->set('rank', $rank);
		$this->set('provinces', $this->User->getRankedProvinces());
	    $this->set('user', $this->user);
	}
	
	function findProvinces($rank = 1, $limit = 20) {
		$this->set('selected', 'Provinces');
		if($rank < 1)
			$rank = 1;
		$this->set('rank', $rank);
		$this->set('provinces', $this->User->getRankedProvinces($rank, $limit));
		$this->set('user', $this->user);
	}
	
	function findProvincesRedirect() {
		if(!empty($this->data))          
			$this->redirect(array('action'=>'findProvinces/' . $this->data['Leaderboard']['rank'])); 
	}
	
	function autoComplete() {
		//debug($this->data);
		$this->set('receivers', Classregistry::init('User')->find('all', array('conditions' => array('User.ruler LIKE' => $this->params['url']['q'] . '%'), 'fields' => array('ruler'), 'limit' => 20)));
		$this->layout = 'ajax';
	}
	
	function allianceAutoComplete() {
		$this->set('receivers', Classregistry::init('Alliance')->find('all', array('conditions' => array('Alliance.short_name LIKE' => $this->params['url']['q'] . '%'), 'fields' => array('short_name'), 'limit' => 20)));
		$this->layout = 'ajax';
	}

}
?>