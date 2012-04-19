<?php
class Forum extends AppModel {
	var $name = 'Forum';
	var $validate = array(
		'id' => array(
			'rule' => 'blank',
			'on' => 'create'
		),
		'name' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'That email is in use.'
			)
		)
	);
	var $useDbConfig = "forum";

}
?>
