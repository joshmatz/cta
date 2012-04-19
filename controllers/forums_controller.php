<?php
class ForumsController extends AppController {     
	var $name = 'Forums';
	var $uses = array('Forum', 'Topic', 'Post', 'TopicTrack', 'ForumTrack', 'Account', 'User');
	var $layout = 'forum';
	var $paginate = array(
		'Forum' => array(
			'limit' => 27,
			'order' => array(
				'Forum.id' => 'asc'
			)     
		),
		'Topic' => array(
			'limit' => 27,
			'order' => array(
				'Topic.last_post_time' => 'desc'
			)
		),
		'Post' => array(
			'limit' => 27,
			'order' => array(
				'Post.time' => 'asc'
			)  
		)
	);
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index', 'viewTopic', 'viewForum');
		$this->menu = array(
			array(
				'name' => 'View Forum List',
				'link' => '/admin/forums'
			),
			array(
				'name' => 'Add Forum',
				'link' => '/admin/forums/addForum'
			)
		);
		$user = $this->User->find('first', array('conditions' => array('User.account_id' => $this->Auth->user('id')), 'fields' => array('User.id', 'User.ruler', 'User.score', 'User.alliance_id')));
		if($user['User']['alliance_id']):
			$this->loadModel('Alliance');
			$user['details'] = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $user['User']['alliance_id']), 'fields' => array('Alliance.short_name')));
		endif;
		$this->set('user', $user);
		//$this->set('menu', $this->menu);
	}
	
	function admin_index() {
		$this->set('forums', $this->Forum->find('all'));
	}
	
	function admin_addForum(){
		if(!empty($this->data)) :
			if($this->Forum->save($this->data)):
				$this->Session->setFlash('New forum saved successfully!', 'default', array('class'=>'info-box'));
				$this->redirect(array('action' => 'index'));
			else:
				$this->Session->setFlash('There was a problem saving your forum!', 'default', array('class'=>'warning-box'));
			endif;
		endif;
	}
	
	function admin_deleteForum() {
		
	}
	
	function editPost($id = null, $topicId = null) {
		if($this->data):
			$post = $this->Post->find('first', array('conditions' => array('Post.id' => $id)));
			if($this->Auth->user('role') == "admin" || $this->Auth->user('role') == "council" || $post['Post']['account_id'] == $this->Auth->user('id')):
				$post['Post']['text'] = $this->data['Post']['text'];
				$post['Post']['edit_reason'] = $this->data['Post']['edit_reason'];
				$post['Post']['edit_count']++;
				$post['Post']['edit_time'] = date("Y-m-d H:i:s", time());
				$post['Post']['edit_account_id'] = $this->Auth->user('id');
				$this->Post->save($post);
				$this->Session->setFlash('Post edited successfully.', 'default', array('class'=>'info-box'));
				
				$this->redirect(array('action' => 'viewTopic', $post['Post']['topic_id'], 'page:' . max(1, ceil($this->Post->find('count', array('conditions' => array('Post.topic_id' => $post['Post']['topic_id']))) / 27))));
			endif;
		else:
			$this->data = $this->Post->find('first', array('conditions' => array('Post.id' => $id)));
		endif;
		$this->set('postId', $id);
	}
	
	function deleteTopic($topicId = null){
		if($this->Auth->user('role') == "admin" || $this->Auth->user('role') == "council"):
			$topic = $this->Topic->find('first', array('conditions' => array('Topic.id' => $topicId), 'fields' => array('Topic.id', 'Topic.forum_id')));
			if($topic):
				$this->Topic->delete($topicId);
				$this->Post->deleteAll(array('Post.topic_id' => $topicId));
				$this->TopicTrack->deleteAll(array('TopicTrack.topic_id' => $topicId));
				$this->Session->setFlash('Topic deleted successfully.', 'default', array('class'=>'info-box'));
				$this->redirect(array('action' => 'viewForum', $topic['Topic']['forum_id']));
			else:
				$this->Session->setFlash('There was a problem finding the topic to delete.', 'default', array('class'=>'warning-box'));
			endif;
		else:
			$this->Session->setFlash('You do not have necessary permissions.', 'default', array('class'=>'warning-box'));
		endif;
		$this->redirect(array('action' => 'index'));
	}
	
	function deletePost($id = null, $topicId = null) {
		if($this->Auth->user('role') == "admin" || $this->Auth->user('role') == "council" && $topicId):
			$topic = $this->Topic->find('first', array('conditions' => array('Topic.id' => $topicId), 'fields' => array('Topic.id', 'Topic.replies', 'Topic.forum_id')));
			if($topic['Topic']['replies']):
				$this->Post->id = $id;
				$this->Post->saveField('is_deleted', 1);
				$this->Session->setFlash('Post deleted successfully.', 'default', array('class'=>'info-box'));
				if($topicId): 
					$this->redirect(array('action' => 'viewTopic', $topicId));
				endif;
			else:
				$this->Post->delete($id);
				$this->Topic->delete($topicId);
				$this->TopicTrack->deleteAll(array('TopicTrack.topic_id' => $topicId));
				$this->Session->setFlash('Post and Topic deleted successfully.', 'default', array('class'=>'info-box'));
				if($topicId): 
					$this->redirect(array('action' => 'viewForum', $topic['Topic']['forum_id']));
				endif;
			endif;
		else:
			$this->Session->setFlash('You do not have necessary permissions.', 'default', array('class'=>'warning-box'));
		endif;
		$this->redirect(array('action' => 'index'));
	}
	
	function lockTopic($topicId = null, $forumId = null) {
		if($this->Auth->user('role') == "admin" || $this->Auth->user('role') == "council") :
			$topic = $this->Topic->find('first', array('conditions' => array('Topic.id' => $topicId), 'fields' => array('Topic.locked')));
			$this->Topic->id = $topicId;
			if($topic['Topic']['locked'] == 0):
				$this->Topic->saveField('locked', 1);
				$this->Session->setFlash('Topic locked successfully.', 'default', array('class'=>'info-box'));
			else:
				$this->Topic->saveField('locked', 0);
				$this->Session->setFlash('Topic unlocked successfully.', 'default', array('class'=>'info-box'));
			endif;
		else:
			$this->Session->setFlash('You do not have necessary permissions.', 'default', array('class'=>'warning-box'));
		endif;
		$this->redirect(array('action' => 'viewTopic', $topicId));
	}
	
	function preview($parser = '') { 
        $this->layout = 'ajax'; 
        $this->set('parser', $parser);         
        $this->set('content', $this->data); 
    }
	
	function index() {
		$this->Forum->bindModel(array(
			'hasOne'=>array(
				'Post' => array(
					'conditions'    => array('Post.id = Forum.last_post_id'),
					'foreignKey'=>false,
				),
				'ForumTrack'=>array(
	                'foreignKey'=>false,
	                'conditions'=> array(
	                	'ForumTrack.account_id' => $this->Auth->user('id'),
	                	'ForumTrack.forum_id = Forum.id'
	                )
				)
			)
		));

		$forums = $this->Forum->find('all');
		$unread = 0;
		foreach($forums as &$forum):
			$forum['Forum']['is_read'] = 0;
			if(!empty($forum['ForumTrack']['date'])):
				if($forum['Forum']['last_post_time'] < $forum['ForumTrack']['date']):
					$forum['Forum']['is_read'] = 1;
				endif;
			elseif($forum['Forum']['last_post_time'] < $this->Auth->user('last_forum_mark') || $this->Auth->user('last_forum_mark') == "0000-00-00 00:00:00"):
				$forum['Forum']['is_read'] = 1;
			endif;
			
			if($forum['Forum']['is_read'] == 0):
				$unread++;
			endif;
			$forum['Author'] = $this->Account->find('first', array('conditions' => array('Account.id' => $forum['Post']['account_id']), 'fields' => array('Account.id, Account.username')));
		endforeach;
		if($unread == 0):
			$this->ForumTrack->deleteAll(array('ForumTrack.account_id' => $this->Auth->user('id')));
			$this->Account->id = $this->account['Account']['id'];
			$this->Account->saveField('last_forum_mark', date("Y-m-d H:i:s", time()));
			$this->_refreshAuth();
		endif;
		$this->set('forums', $forums);
	}
	
	function viewForum($id = null) {
		if($id):
			// Bind models so data is fetched in 1 query.
			$this->Forum->bindModel(array(
				'hasOne'=>array(
					'Post' => array(
						'conditions'    => array('Post.id = Forum.last_post_id'),
						'foreignKey'	=>false,
					),
					'ForumTrack'=>array(
						'foreignKey'=>false,
						'conditions'=> array(
							'ForumTrack.account_id' => $this->Auth->user('id'),
							'ForumTrack.forum_id = Forum.id'
						)
					)
				)
			));
			$this->Topic->bindModel(array(
				'hasOne'=>array(
					'TopicTrack'=>array(
		                'conditions'=> array(
		                	'TopicTrack.account_id' => $this->Auth->user('id'), 
		                	'TopicTrack.forum_id = Topic.forum_id',
		                	'TopicTrack.topic_id = Topic.id'
		                ),
		                'foreignKey'=>false
					),
					'ForumTrack'=>array(
		                'conditions'=> array(
		                	'ForumTrack.account_id' => $this->Auth->user('id'), 
		                	'ForumTrack.forum_id = Topic.forum_id'
		                ),
		                'foreignKey'=>false
					)
				),
				//'hasMany' => array(
				//	'Post' => array(
		       //         'conditions' => array('Post.topic_id = Topic.id'),
		       //         'foreignKey' => 'topic_id',
		       //         'order' => 'Post.time ASC',
		        //        'limit' => '1'
				//	)
				//)
		    ), false);
		    
		    // Not sure why I'm finding the forum when I find it with the bindModel()
		    // Perhaps it's just easier to access this way...
			$forum = $this->Forum->find('first', array('conditions' => array('Forum.id' => $id)));
			
			$topics = $this->paginate('Topic', array('Topic.forum_id' => $id));
			
			// Increase unread count when topics aren't read. 
			// If unread stays 0, we'll update ForumTrack
			$unread = 0;
			
			// Mark Topics read and find out if need to update ForumTrack
			foreach($topics as &$topic):
				$topic['Topic']['is_read'] = 0;
				
				// Check TopicTrack
				if(!empty($topic['TopicTrack']['date'])): 
					if($topic['Topic']['last_post_time'] < $topic['TopicTrack']['date']):
						$topic['Topic']['is_read'] = 1;
					endif;
				elseif(!empty($topic['ForumTrack']['date'])):
					if($topic['Topic']['last_post_time'] < $topic['ForumTrack']['date']):
						$topic['Topic']['is_read'] = 1;
					endif;
				elseif($topic['Topic']['last_post_time'] < $this->Auth->user('last_forum_mark')):
						$topic['Topic']['is_read'] = 1;
				endif;
				
				// Increase unread count if necessary. 
				// $unread is used to update Trackers if needed
				if($topic['Topic']['is_read'] == 0):
					$unread++;
				endif;
				
				// Give topic two authors, one to show who started topic
				// One to show who last posted.
				$topic['FirstAuthor'] = $this->Account->find('first', array('conditions' => array('Account.id' => $topic['Topic']['account_id']), 'fields' => array('Account.id, Account.username')));
				$topic['LastAuthor'] = $this->Account->find('first', array('conditions' => array('Account.id' => $topic['Topic']['last_account_id']), 'fields' => array('Account.id, Account.username')));
			endforeach;
			
			// Let the view have some fun...
			$this->set('topics', $topics);
			$this->set('forum', $forum);
			
			// Update trackers if necessary
			
			if($unread == 0):
				// Remove TopicTracks and update ForumTrack
				$this->TopicTrack->deleteAll(array('TopicTrack.account_id' => $this->Auth->user('id'), 'TopicTrack.forum_id' => $forum['Forum']['id']));
				$tracker = $this->ForumTrack->find('first', array('conditions' => array('ForumTrack.account_id' => $this->Auth->user('id'), 'ForumTrack.forum_id' => $id)));
				if($tracker):
					$tracker['ForumTrack']['date'] = date("Y-m-d H:i:s", time()); 
					$this->ForumTrack->save($tracker);	
				else:
					$trackerCount = $this->ForumTrack->find('count', array('conditions' => array('ForumTrack.account_id' => $this->Auth->user('id'))));
					if($trackerCount):
						$tracker['ForumTrack']['account_id'] = $this->Auth->user('id');
						$tracker['ForumTrack']['forum_id'] = $forum['Forum']['id'];
						$tracker['ForumTrack']['date'] = date("Y-m-d H:i:s", time()); 
						$this->ForumTrack->save($tracker);	
					else:
						// do nothing if no other forum tracks, update last_forum_mark for user
					endif;
				endif;
				// If no other forum tracks, update last_forum_mark for user.
				if($trackerCount == 0):
					$newestTopic = $this->Topic->find('first', array('order' => 'Topic.last_post_time DESC'));
					if($newestTopic['Topic']['last_post_time'] < $this->Auth->user('last_forum_mark')):
						$this->Account->id = $this->Auth->user('id');
						$this->Account->saveField('last_forum_mark', date("Y-m-d H:i:s", time()));
						$this->_refreshAuth();
					else:
						// do nothing because other topics are likely unread.
					endif;
				endif;
			endif;
		else:
			$this->Session->setFlash('The selected forum does not exist.', 'default', array('class'=>'warning-box'));
			$this->redirect(array('action' => 'index'));
		endif;
	}
	
	function viewTopic($id = null){
		if($id):
			$topic = $this->Topic->find('first', array('conditions' => array('Topic.id' => $id)));
			$forum = $this->Forum->find('first', array('conditions' => array('Forum.id' => $topic['Topic']['forum_id'])));
			$posts = $this->paginate('Post', array('Post.topic_id' => $id));
			foreach($posts as &$post):
				$author = $this->Account->find('first', array('conditions' => array('Account.id' => $post['Post']['account_id']), 'fields' => array('Account.username')));
				$post['Post']['author'] = $author['Account']['username'];
				$post['details'] = $this->User->find('first', array('conditions' => array('User.account_id' => $post['Post']['account_id']), 'fields' => array('User.ruler, User.score, User.alliance_id')));
				if($post['details']['User']['alliance_id']):
					$this->loadModel('Alliance');
					$post['details']['alliance'] = $this->Alliance->find('first', array('conditions' => array('Alliance.id' => $post['details']['User']['alliance_id']), 'fields' => array('Alliance.short_name')));
				endif;
				
				if($post['Post']['edit_count']):
					$editor = $this->Account->find('first', array('conditions' => array('Account.id' => $post['Post']['edit_account_id']), 'fields' => array('Account.username')));
					$post['details']['editor'] = $editor['Account']['username'];
				endif;
			endforeach;
			$this->set('posts', $posts);
			$this->set('topic', $topic);
			$this->set('forum', $forum);
			$topic['Topic']['views']++;
			$this->Topic->id = $topic['Topic']['id'];
			$this->Topic->saveField('views', $topic['Topic']['views']);
			$tracker = $this->TopicTrack->find('first', array('conditions' => array('TopicTrack.account_id' => $this->Auth->user('id'), 'TopicTrack.topic_id' => $id)));
			if($tracker):
				if($tracker['TopicTrack']['date'] > $post['Post']['time']):
					// do nothing
				else:
					$tracker['TopicTrack']['date'] = date("Y-m-d H:i:s", time()); 
					$this->TopicTrack->save($tracker);
				endif;
			else:
				$trackerCount = $this->TopicTrack->find('count', array('conditions' => array('TopicTrack.account_id' => $this->Auth->user('id'))));
				if($trackerCount):
					$tracker['TopicTrack']['account_id'] = $this->Auth->user('id');
					$tracker['TopicTrack']['topic_id'] = $id;
					$tracker['TopicTrack']['forum_id'] = $forum['Forum']['id'];
					$tracker['TopicTrack']['date'] = date("Y-m-d H:i:s", time()); 
					$this->TopicTrack->save($tracker);
				else:
					unset($trackerCount);
					$trackerCount = $this->ForumTrack->find('count', array('conditions' => array('ForumTrack.account_id' => $this->Auth->user('id'))));
					if($trackerCount):
						$tracker['ForumTrack']['account_id'] = $this->Auth->user('id');
						$tracker['ForumTrack']['forum_id'] = $forum['Forum']['id'];
						$tracker['ForumTrack']['date'] = date("Y-m-d H:i:s", time()); 
						$this->ForumTrack->save($tracker);	
					else:
						$newestTopic = $this->Topic->find('first', array('order' => 'Topic.last_post_time DESC'));
						if($newestTopic['Topic']['last_post_time'] < $this->Auth->user('last_forum_mark') || $newestTopic['Topic']['id'] == $id):
							$this->Account->id = $this->Auth->user('id');
							$this->Account->saveField('last_forum_mark', date("Y-m-d H:i:s", time()));
							$this->_refreshAuth();
						else:
							// do nothing
						endif;
					endif;
				endif;
			endif;
			
		else:
			$this->Session->setFlash('The selected topic does not exist.', 'default', array('class'=>'warning-box'));
			$this->redirect(array('action' => 'index'));
		endif;
	}

	function addTopic($currentForum = null) {
		if($this->Auth->user()):
			if(!empty($this->data)) :
				$this->data['Topic']['forum_id'] = $currentForum;
				$this->data['Topic']['account_id'] = $this->Auth->user('id');
				$this->data['Topic']['views'] = 0;
				$this->data['Topic']['replies'] = 0;
				$this->data['Topic']['last_account_id'] = $this->Auth->user('id');
				$this->data['Topic']['time'] = date("Y-m-d H:i:s", time()); 
				$this->data['Topic']['last_post_time'] = date("Y-m-d H:i:s", time()); 
				if($this->Topic->save($this->data)):
					$this->data['Post']['topic_id'] = $this->Topic->getLastInsertID();
					$this->data['Post']['forum_id'] = $currentForum;
					$this->data['Post']['account_id'] = $this->Auth->user('id');
					$this->data['Post']['time'] = date("Y-m-d H:i:s", time()); 
					$this->Post->save($this->data);
					
					$forum = $this->Forum->find('first', array('conditions' => array('Forum.id' => $currentForum)));
					$forum['Forum']['posts']++;
					$forum['Forum']['last_post_id'] = $this->Post->getLastInsertID();
					$forum['Forum']['last_post_time'] = date("Y-m-d H:i:s", time()); 
					$forum['Forum']['topics']++;
					$this->Forum->save($forum);
					
					//unset($trackerCount);
					$trackerCount = $this->TopicTrack->find('count', array('conditions' => array('TopicTrack.account_id' => $this->Auth->user('id'))));
					if($trackerCount):
						$tracker['TopicTrack']['account_id'] = $this->Auth->user('id');
						$tracker['TopicTrack']['topic_id'] = $id;
						$tracker['TopicTrack']['forum_id'] = $forum['Forum']['id'];
						$tracker['TopicTrack']['date'] = date("Y-m-d H:i:s", time() + 1); 
						$this->TopicTrack->save($tracker);
					else:
						unset($trackerCount);
						$trackerCount = $this->ForumTrack->find('count', array('conditions' => array('ForumTrack.account_id' => $this->Auth->user('id'))));
						if($trackerCount):
							$tracker['ForumTrack']['account_id'] = $this->Auth->user('id');
							$tracker['ForumTrack']['forum_id'] = $forum['Forum']['id'];
							$tracker['ForumTrack']['date'] = date("Y-m-d H:i:s", time() + 1); 
							$this->ForumTrack->save($tracker);	
						else:
							$this->Account->id = $this->Auth->user('id');
							$this->Account->saveField('last_forum_mark', date("Y-m-d H:i:s", time() + 1));
							$this->_refreshAuth();
						endif;
					endif;
										
					$this->Session->setFlash('Your topic has been successfully created!', 'default', array('class'=>'success-box'));
					$this->redirect(array('action' => 'viewForum', $this->data['Topic']['forum_id']));
				else:
					$this->Session->setFlash('There was a problem saving your topic!', 'default', array('class'=>'warning-box'));
				endif;
			endif;
		else:
			$this->Session->setFlash('You must be logged in to post on the forum.', 'default', array('class'=>'info-box'));
			$this->redirect(array('action' => 'viewForum', $this->data['Topic']['forum_id']));
		endif;
		$this->set('forum', $currentForum);
	}
	
	function reply($topicId = null) {
		if($this->Auth->user()):
			if(!empty($this->data)) :
				$topic = $this->Topic->find('first', array('conditions' => array('Topic.id' => $topicId)));
				if($topic):
					$this->data['Post']['topic_id'] = $topic['Topic']['id'];
					$this->data['Post']['forum_id'] = $topic['Topic']['forum_id'];
					$this->data['Post']['time'] = date("Y-m-d H:i:s", time()); 
					$this->data['Post']['account_id'] = $this->Auth->user('id');
					if($this->Post->save($this->data)):
						$topic['Topic']['replies']++;
						$topic['Topic']['last_post_time'] = date("Y-m-d H:i:s", time()); 
						$topic['Topic']['last_account_id'] = $this->Auth->user('id');
						$this->Topic->save($topic);
						
						$forum = $this->Forum->find('first', array('conditions' => array('Forum.id' => $topic['Topic']['forum_id'])));
						$forum['Forum']['posts']++;
						$forum['Forum']['last_post_id'] = $this->Post->getLastInsertID();
						$forum['Forum']['last_post_time'] = date("Y-m-d H:i:s", time()); 
						$this->Forum->save($forum);
						
						$this->Session->setFlash('Your reply has been successfully posted!', 'default', array('class'=>'success-box'));
						$this->redirect(array('action' => 'viewTopic', $this->data['Post']['topic_id'], 'page:' . max(1, ceil($topic['Topic']['replies'] / 27))));
					else:
						$this->Session->setFlash('There was a problem saving your topic!', 'default', array('class'=>'warning-box'));
					endif;
				else:
					$this->Session->setFlash('That topic does not exist.', 'default', array('class'=>'warning-box'));
					$this->redirect(array('action' => 'index'));
				endif;
			endif;
		else:
			$this->Session->setFlash('You must be logged in to post on the forum.', 'default', array('class'=>'info-box'));
			$this->redirect(array('action' => 'viewForum', $this->data['Topic']['forum_id']));
		endif;
		$this->set('topic', $topicId);
	}
	
	/**
	 * Refreshes the Auth session
	 * @param string $field
	 * @param string $value
	 * @return void 
	 */
	function _refreshAuth($field = '', $value = '') {
		if (!empty($field) && !empty($value)) { 
			$this->Session->write($this->Auth->sessionKey .'.'. $field, $value);
		} else {
			if (isset($this->User)) {
				$this->Auth->login($this->Account->read(false, $this->Auth->user('id')));
			} else {
				$this->Auth->login(ClassRegistry::init('Acount')->findById($this->Auth->user('id')));
			}
		}
	}
}