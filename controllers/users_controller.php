<?php
class UsersController extends AppController {    
	var $name = 'Users';
	var $facebook_id;
	var $found;
	var $layout = 'redesign';
	
	// The next two methods belong in each Controller class
	function beforeFilter() {  
		parent::beforeFilter();
		//$this->facebook_id = $this->facebook->require_login(); 
		$this->loadModel('Report');
		//$this->facebook_id = $this->facebookId;
        //$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->found = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id)));
		$this->User->id = $this->found['User']['id'];
		//debug($this->facebook_id);
		//debug($this->found);

		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->found['User']['id']));
		$this->set('parms', $this->parms);
    }  
    
    function admin_random() {
    	$users = $this->User->find('all', array('fields' => array('User.alliance_id, User.ruler, User.score'), 'recursive' => 0, 'order' => 'User.score DESC'));
    	//debug($users);
    	foreach($users as &$user):
    		if($user['User']['alliance_id'] == 3)	:
    			$user['User']['alliance_id'] = mt_rand(1,2);
    			$user['User']['alliance_changed'] = true;
    		endif;
    	endforeach;
    	
    	foreach($users as $user):
    		$alliance = $user['User']['alliance_id'];
    		if(!isset($alliances[$alliance])):
    			$alliances[$alliance] = 0;
    		endif;
    		$alliances[$alliance] += $user['User']['score'];
    	endforeach;
    	
    	debug($alliances);
    	debug($users);
    	
    	$this->set('user', $this->found);
    }
    
    function tarena() {
       	$time = date("Y-m-d H:m:s"); 
		$strToTime = strtotime($time);
		$voteTime = strtotime($this->found['User']['tarena']);
    	if($this->found['User']['tarena'] == 0 || $voteTime <= ($strToTime - 60*60*24)){
    		$this->Session->setFlash('Thanks for voting! You received <a href="/technologies/coins">2 tech coins</a>!');
    		$this->found['User']['tarena'] = $time;
    		$this->found['Technology']['coins'] += 2;
    		$this->User->save($this->found, array('validate' => true, 'fieldList' => array('tarena')));
    		$this->loadModel('Technology');
    		$this->Technology->save($this->found['Technology']);
    		$this->redirect('http://mmorpg.top100arena.com/in.asp?id=58936');
    	} else {
    		$this->Session->setFlash('You must wait ' . number_format(($voteTime - $strToTime + 60*60*24) / 60 / 60) . ' more hours.');
    	}
    	$this->set('user', $this->found);
    	$this->redirect('/play');
    }
    
    function twg() {
    	$time = date("Y-m-d H:m:s"); 
		$strToTime = strtotime($time);
		$voteTime = strtotime($this->found['User']['twg']);
    	if($this->found['User']['twg'] == 0 || $voteTime <= ($strToTime - 60*60*24)){
    		$this->Session->setFlash('Thanks for voting! You received <a href="/technologies/coins">2 tech coins</a>!');
    		$this->found['User']['twg'] = $time;
    		$this->found['Technology']['coins'] += 2;
    		$this->User->save($this->found, array('validate' => true, 'fieldList' => array('twg')));
    		$this->loadModel('Technology');
    		$this->Technology->save($this->found['Technology']);
    		$this->redirect('http://www.topwebgames.com/in.asp?id=7564');
    	} else {
    		$this->Session->setFlash('You must wait ' . number_format(($voteTime - $strToTime + 60*60*24) / 60 / 60) . ' more hours.');
    	}
    	$this->set('user', $this->found);
    	$this->redirect('/play');
    }
    
    function apex() {
    	$time = date("Y-m-d H:m:s"); 
		$strToTime = strtotime($time);
		$apexTime = strtotime($this->found['User']['apex']);
    	if($this->found['User']['apex'] == 0 || $apexTime <= ($strToTime - 60*60*24)){
    		$this->Session->setFlash("Thanks for voting! You received " . 350 * $this->found['Building']['castle'] . " wood, " . 250 * $this->found['Building']['castle'] . " iron, and " . 3750 * $this->found['Building']['castle'] . " gold!");
    		$this->found['User']['apex'] = $time;
    		$this->found['Resource']['wood'] += 350 * $this->found['Building']['castle'];
    		$this->found['Resource']['iron'] += 250 * $this->found['Building']['castle'];
    		$this->found['Resource']['gold'] += 3750 * $this->found['Building']['castle'];
    		$this->User->save($this->found, array('validate' => true, 'fieldList' => array('apex')));
    		$this->loadModel('Resource');
    		$this->Resource->save($this->found['Resource']);
    		$this->redirect('http://apexwebgaming.com/in/5446');
    	} else {
    		$this->Session->setFlash('You must wait ' . number_format(($apexTime - $strToTime + 60*60*24) / 60 / 60) . ' more hours.');
    	}
    	$this->set('user', $this->found);
    	$this->redirect('/play');
    }
    
	function index() {        
		// Pages.isFan
		// App Id: 159349003521
		//$query = "YOUR QUERY HERE";     //(see above examples)
		//$result = $facebook->api_client->fql_query($query);
		
		// Retrieve the user's friends and pass them to the view. 
		if(isset($this->found)) {
	        $this->set('user', $this->found);
	        $this->loadModel('Alliance');
      		$alliance = $this->Alliance->getAlliance($this->found['User']['alliance_id']);
    		$this->set('alliance', $alliance);
	        if($reports = $this->Report->find('all', array('conditions' => array('Report.user_id' => $this->found['User']['id']), 'order' => array('Report.time DESC'), 'limit' => 5))){
	      		$this->set('reports', $reports);
	      		$countedReports = $this->Report->countNewReports($this->found);
	      		$this->set('countedReports', $countedReports);
	      		
	        }
	      	if(isset($this->found['Building']['castle'])){
	      		
	      		$this->set('login', $this->found['User']['last_login']);
	      		$this->found['User']['last_login'] = strtotime(date('r', time()));
	      		if($countedReports < 6)
	      			$this->User->save($this->found, array('validate' => true, 'fieldList' => array('last_login')));
	      	}
	      	else {
	      		$this->redirect(array('action' => 'create'));
	      	}
			if($this->found['Building']['castle'] == 0){
	      		$this->Session->setFlash('Your kingdom has been conquered, you may restart by creating a new kingdom.');
	      		$this->redirect(array('action' => 'destroyed'));
	      	}
		}
		else {
			$this->redirect(array('action' => 'create'));
		}
	}
	
	function confirmRestart() {
		if($this->found['User']['beginner_protection'] && false) {
			$this->Session->setFlash('You may not restart while in beginner protection');
		}
		$this->set('user', $this->found); 
        if($reports = $this->Report->getAllReports($this->found))
      		$this->set('reports', $reports);
        
      	$this->found['User']['last_login'] = strtotime(date('r', time()));
      	$this->User->save($this->found);
	}
	
	function destroyed() {
		$this->set('user', $this->found); 
        if($reports = $this->Report->getAllReports($this->found))
      		$this->set('reports', $reports);
        
      	$this->found['User']['last_login'] = strtotime(date('r', time()));
      	$this->User->save($this->found);
	}
	
	function profile($id = null) {
		$this->menu = array(
			array(
				'name' => 'My Profile',
				'link' => '/users/profile'
			)
		);
		$this->set('menu', $this->menu);
		
	
		
		if(empty($id)){
			$id = $this->found['User']['id'];
			$this->set('profileRuler', $this->found);
			$profile = $this->User->find('first', array('conditions' => array('User.id' => $id)));
			$this->set('profile', $profile);
		} else {
			$this->set('profileRuler', $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => 'User.ruler')));
			$retals = $this->User->Retaliation->getTopRetaliation($this->found['User']['id'], $id);
			$profile = $this->User->find('first', array('conditions' => array('User.id' => $id)));
			if($profile['User']['suspended'] == 1 && empty($retals)) {
				$this->Session->setFlash('That user is currently suspended.');
				$this->redirect('index');
			} else if ($profile['User']['suspended'] == 1){
				$this->Session->setFlash('That user is suspended. You have a retaliation right to take on the kingdom.');
			}
			$this->set('profile', $profile);
		}
		
		
			
		if(!empty($this->data)){
			$this->loadModel('About');
			$about = $this->About->find('first', array('conditions' => array('About.user_id' => $this->found['User']['id'])));
			if(!empty($about)){
				$about['About']['about'] = $this->data['About']['about'];
			} else {
				$about['About']['about'] = $this->data['About']['about'];
				$about['About']['user_id'] = $this->found['User']['id'];
				$this->About->create();
			}
			$about['About']['about'] = htmlspecialchars($about['About']['about']);
			$about['About']['about'] = nl2br($about['About']['about']);
			$about['About']['about'] = $this->Bbcode->parse_bbcode($about['About']['about'], $this->found['User']['premium']);
			if($this->About->save($about)){
				$this->Session->setFlash('Profile saved.');
			}
		}
		$this->loadModel('KillStat');
		$userKillStats = $this->KillStat->find('first', array('conditions' => array('KillStat.user_id' => $id, 'KillStat.type' => "Total")));
		$this->set('userKillStats', $userKillStats);
		if($profile['User']['alliance_id']){
			$this->loadModel('Alliance');
			$alliance = $this->Alliance->getAlliance($profile['User']['alliance_id']);
			$this->set('alliance', $alliance);
		} else {
			$this->set('alliance', 0);
		}
		$this->set('account', $this->account);
		$this->loadModel('About');
		$foundAbout = $this->About->find('first', array('conditions' => array('About.user_id' => $id)));
		$this->set('about', $foundAbout['About']['about']);
		$this->set('name', $this->User->getName($id));
		$this->set('id', $id);
		$this->set('user', $this->found);
	}
	
	
	function restart() {
		if($this->found['User']['alliance_rank'] == 'admin') {
			$this->Session->setFlash('You must disband your alliance before restarting your kingdom.');
			$this->redirect(array('controller' => 'alliances', 'action' => 'index'));
		}
		if($this->found['User']['beginner_protection'] && false){
			$this->Session->setFlash('You may not restart while in beginner protection');
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->loadModel('Retaliation');
		$retaliation = $this->Retaliation->find('first', array('conditions' => array('Retaliation.attacker_id' => $this->found['User']['id'])));
		if($retaliation){
			$this->Session->setFlash('You may not restart while you have retaliations out on your kingdom.');
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->loadModel('Alliance');
		$alliance = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $this->found['User']['alliance_id'])));
		if($alliance){
			$alliance['Alliance']['members']--;
			if($alliance['Alliance']['members'] != 0) {
				$newLeader = $this->User->find('first', array('conditions' => array('User.alliance_id' => $this->found['User']['alliance_id'], 'User.ruler !=' => $this->found['User']['ruler'])));
	    		$newLeader['User']['alliance_rank'] = 'admin';
	    		$this->User->save($newLeader);
	    		$this->Alliance->save($alliance); 
			} else {
				$this->Alliance->delete($alliance['Alliance']['id']);
	    		
	    		$this->loadModel('AllianceInvite');
	    		$invites = $this->AllianceInvite->find('all', array('conditions' => array('AllianceInvite.user_id' => $this->found['User']['id'])));
	    		$this->AllianceInvite->deleteAll(array('AllianceInvite.alliance_id' => $alliance['Alliance']['id']));
			}
	    	$this->Alliance->updateAllianceStats();
		}
		
		$this->loadModel('Trade');
		$trades = $this->Trade->find('all', array('conditions' => array('Trade.user_id' => $this->found['User']['id'])));
		if(!empty($trades)) {
			foreach($trades as $trade):
				$this->Trade->delete($trade['Trade']['id']);
			endforeach;
		}
		$this->loadModel('MagicSchedule');
		$schedules = $this->MagicSchedule->find('all', array('conditions' => array('MagicSchedule.user_id' => $this->found['User']['id'])));
		if(!empty($schedules)) {
			foreach($schedules as $schedule):
				$this->MagicSchedule->delete($schedule['MagicSchedule']['id']);
			endforeach;
		} /*
		$player = $this->found;
		if($player['User']['premium'] || $player['User']['premium_points']){	
			$this->loadModel('Reset');
			$reset = $this->Reset->find('first', array('conditions' => array('Reset.facebook_id' => $player['User']['facebook_id'])));
			if(empty($reset['Reset'])) {
				$reset['Reset']['facebook_id'] = $player['User']['facebook_id'];
				$reset['Reset']['premium_time'] = $player['User']['premium'];
				$reset['Reset']['premium_points'] = $player['User']['premium_points'];
				$this->Reset->create();
			}
			else {
				$reset['Reset']['premium_time'] = $player['User']['premium'];
				$reset['Reset']['premium_points'] = $player['User']['premium_points'];
			}
			$this->Reset->save($reset);
		} */
		$this->loadModel('KillStat');
		$OKillStat = $this->KillStat->find('first', 
			array(
				'conditions' => array(
					'KillStat.type LIKE' => 'Offense', 
					'KillStat.user_id' => $this->found['User']['id']
				)
			)
		);
		
		$DKillStat = $this->KillStat->find('first', 
			array(
				'conditions' => array(
					'KillStat.type LIKE' => 'Defense', 
					'KillStat.user_id' => $this->found['User']['id']
				)
			)
		);
		
		$TKillStat = $this->KillStat->find('first', 
			array(
				'conditions' => array(
					'KillStat.type LIKE' => 'Total', 
					'KillStat.user_id' => $this->found['User']['id']
				)
			)
		);
		$this->KillStat->delete($TKillStat['KillStat']['id']);
		$this->KillStat->delete($DKillStat['KillStat']['id']);
		$this->KillStat->delete($OKillStat['KillStat']['id']);
		//$this->found['User']['facebook_id'] = 0;
		//$this->User->save($this->found, array('validate' => true, 'fieldList' => array('facebook_id')));
		if($this->found['User']['premium']){
			//$this->Premium->addNewTransaction($this->account['Account']['id'], "Restart Credit", floor($this->found['User']['premium'] / 48), "Remaining premium time was converted to PP");
		}
		$this->User->delete($this->found['User']['id']);
		$this->redirect(array('action' => 'create'));
	}
	
	
	function create() {
		$this->layout = 'default';
		$this->set('facebookID', $this->facebook_id);
		$this->set('account', $this->account);
		$this->found = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id)));
		if(!empty($this->found)){
			$this->redirect('/users');
		}
		if($this->data) {
			switch($this->data['User']['race'])
			{
				case("Human"):
					$generals = 5;
					break;
				case("Dwarf"):
					$generals = 6;
					break;					
				case("Elf"):
					$generals = 4;
					break;					
				case("Gnome"):
					$generals = 4;
					break;
				case("Orc"):
					$generals = 7;
					break;	
			}
			$this->data['User']['facebook_id'] = $this->facebook_id;
			// Find the new rank
			$this->data['User']['apex'] = ("Y-m-d H:m:s");
			$this->data['User']['rank'] = $this->User->getTotalPlayers() + 1;
			$this->data['User']['honor_rank'] = $this->data['User']['rank'];
			$this->data['User']['account_id'] = $this->account['Account']['id'];
			$user = $this->User->save($this->data);
			// If the user was saved, Now we add this information to the data
			// and save the Profile.
			//debug($user);
			if (!empty($user)) {
				// The ID of the newly created user has been set
				// as $this->User->id.
				// We'll have to do this for every table we have :\
				$this->data['Resource']['user_id'] = $this->User->id;
				$this->data['Building']['user_id'] = $this->User->id;
				$this->data['Army']['user_id'] = $this->User->id;
				if($this->data['User']['race'] == "Human")
					$this->data['Army']['bas'] = 105;
				$this->data['Army']['generals'] = $generals;
				$this->data['Population']['user_id'] = $this->User->id;
				$this->data['Spell']['user_id'] = $this->User->id;
				$this->data['Weapon']['user_id'] = $this->User->id;
				$this->data['Technology']['user_id'] = $this->User->id;
				$this->data['Attack']['user_id'] = $this->User->id;
				$this->data['Attack']['rounds'] = -5;
				
								
				// Because our User hasOne Profile, we can access
				// the Profile model through the User model:
				// And then we have to save them all >.<
				$this->User->Attack->save($this->data);
				$this->User->Technology->save($this->data);
				$this->User->Weapon->save($this->data);
				$this->User->Resource->save($this->data);
				$this->User->Building->save($this->data);
				$this->User->Army->save($this->data);
				$this->User->Population->save($this->data);
				$this->User->Spell->save($this->data);
    			/*
				$this->loadModel('Alliance');
				$alliances = $this->Alliance->find('all');
				$random = mt_rand(0,2);
				foreach($alliances as $alliance):
					if($alliance['Alliance']['id'] == $random):
					
					else:
						if($alliance['Alliance']['members'] < ($alliances[$random]['Alliance']['members'] - 5)):
							if($random < 2):
								$random++;
							else:
								$random = 0;
							endif;
						endif;
					endif;
				endforeach;
				$alliances[$random]['Alliance']['members']++;
    			$this->Alliance->save($alliances[$random]);
    			$this->data['User']['alliance_id'] = $alliances[$random]['Alliance']['id'];
    			$this->User->save($this->data, array('validate' => true, 'fieldList' => array('alliance_id')));
    			$this->Alliance->updateAllianceStats();
				*/
				$this->Report->makeNewReport($this->User->id, 'You joined Call to Arms, congratulations! Be sure to check out the Call to Arms chat room, found at the bottom on the chat bar, where you can find helpful players online. This is especially important if you are new to the game. You can also brush up on your CTA knowledge by visiting the <a href="http://www.provokeone.com/ctagamewiki/index.php?title=Game_Guide_Contents" target="_new">Call to Arms Wiki</a>.' , "Create Account", $_SERVER['REMOTE_ADDR'], $this->User->id, "You created an account!");
				$this->Message->sendMessage('This is your mailbox, you may use it to contact anybody in Call to Arms. The "To:" field should be the ruler name of the person you\'re contacting. Title and Body must not be blank.', 'Your mailbox', $this->User->id, $this->User->id);
				$this->Message->sendRules();
				$this->Session->setFlash('Kingdom registered');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	
	function suspended() {
		

		$this->layout = 'newuser';
	}
	
	function invite() {

		$this->layout = 'premium';
		$this->set('user', $this->found);
	}
}
?>