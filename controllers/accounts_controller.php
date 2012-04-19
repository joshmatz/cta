<?php 
class AccountsController extends AppController {
	var $name = 'Accounts';
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('register', 'logout');
		
	}

	function index() {
		
	}
	
	function profile($id) {
		$this->set('account', $this->Account->find('first', array('conditions' => array('Account.id' => $id))));
		
		$this->loadModel('Game');
		$ctagames = $this->Game->find('all', array('conditions' => array('Game.category' => 'CTA')));
		$ctagames = Set::combine($ctagames, '{n}.Game.id', '{n}.Game');
		$this->set('ctagames', $ctagames);
		
		$this->loadModel('RegisteredGame');
		$this->set('registeredGames', $this->RegisteredGame->find('all', array('conditions' => array('RegisteredGame.account_id' => $id))));

	}
	
	function register() {
		if($this->Auth->user()){
			$this->Session->setFlash('You have already registered an account!');
			$this->redirect('/play');
		}
		if(!empty($this->data)) :
	    	// make sure passwords match
	    	// hash second because first should automagically be hashed
	    	if($this->data['Account']['bot_check'] == 11):
		        if ($this->data['Account']['password'] == $this->Auth->password($this->data['Account']['password2'])):
					if($this->Account->save($this->data)):
			            $this->__sendActivationEmail($this->Account->getLastInsertID());
			            $this->Auth->login($this->data);
			            $this->Session->setFlash('You\'ve created your new Provoke One account! An email has been sent to your email address to activate the account.');
			            $this->redirect(array('controller' => 'users', 'action' => 'create'));
					endif; // data saved
				else:
				    $this->Session->setFlash('Your passwords did not match!');
				endif; // passwords match
			else: // botcheck
				$this->Session->setFlash('You did not answer the math problem correctly.');
			endif;
		endif; // data is not empty
		
		$this->data['Account']['password'] = "";
		$this->data['Account']['password2'] = "";
	}	
	
	/*
	 * Because we're not redirecting, we can also keep track of login attempts/successful logins using something like this:
	 
 <?php
function login() { 
	if( !(empty($this->data)) && $this->Auth->User() ){
		$this->Account->id = $this->Auth->User('id');
		$this->Account->saveField('last_login', date('Y-m-d H:i:s') );
		$this->redirect($this->Auth->redirect());
	}
}
?>

	 */
	function login() {
		//-- code inside this function will execute only when autoRedirect was set to false (i.e. in a beforeFilter).
		if ($this->Auth->User()):
			if (!empty($this->data['Account']['remember_me'])):
				$cookie = array();
				$cookie['email'] = $this->data['Account']['email'];
				$cookie['password'] = $this->data['Account']['password'];
				$this->Cookie->write('Auth.Account', $cookie, true, '+2 weeks');
				unset($this->data['Account']['remember_me']);
			endif;
			$this->redirect($this->Auth->redirect());
		endif;
		if (empty($this->data)):
			$cookie = $this->Cookie->read('Auth.Account');
			if (!is_null($cookie)):
				if ($this->Auth->login($cookie)):
					//  Clear auth message, just in case we use it.
					$this->Session->delete('Message.auth');
					$this->redirect($this->Auth->redirect());
				endif;
			endif;
		endif;
	}
	
	function logout() {
		$this->redirect($this->Auth->logout());
	} 
	
	function myaccount() {
		$this->set('account', $this->Auth->user());
		
		$this->loadModel('Game');
		$ctagames = $this->Game->find('all', array('conditions' => array('Game.category' => 'CTA')));
		$ctagames = Set::combine($ctagames, '{n}.Game.id', '{n}.Game');
		$this->set('ctagames', $ctagames);
		
		$this->loadModel('RegisteredGame');
		$this->set('registeredGames', $this->RegisteredGame->find('all', array('conditions' => array('RegisteredGame.account_id' => $this->Auth->user('id')))));
	}
	
	function changeEmail() {
		if(!empty($this->data)) :
	    	$account = $this->Account->find('first', array('conditions' => array('Account.id' => $this->Auth->User('id'))));
			if($account['Account']['password'] == $this->Auth->password($this->data['Account']['password2'])):
				$email = $this->Account->find('first', array('conditions' => array('Account.email' => $this->data['Account']['email']), 'fields' => array('Account.email')));
				if(empty($email)):
					if($this->data['Account']['email'] == $this->data['Account']['confirm_email']):
						$account['Account']['email_change'] = $this->data['Account']['email'];
						if($this->Account->save($account)):
				            $this->__sendActivationForNewEmail($account['Account']['id']);
				            $this->Session->setFlash('An email has been sent to <span class="email">' . $this->data['Account']['email'] . '</span> to confirm your address. You must click the included link to complete the address change process.');
				            $this->redirect(array('controller' => 'pages', 'action' => 'display'));
						else: 
							$this->Session->setFlash('Something went wrong saving your new email. Please try again or contact the administrator.');
						endif;
					else:
						$this->Session->setFlash('Your email addresses did not match.');
					endif;
				else:
					$this->Session->setFlash('You or someone else is already using that email!');
				endif;
			else:
			    $this->Session->setFlash('You did not enter the correct password.');
			endif;
		endif;
		$this->data['Account']['password2'] = "";
	}
	
	function changePassword() {
		if($this->data['Account']):
			$oldPass = $this->Account->find('first', array('conditions' => array('Account.id' => $this->Auth->User('id')), 'fields' => array('Account.password', 'Account.id')));
			if($oldPass['Account']['password'] == $this->Auth->password($this->data['Account']['password'])):
				if ($this->data['Account']['password2'] == $this->data['Account']['password3']):
					$oldPass['Account']['password'] = $this->Auth->password($this->data['Account']['password2']);
					if($this->Account->save($oldPass)):
						$this->Session->setFlash('New password saved successfully!');
					else:
						$this->Session->setFlash('There was a problem saving the new password');
					endif;
				else:
					$this->Session->setFlash('The new passwords did not match!');
				endif;
				
			else:
				$this->Session->setFlash('You did not type the correct password.');
				
			endif;
		endif;
		unset($this->data['Account']);
	}
	

	/**
	 * Activates a Account account from an incoming link
	 *
	 *  @param Int $Account_id Account.id to activate
	 *  @param String $in_hash Incoming Activation Hash from the email
	*/
	function activate($account_id = null, $in_hash = null) {
	        $this->Account->id = $account_id;
	        if ($this->Account->exists() && ($in_hash == $this->Account->getActivationHash()))
	        {
	                // Update the active flag in the database
	                $this->Account->saveField('active', 1);
	                
	                // Let the Account know they can now log in!
	                $this->Session->setFlash('Your account has been activated, please log in below if you are not already!');
	                $this->redirect('login');
	        }
	        
	        // Activation failed, render '/views/Account/activate.ctp' which should tell the Account.
	}
	
	function resendactivation($account_id = null){
		$account = $this->Account->find('first', array('conditions' => array('Account.id' => $account_id)));
		if($account):
			if(!$account['Account']['active']):
				$this->__sendActivationEmail($account_id);
				// Let the Account know they can now log in!
				$this->Session->setFlash('A new activation email has been sent to your account!');
			else:
				$this->Session->setFlash('Your account is already active!');
			endif;
		else:
			$this->Session->setFlash('That account could not be found.');
		endif;
		$this->redirect('login');
	}
	
	function resendchangeactivation($account_id = null){
		$account = $this->Account->find('first', array('conditions' => array('Account.id' => $account_id)));
		if($account):
			if(!empty($account['Account']['email_change'])):
				if(!$account['Account']['active']):
					$this->__sendActivationForNewEmail($account_id);
					// Let the Account know they can now log in!
					$this->Session->setFlash('A new activation email has been sent to your account!');
				else:
					$this->Session->setFlash('Your account is already active!');
				endif;
			else:
				$this->Session->setFlash('There is no email to confirm. Please try again.');
			endif;
		else:
			$this->Session->setFlash('That account could not be found.');
		endif;
		$this->redirect('login');
	}

	
	/**
     * Send out an activation email to the Account.id specified by $Account_id
     *  @param Int $Account_id Account to send activation email to
     *  @return Boolean indicates success
    */ 
    function __sendActivationEmail($account_id) {
            $account = $this->Account->find(array('Account.id' => $account_id), array('Account.email'), null, false);
            if ($account === false) {
                    debug(__METHOD__." failed to retrieve Account data for Account.id: {$account_id}");
                    return false;
            }

            // Set data for the "view" of the Email
            $this->set('activate_url', 'http://' . env('SERVER_NAME') . '/accounts/activate/' . $account_id . '/' . $this->Account->getActivationHash());
            $this->set('account', $this->data['Account']['email']);
            
            $this->Email->to = $account['Account']['email'];
            $this->Email->subject = env('SERVER_NAME') . ' - Please confirm your email address';
            $this->Email->from = 'noreply@' . env('SERVER_NAME');
            $this->Email->template = 'account_confirm';
            $this->Email->sendAs = 'text';   // you probably want to use both :)    
            return $this->Email->send();
    }
    
    /**
	 * Activates a Account account from an incoming link
	 *
	 *  @param Int $Account_id Account.id to activate
	 *  @param String $in_hash Incoming Activation Hash from the email
	*/
	function activateNewEmail($account_id = null, $in_hash = null) {
	       	$account = $this->Account->find('first', array('conditions' => array('Account.id' => $account_id)));
	        if ($this->Account->exists() && ($in_hash == $this->Account->getActivationHash()) && $account['Account']['email_change'])
	        {
	               	// Update email and delete email_change field
	               	$account['Account']['email'] = $account['Account']['email_change'];
	               	$account['Account']['email_change'] = "";
	                $this->Account->save($account);
	                $this->Auth->login($account);
	                
	                // Let the Account know they can now log in!
	                $this->Session->setFlash('Your new email is ready to be used!');
	                $this->redirect('login');
	        }
	        
	        // Activation failed, render '/views/Account/activate.ctp' which should tell the Account.
	}

    
    /**
     * Send out an activation email to the Account.id specified by $Account_id
     *  @param Int $Account_id Account to send activation email to
     *  @return Boolean indicates success
    */ 
    function __sendActivationForNewEmail($account_id) {
            $account = $this->Account->find(array('Account.id' => $account_id), array('Account.email_change'), null, false);
            if ($account === false) {
                    debug(__METHOD__." failed to retrieve Account data for Account.id: {$account_id}");
                    return false;
            }

            // Set data for the "view" of the Email
            $this->set('activate_url', 'http://' . env('SERVER_NAME') . '/accounts/activateNewEmail/' . $account_id . '/' . $this->Account->getActivationHash());
            $this->set('account', $this->data['Account']['email']);
            
            $this->Email->to = $account['Account']['email_change'];
            $this->Email->subject = env('SERVER_NAME') . ' - Please confirm your email address';
            $this->Email->from = 'noreply@' . env('SERVER_NAME');
            $this->Email->template = 'account_confirm';
            $this->Email->sendAs = 'text';   // you probably want to use both :)    
            return $this->Email->send();
    }
}

?>