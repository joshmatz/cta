<?php
class Topic extends AppModel {
	var $name = 'Topic';
	var $validate = array(
		'id' => array(
			'rule' => 'blank',
			'on' => 'create'
		)
	);
	var $useDbConfig = "forum";

}
?>
