<?php

class Help extends AppModel {    
	var $name = 'Help';
	
	/*
	 * This is the main function to call from helpsController, it should do all the work
	 * 
	 * Current_facebook_id refers to the user that is doing the helping
	 * Existing_facebook_id refers to the user that is being helped
	 * type refers to what type of help is being completed 
	 * time makes sure that this is a real help that needs helping
	 */
	function checkHelper($current_facebook_id = null, $existing_facebook_id = null, $type = null, $time = 0) {
		$user['User']['facebook_id'] = $existing_facebook_id;
		$help = $this->getNewestHelp($user);
		if($current_facebook_id == $existing_facebook_id)
			return "Same User";
		if($help['Help']['expired'])
			return "Expired";
		if($help['Help']['date'] != $time)
			return "Wrong Date";
		$helps = $this->find('all', array('conditions' => array('Help.facebook_id' => $existing_facebook_id, 'Help.date' => $time, 'Help.type' => $type)));
		//debug($helps);
		if(!$helps)
			return "None Found";
		if($this->checkIfHelped($helps, $current_facebook_id))
			return "Already Helped";
		else {
			$total = $this->countHelps($helps);
			if($total > 5)
				return "Max";
			else
				return "OK";
		}
		
		return "Problem";
	}
	
	function setExpire($user = null, $bool = true) {
		$help = $this->getNewestHelp($user);
		$help['Help']['expire'] = 1;
		if($this->save($help))
			return 1;
		else
			return 0;
	}
	
	function getNewestHelp($user = null){
		return $this->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id']), 'order' => 'Help.date DESC'));
	}
	
	function getAllHelpsForUser($user = null){
		return $this->find('all', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
	}
	
	function makeNewHelp($facebook_id = null, $type = null){
		$help['facebook_id'] = $facebook_id;
		$help['type'] = $type;
		$help['date'] = strtotime(date('r', time()));
		if($this->save($help))
			return $help['date'];
		
		return 0;
	}
	
	function checkIfHelped($helps = null, $id = null) {
		//debug($helps);
		$isFound = false;
		foreach($helps as $help):
			if($id == $help['Help']['helper'])
				$isFound = true;
		endforeach;
		
		return $isFound;
	}
	
	function countHelps($helps = null) {
		$count = 0;
		foreach($helps as $help):
			$count++;
		endforeach;
		
		return $count;
	}
	
	function addNewHelp($facebook_id = null, $helper = null, $type = null, $date = null){
		$help['facebook_id'] = $facebook_id;
		$help['helper'] = $helper;
		$help['type'] = $type;
		$help['date'] = $date;
		if($this->save($help))
			return 1;
		
		return 0;
	}
}
?>