<?php
class Retaliation extends AppModel {    
	var $name = 'Retaliation';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id',
			'counterCache' => true,
		)
	);
	var $recursive = -1;
		
	function getAllRetaliations($userId = null){
		return $this->find('all', array('conditions' => array('Retaliation.user_id' => $userId), 'order' => array('Retaliation.rounds ASC')));
	}
	
	function getAllRetaliationsAgainst($userId = null){
		return $this->find('all', array('conditions' => array('Retaliation.attacker_id' => $userId), 'order' => array('Retaliation.rounds ASC')));
	}
	
	/*
	 * $attackerId is the one that causes the retalation to be created.
	 */
	function getTopRetaliation($userId = null, $attackerId = null){
		return $this->find('first', array('conditions' => array('Retaliation.user_id' => $userId, 'Retaliation.attacker_id' => $attackerId), 'order' => array('Retaliation.rounds ASC')));
	}
	
	function createRetaliation($userId = null, $attackerId = null, $type = null){
		if(!isset($type)){
			$retaliation['user_id'] = $userId;
			$retaliation['attacker_id'] = $attackerId;
			$this->create();
			$this->save($retaliation);
		}
		else{
			if($type == "Spell"){
				$current = $this->find('first', array('conditions' => array('Retaliation.user_id' => $userId, 'Retaliation.attacker_id' => $attackerId, 'Retaliation.type' => $type)));
				if(empty($current)){
					$retaliation['user_id'] = $userId;
					$retaliation['attacker_id'] = $attackerId;
					$retaliation['type'] = $type;
					$this->create();
					$this->save($retaliation);
				} else {
					$retaliation['rounds'] = 36;
					$this->save($retaliation);
				}
			} else {
				$retaliation['user_id'] = $userId;
				$retaliation['attacker_id'] = $attackerId;
				$retaliation['type'] = $type;
				$this->create();
				$this->save($retaliation);
			}
		}
	}
	
	function reduceRetaliations($user = null) {
		$i = 0;
		foreach($user['Retaliation'] as &$retaliation):
			if($retaliation['rounds'] > 1){
				$retaliation['rounds']--;
			}
			else {
				$this->delete($retaliation['id']);
				unset($user['Retaliation'][$i]);
			}
			$i++;
		endforeach;
		return $user;
	}
	
	function removeRetaliation($retaliationId = null){
		$this->delete($retaliationId);
	}
}
	
?>