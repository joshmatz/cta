<?php
class Technology extends AppModel {    
	var $name = 'Technology';
	var $belongsTo = array(
		'User' => array(
			'className'    => 'User',
			'foreignKey'    => 'user_id'
		)
	);
	
	/*
	 * 
	 * 
	 * Elf needs programming
	 * 
	 */
	
	function getTree1Display($user = null){
		$tree1['set'] = false;
		
		if($user['Technology']['tree_1'] == 0) { 
			$tree1['name'] = "Fieldglass";
			$tree1['img'] = "fieldglass";
			$tree1['description'] = "Doubles exploration efficiency.";
			$tree1['time'] = 20;
			$tree1['cost'] = 100000;
			$tree1['set'] = true;
		 } elseif($user['Technology']['tree_1'] == 1) { 
			$tree1['name'] = "Convertion";
			$tree1['img'] = "convertion";
			$tree1['description'] = "Increases the number of peasants joining your kingdom after capturing land from an enemy.";
			$tree1['time'] = 25;
			$tree1['cost'] = 200000; 
			$tree1['set'] = true;
		 } elseif($user['Technology']['tree_1'] == 2){ 
			$tree1['name'] = "Transport";
			$tree1['img'] = "transport";
			$tree1['description'] = "Reduces attack time to 18 days.";
			$tree1['time'] = 30;
			$tree1['cost'] = 300000; 
			$tree1['set'] = true;
		 } elseif($user['Technology']['tree_1'] == 3){ 
			$tree1['name'] = "Improved Medicine";
			$tree1['img'] = "clerical_magic";
			$tree1['description'] = "Using improved medical techniques, units have an improved natural recovery rate (10% to 20%) and medical centers provide additional recovery (15% to 30%).";
			$tree1['time'] = 35;
			$tree1['cost'] = 400000; 
			$tree1['set'] = true;	
		 } elseif($user['Technology']['tree_1'] == 4){ 
			$tree1['name'] = "Leadership";
			$tree1['img'] = "leadership";
			$tree1['description'] = "Increase minumum Basic Army Strength to 100";
			$tree1['time'] = 40;
			$tree1['cost'] = 500000; 
			$tree1['set'] = true;
		 } elseif($user['Technology']['tree_1'] == 5){ 
			$tree1['name'] = "Improved Law System";
			$tree1['img'] = "improved_law_system";
			$tree1['description'] = "Reduce crime rate natural growth and reduce maximum crime rate to 25%.";
			$tree1['time'] = 45;
			$tree1['cost'] = 1000000;
			$tree1['set'] = true; 
		 } elseif($user['Technology']['tree_1'] == 6){ 
			$tree1['name'] = "Tactical Knowledge";
			$tree1['img'] = "tactical_knowledge";
			$tree1['description'] = "Greatly improves chances of victory in combat.";
			$tree1['time'] = 50;
			$tree1['cost'] = 2000000; 
			$tree1['set'] = true;
		 } 
		 
		if($user['User']['race'] == "Gnome" && isset($tree1['time'])) {
			$tree1['time'] = round($tree1['time'] * .9);
			$tree1['cost'] = round($tree1['cost'] * .9);
		}

		return $tree1;
	}
	
