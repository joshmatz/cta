<?php
class HelpsController extends AppController {    
	var $name = 'Helps';
	var $facebook_id;
	var $layout = 'newuser';
	var $user;
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() { 
		parent::beforeFilter();
		$this->loadModel('User'); 
		$this->User->recursive = 2;
        //$this->facebook_id = $this->facebookId; 
        $this->user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account_id))); 
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->set('parms', $this->parms);
    }  
	
	function index($user_id, $type = null, $date = null) {   
		if(false){
			echo "Helps are currently down. Please try again later.";
		} else {
			if($this->facebook_id) {     
				$this->loadModel('IpAddress');
				if(!isset($this->user)){
					$this->user['User']['premium'] = 0;
				}
				if($this->IpAddress->checkAddresses($user_id, 0, $_SERVER['REMOTE_ADDR']) && !$this->user['User']['premium']){
					$this->Session->setFlash('Unfortunately you are trying to help a player on your same network. To curb multi-accounts, please register and become a premium member to complete this action.');
					if(!isset($this->user['User']['ruler'])){
						$this->redirect(array('controller' => 'users', 'action' => 'create'));
					} else {
						$this->redirect(array('controller' => 'premiums', 'action' => 'index'));
					}
				}
				
				switch($this->Help->checkHelper($this->facebook_id, $user_id, $type, $date)) {
					case "Same User":
						$this->Session->setFlash('You cannot help yourself.');
						break;
					case "Expired":
						$this->Session->setFlash('This offer has expired.');
						break;
					case "None Found":
						$this->Session->setFlash('There was no offer found.');
						break;
					case "Already Helped":
						$this->Session->setFlash('You\'ve already helped <fb:name uid="' . $user_id .'" capitalize="true" /></fb:name>.');
						break;
					case "Max":
						$this->Session->setFlash('<fb:name uid="' . $user_id .'" capitalize="true" /></fb:name> has reached the maximum help they can receive.');
						break;
					case "Wrong Date":
						$this->Session->setFlash('The help did not match a validity check. You likely clicked an old help request.');
						break;
					case "OK":
						$fql = 'SELECT uid1 FROM friend WHERE uid1 = '. $this->facebook_id .' AND uid2 = '.$user_id.';';
						$friends = $this->facebook->api(array(
								'method' => 'fql.query',
								'query' =>$fql,
							)
						);
						
						$found = false;
						if($this->account['Account']['role'] == 'admin') {
							$debugstuff = $this->facebook->api('/515367144/friends');
							//debug($debugstuff);
							debug($friends);
						}
						foreach($friends['data'] as $friend):
							if($user_id == $friend['id'])
								$found = true;
						endforeach;
						if($found){
							if($type == "Tech"){
								$helpee = $this->User->find('first', array('conditions' => array('User.facebook_id' => $user_id)));
								if($helpee['Technology']['time'] != 0) {
									// Add in the help
									$this->Help->addNewHelp($user_id, $this->facebook_id, $type, $date);
									
									// Let the user know it a
									$this->Session->setFlash('You helped <fb:name uid="' . $user_id .'" capitalize="true" /></fb:name> research technology faster!');
									
									// Research
									$helpee['Technology']['time'] -= 2;
									if($helpee['Technology']['time'] < 1)
										$helpee['Technology']['time'] = 1;
									$this->User->Technology->id = $helpee['Technology']['user_id'];
									$this->User->Technology->save($helpee);
									
									// Generate Report
									$this->loadModel('Report');
									$this->Report->makeNewReport($helpee['User']['id'], "<fb:name uid=\"" . $this->facebook_id .  "\" capitalize=\"true\" /></fb:name> helped you complete your research technology faster!", "Help", $_SERVER['REMOTE_ADDR'], 0, "Someone helped you research tech!");
								}
								else {
									$this->Session->setFlash('It looks like <fb:name uid="' . $user_id .'" capitalize="true" /></fb:name> has already completed that technology!');
								}
						
							}
							elseif($type == "Attacked"){
								$this->Session->setFlash('You helped <fb:name uid="' . $user_id .'" capitalize="true" /></fb:name> recover from an attack by providing additional resources!');
							}	
						}
						else {
							$this->Session->setFlash('You must be that user\'s friend to help them.');
						}	

						break;
					default:
						// redirect problem
						break;
				}
				if(isset($this->user['User']))
							$this->redirect(array('controller' => 'users', 'action' => 'index' . $this->parms));
			}
			else {
				
				$this->Session->setFlash('There was a problem obtaining your user id. <fb:name uid="' . $user_id .'" capitalize="true" /></fb:name> could not be helped.');
				//$this->redirect(array('controller' => 'users', 'action' => 'index'));
			}
		}
	}

}
?>