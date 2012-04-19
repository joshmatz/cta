<?php
class User extends AppModel {    
	var $name = 'User';	
	var $hasOne = array(
		'Resource' => array(
			'className'    => 'Resource',
			'dependent'    => true
		),
		'Army' => array(
			'className'    => 'Army',
			'dependent'    => true
		),
		'Population' => array(
			'className'    => 'Population',
			'dependent'    => true
		),
		'Spell' => array(
			'className'    => 'Spell',
			'dependent'    => true
		),
		'Building' => array(
			'className'    => 'Building',
			'dependent'    => true
		),
		'Technology' => array(
			'className'    => 'Technology',
			'dependent'    => true
		),
		'Weapon' => array(
			'className'    => 'Weapon',
			'dependent'    => true
		)
	);
	var $hasMany = array(
		'Attack' => array(
			'className'    => 'Attack',
			'dependent'    => true
		),
		'Retaliation' => array(
			'className'    => 'Retaliation',
			'dependent'    => true
		)
	);
	var $validate = array(
		'ruler' => array(
			'rulerRule-1' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Ruler name must contain only letters or numbers.'
			),
			'rulerRule-2' => array(
				'rule' => 'notEmpty',
				'message' => 'Ruler name must not be empty.'
			),
			'rulerRule-3' => array(
				'rule' => array('limitDuplicates'),
				'message' => 'This ruler name already exists.'
			),
			'rulerRule-4' => array(
				'rule' => array('between', 2, 20),
				'message' => 'Ruler name must be between 2 and 20 characters'
			)
		)
	);
	
	function pluralize($s, $c) {
      if ($c != 1) {
        return $c . ' ' . Inflector::pluralize($s);
      }
      return $c . ' ' . $s;
    }
	
	function limitDuplicates($check){
		//$check will have value: array('promomotion_code' => 'some-value')
		//$limit will have value: 25        
    	$count = $this->find('count', array('conditions' => $check, 'recursive' => -1) );
    	return $count < 1;
	}
	
	function getName($targetId = null, $link = true){
		$target = $this->find('first', array('conditions' => array('User.id' => $targetId), 'fields' => 'User.ruler'));
		if(!empty($target)) {
			if($link) {
				$name = '<a href="/users/profile/' . $targetId . '">' . $target['User']['ruler']/* . " of " . $target['User']['kingdom']*/ . "</a>";
			} else {
				$name = $target['User']['ruler'] /* . " of " . $target['User']['kingdom'] */;
			}
		} 
		else
			$name = "No Account";
		return $name;
	}
	
	function getRankedPlayers($rank = 1, $limit = 20){

		$order = 'User.rank ASC';
		$conditions = array('User.rank >=' => $rank);
		$fields = "User.id, User.ruler, User.kingdom, User.rank, User.race, User.score, User.province, User.province_number, User.premium, User.suspended, Resource.land, Building.castle";
		$players = $this->find('all', array('fields' => $fields, 'conditions' => $conditions, 'order' => $order, 'limit' => $limit, 'recursive' => 1));
		
		return $players;
	}
	
	function rankPlayers() {
		$order = 'User.score DESC';
		$fields = "User.score, User.id";
		$players = $this->find('all', array('fields' => $fields, 'order' => $order, 'recursive' => -1));
		$rank = 1;
		foreach($players as &$player):
			$player['User']['rank'] = $rank;
			$this->id = $player['User']['id'];
			if($this->save($player['User'], array('validate' => true, 'fieldList' => array('rank')))){
				//echo "Rank saved as $rank";
			}
			$rank++;
		endforeach;
	}
	
	function rankHonorPlayers() {
		$order = 'User.honor DESC';
		$fields = "User.honor, User.loyalty, User.id";
		$players = $this->find('all', array('fields' => $fields, 'order' => $order, 'recursive' => -1));
		$rank = 1;
		foreach($players as &$player):
			$player['User']['honor_rank'] = $rank;
			//$player['User']['honor'] += 40;

			if($player['User']['honor'] < 0){ 
				$loyalty = $player['User']['honor'] / 300;
			} else {
				$loyalty = $player['User']['honor'] / 400;
			}
			
			if($loyalty > 50) {
				$loyalty = 50;
			} 
			if($loyalty < -50) {
				$loyalty = -50;
			}

			$player['User']['loyalty'] = $loyalty;
			$this->id = $player['User']['id'];
			if($this->save($player['User'], array('validate' => true, 'fieldList' => array('honor_rank', 'loyalty', 'honor')))){
				//echo "Rank saved as $rank";
			}
			$rank++;
		endforeach;
	}
	
	
	function getTotalPlayers(){		
		return $this->find('count', array('fields' => 'DISTINCT User.id'));
	}
	
	function getRankedFriends($friendIds = null, $limit = 40, $rank =1){
		$order = 'User.score DESC';
		$conditions = array("User.facebook_id" => $friendIds);
		$fields = "User.facebook_id, User.id, User.ruler, User.rank, User.kingdom, User.race, User.score, User.province, User.province_number, Resource.land, Building.castle";
		$friends = $this->find('all', array('fields' => $fields, 'order' => $order, 'recursive' => 1, 'conditions' => $conditions));
		$this->Setting = Classregistry::init('Setting');
		$hide = array();
		foreach($friends as $value => $friend):
			$setting = $this->Setting->find('first', array('conditions' => array('Setting.user_id' => $friend['User']['id'])));
			if(!empty($setting)){
				if($setting['Setting']['hide_self']){
					$hide[] = $value;
				}
			}
		endforeach;
		
		foreach($hide as $hidden):
			unset($friends[$hidden]);
		endforeach;
		
		$count = 0;
		if((isset($rank)) && ($rank > 1)){
			while($count < ($rank - 1)) {
				unset($players[$count]);
				$count++;
			}
		}
		// reset keys
		$friends = array_values($friends);
		
		return $friends;
	}

	function updateScore($user = null){
		// echo "Current score: " . $user['User']['score'] . "<br />";
		$user['User']['score'] = 0;
		if($user['Building']['castle'] > 0){
			$user['User']['score'] += ($user['Building']['farm'] * 75);
			$user['User']['score'] += ($user['Building']['mill'] * 150);
			$user['User']['score'] += ($user['Building']['mine'] * 150);
			$user['User']['score'] += ($user['Building']['house'] * 75);
			$user['User']['score'] += ($user['Building']['castle'] * 5000);
			$user['User']['score'] += ($user['Building']['forge'] * 250);
			$user['User']['score'] += ($user['Building']['market'] * 250);
			$user['User']['score'] += ($user['Building']['school'] * 300);
			$user['User']['score'] += ($user['Building']['medical_center'] * 175);
			$user['User']['score'] += ($user['Building']['guard_house'] * 250);
			$user['User']['score'] += ($user['Building']['guard_tower'] * 175);
			$user['User']['score'] += ($user['Building']['training_center'] * 250);
			$user['User']['score'] += ($user['Building']['advanced_training_center'] * 350);
			//echo "Pay attention to this if you want to know why your score is the way it is! <br />";
			//echo "If you think your score is current inflated or lopsided, please post <a href=\"http://www.bojoto.com/ctagameforum/viewtopic.php?f=4&t=131&start=10\">in the forum</a>. <br />";

			$user['User']['score'] = round($user['User']['score'] / 10);
			
			$user['User']['score'] += $this->Army->getArmyPoints($user);
			
			$user['User']['score'] += (($user['Technology']['tree_1'] + $user['Technology']['tree_2'] + $user['Technology']['tree_3'] + $user['Technology']['tree_4']
								   + $user['Technology']['racial'] + $user['Technology']['blade'] + $user['Technology']['range'] + $user['Technology']['light_armor']
								   + $user['Technology']['heavy_armor']) * 1000);
			
		}
		
		// echo "Score updated to " . $user['User']['score'] . "<br />";
		
		return $user;
	}
	
	function updateCrimeRate($user = null) {
		$crimeRate = $user['User']['crime_rate'];
		
		// fluctuate, but slowly increaase
		if($user['Technology']['tree_1'] > 5) {
			$fluctuation = mt_rand(1,72);
			if($fluctuation == 1 || $fluctuation == 2)
				$crimeRate++;
			if($fluctuation == 3)
				$crimeRate--;
		}
		else {
			$fluctuation = mt_rand(1,48);
			if($fluctuation == 1 || $fluctuation == 2)
				$crimeRate++;
			if($fluctuation == 3)
				$crimeRate--;
		}
		
		if($user['Spell']['god_blessing']){
			$crimeRate--;
		}
		
		// Set minimum to # of castles
		if($crimeRate < $user['Building']['castle'])
			$crimeRate = $user['Building']['castle'];
		
		// Set maximum
		if($crimeRate > 45)
			$crimeRate = 45;
		if($user['Technology']['tree_1'] > 5) {
			if($crimeRate > 25)
				$crimeRate = 25;
		}
		//if($user['User']['victim_bonus'] == 100){
		//	$user['User']['victim_bonus'] = 0;
		//}
		//if($user['User']['victim_time']) {
		//	$user['User']['victim_time']--;
		//	if($user['User']['victim_time'] == 0)
		//		$user['User']['victim_bonus'] = 0;
		//}
		$user['User']['crime_rate'] = $crimeRate;
		return $user;
	}
	
	function updateProtection($user = null) {
		if($user['User']['protection'] > 0) {
			$user['User']['protection']--;
			if($user['User']['protection'] == 0 && $user['User']['beginner_protection']){
				$user['User']['beginner_protection'] = 0;
			}
		}
		
		return $user;
	}
	
	function updatePremium($user = null) {
		if($user['User']['premium'] > 0){ 
			$user['User']['premium']--;
			if($user['User']['premium'] == 0)
				$user['Army']['strategy'] = "None";
		} else {
			// $user['User']['premium'] = 1500;
		}
			
		
		return $user;
	}
	
	function sendNotification($user = null, $facebook = null) {
		//give notification if kingdom hasn't been accessed in 3 days
		$login = $user['User']['last_login'];
		$timeElapsed = time() - $login; 
		
		$minTime = (3 * 24 * 60 * 60);
		$maxTime = (3 * 24 * 60 * 60) + (60 * 60);
		if (($timeElapsed > $minTime) && ($timeElapsed < $maxTime)){
			$notification = 'Your peasants are growing idle, return to <a href="http://apps.facebook.com/ctagame">Call to Arms</a> to manage your kingdom!';
			$facebook->api_client->notifications_send($user['User']['facebook_id'], $notification, 'app_to_user');
		}
		$minTime = (7 * 24 * 60 * 60);
		$maxTime = (7 * 24 * 60 * 60) + (60 * 60);
		if (($timeElapsed > $minTime) && ($timeElapsed < $maxTime)){
			$notification = 'Your kingdom lacks leadership, return to <a href="http://apps.facebook.com/ctagame">Call to Arms</a> to manage your kingdom!';
			$facebook->api_client->notifications_send($user['User']['facebook_id'], $notification, 'app_to_user');
		}
	}
}
?>