	function getTree2Display($user = null){
		$tree2['set'] = false;
		if($user['Technology']['tree_2'] == 0) { 
			$tree2['name'] = "Habitation";
			$tree2['img'] = "habitation";
			$tree2['description'] = "Increases the number of peasants per house to 115.";
			$tree2['time'] = 20;
			$tree2['cost'] = 100000;
			$tree2['set'] = true;
 		} elseif($user['Technology']['tree_2'] == 1) { 
			$tree2['name'] = "Commerce";
			$tree2['img'] = "commerce";
			$tree2['description'] = "Increases gold production.";
			$tree2['time'] = 25;
			$tree2['cost'] = 200000;
			$tree2['set'] = true;
		} elseif($user['Technology']['tree_2'] == 2) { 
			$tree2['name'] = "Blacksmithing";
			$tree2['img'] = "blacksmithing_weapon";
			$tree2['description'] = "Increases weapon production by 25%";
			$tree2['time'] = 30;
			$tree2['cost'] = 300000;
			$tree2['set'] = true;
		} elseif($user['Technology']['tree_2'] == 3) { 
			$tree2['name'] = "Carpentry";
			$tree2['img'] = "carpentry";
			$tree2['description'] = "Increases building construction speed by 50%.";
			$tree2['time'] = 35;
			$tree2['cost'] = 400000;
			$tree2['set'] = true;
		} elseif($user['Technology']['tree_2'] == 4) { 
			$tree2['name'] = "Barracks";
			$tree2['img'] = "barrack";
			$tree2['description'] = "Increases the number of men that can be housed in a guard house by 25.";
			$tree2['time'] = 40;
			$tree2['cost'] = 500000;
			$tree2['set'] = true;
		} elseif($user['Technology']['tree_2'] == 5) { 
			$tree2['name'] = "Training Techniques";
			$tree2['img'] = "training_techniques";
			$tree2['description'] = "Increases unit production speed by 25%.";
			$tree2['time'] = 45;
			$tree2['cost'] = 1000000;
			$tree2['set'] = true;
		} elseif($user['Technology']['tree_2'] == 6) { 
			$tree2['name'] = "Fortification";
			$tree2['img'] = "fortification";
			$tree2['description'] = "Decreases enemy attack power by 5% when defending a castle.";
			$tree2['time'] = 50;
			$tree2['cost'] = 2000000;
			$tree2['set'] = true;
		} 
		
		if($user['User']['race'] == "Gnome" && isset($tree2['time'])) {
			$tree2['time'] = round($tree2['time'] * .9);
			$tree2['cost'] = round($tree2['cost'] * .9);
		}
		
		return $tree2;
	}
	
	function getTree3Display($user = null){
		$tree3['set'] = false;
		
		if($user['Technology']['tree_3'] == 0) { 
			$tree3['name'] = "Irrigation";
			$tree3['img'] = "irrigation";
			$tree3['description'] = "Increases the number of food produced per farm by 15.";
			$tree3['time'] = 30;
			$tree3['cost'] = 100000;
			$tree3['set'] = true;
		} elseif($user['Technology']['tree_3'] == 1) { 
			$tree3['name'] = "Machinery";
			$tree3['img'] = "machinery";
			$tree3['description'] = "Increases iron production by 1.5 per mine.";
			$tree3['time'] = 40;
			$tree3['cost'] = 500000;
			$tree3['set'] = true;
		} elseif($user['Technology']['tree_3'] == 2) { 
			$tree3['name'] = "Wood Recycling";
			$tree3['img'] = "wood_recycling";
			$tree3['description'] = "Increases wood production by 1.5 per Mill.";
			$tree3['time'] = 50;
			$tree3['cost'] = 1000000;
			$tree3['set'] = true;
		} 
		
		if($user['User']['race'] == "Gnome" && isset($tree3['time'])) {
			$tree3['time'] = round($tree3['time'] * .9);
			$tree3['cost'] = round($tree3['cost'] * .9);
		}
		
		return $tree3;
	}
	
	function getTree4Display($user = null){
		$tree4['set'] = false;
		
		if($user['Technology']['tree_4'] == 0) { 
			$tree4['name'] = "New Spells 1";
			$tree4['img'] = "newspells";
			$tree4['description'] = "Adds new spells to your spellbook.";
			$tree4['time'] = 25;
			$tree4['cost'] = 125000;
			$tree4['set'] = true;
		} elseif($user['Technology']['tree_4'] == 1) { 
			$tree4['name'] = "New Spells 2";
			$tree4['img'] = "newspells";
			$tree4['description'] = "Adds new spells to your spellbook. ";
			$tree4['time'] = 30;
			$tree4['cost'] = 250000;
			$tree4['set'] = true;
		} elseif($user['Technology']['tree_4'] == 2) { 
			$tree4['name'] = "New Spells 3";
			$tree4['img'] = "newspells";
			$tree4['description'] = "Adds new spells to your spellbook. ";
			$tree4['time'] = 35;
			$tree4['cost'] = 375000;
			$tree4['set'] = true;
		} elseif($user['Technology']['tree_4'] == 3) { 
			$tree4['name'] = "New Spells 4";
			$tree4['img'] = "newspells";
			$tree4['description'] = "Adds new spells to your spellbook.";
			$tree4['time'] = 40;
			$tree4['cost'] = 500000;
			$tree4['set'] = true;
		} elseif($user['Technology']['tree_4'] == 4) { 
			$tree4['name'] = "Insight";
			$tree4['img'] = "insight";
			$tree4['description'] = "Decreases wizard rest time.  They will rest at a rate of 2 per school per day.";
			$tree4['time'] = 50;
			$tree4['cost'] = 1000000;
			$tree4['set'] = true;
		} elseif($user['Technology']['tree_4'] == 5) { 
			$tree4['name'] = "Magical Instruments";
			$tree4['img'] = "magical_instruments";
			$tree4['description'] = "Greatly improves spell casting success.";
			$tree4['time'] = 60;
			$tree4['cost'] = 2000000;
			$tree4['set'] = true;
		} 
		
		if($user['User']['race'] == "Gnome" && isset($tree4['time'])) {
			$tree4['time'] = round($tree4['time'] * .9);
			$tree4['cost'] = round($tree4['cost'] * .9);
		}
		
		return $tree4;
	}

