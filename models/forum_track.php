<?php
class ForumTrack extends AppModel {
	var $name = 'ForumTrack';
	var $validate = array(
		'id' => array(
			'rule' => 'blank',
			'on' => 'create'
		)
	);
	var $useDbConfig = "forum";
		

}
?>
