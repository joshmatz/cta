<?php
class GameRace extends AppModel {
	var $name = 'GameRace';
	var $useTable = false; 
	
	
	/*
	 * Summary:     Return every race and details whe needed.
	 * Parameters:  none
	 * Return:      Array of races and their details
	 * Author:      Josh Matz
	 */
	function getRaces() {
		return array(
			array(
				'name' => 'All',
				'description' => 'Use this race when an object (building, weapon, unit) belongs to every race.'
			),
			array(
				'name' => 'Human',
				'description' => 'Use this race when an object (building, weapon, unit) belongs to every race.'
			),
			array(
				'name' => 'Elf',
				'description' => 'Use this race when an object (building, weapon, unit) belongs to every race.'
			),
			array(
				'name' => 'Gnome',
				'description' => 'Use this race when an object (building, weapon, unit) belongs to every race.'
			),
			array(
				'name' => 'Dwarf',
				'description' => 'Use this race when an object (building, weapon, unit) belongs to every race.'
			),
			array(
				'name' => 'Orc',
				'description' => 'Use this race when an object (building, weapon, unit) belongs to every race.'
			),
		);
	}

}
?>
