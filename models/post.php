<?php
class Post extends AppModel {
	var $name = 'Post';
	var $validate = array(
		'id' => array(
			'rule' => 'blank',
			'on' => 'create'
		)
	);
	var $useDbConfig = "forum";         

}
?>
