<?php

class PremiumsController extends AppController {    
	var $name = 'Premiums';
	var $layout = 'redesign';
	var $user;
	var $uses = array('Premium');
	
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		parent::beforeFilter();
		$this->loadModel('User');
		$this->User->recursive = 2;
        
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->User->id = $this->user['User']['id'];
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id);  
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		$this->menu = array(
			array(
				'name' => 'Use PP',
				'link' => '/premiums'
			),
			array(
				'name' => 'Buy/Obtain PP',
				'link' => '/premiums/buy'
			),
			array(
				'name' => 'Premium History',
				'link' => '/premiums/premiumHistory'
			),
			array(
				'name' => 'Transfer PP',
				'link' => '/premiums/send'
			)
		);
		$this->set('menu', $this->menu);
    }  
    
	function premiumHistory() {
		$this->set('selected', 'Premium History');
		$this->paginate = array('conditions' => array('Premium.account_id' => $this->account['Account']['id']), 'order' => 'Premium.date DESC');
		$this->set('premiums', $this->paginate('Premium'));
		$this->set('user', $this->user);
	}
    
	/******
	 * Give the view basic data to make the table
	 */	
	function index() {  
		$this->set('selected', 'Use PP');
		$this->loadModel('RaceChange');
		$this->set('user', $this->user);
		$this->set('changePrice', (10 + $this->RaceChange->countChanges($this->user['User']['id'])));
		if(!empty($this->me['gender'])){
			if($this->me['gender'] == "male"){
				$sex = 1;
			}
			else {
				$sex = 0;
			}
			$this->set('sex', $sex);
		}
		if(!empty($this->me['gender']['birthday'])){
			$birthdate = explode("/", $this->me['gender']['birthday']);
			if(!empty($birthdate[2])){
				$birthday = $birthdate[0] . "-" . $birthdate[1] . "-" . $birthdate[2];
				$this->set('birthday', $birthday);
			}
		}
		
		$this->set('endUserId', $this->account_id);
	}
	
	function buy() {
		$this->set('endUserId', $this->account_id);
		$this->set('selected', 'Buy/Obtain PP');
		//$this->layout = 'premiumbuy';
		$this->set('user', $this->user);
		$this->set('account', $this->account);
	}
	
	function send() {
		$this->set('selected', 'Transfer PP');
		if(!empty($this->data)){
			$receiver = Classregistry::init('User')->find(
				'first', 
				array(
					'conditions' => array(
						'User.ruler' => $this->data['Premium']['receiver']
					), 
					'fields' => array(
						'User.premium_points, User.id, User.account_id'
					), 
					'limit' => 20
				)
			);
			if(!empty($receiver)){
				if($this->premium['Premium']['new_amount'] >= $this->data['Premium']['amount']){
					$this->loadModel('Account');
					$sent = $this->data['Premium']['amount'] * -1;
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Transfer", $sent, "Sent to " . $this->data['Premium']['receiver']);
					$receivedAccount = $this->Account->find('first', array('conditions' => array('Account.id' => $receiver['User']['account_id'])));
					$this->Premium->addNewTransaction($receivedAccount['Account']['id'], "Transfer", $this->data['Premium']['amount'], "Received from " . $this->user['User']['ruler']);
					$this->Session->setFlash("Successfully sent " . $this->data['Premium']['amount'] . "PP to " . $this->data['Premium']['receiver']);
					$this->premium = $this->Premium->find('first', array('conditions' => array('Premium.account_id' => $this->account['Account']['id']), 'order' => 'Premium.date DESC'));
					if($this->premium) {
			       		$this->set('points', $this->premium['Premium']['new_amount']);
					} else {
						$this->set('points', 0);
					}
				} else {
					$this->Session->setFlash('You do not have enough Premium Points to send that many!');
				}
			} else {
				$this->Session->setFlash('That user could not be found!');
			}
		}
		$this->set('user', $this->user);
	}
	
	function sendAutoComplete() {
		$this->set('receivers', Classregistry::init('User')->find('all', array('conditions' => array('User.ruler LIKE' => $this->params['url']['q'] . '%'), 'fields' => array('ruler'), 'limit' => 20)));
		$this->layout = 'ajax';
	}
	
	function rockyou() {
		$this->set('user', $this->user);
		if(!empty($this->me['gender'])){
			if($this->me['gender'] == "male"){
				$sex = 1;
			}
			else {
				$sex = 0;
			}
			$this->set('sex', $sex);
		}
		if(!empty($this->me['gender']['birthday'])){
			$birthdate = explode("/", $this->me['gender']['birthday']);
			if(!empty($birthdate[2])){
				$birthday = $birthdate[0] . "-" . $birthdate[1] . "-" . $birthdate[2];
				$this->set('birthday', $birthday);
			}
		}
		
		$this->set('endUserId', $this->account_id);
		$this->set('userGo', substr( md5($this->account_id . '2620' . '201234dfb56242238867930c79c1702c' ), 0, 10 ));
		$this->set('applicationId', 2620);
		
	}
	
	function buyMonths($months = null){
		switch($months){
			case "1":
				if($this->premium['Premium']['new_amount'] >= 15) {
					$this->user['User']['premium'] += 720;
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -15, "1 months / 720 ticks");
					$this->Session->setFlash("You've successfully bought 1 month of premium! Congratulations!");
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('premium')));
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.");
				break;
			case "2": 
				if($this->premium['Premium']['new_amount'] >= 30) {
					$this->user['User']['premium'] += 1440;
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -30, "2 months / 1440 ticks");
					$this->Session->setFlash("You've successfully bought 2 months of premium! Congratulations!");
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('premium')));
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.");
				break;
			case "4": 
				if($this->premium['Premium']['new_amount'] >= 60) {
					$this->user['User']['premium'] += 2880;
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -60, "4 months / 2880 ticks");
					$this->Session->setFlash("You've successfully bought 4 months of premium! Congratulations!");
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('premium')));
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.");
				break;
			case "10": 
				if($this->premium['Premium']['new_amount'] >= 150) {
					$this->user['User']['premium'] += 7200;
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -150, "10 months / 7200 ticks");
					$this->Session->setFlash("You've successfully bought 10 months of premium! Congratulations!");
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('premium')));
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.");
				break;
			default: 
				$this->Session->setFlash('There was a problem in processing your request.');
				break;
		}
		
		$this->redirect(array('action' => 'index' . $this->parms));
	}
	
	function changeName() {
		if(!empty($this->data)){
			if($this->premium['Premium']['new_amount'] > 20){
				$this->data['User']['premium_points'] = $this->premium['Premium']['new_amount'];
				$this->User->id = $this->user['User']['id'];
				if($this->User->save($this->data['User'], array('validate' => true, 'fieldList' => array('ruler', 'kingdom')))){
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -20, "Name change");
					$this->Session->setFlash('You have successfully changed your name to ' . $this->data['User']['ruler'] . ' of ' . $this->data['User']['kingdom'] . '!');
					$this->redirect(array('action' => 'index' . $this->parms));
				}
				else
					$this->Session->setFlash('Something went wrong while saving your new name, it likely is already in use!');
				
			}else {
				$this->Session->setFlash('You do not have enough premium points to change your name.');
			}
		}
		
		$this->set('user', $this->user);
	}
	
	function changeRace($race = null){
		$this->loadModel('RaceChange');
		$previousRace = $this->user['User']['race'];
		$changed = false;
		switch($race){
			case "Dwarf":
				if($this->premium['Premium']['new_amount'] >= 10 + $this->RaceChange->countChanges($this->user['User']['id'])) {
					$this->user['User']['race'] = $race;
					
					// Healing units reset
					// No loadmodel() because function only used once
					Classregistry::init('Center')->setCenterUnits($this->user['User']['id'], 0, 0, 0, 0, 0);
					
					// Army Reset 
					$this->user['Army']['weak'] = 0;
					$this->user['Army']['standard'] = 0;
					$this->user['Army']['range'] = 0;
					$this->user['Army']['mount'] = 0;
					$this->user['Army']['small_elite'] = 0;
					$this->user['Army']['big_elite'] = 0;
					$this->user['Army']['weak_training'] = 0;
					$this->user['Army']['standard_training'] = 0;
					$this->user['Army']['range_training'] = 0;
					$this->user['Army']['mount_training'] = 0;
					$this->user['Army']['small_elite_training'] = 0;
					$this->user['Army']['big_elite_training'] = 0;
					$this->user['Army']['generals'] = 6;
					if($this->user['User']['premium'])
						$this->user['Army']['strategy'] = "Shield Line";
					else 
						$this->user['Army']['strategy'] = "None";
					
					// Weapon reset
					$this->user['Weapon']['vehicle'] = 0;
					$this->user['Weapon']['big_melee'] = 0;
					$this->user['Weapon']['small_melee'] = 0;
					$this->user['Weapon']['big_armor'] = 0;
					$this->user['Weapon']['small_armor'] = 0;
					$this->user['Weapon']['range'] = 0;
					$this->user['Weapon']['vehicle_building'] = 0;
					$this->user['Weapon']['big_melee_building'] = 0;
					$this->user['Weapon']['small_melee_building'] = 0;
					$this->user['Weapon']['big_armor_building'] = 0;
					$this->user['Weapon']['small_armor_building'] = 0;
					$this->user['Weapon']['range_building'] = 0;
					
					$this->Session->setFlash("You've successfully made the switch to a Dwarf! Congratulations!<br /> You've received an extra 30 hours of protection to rebuild.");
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -(10 + $this->RaceChange->countChanges($this->user['User']['id'])), "Name change");
					if($this->user['User']['protection'] < 30){
						$this->user['User']['protection'] += (30 - $this->user['User']['protection']);
					}
					
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('race', 'protection')));
					$this->User->Army->save($this->user['Army']);
					$this->User->Weapon->save($this->user['Weapon']);
					foreach($this->user['Attack'] as $attack):
						if($attack['rounds'] > 0) 
							$this->User->Attack->delete($attack['id']);
					endforeach; 
					
					$changed = true;
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.<br /> You've received an extra 30 hours of protection to rebuild.");
				break;
			case "Orc": 
				if($this->premium['Premium']['new_amount'] >= 10 + $this->RaceChange->countChanges($this->user['User']['id'])) {
					// Healing units reset
					// No loadmodel() because function only used once
					Classregistry::init('Center')->setCenterUnits($this->user['User']['id'], 0, 0, 0, 0, 0);
					
					$this->user['User']['race'] = $race;
					$this->user['Army']['weak'] = 0;
					$this->user['Army']['standard'] = 0;
					$this->user['Army']['range'] = 0;
					$this->user['Army']['mount'] = 0;
					$this->user['Army']['small_elite'] = 0;
					$this->user['Army']['big_elite'] = 0;
					$this->user['Army']['weak_training'] = 0;
					$this->user['Army']['standard_training'] = 0;
					$this->user['Army']['range_training'] = 0;
					$this->user['Army']['mount_training'] = 0;
					$this->user['Army']['small_elite_training'] = 0;
					$this->user['Army']['big_elite_training'] = 0;
					$this->user['Army']['generals'] = 7;
					if($this->user['User']['premium'])
						$this->user['Army']['strategy'] = "Surrounding";
					else 
						$this->user['Army']['strategy'] = "None";
					
					// Weapon reset
					$this->user['Weapon']['vehicle'] = 0;
					$this->user['Weapon']['big_melee'] = 0;
					$this->user['Weapon']['small_melee'] = 0;
					$this->user['Weapon']['big_armor'] = 0;
					$this->user['Weapon']['small_armor'] = 0;
					$this->user['Weapon']['range'] = 0;
					$this->user['Weapon']['vehicle_building'] = 0;
					$this->user['Weapon']['big_melee_building'] = 0;
					$this->user['Weapon']['small_melee_building'] = 0;
					$this->user['Weapon']['big_armor_building'] = 0;
					$this->user['Weapon']['small_armor_building'] = 0;
					$this->user['Weapon']['range_building'] = 0;
					
					$this->Session->setFlash("You've successfully made the switch to a Orc! Congratulations!");
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -(10 + $this->RaceChange->countChanges($this->user['User']['id'])), "Name change");
					if($this->user['User']['protection'] < 30){
						$this->user['User']['protection'] += (30 - $this->user['User']['protection']);
					}
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('race', 'protection')));
					$this->User->Army->save($this->user['Army']);
					$this->User->Weapon->save($this->user['Weapon']);
					foreach($this->user['Attack'] as $attack):
						if($attack['rounds'] > 0) 
							$this->User->Attack->delete($attack['id']);
					endforeach; 
					$changed = true;
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.<br /> You've received an extra 30 hours of protection to rebuild.");
				break;
			case "Gnome": 
				if($this->premium['Premium']['new_amount'] >= 10 + $this->RaceChange->countChanges($this->user['User']['id'])) {
					// Healing units reset
					// No loadmodel() because function only used once
					Classregistry::init('Center')->setCenterUnits($this->user['User']['id'], 0, 0, 0, 0, 0);
					
					$this->user['User']['race'] = $race;
					$this->user['Army']['weak'] = 0;
					$this->user['Army']['standard'] = 0;
					$this->user['Army']['range'] = 0;
					$this->user['Army']['mount'] = 0;
					$this->user['Army']['small_elite'] = 0;
					$this->user['Army']['big_elite'] = 0;
					$this->user['Army']['weak_training'] = 0;
					$this->user['Army']['standard_training'] = 0;
					$this->user['Army']['range_training'] = 0;
					$this->user['Army']['mount_training'] = 0;
					$this->user['Army']['small_elite_training'] = 0;
					$this->user['Army']['big_elite_training'] = 0;
					$this->user['Army']['generals'] = 4;
					if($this->user['User']['premium'])
						$this->user['Army']['strategy'] = "Far Fighting";
					else 
						$this->user['Army']['strategy'] = "None";
					
					// Weapon reset
					$this->user['Weapon']['vehicle'] = 0;
					$this->user['Weapon']['big_melee'] = 0;
					$this->user['Weapon']['small_melee'] = 0;
					$this->user['Weapon']['big_armor'] = 0;
					$this->user['Weapon']['small_armor'] = 0;
					$this->user['Weapon']['range'] = 0;
					$this->user['Weapon']['vehicle_building'] = 0;
					$this->user['Weapon']['big_melee_building'] = 0;
					$this->user['Weapon']['small_melee_building'] = 0;
					$this->user['Weapon']['big_armor_building'] = 0;
					$this->user['Weapon']['small_armor_building'] = 0;
					$this->user['Weapon']['range_building'] = 0;
					
					$this->Session->setFlash("You've successfully made the switch to a $race! Congratulations!");
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -(10 + $this->RaceChange->countChanges($this->user['User']['id'])), "Name change");
					if($this->user['User']['protection'] < 30){
						$this->user['User']['protection'] += (30 - $this->user['User']['protection']);
					}
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('race', 'protection')));
					$this->User->Army->save($this->user['Army']);
					$this->User->Weapon->save($this->user['Weapon']);
					foreach($this->user['Attack'] as $attack):
						if($attack['rounds'] > 0) 
							$this->User->Attack->delete($attack['id']);
					endforeach; 
					$changed = true;
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.<br /> You've received an extra 30 hours of protection to rebuild.");
				break;
			case "Elf": 
				if($this->premium['Premium']['new_amount'] >= 10 + $this->RaceChange->countChanges($this->user['User']['id'])) {
					$this->user['User']['race'] = $race;
					// Healing units reset
					// No loadmodel() because function only used once
					Classregistry::init('Center')->setCenterUnits($this->user['User']['id'], 0, 0, 0, 0, 0);
					
					// Army Reset
					$this->user['Army']['weak'] = 0;
					$this->user['Army']['standard'] = 0;
					$this->user['Army']['range'] = 0;
					$this->user['Army']['mount'] = 0;
					$this->user['Army']['small_elite'] = 0;
					$this->user['Army']['big_elite'] = 0;
					$this->user['Army']['weak_training'] = 0;
					$this->user['Army']['standard_training'] = 0;
					$this->user['Army']['range_training'] = 0;
					$this->user['Army']['mount_training'] = 0;
					$this->user['Army']['small_elite_training'] = 0;
					$this->user['Army']['big_elite_training'] = 0;
					$this->user['Army']['generals'] = 4;
					if($this->user['User']['premium'])
						$this->user['Army']['strategy'] = "Energy Gathering";
					else 
						$this->user['Army']['strategy'] = "None";
					
					// Weapon reset
					$this->user['Weapon']['vehicle'] = 0;
					$this->user['Weapon']['big_melee'] = 0;
					$this->user['Weapon']['small_melee'] = 0;
					$this->user['Weapon']['big_armor'] = 0;
					$this->user['Weapon']['small_armor'] = 0;
					$this->user['Weapon']['range'] = 0;
					$this->user['Weapon']['vehicle_building'] = 0;
					$this->user['Weapon']['big_melee_building'] = 0;
					$this->user['Weapon']['small_melee_building'] = 0;
					$this->user['Weapon']['big_armor_building'] = 0;
					$this->user['Weapon']['small_armor_building'] = 0;
					$this->user['Weapon']['range_building'] = 0;
					
					$this->Session->setFlash("You've successfully made the switch to a $race! Congratulations!<br /> You've received an extra 30 hours of protection to rebuild.");
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -(10 + $this->RaceChange->countChanges($this->user['User']['id'])), "Name change");
					if($this->user['User']['protection'] < 30){
						$this->user['User']['protection'] += (30 - $this->user['User']['protection']);
					}
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('race', 'protection')));
					$this->User->Army->save($this->user['Army']);
					$this->User->Weapon->save($this->user['Weapon']);
					foreach($this->user['Attack'] as $attack):
						if($attack['rounds'] > 0) 
							$this->User->Attack->delete($attack['id']);
					endforeach; 
					$changed = true;
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.");
				break;
			case "Human": 
				if($this->premium['Premium']['new_amount'] >= 10 + $this->RaceChange->countChanges($this->user['User']['id'])) {
					// Healing units reset
					// No loadmodel() because function only used once
					Classregistry::init('Center')->setCenterUnits($this->user['User']['id'], 0, 0, 0, 0, 0);
					
					$this->user['User']['race'] = $race;
					$this->user['Army']['weak'] = 0;
					$this->user['Army']['standard'] = 0;
					$this->user['Army']['range'] = 0;
					$this->user['Army']['mount'] = 0;
					$this->user['Army']['small_elite'] = 0;
					$this->user['Army']['big_elite'] = 0;
					$this->user['Army']['weak_training'] = 0;
					$this->user['Army']['standard_training'] = 0;
					$this->user['Army']['range_training'] = 0;
					$this->user['Army']['mount_training'] = 0;
					$this->user['Army']['small_elite_training'] = 0;
					$this->user['Army']['big_elite_training'] = 0;
					$this->user['Army']['generals'] = 5;
					if($this->user['User']['premium'])
						$this->user['Army']['strategy'] = "Charging";
					else 
						$this->user['Army']['strategy'] = "None";
					
					// Weapon reset
					$this->user['Weapon']['vehicle'] = 0;
					$this->user['Weapon']['big_melee'] = 0;
					$this->user['Weapon']['small_melee'] = 0;
					$this->user['Weapon']['big_armor'] = 0;
					$this->user['Weapon']['small_armor'] = 0;
					$this->user['Weapon']['range'] = 0;
					$this->user['Weapon']['vehicle_building'] = 0;
					$this->user['Weapon']['big_melee_building'] = 0;
					$this->user['Weapon']['small_melee_building'] = 0;
					$this->user['Weapon']['big_armor_building'] = 0;
					$this->user['Weapon']['small_armor_building'] = 0;
					$this->user['Weapon']['range_building'] = 0;
					
					$this->Session->setFlash("You've successfully made the switch to a $race! Congratulations!<br /> You've received an extra 30 hours of protection to rebuild.");
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -(10 + $this->RaceChange->countChanges($this->user['User']['id'])), "Name change");
					if($this->user['User']['protection'] < 30){
						$this->user['User']['protection'] += (30 - $this->user['User']['protection']);
					}
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('race', 'protection')));
					$this->User->Army->save($this->user['Army']);
					$this->User->Weapon->save($this->user['Weapon']);
					foreach($this->user['Attack'] as $attack):
						if($attack['rounds'] > 0) 
							$this->User->Attack->delete($attack['id']);
					endforeach; 
					$changed = true;
				}
				else 
					$this->Session->setFlash("You do not have enough premium points to purchase this item.");
				break;
			default: 
				$this->Session->setFlash('There was a problem in processing your request.');
				break;
		}
		if($changed){
			$this->RaceChange->addChange($this->user['User']['id'], $race, $previousRace);
			// removes elf only spells from scheduler
			if($previousRace == "Elf"){
				$this->loadModel('MagicSchedule');
				$schedules = $this->MagicSchedule->find('all', array('conditions' => array('MagicSchedule.user_id' => $this->user['User']['id'])));
				foreach($schedules as $schedule):
					// Because I'm not sure an easier way to check the whole list, 
					// besides making an array with spell names and doing another foreach every time...
					if($schedule['MagicSchedule']['spell'] == "fiery_arrows" || $schedule['MagicSchedule']['spell'] == "hardened_armor" || 
					$schedule['MagicSchedule']['spell'] == "enchanted_weapons" || $schedule['MagicSchedule']['spell'] == "shadow_hall" || 
					$schedule['MagicSchedule']['spell'] == "ice_storm" ) {
						$this->MagicSchedule->delete($schedule['MagicSchedule']['id']);
					}
				endforeach;
				
			}
		}
		
		$this->redirect(array('action' => 'index' . $this->parms));
	}
	
	function changeProvince(){
		if (!empty($this->data)) {
			if($this->data['Premiums']['province_number'] < 100 && $this->data['Premiums']['province_number'] > 0 && ctype_digit($this->data['Premiums']['province_number'])) {
				if($this->User->countProvinceMembers($this->data['Premiums']['province'], $this->data['Premiums']['province_number']) > 7){
					unset($this->data);
					$this->Session->setFlash("That province is currently full. Please select another.");
					$this->redirect(array('action' => 'changeProvince' . $this->parms));
				}
				if($this->premium['Premium']['new_amount'] >= 10) {
					$this->user['User']['province'] = $this->data['Premiums']['province'];
					$this->user['User']['province_number'] = $this->data['Premiums']['province_number'];
					$this->Premium->addNewTransaction($this->account['Account']['id'], "Purchase", -10, "Name change");
					$this->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('province', 'province_number')));
					$this->Session->setFlash("You've succesfully made the switch to " . $this->data['Premiums']['province'] . " " . $this->data['Premiums']['province_number'] . "! Congratulations!");
				}
				else {
					$this->Session->setFlash('You do not have enough premium points!');
				}
			}
			else
				$this->Session->setFlash('You entered an invalid Province Number.');
			
			$this->redirect(array('action' => 'index' . $this->parms));
			
		}
		$this->set('user', $this->user);
	}    
	
}

?>