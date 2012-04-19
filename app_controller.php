<?php
App::import('Lib', 'Facebook.FacebookInfo');
class AppController extends Controller {
	var $helpers = array('Session', 'Facebook.Facebook', 'Html', 'Form', 'Js', 'Plural', 'InplaceUpdater.InplaceUpdater', 'Ajax', 'Javascript', 'Bbcode', 'Markitup');
	var $components = array(
		'Session', 
		'Auth' => array(
			'authorize' => 'controller', 
			'loginError' => 'No, you fool!  You entered the wrong email or password!',
			'authError' => 'Sorry, you are lacking access.',
			'autoRedirect' => false,
			'fields' => array('username' => 'email', 'password' => 'password'),
			'userModel' => 'Account'
		),
		'Facebook.Connect',
		'Cookie', 
		'Email',
		'RequestHandler',
		'Bbcode'
	);
	var $view = 'Theme';
	var $theme = 'blank';
	
	function beforeFilter() {
		$this->set("title_for_layout","Call to Arms");
		
		/*
		 *	Grab $this->account. and facebook info
		 */
		$this->account = $this->Auth->user();
		if(!empty($this->account)):
			$this->Session->write('isLoggedIn', true);
		endif;
		$this->set('account', $this->account);
		$this->account_id = $this->account['Account']['id'];
		if($this->Connect->user()):
			$this->set('facebook_user', $this->Connect->user());			
		endif;
		
		/*
		 *	Admin routing
		 */ 
		if(isset($this->params['admin']) && $this->params['admin']) {
			// set Flash and redirect to login page
			if($this->Auth->user('role') != 'admin' && $this->Auth->user('role') != 'council') {
				$this->Session->setFlash('You do not have the correct privileges to access that.','default',array('class'=>'warning-box'));
				$this->redirect(array('controller'=>'users','action'=>'login','admin'=>FALSE));
			}
		}
		
		/*
		 * This is shit, but I have other stuff that needs it because I was an idiot.
		 */
		$this->parms="";  // This parameter string must be added to all links and forms urls
		$this->simpleParms = ""; // This string must be added to all CakePHP Helpers
		$this->set('simpleParms', '');
		/*
		 * End shit
		 */
		
		/*
		 *	Set $this->premium for controller use and $points in view.
		 */
		$this->loadModel('Premium');
        $this->premium = $this->Premium->find('first', array('conditions' => array('Premium.account_id' => $this->account['Account']['id']), 'order' => 'Premium.date DESC'));
		if($this->premium) {
       		$this->set('points', $this->premium['Premium']['new_amount']);
		} else {
			$this->set('points', 0);
		}
		
		/*
		 * If round is over, don't give access to game functions
		 * Allows /pages, /guides, /fames, and login/out
		 */
		 $action = $GLOBALS['Dispatcher']->here;
		 if($this->Auth->user('role') != 'admin' && $this->Auth->user('role') != 'council') {
			if($roundOver = false) { 
				
				
				$actions = explode('/', $action);
				if($actions[1] != 'pages' && $actions[1] != 'guides' && $actions[1] != "" && $actions[1] != 'fames' && $actions[1] != 'forums'){
					$login = false;
					if(isset($actions[2])){
						if($actions[2] == 'login' || $actions[2] == 'logout')
						$login = true;
					}
					if(!$login){
						$this->Session->setFlash('A new round has not started yet, be patient!');
						$this->redirect('/');
					}
				}
			}
		}
		
		/*
		 * If we want to display something differently for mobile users.
		 * Sucks right now, but only way I know how?
		 */
		$mobileUA = '(iPhone|Mobile Safari|MIDP|AvantGo|BlackBerry|J2ME|Opera Mini|DoCoMo|NetFront|Nokia|PalmOS|PalmSource|portalmmm|Plucker|ReqwirelessWeb|SonyEricsson|Symbian|UP\.Browser|Windows CE|Xiino)';
        preg_match('/' . $mobileUA . '/i', env('HTTP_USER_AGENT'), $match);
        $mobile = false;
        if(!empty($match))
        	$mobile = true;
        $this->set('mobile', $mobile);
		
		/*
		 * Check suspension status. 
		 * Not sure why it's setting $newReport to view here...
		 */
        $this->loadModel('User');
        $user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->account['Account']['id']), 'fields' => array('suspended', 'suspension_view', 'id')));
        $checkSuspend = true;
   		if($checkSuspend == true && !empty($user)){
			$this->loadModel('Report');
			if($this->Report->checkNewReports($user['User']['id'])){
				$this->set('newReport', true);
			} else {
				$this->set('newReport', false);
			}
			
			$action = $GLOBALS['Dispatcher']->here;
			
			$actions = explode('/', $action);
			$this->set('actions', $actions);
			unset($action);
			//debug($actions);
			$found = false;
			foreach($actions as $action):
				if($action == 'suspended'/* || $action == 'forums'*/){
					$found = true;
				}
			endforeach;
			if(!$found) {
				if($user['User']['suspended'] || $this->account['Account']['suspended'] || $this->Auth->user('suspended')){
		        	$this->redirect(array('controller' => 'users', 'action' => 'suspended'));
		        }
			}
		}
		
        
        /*
         *	Log IPs, I want to eventually remove this and have it only log when they login to the account. 
         *	There was no login on FB, so was difficult to do that...
         */
        $logIps = true;
        if($logIps && !empty($user)){
        	$this->loadModel('IpAddress');
        	$lastIp = $this->IpAddress->find('first', array('conditions' => array('IpAddress.user_id' => $user['User']['id'], 'IpAddress.ip_address' => $_SERVER['REMOTE_ADDR'], 'IpAddress.user_agent' => $_SERVER['HTTP_USER_AGENT'], 'IpAddress.accept' => $_SERVER['HTTP_ACCEPT']), 'order' => array('IpAddress.last_used DESC')));
        	
        	if(!empty($lastIp)){
        		//if($lastIp['IpAddress']['ip_address'] == $_SERVER['REMOTE_ADDR']){
        			$lastIp['IpAddress']['last_used'] = date('c', time());
          			$this->IpAddress->save($lastIp);
        		/*}
        		else {
        			$this->IpAddress->create();
        			$newIp['IpAddress']['user_id'] = $user['User']['id'];
        			$newIp['IpAddress']['last_used'] = date('c', time());
        			$newIp['IpAddress']['ip_address'] = $_SERVER['REMOTE_ADDR'];
        			$this->IpAddress->save($newIp);
        		}*/
        	} else {
        		$this->IpAddress->create();
        		$newIp['IpAddress']['user_id'] = $user['User']['id'];
        		$newIp['IpAddress']['last_used'] = date('c', time());
        		$newIp['IpAddress']['ip_address'] = $_SERVER['REMOTE_ADDR'];
        		$newIp['IpAddress']['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        		$newIp['IpAddress']['accept'] = $_SERVER['HTTP_ACCEPT'];
        		$this->IpAddress->save($newIp);
        	}
        }

        if($this->chkiplist($_SERVER['REMOTE_ADDR'])){
        	$actions = explode('/', $action);
			$this->set('actions', $actions);
			unset($action);
			//debug($actions);
			$found = false;
			foreach($actions as $action):
				if($action == 'suspended'/* || $action == 'forums'*/){
					$found = true;
				}
			endforeach;
			if(!$found) {
		        $this->redirect(array('controller' => 'users', 'action' => 'suspended'));
			}
        }
	}
	
	function isAuthorized(){
		// This was for CometChat... not sure if it's necessary anymore.
		$_SESSION['Auth']['Account']['id'] = $this->Auth->user('id');
		return $this->Auth->user('id');
	}
	
	function beforeFacebookSave(){
		// This is the info we want to save when they register using Facebook.
		$this->Connect->authUser['Account']['username'] = $this->Connect->user('first_name') . " " . $this->Connect->user('last_name');
		$this->Connect->authUser['Account']['email'] = $this->Connect->user('email');
		$this->Connect->authUser['Account']['active'] = 1;
		return true; //Must return true or will not save.
	}
	
	function beforeFacebookLogin($user) {
		// We want to do this stuff if for some reason we're missing info from a facebook user.
		$save = false;
		if(!isset($this->Connect->authUser['Account']['id'])):
			$this->loadModel('Account');
			$account = $this->Account->find('first', array('conditions' => array('Account.email' => $this->Connect->authUser['Account']['email'])));
			if($account):
				$this->Connect->authUser['Account']['id'] = $account['Account']['id'];
				$this->Connect->authUser['Account']['email'] = $account['Account']['email'];
				$this->Connect->authUser['Account']['password'] = $account['Account']['password'];
				$save = true;
			endif;
		endif;
		
		
		if($this->Connect->authUser['Account']['username'] == null){
			$this->Connect->authUser['Account']['username'] = $this->Connect->user('first_name') . " " . $this->Connect->user('last_name');
			$save = true;
		}
		if($this->Connect->authUser['Account']['email'] == null):
			$this->Connect->authUser['Account']['email'] = $this->Connect->user('email');
			$this->Connect->authUser['Account']['active'] = 1;
			$save = true;
		endif;
		if($this->Connect->authUser['Account']['password'] == null):
			$this->Connect->authUser['Account']['password'] = $this->Auth->password(FacebookInfo::randPass());
			$save = true;
		endif;
		if($save == true){
			$this->loadModel('Account');
			$this->Account->save($this->Connect->authUser);
		}

		
	}
	
