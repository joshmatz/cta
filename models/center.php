<?php
class Center extends AppModel {    
	var $name = 'Center';
	
	function checkCenter($userId = null){
		$center = $this->find('first', array('conditions' => array('Center.user_id' => $userId), 'fields' => 'Center.id'));
		if(!empty($center)){
			return true;
		} else {
			if($this->createCenter($userId)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	function setCenterUnits($userId = null, $bigElite = 0, $smallElite = 0, $weak = 0, $standard = 0, $range = 0, $mount = 0) {
		$center = $this->find('first', array('conditions' => array('Center.user_id' => $userId)));
		
		$center['Center']['big_elite'] = $bigElite;
		$center['Center']['small_elite'] = $smallElite;
		$center['Center']['weak'] = $weak;
		$center['Center']['standard'] = $standard;
		$center['Center']['range'] = $range;
		$center['Center']['mount'] = $mount;
		if($this->save($center)) {
			$center['Center']['id'] = $this->id;
			return $center;
		}
		else 
			return false;
	}
	
	function createCenter($userId = null, $bigElite = 0, $smallElite = 0, $weak = 0, $standard = 0, $range = 0, $mount = 0) {
		$center['Center']['user_id'] = $userId;
		$center['Center']['big_elite'] = $bigElite;
		$center['Center']['small_elite'] = $smallElite;
		$center['Center']['weak'] = $weak;
		$center['Center']['standard'] = $standard;
		$center['Center']['range'] = $range;
		$center['Center']['mount'] = $mount;
		$this->create();
		if($this->save($center)) {
			$center['Center']['id'] = $this->id;
			return $center;
		}
		else 
			return false;
	}
	
	function getCenterUnits($userId) {
		return $this->find('first', array('conditions' => array('Center.user_id' => $userId)));
	}
	
	function subtractCenterUnits($userId, $bigElite, $smallElite, $weak, $standard, $range, $mount) {
		$center = $this->find('first', array('conditions' => array('Center.user_id' => $userId)));
		
		$center['Center']['big_elite'] -= $bigElite;
		$center['Center']['small_elite'] -= $smallElite;
		$center['Center']['weak'] -= $weak;
		$center['Center']['standard'] -= $standard;
		$center['Center']['range'] -= $range;
		$center['Center']['mount'] -= $mount;
		
		if($center['Center']['big_elite'] < 0){
			$center['Center']['big_elite'] = 0;
		}
		if($center['Center']['small_elite'] < 0){
			$center['Center']['small_elite'] = 0;
		}
		if($center['Center']['weak'] < 0){
			$center['Center']['weak'] = 0;
		}
		if($center['Center']['standard'] < 0){
			$center['Center']['standard'] = 0;
		}
		if($center['Center']['range'] < 0){
			$center['Center']['range'] = 0;
		}
		if($center['Center']['mount'] < 0){
			$center['Center']['mount'] = 0;
		}
		if($center['Center']['mount'] < 0){
			$center['Center']['mount'] = 0;
		}
		
		if($this->save($center)) {
			return $center;
		}
		else 
			return false;
	}
	
	function addCenterUnits($userId, $bigElite, $smallElite, $weak, $standard, $range, $mount) {
		$center = $this->find('first', array('conditions' => array('Center.user_id' => $userId)));
		
		$center['Center']['big_elite'] += $bigElite;
		$center['Center']['small_elite'] += $smallElite;
		$center['Center']['weak'] += $weak;
		$center['Center']['standard'] += $standard;
		$center['Center']['range'] += $range;
		$center['Center']['mount'] += $mount;
		
		if($this->save($center)) {
			return $center;
		}
		else 
			return false;
	}
	
	function getTotalCenterUnits($center, $userId = null){
		if($userId) {
			$center = $this->find('first', array('conditions' => array('Center.user_id' => $userId)));
		}
		
		return $center['Center']['big_elite'] + $center['Center']['small_elite'] + $center['Center']['weak'] + $center['Center']['standard']
			 + $center['Center']['range'] + $center['Center']['mount'];
	}
	
    function getTotalCenterDefense($center, $default, $userId = null) {
   		if($userId) {
			$center = $this->find('first', array('conditions' => array('Center.user_id' => $userId)));
		}
    	
    	return $center['Center']['big_elite'] * $default[1]['defense']  + $center['Center']['small_elite'] * $default[0]['defense']
    		 + $center['Center']['weak'] * $default[2]['defense'] + $center['Center']['standard'] * $default[3]['defense']
			 + $center['Center']['range'] * $default[4]['defense'] + $center['Center']['mount'] * $default[5]['defense'];
    }
	
}

?>