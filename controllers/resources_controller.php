<?php
class ResourcesController extends AppController {    
	var $name = 'Resources';
	
	function index() {      
		parent::beforeFilter();  
		//$this->set('posts', $this->Post->find('all'));    
	}
	

?>