function chkiplist($ip) {
	# read in the ip address file
	$dir = getcwd();
	$lines = file($dir . "/PH_ipranges.txt");
	# set a variable as false
	$found = false;
	# convert ip address into a number
	$split_it = split("\.",$ip);
	$ip = "1" . sprintf("%03d",$split_it[0]) .
sprintf("%03d",$split_it[1]) . sprintf("%03d",$split_it[2]) .
sprintf("%03d",$split_it[3]);
	# loop through the ip address file
	foreach ($lines as $line) {
		# remove line feeds from the line
		$line = chop($line);
		# replace x with a *
		$line = str_replace("x","*",$line);
		# remove comments
		$line = preg_replace("|[A-Za-z#/]|","",$line);
		# set a maximum and minimum value
		$max = $line;
		$min = $line;
		# replace * with a 3 digit number
		if ( strpos($line,"*",0) <> "" ) {
			$max = str_replace("*","999",$line);
			$min = str_replace("*","000",$line);
		}
		# replace ? with a single digit
		if ( strpos($line,"?",0) <> "" ) {
			$max = str_replace("?","9",$line);
			$min = str_replace("?","0",$line);
		}
		# if the line is invalid go to the next line
		if ( $max == "" ) { continue; };
		# check for a range
		if ( strpos($max," - ",0) <> "" ) {
			$split_it = split(" - ",$max);
			# if the second part does not match an ip address
			if ( !preg_match("|\d{1,3}\.|",$split_it[1]) ) {
				$max = $split_it[0];
			}
			else { 
				$max = $split_it[1];
			};
		}
		if ( strpos($min," - ",0) <> "" ) {
			$split_it = split(" - ",$min);
			$min = $split_it[0];
		}
		# make $max into a number
		$split_it = split("\.",$max);
		for ( $i=0;$i<4;$i++ ) {
			if ( $i == 0 ) { $max = 1; };
			if ( strpos($split_it[$i],"-",0) <> "" ) {
				$another_split = split("-",$split_it[$i]);
				$split_it[$i] = $another_split[1];
			} 
		$max .= sprintf("%03d",$split_it[$i]);
		}
		# make $min into a number
		$split_it = split("\.",$min);
		for ( $i=0;$i<4;$i++ ) {
			if ( $i == 0 ) { $min = 1; };
			if ( strpos($split_it[$i],"-",0) <> "" ) {
				$another_split = split("-",$split_it[$i]);
				$split_it[$i] = $another_split[0];
			} 
		$min .= sprintf("%03d",$split_it[$i]);
		}
		# check for a match
		if ( ($ip <= $max) && ($ip >= $min) ) {
			$found = true;
			break;
		};
	}
	return $found;
} # end function
		
}
?>