<?php
class TopicTrack extends AppModel {
	var $name = 'TopicTrack';
	var $validate = array(
		'id' => array(
			'rule' => 'blank',
			'on' => 'create'
		)
	);
	var $useDbConfig = "forum";
		

}
?>