	function getRacialDisplay($user = null){
		$racial['set'] = false;
		
		if($user['User']['race'] == "Dwarf" && $user['Technology']['racial'] == 0) { 
			$racial['name'] = "True Silver";
			$racial['img'] = "true_silver";
			$racial['description'] = "Researching new materials for shield creating has been found to help in many great armies.  It is believed that many dwarven leaders found a new metal called <i>True Silver</i> that is one of the most resistant material ever found.  With this metal, they were able to create tougher shields for their shieldbearers.";
			$racial['time'] = 60;
			$racial['cost'] = 2000000;
			$racial['set'] = true;
		} elseif($user['User']['race'] == "Elf" && $user['Technology']['racial'] == 0) { 
			$racial['name'] = "Magic Field";
			$racial['img'] = "magic_field";
			$racial['description'] = "This new technology consists in researching a new spell for your wizards. The new spells include Ice Storm and Shadow Hall, allowing you to cease a kingdom's production of food and hide a kingdom from your enemies.";
			$racial['time'] = 60;
			$racial['cost'] = 2000000;
			$racial['set'] = true;
		} elseif($user['User']['race'] == "Gnome" && $user['Technology']['racial'] == 0) { 
			$racial['name'] = "Explosive powder";
			$racial['img'] = "explosive_powder";
			$racial['description'] = "This technology allows you to build explosive projectiles for your catapults, increasing their attack radius.";
			$racial['time'] = 60;
			$racial['cost'] = 2000000;
			$racial['set'] = true;
		} elseif($user['User']['race'] == "Human" && $user['Technology']['racial'] == 0) { 
			$racial['name'] = "Charisma";
			$racial['img'] = "clerical_magic";
			$racial['description'] = "Charisma helps your own units fight better by granting additional BAS. It can increase your BAS up to an extra 55 percent, based on the proportion of Knights in your army in conjunction with a small random factor. (An army consisting of 50% Knights can receive 22.5-27.5 additional BAS).";
			$racial['time'] = 60;
			$racial['cost'] = 2000000;
			$racial['set'] = true;
		} elseif($user['User']['race'] == "Orc" && $user['Technology']['racial'] == 0) { 
			$racial['name'] = "For the Pack";
			$racial['img'] = "cloaking";
			$racial['description'] = "Orcs, being very obtuse disgusting creatures can still learn to create attachments with animals who don't think of how ugly an Orc's face is. Researching this technology allows wolves whose masters have died in long range to still attack in the melee phase. Wolves receive 2 melee attack, +.5 for each blade technology.";
			$racial['time'] = 60;
			$racial['cost'] = 2000000;
			$racial['set'] = true;
		} 
		
		if($user['User']['race'] == "Gnome" && isset($racial['time'])) {
			$racial['time'] = round($racial['time'] * .9);
			$racial['cost'] = round($racial['cost'] * .9);
		}	
		
		return $racial;
	}
	
	
	function getBladeDisplay($user = null){
		$blade['set'] = false;
		
		if($user['Technology']['blade'] == 0) { 
			$blade['name'] = "Sharper Blades 1";
			$blade['img'] = "blacksmithing_weapon";
			$blade['description'] = "Increases blade weapon damage. ";
			$blade['time'] = 30;
			$blade['cost'] = 250000;
			$blade['set'] = true;
		} elseif($user['Technology']['blade'] == 1) { 
			$blade['name'] = "Sharper Blades 2";
			$blade['img'] = "blacksmithing_weapon";
			$blade['description'] = "Increases blade weapon damage. ";
			$blade['time'] = 40;
			$blade['cost'] = 500000;
			$blade['set'] = true;
		} elseif($user['Technology']['blade'] == 2) { 
			$blade['name'] = "Sharper Blades 3";
			$blade['img'] = "blacksmithing_weapon";
			$blade['description'] = "Increases blade weapon damage. ";
			$blade['time'] = 50;
			$blade['cost'] = 750000;
			$blade['set'] = true;
		} elseif($user['Technology']['blade'] == 3) { 
			$blade['name'] = "Sharper Blades 4";
			$blade['img'] = "blacksmithing_weapon";
			$blade['description'] = "Increases blade weapon damage. ";
			$blade['time'] = 60;
			$blade['cost'] = 1000000;
			$blade['set'] = true;
		}
		
		if($user['User']['race'] == "Gnome" && isset($blade['time'])) {
			$blade['time'] = round($blade['time'] * .9);
			$blade['cost'] = round($blade['cost'] * .9);
		}
		
		return $blade;
	}
	
