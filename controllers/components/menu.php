<?php 
class Menu extends Object {
	var $name = 'Menu';
	var $menu = array();
		
	function create ($selected = 'home') {
		// create regular menu array
	}
	
	function addChild() {
		// add child menu array to anything
	}
	
	// create helper, convert to plugin
	// inside helper use a recursive function to output menu
	// output() { output one, if children, call output, else done }
	
	// We can call the set menu method in afterFilter()
	
	// We can create menus in beforeFilter based on the current controller. Pages == default
}
?>