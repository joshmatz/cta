<?php
class IpAddress extends AppModel {    
	var $name = 'IpAddress';
	
	function getAll($userID){
		return $this->find('all', array('conditions' => array('IpAddress.user_id' => $userID), 'order' => 'last_used DESC'));
	}
	
	// Returns true if there is a match
	function checkAddresses($victimID, $userID, $ipAddress = null) {
		$ipAddress = null;
		$numberDaysAgo = time() - (14 * 24 * 60 * 60);
		$numberDaysAgo = date('c', $numberDaysAgo);
		
		$victimIPs = $this->find('all', array('conditions' => array('IpAddress.user_id' => $victimID, 'IpAddress.last_used >' => $numberDaysAgo)));
		if(!isset($ipAddress)) {
			$userIPs = $this->find('all', array('conditions' => array('IpAddress.user_id' => $userID, 'IpAddress.last_used >' => $numberDaysAgo)));
		} else {
			$userIPs[0]['IpAddress']['ip_address'] = $ipAddress;
		}
		
		$match = false;
		if($victimID != $userID) {
			foreach($victimIPs as $victimIP):
				foreach($userIPs as $userIP):
					if($userIP['IpAddress']['ip_address'] == $victimIP['IpAddress']['ip_address']) {
						$match = true;
						break;
					}
				endforeach;
				if($match){
					break;
				}
			endforeach; 
		}
		
		return $match;
	}
	
	// Returns true if there is a match
	function checkInteractAddresses($victimID, $userID, $ipAddress) {
		$sevenDaysAgo = time() - (7 * 24 * 60 * 60);
		$userIPs = $this->find('all', array('conditions' => array('IpAddress.user_id' => $userID)));
		$victimIPs = Classregistry::init('Report')->find('all', array('conditions' => array('Report.user_id' => $victimID, 'Report.ip' => $ipAddress, 'Report.time >' => $sevenDaysAgo), 'fields' => 'Report.time, Report.ip, Report.user_id, Report.maker_id'));
		
		$match = false;
		if($victimID != $userID){
			foreach($victimIPs as $victimIP):
				foreach($userIPs as $userIP):
					if($userIP['IpAddress']['ip_address'] == $victimIP['Report']['ip']) {
						if($userID != $victimIP['Report']['maker_id'] && $victimIP['Report']['maker_id'] != 0){
							$match = true; 
							break;
						}
					}
				endforeach;
			endforeach; 
		}
		
		return $match;
	}

}