<?php
class AllianceTopic extends AppModel {    
	var $name = 'AllianceTopic';
	
	function createTopic($topic = null, $userId = null) {
		$errors = "";
		$topic['AllianceTopic']['user_id'] = $userId;
		$topic['AllianceTopic']['date'] = time();
		$topic['AllianceTopic']['new_post_date'] = $topic['AllianceTopic']['date'];
		$this->create();
		if($this->save($topic)) {
			$topic['AllianceTopic']['id'] = $this->id;
			return $topic;
		}
		else 
			return $errors;
	}
	
	function increaseCount($topic = null){
		$topic['AllianceTopic']['posts'] += 1;
		$topic['AllianceTopic']['new_post_date'] = time();
		$this->id = $topic['AllianceTopic']['id'];
		$this->save($topic);
	}
	
}

?>