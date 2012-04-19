<?php
class Message extends AppModel {    
	var $name = 'Message';
	
	function getAllMessages($userId = null){
		return $this->find('all', array('conditions' => array('Message.user_id' => $userId), 'order' => array('Message.time DESC')));
	}
	
	function checkUnreadMessages($userId = null){
		return $this->find('first', array('conditions' => array('Message.is_read' => 0, 'Message.is_deleted' => 0, 'Message.user_id' => $userId)));
	}
	
	function getAMessage($userId = null, $messageId = null){
		return $this->find('first', array('conditions' => array('Message.id' => $messageId, 'Message.user_id' => $userId)));
	}
	
	function getASentMessage($userId = null, $messageId = null){
		return $this->find('first', array('conditions' => array('Message.id' => $messageId, 'Message.sender_id' => $userId)));
	}
	
	function sendMessageToAll($userId = null, $message = null, $account = null) {
		$User = Classregistry::init('User');
		$fields = "User.id";
		$users = $User->find('all', array('fields' => $fields));
		foreach($users as $user):
			$this->sendMessage($message['Message']['body'], $message['Message']['title'], $user['User']['id'], $userId, null, $account['Account']['facebook_id']);
		endforeach;
	}
	
	function sendMessage($body = null, $title = null, $receiverId = 0, $senderId = 0, $facebook = null, $facebook_id = null){
		$message['user_id'] = $receiverId;
		$message['sender_id'] = $senderId;
		$message['body'] = $body;
		$message['title'] = $title;
		$message['time'] = strtotime(date('r', time()));
		$this->create();
		$this->save($message);
		//if($facebook_id)
			//$facebook->api_client->notifications_send($facebook_id, 'You received a new message on <a href="http://apps.facebook.com/ctagame">Call to Arms</a>. <a href="http://apps.facebook.com/ctagame/messages/">Check your mail!</a>', 'app_to_user');
	}
	
	function sendRules() {
		$rules = "[b]Player accounts:[/b]
1 account per person and household.
Up to 2 additional accounts can be requested per household. This may be done automatically via an (as of yet produced) in-game household manager.
A player is considered to be playing an account even if he/she is not the one actively using the computer to do things if: 
Player A is directing Player B to do exactly what Player A says.
Interaction between players sharing an internet connection is prohibited for 72 hours after connection sharing has ended. This includes spell casting, resource trading and attacks. 
It is not allowed to use a third party to get around this.
It is not allowed to use the trading system to get around this.
It is not allowed to have Player A login to Player B's account at any time, even if in the same household.
[b]Merging/Quitting Accounts:[b]
Passing castles from an account that will be deleted to an account that is still active is not allowed.
It is allowed to transfer resources from a to-be-deleted account to an active account.
However, kingdoms that intend to go inactive are not allowed to be used to feed resources to active accounts---they must be deleted or abandoned.
[b]Feeding[/b]
It is not allowed to consistently feed other kingdoms (regardless of the legitimacy of the users). Feeding can be in the form of:
Land/castles being taken by a player and/or his/her allies.
Resources sent to a player and/or his/her allies.
Beneficial spells cast on a player and/or his/her allies.
Offensive (Truesight included) spells cast on a player's enemies (when the kingdom is used for little else).
[b]Communication:[/b]
Insulting other players and the use of language in an abusive/offensive context is forbidden.
Ruler/Kingdom names found to be illegal, insulting, threatening, abusive, real-life-aggressive, sexual, politically incorrect, religiously fanatic, racist or in any way inappropriate are not allowed.
Advertising moneymaking sites, or posting links to inappropriate content is forbidden.
Phishing or blackmailing players for passwords in any way is forbidden. Spamming other players with constant in-game mails can be seen as abusive and is not allowed.
[b]Bots and scripts:[/b]
Call to Arms should be played with a normal browser only. Bots, browser add-ons and other applications that automate game activities are forbidden.
No programs/scripts are allowed to access game data directly. 
It is allowed to use a script that culls through information that you actively fed (such as copy/pasting or typing) into it.
Call to Arms does not offer for issues related to these external programs.
Call to Arms is not responsible for monitoring the safety and reliability of these external programs and is not liable for any damage they may cause to your computer, your well-being or your dog.
All Greasemonkey (and programs like it) scripts are forbidden.
[b]Miscellaneous:[/b]
Buying/Selling of castles is not allowed.
Anything you write may be used as evidence against you in an appeal. Even if you later say that you were joking.
Encouraging or tricking others into breaking the rules is strictly prohibited.
If you experience a bug or discover an abusable flaw* you must immediately report it on the bug report forum or a PM to Josh on the public forums. Failing to do so may result in a punishment. Continued exploitation before a fix is implemented may result in punishment.
Payments will not be refunded to players who are banned or suspended for breaking our rules.
[b]Punishments:[/b]
On a good day -- and depending on the infractions -- your account or actions you made will be edited.
On a bad day your account will be deleted.
Repeat offenders will be banned.
I reserve these rights and will use them at my sole discretion, regardless of violation status of these exact rules.

* An abusable flaw is something that gives your kingdom an extraordinary advantage over others. Such things include, but are not limited to: an abnormal amount of protection, an abnormal amount of retals, an abnormal spell success rate and abnormal unit losses or kills in battles.";
		
	}
}
	
?>