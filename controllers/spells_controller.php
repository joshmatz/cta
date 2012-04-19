<?php
class SpellsController extends AppController {    
	var $name = 'Spells';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	
	
	/**
	 * Only listing one player in Alderd province?
	 */
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		parent::beforeFilter();
		$this->Spell->recursive = 2;
        $this->user = $this->Spell->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->Spell->id = $this->user['Spell']['id'];
		$this->Spell->User->id = $this->user['User']['id'];
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		
		$menu = array(
			array(
				'name' => 'Magician\'s Room',
				'link' => '/spells'
			),
			array(
				'name' => 'Manage Schedule',
				'link' => '/spells/schedule'
			)
		);
		if($this->account['Account']['role'] == 'admin' || $this->account['Account']['role'] == 'council'){
			//$menu[] = array('name' => 'Players using GB', 'link' => '/admin/spells/gb');
		}
		$this->set('menu', $menu);
    }  
    
    function admin_gb(){
    	$godUsers = $this->Spell->find('all', array('conditions' => array('User.rank < ' => 40, 'Spell.god_blessing != ' => 0), 'fields' => array('Spell.god_blessing, User.ruler, User.rank'), 'order' => 'User.rank ASC'));
    	$this->set('godUsers', $godUsers);
    	$this->set('user', $this->user);
    }
    
	/******
	 * Give the view basic data to make the table
	 */	
	function index() {  
		$this->set('selected', 'Magician\'s Room');
		$this->set('willRest', $this->Spell->User->Army->getRestingWizards($this->user['User']));
		$this->set('user', $this->user);
	}
	
	function autoComplete() {
		$this->set('receivers', Classregistry::init('User')->find('all', array('conditions' => array('User.ruler LIKE' => $this->params['url']['q'] . '%'), 'fields' => array('ruler'), 'limit' => 20)));
		$this->layout = 'ajax';
	}
	
	function searchSpell() {
		if($this->data){
			$found = $this->Spell->User->find('first', array('conditions' => array('User.ruler' => $this->data['Spell']['receiver']), 'fields' => array('id')));
			if(!empty($found)){
				$this->redirect(array('controller' => 'spells', 'action' => 'selectSpell/' . $found['User']['id']));
			} else {
				$this->Session->setFlash('Kingdom not found.');
				$this->redirect(array('controller' => 'spells', 'action' => 'index'));
			}
		} else {
			$this->Session->setFlash('The page was accessed incorrectly.');
			$this->redirect(array('controller' => 'spells', 'action' => 'index'));
		}
	}

	function gate($spell = null, $wizards = null){
		if($this->user['User']['id'] == 5 && false){
			$this->Session->setFlash('This spell is termporarily disabled!');
			$this->redirect(array('controller' => 'spells', 'action' => 'index'));
		}
		if($spell = 'gate') {
			if($wizards <= $this->user['User']['Army']['wizard']) { 
				//You should have 1 wizards per 3 troops
				$units = $this->Spell->User->Army->getFullArmyData($this->user['User']);
				$troops = 0;
				foreach($units as $unit):
					$troops += $unit['amount_away'];
				endforeach;
				if($troops > 0)
					$wizardsPerTroop = ($wizards*3/ $troops);
				else 
					$wizardsPerTroop = $wizards;
				$successChance = (mt_rand(50,150) / 100) + (.75 * ($wizardsPerTroop - 1/3));
				if($successChance < .35)
					$successChance = .35;
				elseif($successChance > 1)
					$successChance = 1;
				if($wizardsPerTroop > 1)
					$wizardsPerTroop = 1;
					
					
				$this->user['User']['Army']['wizard_resting'] += $wizards;
				$this->user['User']['Army']['wizard'] -= $wizards;
				
				
				$this->user['User']['Army']['small_elite_dead'] = 0;
				$this->user['User']['Army']['big_elite_dead'] = 0;
				$this->user['User']['Army']['standard_dead'] = 0;
				$this->user['User']['Army']['weak_dead'] = 0;
				$this->user['User']['Army']['mount_dead'] = 0;
				$this->user['User']['Army']['range_dead'] = 0;
				$this->user['User']['Army']['small_elite_success'] = 0;
				$this->user['User']['Army']['big_elite_success'] = 0;
				$this->user['User']['Army']['standard_success'] = 0;
				$this->user['User']['Army']['weak_success'] = 0;
				$this->user['User']['Army']['mount_success'] = 0;
				$this->user['User']['Army']['range_success'] = 0;
				
				$attacks = $this->user['User']['Attack'];
				$firstGate = true;
				foreach($attacks as &$attack):
					if($attack['rounds'] != -5) {
						$this->user['User']['Army']['small_elite'] += round($attack['small_elite'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['big_elite'] += round($attack['big_elite'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['standard'] += round($attack['standard'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['weak'] += round($attack['weak'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['mount'] += round($attack['mount'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['range'] += round($attack['range'] * $successChance * $wizardsPerTroop);
						
						$this->user['User']['Army']['small_elite_success'] += round($attack['small_elite'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['big_elite_success'] += round($attack['big_elite'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['standard_success'] += round($attack['standard'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['weak_success'] += round($attack['weak'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['mount_success'] += round($attack['mount'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['range_success'] += round($attack['range'] * $successChance * $wizardsPerTroop);
						
						$this->user['User']['Army']['small_elite_dead'] += $attack['small_elite'] - round($attack['small_elite'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['big_elite_dead'] += $attack['big_elite'] - round($attack['big_elite'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['standard_dead'] += $attack['standard'] - round($attack['standard'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['weak_dead'] += $attack['weak'] - round($attack['weak'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['mount_dead'] += $attack['mount'] - round($attack['mount'] * $successChance * $wizardsPerTroop);
						$this->user['User']['Army']['range_dead'] += $attack['range'] - round($attack['range'] * $successChance * $wizardsPerTroop);
						
						$attack['small_elite'] = 0;
						$attack['big_elite'] = 0;
						$attack['standard'] = 0;
						$attack['weak'] = 0;
						$attack['mount'] = 0;
						$attack['range'] = 0;
						$firstGate = false;
					}
				endforeach;
				
				
				$this->Spell->User->Attack->saveAll($attacks);
				$this->Spell->User->Army->save($this->user['User']['Army']);
				$unitNames = $this->Spell->User->Attack->getUnitNames($this->user);
				$this->user['User']['protection'] = 0;
				$this->Spell->User->save($this->user, array('validate' => true, 'fieldList' => array('protection')));
				
				
				$result = "Your " . $this->Spell->pluralize('wizard', $wizards) . " successfully cast the Gate spell. <br /><br />";
				
				$result .= "Of your returning troops, ";
				$result .= $this->Spell->pluralize($unitNames[0], $this->user['User']['Army']['weak_success']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[2], $this->user['User']['Army']['range_success']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[3], $this->user['User']['Army']['mount_success']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[5], $this->user['User']['Army']['big_elite_success']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[1], $this->user['User']['Army']['standard_success']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[4], $this->user['User']['Army']['small_elite_success']) . " ";
				$result .= "returned successfully. <br /><br />"; 
				$result .= "Unfortunately you lost "; 
				$result .= $this->Spell->pluralize($unitNames[0], $this->user['User']['Army']['weak_dead']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[2], $this->user['User']['Army']['range_dead']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[3], $this->user['User']['Army']['mount_dead']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[5], $this->user['User']['Army']['big_elite_dead']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[1], $this->user['User']['Army']['standard_dead']) . ", ";
				$result .= $this->Spell->pluralize($unitNames[4], $this->user['User']['Army']['small_elite_dead']) . " ";
				$result .= ".<br />";
				
				$this->set('result', $result);
				$this->Session->setFlash($result);
				$this->redirect(array('action' => '/index/'));
				//$this->unset($this->data);
			}
			else {
				$this->Session->setFlash('You do not have that many available wizards.');
				$this->redirect(array('action' => '/index'));
			}
		}
		elseif($spell != 'gate') {
			$this->Session->setFlash('You accesses this page incorrectly.');
			$this->redirect(array('action' => '/index'));
		}

		$this->set('user', $this->user);
	}
	
	/*
	 * Spell selection page, $options refers to all the different spells
	 * Spells need to be added for Elves.
	 */
	function selectSpell($target = null) {	
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed'));
	    }
		$verification = $this->Connect->user('verified');
    	if(isset($verification)){
    		$verified = true;
    	} else {
    		$verified = false;
    	}
		$this->loadModel('IpAddress');
		if($this->IpAddress->checkAddresses($target, $this->user['User']['id']) && !$this->user['User']['premium']){
			$this->Session->setFlash('You\'ve played on this player\'s IP Address before. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
			$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			
		}
		if($this->IpAddress->checkInteractAddresses($target, $this->user['User']['id'], $_SERVER['REMOTE_ADDR']) && !$this->user['User']['premium']){
			$this->Session->setFlash('Another player on this IP address has interacted with your target in the past 7 days. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
			$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			
		}
	    $targetScore = $this->Spell->User->find('first', array('conditions' => array('User.id' => $target), 'fields' => array('score', 'suspended')));
	    if($targetScore['User']['suspended']){
	    	$this->Session->setFlash('This player is suspended. You are not allowed to cast spells on him/her.');
	    	$this->redirect(array('action' => '/index'));
	    }
	    if($targetScore['User']['score'] < $this->user['User']['score'] * .6)
	    	$this->Session->setFlash('The target you have selected is less than 60% your size. You will see a ' . (100 - round(($targetScore['User']['score'] * 100) / $this->user['User']['score'])) . '% decrease in your spell success rate.');
		$options = $this->Spell->getSpellList($this->user);
		$this->set('name', $this->Spell->getTargetName($target));
		$this->set('available_wizards', $this->user['User']['Army']['wizard']);
		$this->set('resting_wizards', $this->user['User']['Army']['wizard_resting']);
		$this->set('target', $target);
		$this->set('options', $options);
		$this->set('user', $this->user);
	}
	
	function updateSchedule($update = null, $id = null) {
		if(isset($update)){
			$this->loadModel('MagicSchedule');
			$schedule = $this->MagicSchedule->find('first', array('conditions' => array('MagicSchedule.user_id' => $this->user['User']['id'], 'MagicSchedule.id' => $id), 'order' => 'MagicSchedule.order ASC'));
			if($schedule){
				if(!ctype_digit($this->data['MagicSchedule'])){
					echo "Enter only numbers!";
					exit();
				}
				switch($update):
					case 'wizards';
						$schedule['MagicSchedule']['wizards'] = $this->data['MagicSchedule'];
						$this->MagicSchedule->save($schedule);
						break;
					default;
						break;
				endswitch;
			}
		}
		$this->set('result', $this->data['MagicSchedule']);
	}

	
	/*
	 * Displays the results of a spell cast.
	 * Results need to be tailored according the spell cast
	 */
	function castSpell() {
	
		$verification =  $this->Connect->user('verified');
    	if(isset($verification)){
    		$verified = true;
    	} else {
    		$verified = false;
    	}
	    $this->loadModel('IpAddress');
		if($this->IpAddress->checkAddresses($this->data['Spell']['target'], $this->user['User']['id']) && !$this->user['User']['premium']){
			$this->Session->setFlash('You\'ve played on this player\'s IP Address before. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
			$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			
		}
		if($this->IpAddress->checkInteractAddresses($this->data['Spell']['target'], $this->user['User']['id'], $_SERVER['REMOTE_ADDR']) && !$this->user['User']['premium']){
			$this->Session->setFlash('Another player on this IP address has interacted with your target in the past 7 days. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
			$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			
		}
	    if(!ctype_digit($this->data['Spell']['wizards'])){
	    	$this->Session->setFlash('You must use only numbers when selecting wizards.');
	    	$this->redirect(array('action' => 'selectSpell/' . $this->data['Spell']['target']));
	    } 			
		if($this->data['Spell']['wizards'] > 0 && $this->data['Spell']['wizards'] <= $this->user['User']['Army']['wizard']) {
			if($this->data['Spell']['spell'] != "gate") {
				switch($this->data['Spell']['spell']) {
					case "fireball":
						if($this->user['User']['beginner_protection']) {
							$this->Session->setFlash('You cannot cast that spell while in beginner protection');
							$this->redirect(array('action' => 'selectSpell/' . $this->data['Spell']['target']));		
						}
						break;
					case "truesight":
						if($this->user['User']['beginner_protection']) {
							$this->Session->setFlash('You cannot cast that spell while in beginner protection');
							$this->redirect(array('action' => 'selectSpell/' . $this->data['Spell']['target']));		
						}
						break;
					case "freeze":
						if($this->user['User']['beginner_protection']) {
							$this->Session->setFlash('You cannot cast that spell while in beginner protection');
							$this->redirect(array('action' => 'selectSpell/' . $this->data['Spell']['target']));		
						}
						break;
					case "famine":
						if($this->user['User']['beginner_protection']) {
							$this->Session->setFlash('You cannot cast that spell while in beginner protection');
							$this->redirect(array('action' => 'selectSpell/' . $this->data['Spell']['target']));		
						}
						break;
					case "earthquake":
						if($this->user['User']['beginner_protection']) {
							$this->Session->setFlash('You cannot cast that spell while in beginner protection');
							$this->redirect(array('action' => 'selectSpell/' . $this->data['Spell']['target']));		
						}
						break;
					case "ice_storm":
						if($this->user['User']['beginner_protection']) {
							$this->Session->setFlash('You cannot cast that spell while in beginner protection');
							$this->redirect(array('action' => 'selectSpell/' . $this->data['Spell']['target']));		
						}
						break;
					default:
						break;
				}
				$result = $this->Spell->castSpell($this->user, $this->data['Spell']['target'], $this->data['Spell']['spell'], $this->data['Spell']['wizards'], $_SERVER['REMOTE_ADDR']);
			}
			else{
				if($this->data['Spell']['target'] == $this->user['User']['id'])
					$this->redirect(array('action' => 'gate/' . $this->data['Spell']['spell'] . '/' . $this->data['Spell']['wizards']));
				else {
					$this->Session->setFlash('You must use this spell only on yourself.');
	    			$this->redirect(array('action' => '/index'));
				}
					
			}
		}
		else {
			$this->Session->setFlash('Invalid amount of Wizards');
			
			$this->redirect(array('action' => 'selectSpell/' . $this->data['Spell']['target']));
		}
		
		$this->set('user', $this->Spell->find('first', array('conditions' => array('User.account_id' => $this->account_id))));
		$this->set('result', $result);
	}
	
	/*
	 * Used to redirect in order to find a province, select a spell, and cast a spell
	 */
	function castRedirect() {
		if($this->data['Spell']['target'] == 0)
			$this->redirect(array('action'=>'find/' . $this->data['Spell']['name'] . '/' . $this->data['Spell']['provinceNumber'])); 
		else { 
			
			$this->redirect(array('action'=>'selectSpell/' . $this->data['Spell']['target'])); 
		}
	}
	
	function schedule() {
		if(!$this->user['User']['premium']) {
			$this->Session->setFlash('Currently only premium members are allowed to schedule spells. <a href="/premiums' . '">Upgrade your account</a> to gain access this this and more.');
			$this->redirect(array('action'=>'index'));
		}
		$this->loadModel('MagicSchedule');
		$schedules = $this->MagicSchedule->find('all', array('conditions' => array('MagicSchedule.user_id' => $this->user['User']['id']), 'order' => 'MagicSchedule.order ASC'));
		foreach($schedules as &$schedule):
			$schedule['MagicSchedule']['target_name'] = $this->Spell->User->getName($schedule['MagicSchedule']['target_id']);
		endforeach;
		
		$this->set('selected', 'Manage Schedule');
		$this->set('spellOptions', $this->Spell->getSpellList($this->user));
		$this->set('schedules', $schedules);
		$this->set('user', $this->user);
	}
	
	function addSchedule() {
		$this->layout = 'ajax';
		$this->loadModel('MagicSchedule');

		$kingdom = $this->Spell->User->find('first', array('conditions' => array('User.ruler' => $this->data['User']['target']), 'fields' => 'User.id'));
		if(empty($kingdom)){
			echo "<div style=\"color: red;\">The ruler you selected was not found in the database.</div>";
		} else {
			$schedule['MagicSchedule']['user_id'] = $this->user['User']['id'];
			$schedule['MagicSchedule']['target_id'] = $kingdom['User']['id'];
			$schedule['MagicSchedule']['wizards'] = $this->data['User']['wizards'];
			$schedule['MagicSchedule']['frequency'] = $this->data['User']['frequency'];
			$schedule['MagicSchedule']['spell'] = $this->data['User']['spell'];
			$schedule['MagicSchedule']['current'] = $schedule['MagicSchedule']['frequency'];
			$schedule['MagicSchedule']['order'] = $this->MagicSchedule->find('count', array('conditions' => array('MagicSchedule.user_id' => $this->user['User']['id']))) + 1;
			$this->MagicSchedule->create();
			$this->MagicSchedule->save($schedule);
		}
		
		$schedules = $this->MagicSchedule->find('all', array('conditions' => array('MagicSchedule.user_id' => $this->user['User']['id']), 'order' => 'MagicSchedule.order ASC'));
		foreach($schedules as &$schedule):
			$schedule['MagicSchedule']['target_name'] = $this->Spell->User->getName($schedule['MagicSchedule']['target_id']);
		endforeach;
		$this->set('schedules', $schedules);
		$this->set('spellOptions', $this->Spell->getSpellList($this->user));
		$this->set('user', $this->user);
	}
	
	function deleteSchedule($id = null) {
		$this->layout = 'ajax';
		$this->loadModel('MagicSchedule');
		
		$deletable = $this->MagicSchedule->find('first', array('conditions' => array('MagicSchedule.id' => $id)));
		if(!empty($deletable)) {
			if($deletable['MagicSchedule']['user_id'] == $this->user['User']['id'])
				$this->MagicSchedule->delete($id);
			else
				echo "<div style=\"color: red;\">Your deletion could not be completed.</div>";
		}
		
		$schedules = $this->MagicSchedule->find('all', array('conditions' => array('MagicSchedule.user_id' => $this->user['User']['id']), 'order' => 'MagicSchedule.order ASC'));
		foreach($schedules as &$schedule):
			$schedule['MagicSchedule']['target_name'] = $this->Spell->User->getName($schedule['MagicSchedule']['target_id']);
		endforeach;
		$this->set('schedules', $schedules);
		$this->set('spellOptions', $this->Spell->getSpellList($this->user));
		$this->set('user', $this->user);		
	}
	
	function reorder() {
		$this->loadModel('MagicSchedule');
		debug($_POST);
		/** $_POST because I couldn't figure out how to save the reorders without using this method... :\
		**/
		foreach($_POST['schedule'] as $position => $id):
			$this->MagicSchedule->id = $id;
			$this->MagicSchedule->saveField('order',$position); 
		endforeach;
		$this->autoRender=false;
	}
	
	function autoCompleteKingdom() {
		$this->set('receivers', Classregistry::init('User')->find('all', array('conditions' => array('User.kingdom LIKE' => $this->params['url']['q'] . '%'), 'fields' => array('kingdom'), 'limit' => 20)));
		$this->layout = 'ajax';
	}

}
?>