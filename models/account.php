<?php
class Account extends AppModel {
	var $name = 'Account';
	var $validate = array(
		'id' => array(
			'rule' => 'blank',
			'on' => 'create'
		),
		'password' => array(
			'rule' => array('minLength', '8'),
			'message' => 'Your password must be at least 8 characters long.'
		),
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Please use a valid email address.'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'That email is in use.'
			)
		),
		'username' => array(
			'rule' => 'isUnique',
			'message' => 'That username is in use.'
		)
	);
	
	var $useDbConfig = "accounts";
		
	function getActivationHash() {
		if (!isset($this->id)):
			return false;
		endif;
		return substr(Security::hash(Configure::read('Security.salt') . $this->field('created') . date('Ymd')), 0, 8);
	}
}
?>
