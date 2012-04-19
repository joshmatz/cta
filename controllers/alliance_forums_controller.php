<?php
class AllianceForumsController extends AppController {    
	var $name = 'AllianceForums';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	var $uses = array();
	
	var $paginate = array(
		'AllianceTopic' => array(
			'limit' => 15,
			'order' => array(
				'AllianceTopic.new_post_date' => 'desc'
			)    
		),
		'AlliancePost' => array(
			'limit' => 15,
			'order' => array(
				'AlliancePost.date' => 'asc'
			)  
		)
	);
	
	function beforeFilter() {  
		parent::beforeFilter();
		$this->loadModel('User');
		$this->loadModel('AllianceTopic');
		$this->loadModel('AlliancePost');
		$this->User->recursive = 2;
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
		
		if($this->user['User']['alliance_id'] != 0) {
			$this->menu = array(
				array(
					'name' => 'Overview',
					'link' => '/alliances'
				),
				array(
					'name' => 'Profile',
					'link' => '/alliances/profile'
				),
				array(
					'name' => 'Members',
					'link' => '/alliances/members'
				),
				array(
					'name' => 'Diplomacy',
					'link' => '/alliances/diplomacy'
				),
				array(
					'name' => 'Forum',
					'link' => '/allianceForums'
				)
			);
			if($this->user['User']['alliance_rank'] != 'admin'){
				$this->menu[] = array('name' => 'Leave Alliance', 'link' => '/alliances/leave');
			} else {
				$this->menu[] = array('name' => 'Sent Invitations', 'link' => '/alliances/invite');
				$this->menu[] = array('name' => 'Alliance Settings', 'link' => '/alliances/settings');
				$this->menu[] = array('name' => 'Disband Alliance', 'link' => '/alliances/disband');
			}
		} else {
			$this->menu = array(
				array(
					'name' => 'Create or Join an Alliance',
					'link' => '/alliances'
				),
			);
		}
		$this->set('selected', 'Forum');
		$this->set('menu', $this->menu);
		$this->loadModel('Alliance');
		$alliance = $this->Alliance->getAlliance($this->user['User']['alliance_id']);
	    $this->set('alliance', $alliance);
	    if(empty($alliance)){
	    	$this->redirect('/alliances');
	    }
    }  
    
    function index(){
    	$topics = $this->paginate('AllianceTopic', array('AllianceTopic.alliance_id' => $this->user['User']['alliance_id']));
    	
    	foreach($topics as &$topic):
    		$topic['AllianceTopic']['author'] = $this->User->getName($topic['AllianceTopic']['user_id']);
    	endforeach;
    	
    	$this->set('topics', $topics);
    	$this->set('user', $this->user);
    }
    
    function post() {
    	
    	
    	$this->set('user', $this->user);
    }
    
	function postTopic() {
		if($this->data['AlliancePost']['body'] == NULL) {
			$this->Session->setFlash('You did not type anything in the body of the Post.');
			$this->redirect(array('action' => 'newTopic'));
		}
		if($this->data['AllianceTopic']['title'] == NULL) {
			$this->Session->setFlash('You did not type anything for the title of the Topic.');
			$this->redirect(array('action' => 'newTopic'));
		}
		$this->data['AllianceTopic']['alliance_id'] = $this->user['User']['alliance_id'];
		$topic = $this->AllianceTopic->createTopic($this->data, $this->user['User']['id']);

		$this->AlliancePost->createPost($topic, $this->user['User']['id']);
		$this->Session->setFlash('Your Topic was succesfully posted.');
		$this->redirect(array('action' => 'index'));
	}
	
	function postPost() {
		if($this->data['AlliancePost']['body'] == NULL) {
			$this->Session->setFlash('You did not type anything in the body of the Post.');
			$this->redirect(array('action' => 'newTopic'));
		}
		$topic = $this->AllianceTopic->find('first', array('conditions' => array('AllianceTopic.id' => $this->data['AlliancePost']['topic_id'])));
		$this->AllianceTopic->increaseCount($topic);
		$this->data['AllianceTopic'] = $topic['AllianceTopic'];
		unset($this->data['AllianceTopic']['date']);
		$this->AlliancePost->createPost($this->data, $this->user['User']['id']);	
		$this->Session->setFlash('Your reply was succesfully posted.');
		$this->redirect(array('action' => 'index'));
	}
	
	function viewTopic($topicId = null) {
    	$topic = $this->AllianceTopic->find('first', array('conditions' => array('AllianceTopic.alliance_id' => $this->user['User']['alliance_id'], 'AllianceTopic.id' => $topicId)));
    	
    	if($topic['AllianceTopic']['alliance_id'] == $this->user['User']['alliance_id']){
			$posts = $this->paginate('AlliancePost', array('AlliancePost.topic_id' => $topicId));

			foreach($posts as &$post):
	    		$post['AlliancePost']['author'] = $this->User->getName($post['AlliancePost']['user_id']);
	    		$post['AlliancePost']['body'] = $this->Bbcode->sanitize($post['AlliancePost']['body']);
	    	endforeach;
	    	
	    	$this->set('topicId', $topicId);
	    	$this->set('posts', $posts);
    	}
    	else {
    		$this->Session->setFlash('You do not have access to that topic.');
			$this->redirect(array('action' => 'index'));
    	}
    	$this->set('user', $this->user);
	}
	
}