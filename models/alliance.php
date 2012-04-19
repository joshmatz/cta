<?php
class Alliance extends AppModel {    
	var $name = 'Alliance';
	var $validate = array(
		'name' => array(
			'nameRule-1' => array(
				'rule' => 'notEmpty',
				'message' => 'name name must not be empty.'
			),
			'nameRule-2' => array(
				'rule' => 'isUnique',
				'message' => 'This name already exists.'
			),
			'nameRule-3' => array(
				'rule' => array('maxLength', 40),
				'message' => 'Alliance names must be no larger than 40 characters long.'
			)
		),
		'short_name' => array(
			'shortnameRule-1' => array(
				'rule' => 'notEmpty',
				'message' => 'name name must not be empty.'
			),
			'shortnameRule-2' => array(
				'rule' => 'isUnique',
				'message' => 'This name already exists.'
			),
			'shortnameRule-3' => array(
				'rule' => array('maxLength', 7),
				'message' => 'Short names must be no larger than 7 characters long.'
			)
		)
	);
	
	function updateAllianceStats() {
		$this->User = Classregistry::init('User');
		$users = $this->User->find('all', array('conditions' => array('User.alliance_id !=' => 0), 'fields' => 'User.alliance_id, User.id, User.score, User.honor, Resource.land', 'recursive' => 1));
		$alliances = $this->find('all', array('fields' => 'Alliance.score, Alliance.id, Alliance.score_rank, Alliance.land, Alliance.honor, Alliance.honor_rank, Alliance.oks, Alliance.oks_rank, Alliance.dks, Alliance.dks_rank, Alliance.ks, Alliance.ks_rank'));
		foreach($alliances as &$alliance):
			$alliance['Alliance']['score'] = 0;
			$alliance['Alliance']['score_rank'] = 0;
			$alliance['Alliance']['oks'] = 0;
			$alliance['Alliance']['oks_rank'] = 0;
			$alliance['Alliance']['dks'] = 0;
			$alliance['Alliance']['dks_rank'] = 0;
			$alliance['Alliance']['ks'] = 0;
			$alliance['Alliance']['ks_rank'] = 0;
			$alliance['Alliance']['land'] = 0;
			$alliance['Alliance']['honor'] = 0;
			$alliance['Alliance']['honor_rank'] = 0;
		endforeach;
		
		$this->KillStat = Classregistry::init('KillStat');
		foreach($users as $user):
			foreach($alliances as &$alliance):
				if($user['User']['alliance_id'] == $alliance['Alliance']['id']){
					$alliance['Alliance']['score'] += $user['User']['score'];
					$alliance['Alliance']['honor'] += $user['User']['honor'];
					$alliance['Alliance']['land'] += $user['Resource']['land'];
	    			$stats = $this->KillStat->find('all', array('conditions' => array('KillStat.user_id' => $user['User']['id'])));
	    			foreach($stats as $stat):
	    				if($stat['KillStat']['type'] == "Offense"){
	    					$alliance['Alliance']['oks'] += $stat['KillStat']['points'];
	    				}
	    				if($stat['KillStat']['type'] == "Defense"){
	    					$alliance['Alliance']['dks'] += $stat['KillStat']['points'];
	    				}
	    				$alliance['Alliance']['ks'] += $stat['KillStat']['points'];
	    			endforeach;
				}
			endforeach;
		endforeach;
		
		$alliances = Set::sort($alliances, '{n}.Alliance.score', 'desc');
		$rank = 1;
		foreach($alliances as &$alliance):
			$alliance['Alliance']['score_rank'] = $rank++;
		endforeach;
		
		$alliances = Set::sort($alliances, '{n}.Alliance.honor', 'desc');
		$rank = 1;
		foreach($alliances as &$alliance):
			$alliance['Alliance']['honor_rank'] = $rank++;
		endforeach;
		
		$alliances = Set::sort($alliances, '{n}.Alliance.oks', 'desc');
		$rank = 1;
		foreach($alliances as &$alliance):
			$alliance['Alliance']['oks_rank'] = $rank++;
		endforeach;
		
		$alliances = Set::sort($alliances, '{n}.Alliance.dks', 'desc');
		$rank = 1;
		foreach($alliances as &$alliance):
			$alliance['Alliance']['dks_rank'] = $rank++;
		endforeach;
		
		$alliances = Set::sort($alliances, '{n}.Alliance.ks', 'desc');
		$rank = 1;
		foreach($alliances as &$alliance):
			$alliance['Alliance']['ks_rank'] = $rank++;
		endforeach;
		
		$this->saveAll($alliances);
	}
	
	function getAlliance($id = null){
		if($id){
			$User = Classregistry::init('User');
			$alliance = $this->find('first', array('conditions' => array('Alliance.id' => $id)));
		    $alliance['Members'] = $User->find('all', array('conditions' => array('User.alliance_id' => $id), 'fields' => 'User.id, User.ruler, User.facebook_id', 'recursive' => 0));
		    
		    return $alliance;
		} else {
			return 0;
		}
	}
	
	function addNamesToInvites($invites = null, $alliance_id = null){
		$User = Classregistry::init('User');
		foreach($invites as &$invite):
	    	$invite['AllianceInvite']['name'] = $User->find('first', array('conditions' => array('User.id' => $invite['AllianceInvite']['user_id']), 'fields' => 'User.id,  User.ruler', 'recursive' => 0));
	    endforeach;
	    
	    return $invites;
	}
	
	function addAllianceNames($invites = null){
		foreach($invites as &$invite):
			$invite['AllianceInvite']['name'] = $this->find('first', array('conditions' => array('Alliance.id' => $invite['AllianceInvite']['alliance_id'])));
		endforeach;
		
		return $invites;
	}
	
}