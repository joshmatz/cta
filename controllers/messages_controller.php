<?php
class MessagesController extends AppController {    
	var $name = 'Messages';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	var $uses = array('Message', 'User');
	
	
	var $paginate = array(
		'limit' => 15,
		'order' => array(
			'Message.time' => 'desc'
		)    
	);
	
	/********
	 * Execute this before every action
	 */
	function beforeFilter() {  
		parent::beforeFilter();
		//debug($this->components);
		$this->Message->recursive = 2;
        //$this->facebook_id = $this->facebookId;
        parent::beforeFilter();
        $this->user = Classregistry::init('User')->find('first', array('conditions' => array('User.account_id' => $this->account['Account']['id']))); 
		//$id = preg_replace("/[^A-Za-z0-9-]/","",$this->facebook->api_client->session_key);
		//$this->Session->id($id); 
		$this->set('newMessages', $this->Message->checkUnreadMessages($this->user['User']['id']));
		$this->set('parms', $this->parms);
		
		$menu = array(
			array(
				'name' => 'Mailbox',
				'link' => '/messages'
			),
			array(
				'name' => 'New Message',
				'link' => '/messages/newMessage'
			),
			array(
				'name' => 'Sent Messages',
				'link' => '/messages/sentIndex'
			),
		);
		$this->set('menu', $menu);
    }  
	
	function index() {        
		//$messages = $this->Message->getAllMessages($this->user['User']['id']);
		
		$messages = $this->paginate('Message', array('Message.user_id' => $this->user['User']['id'], 'Message.is_deleted' => 0));
		foreach($messages as &$message):
			$sender = Classregistry::init('User')->getName($message['Message']['sender_id']);
			$message['Message']['sender'] = $sender;
		endforeach;
		$this->set('messages', $messages);
		$this->set('user', $this->user);
		$this->set('selected', 'Mailbox');
	}
	
	function messageList() {
		$this->layout = 'ajax';
		$messages = $this->paginate('Message', array('Message.user_id' => $this->user['User']['id'], 'Message.is_deleted' => 0));
		foreach($messages as &$message):
			$sender = Classregistry::init('User')->getName($message['Message']['sender_id']);
			$message['Message']['sender'] = $sender;
		endforeach;
		$this->set('messages', $messages);
		$this->set('user', $this->user);
	}
	
	function sentIndex() {        
		//$messages = $this->Message->getAllMessages($this->user['User']['id']);
		
		$messages = $this->paginate('Message', array('Message.sender_id' => $this->user['User']['id']));
		foreach($messages as &$message):
			$sender = Classregistry::init('User')->getName($message['Message']['user_id']);
			$message['Message']['user'] = $sender;
		endforeach;
		$this->set('messages', $messages);
		$this->set('user', $this->user);
		$this->set('selected', 'Sent Messages');
	}
	
	function sentList() {
		$this->layout = 'ajax';
		$messages = $this->paginate('Message', array('Message.sender_id' => $this->user['User']['id']));
		foreach($messages as &$message):
			$sender = Classregistry::init('User')->getName($message['Message']['user_id']);
			$message['Message']['user'] = $sender;
		endforeach;
		$this->set('messages', $messages);
		$this->set('user', $this->user);
	}
	
	function sentView($id = null) {
		$message = $this->Message->getASentMessage($this->user['User']['id'], $id);
		$fields = "User.ruler,  User.id";
		$message['Message']['body'] = $this->Bbcode->sanitize($message['Message']['body']);
		$receiver = Classregistry::init('User')->find('first', array('conditions' =>  array('User.id' => $message['Message']['user_id']), 'fields' => $fields));
		$message['Message']['sender'] = '<a href="/users/profile/' . $receiver['User']['id'] . '">' . $receiver['User']['ruler']  . "</a>";
		$this->set('sender', $receiver['User']['ruler']);
		$this->set('message', $message);	
		$this->set('user', $this->user);
		$this->set('selected', 'Sent Messages');
	}
	
	function view($id = null) {
		$message = $this->Message->getAMessage($this->user['User']['id'], $id);
		$message['Message']['is_read'] = 1;
		$this->Message->id = $message['Message']['id'];
		$this->Message->save($message);
		$message['Message']['body'] = $this->Bbcode->sanitize($message['Message']['body']);
		$fields = "User.ruler, User.id";
		$sender = Classregistry::init('User')->find('first', array('conditions' =>  array('User.id' => $message['Message']['sender_id']), 'fields' => $fields));
		$message['Message']['sender'] = '<a href="/users/profile/' . $sender['User']['id'] . '">' . $sender['User']['ruler'] . "</a>";
		$this->set('sender', $sender['User']['ruler']);
		$this->set('message', $message);	
		$this->set('user', $this->user);
		$this->set('selected', 'Mailbox');
	}
	