	function getLightArmorDisplay($user = null){
		$lightArmor['set'] = false;
		
		if($user['Technology']['light_armor'] == 0) { 
			$lightArmor['name'] = "Improved Light Armor 1";
			$lightArmor['img'] = "blacksmithing_weapon";
			$lightArmor['description'] = "Increases light armor effectiveness. ";
			$lightArmor['time'] = 30;
			$lightArmor['cost'] = 250000;
			$lightArmor['set'] = true;
		} elseif($user['Technology']['light_armor'] == 1) { 
			$lightArmor['name'] = "Improved Light Armor 2";
			$lightArmor['img'] = "blacksmithing_weapon";
			$lightArmor['description'] = "Increases light armor effectiveness. ";
			$lightArmor['time'] = 40;
			$lightArmor['cost'] = 500000;
			$lightArmor['set'] = true;
		} elseif($user['Technology']['light_armor'] == 2) { 
			$lightArmor['name'] = "Improved Light Armor 3";
			$lightArmor['img'] = "blacksmithing_weapon";
			$lightArmor['description'] = "Increases light armor effectiveness. ";
			$lightArmor['time'] = 50;
			$lightArmor['cost'] = 750000;
			$lightArmor['set'] = true;
		} elseif($user['Technology']['light_armor'] == 3) { 
			$lightArmor['name'] = "Improved Light Armor 4";
			$lightArmor['img'] = "blacksmithing_weapon";
			$lightArmor['description'] = "Increases light armor effectiveness. ";
			$lightArmor['time'] = 60;
			$lightArmor['cost'] = 1000000;
			$lightArmor['set'] = true;
		}
		
		if($user['User']['race'] == "Gnome" && isset($lightArmor['time'])) {
			$lightArmor['time'] = round($lightArmor['time'] * .9);
			$lightArmor['cost'] = round($lightArmor['cost'] * .9);
		}
		
		return $lightArmor;
	}
	
	function getHeavyArmorDisplay($user = null){
		$heavyArmor['set'] = false;
		
		if($user['Technology']['heavy_armor'] == 0) { 
			$heavyArmor['name'] = "Improved Heavy Armor 1";
			$heavyArmor['img'] = "blacksmithing_weapon";
			$heavyArmor['description'] = "Increases heavy armor effectiveness. ";
			$heavyArmor['time'] = 30;
			$heavyArmor['cost'] = 250000;
			$heavyArmor['set'] = true;
		} elseif($user['Technology']['heavy_armor'] == 1) { 
			$heavyArmor['name'] = "Improved Heavy Armor 2";
			$heavyArmor['img'] = "blacksmithing_weapon";
			$heavyArmor['description'] = "Increases heavy armor effectiveness. ";
			$heavyArmor['time'] = 40;
			$heavyArmor['cost'] = 500000;
			$heavyArmor['set'] = true;
		} elseif($user['Technology']['heavy_armor'] == 2) { 
			$heavyArmor['name'] = "Improved Heavy Armor 3";
			$heavyArmor['img'] = "blacksmithing_weapon";
			$heavyArmor['description'] = "Increases heavy armor effectiveness. ";
			$heavyArmor['time'] = 50;
			$heavyArmor['cost'] = 750000;
			$heavyArmor['set'] = true;
		} elseif($user['Technology']['heavy_armor'] == 3) { 
			$heavyArmor['name'] = "Improved Heavy Armor 4";
			$heavyArmor['img'] = "blacksmithing_weapon";
			$heavyArmor['description'] = "Increases heavy armor effectiveness. ";
			$heavyArmor['time'] = 60;
			$heavyArmor['cost'] = 1000000;
			$heavyArmor['set'] = true;
		}
		
		if($user['User']['race'] == "Gnome" && isset($heavyArmor['time'])) {
			$heavyArmor['time'] = round($heavyArmor['time'] * .9);
			$heavyArmor['cost'] = round($heavyArmor['cost'] * .9);
		}
		
		return $heavyArmor;
	}
	
