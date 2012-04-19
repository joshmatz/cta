<?php
class FamesController extends AppController {    
	var $name = 'Fames';
	var $facebook_id;
	var $layout = 'newuser';
	var $paginate = array(
		'limit' => 25,
		'order' => array(
			'Fame.rank' => 'asc'
		)
	);
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index', 'alliance');
	}

	function index($round = 1) {  
		$data = $this->paginate('Fame', array('Fame.round' => $round));
		$this->set('data', $data);
		$this->set('round', $round);
	}
	
	function alliance($round = 4){
		if($round < 4){
			$round = 4;
		}
		$this->paginate = array(
			'limit' => 25,
			'order' => array(
				'AllianceFame.score_rank' => 'asc'
			)
		);
		$this->loadmodel('AllianceFame');
		$data = $this->paginate('AllianceFame', array('AllianceFame.round' => $round));
		$this->set('data', $data);
		$this->set('round', $round);
	}
	

	
}
?>