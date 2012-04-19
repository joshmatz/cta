<fb:google-analytics uacct="UA-6111920-4"/> 
<link rel="stylesheet" type="text/css" media="screen" href="http://www.provokeone.com/sd_cta/css/ctagame.css?v=2.5" />
<?php if(!$user['User']['premium']) { ?>
<center>
<!--   <fb:iframe src="http://www.adparlor.com/serveIframeAd.aspx?appId=7269322&adType=8" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" height="90" width="728"></fb:iframe>-->
<fb:iframe src='http://rya.rockyou.com/ams/ad.php?placeguid=41CBC40056&type=Leaderboard&title_color=006699' style='border:0px;' width='728' height='90' scrolling='no' frameborder='0'/>
</center> 
<?php } ?>
<div class="main_container">

	<div class="logo"><a href="/sd_cta/premiums"><?php echo $html->image("http://www.provokeone.com/sd_cta/img/logo.png", array("alt" => "Call to Arms", "style" => "float:left;"))?></a></div>
	<div class="kingdomInfo"><b>Ruler:</b> <?php echo $user['User']['ruler'] ?> | <b>Kingdom Name:</b> <?php echo $user['User']['kingdom'] ?> | <b>Race:</b> <?php echo $user['User']['race'] ?> | </div>
	<div class="kingdomInfo2"><b>Province:</b> <?php echo "" . $user['User']['province']. " " . $user['User']['province_number'] ?> | <b>Kingdom Strength:</b> <?php echo number_format($user['User']['score']) ?></div>
	<div class="navBar">
		<div class="home"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/home_off.png", array("alt" => "Home")), "/", array('escape'=>false)); ?></div>
		<div class="buildings"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/building_off.png", array("alt" => "Building")), "/buildings/", array('escape'=>false)); ?></div>
		<div class="population"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/population_off.png", array("alt" => "Population")), "/populations/", array('escape'=>false)); ?></div>
		<div class="forge"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/forge_off.png", array("alt" => "Forge")), "/weapons/", array('escape'=>false)); ?></div>
		<div class="military"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/military_off.png", array("alt" => "Military")), "/armies/", array('escape'=>false)); ?></div>
		<div class="attack"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/attack_off.png", array("alt" => "Attack")), "/attacks/", array('escape'=>false)); ?></div>
		<div class="spell"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/spells_off.png", array("alt" => "Spells")), "/spells/", array('escape'=>false)); ?></div>
		<div class="strategy"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/strategy_off.png", array("alt" => "Strategy")), "/strategies/", array('escape'=>false)); ?></div>
		<div class="province"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/province_off.png", array("alt" => "Province")), "/provinces/", array('escape'=>false)); ?></div>
		<div class="rankings"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/rankings_off.png", array("alt" => "Rankings")), "/leaderboards/", array('escape'=>false)); ?></div>
		<div class="technology"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/technology_off.png", array("alt" => "Technology")), "/technologies/", array('escape'=>false)); ?></div>
		<?php if($user['User']['premium'] && $newMessages) { ?>
		<div class="mailbox"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/mailbox_new.png", array("alt" => "Mailbox")), "/messages/", array('escape'=>false)); ?></div>
		<?php } else { ?>
		<div class="mailbox"><?php echo $html->link($html->image("http://www.provokeone.com/sd_cta/img/mailbox_off.png", array("alt" => "Mailbox")), "/messages/", array('escape'=>false)); ?></div>
		<?php } ?>
	</div>
	<div class="resources">
		<b><?php echo $html->image("http://www.provokeone.com/sd_cta/img/land.png", array("alt" => "Land", "height" => 18)) ?>Land:</b> <?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['land']); else echo number_format($user['User']['Resource']['land']) ?> | 
		<b><?php echo $html->image("http://www.provokeone.com/sd_cta/img/gold.png", array("alt" => "Gold", "height" => 18)) ?>Gold:</b> <?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['gold']); else echo number_format($user['User']['Resource']['gold']) ?> | 
		<b><?php echo $html->image("http://www.provokeone.com/sd_cta/img/food.png", array("alt" => "Food", "height" => 18)) ?>Food:</b> <?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['food']); else echo number_format($user['User']['Resource']['food']) ?> | 
		<b><?php echo $html->image("http://www.provokeone.com/sd_cta/img/iron.png", array("alt" => "Iron", "height" => 18)) ?>Iron:</b> <?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['iron']); else echo number_format($user['User']['Resource']['iron']) ?> | 
		<b><?php echo $html->image("http://www.provokeone.com/sd_cta/img/wood.png", array("alt" => "Wood", "height" => 18)) ?>Wood:</b> <?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['wood']); else echo number_format($user['User']['Resource']['wood']) ?> |
		<a href="/sd_cta/premiums"><?php echo $html->image("http://www.provokeone.com/sd_cta/img/premium_points.png", array("alt" => "Premium Points", "height" => 18)) ?><b>PP:</b> </a><?php echo number_format($user['User']['premium_points']); ?>
	</div>

	<div class="content_container">
		<div class="flash"><b><?php $session->flash() ?></b></div>
		<?php echo $content_for_layout ?>	
		<?php if(!$user['User']['premium']) { ?><div class="premium"> You're using a non-premium account. To get rid of ads and receive other benefits, <a href="/sd_cta/premiums/">upgrade today</a>!</div> <br /><br /><?php } ?>
	</div>  <center>
	<?php if(!$user['User']['premium']) { ?>
<!-- RockYou: --><fb:iframe src='http://rya.rockyou.com/ams/ad.php?placeguid=18D3539962&type=Leaderboard&bg_color=000000&body_color=FFFFCC' style='border:0px;' width='728' height='90' scrolling='no' frameborder='0'/>
<!-- Cubics:  <fb:iframe src='http://social.bidsystem.com/displayAd.aspx?pid=358643&plid=15965&adSize=468x60&bgColor=%23000000&textColor=%23eeeeee&linkColor=%23888888&channel=&appid=116612&pfid=' width='468' height='60' frameborder='0' border='0' scrolling='no'></fb:iframe> --><?php } ?>
	 <a href="/sd_cta/policies/privacy">Privacy Policy</a> | <a href="/sd_cta/policies/terms">Terms of Service</a> | <a href="http://www.provokeone.com/ctagameforum">Forum</a></center>
</div>
