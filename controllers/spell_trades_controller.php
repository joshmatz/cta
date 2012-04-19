<?php
class SpellTradesController extends AppController {    
	var $name = 'SpellTrades';
	var $facebook_id;
	var $layout = 'redesign';
	var $user;
	
	var $paginate = array(
		'limit' => 15,
		'order' => array(
			'SpellTrade.date' => 'desc'
		)    
	);
	
	function spellsList() {
		
	}
	
	function createSpellTrade() {
		
	}
	
	function acceptSpellTrade() {
		
	}
	
	function removeSpellTrade() {
		
	}
	
	function removeAllSpellTrades() {
		
	}
	
}
?>