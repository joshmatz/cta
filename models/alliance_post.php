<?php
class AlliancePost extends AppModel {    
	var $name = 'AlliancePost';
	
	function createPost($post = null, $userId = null) {
		$errors = "";
		$post['AlliancePost']['topic_id'] = $post['AllianceTopic']['id'];
		$post['AlliancePost']['user_id'] = $userId;
		if(isset($post['AllianceTopic']['date']))
			$post['AlliancePost']['date'] = $post['AllianceTopic']['date'];
		else
			$post['AlliancePost']['date'] = time();
		$this->create();
		if($this->save($post))
			return 1;
		else 
			return $errors;
	}
}

?>