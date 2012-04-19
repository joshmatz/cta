<?php
class DeadUnit extends AppModel {    
	var $name = 'DeadUnit';
	
	
	function addOffensiveKills($unitsKilled = null, $race = null, $user = null){
		$kills['DeadUnit']['type'] = "Offense";
		$kills['DeadUnit']['big_elite'] = $unitsKilled[1]['number'];
		$kills['DeadUnit']['small_elite'] = $unitsKilled[0]['number'];
		$kills['DeadUnit']['weak'] = $unitsKilled[2]['number'];
		$kills['DeadUnit']['standard'] = $unitsKilled[3]['number'];
		$kills['DeadUnit']['mount'] = $unitsKilled[5]['number'];
		$kills['DeadUnit']['range'] = $unitsKilled[4]['number'];
		$kills['DeadUnit']['race'] = $race;
		$kills['DeadUnit']['user_id'] = $user['User']['id'];
		$kills['DeadUnit']['time'] = date('c', time());
		//debug($user);
		
		$this->create();
		$this->save($kills);
		
		return true;
	}
	
	function addDefensiveKills($unitsKilled = null, $race = null, $user = null){
		$kills['DeadUnit']['type'] = "Defense";
		$kills['DeadUnit']['big_elite'] = $unitsKilled[1]['number'];
		$kills['DeadUnit']['small_elite'] = $unitsKilled[0]['number'];
		$kills['DeadUnit']['weak'] = $unitsKilled[2]['number'];
		$kills['DeadUnit']['standard'] = $unitsKilled[3]['number'];
		$kills['DeadUnit']['mount'] = $unitsKilled[5]['number'];
		$kills['DeadUnit']['range'] = $unitsKilled[4]['number'];
		$kills['DeadUnit']['race'] = $race;
		$kills['DeadUnit']['user_id'] = $user['User']['id'];
		$kills['DeadUnit']['time'] = date('c', time());
		//debug($user);
		
		$this->create();
		$this->save($kills);
		
		return true;
	}
	
}
?>