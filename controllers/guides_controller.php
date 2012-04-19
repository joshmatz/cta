<?php
class GuidesController extends AppController {    
	var $name = 'Guides';
	var $facebook_id;
	var $layout = 'newuser';
	var $uses = array();
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
	}

	function index() {  
		
		$this->redirect(array('action' => 'babySteps'));
	}
	
	function rockyou(){
		if(!empty($this->me['gender'])){
			if($this->me['gender'] == "male"){
				$sex = 1;
			}
			else {
				$sex = 0;
			}
			$this->set('sex', $sex);
		}
		if(!empty($this->me['gender']['birthday'])){
			$birthdate = explode("/", $this->me['gender']['birthday']);
			if(!empty($birthdate[2])){
				$birthday = $birthdate[0] . "-" . $birthdate[1] . "-" . $birthdate[2];
				$this->set('birthday', $birthday);
			}
		}
	}
	
	function experts(){
		
		$this->redirect(array('action' => 'navigate'));
	}
	
	function navigate(){
		
	}
	
	function glossary(){
		
	}
	
	function kingdom() {
		
	}
	
	function building(){
		
	}
	
	function population(){
		
	}
	
	function technology(){
		
	}
	
	function military(){
		
	}
	
	function magic(){
		
	}
	
	function babySteps(){
		
	}
	
	function toddlerSteps(){
		
	}
	
	function register(){
		
	}
	
	function strategy(){
		
	}
	
	function units(){
		
	}
	

	
}
?>