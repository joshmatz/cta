<?php
class Premium extends AppModel {    
	var $name = 'Premium';
	var $useDbConfig = "accounts";
	
	function countPremiumPoints($account_id = null){
		if(isset($account_id)){
			$premiums = $this->find('all', array('conditions' => array('Premium.account_id' => $account_id)));
			
			$points = 0;
			foreach($premiums as $premium):
				$points += $premium['Premium']['change'];
			endforeach;
			
			return $points;
		} else {
			return 0;
		}
	}
	
	function addNewTransaction($account_id = null, $type = null, $change = null, $information = null){
		$transaction['Premium']['account_id'] = $account_id;
		$transaction['Premium']['type'] = $type;
		$transaction['Premium']['change'] = $change;
		$transaction['Premium']['information'] = $information;
		$transaction['Premium']['date'] = date('c', time());
		
		$old = $this->find('first', array('conditions' => array('Premium.account_id' => $account_id), 'order' => 'Premium.date DESC'));
		
		if($old) {
			$transaction['Premium']['new_amount'] = $old['Premium']['new_amount'] + $change;
		} else {
			$transaction['Premium']['new_amount'] = $change;
		}
		$this->create();
		$this->save($transaction);
	}
	
}
?>