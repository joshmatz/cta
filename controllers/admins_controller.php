<?php
class AdminsController extends AppController {    
	var $name = 'Admins';
	var $uses = array();
	var $layout = 'default';

	function beforeFilter() {  
		parent::beforeFilter();
		$this->loadModel('User');
		
		if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council') {
			$this->menu = array(
				array(
					'name' => 'Return to Game',
					'link' => ''
				),
				array(
					'name' => 'Index',
					'link' => '/admins'
				),
				array(
					'name' => 'Score List',
					'link' => '/admins/scores'
				),
				array(
					'name' => 'Race Stats',
					'link' => '/admins/raceStats'
				),
				array(
					'name' => 'List Names',
					'link' => '/admins/listNames'
				),
				array(
					'name' => 'List suspensions',
					'link' => '/admins/listSuspendedNames'
				),
				array(
					'name' => 'Current Weapon Prices',
					'link' => '/admins/viewWeaponPrices'
				),
				array(
					'name' => 'Ip list',
					'link' => '/admins/iplist'
				),
				array(
					'name' => 'Redirect Test',
					'link' => '/admins/redirectTest'
				),
				array(
					'name' => 'KS to Land',
					'link' => '/admins/ksland'
				)
			);
			if($this->account['Account']['role'] == 'admin'){
				$this->menu[] = array(
					'name' => 'Set Role',
					'link' => '/admins/setRole'
				);
				$this->menu[] = array(
					'name' => 'Add Transaction',
					'link' => '/admins/addTransaction'
				);
				$this->menu[] = array(
					'name' => 'PHP Info',
					'link' => '/admins/php'
				);
			} else {
				
			}
		} else {
			$this->Session->setFlash('You do not have permission to access that function.');
			$this->redirect("/");
		}
		$this->set('menu', $this->menu);
    }  
    
    function ksland() {
    	if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council'){
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    		$this->redirect(array('action' => 'index'));

    	}
    }
    
    function redirectTest() {
    	$this->redirect('http://www.google.com');
    }
    
    function iplist() {
    	if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council'){
    		$this->loadModel('IpAddress');
    		$this->paginate = array(
				'limit' => 15,
				'order' => array(
					'IpAddress.last_used' => 'desc'
				)    
			);
			//$addresses = $this->paginate('IpAddress');
			$addresses = $this->IpAddress->find('all', array('limit' => 20));
			debug($addresses);
			foreach($addresses as &$address):
				if(!isset($check))
					$checks[] = $address['IpAddress']['ip_address'];
				foreach($checks as $check):
					if($check == $address['IpAddress']['ip_address'])
						unset($address);
					else {
						$checks[] = $address['IpAddress']['ip_address'];
						$address['IpAddress']['count'] = $this->IpAddress->find('count', array('conditions' => array('IpAddress.ip_address' => $address['IpAddress']['ip_address'])));
					}
				endforeach;
			
			endforeach;
			$this->set('addresses', $addresses);
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    		$this->redirect(array('action' => 'index'));
    	}
    }
    
    function php() {
    	
    }
    
    function viewWeaponPrices() {
    	if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council'){
    		$result = "";
    		$fake['User']['Resource']['wood'] = 0;
    		$fake['User']['Resource']['iron'] = 0;
    		$fake['User']['Resource']['gold'] = 0;
    		$this->loadModel('Weapon');
			$weapons = $this->Weapon->getWeaponNames("Gnome", $fake['User']['Resource']);
		    $gold = $this->Weapon->getWeaponGold("Gnome", $fake['User']['Resource']);
		    $iron = $this->Weapon->getWeaponIron("Gnome", $fake['User']['Resource']);
		    $wood = $this->Weapon->getWeaponWood("Gnome", $fake['User']['Resource']);
		    $result .= "<strong>Gnome:</strong><br />";
		    for($i = 0; $i < 6; $i++){
		    	$result .= $weapons[$i] . " costs " . $gold[$i] . " gold, " . $iron[$i] . " iron, and " . $wood[$i] . " wood. <br />";
		    }
		    $result .= "<br /><br />";
		    $result .= "<strong>Dwarf:</strong><br />";
	    	$weapons = $this->Weapon->getWeaponNames("Dwarf", $fake['User']['Resource']);
		    $gold = $this->Weapon->getWeaponGold("Dwarf", $fake['User']['Resource']);
		    $iron = $this->Weapon->getWeaponIron("Dwarf", $fake['User']['Resource']);
		    $wood = $this->Weapon->getWeaponWood("Dwarf", $fake['User']['Resource']);
		    for($i = 0; $i < 6; $i++){
		    	$result .= $weapons[$i] . " costs " . $gold[$i] . " gold, " . $iron[$i] . " iron, and " . $wood[$i] . " wood. <br />";
		    }
		    $result .= "<br /><br />";
		    $result .= "<strong>Human:</strong><br />";
	   		$weapons = $this->Weapon->getWeaponNames("Human", $fake['User']['Resource']);
		    $gold = $this->Weapon->getWeaponGold("Human", $fake['User']['Resource']);
		    $iron = $this->Weapon->getWeaponIron("Human", $fake['User']['Resource']);
		    $wood = $this->Weapon->getWeaponWood("Human", $fake['User']['Resource']);
		    for($i = 0; $i < 6; $i++){
		    	$result .= $weapons[$i] . " costs " . $gold[$i] . " gold, " . $iron[$i] . " iron, and " . $wood[$i] . " wood. <br />";
		    }
		    $result .= "<br /><br />";
		    $result .= "<strong>Elf:</strong><br />";
	    	$weapons = $this->Weapon->getWeaponNames("Elf", $fake['User']['Resource']);
		    $gold = $this->Weapon->getWeaponGold("Elf", $fake['User']['Resource']);
		    $iron = $this->Weapon->getWeaponIron("Elf", $fake['User']['Resource']);
		    $wood = $this->Weapon->getWeaponWood("Elf", $fake['User']['Resource']);
		    for($i = 0; $i < 6; $i++){
		    	$result .= $weapons[$i] . " costs " . $gold[$i] . " gold, " . $iron[$i] . " iron, and " . $wood[$i] . " wood. <br />";
		    }
		    $result .= "<br /><br />";
		    $result .= "<strong>Orc:</strong><br />";
	    	$weapons = $this->Weapon->getWeaponNames("Orc", $fake['User']['Resource']);
		    $gold = $this->Weapon->getWeaponGold("Orc", $fake['User']['Resource']);
		    $iron = $this->Weapon->getWeaponIron("Orc", $fake['User']['Resource']);
		    $wood = $this->Weapon->getWeaponWood("Orc", $fake['User']['Resource']);
		    for($i = 0; $i < 6; $i++){
		    	$result .= $weapons[$i] . " costs " . $gold[$i] . " gold, " . $iron[$i] . " iron, and " . $wood[$i] . " wood. <br />";
		    }
		    $result .= "<br /><br />";
		    $this->set('result', $result);
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    		$this->redirect(array('action' => 'index'));
    	}

    }
    
    function addTransaction() {
        if($this->account['Account']['role'] == 'admin'){
    		if(isset($this->data)){
				$this->loadModel('Premium');
				$this->Premium->addNewTransaction($this->data['Premium']['account_id'], $this->data['Premium']['type'], $this->data['Premium']['change'], $this->data['Premium']['information']);
    			$this->Session->setFlash('Transaction added.');
    		} else {
    			
    		}
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    		$this->redirect(array('action' => 'index'));
    	}
    }
    
    function setRole($facebook_id = null, $role = null) {
    	if($this->account['Account']['role'] == 'admin'){
    		if(($facebook_id && $role) || isset($this->data)){
    			if($facebook_id && $role) {
    				$role = $this->Account->find('first', array('conditions' => array('Account.facebook_id' => $facebook_id)));
    				if($role){
    					$role['Account']['role'] = $role;
    					$this->Account->save($role);
    				} else {
    					$this->Session->setFlash('No account found');
    				}
    				$this->Session->setFlash('Role Set');
    			} elseif($this->data['Account']['facebook_id']){
    				$role = $this->Account->find('first', array('conditions' => array('Account.facebook_id' => $this->data['Account']['facebook_id'])));
    				if($role){
    					$role['Account']['role'] = $this->data['Account']['role'];
    					$this->Account->save($role);
    				} else {
    					$this->Session->setFlash('No account found');
    				}
    				
    				$this->Session->setFlash('Role Set');
    			} else {
    				$this->Session->setFlash('Invalid data');
    			}  			
    		} else {
    			
    		}
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	if($this->data){
    		$this->redirect(array('action' => 'index'));
    	}
    }
    
    function deleteEmptyStats () {
    	if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council'){
    		$this->loadModel('KillStat');
    		
    		$stats = $this->KillStat->find('all', array('fields' => 'KillStat.id, KillStat.user_id'));
    		foreach($stats as $stat):
    			$player = $this->User->find('first', array('conditions' => array('User.id' => $stat['KillStat']['user_id']), 'fields' => 'User.id', 'recursive' => 0));
    			if(empty($player)) {
    				$this->KillStat->delete($stat['KillStat']['id']);
    			}
    		endforeach;
    		
    		$this->Session->setFlash('Empty Stats deleted.');
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	$this->redirect(array('action' => 'index'));
    }
    
    function resetTime() {
    	$time = date("r"); 
		$strToTime = strtotime($time);
		$resetTime = strtotime("Thu, 23 Dec 2010 12:00:00 -0600 "); 
		if($strToTime <= $resetTime) {
			$this->Session->setFlash("time: $time <br /> strToTime: $strToTime <br /> resetTime: $resetTime<br />");
		} else {
			$this->Session->setFlash('no time!');
		}
		$this->redirect(array('action' => 'index'));
    }
    
    function allianceFames() {
    	if($this->account['Account']['role'] == 'admin'){
    		$this->loadModel('AllianceFame');
    		$this->loadModel('Alliance');
    		$round = 4;
    		$alliances = $this->Alliance->find('all');
        	
        	foreach($alliances as $alliance):

				$hofAlliance['AllianceFame']['name'] = $alliance['Alliance']['short_name'];
				$hofAlliance['AllianceFame']['score'] = $alliance['Alliance']['score'];
				$hofAlliance['AllianceFame']['score_rank'] = $alliance['Alliance']['score_rank'];
				$hofAlliance['AllianceFame']['oks'] = $alliance['Alliance']['oks'];
				$hofAlliance['AllianceFame']['oks_rank'] = $alliance['Alliance']['oks_rank'];
				$hofAlliance['AllianceFame']['dks'] = $alliance['Alliance']['dks'];
				$hofAlliance['AllianceFame']['dks_rank'] = $alliance['Alliance']['dks_rank'];
				$hofAlliance['AllianceFame']['tks'] = $alliance['Alliance']['ks'];
				$hofAlliance['AllianceFame']['tks_rank'] = $alliance['Alliance']['ks_rank'];
				$hofAlliance['AllianceFame']['honor'] = $alliance['Alliance']['honor'];
				$hofAlliance['AllianceFame']['honor_rank'] = $alliance['Alliance']['honor_rank'];
				$hofAlliance['AllianceFame']['members'] = $alliance['Alliance']['members'];
				$hofAlliance['AllianceFame']['round'] = $round;
				
				$this->AllianceFame->create();
				$this->AllianceFame->save($hofAlliance);
        	endforeach;
        	
    		$this->Session->setFlash('Fames saved');
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	$this->redirect(array('action' => 'index'));
    	
    }
    
    function accounts () {
    	if($this->account['Account']['role'] == 'admin'){
    		$this->loadModel('Account');
    		
    		$users = $this->User->find('all', array('fields' => 'User.facebook_id, User.suspended'));
    		
    		foreach($users as $user):
    			$account = $this->Account->find('first', array('conditions' => array('Account.facebook_id' => $user['User']['facebook_id'])));
    			if(empty($account)){
	    			$this->account['Account']['facebook_id'] = $user['User']['facebook_id'];
					$this->account['Account']['created'] = date('c', time());
					$this->account['Account']['auto_login'] = 0;
					$this->account['Account']['suspended'] = $user['User']['suspended'];
					$this->Account->create();
					$this->Account->save($this->account);
    			}
    		endforeach;
    		$this->Session->setFlash('Accounts created');
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	$this->redirect(array('action' => 'index'));
    }
    
    function index(){
    	if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council'){
    		
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	
    	$this->set('account', $this->account);
    }
    
    function scores() {
    	if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council'){
    		$players = $this->User->find('all', array('fields' => 'User.score', 'recursive' => 0, 'sort' => 'User.score ASC'));
    		
    		$scores[] = array('score' => 0, 'count' => 1);
    		$found = false;
    		foreach($players as $player):
    			foreach($scores as &$score):
    				if($score['score'] == $player['User']['score']) {
    					$score['count']++;
    					$found = true;
    					break;
    				}
    			endforeach;
    			if(!$found){
    				$scores[] = array('score' => $player['User']['score'], 'count' => 1);
    			}
    			$found = false;
    		endforeach;
    		
    		$this->set('scores', $scores);
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	
    	$this->set('account', $this->account);
    }
    
    function raceStats(){
    	if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council'){
    		$this->set('human', $this->User->find('count', array('conditions' => array('User.race' => 'Human'))));
    		$this->set('gnome', $this->User->find('count', array('conditions' => array('User.race' => 'Gnome'))));
    		$this->set('orc', $this->User->find('count', array('conditions' => array('User.race' => 'Orc'))));
    		$this->set('elf', $this->User->find('count', array('conditions' => array('User.race' => 'Elf'))));
    		$this->set('dwarf', $this->User->find('count', array('conditions' => array('User.race' => 'Dwarf'))));
    		
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	
    	$this->set('account', $this->account);
    }
    
    function getRockYou($id = null){
    	if($this->account['Account']['role'] == 'admin' ) {
    		if(isset($id)){
    			if(!isset($facebook_id)){
    				$user = $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => 'User.facebook_id'));
    			}
				$this->set('rockYou', $user['User']['facebook_id'] . "-" . "2620" . "-" . strtolower(substr(md5(525227112 . 2620 . "201234dfb56242238867930c79c1702c"), 0, 10 )));
    		}
		} else {
			$this->Session->setFlash('You do not have permission to access that function.');
		}
		
		$this->set('account', $this->account);
    }
	
	function listNames(){
    	if($this->account['Account']['role'] == 'council' || $this->account['Account']['role'] == 'admin' ) {
    		
    		$this->paginate = array('fields' => 'User.id, User.facebook_id', 'recursive' => 0, 'limit' => 25);
    		$data = $this->paginate('User', array('User.suspended !=' => 1));
    		$this->set('data', $data);
    		$this->set('facebook', $this->facebook);

    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	$this->set('account', $this->account);
    }
    
    function listSuspendedNames() {
    	if($this->account['Account']['role'] == 'council' || $this->account['Account']['role'] == 'admin' ) {
    		$this->loadModel('Account');
    		$accounts = $this->Account->find('list', array('conditions' => array('Account.suspended' => 1), 'fields' => array('Account.id')));
    		$users = $this->User->find('all', array('conditions' => array('User.account_id' => $accounts, 'User.suspended' => 0), 'fields' => array('User.id', 'User.account_id', 'User.suspended')));
    		foreach($users as &$user):
    			$user['User']['suspended'] = 1;
    		endforeach;
    		$this->User->saveAll($users);
    		debug($users);

    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	$this->set('account', $this->account);
    }
    
    function deleteSuspended() {
    	if($this->account['Account']['role'] == 'council' || $this->account['Account']['role'] == 'admin' ) {
    		$users = $this->User->find('list', array('conditions' => array('User.suspended' => 1), 'fields' => array('User.id')));
    		$this->User->deleteAll($users);
    		$this->set('users', $users);

    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	$this->set('account', $this->account);
    }
    
    function suspend($id = null){
		if($this->account['Account']['role'] == 'admin' ) {
			// If data coming from form list.
			// Do this so we can send IDs in via url for other pages without forms.
			if(isset($this->data)){
				foreach($this->data['Users']['id'] as $key => $value) {
					if($value != 0) {
						$user['User']['id'] = $value;
						$user['User']['suspended'] = 1;
						$this->User->save($user);
					}
				}
				$this->Session->setFlash('Users suspended successfully.');
			} // end if data coming from form list
			
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	$this->redirect(array('action' => 'index'));
    	$this->set('account', $this->account);
    }
    
    function unsuspend($id = null){
		if($this->account['Account']['role'] == 'admin' ) {
			// If data coming from form list.
			// Do this so we can send IDs in via url for other pages without forms.
			if(isset($this->data)){
				foreach($this->data['Users']['id'] as $key => $value) {
					if($value != 0) {
						$user['User']['id'] = $value;
						$user['User']['suspended'] = 0;
						$this->User->save($user);
					}
				}
				$this->Session->setFlash('Users suspended successfully.');
			} // end if data coming from form list
			
    	} else {
    		$this->Session->setFlash('You do not have permission to access that function.');
    	}
    	$this->redirect(array('action' => 'index'));
    	$this->set('account', $this->account);
    }
}