	function reply($receiver = null, $id = null) {
		$this->loadModel('User');
		$message = $this->Message->getAMessage($this->user['User']['id'], $id);
		if(empty($message['Message']['body'])){
			$profile = $this->User->find('first', array('conditions' => array('User.ruler' => $receiver), 'fields' => 'User.id'));
			$message['Message']['sender_id'] = $profile['User']['id'];
		}
		
		$fields = "User.ruler, User.id";
		$sender = $this->User->find('first', array('conditions' =>  array('User.id' => $message['Message']['sender_id']), 'fields' => $fields));
		$message['Message']['sender'] = '<a href="/users/profile/' . $sender['User']['id'] . '">' . $sender['User']['ruler'] . "</a>";
		
		$this->set('message', $message);
		$this->set('receiver', $receiver);
		$this->set('user', $this->user);
	}
	
	function newMessage() {
		$this->set('user', $this->user);
		$this->set('selected', 'New Message');
	}
	
	function sendMessage() {

		if($this->data['Message']['body'] == NULL) {
			$this->Session->setFlash('You did not type anything in the body of the message.');
			$this->redirect(array('action' => 'newMessage' . $this->parms));
		}
		if($this->data['Message']['title'] == NULL) {
			$this->Session->setFlash('You did not type anything for the title of the message.');
			$this->redirect(array('action' => 'newMessage' . $this->parms));
		}
		if($this->data['Message']['receiver'] == NULL) {
			$this->Session->setFlash('You did not specify a receiver.');
			$this->redirect(array('action' => 'newMessage' . $this->parms));
		}
		
		// Make sure the receiver exists, and if so, get their id number
		$found = false;
		
		if(strtolower($this->data['Message']['receiver']) == "[alliance]"){
			if($this->user['User']['alliance_id']){
				$this->loadModel('Alliance');
				$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
				$this->data['Message']['body'] = "[b][i]This is a circular message to " . $alliance['Alliance']['name'] . ".[/i][/b]
				
				". $this->data['Message']['body'];
				foreach($alliance['Members'] as $member):
					if($member['User']['id'] != $this->user['User']['id']){
						$this->data['Message']['user_id'] = $member['User']['id'];
						//$this->data['Message']['facebook_id'] = $member['User']['facebook_id'];
						$this->Message->sendMessage($this->data['Message']['body'], $this->data['Message']['title'], $this->data['Message']['user_id'], $this->user['User']['id'], null, null);
					}
				endforeach;
				$this->Session->setFlash('You successfully sent your message to ' . $alliance['Alliance']['name'] . '.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('You cannot send an alliance message when you are not in an alliance.');
				$this->redirect(array('action' => 'newMessage'));
			}
		} elseif(strtolower($this->data['Message']['receiver']) == "[mass]"){
			if($this->account['Account']['role'] == "council" || $this->account['Account']['role'] == "admin") {
				$this->data['Message']['body'] = "[b][i]This is a mass message sent to everyone.[/i][/b]
				
				". $this->data['Message']['body'];
				$this->Message->sendMessageToAll($this->user['User']['id'], $this->data, $this->account);
				$this->Session->setFlash('You successfully sent a message to everyone.');
			}else {
				$this->Session->setFlash('You cannot send mass messages if you are not on the council.');
				$this->redirect(array('action' => 'newMessage'));
			}
		} else {
			$fields = "User.ruler, User.id, User.account_id";
			$users = Classregistry::init('User')->find('all', array('fields' => $fields));
			foreach($users as $user):
				if($user['User']['ruler'] == $this->data['Message']['receiver']) {
					$this->data['Message']['user_id'] = $user['User']['id'];
					//$this->data['Message']['facebook_id'] = $user['User']['facebook_id'];
					$found = true;
					break;
				}
			endforeach;
		}
		
		if($found) {
			$this->Message->sendMessage($this->data['Message']['body'], $this->data['Message']['title'], $this->data['Message']['user_id'], $this->user['User']['id'], $this->Connect, null);
			$this->Session->setFlash('Your message was succesfully sent');
			$this->redirect(array('action' => 'index' . $this->parms));
		}
		else {
			$this->Session->setFlash('That user was not found, please try again.');
			$this->redirect(array('action' => 'newMessage' . $this->parms));
		}
	}
	
	function delete(){
		//debug($this->data['Messages']['id']);
		$i = 0;
		$messages = array();
		foreach($this->data['Messages']['id'] as $key => $value) {
			if($value != 0) {
				$messages[$i]['Message']['id'] = $value;
				$messages[$i]['Message']['is_deleted'] = 1;
				$i++;
				//$this->Message->delete($value);
			}
		}
		//debug($messages);
		$this->Message->saveAll($messages);
		$this->redirect(array('action' => 'index' . $this->parms));	
	}
	
	/*
	function sendAll(){
		$this->Message->sendMessageToAll();
		$this->redirect(array('action' => 'index'));
	}
	*/
	
	
	function autoComplete() {
		$this->set('receivers', Classregistry::init('User')->find('all', array('conditions' => array('User.ruler LIKE' => $this->params['url']['q'] . '%'), 'fields' => array('ruler'), 'limit' => 20)));
		$this->layout = 'ajax';
	}
	
}
?>