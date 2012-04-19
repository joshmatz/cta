<?php
class Trade extends AppModel {    
	var $name = 'Trade';

	
	function createTrade($trade = null, $userId = null) {
		$errors = "Your trade did not save. Please try again.";
		$trade['Trade']['user_id'] = $userId;
		$trade['Trade']['date'] = time();
		
		$this->create();
		if($this->save($trade))
			return 1;
		else 
			return $errors;
	}
	
	function getTrade($tradeId = null){
		return $this->find('first', array('conditions' => array('Trade.id' => $tradeId)));
	}
	
}
?>