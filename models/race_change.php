<?php
class RaceChange extends AppModel {    
	var $name = 'RaceChange';
	
	function addChange($userId = null, $newRace = null, $oldRace = null){
		if($userId){
			$change['RaceChange']['user_id'] = $userId;
			$change['RaceChange']['new_race'] = $newRace;
			$change['RaceChange']['previous_race'] = $oldRace;
			$change['RaceChange']['date'] = strtotime(date('r', time()));;
			$this->create();
			$this->save($change);
		}
		else {
			// error
		}
	}
	
	function countChanges($userId = null){
		if(1)
			return ($this->find('count', array('conditions' => array('RaceChange.user_id' => $userId)))) * 5;
		else
			return 0;
	}
	
}