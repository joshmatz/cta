<?php
class KillStat extends AppModel {    
	var $name = 'KillStat';
	
	function updateAllStats(){
		$User = Classregistry::init('User');
		
		$fields = "User.id, User.ruler";
		$users = $User->find('all', array('fields' => $fields, 'recursive' => -1));
		
		foreach($users as $user):
			$this->updateKillStats($user);
		endforeach;
		
		$this->rankKillStats();
		
	}
	
	function rankKillStats(){
		$order = 'KillStat.points DESC';
		$fields = "KillStat.points, KillStat.id";
		$type = "Defense";
		$players = $this->find('all', array('conditions' => array('KillStat.type' => $type), 'fields' => $fields, 'order' => $order));
		$rank = 1;
		foreach($players as &$player):
			$player['KillStat']['rank'] = $rank;
			if($this->save($player['KillStat'], array('validate' => true, 'fieldList' => array('rank')))){
				//echo "Rank saved as $rank";
			}
			$rank++;
		endforeach;
		unset($players);
		
		$type = "Offense";
		$players = $this->find('all', array('conditions' => array('KillStat.type' => $type), 'fields' => $fields, 'order' => $order));
		$rank = 1;
		foreach($players as &$player):
			$player['KillStat']['rank'] = $rank;
			if($this->save($player['KillStat'], array('validate' => true, 'fieldList' => array('rank')))){
				//echo "Rank saved as $rank";
			}
			$rank++;
		endforeach;
		
		unset($players);
		
		$type = "Total";
		$players = $this->find('all', array('conditions' => array('KillStat.type' => $type), 'fields' => $fields, 'order' => $order));
		$rank = 1;
		foreach($players as &$player):
			$player['KillStat']['rank'] = $rank;
			if($this->save($player['KillStat'], array('validate' => true, 'fieldList' => array('rank')))){
				//echo "Rank saved as $rank";
			}
			$rank++;
		endforeach;
	}
	
	function updateKillStats($user = null){
		$killStatDefense = $this->find('first', array('conditions' => array('KillStat.user_id' => $user['User']['id'], 'KillStat.type' => "Defense")));
		$killStatDefense['KillStat']['points'] = $this->getPoints($user, "Defense");
		if(isset($killStatDefense['KillStat']['id'])){
			$this->save($killStatDefense);
		} else {
			if(!empty($user['User']['ruler'])) {
				$this->create();
				$killStatDefense['KillStat']['user_id'] = $user['User']['id'];
				$killStatDefense['KillStat']['type'] = "Defense";
				$killStatDefense['KillStat']['rank'] = $this->find('count', array('conditions' => array('KillStat.type' => "Offense"))) + 1;
				$this->save($killStatDefense);
			}
		}
		unset($killStat);
		
		$killStatOffense = $this->find('first', array('conditions' => array('KillStat.user_id' => $user['User']['id'], 'KillStat.type' => "Offense")));
		$killStatOffense['KillStat']['points'] = $this->getPoints($user, "Offense");		
		if(isset($killStatOffense['KillStat']['id'])){
			$this->save($killStatOffense);
		} else {
			if(!empty($user['User']['ruler'])){
				$this->create();
				$killStatOffense['KillStat']['user_id'] = $user['User']['id'];
				$killStatOffense['KillStat']['type'] = "Offense";
				$killStatOffense['KillStat']['rank'] = $this->find('count', array('conditions' => array('KillStat.type' => "Offense"))) + 1;
				$this->save($killStatOffense);
			}
		}
		
		$killStatTotal = $this->find('first', array('conditions' => array('KillStat.user_id' => $user['User']['id'], 'KillStat.type' => "Total")));
		$killStatTotal['KillStat']['points'] = $killStatDefense['KillStat']['points'] + $killStatOffense['KillStat']['points'];
		if(isset($killStatTotal['KillStat']['id'])){
			$this->save($killStatTotal);
		} else {
			if(!empty($user['User']['ruler'])){
				$this->create();
				$killStatTotal['KillStat']['user_id'] = $user['User']['id'];
				$killStatTotal['KillStat']['type'] = "Total";
				$killStatTotal['KillStat']['rank'] = $this->find('count', array('conditions' => array('KillStat.type' => "Total"))) + 1;
				$this->save($killStatTotal);
			}
		}
	}
	
	function getPoints($user = null, $type = null){
		$DeadUnit = Classregistry::init('DeadUnit');
		
		$unitsKilled = $DeadUnit->find('all', array('conditions' => array('DeadUnit.user_id' => $user['User']['id'], 'DeadUnit.type' => $type)));
		$points = 0;
		foreach($unitsKilled as $units):	
			switch($units['DeadUnit']['race']) {
	    	case "Orc":
				$points += ($units['DeadUnit']['weak'] * 1.4); // weak unit
		    	$points += ($units['DeadUnit']['standard'] * 2.1); // standard unit
		    	$points += ($units['DeadUnit']['small_elite'] * 3.8); // small elite
		    	$points += ($units['DeadUnit']['big_elite'] * 5.3); // big elite
		    	$points += ($units['DeadUnit']['range'] * 1.8); // range
		    	$points += ($units['DeadUnit']['mount'] * 3.3); // mount			
		    	break;
			case "Gnome":
				$points += ($units['DeadUnit']['weak'] * .7); // weak unit
		    	$points += ($units['DeadUnit']['standard'] * 1.4); // standard unit
		    	$points += ($units['DeadUnit']['small_elite'] * 12); // small elite
		    	$points += ($units['DeadUnit']['big_elite'] * 16.5); // big elite
		    	$points += ($units['DeadUnit']['range'] * .9); // range
		    	$points += ($units['DeadUnit']['mount'] * 3.6); // mount	
				break;
			case "Dwarf":
				$points += ($units['DeadUnit']['weak'] * 4.2); // weak unit
		    	$points += ($units['DeadUnit']['standard'] * 6); // standard unit
		    	$points += ($units['DeadUnit']['small_elite'] * 3.5); // small elite
		    	$points += ($units['DeadUnit']['big_elite'] * 6); // big elite
		    	$points += ($units['DeadUnit']['range'] * 2.7); // range
		    	$points += ($units['DeadUnit']['mount'] * 3.6); // mount		
				break;
			case "Elf":
				$points += ($units['DeadUnit']['weak'] * 2.1); // weak unit
		    	$points += ($units['DeadUnit']['standard'] * 2.1); // standard unit
		    	$points += ($units['DeadUnit']['small_elite'] * 9); // small elite
		    	$points += ($units['DeadUnit']['big_elite'] * 7.7); // big elite
		    	$points += ($units['DeadUnit']['range'] * 2.4); // range
		    	$points += ($units['DeadUnit']['mount'] * 3.9); // mount	
				break;
			case "Human":
				$points += ($units['DeadUnit']['weak'] * 2.5); // weak unit
		    	$points += ($units['DeadUnit']['standard'] * 2.4); // standard unit
		    	$points += ($units['DeadUnit']['small_elite'] * 4.5); // small elite
		    	$points += ($units['DeadUnit']['big_elite'] * 6.3); // big elite
		    	$points += ($units['DeadUnit']['range'] * 2.1); // range
		    	$points += ($units['DeadUnit']['mount'] * 4.2); // mount	
				break;
			default:
				echo "Error accessing database.";
				break;
			}
		endforeach; // totalling points
		
		$points = round($points / 3);
		
		return $points;
	}
	
}
?>