<?php
class TechnologiesController extends AppController {    
	var $name = 'Technologies';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	var $date;
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		$this->Technology->recursive = 2;
		parent::beforeFilter();
        //$this->facebook_id = $this->facebookId;
        $this->user = $this->Technology->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
        $this->Technology->id = $this->user['Technology']['id'];
		$this->Technology->User->id = $this->user['User']['id'];
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->loadModel('Message');
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		
		$this->menu = array(
			array(
				'name' => 'Current Research',
				'link' => '/technologies/'
			),
			array(
				'name' => 'Mastered Techs',
				'link' => '/technologies/currentTechs'
			),
			array(
				'name' => 'Tech Coins',
				'link' => '/technologies/coins'
			)
		);
		$this->set('menu', $this->menu);
    }  
    
    function coins() {
		if($this->data):
			if($this->data['Technology']['coins']): 
				if($this->user['Technology']['time'] > 1):
		    		if($this->data['Technology']['coins'] <= $this->user['Technology']['coins']) :
		    			if($this->data['Technology']['coins'] >= $this->user['Technology']['time']):
		    				$this->data['Technology']['coins'] = $this->user['Technology']['time'] - 1;
		    			endif;
		    			if($this->data['Technology']['coins'] == 0):
		    				$this->Session->setFlash('You do not have enough time remaining on your technology to use coins.');
		    			else:
		    				$this->user['Technology']['time'] -= $this->data['Technology']['coins'];
		    				$this->user['Technology']['coins'] -= $this->data['Technology']['coins'];
		    				$this->Session->setFlash('You used '.$this->data['Technology']['coins'].' to reduce your tech time to '.$this->user['Technology']['time'].'!');
		    				$this->Technology->save($this->user['Technology']);
		    				$this->data = array();
		    			endif;
		    		else:
		    			$this->Session->setFlash('You do not have that many TechCoins');
		    		endif;
		    	else:
		    		$this->Session->setFlash('You need more than 1 tick remaining on your technology to use a TechCoin.');
				endif;
			else:
				$this->Session->setFlash('You must put in a number greater than 0.');
			endif;
		endif; // end if form submitted
    	$this->set('user', $this->user);
    }
    
	/******
	 * Give the view basic data to make the table
	 */	
	function index($date = null) {
				
		if($this->user['Technology']['time'] > 0) {
			$this->loadModel('Help');
			$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $this->user['User']['facebook_id']), 'order' => 'Help.date DESC'));
			$date = $help['Help']['date'];
			switch($this->user['Technology']['research']){
				case "tree_1":
					$display = $this->Technology->getTree1Display($this->user);
					break;
				case "tree_2":
					$display = $this->Technology->getTree2Display($this->user);
					break;
				case "tree_3":
					$display = $this->Technology->getTree3Display($this->user);
					break;
				case "tree_4":
					$display = $this->Technology->getTree4Display($this->user);
					break;
				case "racial":
					$display = $this->Technology->getRacialDisplay($this->user);
					break;
				case "blade":
					$display = $this->Technology->getBladeDisplay($this->user);
					break;
				case "light_armor":
					$display = $this->Technology->getLightArmorDisplay($this->user);
					break;
				case "heavy_armor":
					$display = $this->Technology->getHeavyArmorDisplay($this->user);
					break;
				case "range":
					$display = $this->Technology->getRangeDisplay($this->user);
					break;
				default:
					break;
			}
			$this->set('newResearch', 1);
			//$this->set('facebook', $this->facebook);
			$this->set('image', $display['img']);
			$this->set('name', $display['name']);
			$this->set('totalHelps', $this->Help->find('count', array('conditions' => array('Help.facebook_id' => $this->user['User']['facebook_id'], 'Help.date' => $date, 'Help.type' => "tech"))));
			$this->set('currentResearch', $this->Technology->getCurrentResearch($this->user));
			$this->set('queuedResearch', $this->Technology->getQueuedResearch($this->user));
		}
		$this->set('date', $date);
		//debug($this->Technology->getBladeDisplay($this->user));
		$this->set('user', $this->user);
		$this->set('facebook_id', $this->user['User']['facebook_id']);
		
		$this->user = $this->Technology->increaseTechForDisplay($this->user);
		$this->set('tree1', $this->Technology->getTree1Display($this->user));
		$this->set('tree2', $this->Technology->getTree2Display($this->user));
		$this->set('tree3', $this->Technology->getTree3Display($this->user));
		$this->set('tree4', $this->Technology->getTree4Display($this->user));
		$this->set('racial', $this->Technology->getRacialDisplay($this->user));
		$this->set('blade', $this->Technology->getBladeDisplay($this->user));
		$this->set('lightArmor', $this->Technology->getLightArmorDisplay($this->user));
		$this->set('heavyArmor', $this->Technology->getHeavyArmorDisplay($this->user));
		$this->set('range', $this->Technology->getRangeDisplay($this->user));
		$this->set('selected', 'New Research');
	}
	
	function research() {
		$date = 0;
		if($this->user['User']['Building']['castle'] == 0){
	      		$this->redirect(array('controller' => 'users', 'action' => 'destroyed'));
	    } 		
		if($this->user['Technology']['time'] == 0) {
			if(isset($this->data)) {
				$research = $this->Technology->research($this->user, $this->data);
			}
			else {
				$this->Session->setFlash('There is no form data.');
			}
		}
		else if(!$this->user['Technology']['queue']) {
			if(isset($this->data)) {
				$research = $this->Technology->researchQueue($this->user, $this->data);
			}
			else {
				$this->Session->setFlash('There is no form data.');
			}
		} else {
			$this->Session->setFlash("You're currently researching something");
		}
			
		
		$this->redirect(array('action'=>'index/' . $date )); 
	}
	
	function currentTechs() {
		$this->set('selected', 'Mastered Techs');
		$this->set('user', $this->user);
		$tree1 = array();
		$tree2 = array();
		$tree3 = array();
		$tree4 = array();
		$racial = array();
		$blade = array();
		$lightArmor = array();
		$heavyArmor = array();
		$range = array();
		--$this->user['Technology']['tree_1'];
		--$this->user['Technology']['tree_2'];
		--$this->user['Technology']['tree_3'];
		--$this->user['Technology']['tree_4'];
		--$this->user['Technology']['racial'];
		--$this->user['Technology']['blade'];
		--$this->user['Technology']['light_armor'];
		--$this->user['Technology']['heavy_armor'];
		--$this->user['Technology']['range'];
		for(; $this->user['Technology']['tree_1'] >= 0; --$this->user['Technology']['tree_1']){
			$tree1[] = $this->Technology->getTree1Display($this->user);
		}
		$this->set('tree1', $tree1);
		for(; $this->user['Technology']['tree_2'] >= 0; $this->user['Technology']['tree_2']--){
			$tree2[] = $this->Technology->getTree2Display($this->user);
		}
		$this->set('tree2', $tree2);
		for(; $this->user['Technology']['tree_3'] >= 0; $this->user['Technology']['tree_3']--){
			$tree3[] = $this->Technology->getTree3Display($this->user);
		}
		$this->set('tree3', $tree3);
		for(; $this->user['Technology']['tree_4'] >= 0; $this->user['Technology']['tree_4']--){
			$tree4[] = $this->Technology->getTree4Display($this->user);
		}
		$this->set('tree4', $tree4);
		for(; $this->user['Technology']['racial'] >= 0; $this->user['Technology']['racial']--){
			$racial[] = $this->Technology->getRacialDisplay($this->user);
		}
		$this->set('racial', $racial);
		for(; $this->user['Technology']['blade'] >= 0; $this->user['Technology']['blade']--){
			$blade[] = $this->Technology->getBladeDisplay($this->user);
		}
		$this->set('blade', $blade);
		for(; $this->user['Technology']['light_armor'] >= 0; $this->user['Technology']['light_armor']--){
			$lightArmor[] = $this->Technology->getLightArmorDisplay($this->user);
		}
		$this->set('lightArmor', $lightArmor);
		for(; $this->user['Technology']['heavy_armor'] >= 0; $this->user['Technology']['heavy_armor']--){
			$heavyArmor[] = $this->Technology->getHeavyArmorDisplay($this->user);
		}
		$this->set('heavyArmor', $heavyArmor);
		for(; $this->user['Technology']['range'] >= 0; $this->user['Technology']['range']--){
			$range[] = $this->Technology->getRangeDisplay($this->user);
		}
		$this->set('range', $range);
	}

	
}
?>