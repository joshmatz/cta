<?php
class AttacksController extends AppController {    
	var $name = 'Attacks';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	var $helpers = array('Form', 'Html', 'Javascript', 'Time', 'Ajax');

	function beforeFilter() {  
		parent::beforeFilter();
		$this->Attack->recursive = 2;
		//$this->getFacebookCredentials();
		//$this->facebook_id = $this->facebookId;
		$this->user = $this->Attack->find('first', array('conditions' => array('User.account_id' => $this->account_id), 'recursive' => 2)); 
		
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		
		$menu = array(
			array(
				'name' => 'War Room',
				'link' => '/attacks'
			),
			array(
				'name' => 'Strategy',
				'link' => '/attacks/strategies'
			),
			array(
				'name' => 'Retaliations',
				'link' => 'retaliations'
			),
			array(
				'name' => 'Simulator',
				'link' => '/attacks/attackSimulator'
			)
		);
		$this->set('menu', $menu);
    }  
    
	/******
	 * Give the view basic data to make the table
	 */
	function index() {  
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed'));
	    }
		
		
		$this->set('selected', 'War Room');
		$this->set('unitNames', $this->Attack->getUnitNames($this->user));
		$this->set('user', $this->user);
	}
	
	function autoComplete() {
		$this->set('receivers', Classregistry::init('User')->find('all', array('conditions' => array('User.ruler LIKE' => $this->params['url']['q'] . '%'), 'fields' => array('ruler'), 'limit' => 20)));
		$this->layout = 'ajax';
	}
	
	function searchAttack() {
		if($this->data){
			$found = $this->Attack->User->find('first', array('conditions' => array('User.ruler' => $this->data['Attack']['receiver']), 'fields' => array('id', 'alliance_id')));
			if(!empty($found)){
				$verification = $this->Connect->user('verified');
		    	if(isset($verification)){
		    		$verified = true;
		    	} else {
		    		$verified = false;
		    	}
				$alerts = "";
				$this->data['Attack']['target'] = $found['User']['id'];
				$victim = $this->Attack->find('first', array('conditions' => array('User.id' => $this->data['Attack']['target']))); 
				$this->loadModel('IpAddress');
				if($this->IpAddress->checkAddresses($victim['User']['id'], $this->user['User']['id']) && !$this->user['User']['premium']){
					$this->Session->setFlash('You\'ve played on this player\'s IP Address before. In order to reduce multiple accounts, please upgrade to premium.');
					$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
				}
				if($victim['User']['alliance_id'] && $this->user['User']['alliance_id'] != 0){
		    		$this->loadModel('AllianceFriend');
		    		$relations = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
		    		foreach($relations as $relation):
		    			if($victim['User']['alliance_id'] == $relation['AllianceFriend']['friend_id'] && $relation['AllianceFriend']['type'] == "ally"):
		    				if(empty($retals)) {
		    					$this->Session->setFlash('You cannot hit someone allied to your Alliance.');
		    					$this->redirect(array('action'=>'index'));
							} else {
								$alerts.= 'You should not hit someone allied to your Alliance, but you have a retaliation.';
							}
		    			elseif($victim['User']['alliance_id'] == $relation['AllianceFriend']['friend_id'] && $relation['AllianceFriend']['type'] == "nap"):
		    				$alerts .= 'You\'re alliance has a Non-Aggression Pact with this alliance. You should not hit this person. You have a retaliation';
		    			endif;
		    		endforeach;
		    	}
				if($this->user['User']['alliance_id'] == $victim['User']['alliance_id'] && $this->user['User']['alliance_id'] != 0){
					$this->Session->setFlash('You cannot hit someone in your own alliance.');
					$this->redirect(array('action'=>'index'));
				}
				if($this->user['User']['beginner_protection']){
					$this->Session->setFlash('You are currently under <b>beginner</b> protection for ' . $this->user['User']['protection'] . ' more hours ');
					$this->redirect(array('action'=>'index'));
				}
				if ($this->user['User']['protection'])
					$alerts .= ('You are still under protection for ' . $this->user['User']['protection'] . ' hours. <br />Attacking now would end your protection.<br />');
				$retaliations = $this->Attack->User->Retaliation->getTopRetaliation($this->user['User']['id'], $victim['User']['id']);
				if(empty($retaliations)){
					$this->loadModel('AllianceRetaliation');
					$time = date("Y-m-d H:i:s", time() - (60*60*5));
					$allianceRetaliations = $this->AllianceRetaliation->find(
						'first', 
						array(
							'conditions' => array(
								'AllianceRetaliation.datetime > ' => $time, 
								'AllianceRetaliation.alliance_id' => $this->user['User']['alliance_id'], 
								'AllianceRetaliation.player_id' => $victim['User']['id'],
								'AllianceRetaliation.used' => 0 
							), 
							'sort' => 'AllianceRetaliation.datetime DESC'
						)
					);
		    		if(!empty($allianceRetaliations)){
		    			$alerts .= 'You will use an alliance retaliation attacking this player. View list of all your <a href="/alliances/retaliations/">current alliance retaliations</a>.';
		    		}
				}
				//debug($retaliations);
				if($victim['User']['suspended'] && empty($retaliations) && empty($allianceRetaliations)){
					$this->Session->setFlash('This player is suspended and you may not attack him/her.');
					$this->redirect(array('action'=>'index'));
				}
				if ($victim['User']['protection'] > 0 && empty($retaliations) && empty($allianceRetaliations)){
					$this->Session->setFlash('This player is currently under protection.');
					$this->redirect(array('action'=>'index'));
				}
				if(empty($retaliations)) {
					if($this->IpAddress->checkInteractAddresses($victim['User']['id'], $this->user['User']['id'], $_SERVER['REMOTE_ADDR']) && !$this->user['User']['premium']){
						$this->Session->setFlash('Another player on this IP address has attacked your target in the past 7 days. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
						$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
					}
				}
				
				$retal = false;
				if(!empty($retaliations)){
					$alerts .= 'You will use a retaliation attacking this player. To see a list of all your current retaliations <a href="/retaliations/' . ' ">click here</a><br />';
					$retal = true;
				}
				
				if ($victim['User']['score'] <= 0){
					$this->Session->setFlash('This player is already conquered');
					$this->redirect(array('action'=>'index'));
				}
				if ($victim['User']['Resource']['land'] <= 10){
					$this->Session->setFlash('You cannot attack a player than has 10 or less land.');
					$this->redirect(array('action'=>'index'));
				}
				if ($victim['User']['score'] < .15 * $this->user['User']['score'] && empty($retaliations) && false){
					$this->Session->setFlash('This person is less than 15% your size. You are not allowed to attack them.');
					$this->redirect(array('action'=>'index'));
				}
				/* 
				 * This is upperfeeding. Removed.
				elseif($this->user['User']['score'] < .6 * $victim['User']['score']) {
					$alerts .= 'You are less than 60% of your opponent\'s size, you troops will be significantly less effective.<br />';
				}
				*/
				if ($this->user['User']['Army']['generals'] < 1){
					$this->Session->setFlash('You do not have enough generals.<br />Wait for an army to return.');
					$this->redirect(array('action'=>'index'));
				}
				$this->Session->setFlash('error', $alerts);
				$this->redirect(array('action'=>'selectUnits/' . $this->data['Attack']['target'])); 
			} else {
				$this->Session->setFlash('Ruler not found.');
				$this->redirect(array('controller' => 'attacks', 'action' => 'index'));
			}
		} else {
			$this->Session->setFlash('The page was accessed incorrectly.');
			$this->redirect(array('controller' => 'attacks', 'action' => 'index'));
		}
	}
	
	
	
	/*
	 * Unit selection page, 
	 */
	function selectUnits($target = null) {
		$alerts = "";
		$targetUser = $this->Attack->User->find('first', array('conditions' => array('User.id' => $target), 'fields' => 'User.protection, User.alliance_id, User.suspended'));
		if($this->account['Account']['role'] == 'admin') { 
			//debug($targetUser); 
		}
		
		if($this->user['User']['alliance_id'] == $targetUser['User']['alliance_id'] && $this->user['User']['alliance_id'] != 0){
			$this->Session->setFlash('You cannot hit someone in your own alliance.');
			$this->redirect(array('action'=>'index'));
		}
		$victim = $this->Attack->User->find('first', array('conditions' => array('User.id' => $target), 'fields' => 'User.id, User.alliance_id'));
		$time = date("Y-m-d H:i:s", time() - (60*60*5));
		$retals = $this->Attack->User->Retaliation->getTopRetaliation($this->user['User']['id'], $target);
		if(empty($retals)){
			$this->loadModel('AllianceRetaliation');
			$allianceRetaliations = $this->AllianceRetaliation->find(
				'first', 
				array(
					'conditions' => array(
						'AllianceRetaliation.datetime > ' => $time, 
						'AllianceRetaliation.alliance_id' => $this->user['User']['alliance_id'], 
						'AllianceRetaliation.player_id' => $victim['User']['id'],
						'AllianceRetaliation.used' => 0 
					), 
					'sort' => 'AllianceRetaliation.datetime DESC'
				)
			);
    		if(!empty($allianceRetaliations)){
    			$alerts .= 'You will use an alliance retaliation attacking this player. View list of all your <a href="/alliances/retaliations/">current alliance retaliations</a>.';
    		}
		}
		if($targetUser['User']['suspended'] && empty($retals) && empty($allianceRetaliations)){
			$this->Session->setFlash('This player is suspended and you may not attack him/her.');
			$this->redirect(array('action'=>'index'));
		}else if ($targetUser['User']['suspended'] == 1){
			$alerts .= 'That user is suspended. You have a retaliation right to take on the kingdom.';
		}



		if($targetUser['User']['alliance_id'] && $this->user['User']['alliance_id'] != 0){
    		$this->loadModel('AllianceFriend');
    		$relations = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));

    		foreach($relations as $relation):
    			if($targetUser['User']['alliance_id'] == $relation['AllianceFriend']['friend_id'] && $relation['AllianceFriend']['type'] == "ally"):
    				if(empty($retals)) {
    					$this->Session->setFlash('You cannot hit someone allied to your Alliance.');
    					$this->redirect(array('action'=>'index'));
					} else {
						$alerts.= 'You should not hit someone allied to your Alliance, but you have a retaliation.';
					}
    			elseif($targetUser['User']['alliance_id'] == $relation['AllianceFriend']['friend_id'] && $relation['AllianceFriend']['type'] == "nap"):
    				$alerts .= 'You\'re alliance has a Non-Aggression Pact with this alliance. You should not hit this person. You have a retaliation';
    			endif;
    		endforeach;
    	}


		$lastDefend = $this->Report->find('first', array('conditions' => array('Report.user_id' => $victim['User']['id'], 'Report.time >' => (time() - (60*60*6)), 'Report.type LIKE' => '%Defend' ), 'fields' => 'Report.maker_id', 'order' => 'time desc'));
		$clear = false;
		if(!empty($retals) || !empty($allianceRetaliations)){
			$clear = true;
		}
		if($lastDefend){
			$lastAttacker = $this->User->find('first', array('conditions' => array('User.id' => $lastDefend['Report']['maker_id']), 'fields' => 'User.alliance_id'));
			foreach($relations as $relation):
				if($lastAttacker['User']['alliance_id'] == $relation['AllianceFriend']['friend_id']){
					$clear = true;
				}
			endforeach;
			if(!$clear && $lastAttacker['User']['alliance_id'] == $this->user['User']['alliance_id']) {
				$clear = true;
			}
		} else {
			$clear = true;
		}
		if($clear == false){
			$this->Session->setFlash('That person has been attacked recently by someone outside of your Alliance pacts. You will need to wait to hit them.');
			$this->redirect(array('controller' => 'attacks', 'action' => 'index'));
		}
			
		if($alerts) { $this->Session->setFlash($alerts); }
		$this->set('targetUser', $targetUser);
		$this->set('name', $this->Attack->User->getName($target));
		$this->set('unitNumbers', $this->Attack->User->Army->getUnitNumbers($this->user));
		$this->set('unitNames', $this->Attack->User->Army->getUnitNames($this->user));
		$this->set('armySize', round($this->Attack->User->Army->getTotalArmyHousing($this->user) * .1));
		$this->set('target', $target);
		$this->set('user', $this->user);
		$this->set('retals', $retals);
		$this->set('allianceRetaliations', $allianceRetaliations);
	}
	
	/*
	 * Used to redirect in order to find a province, select unit amounts, and attack
	 */
	function attackRedirect() {
		$alerts = "";

		if(!isset($this->data))
			$this->redirect(array('action'=>'index'));
		if($this->data['Attack']['target'] == 0 || $this->data['Attack']['target'] == $this->user['User']['id']){
			if($this->data['Attack']['target'] == $this->user['User']['id'])
				$this->Session->setFlash('You cannot attack yourself.');
			$this->redirect(array('action'=>'find/' . $this->data['Attack']['name'] . '/'. $this->data['Attack']['provinceNumber'])); 
		}
		else  {      
			$victim = $this->Attack->find('first', array('conditions' => array('User.id' => $this->data['Attack']['target']))); 
			if($this->account['Account']['role'] == 'admin') { 
				//debug($victim); 
			}
			$verification = $this->Connect->user('verified');
	    	if(isset($verification)){
	    		$verified = true;
	    	} else {
	    		$verified = false;
	    	}
			if($this->user['User']['alliance_id'] == $victim['User']['alliance_id'] && $this->user['User']['alliance_id'] != 0){
				$this->Session->setFlash('You cannot hit someone in your own alliance.');
				$this->redirect(array('action'=>'index'));
			}
			if($victim['User']['alliance_id'] && $this->user['User']['alliance_id'] != 0){
	    		$this->loadModel('AllianceFriend');
	    		$relations = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
	    		foreach($relations as $relation):
	    			if($victim['User']['alliance_id'] == $relation['AllianceFriend']['friend_id'] && $relation['AllianceFriend']['type'] == "ally"):
	    				if(empty($retals)) {
	    					$this->Session->setFlash('You cannot hit someone allied to your Alliance.');
	    					$this->redirect(array('action'=>'index'));
						} else {
							$alerts.= 'You should not hit someone allied to your Alliance, but you have a retaliation.';
						}
	    			elseif($victim['User']['alliance_id'] == $relation['AllianceFriend']['friend_id'] && $relation['AllianceFriend']['type'] == "nap"):
	    				$alerts .= 'You\'re alliance has a Non-Aggression Pact with this alliance. You should not hit this person. You have a retaliation';
	    			endif;
	    		endforeach;
	    	}
			$this->loadModel('IpAddress');
			if($this->IpAddress->checkAddresses($victim['User']['id'], $this->user['User']['id']) && !$this->user['User']['premium']){
				$this->Session->setFlash('You\'ve played on this player\'s IP Address before. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
				$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			}
			
			if($this->user['User']['beginner_protection']){
				$this->Session->setFlash('You are currently under <b>beginner</b> protection for ' . $this->user['User']['protection'] . ' more hours ');
				$this->redirect(array('action'=>'index'));
			}
			if ($this->user['User']['protection'])
				$alerts .= ('You are still under protection for ' . $this->user['User']['protection'] . ' hours. <br />Attacking now would end your protection.<br />');
			$retaliations = $this->Attack->User->Retaliation->getTopRetaliation($this->user['User']['id'], $victim['User']['id']);
			//debug($retaliations);
			if($victim['User']['suspended'] && empty($retaliations)){
				$this->Session->setFlash('This player is suspended and you may not attack him/her.');
				$this->redirect(array('action'=>'index'));
			}
			if ($victim['User']['protection'] > 0 && empty($retaliations)){
				$this->Session->setFlash('This player is currently under protection.');
				$this->redirect(array('action'=>'index'));
			}
			if(empty($retaliations)) {
				if($this->IpAddress->checkInteractAddresses($victim['User']['id'], $this->user['User']['id'], $_SERVER['REMOTE_ADDR']) && !$this->user['User']['premium']){
					$this->Session->setFlash('Another player on this IP address has attacked your target in the past 7 days. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
					$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
				}
			}

			$retal = false;
			if(!empty($retaliations)){
				$alerts .= 'You will use a retaliation attacking this player. To see a list of all your current retaliations <a href="/retaliations/">click here</a><br />';
				$retal = true;
			}
			if ($retal == true)
				$BASMultiplier = 1;
			else if ($victim['User']['score']/$user['User']['score'] >= .9)
				$BASMultiplier = 1; 
			else if (.9 > $victim['User']['score']/$user['User']['score'] && $victim['User']['score']/$user['User']['score'] >= .5)
				$BASMultiplier = pow(max(($victim['User']['score']/$user['User']['score'])-(.3333), 0)*(30/17), .29);
			else if ($victim['User']['score']/$user['User']['score'] < .5)
				$BASMultiplier = max(($victim['User']['score']/$user['User']['score'])-(.3333), 0)*4.2;
			if($BASMultiplier != 1){
				if($BASMultiplier != 0)
					$alerts .= "Your BAS will be reduced by " . (100 - number_format($BASMultiplier * 100)) . "% on this attack. <br />";
				else 
					$alerts .= "Your BAS will be 0 on this attack. You will do no damage. <br />";
			}
			
			if ($victim['User']['score'] <= 0){
				$this->Session->setFlash('This player is already conquered');
				$this->redirect(array('action'=>'index'));
			}
			if ($victim['User']['Resource']['land'] <= 10){
				$this->Session->setFlash('You cannot attack a player than has 10 or less land.');
				$this->redirect(array('action'=>'index'));
			}
			if ($victim['User']['score'] < .15 * $this->user['User']['score'] && empty($retaliations) && false){
				$this->Session->setFlash('This person is less than 15% your size. You are not allowed to attack them.');
				$this->redirect(array('action'=>'index'));
			}
			/* 
			 * This is upperfeeding. Removed.
			elseif($this->user['User']['score'] < .6 * $victim['User']['score']) {
				$alerts .= 'You are less than 60% of your opponent\'s size, you troops will be significantly less effective.<br />';
			}
			*/
			if ($this->user['User']['Army']['generals'] < 1){
				$this->Session->setFlash('You do not have enough generals.<br />Wait for an army to return.');
				$this->redirect(array('action'=>'index'));
			}
			if($alerts) { $this->Session->setFlash($alerts); }
			$this->redirect(array('action'=>'selectUnits/' . $this->data['Attack']['target'])); 
		}
	}
	
	/*
	 * 
	 */
	function attack(){
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed'));
	    }
		if($this->user['User']['beginner_protection']){
			$this->Session->setFlash('You are currently under <b>beginner</b> protection for ' . $this->user['User']['protection'] . ' more hours ');
			$this->redirect(array('action'=>'index'));
		}
		$verification = $this->Connect->user('verified');
    	if(isset($verification)){
    		$verified = true;
    	} else {
    		$verified = false;
    	}
		$this->loadModel('IpAddress');
		if($this->IpAddress->checkAddresses($this->data['Attack']['target'], $this->user['User']['id']) && !$this->user['User']['premium']){
			$this->Session->setFlash('You\'ve played on this player\'s IP Address before. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
			$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
		}
		
		if((!ctype_digit($this->data['Attack']['weak']) && $this->data['Attack']['weak'] != NULL) || 
		(!ctype_digit($this->data['Attack']['standard']) && $this->data['Attack']['standard'] != NULL) || 
		(!ctype_digit($this->data['Attack']['mount']) && $this->data['Attack']['mount'] != NULL) || 
	    (!ctype_digit($this->data['Attack']['range']) && $this->data['Attack']['range'] != NULL) || 
	    (!ctype_digit($this->data['Attack']['small_elite']) && $this->data['Attack']['small_elite'] != NULL) || 
	    (!ctype_digit($this->data['Attack']['big_elite']) && $this->data['Attack']['big_elite'] != NULL)){
	    	$this->Session->setFlash('You must use only numbers when sending units on attack.');
	    	$this->redirect(array('action' => '/index'));
	    }
		if (!empty($this->data)){
			if (($this->data['Attack']['small_elite'] < 0) ||
				($this->data['Attack']['big_elite'] < 0) ||
				($this->data['Attack']['weak'] < 0) ||
				($this->data['Attack']['standard'] < 0) ||
				($this->data['Attack']['range'] < 0) ||
				($this->data['Attack']['mount'] < 0)){
					$this->Session->setFlash('Please enter positive integers.');
					$this->redirect(array('action'=>'index'));
			}
			
			if (($this->data['Attack']['small_elite'] == 0) &&
				($this->data['Attack']['big_elite'] == 0) &&
				($this->data['Attack']['weak'] == 0) &&
				($this->data['Attack']['standard'] == 0) &&
				($this->data['Attack']['range'] == 0) &&
				($this->data['Attack']['mount'] == 0)){
					$this->Session->setFlash('Please enter positive integers.');
					$this->redirect(array('action'=>'index'));
			}
		
			
			if (($this->data['Attack']['small_elite'] > $this->user['User']['Army']['small_elite']) ||
				($this->data['Attack']['big_elite'] > $this->user['User']['Army']['big_elite']) ||
				($this->data['Attack']['weak'] > $this->user['User']['Army']['weak']) ||
				($this->data['Attack']['standard'] > $this->user['User']['Army']['standard']) ||
				($this->data['Attack']['range'] > $this->user['User']['Army']['range']) ||
				($this->data['Attack']['mount'] > $this->user['User']['Army']['mount'])){
					$this->Session->setFlash('You do not have that many units to send.');
					$this->redirect(array('action'=>'index'));
				}
			elseif ($this->user['User']['Army']['generals'] < 1){
				$this->Session->setFlash('You do not have enough generals.<br />Wait for an army to return.');
				$this->redirect(array('action'=>'index'));
			}
			else {
				$victim = $this->Attack->find('first', array('conditions' => array('User.id' => $this->data['Attack']['target']))); 
				if($this->user['User']['alliance_id'] == $victim['User']['alliance_id'] && $this->user['User']['alliance_id'] != 0){
					$this->Session->setFlash('You cannot hit someone in your own alliance.');
					$this->redirect(array('action'=>'index'));
				}
				
				$retaliations = $this->Attack->User->Retaliation->getTopRetaliation($this->user['User']['id'], $victim['User']['id']);
				$time = date("Y-m-d H:i:s", time() - (60*60*5));
				if(empty($retaliations)){
					$this->loadModel('AllianceRetaliation');
					$allianceRetaliations = $this->AllianceRetaliation->find(
						'first', 
						array(
							'conditions' => array(
								'AllianceRetaliation.datetime > ' => $time, 
								'AllianceRetaliation.alliance_id' => $this->user['User']['alliance_id'], 
								'AllianceRetaliation.player_id' => $victim['User']['id'],
								'AllianceRetaliation.used' => 0 
							), 
							'sort' => 'AllianceRetaliation.datetime DESC'
						)
					);
		    		if(!empty($allianceRetaliations)){
		    			$alerts .= 'You will use an alliance retaliation attacking this player. View list of all your <a href="/alliances/retaliations/">current alliance retaliations</a>.';
		    		}
				}
				//debug($retaliations);
				if ($victim['User']['protection'] > 0 && empty($retaliations) && empty($allianceRetaliations)){
					$this->Session->setFlash('This player is currently under protection.');
					$this->redirect(array('action'=>'index'));
				}

				$this->loadModel('AllianceFriend');
	    		$relations = $this->AllianceFriend->find('all', array('conditions' => array('AllianceFriend.alliance_id' => $this->user['User']['alliance_id'])));
	    		foreach($relations as $relation):
	    			if($victim['User']['alliance_id'] == $relation['AllianceFriend']['friend_id'] && $relation['AllianceFriend']['type'] == "ally" && empty($retaliations) && empty($allianceRetaliations)):
	    				$this->Session->setFlash('You cannot hit someone allied to your Alliance.');
						$this->redirect(array('action'=>'index'));
	    			endif;
	    		endforeach;
	    		$lastDefend = $this->Report->find('first', array('conditions' => array('Report.user_id' => $victim['User']['id'], 'Report.time >' => (time() - (60*60*6)), 'Report.type LIKE' => '%Defend' ), 'fields' => 'Report.maker_id', 'order' => 'time desc'));
				
				$clear = false;
				if(!empty($retaliations) || !empty($allianceRetaliations)){
					$clear = true;
				}
				if($lastDefend){
					$lastAttacker = $this->User->find('first', array('conditions' => array('User.id' => $lastDefend['Report']['maker_id']), 'fields' => 'User.alliance_id'));
					foreach($relations as $relation):
						if($lastAttacker['User']['alliance_id'] == $relation['AllianceFriend']['friend_id']){
							$clear = true;
						}
					endforeach;
					if(!$clear && $lastAttacker['User']['alliance_id'] == $this->user['User']['alliance_id']) {
						$clear = true;
					}
				} else {
					$clear = true;
				}
				if($clear == false){
					$this->Session->setFlash('That person has been attacked recently by someone outside of your Alliance pacts. You will need to wait to hit them.');
					$this->redirect(array('controller' => 'attacks', 'action' => 'index'));
				}
				//if(empty($retaliations)){
					if($this->IpAddress->checkInteractAddresses($this->data['Attack']['target'], $this->user['User']['id'], $_SERVER['REMOTE_ADDR']) && !$this->user['User']['premium']){
						$this->Session->setFlash('Another player on this IP address has attacked your target in the past 7 days. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
						$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
					}
				//}
				if($victim['User']['suspended'] && empty($retaliations)){
					$this->Session->setFlash('This player is suspended and you may not attack him/her.');
					$this->redirect(array('action'=>'index'));
				}
				
				$retal = false;
				if(!empty($retaliations) || !empty($allianceRetaliations)){
					$retal = true;
				}
				
				if ($victim['User']['score'] <= 0){
					$this->Session->setFlash('This player is already conquered');
					$this->redirect(array('action'=>'index'));
				}
				if ($victim['User']['Resource']['land'] <= 10){
					$this->Session->setFlash('You cannot attack a player than has 10 or less land.');
					$this->redirect(array('action'=>'index'));
				}
				
				
				if ($this->data['Attack']['small_elite'] == null)
					$this->data['Attack']['small_elite'] = 0;
				if ($this->data['Attack']['big_elite'] == null)
					$this->data['Attack']['big_elite'] = 0;
				if ($this->data['Attack']['standard'] == null)
					$this->data['Attack']['standard'] = 0;
				if ($this->data['Attack']['weak'] == null)
					$this->data['Attack']['weak'] = 0;
				if ($this->data['Attack']['range'] == null)
					$this->data['Attack']['range'] = 0;
				if ($this->data['Attack']['mount'] == null)
					$this->data['Attack']['mount'] = 0;
				
				$this->user['User']['Army']['small_elite'] -= $this->data['Attack']['small_elite'];
				$this->user['User']['Army']['big_elite'] -= $this->data['Attack']['big_elite'];
				$this->user['User']['Army']['weak'] -= $this->data['Attack']['weak'];
				$this->user['User']['Army']['standard'] -= $this->data['Attack']['standard'];
				$this->user['User']['Army']['range'] -= $this->data['Attack']['range'];
				$this->user['User']['Army']['mount'] -= $this->data['Attack']['mount'];
				$this->user['User']['Army']['generals'] -= 1;
				$this->user['User']['protection'] = 0;
				
				$this->Attack->User->save($this->user['User'], array('validate' => true, 'fieldList' => array('protection')));
				$this->Attack->User->Army->id = $this->user['User']['Army']['id'];
				$this->Attack->User->Army->save($this->user['User']['Army']);
			}
			$retal = false;
			if(!empty($retaliations) || !empty($allianceRetaliations))
				$retal = true;
				
			$result = $this->Attack->invade($this->user, $this->data, $this->Connect, $retal, null, $retaliations['Retaliation']['type'], $_SERVER['REMOTE_ADDR']);
			$this->set('result', $result);
			
			//debug($retaliations);
			//echo "Magical Fog search: <br />";
			//debug(strpos($result, "magical fog"));
			if(!empty($retaliations) && strpos($result, "magical fog") == false){
				$this->Attack->User->Retaliation->removeRetaliation($retaliations['Retaliation']['id']);
			} elseif(!empty($allianceRetaliations) && strpos($result, "magical fog") == false) {
				$allianceRetaliations['AllianceRetaliation']['used'] = 1;
				$this->AllianceRetaliation->save($allianceRetaliations);
			}
		}
		else
			$this->Session->setFlash('You accessed this page incorrectly.');
		$this->set('user', $this->user);
	}		
	
	/*
	 * 
	 */
	function attackSimulator(){
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed'));
	    }
		$this->set('selected', 'Simulator');
		$this->set('user', $this->user);
	}	

	function attackSimulatorResult() {
		$this->layout = 'ajax';
		if(!empty($this->data)){
			
			if((!ctype_digit($this->data['attack']['Attack']['weak']) && $this->data['attack']['Attack']['weak'] != NULL) || 
			(!ctype_digit($this->data['attack']['Attack']['standard']) && $this->data['attack']['Attack']['standard'] != NULL) || 
			(!ctype_digit($this->data['attack']['Attack']['mount']) && $this->data['attack']['Attack']['mount'] != NULL) || 
		    (!ctype_digit($this->data['attack']['Attack']['range']) && $this->data['attack']['Attack']['range'] != NULL) || 
		    (!ctype_digit($this->data['attack']['Attack']['small_elite']) && $this->data['attack']['Attack']['small_elite'] != NULL) || 
		    (!ctype_digit($this->data['attack']['Attack']['big_elite']) && $this->data['attack']['Attack']['big_elite'] != NULL) ||
		    (!ctype_digit($this->data['User']['luck']) && $this->data['User']['luck'] != NULL)){
		    	echo "You must use only numbers when simulating attacks. <br />";
		    	exit();
		    }
			if((!ctype_digit($this->data['defend']['Army']['weak']) && $this->data['defend']['Army']['weak'] != NULL) || 
			(!ctype_digit($this->data['defend']['Army']['standard']) && $this->data['defend']['Army']['standard'] != NULL) || 
			(!ctype_digit($this->data['defend']['Army']['mount']) && $this->data['defend']['Army']['mount'] != NULL) || 
		    (!ctype_digit($this->data['defend']['Army']['range']) && $this->data['defend']['Army']['range'] != NULL) || 
		    (!ctype_digit($this->data['defend']['Army']['small_elite']) && $this->data['defend']['Army']['small_elite'] != NULL) || 
		    (!ctype_digit($this->data['defend']['Army']['big_elite']) && $this->data['defend']['Army']['big_elite'] != NULL) ||
		    (!ctype_digit($this->data['defend']['luck']) && $this->data['defend']['luck'] != NULL)){
		    	echo "You must use only numbers when simulating attacks. <br />";
		    	exit();
		    }
		   
		    if($this->data['attack']['Attack']['weak'] == NULL)
		    	$this->data['attack']['Attack']['weak'] = 0;
		    if($this->data['attack']['Attack']['standard'] == NULL)
		    	$this->data['attack']['Attack']['standard'] = 0;
		    if($this->data['attack']['Attack']['range'] == NULL)
		    	$this->data['attack']['Attack']['range'] = 0;
		    if($this->data['attack']['Attack']['mount'] == NULL)
		    	$this->data['attack']['Attack']['mount'] = 0;
		    if($this->data['attack']['Attack']['small_elite'] == NULL)
		    	$this->data['attack']['Attack']['small_elite'] = 0;
		    if($this->data['attack']['Attack']['big_elite'] == NULL)
		    	$this->data['attack']['Attack']['big_elite'] = 0;
		    if($this->data['defend']['Army']['weak'] == NULL)
		    	$this->data['defend']['Army']['weak'] = 0;
		    if($this->data['defend']['Army']['standard'] == NULL)
		    	$this->data['defend']['Army']['standard'] = 0;
		    if($this->data['defend']['Army']['range'] == NULL)
		    	$this->data['defend']['Army']['range'] = 0;
		    if($this->data['defend']['Army']['mount'] == NULL)
		    	$this->data['defend']['Army']['mount'] = 0;
		    if($this->data['defend']['Army']['small_elite'] == NULL)
		    	$this->data['defend']['Army']['small_elite'] = 0;
		    if($this->data['defend']['Army']['big_elite'] == NULL)
		    	$this->data['defend']['Army']['big_elite'] = 0;
		    if($this->data['defend']['luck'] == NULL)
		    	$this->data['defend']['luck'] = 5;
		    if($this->data['User']['luck'] == NULL)
		    	$this->data['User']['luck'] = 5;
		    	
		    if($this->data['defend']['luck'] > 10)
		    	$this->data['defend']['luck'] = 10;
		    if($this->data['User']['luck'] > 10)
		    	$this->data['User']['luck'] = 10;
		    if($this->data['defend']['luck'] < 0)
		    	$this->data['defend']['luck'] = 0;
		    if($this->data['User']['luck'] < 0)
		    	$this->data['User']['luck'] = 0;
		    	
		    	
			
			$retal = false;
			/*
			 * Set up Attacker
			 */
			$attacker['User'] = $this->data['User'];
			if($this->data['User']['Attacking Score: '] != NULL)
				$attacker['User']['score'] = $this->data['User']['Attacking Score: '];
			else 
				$attacker['User']['score'] = 10000;
			$attacker['User']['id'] = $this->user['User']['id'];
			$attacker['User']['luck'] -= 5;
			$attacker['User']['race'] = $this->data['User']['Attacking Race: '];
			$attacker['User']['loyalty'] = 0;
			//$attacker['User']['Army']['bas'] = 100;
			$attacker['User']['Army']['strategy'] = $this->data['User']['Army']['strategy'];
			//$attacker['User']['Building']['medical_center'] = 0;
			//$attacker['User']['Resource']['land'] = 10;
			//$attacker['User']['Technology']['racial'] = 0;
			//$attacker['User']['Technology']['range'] = 0;
			//$attacker['User']['Technology']['blade'] = 0;
			//$attacker['User']['Technology']['light_armor'] = 0;
			//$attacker['User']['Technology']['heavy_armor'] = 0;
			//$attacker['User']['Technology']['tree_1'] = 0;
			$attacker['User']['Technology']['tree_2'] = 0;
			$attacker['User']['Technology']['tree_3'] = 0;
			
			$attacker['User']['Spell']['firewall'] = 0;
			if($attacker['User']['race'] == "Elf") {
				
			} else {
				$attacker['User']['Spell']['fiery_arrows'] = 0;
				$attacker['User']['Spell']['enchanted_weapons'] = 0;
				$attacker['User']['Spell']['hardened_armor'] = 0;
				$attacker['User']['Technology']['tree_4'] = 0;
			}
			
			/*
			 * Set up Defender
			 */
			$defender['User'] = $this->data['defend'];
			$defender['User']['id'] = 0;
			$defender['User']['race'] = $this->data['User']['Defending Race: '];
			$defender['User']['luck'] -= 5;
			$defender['User']['loyalty'] = 0;
			if($this->data['User']['Defending Score: '] != NULL)
				$defender['User']['score'] = $this->data['User']['Defending Score: '];
			else 
				$defender['User']['score'] = 10000;
			//$defender['User']['Army']['bas'] = 100;
			$defender['User']['Army']['strategy'] = $this->data['defend']['Army']['strategy'];
			$defender['User']['Spell']['shadow_hall'] = 0;
			//$defender['User']['Building']['guard_tower'] = 0;
			//$defender['User']['Building']['medical_center'] = 0;
			//$defender['User']['Resource']['land'] = 10;
			//$defender['User']['Building']['castle'] = 1;
			//$defender['User']['Building']['medical_center'] = 0;
			//$defender['User']['Technology']['racial'] = 0;
			//$defender['User']['Technology']['range'] = 0;
			//$defender['User']['Technology']['blade'] = 0;
			//$defender['User']['Technology']['light_armor'] = 0;
			//$defender['User']['Technology']['heavy_armor'] = 0;
			//$defender['User']['Technology']['tree_1'] = 0;
			//$defender['User']['Technology']['tree_2'] = 0;
			$defender['User']['Technology']['tree_3'] = 0;
			
			//$defender['User']['Spell']['firewall'] = 0;
			if($defender['User']['race'] == "Elf") {
				
			} else {
				$defender['User']['Spell']['fiery_arrows'] = 0;
				$defender['User']['Spell']['enchanted_weapons'] = 0;
				$defender['User']['Spell']['hardened_armor'] = 0;
				$defender['User']['Technology']['tree_4'] = 0;
			}
			
			$this->set('result', $this->Attack->invade($attacker, $this->data['attack'], $this->Connect, $retal, $defender));
		} else {
			$this->redirect(array('controller' => 'attacks', 'action' => '/attackSimulator'));
		}
			
	}
	
	function attackRaces(){
		$this->layout = 'ajax';
		$this->loadModel('Army');
		$user['User']['race'] = $this->data['User']['Attacking Race: '];

		switch($user['User']['race']){
			case "Orc":
				$tech = "For the Pack";
				break;
			case "Dwarf":
				$tech = "True Silver";
				break;
			case "Human":
				$tech = "Charisma";
				break;
			case "Gnome":
				$tech = "Explosives";
				break;
			case "Elf":
				$tech = "Magic Field";
				break;
		}
		
		$this->set('racialTech', $tech);
		$this->set('race', $user['User']['race']);
		$this->set('units', $this->Army->getUnitNames($user));
	}
	
	function defendRaces(){
		$this->layout = 'ajax';
		$this->loadModel('Army');
		$user['User']['race'] = $this->data['User']['Defending Race: '];
		
		switch($user['User']['race']){
			case "Orc":
				$tech = "For the Pack";
				break;
			case "Dwarf":
				$tech = "True Silver";
				break;
			case "Human":
				$tech = "Charisma";
				break;
			case "Gnome":
				$tech = "Explosives";
				break;
			case "Elf":
				$tech = "Magic Field";
				break;
		}
		
		$this->set('racialTech', $tech);
		$this->set('race', $user['User']['race']);
		$this->set('units', $this->Army->getUnitNames($user));
	}
	
}
?>