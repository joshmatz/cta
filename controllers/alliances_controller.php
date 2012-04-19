<?php
class AlliancesController extends AppController {    
	var $name = '/alliances';
	var $layout = 'redesign';
	var $uses = array('Alliance');
	
	function beforeFilter() {  
		parent::beforeFilter();
		$this->loadModel('User');
		$this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id)));
		
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		
		if($this->user['User']['admin_status'] == "admin"){
			$this->Alliance->updateAllianceStats();
		}
		
		if($this->user['User']['alliance_id'] != 0) {
			$this->menu = array(
				array(
					'name' => 'Overview',
					'link' => '/alliances'
				),
				array(
					'name' => 'Profile',
					'link' => '/alliances/profile'
				),
				//array(
				//	'name' => 'Bank',
				//	'link' => '/alliances/bank'
				//),
				array(
					'name' => 'Alliance Reports',
					'link' => '/alliances/allianceReports'
				),
				array(
					'name' => 'Diplomacy',
					'link' => '/alliances/diplomacy'
				),
				array(
					'name' => 'Forum',
					'link' => '/allianceForums'
				),
				array(
					'name' => 'Retaliations',
					'link' => '/alliances/retaliations'
				)
			);
			if($this->user['User']['alliance_rank'] != 'admin'){
				// Disabled with 3 alliances
				$this->menu[] = array('name' => 'Leave Alliance', 'link' => '/alliances/leave');
			} else {
				$this->menu[] = array('name' => 'Sent Invitations', 'link' => '/alliances/invite');
				$this->menu[] = array('name' => 'Alliance Settings', 'link' => '/alliances/settings');
				$this->menu[] = array('name' => 'Disband Alliance', 'link' => '/alliances/disband');
			}
		} else {
			$this->menu = array(
				array(
					'name' => 'Create or Join an Alliance',
					'link' => '/alliances'
				),
			);
		}
		$this->set('menu', $this->menu);
    } 
    
    function bank() {
    	if(!empty($this->user['User']['alliance_id'])){
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	$this->set('alliance', $alliance);
	    	
    	} else {
    		$this->Session->setFlash('You must be in an alliance to view its bank.');
    		$this->redirect('/alliances');
    	}
    	$this->set('selected', 'Bank');
    	$this->set('user', $this->user);
    }
    
    function allianceReports($type = 'all') {
    	$this->loadModel('Report');
    	$this->paginate = array(
			'limit' => 20,
			'order' => array(
				'Report.time' => 'desc'
			)    
		);
    	$submenu = array(
			array(
				'name' => 'All Reports',
				'link' => '/alliances/allianceReports'
			),
			array(
				'name' => 'Attacks',
				'link' => '/alliances/allianceReports/attacks'
			),
			array(
				'name' => 'Defends',
				'link' => '/alliances/allianceReports/defends'
			),
			array(
				'name' => 'Trades',
				'link' => '/alliances/allianceReports/trades'
			),
			array(
				'name' => 'Spells',
				'link' => '/alliances/allianceReports/spells'
			),
			array(
				'name' => 'Miscellaneous',
				'link' => '/alliances/allianceReports/misc'
			)
		);
		
   		if($type != "all" && $type != "attacks" && $type != "defends" && !$this->user['User']['premium']){
			$this->Session->setFlash('That is reserved for premium members only.');
			$type = "all";
		}
		
		$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
		foreach($alliance['Members'] as $member):
			$members[] = $member['User']['id'];
		endforeach;
		
		if($type == "all"){
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0));
			$this->set('subselected', "All Reports");
		} elseif($type == "attacks") {
			$type = array('Fail Attack', 'Success Attack');
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('subselected', "Attacks");
		} elseif($type == "defends") {
			$type = array('Fail Defend', 'Success Defend');
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('subselected', "Defends");
		} elseif($type == "trades") {
			$type = array('Accept Trade', 'Make Trade', 'Sent Trade', 'Received Trade');
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('subselected', "Trades");
		} elseif($type == "spells") {
			$type = array('Spell', 'Success Spell', 'Fail Spell');
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('subselected', "Spells");
		} elseif($type == "misc") {
			$type = array('Fail Defend', 'Success Defend', 'Spell', 'Success Spell', 'Fail Spell', 'Accept Trade', 'Make Trade', 'Sent Trade', 'Received Trade', 'Fail Attack', 'Success Attack');
			$reports = $this->paginate('Report', array('Report.user_id' => $this->user['User']['id'], 'Report.is_deleted' => 0, 'Report.type NOT' => $type));
			$this->set('subselected', "Miscellaneous");
		}
		$members = array();
		foreach($alliance['Members'] as $member):
			$members[$member['User']['id']] = '<a href="/users/profile/' . $member['User']['id'] . '">' . $member['User']['ruler'] . '</a>';
		endforeach;
		
		$this->set('members', $members);
		$this->set('reports', $reports);
		$this->set('user', $this->user);
		$this->set('selected', 'Alliance Reports');
		$this->set('submenu', $submenu);
		$this->set('alliance', $alliance);
	}
	
	function ajaxAllianceReports($type = 'all') {
    	$this->loadModel('Report');
    	$this->paginate = array(
			'limit' => 20,
			'order' => array(
				'Report.time' => 'desc'
			)    
		);
    	$submenu = array(
			array(
				'name' => 'All Reports',
				'link' => '/alliances/allianceReports'
			),
			array(
				'name' => 'Attacks',
				'link' => '/alliances/allianceReports/attacks'
			),
			array(
				'name' => 'Defends',
				'link' => '/alliances/allianceReports/defends'
			),
			array(
				'name' => 'Trades',
				'link' => '/alliances/allianceReports/trades'
			),
			array(
				'name' => 'Spells',
				'link' => '/alliances/allianceReports/spells'
			),
			array(
				'name' => 'Miscellaneous',
				'link' => '/alliances/allianceReports/misc'
			)
		);
		
   		if($type != "all" && $type != "attacks" && $type != "defends" && !$this->user['User']['premium']){
			$this->Session->setFlash('That is reserved for premium members only.');
			$type = "all";
		}
		
		$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
		foreach($alliance['Members'] as $member):
			$members[] = $member['User']['id'];
		endforeach;
		
		if($type == "all"){
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0));
			$this->set('subselected', "All Reports");
		} elseif($type == "attacks") {
			$type = array('Fail Attack', 'Success Attack');
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('subselected', "Attacks");
		} elseif($type == "defends") {
			$type = array('Fail Defend', 'Success Defend');
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('subselected', "Defends");
		} elseif($type == "trades") {
			$type = array('Accept Trade', 'Make Trade', 'Sent Trade', 'Received Trade');
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('subselected', "Trades");
		} elseif($type == "spells") {
			$type = array('Spell', 'Success Spell', 'Fail Spell');
			$reports = $this->paginate('Report', array('Report.user_id' => $members, 'Report.is_deleted' => 0, 'Report.type' => $type));
			$this->set('subselected', "Spells");
		} elseif($type == "misc") {
			$type = array('Fail Defend', 'Success Defend', 'Spell', 'Success Spell', 'Fail Spell', 'Accept Trade', 'Make Trade', 'Sent Trade', 'Received Trade', 'Fail Attack', 'Success Attack');
			$reports = $this->paginate('Report', array('Report.user_id' => $this->user['User']['id'], 'Report.is_deleted' => 0, 'Report.type NOT' => $type));
			$this->set('subselected', "Miscellaneous");
		}
		$members = array();
		foreach($alliance['Members'] as $member):
			$members[$member['User']['id']] = '<a href="/users/profile/' . $member['User']['id'] . '">' . $member['User']['ruler'] . '</a>';
		endforeach;
		
		$this->set('members', $members);
		$this->set('reports', $reports);
		$this->set('user', $this->user);
		$this->set('selected', 'Alliance Reports');
		$this->set('submenu', $submenu);
		$this->set('alliance', $alliance);
	}

	
	function ajaxView($id = null) {
		$this->loadModel('Report');
		$report = $this->Report->find('first', array('conditions' => array('Report.id' => $id)));
		$member = $this->User->find('first', array('conditions' => array('User.id' => $report['Report']['user_id']), 'fields' => array('alliance_id')));
		if($member['User']['alliance_id'] != $this->user['User']['alliance_id']){
			$report['Report']['body'] = 'You accessed this report incorrectly.';
		}
		$this->set('report', $report);
	}
    
    function diplomacy() {
    	if(!empty($this->user['User']['alliance_id'])){
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	$this->set('alliance', $alliance);
	    	
	    	$this->loadModel('AllianceFriend');
	    	$allies = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
	    	foreach($allies as &$ally):
	    		$ally['AllianceFriend']['Alliance'] = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $ally['AllianceFriend']['friend_id'])));
	    	endforeach;
	    	$this->set('allies', $allies);
    	} else {
    		$this->Session->setFlash('You must be in an alliance to disband one.');
    		$this->redirect('/alliances');
    	}
    	$this->set('selected', 'Diplomacy');
    	$this->set('user', $this->user);
    }
    
    function removeFriend($id = null) {
    	if($this->user['User']['alliance_rank'] == "admin") {
    		$this->loadModel('AllianceFriend');
    		$friend = $this->AllianceFriend->find('first', array('conditions' => array('AllianceFriend.friend_id' => $id, 'AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
    		if(empty($friend)) {
    			$this->set('error', "That is not a valid relationship to reset.");
    		} else {
    			$setDate = new DateTime($friend['AllianceFriend']['date']);
    			$now = new DateTime("now");
    			$difference = $setDate->diff($now);
    			$days = $difference->format('%d');
    			if($friend['AllianceFriend']['type'] == "ally" || $friend['AllianceFriend']['type'] == "enemy"){
	    			if($days > 7){
	    				$this->AllianceFriend->delete($friend['AllianceFriend']['id']);
	    				$friend = $this->AllianceFriend->find('first', array('conditions' => array('AllianceFriend.friend_id' => $this->user['User']['alliance_id'], 'AllianceFriend.alliance_id' => $id)));
	    				$this->AllianceFriend->delete($friend['AllianceFriend']['id']);

	    				$members = $this->User->find('all', array('conditions' => array('User.alliance_id' => $id), array('fields' => 'User.id')));
		    			$friendMembers = $this->User->find('all', array('conditions' => array('User.alliance_id' => $this->user['User']['alliance_id']), array('fields' => 'User.id')));
		    			if($friend['AllianceFriend']['type'] == "ally"){
			    			foreach($members as $member):
				    			$this->Report->makeNewReport(
				    				$member['User']['id'], 
				    				$this->user['User']['ruler'] . " has desolved the alliance with " . $found['Alliance']['short_name'] . "! You are now free to engage these players in combat.",
				    				"Misc", 
				    				$_SERVER['REMOTE_ADDR'], 
				    				$this->user['User']['id'], 
									$alliance['Alliance']['short_name'] . " has desolved the alliance with " . $found['Alliance']['short_name'] . "!"
				    			);
			    			endforeach;
			    			foreach($friendMembers as $member):
				    			$this->Report->makeNewReport(
				    				$member['User']['id'], 
				    				$this->user['User']['ruler'] . " has desolved the alliance with " . $found['Alliance']['short_name'] . "! you are now free to engage these players in combat.", 
				    				"Misc", 
				    				$_SERVER['REMOTE_ADDR'], 
				    				$this->user['User']['id'], 
									$alliance['Alliance']['short_name'] . " has desolved the alliance with " . $found['Alliance']['short_name'] . "!"
				    			);
			    			endforeach;
		    			} else {
		    				foreach($members as $member):
				    			$this->Report->makeNewReport(
				    				$member['User']['id'], 
				    				$this->user['User']['ruler'] . " has cancelled the enemy status of " . $found['Alliance']['short_name'] . "! You no longer receive additional spoils for attacking these players.",
				    				"Misc", 
				    				$_SERVER['REMOTE_ADDR'], 
				    				$this->user['User']['id'], 
									$alliance['Alliance']['short_name'] . " has cancelled the enemy status of " . $found['Alliance']['short_name'] . "!"
				    			);
			    			endforeach;
			    			foreach($friendMembers as $member):
				    			$this->Report->makeNewReport(
				    				$member['User']['id'], 
				    				$this->user['User']['ruler'] . " has has cancelled the enemy status of " . $found['Alliance']['short_name'] . "! You no longer receive additional spoils for attacking these players.", 
				    				"Misc", 
				    				$_SERVER['REMOTE_ADDR'], 
				    				$this->user['User']['id'], 
									$alliance['Alliance']['short_name'] . " has cancelled the enemy status of " . $found['Alliance']['short_name'] . "!"
				    			);
			    			endforeach;
		    			}
	    			} else {
	    				$this->set('error', "You cannot remove that relationship until 7 days has passed. You are on day " . $days);
	    			}
    			} else {
    				$this->AllianceFriend->delete($friend['AllianceFriend']['id']);
    			}
	    		
    		}
    	} else {
    		$this->Session->setFlash('You do not have permission to do that.');
    		$this->redirect('/alliances');
    	}
    	
    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
    	$this->set('alliance', $alliance);
    	
    	$allies = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
    	foreach($allies as &$ally):
    		$ally['AllianceFriend']['Alliance'] = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $ally['AllianceFriend']['friend_id'])));
    	endforeach;
    	$this->set('allies', $allies);
    	$this->set('user', $this->user);
    }
    
    function addAlly() {
    	if(!empty($this->data)){
    		$this->loadModel('AllianceFriend');
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	$this->set('alliance', $alliance);

	    	$found = $this->Alliance->find('first', array('conditions' => array('Alliance.short_name' => $this->data['Alliance']['short_name']), 'fields' => 'Alliance.id, Alliance.short_name'));
	    	if(!empty($found)){
	    		if($found['Alliance']['id'] != $alliance['Alliance']['id']){
		    		if($this->AllianceFriend->find('first', array('conditions' => array('AllianceFriend.friend_id' => $found['Alliance']['id'], 'AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])))) {
		    			$this->set('error', "You have already set a diplomatic stance with that alliance.");
		    		} else {
		    			$this->loadModel('DiplomacyRequest');
		    			$requests = $this->DiplomacyRequest->find('first', array('conditions' => array('DiplomacyRequest.alliance_id' => $found['Alliance']['id'], 'DiplomacyRequest.requester_id' => $this->user['User']['alliance_id'])));
			    		if($requests) {
			    			$this->set('error', "You have already sent a request to that alliance.");

			    		} else {
				    		$ally['DiplomacyRequest']['requester_id'] = $this->user['User']['alliance_id'];
				    		$ally['DiplomacyRequest']['alliance_id'] = $found['Alliance']['id'];
				    		$ally['DiplomacyRequest']['type'] = "ally";
				    		$ally['DiplomacyRequest']['date'] = date ("Y-m-d H:m:s");
				    		$this->DiplomacyRequest->save($ally);
				    		$leader = $this->User->find('first', array('conditions' => array('User.alliance_id' => $found['Alliance']['id'], 'User.alliance_rank' => 'admin'), 'fields' => 'User.id'));
				    		$this->Report->makeNewReport(
			    				$leader['User']['id'], 
			    				$this->user['User']['ruler'] . " has requested your Alliance be an ally with " . $alliance['Alliance']['short_name'] . 
			    				"You can choose to <a href=\"/alliances/ally/accept/" . $this->DiplomacyRequest->getInsertID() . '">accept</a> the request, or <a href="/alliances/ally/deny/' . $this->DiplomacyRequest->getInsertID() . '">deny</a> it. The request will expire after 3 days',
			    				"Alliance Request",
			    				$_SERVER['REMOTE_ADDR'], 
			    				$this->user['User']['id'], 
								$alliance['Alliance']['short_name'] . " requests to be an Ally!"
			    			);
			    			$this->set('success', "You've successfully sent your request to " . $found['Alliance']['short_name'] . ".");
			    		}
		    		}
	    		} else {
	    			$this->set('error', 'You cannot do that to your own alliance.');
	    		}
	    	} else {
	    		$this->set('error', "Alliance not found.");
	    	}
	    	unset($ally);

	    	$allies = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
	    	foreach($allies as &$ally):
	    		$ally['AllianceFriend']['Alliance'] = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $ally['AllianceFriend']['friend_id'])));
	    	endforeach;
	    	$this->set('allies', $allies);
    	} else {
    		$this->Session->setFlash('Incorrect data.');
    		$this->redirect('/alliances');
    	}
    	
    	$this->set('user', $this->user);
    }
    
    function addNap() {
    	if(!empty($this->data)){
    		$this->loadModel('AllianceFriend');
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	$this->set('alliance', $alliance);

	    	$found = $this->Alliance->find('first', array('conditions' => array('Alliance.short_name' => $this->data['Alliance']['short_name']), 'fields' => 'Alliance.id'));
	    	if(!empty($found)){
	    		if($found['Alliance']['id'] != $alliance['Alliance']['id']){
		    		if($this->AllianceFriend->find('first', array('conditions' => array('AllianceFriend.friend_id' => $found['Alliance']['id'], 'AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])))) {
		    			$this->set('error', "You have already set a diplomatic stance with that alliance.");
		    		} else {
			    		$ally['AllianceFriend']['alliance_id'] = $this->user['User']['alliance_id'];
			    		$ally['AllianceFriend']['friend_id'] = $found['Alliance']['id'];
			    		$ally['AllianceFriend']['type'] = "nap";
			    		$ally['AllianceFriend']['date'] = date ("Y-m-d H:m:s");
			    		$this->AllianceFriend->save($ally);
		    		}
	    		} else {
	    			$this->set('error', 'You cannot do that to your own alliance.');
	    		}
	    	} else {
	    		$this->set('error', "Alliance not found.");
	    	}
	    	unset($ally);

	    	$allies = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
	    	foreach($allies as &$ally):
	    		$ally['AllianceFriend']['Alliance'] = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $ally['AllianceFriend']['friend_id'])));
	    	endforeach;
	    	$this->set('allies', $allies);
    	} else {
    		$this->Session->setFlash('Incorrect data.');
    		$this->redirect('/alliances');
    	}
    	
    	$this->set('user', $this->user);
    }
    
    function addEnemy() {
    	if(!empty($this->data)){
    		$this->loadModel('AllianceFriend');
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	$this->set('alliance', $alliance);

	    	$found = $this->Alliance->find('first', array('conditions' => array('Alliance.short_name' => $this->data['Alliance']['short_name']), 'fields' => 'Alliance.id, Alliance.short_name'));
	    	if(!empty($found)){
	    		if($found['Alliance']['id'] != $alliance['Alliance']['id']){
		    		if($this->AllianceFriend->find('first', array('conditions' => array('AllianceFriend.friend_id' => $found['Alliance']['id'], 'AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])))) {
		    			$this->set('error', "You have already set a diplomatic stance with that alliance.");
		    		} else {
		    			$this->loadModel('Report');
		    			$members = $this->User->find('all', array('conditions' => array('User.alliance_id' => $found['Alliance']['id']), array('fields' => 'User.id')));
		    			$friendMembers = $this->User->find('all', array('conditions' => array('User.alliance_id' => $this->user['User']['alliance_id']), array('fields' => 'User.id')));
		    			foreach($members as $member):
			    			$this->Report->makeNewReport(
			    				$member['User']['id'], 
			    				$this->user['User']['ruler'] . " marked " . $found['Alliance']['short_name'] . " as an enemy! The two alliances will be in conflict for at least 7 days. Spoils are increased 10%.", 
			    				"Misc", 
			    				$_SERVER['REMOTE_ADDR'], 
			    				$this->user['User']['id'], 
								$alliance['Alliance']['short_name'] . " marked " . $found['Alliance']['short_name'] . " as an enemy!"
			    			);
		    			endforeach;
		    			foreach($friendMembers as $member):
			    			$this->Report->makeNewReport(
			    				$member['User']['id'], 
			    				$this->user['User']['ruler'] . " marked " . $found['Alliance']['short_name'] . " as an enemy! The two alliances will be in conflict for at least 7 days. Spoils are increased 10%.", 
			    				"Misc", 
			    				$_SERVER['REMOTE_ADDR'], 
			    				$this->user['User']['id'], 
								$alliance['Alliance']['short_name'] . " marked " . $found['Alliance']['short_name'] . " as an enemy!"
			    			);
		    			endforeach;
			    		$ally['AllianceFriend']['alliance_id'] = $this->user['User']['alliance_id'];
			    		$ally['AllianceFriend']['friend_id'] = $found['Alliance']['id'];
			    		$ally['AllianceFriend']['type'] = "enemy";
			    		$ally['AllianceFriend']['date'] = date ("Y-m-d H:m:s");
			    		$this->AllianceFriend->save($ally);
			    		$ally['AllianceFriend']['alliance_id'] = $found['Alliance']['id'];
			    		$ally['AllianceFriend']['friend_id'] = $this->user['User']['alliance_id'];
			    		$this->AllianceFriend->save($ally);
		    		}
	    		} else {
	    			$this->set('error', 'You cannot do that to your own alliance.');
	    		}
	    	} else {
	    		$this->set('error', "Alliance not found.");
	    	}
	    	unset($ally);

	    	$allies = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
	    	foreach($allies as &$ally):
	    		$ally['AllianceFriend']['Alliance'] = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $ally['AllianceFriend']['friend_id'])));
	    	endforeach;
	    	$this->set('allies', $allies);
    	} else {
    		$this->Session->setFlash('Incorrect data.');
    		$this->redirect('/alliances');
    	}
    	
    	$this->set('user', $this->user);
    }
    
    function profile($id = null){
    	if(!isset($id)){
    		if($this->user['User']['alliance_id']) {
    			$id = $this->user['User']['alliance_id'];
    		} else {
    			$this->redirect('/alliances');
    		}
    	} 
    	$alliance = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $id)));
    	if(empty($alliance)){
    		$this->Session->setFlash('That is an invalid alliance.');
    		$this->redirect('/alliances');
    	}
    	$alliance['Alliance']['about'] = $this->Bbcode->sanitize($alliance['Alliance']['about'], $this->user['User']['premium']);
    	
    	$members = $this->User->find('all', array('conditions' => array('User.alliance_id' => $id), 'recursive' => 0, 'fields' => 'User.id, User.ruler, User.race, Resource.land, Building.castle, User.score'));
	    
    	$this->set('selected', 'Profile');
    	$this->set('members', $members);
    	$this->set('alliance', $alliance);
    	$this->set('user', $this->user);
    }
    
    function boot($id = null){
    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
    	if($this->user['User']['id'] == $alliance['Alliance']['user_id']){
    		$booted = $this->User->find('first', array('conditions' => array('User.id' => $id)));
    		if($booted['User']['alliance_id'] == $alliance['Alliance']['id']){
    			$booted['User']['alliance_id'] = 0;
    			$booted['User']['alliance_rank'] = null;
				$booted['User']['honor'] -= 1000;
    			$this->User->save($booted, array('validate' => true, 'fieldList' => array('alliance_id', 'alliance_rank', 'honor')));
    			$this->Alliance->id = $alliance['Alliance']['id'];
    			$this->Alliance->save('members', --$alliance['Alliance']['members']);
    			$this->loadModel('Report');
    			$this->Report->makeNewReport(
    				$booted['User']['id'], 
    				$this->user['User']['ruler'] . " kicked you out of " . $alliance['Alliance']['short_name'] . "! Time to find a new alliance!", 
    				"Misc", 
    				$_SERVER['REMOTE_ADDR'], 
    				$this->user['User']['id'], 
    				"You were kicked out of " . $alliance['Alliance']['short_name'] . "!"
    			);
    			$this->Report->makeNewReport(
    				$this->user['User']['id'], 
    				"You kicked " . $booted['User']['ruler'] . " out of " . $alliance['Alliance']['short_name'] . "! Good riddance!", 
    				"Misc", 
    				$_SERVER['REMOTE_ADDR'], 
    				$this->user['User']['id'], 
    				"You kicked " . $booted['User']['ruler'] . " out of " . $alliance['Alliance']['short_name'] . "!"
    			);
    		} else {
    			$this->Session->setFlash('That player is not in your alliance.');
    		}
    	} else {
    		$this->Session->setFlash('You are not the alliance leader.');
    	}
    	$this->redirect(array('action' => 'profile'));
    }
    
    function disband() {
    	if(!empty($this->user['User']['alliance_id'])){
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	if($this->user['User']['id'] == $alliance['Alliance']['user_id']){
	    		if($alliance['Alliance']['members'] == 1){
	    			$this->Alliance->delete($alliance['Alliance']['id']);
	    			
	    			$this->loadModel('AllianceInvite');
    				$invites = $this->AllianceInvite->find('all', array('conditions' => array('AllianceInvite.user_id' => $this->user['User']['id'])));
	    			$this->AllianceInvite->deleteAll(array('AllianceInvite.alliance_id' => $alliance['Alliance']['id']));
	    			
	    			$this->user['User']['alliance_id'] = 0;
	    			$this->user['User']['alliance_rank'] = null;
	    			$this->User->save($this->user, array('validate' => true, 'fieldList' => array('alliance_id', 'alliance_rank')));
	    			
	    			$this->Session->setFlash('You have disbanded ' . $alliance['Alliance']['name'] . '.');
	    		} else {
	    			$this->Session->setFlash('You must be the last member of an alliance to disband it.');
	    		}
	    	} else {
	    		$this->Session->setFlash('You must be the leader of the alliance to disband it.');
	    	}
	    	
    	} else {
    		$this->Session->setFlash('You must be in an alliance to disband one.');
    	}
    	
    	$this->redirect('/alliances');
    	$this->set('user', $this->user);
    }
    
    function index() {
    	// make sure they have alliance, otherwise give them option to create
    	if(!empty($this->user['User']['alliance_id'])){
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	$this->set('alliance', $alliance);
	    	
	    	$this->loadModel('Report');
	    	foreach($alliance['Members'] as $member):
	    		$reportConditions[] = array('Report.user_id' => $member['User']['id']);
	    	endforeach;
	    	$type = array('Fail Defend', 'Success Defend', 'Spell', 'Success Spell', 'Fail Spell', 'Accept Trade', 'Make Trade', 'Sent Trade', 'Received Trade', 'Fail Attack', 'Success Attack');
    		$reports = $this->Report->find('all', array('conditions' => array('OR' => $reportConditions, 'Report.type' => $type), 'limit' => 10, 'order' => array('Report.time DESC')));
    		$reportCounted = 0;
    		foreach($reports as &$report):
    			foreach($alliance['Members'] as $member):
    				if($member['User']['id'] == $report['Report']['user_id']){
    					$report['Report']['ruler'] = $member['User']['ruler'];
    					if(++$reportCounted == 10)
    						break;
    				}
    			endforeach;
    			if($reportCounted == 10)
    				break;
    		endforeach;
    		$this->set('reports', $reports);
    	} else {
    		$this->loadModel('AllianceInvite');
    		$invites = $this->AllianceInvite->find('all', array('conditions' => array('AllianceInvite.user_id' => $this->user['User']['id'])));
    		$invites = $this->Alliance->addAllianceNames($invites);
    		$this->set('invites', $invites);
    	}
    	
    	$this->set('selected', 'Overview');
    	$this->set('user', $this->user);
    }
    
    function members() {
    	if(!empty($this->user['User']['alliance_id'])){
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	$this->set('alliance', $alliance);
	    	
	    	$members = $this->User->find('all', array('conditions' => array('User.alliance_id' => $this->user['User']['alliance_id']), 'recursive' => 0, 'fields' => 'User.id, User.ruler'));
	    	$this->set('members', $members);
	    	
	    	$this->loadModel('KillStat');
    	} else {
    		$this->loadModel('AllianceInvite');
    		$invites = $this->AllianceInvite->find('all', array('conditions' => array('AllianceInvite.user_id' => $this->user['User']['id'])));
    		$invites = $this->Alliance->addAllianceNames($invites);
    		$this->set('invites', $invites);
    	}
    	
    	$this->set('selected', 'Members');
    	$this->set('user', $this->user);
    }
    
    function create() {
    	// make sure they have alliance, otherwise give them option to create
    	if(!empty($this->user['User']['alliance_id'])){
			$this->Session->setFlash('You cannot create an alliance while in an existing one.');
	    	$this->redirect('/alliances');
    	} /* else if ($this->user['User']['premium'] == 0 || $threeAlliances = false) { 
    		$this->Session->setFlash('You cannot create an alliance without a premium account.');
    		$this->redirect('/premiums');
    	}*/ else {
    		if(!empty($this->data)){
    			$this->data['Alliance']['user_id'] = $this->user['User']['id'];
    			$this->data['Alliance']['members'] = 1;
    			$this->data['Alliance']['score'] = $this->user['User']['score'];
    			$this->data['Alliance']['land'] = $this->user['Resource']['land'];
    			$this->loadModel('KillStat');
    			$stats = $this->KillStat->find('all', array('conditions' => array('KillStat.user_id' => $this->user['User']['id'])));
    			foreach($stats as $stat):
    				if($stat['KillStat']['type'] == "Offense"){
    					$this->data['Alliance']['oks'] = $stat['KillStat']['points'];
    				}
    				if($stat['KillStat']['type'] == "Defense"){
    					$this->data['Alliance']['dks'] = $stat['KillStat']['points'];
    				}
    				$this->data['Alliance']['ks'] = $stat['KillStat']['points'];
    			endforeach;
    			if($this->Alliance->save($this->data)){
    				$this->user['User']['alliance_id'] = $this->Alliance->id;
    				$this->user['User']['alliance_rank'] = 'admin';
    				$this->loadModel('Report');
    				$this->Report->makeNewReport($this->user['User']['id'], "You have successfully created " . $this->data['Alliance']['name'] . "!", "Alliance Created", $_SERVER['REMOTE_ADDR'], $this->user['User']['id'], "You created an alliance!");
    				$this->User->save($this->user, array('validate' => false, 'fieldList' => array('alliance_id', 'alliance_rank')));
    				$this->redirect('/alliances');
    			}
    		}
    	}
    	$this->redirect('/alliances');
    	$this->set('user', $this->user);
    }
    
    function settings() {
    	if(!empty($this->user['User']['alliance_id'])){
	    	$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    	if($alliance['Alliance']['user_id'] == $this->user['User']['id']) {
	    		if(empty($this->data)){
	    			$this->data = $alliance;
	    			$this->set('alliance', $alliance);
	    		} else {
	    			$this->Alliance->id = $alliance['Alliance']['id'];
	    			$this->Alliance->save($this->data);
	    			unset($alliance);
	    			$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	  				$this->set('alliance', $alliance);
	    		}
	    	} else {
	    		$this->Session->setFlash('You do not have the necessary permissions!');
	    		$this->redirect('/alliances');
	    	}
    	} else {
    		$this->Session->setFlash('You are not in an alliance!');
    		$this->redirect('/alliances');
    	}
    	
    	$this->set('selected', 'Alliance Settings');
    	$this->set('user', $this->user);
    }
    
    function acceptInvite($invite_id = null) {
    	$this->loadModel('AllianceInvite');
    	if(!empty($this->user['User']['alliance_id'])){
			$this->Session->setFlash('You cannot accept an invite when you\'re in an alliance.');
	    	$this->redirect('/alliances');
    	} else {
    		$invite = $this->AllianceInvite->find('first', array('conditions' => array('AllianceInvite.id' => $invite_id)));
    		if($invite['AllianceInvite']['user_id'] == $this->user['User']['id']){
    			$alliance = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $invite['AllianceInvite']['alliance_id'])));
    			if($alliance['Alliance']['members'] < 8){
    				$this->user['User']['alliance_id'] = $invite['AllianceInvite']['alliance_id'];
    				$alliance['Alliance']['members']++;
    				$this->Alliance->save($alliance);
    				$this->User->save($this->user, array('validate' => true, 'fieldList' => array('alliance_id')));
    				$this->Alliance->updateAllianceStats();
    				
    				
    				$this->AllianceInvite->delete($invite['AllianceInvite']['id']);
    				$this->Session->setFlash('You joined ' . $alliance['Alliance']['name'] . '!');
    				$this->redirect('/alliances');
    			} else {
    				$this->Session->setFlash('That alliance is full.');
	    			$this->redirect('/alliances');
    			}
    		} else {
    			$this->Session->setFlash('That invitation is invalid.');
	    		$this->redirect('/alliances');
    		}
    	}
    }
    
	function denyInvite($invite_id = null) {
    	$this->loadModel('AllianceInvite');
    	if(!empty($this->user['User']['alliance_id'])){
			$this->Session->setFlash('You cannot accept an invite when you\'re in an alliance.');
	    	$this->redirect('/alliances');
    	} else {
    		$invite = $this->AllianceInvite->find('first', array('conditions' => array('AllianceInvite.id' => $invite_id)));
    		if($invite['AllianceInvite']['user_id'] == $this->user['User']['id']){
    			$this->AllianceInvite->delete($invite['AllianceInvite']['id']);
    			$this->Session->setFlash('You have successfully declined the invitation.');
    			$this->redirect('/alliances');
    		} else {
    			$this->Session->setFlash('That invitation is invalid.');
	    		$this->redirect('/alliances');
    		}
    	}
    }
    
    function invite($user_id = null) {
    	$this->loadModel('AllianceInvite');
    	if(empty($this->user['User']['alliance_id'])){
			$this->Session->setFlash('You cannot invite a user when you\'re not in an alliance.');
	    	$this->redirect('/alliances');
    	} else if($this->user['User']['alliance_rank'] != 'admin') {
    		$this->Session->setFlash('You cannot invite a when you are not the administrator.');
	    	$this->redirect('/alliances');
    	} else {
    		if(!empty($user_id)){
	    		if($this->User->find('first', array('conditions' => array('User.id' => $user_id)))){
	    			$invite['AllianceInvite']['user_id'] = $user_id;
	    			$invite['AllianceInvite']['alliance_id'] = $this->user['User']['alliance_id'];
	    			
					$currentInvites = $this->AllianceInvite->find('first', array('conditions' => array('AllianceInvite.user_id' => $user_id, 'AllianceInvite.alliance_id' => $this->user['User']['alliance_id'])));
					if(empty($currentInvites)){
		    			if($this->AllianceInvite->save($invite)){
		    				$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
		    				$title = "You've been invited to " . $alliance['Alliance']['short_name'] . "!";// create message for receiver
		    				$body = 'You may [url=/alliances/acceptInvite/' . $this->AllianceInvite->id . ']accept[/url] this invitation, [url=/alliances/denyInvite/' . $this->AllianceInvite->id . ']deny[/url] it or ignore it. The choice is yours, choose wisely.';
		    				$this->Message->sendMessage($body, $title, $user_id, $this->user['User']['id']);
		    				$this->Session->setFlash('User invited successfully');
		    			} else {
		    				$this->Session->setFlash('There was an error sending your inviation.');
		    			}
					} else {
						$this->Session->setFlash('That user already has an invite for your alliance.');
					}
	    		} else {
	    			$this->Session->setFlash('That user does not exist.');
	    			$this->redirect('/alliances');
    			}
    		} 
    	}
    	$invites = $this->AllianceInvite->find('all', array('conditions' => array('AllianceInvite.alliance_id' => $this->user['User']['alliance_id'])));
    	$this->set('selected', 'Sent Invitations');
    	if(!empty($invites))
    		$invites = $this->Alliance->addNamesToInvites($invites, $this->user['User']['alliance_id']);
		$this->set('alliance', $this->Alliance->getAlliance($this->user['User']['alliance_id']));
    	$this->set('invites', $invites);
    	$this->set('user', $this->user);
    }
    
    function leave() {
    	if(empty($this->user['User']['alliance_id']) || $threeAlliances = false){
			$this->Session->setFlash('You cannot leave an alliance you are not in!');
	    	$this->redirect('/alliances');
    	} 
    	
    	$alliance = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $this->user['User']['alliance_id'])));
	    if($alliance['Alliance']['members'] > 1){
	    	$this->user['User']['alliance_id'] = 0;
	    	
	    	if($this->user['User']['alliance_rank'] == 'admin'){
	    		$this->user['User']['alliance_rank'] = null;
	    		
	    		$newLeader = $this->User->find('first', array('conditions' => array('User.id !=' => $this->user['User']['id'], 'User.alliance_id' => $alliance['Alliance']['id'])));
	    		$newLeader['User']['alliance_rank'] = 'admin';
	    		$this->User->save($newLeader, array('validate' => true, 'fieldList' => array('alliance_rank')));
	    		$alliance['Alliance']['user_id'] = $newLeader['User']['id'];
	    	}
	 		$this->user['User']['honor'] -= 1000;
	    	$alliance['Alliance']['members']--;
	    	$this->User->save($this->user, array('validate' => true, 'fieldList' => array('alliance_id', 'alliance_rank', 'honor')));
	    	$this->Alliance->save($alliance['Alliance']);
	    	
	    	$this->Alliance->updateAllianceStats();
	    	
	    	$this->Session->setFlash('You left ' . $alliance['Alliance']['name'] . '!');
	    	$this->redirect('/alliances');
	    } else {
	    	$this->Session->setFlash('You must disband the alliance in order to leave.');
	    	$this->redirect('/alliances');
	    }
    	
    }

    function ally($decision = null, $id = null){
    	$this->loadModel('DiplomacyRequest');
    	$request = $this->DiplomacyRequest->find('first', array('conditions' => array('DiplomacyRequest.id' => $id)));
    	$this->loadModel('AllianceFriend');
    	if($this->user['User']['alliance_rank'] == 'admin' && $request['DiplomacyRequest']['alliance_id'] == $this->user['User']['alliance_id']){
    		$alliance = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $this->user['User']['alliance_id'])));
    		$found = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $request['DiplomacyRequest']['requester_id'])));
    		if($decision == 'accept') {
    			$ally['AllianceFriend']['alliance_id'] = $request['DiplomacyRequest']['alliance_id'];
	    		$ally['AllianceFriend']['friend_id'] = $request['DiplomacyRequest']['requester_id'];
	    		$ally['AllianceFriend']['type'] = "ally";
	    		$ally['AllianceFriend']['date'] = date ("Y-m-d H:m:s");
	    		$this->AllianceFriend->save($ally);
	    		$ally['AllianceFriend']['alliance_id'] = $request['DiplomacyRequest']['requester_id'];
	    		$ally['AllianceFriend']['friend_id'] = $request['DiplomacyRequest']['alliance_id'];
	    		$this->AllianceFriend->create();
	    		$this->AllianceFriend->save($ally);

	    		$this->DiplomacyRequest->delete($request['DiplomacyRequest']['id']);

	    		$members = $this->User->find('all', array('conditions' => array('User.alliance_id' => $request['DiplomacyRequest']['requester_id']), array('fields' => 'User.id')));
    			$friendMembers = $this->User->find('all', array('conditions' => array('User.alliance_id' => $request['DiplomacyRequest']['alliance_id']), array('fields' => 'User.id')));
    			foreach($members as $member):
	    			$this->Report->makeNewReport(
	    				$member['User']['id'], 
	    				$this->user['User']['ruler'] . " has agreed to ally " . $found['Alliance']['short_name'] . ". You may now work with them in combat. This partnership will last at least 7 days.", 
	    				"Misc", 
	    				$_SERVER['REMOTE_ADDR'], 
	    				$this->user['User']['id'], 
						$alliance['Alliance']['short_name'] . " proclaims " . $found['Alliance']['short_name'] . " as an ally!"
	    			);
    			endforeach;
    			foreach($friendMembers as $member):
	    			$this->Report->makeNewReport(
	    				$member['User']['id'], 
	    				$this->user['User']['ruler'] . " has agreed to ally " . $found['Alliance']['short_name'] . ". You may now work with them in combat. This partnership will last at least 7 days.", 
	    				"Misc", 
	    				$_SERVER['REMOTE_ADDR'], 
	    				$this->user['User']['id'], 
						$alliance['Alliance']['short_name'] . " proclaims " . $found['Alliance']['short_name'] . " as an ally!"
	    			);
    			endforeach;
	    	} elseif ($decision == 'deny') {
				$this->DiplomacyRequest->delete($request['DiplomacyRequest']['id']);
				$otherLeader = $this->User->find('first', array('conditions' => array('User.alliance_rank' => 'admin', 'User.alliance_id' => $request['DiplomacyRequest']['requester_id']), 'fields' => 'User.id'));
				$this->Report->makeNewReport(
    				$otherLeader['User']['id'], 
    				$this->user['User']['ruler'] . " has denied your request at an alliance.", 
    				"Misc", 
    				$_SERVER['REMOTE_ADDR'], 
    				$this->user['User']['id'], 
    				$alliance['Alliance']['short_name'] . " has rejected your offer to ally!"
    			);
    			$this->Report->makeNewReport(
    				$this->user['User']['id'], 
    				"You have rejected the offer to ally with " . $found['Alliance']['short_name'], 
    				"Misc", 
    				$_SERVER['REMOTE_ADDR'], 
    				$this->user['User']['id'], 
    				$found['Alliance']['short_name'] . " offer to ally was rejected!"
    			);
	    	} else {
	    		$this->Session->setFlash('Error in URL.');
	    	}
    	} else {
    		$this->Session->setFlash('You do not have the privelege to do that.');
    	}
    	
    	$this->redirect('/alliances');
    }

    function retaliations() {
    	if($this->user['User']['alliance_id']) {
    		$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	  		$this->set('alliance', $alliance);
    		$this->set('user', $this->user);

    		$this->loadModel('AllianceRetaliation');
    		$time = date("Y-m-d H:i:s", time() - (60*60*5));
    		$allianceRetaliations = $this->AllianceRetaliation->find('all', array('conditions' => array('AllianceRetaliation.datetime > ' => $time, 'AllianceRetaliation.alliance_id' => $this->user['User']['alliance_id'], 'AllianceRetaliation.used' => 0 )));
    		foreach($allianceRetaliations as &$retal):
    			$attacker = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $retal['AllianceRetaliation']['attacker_id'])));
    			$retal['AllianceRetaliation']['name'] = $attacker['Alliance']['short_name'];
    			$player = $this->User->find('first', array('conditions' => array('User.id' => $retal['AllianceRetaliation']['player_id'], 'fields' => 'User.ruler')));
    			$retal['AllianceRetaliation']['player_name'] = $player['User']['ruler'];
    		endforeach;
    		$this->set('allianceRetaliations', $allianceRetaliations);
    	} else {
    		$this->Session->setFlash('You do not have the privelege to do that.');
    	}

    }
    
}

?>