<?php
class Producer extends AppModel {    
	var $name = 'Producer';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id'
		)
	);
	
	/***********
	 * Return total available land (land * 10) - Producers
	 */
	function getAvailableLand($user = null) {		
		return $user['User']['Resource']['land'] - $this->getTotalProducers($user['Producer']);
	}
	
	function isUnderCastleRatio($user) {
		$state = false;
		if($user['Producer']['castle'] * 450 > $this->getTotalProducers($user['Producer']))
			$state = true;
		
		return $state;
	}	
	
	function getTotalProducers($user = null){
		$total = 0;
		
		$total += $user['castle'];
		$total += $user['farm_total'];
		$total += $user['house_total'];
		$total += $user['market_total'];
		$total += $user['mill_total'];
		$total += $user['mine_total'];
		$total += $user['school_total'];
		$total += $user['forge_total'];
		$total += $user['guard_house_total'];
		$total += $user['guard_tower_total'];
		$total += $user['medical_center_total'];
		$total += $user['training_center_total'];
		
		return $total;
	}
	
	/*
	 * Return production value
	 */
	
	function getHouseProduction($user = null){
		$production = $user['Producer']['house_production'] * 4;
		return $production;
	}
	
	function getMarketProduction($user = null){
		$production = $user['Producer']['house_production'] * 200;
		return $production;
	}
	
	function getMillProduction($user = null) {
		$production = $user['Producer']['house_production'] * 5;
		return $production;
	}
	
	function getMineProduction($user = null) {
		$production = $user['Producer']['house_production'] * 4;
		return $production;
	}
	
	function getFarmProduction($user = null) {
		$production = $user['Producer']['house_production'] * 100;
		return $production;
	}
	
	function getTrainingProduction($user = null) {
		$production = $user['Producer']['house_production'] * 5;
		return $production;
	}
	
	function getForgeProduction($user = null) {
		$production = $user['Producer']['house_production'] * 15;
		return $production;
	}
	
	function getSchoolProduction($user = null) {
		$production = $user['Producer']['house_production'] * 5;
		return $production;
	}
	
	/*
	 * Output upgrade prices
	 */
	function getHouseOutputPrice($user = null) {
		
		return $price;
	}
	
	function getMarketOutputPrice($user = null) {
		
		return $price;
	}
	
	function getMillOutputPrice($user = null) {
		
		return $price;
	}
	
	function getMineOutputPrice($user = null) {
		
		return $price;
	}
	
	function getFarmOutputPrice($user = null) {
		
		return $price;
	}
	
	function getTrainingOutputPrice($user = null) {
		
		return $price;
	}
	
	function getForgeOutputPrice($user = null) {
		
		return $price;
	}
	
	function getSchoolOutputPrice($user = null) {
		
		return $price;
	}
	
	/*
	 * Capacity upgrade prices
	 */
	
	function getHouseCapacityPrice($user = null) {
		
		return $price;
	}
	
	function getMarketCapacityPrice($user = null) {
		
		return $price;
	}
	
	function getMillCapacityPrice($user = null) {
		
		return $price;
	}
	
	function getMineCapacityPrice($user = null) {
		
		return $price;
	}
	
	function getFarmCapacityPrice($user = null) {
		
		return $price;
	}
	
	function getTrainingCapacityPrice($user = null) {
		
		return $price;
	}
	
	function getForgeCapacityPrice($user = null) {
		
		return $price;
	}
	
	function getSchoolCapacityPrice($user = null) {
		
		return $price;
	}
	

	
	function getProducerInformation($user = null) {
		$prices = array(
			array(
				'info' => array(
					'description' => 'Castles provide defensive, economic, and other benefits to your kingdom.', 
					'name' => 'Castle',
					'lower' => 'castle',
					'img' => 'castle',
					'saved' => 0
				),
				'building' => array(
					'price' => array(
						'wood' => 14000,
						'iron' => 12500,
						'gold' => 30000
					),
					'total' => $user['Producer']['castle']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => 0
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => 0
				)
			),
			array(
				'info' => array(
					'description' => 'Houses are where your peasants live, which help you produce more energy per hour. ', 
					'name' => 'House',
					'lower' => 'house',
					'img' => 'house',
					'saved' => 0		
				),
				'building' => array(
					'price' => array(
						'wood' => 50,
						'iron' => 0,
						'gold' => 345
					),
					'total' => $user['Producer']['house_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $this->getHouseProduction($user)
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $user['Producer']['house_capacity']
				)
			),
			array(
				'info' => array(
					'description' => 'Markets produce your kingdom\'s gold. Nearly every action requires gold.', 
					'name' => 'Market',
					'lower' => 'market',
					'img' => 'market',
					'saved' => $user['User']['Resource']['gold_saved']
				),
				'building' => array(
					'price' => array(
						'wood' => 95,
						'iron' => 75,
						'gold' => 950
					),
					'total' => $user['Producer']['market_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $this->getMarketProduction($user)
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $user['Producer']['market_capacity']
				)
			),
			array(
				'info' => array(
					'description' => 'Farms produce food to feed your troops, wizards and peasants.', 
					'name' => 'Farm',
					'lower' => 'farm',
					'img' => 'farm',
					'saved' => $user['User']['Resource']['food_saved']
				),
				'building' => array(
					'price' => array(
						'wood' => 60,
						'iron' => 40,
						'gold' => 250
					),
					'total' => $user['Producer']['farm_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $this->getFarmProduction($user)
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $user['Producer']['farm_capacity']
				)
			),
			array(
				'info' => array(
					'description' => 'Mills produce your wood, which is helpful in the construction of buildings and crafting of weapons.', 
					'name' => 'Mill',
					'lower' => 'mill',
					'img' => 'mill',
					'saved' => $user['User']['Resource']['wood_saved']
				),
				'building' => array(
					'price' => array(
						'wood' => 70,
						'iron' => 0,
						'gold' => 615
					),
					'total' => $user['Producer']['mill_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $this->getMillProduction($user)
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $user['Producer']['mill_capacity']
				)
			),
			array(
				'info' => array(
					'description' => 'Mines produce iron, which is helpful in the construction of buildings and crafting of weapons.', 
					'name' => 'Mine',
					'lower' => 'mine',
					'img' => 'mine',
					'saved' => $user['User']['Resource']['iron_saved']
				),
				'building' => array(
					'price' => array(
						'wood' => 45,
						'iron' => 55,
						'gold' => 560
					),
					'total' => $user['Producer']['mine_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $this->getMineProduction($user)
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $user['Producer']['mine_capacity']
				)
			),
			array(
				'info' => array(
					'description' => 'Training Centers train your troops and provide untrained soldiers for you to train.', 
					'name' => 'Training Center',
					'lower' => 'training center',
					'img' => 'training_center',
					'saved' => $user['User']['Resource']['untrained_soldiers_saved']
				),
				'building' => array(
					'price' => array(
						'wood' => 225,
						'iron' => 300,
						'gold' => 2500
					),
					'total' => $user['Producer']['training_center_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $this->getTrainingProduction($user)
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $user['Producer']['training_center_capacity']
				)
			),
			array(
				'info' => array(
					'description' => 'Forges produce weapon molds which can be used in the Forge to craft weapons.', 
					'name' => 'Forge',
					'lower' => 'forge',
					'img' => 'forge',
					'saved' => $user['User']['Resource']['molds_saved']
				),
				'building' => array(
					'price' => array(
						'wood' => 90,
						'iron' => 135,
						'gold' => 700
					),
					'total' => $user['Producer']['forge_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $this->getForgeProduction($user)
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $user['Producer']['forge_capacity']
				)
			),
			array(
				'info' => array(
					'description' => 'Schools train wizards and provide housing for your magically inclined population.', 
					'name' => 'School',
					'lower' => 'school',
					'img' => 'school',
					'saved' => $user['User']['Resource']['untrained_wizards_saved']
				),
				'building' => array(
					'price' => array(
						'wood' => 115,
						'iron' => 45,
						'gold' => 3000
					),
					'total' => $user['Producer']['school_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $this->getSchoolProduction($user)
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => $user['Producer']['school_capacity']
				)
			),
			array(
				'info' => array(
					'description' => 'Medical Centers provide additional healing to your troops, cutting your losses when you\'re attacked.', 
					'name' => 'Medical Center',
					'lower' => 'medical center',
					'img' => 'medical_center',
					'saved' => 0
				),
				'building' => array(
					'price' => array(
						'wood' => 165,
						'iron' => 135,
						'gold' => 425
					),
					'total' => $user['Producer']['medical_center_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => 0
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => 0
				)
			),
			array(
				'info' => array(
					'description' => 'Guard Towers provide extra defense when you\'re attacked, dealing additional damage to your enemy.', 
					'name' => 'Guard Tower',
					'lower' => 'guard tower',
					'img' => 'guard_tower',
					'saved' => 0
				),
				'building' => array(
					'price' => array(
						'wood' => 150,
						'iron' => 135,
						'gold' => 675
					),
					'total' => $user['Producer']['guard_tower_total']
				),
				'output' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => 0
				),
				'capacity' => array(
					'price' => array(
						'wood' => 0,
						'iron' => 0,
						'gold' => 0
					),
					'new' => 0,
					'current' => 0
				)
			),
			
		);
		
		return $prices;
	}

}
?>