	function getRangeDisplay($user = null){
		$range['set'] = false;
		
		if($user['Technology']['range'] == 0) { 
			$range['name'] = "Improved Range Attack 1";
			$range['img'] = "blacksmithing_weapon";
			$range['description'] = "Improves ranged attack by +25% of normal ranged attack.";
			$range['time'] = 30;
			$range['cost'] = 250000;
			$range['set'] = true;
		}
		if($user['Technology']['range'] == 1) { 
			$range['name'] = "Improved Range Attack 2";
			$range['img'] = "blacksmithing_weapon";
			$range['description'] = "Improves ranged attack by +25% of normal ranged attack.";
			$range['time'] = 40;
			$range['cost'] = 500000;
			$range['set'] = true;
		}
		if($user['Technology']['range'] == 2) { 
			$range['name'] = "Improved Range Attack 3";
			$range['img'] = "blacksmithing_weapon";
			$range['description'] = "Improves ranged attack by +25% of normal ranged attack.";
			$range['time'] = 50;
			$range['cost'] = 750000;
			$range['set'] = true;
		}
		if($user['Technology']['range'] == 3) { 
			$range['name'] = "Improved Range Attack 4";
			$range['img'] = "blacksmithing_weapon";
			$range['description'] = "Improves ranged attack by +25% of normal ranged attack.";
			$range['time'] = 60;
			$range['cost'] = 1000000;
			$range['set'] = true;
		}
		
		
		if($user['User']['race'] == "Gnome" && isset($range['time'])) {
			$range['time'] = round($range['time'] * .9);
			$range['cost'] = round($range['cost'] * .9);
		}
		
		return $range;
	}
	
	
	
