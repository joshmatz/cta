<fb:google-analytics uacct="UA-6111920-4"/> 
<link rel="stylesheet" type="text/css" media="screen" href="/sd_cta/css/ctagame.css?v=1.0" />
<div class="main_container">

	<div class="logo"><?php echo $html->image("/img/logo_beta.png", array("alt" => "Call to Arms", "style" => "float:left;"))?></div>
	<div class="navBar">
		<div class="home"><?php echo $html->link($html->image("/img/home_off.png", array("alt" => "Home")), "/", array('escape'=>false)); ?></div>
		<div class="buildings"><?php echo $html->link($html->image("/img/building_off.png", array("alt" => "Building")), "/buildings/", array('escape'=>false)); ?></div>
		<div class="population"><?php echo $html->link($html->image("/img/population_off.png", array("alt" => "Population")), "/populations/", array('escape'=>false)); ?></div>
		<div class="forge"><?php echo $html->link($html->image("/img/forge_off.png", array("alt" => "Forge")), "/weapons/", array('escape'=>false)); ?></div>
		<div class="military"><?php echo $html->link($html->image("/img/military_off.png", array("alt" => "Military")), "/armies/", array('escape'=>false)); ?></div>
		<div class="attack"><?php echo $html->link($html->image("/img/attack_off.png", array("alt" => "Attack")), "/attacks/", array('escape'=>false)); ?></div>
		<div class="spell"><?php echo $html->link($html->image("/img/spells_off.png", array("alt" => "Spells")), "/spells/", array('escape'=>false)); ?></div>
		<div class="strategy"><?php echo $html->link($html->image("/img/strategy_off.png", array("alt" => "Strategy")), "/strategies/", array('escape'=>false)); ?></div>
		<div class="province"><?php echo $html->link($html->image("/img/province_off.png", array("alt" => "Province")), "/provinces/", array('escape'=>false)); ?></div>
		<div class="rankings"><?php echo $html->link($html->image("/img/rankings_off.png", array("alt" => "Rankings")), "/leaderboards/", array('escape'=>false)); ?></div>
		<div class="technology"><?php echo $html->link($html->image("/img/technology_off.png", array("alt" => "Technology")), "/technologies/", array('escape'=>false)); ?></div>
	</div>

	<div class="restart_container">
		<div class="flash"><b><?php $session->flash() ?></b></div>
		<?php echo $content_for_layout ?>	
	</div>  
</div>