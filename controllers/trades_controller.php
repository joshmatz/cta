<?php
class TradesController extends AppController {    
	var $name = 'Trades';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	var $paginate = array(
		'limit' => 15,
		'order' => array(
			'Trade.date' => 'desc'
		)    
	);
	

	function beforeFilter() {  
		$this->loadModel('User');
		$this->User->recursive = 2;
		parent::beforeFilter();
        //$this->facebook_id = $this->facebookId;
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
		if($this->user['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed'));
	    }
        $this->User->id = $this->user['User']['id'];
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id);  
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		
		$this->menu = array(
			array(
				'name' => 'Trades',
				'link' => '/trades/index'
			),
			array(
				'name' => 'Create New Offer',
				'link' => '/trades/create'
			),
			array(
				'name' => 'View Your Offers',
				'link' => '/trades/currentOffers'
			)
		);
		$this->set('menu', $this->menu);
    }  
    
	/******
	 * Give the view basic data to make the table
	 */
	function index() {  
		$resources = $this->User->Resource->find('all', array('fields' => array('Resource.gold, Resource.iron, Resource.food, Resource.wood'), 'limit' => 15, 'order' => 'User.rank ASC'));
		//debug($resources);
		$goldTotal = 0;
		$ironTotal = 0;
		$woodTotal = 0;
		$foodTotal = 0;
		//debug($resources);
		foreach($resources as $resource):
			$goldTotal += $resource['Resource']['gold'];
			$foodTotal += $resource['Resource']['food'];
			$ironTotal += $resource['Resource']['iron'];
			$woodTotal += $resource['Resource']['wood'];
		endforeach;
		if($this->user['User']['account_id'] == 1){
			//echo "Gold: $goldTotal. <br />Food: $foodTotal<br />Iron: $ironTotal<br />Wood: $woodTotal";
		}
		$userProvince = $this->user['User']['province'] . " " . $this->user['User']['province_number'];
		$trades = $this->paginate(
			'Trade', array(
				"OR" => array(
					array(
						'Trade.scope' => 'world',
						'Trade.alliance_id' => $this->user['User']['alliance_id']
					),
					array(
						'Trade.scope' => 'race', 
						'Trade.race' => $this->user['User']['race'],
						'Trade.alliance_id' => $this->user['User']['alliance_id']
					), 
					array(
						'Trade.scope' => 'alliance',
						'Trade.alliance_id' => $this->user['User']['alliance_id']
					),
					array(
						'Trade.scope' => 'world',
						'Trade.date <=' => strtotime("-6 hours")
					)
				)
			)
		);
	/*
		$i = 0;
		foreach($trades as &$trade):
			if($trade['Trade']['scope'] != 'world') {
				//if($trade['Trade']['scope'] == 'race' && $trade['Trade']['race'] != $this->user['User']['race']){
				//	unset($trades[$i]);
				//}
				//if($trade['Trade']['scope'] == 'province' && $trade['Trade']['province'] != $userProvince) {
				//	unset($trades[$i]);
				//}
			}
			$i++;
		endforeach;*/
		$this->set('trades', $trades);
		$this->set('user', $this->user);
	}
	
	function tradesList(){
		$this->layout = 'ajax';
		$trades = $this->paginate(
			'Trade', array(
				"OR" => array(
					array(
						'Trade.scope' => 'world',
						'Trade.alliance_id' => $this->user['User']['alliance_id']
					),
					array(
						'Trade.scope' => 'race', 
						'Trade.race' => $this->user['User']['race'],
						'Trade.alliance_id' => $this->user['User']['alliance_id']
					), 
					array(
						'Trade.scope' => 'alliance',
						'Trade.alliance_id' => $this->user['User']['alliance_id']
					),
					array(
						'Trade.scope' => 'world',
						'Trade.date <=' => strtotime("-6 hours")
					)
				)
			)
		);
	/*
		$i = 0;
		foreach($trades as &$trade):
			if($trade['Trade']['scope'] != 'world') {
				//if($trade['Trade']['scope'] == 'race' && $trade['Trade']['race'] != $this->user['User']['race']){
				//	unset($trades[$i]);
				//}
				//if($trade['Trade']['scope'] == 'province' && $trade['Trade']['province'] != $userProvince) {
				//	unset($trades[$i]);
				//}
			}
			$i++;
		endforeach;*/
		$this->set('trades', $trades);
		$this->set('user', $this->user);
	}
	
	function currentOffers() {
		$trades = $this->paginate('Trade', array('Trade.user_id' => $this->user['User']['id']));
		$this->set('trades', $trades);
		$this->set('user', $this->user);
	}
	
	function create() {
		
		$this->set('user', $this->user);
	}
	
	function find($provinceName = null, $provinceNumber = null) {
		if(!$provinceName || !$provinceNumber){
			$provinceName = $this->user['User']['province'];
			$provinceNumber = $this->user['User']['province_number'];
		}
		if($complicatedMembers = $this->User->getProvinceMembers($provinceName, $provinceNumber)){
			$count = 0;
			foreach($complicatedMembers as $member):
				$members[$count]['id'] = $member['User']['id'];
				$members[$count]['name'] = $member['User']['ruler'];
				$count++;
			endforeach;
			$options[0] = " ";
			foreach($members as $member):
				$id = $member['id'];
				$name = $member['name'];
				$options["$id"] = $name;
			endforeach;
			
			$this->set('province_name', $provinceName);
			$this->set('name', $name);
			$this->set('province_number', $provinceNumber);
			$this->set('options', $options);
			$this->set('user', $this->user);
		}
		else
		{
			$members[0]['0'] = 0;
			$members[0]['name'] = " ";
			$options[0] = " ";

			$this->Session->setFlash('This province has no members!');
			$this->set('name', $provinceName);
			$this->set('province_number', $provinceNumber);
			$this->set('options', $options);
			$this->set('user', $this->user);
		}
		$this->set('user', $this->user);
	}
	
	function findRedirect() {
		// If coming from this page from elsewhere
		if(!isset($this->data))
			$this->redirect(array('action'=>'index'));
		// if no target selected
		if($this->data['Trade']['target'] == 0)
			$this->redirect(array('action'=>'find/' . $this->data['Trade']['name'] . '/' . $this->data['Trade']['provinceNumber'])); 
		else 
			$this->redirect(array('action'=>'sendTo/' . $this->data['Trade']['target'])); 
	}
	
	function sendTo($userId) {
		if($userId == $this->user['User']['id']){
			$this->Session->setFlash('You cannot send yourself resources.');
			$this->redirect(array('action'=>'find'));
		}
		if($this->user['User']['beginner_protection']){
			$this->Session->setFlash('You are in beginner protection and are not allowed to send resources.');
			$this->redirect(array('action'=>'index'));
		}
		$target = $this->User->find('first', array('conditions' => array('User.id' => $userId), 'fields' => array('beginner_protection', 'alliance_id'))); 
		if($target['User']['beginner_protection']) {
			$this->Session->setFlash('You cannot send resources to a player in beginner protection.');
			$this->redirect(array('action'=>'index'));
		}
		if($this->user['User']['alliance_id'] != $target['User']['alliance_id']){
			$this->Session->setFlash('You cannot send resources to a player outside your alliance.');
			$this->redirect(array('action'=>'index'));
		}
		$this->set('name', $this->User->getName($userId));
		$this->set('target', $userId);
		$this->set('user', $this->user);
	}
	
	function send() {
		if(!empty($this->data)){
			$verification = $this->Connect->user('verified');
	    	if(isset($verification)){
	    		$verified = true;
	    	} else {
	    		$verified = false;
	    	}
	    	$targetUser = $this->User->find('first', array('conditions' => array('User.id' => $this->data['target'])));
			$this->loadModel('IpAddress');
			if($this->IpAddress->checkAddresses($this->data['target'], $this->user['User']['id']) && !$this->user['User']['premium']){
				$this->Session->setFlash('You\'ve played on this player\'s IP Address before. Please verify your account by purchasing and using premium points before proceeding to interact with this player.');
				$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			}
			if($this->IpAddress->checkInteractAddresses($this->data['target'], $this->user['User']['id'], $_SERVER['REMOTE_ADDR']) && !$this->user['User']['premium']){
				$this->Session->setFlash('Another player on your IP address(es) has traded with this kingdom in the past 7 days. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
				$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			}
			if($this->user['User']['alliance_id'] != $targetUser['User']['alliance_id']){
				$this->Session->setFlash('You cannot send resources to a player outside your alliance.');
				$this->redirect(array('action'=>'index'));
			}
			if((!ctype_digit($this->data['Trade']['wood_amount']) && $this->data['Trade']['wood_amount'] != NULL)  || 
			(!ctype_digit($this->data['Trade']['iron_amount']) && $this->data['Trade']['iron_amount'] != NULL ) || 
			(!ctype_digit($this->data['Trade']['gold_amount']) && $this->data['Trade']['gold_amount'] != NULL) || 
			(!ctype_digit($this->data['Trade']['food_amount']) && $this->data['Trade']['food_amount'] != NULL)){
				$this->Session->setFlash('You must use only numbers for the trade amounts. Please do not use commas.');
			}
			elseif($this->data['Trade']['wood_amount'] == NULL && $this->data['Trade']['iron_amount'] == NULL && $this->data['Trade']['gold_amount'] == NULL && 
			$this->data['Trade']['food_amount'] == NULL ){
				$this->Session->setFlash('You left all the resource fields blank.');
			}
			else {
				$trade = $this->data;
				if($this->user['Resource']['wood'] >= $trade['Trade']['wood_amount'] && $this->user['Resource']['iron'] >= $trade['Trade']['iron_amount'] &&
				$this->user['Resource']['gold'] >= $trade['Trade']['gold_amount'] && $this->user['Resource']['food'] >= $trade['Trade']['food_amount']) {
					
					
					if(!empty($targetUser)) {
						$this->user['Resource']['wood'] -= $trade['Trade']['wood_amount'];
						$this->user['Resource']['iron'] -= $trade['Trade']['iron_amount'];
						$this->user['Resource']['gold'] -= $trade['Trade']['gold_amount'];
						$this->user['Resource']['food'] -= $trade['Trade']['food_amount'];
						
						$targetUser['Resource']['wood'] += $trade['Trade']['wood_amount'];
						$targetUser['Resource']['iron'] += $trade['Trade']['iron_amount'];
						$targetUser['Resource']['gold'] += $trade['Trade']['gold_amount'];
						$targetUser['Resource']['food'] += $trade['Trade']['food_amount'];
						
						$stuff = "";
						if($trade['Trade']['wood_amount'])
							$stuff .= number_format($trade['Trade']['wood_amount']) . " wood";
						if($trade['Trade']['iron_amount']) {
							if($trade['Trade']['wood_amount'])
								$stuff .= ", " . number_format($trade['Trade']['iron_amount']) . " iron";
							else
								$stuff .= number_format($trade['Trade']['iron_amount']) . " iron";
						}
						if($trade['Trade']['gold_amount']) {
							if($trade['Trade']['wood_amount'] || $trade['Trade']['iron_amount'])
								$stuff .= ", " . number_format($trade['Trade']['gold_amount']) . " gold";
							else
								$stuff .= number_format($trade['Trade']['gold_amount']) . " gold";
						}
						if($trade['Trade']['food_amount']) {
							if($trade['Trade']['wood_amount'] || $trade['Trade']['iron_amount'] || $trade['Trade']['gold_amount'])
								$stuff .= ", " . number_format($trade['Trade']['food_amount']) . " food";
							else
								$stuff .= number_format($trade['Trade']['food_amount']) . " food";
						}
						$this->User->Resource->save($this->user['Resource']);
						$this->User->Resource->save($targetUser['Resource']);
						$this->loadModel('Report');
						$this->Report->makeNewReport($trade['target'], "You successfully received " . $stuff . " from " . $this->User->getName($this->user['User']['id']) . ".", "Received Trade", $_SERVER['REMOTE_ADDR'], $this->user['User']['id'], "You received resources from " . $this->User->getName($this->user['User']['id'], false) . ".");
						$sent = "You successfully sent " . $stuff . " to " . $this->User->getName($trade['target']) . "."; 
						$this->Report->makeNewReport($this->user['User']['id'], $sent, "Sent Trade", $_SERVER['REMOTE_ADDR'], $this->user['User']['id'], "You sent resources to " . $this->User->getName($trade['target'], false) . ".");
						$this->Session->setFlash($sent);
					}
					else {
						$this->Session->setFlash('User not found in database. Please try again or contact the administrator.');
					}
				} else {
					$this->Session->setFlash('You do not have the resources you want to send.');
				}
			}
		}
		else {
			$this->Session->setFlash('You accessed this page incorrectly.');
		}
		
		$this->redirect(array('action' => 'index'));
	}
	
	function createOffer() {
		if($this->user['User']['beginner_protection']) {
			$this->Session->setFlash('You cannot create offers when you are under beginner protection.');
			$this->redirect(array('action' => 'index'));
		}
		$trade = $this->data;
		$type = $trade['Trade']['type'];
		//debug($trade);
		if(!ctype_digit($trade['Trade']['amount']) || !ctype_digit($trade['Trade']['request_amount']) || !ctype_digit($trade['Trade']['offers'])){
			$this->Session->setFlash('You must use only numbers for the trade amounts.');
		}
		elseif($trade['Trade']['amount'] == NULL || $trade['Trade']['request_amount'] == NULL || $trade['Trade']['offers'] == NULL){
			$this->Session->setFlash('You left a field blank.');
		}else {
			if($this->user['Resource'][$type] >= ($trade['Trade']['amount'] * $trade['Trade']['offers'])) {
				if($trade['Trade']['type'] != $trade['Trade']['request_type']) {
					
					$resources = $this->User->Resource->find('all', array('fields' => array('Resource.gold, Resource.iron, Resource.food, Resource.wood'), 'limit' => 15, 'order' => 'User.rank ASC'));
					//debug($resources);
					$goldTotal = 0;
					$ironTotal = 0;
					$woodTotal = 0;
					$foodTotal = 0;
					//debug($resources);
					foreach($resources as $resource):
						$goldTotal += $resource['Resource']['gold'];
						$foodTotal += $resource['Resource']['food'];
						$ironTotal += $resource['Resource']['iron'];
						$woodTotal += $resource['Resource']['wood'];
					endforeach;
					if($this->user['User']['account_id'] == 1){
//1029370298
//262662490
//4580101
//6049658

						//debug($goldTotal);
						//debug($foodTotal);
						//debug($ironTotal);
						//debug($woodTotal);
					}
					$ratio = $trade['Trade']['amount'] / $trade['Trade']['request_amount'];
					//debug($ratio);
					if($trade['Trade']['type'] == "gold" && $trade['Trade']['request_type'] == "food"){
						if($ratio < ($goldTotal / $foodTotal) - ($goldTotal / $foodTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "gold" && $trade['Trade']['request_type'] == "iron") {
						if($ratio < ($goldTotal / $ironTotal) - ($goldTotal / $ironTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "gold" && $trade['Trade']['request_type'] == "wood") {
						if($ratio < ($goldTotal / $woodTotal) - ($goldTotal / $woodTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "food" && $trade['Trade']['request_type'] == "gold") {
						if($ratio < ($foodTotal / $goldTotal) - ($foodTotal / $goldTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "food" && $trade['Trade']['request_type'] == "iron") {
						if($ratio < ($foodTotal / $ironTotal) - ($foodTotal / $ironTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "food" && $trade['Trade']['request_type'] == "wood") {
						if($ratio < ($foodTotal / $woodTotal) - ($foodTotal / $woodTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "iron" && $trade['Trade']['request_type'] == "gold") {
						if($ratio < ($ironTotal / $goldTotal) - ($ironTotal / $goldTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "iron" && $trade['Trade']['request_type'] == "food") {
						if($ratio < ($ironTotal / $foodTotal) - ($ironTotal / $foodTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "iron" && $trade['Trade']['request_type'] == "wood") {
						if($ratio < ($ironTotal / $woodTotal) - ($ironTotal / $woodTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "wood" && $trade['Trade']['request_type'] == "gold") {
						if($ratio < ($woodTotal / $goldTotal) - ($woodTotal / $goldTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "wood" && $trade['Trade']['request_type'] == "iron") {
						if($ratio < ($woodTotal / $foodTotal) - ($woodTotal / $foodTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}else if($trade['Trade']['type'] == "wood" && $trade['Trade']['request_type'] == "food") {
						if($ratio < ($woodTotal / $ironTotal) - ($woodTotal / $ironTotal) * .85){
							$this->Session->setFlash('You cannot trade for that cheap amount');
							$this->redirect(array('controller' => 'trades', 'action' => 'create'));
						}
					}
					$trade['Trade']['user_id'] = $this->user['User']['id'];
					$trade['Trade']['kingdom'] = $this->user['User']['ruler'];
					$trade['Trade']['alliance_id'] = $this->user['User']['alliance_id'];
					$trade['Trade']['race'] = $this->user['User']['race'];
					$trade['Trade']['date'] = time();
					$trade['Trade']['ratio'] = $ratio;
					$this->Trade->create();
					$this->Trade->save($trade);
					
					$this->user['Resource'][$type] -= ($trade['Trade']['amount'] * $trade['Trade']['offers']);
					if($this->User->Resource->save($this->user['Resource']))
						$this->Session->setFlash('Trade successfully posted!');
					else {
						
					}
				}
				else {
					$this->Session->setFlash('You cannot offer the same resource you are requesting.');
				}
			} else {
				$this->Session->setFlash('You do not have enough resources to post that trade.');
			}
		}
		
		
		
		$this->redirect(array('controller' => 'trades', 'action' => 'index'));
	}
	
	function acceptOffer($tradeId = null){
		if($this->user['User']['beginner_protection']) {
			$this->Session->setFlash('You cannot accept offers when you are under beginner protection.');
			$this->redirect(array('action' => 'index'));
		}
		if(!ctype_digit($this->data['Trade']['offers'])){
			$this->Session->setFlash('You must type in how many offers you will accept.');
			$this->redirect(array('action' => 'index'));
		}
		$trade = $this->Trade->getTrade($tradeId);
		if(!empty($trade)){
			$verification = $this->Connect->user('verified');
	    	if(isset($verification)){
	    		$verified = true;
	    	} else {
	    		$verified = false;
	    	}
			$this->loadModel('IpAddress');
			if($this->IpAddress->checkAddresses($trade['Trade']['user_id'], $this->user['User']['id']) && !$this->user['User']['premium']){
				$this->Session->setFlash('You\'ve played on this player\'s IP Address before. Please verify your account by purchasing and using premium points before proceeding to interact with this player.');
				$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			}
			if($this->IpAddress->checkInteractAddresses($trade['Trade']['user_id'], $this->user['User']['id'], $_SERVER['REMOTE_ADDR']) && !$this->user['User']['premium']){
				$this->Session->setFlash('Another player on your IP address(es) has traded with this kingdom in the past 7 days. In order to reduce multiple accounts, please upgrade to a premium account before proceeding.');
				$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
			}
			if($trade['Trade']['user_id'] == $this->user['User']['id']){
				$this->Session->setFlash('You cannot accept your own trades');
			}
			else{
				$type = $trade['Trade']['request_type'];
				// If Not enough offers are available
				if($trade['Trade']['offers'] < $this->data['Trade']['offers']){
					$this->Session->setFlash('Not enough offers availabe.');
					$this->redirect(array('action' => 'index'));
				}
				// If scope doesn't match person accepting trade
				if(($trade['Trade']['scope'] == 'world') || 
				($trade['Trade']['scope'] == 'race' && $trade['Trade']['race'] == $this->user['User']['race']) ||
				($trade['Trade']['scope'] == 'alliance' && $trade['Trade']['alliance_id'] == $this->user['User']['alliance_id'])) {
					// If the person accepting the trade does not have enough resources
					if($trade['Trade']['request_amount'] * $this->data['Trade']['offers'] <= $this->user['Resource'][$type]){
						$tradeType = $trade['Trade']['type'];
						$this->user['Resource'][$tradeType] += ($trade['Trade']['amount']) * $this->data['Trade']['offers'];
						$this->user['Resource'][$type] -= ($trade['Trade']['request_amount']) * $this->data['Trade']['offers'];
						$this->User->Resource->save($this->user['Resource']);
						
						$data = $this->User->Resource->find('first', array('conditions' => array('Resource.user_id' => $trade['Trade']['user_id'])));
						$data['Resource'][$type] += $trade['Trade']['request_amount'] * $this->data['Trade']['offers'];
						$tradedStuff = number_format($trade['Trade']['request_amount'] * $this->data['Trade']['offers']) . " $type";
						$stuff = number_format($trade['Trade']['amount'] * $this->data['Trade']['offers']) . " $tradeType";
						$this->loadModel('Report');
						$this->Report->makeNewReport($trade['Trade']['user_id'], "You successfully received " . $tradedStuff . " from " . $this->User->getName($this->user['User']['id']) . " for your " . $stuff .". ", "Make Trade", $_SERVER['REMOTE_ADDR'], $this->user['User']['id'], "You traded with " . $this->User->getName($this->user['User']['id'], false) . ".");
						$this->User->Resource->save($data['Resource']);
						$trade['Trade']['offers'] -= $this->data['Trade']['offers'];
						if($trade['Trade']['offers'] == 0){
							$this->Trade->delete($tradeId);
						} else {
							$this->Trade->save($trade);
						}
						$exchange = "You successfully exchanged " . $tradedStuff . " for " . $stuff . " from " . $this->User->getName($trade['Trade']['user_id']) . "!"; 
						$this->Report->makeNewReport($this->user['User']['id'], $exchange, "Accept Trade", $_SERVER['REMOTE_ADDR'], $this->user['User']['id'], "You traded with " . $this->User->getName($trade['Trade']['user_id'], false) . ".");
						$this->Session->setFlash($exchange);
					}
					else {
						$this->Session->setFlash('You do not have enough resources to accept that offer.');
					}
				} else {
					$this->Session->setFlash('Trade did not match registered scope.');
				}
			}
		}
		else{
			$this->Session->setFlash('Something went wrong accessing the trade data. Please try again and if the problem persists contact the administrator.');
		}
		$this->redirect('index');
	}
	
	function remove($tradeId = null){
		$trade = $this->Trade->getTrade($tradeId);
		if(!empty($trade)){
			if($trade['Trade']['user_id'] == $this->user['User']['id']){
				$type = $trade['Trade']['type'];
				$this->user['Resource'][$type] += $trade['Trade']['amount'] * $trade['Trade']['offers'];
				$this->User->Resource->save($this->user['Resource']);
				$this->Trade->delete($tradeId);
				$this->Session->setFlash('Trade successfully removed.');
			}
			else{
				$this->Session->setFlash('You do not have permission to remove that trade.');
			}
		}
		else{
			$this->Session->setFlash('Something went wrong accessing the trade data. Please try again and if the problem persists contact the administrator.');
		}
		$this->redirect('index');
	}
	
	function removeAll() {
		$trades = $this->Trade->find('all', array('conditions' => array('Trade.user_id' => $this->user['User']['id'])));
		if(!empty($trades)) {
			foreach($trades as $trade):
				$type = $trade['Trade']['type'];
				$this->user['Resource'][$type] += $trade['Trade']['amount'] * $trade['Trade']['offers'];
				$this->Trade->delete($trade['Trade']['id']);
			endforeach;
			$this->User->Resource->save($this->user['Resource']);
			$this->Session->setFlash('Trades successfully removed.');
		}
		
		$this->redirect('index');
	}
	
}
?>