	function updateResearch($user = null){
		if($user['Technology']['research'] == "tree_1") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['tree_1']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");
			}
		}
		elseif($user['Technology']['research'] == "tree_2") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['tree_2']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");
			}
		}
		elseif($user['Technology']['research'] == "tree_3") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['tree_3']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");
			}
		}
		elseif($user['Technology']['research'] == "tree_4") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['tree_4']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");
			}
		}
		elseif($user['Technology']['research'] == "racial") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['racial']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");
			}
		}
		elseif($user['Technology']['research'] == "blade") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['blade']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");
			}
		}
		elseif($user['Technology']['research'] == "light_armor") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['light_armor']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");		
			}
		}
		elseif($user['Technology']['research'] == "heavy_armor") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['heavy_armor']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");
			}
		}
		elseif($user['Technology']['research'] == "range") {
			if(--$user['Technology']['time'] == 0) {
				$user['Technology']['range']++;
				$user['Technology']['research'] = "";
				Classregistry::init('Help')->setExpire($user);
				if($user['Technology']['queue']){
					$user['Technology']['research'] = $user['Technology']['queue'];
					$user['Technology']['time'] = $user['Technology']['queue_time'];
					$user['Technology']['queue'] = "";
					$user['Technology']['queue_time'] = 0;
					$this->Help = Classregistry::init('Help');
					$this->Help->makeNewHelp($user['User']['facebook_id'], "Tech");
					$help = $this->Help->find('first', array('conditions' => array('Help.facebook_id' => $user['User']['facebook_id'])));
				}
				Classregistry::init('Report')->makeNewReport($user['User']['id'], 'Your research has been completed. Your kingdom should start researching something new.', "Technology Finish", 0, 0, "You finished researching something!");
			}
		}
		
		return $user;
	}

	function research($user = null, $data = null) {
		if($data['Technology']['tree'] == "tree_1") {
			$treeDisplay = $this->getTree1Display($user);
			$tree = "tree_1";
		}
		if($data['Technology']['tree'] == "tree_2") {
			$treeDisplay = $this->getTree2Display($user);
			$tree = "tree_2";
		}
		if($data['Technology']['tree'] == "tree_3") {
			$treeDisplay = $this->getTree3Display($user);
			$tree = "tree_3";
		}
		if($data['Technology']['tree'] == "tree_4") {
			$treeDisplay = $this->getTree4Display($user);
			$tree = "tree_4";
		}
		if($data['Technology']['tree'] == "racial") {
			$treeDisplay = $this->getRacialDisplay($user);
			$tree = "racial";
		}
		if($data['Technology']['tree'] == "blade") {
			$treeDisplay = $this->getBladeDisplay($user);
			$tree = "blade";
		}
		if($data['Technology']['tree'] == "light_armor") {
			$treeDisplay = $this->getLightArmorDisplay($user);
			$tree = "light_armor";
		}
		if($data['Technology']['tree'] == "heavy_armor") {
			$treeDisplay = $this->getHeavyArmorDisplay($user);
			$tree = "heavy_armor";
		}
		if($data['Technology']['tree'] == "range") {
			$treeDisplay = $this->getRangeDisplay($user);
			$tree = "range";
		}
			
		if($treeDisplay['cost'] <= $user['User']['Resource']['gold']) {
			$user['User']['Resource']['gold'] -= $treeDisplay['cost'];
			$user['Technology']['research'] = $tree;
			$user['Technology']['time'] = $treeDisplay['time'];
			
			$this->id = $user['Technology']['id'];
			$this->save($user);
			$this->User->Resource->id = $user['User']['Resource']['id'];
			$this->User->Resource->save($user['User']['Resource']);
			return $treeDisplay;
		}
		
		return false;
	}
	
	function researchQueue($user = null, $data = null) {
		$user = $this->increaseTechForDisplay($user);
		
		if($data['Technology']['tree'] == "tree_1") {
			$treeDisplay = $this->getTree1Display($user);
			$tree = "tree_1";
		}
		if($data['Technology']['tree'] == "tree_2") {
			$treeDisplay = $this->getTree2Display($user);
			$tree = "tree_2";
		}
		if($data['Technology']['tree'] == "tree_3") {
			$treeDisplay = $this->getTree3Display($user);
			$tree = "tree_3";
		}
		if($data['Technology']['tree'] == "tree_4") {
			$treeDisplay = $this->getTree4Display($user);
			$tree = "tree_4";
		}
		if($data['Technology']['tree'] == "racial") {
			$treeDisplay = $this->getRacialDisplay($user);
			$tree = "racial";
		}
		if($data['Technology']['tree'] == "blade") {
			$treeDisplay = $this->getBladeDisplay($user);
			$tree = "blade";
		}
		if($data['Technology']['tree'] == "light_armor") {
			$treeDisplay = $this->getLightArmorDisplay($user);
			$tree = "light_armor";
		}
		if($data['Technology']['tree'] == "heavy_armor") {
			$treeDisplay = $this->getHeavyArmorDisplay($user);
			$tree = "heavy_armor";
		}
		if($data['Technology']['tree'] == "range") {
			$treeDisplay = $this->getRangeDisplay($user);
			$tree = "range";
		}
		$user = $this->decreaseTechForDisplay($user);
		if($treeDisplay['cost'] <= $user['User']['Resource']['gold']) {
			$user['User']['Resource']['gold'] -= $treeDisplay['cost'];
			$user['Technology']['queue'] = $tree;
			$user['Technology']['queue_time'] = $treeDisplay['time'];
			
			$this->id = $user['Technology']['id'];
			$this->save($user);
			$this->User->Resource->id = $user['User']['Resource']['id'];
			$this->User->Resource->save($user['User']['Resource']);
			return $treeDisplay;
		}
		
		return false;
	}
	
	function getCurrentResearch($user = null) {
		
		if($user['Technology']['research'] == "tree_1")
			$treeDisplay = $this->getTree1Display($user);
		if($user['Technology']['research']  == "tree_2")
			$treeDisplay = $this->getTree2Display($user);
		if($user['Technology']['research']  == "tree_3")
			$treeDisplay = $this->getTree3Display($user);
		if($user['Technology']['research']  == "tree_4")
			$treeDisplay = $this->getTree4Display($user);
		if($user['Technology']['research']  == "racial")
			$treeDisplay = $this->getRacialDisplay($user);
		if($user['Technology']['research']  == "blade")
			$treeDisplay = $this->getBladeDisplay($user);
		if($user['Technology']['research']  == "light_armor")
			$treeDisplay = $this->getLightArmorDisplay($user);
		if($user['Technology']['research']  == "heavy_armor")
			$treeDisplay = $this->getHeavyArmorDisplay($user);
		if($user['Technology']['research']  == "range")
			$treeDisplay = $this->getRangeDisplay($user);
		
		if(isset($treeDisplay)) {
			$currentResearch['name'] = $treeDisplay['name'];
			$currentResearch['time'] = $user['Technology']['time'];
		}
		else
			$currentResearch = False;
		
		
		return $currentResearch;
	}
	
	function getQueuedResearch($user = null) {
		$user = $this->increaseTechForDisplay($user);
		
		if($user['Technology']['queue'] == "tree_1")
			$treeDisplay = $this->getTree1Display($user);
		if($user['Technology']['queue']  == "tree_2")
			$treeDisplay = $this->getTree2Display($user);
		if($user['Technology']['queue']  == "tree_3")
			$treeDisplay = $this->getTree3Display($user);
		if($user['Technology']['queue']  == "tree_4")
			$treeDisplay = $this->getTree4Display($user);
		if($user['Technology']['queue']  == "racial")
			$treeDisplay = $this->getRacialDisplay($user);
		if($user['Technology']['queue']  == "blade")
			$treeDisplay = $this->getBladeDisplay($user);
		if($user['Technology']['queue']  == "light_armor")
			$treeDisplay = $this->getLightArmorDisplay($user);
		if($user['Technology']['queue']  == "heavy_armor")
			$treeDisplay = $this->getHeavyArmorDisplay($user);
		if($user['Technology']['queue']  == "range")
			$treeDisplay = $this->getRangeDisplay($user);
		
		if(isset($treeDisplay)) {
			$queuedResearch['name'] = $treeDisplay['name'];
			$queuedResearch['time'] = $user['Technology']['time'];
		}
		else
			$queuedResearch = False;
		
		
		return $queuedResearch;
	}
	
	function increaseTechForDisplay($user = null) {
		if($user['Technology']['research'] == "tree_1")
			$user['Technology']['tree_1']++;
		if($user['Technology']['research']  == "tree_2")
			$user['Technology']['tree_2']++;
		if($user['Technology']['research']  == "tree_3")
			$user['Technology']['tree_3']++;
		if($user['Technology']['research']  == "tree_4")
			$user['Technology']['tree_4']++;
		if($user['Technology']['research']  == "racial")
			$user['Technology']['racial']++;
		if($user['Technology']['research']  == "blade")
			$user['Technology']['blade']++;
		if($user['Technology']['research']  == "light_armor")
			$user['Technology']['light_armor']++;
		if($user['Technology']['research']  == "heavy_armor")
			$user['Technology']['heavy_armor']++;
		if($user['Technology']['research']  == "range")
			$user['Technology']['range']++;
			
		return $user;
	}
	
	function decreaseTechForDisplay($user = null) {
		if($user['Technology']['research'] == "tree_1")
			$user['Technology']['tree_1']--;
		if($user['Technology']['research']  == "tree_2")
			$user['Technology']['tree_2']--;
		if($user['Technology']['research']  == "tree_3")
			$user['Technology']['tree_3']--;
		if($user['Technology']['research']  == "tree_4")
			$user['Technology']['tree_4']--;
		if($user['Technology']['research']  == "racial")
			$user['Technology']['racial']--;
		if($user['Technology']['research']  == "blade")
			$user['Technology']['blade']--;
		if($user['Technology']['research']  == "light_armor")
			$user['Technology']['light_armor']--;
		if($user['Technology']['research']  == "heavy_armor")
			$user['Technology']['heavy_armor']--;
		if($user['Technology']['research']  == "range")
			$user['Technology']['range']--;
			
		return $user;
	}

}





?>