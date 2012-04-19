<link rel="stylesheet" type="text/css" media="screen" href="/css/producers.css?v=8.4" />

<div class="information"><center><h3>Buildings</h3></center>
The next output of your buildings will be available for collection in <?php // timer can probably be done: current time modulus 60*60, subtract difference, add difference to time and count down to that time ?><br />
Each building can be built multiple times and some produce a specific resource every hour. 
Once the resources are produced, they will be stored in the building's storage facility, ready to be collected by you.<br /><br /></div>
<center> 
<?php foreach($producers as $producer): ?>
	<?php /** 
			* Display each building based on user level
			*/
		if($user['User']['level'] > 0 && ($producer['info']['name'] == 'Castle' || $producer['info']['name'] == 'House')
			 || ($user['User']['level'] > 1 && ($producer['info']['name'] == 'Mill' || $producer['info']['name'] == 'Mine' || $producer['info']['name'] == 'Market'))
			 || ($user['User']['level'] > 5 && ($producer['info']['name'] == 'Forge' || $producer['info']['name'] == 'Training Center' || $producer['info']['name'] == 'School'))
			 || ($user['User']['level'] > 10 && ($producer['info']['name'] == 'School'))
			 || ($user['User']['level'] > 20 && ($producer['info']['name'] == 'Guard Tower' || $producer['info']['name'] == 'Medical Center'))) { ?>

<div class="building">
	<div class="status">You currently own <span style="color:yellow"><?php echo $plural->ize($producer['info']['img'], $producer['building']['total']) ?></span>. <?php echo $producer['info']['description'] ?> </div>
	<div class="title">
		<img src="/img/buildings/<?php echo $producer['info']['img'] ?>.png" width="150px" /><br />
		<div class="buildAnother"><a href="/producers/build/<?php echo $producer['info']['img'] ?>" style="display:block; height:100%; width: 100%;">Build another <?php echo $producer['info']['name'] ?>: <br />
			<?php if($producer['building']['price']['wood']) echo $html->image("/img/wood.png", array("alt" => "Wood", "height" => 18)) . number_format($producer['building']['price']['wood']) . ", " ?>
			<?php if($producer['building']['price']['iron']) echo $html->image("/img/iron.png", array("alt" => "Iron", "height" => 18)) . number_format($producer['building']['price']['iron']) . ", " ?><?php if($producer['info']['name'] == "Castle") echo "<br />"?>
			<?php if($producer['building']['price']['gold']) echo $html->image("/img/gold.png", array("alt" => "Gold", "height" => 18)) . number_format($producer['building']['price']['gold']) . "" ?></a>
		</div>
	</div>
	
	
	<?php /**
			* Castles, guard towers and medical centers have no production, therefore we remove the buttons from being displayed 
			*/
	if($producer['info']['name'] != 'Castle' && $producer['info']['name'] != 'Guard Tower' && $producer['info']['name'] != 'Medical Center') { ?>
		<div class="upgradeOutput">
			Upgrade output per <?php echo $producer['info']['name'] ?> to <?php echo $producer['output']['new'] ?>.<br />
			<?php if($producer['output']['price']['wood']) echo $html->image("/img/wood.png", array("alt" => "Wood", "height" => 18)) . number_format($producer['output']['price']['wood']) . ", " ?>
			<?php if($producer['output']['price']['iron']) echo $html->image("/img/iron.png", array("alt" => "Iron", "height" => 18)) . number_format($producer['output']['price']['iron']) . ", " ?>
			<?php if($producer['output']['price']['gold']) echo $html->image("/img/gold.png", array("alt" => "Gold", "height" => 18)) . number_format($producer['output']['price']['gold']) . "" ?>	
		</div>
		<div class="output">Output: <?php echo number_format($producer['output']['current']) ?> every hour</div>
		
		<div class="upgradeCapacity">
			Upgrade capacity per <?php echo $producer['info']['name']?> to <?php echo $producer['capacity']['new'] ?>. <br />
			<?php if($producer['capacity']['price']['wood']) echo $html->image("/img/wood.png", array("alt" => "Wood", "height" => 18)) . number_format($producer['capacity']['price']['wood']) . ", " ?>
			<?php if($producer['capacity']['price']['iron']) echo $html->image("/img/iron.png", array("alt" => "Iron", "height" => 18)) . number_format($producer['capacity']['price']['iron']) . ", " ?>
			<?php if($producer['capacity']['price']['gold']) echo $html->image("/img/gold.png", array("alt" => "Gold", "height" => 18)) . number_format($producer['capacity']['price']['gold']) . "" ?>
		</div>
		<div class="capacity">Capacity: <?php echo number_format($producer['capacity']['current']) ?></div>
		
		<div class="business_sell_row">
			<div class="business_progress_bar_outside">
				
				<?php 
					if($producer['info']['name'] == "House"){
						if($user['User']['Population']['total_population'] != 0){ 
							$width = (($user['User']['Population']['total_population'] / $producer['capacity']['current']) * 100);
							if($width > 100) 
								$width = 100;
						} else { 
							$width = 0; 
						}
					} else {
						if($producer['info']['saved'] != 0){ 
							$width = (($producer['info']['saved'] / $producer['capacity']['current']) * 100);
							if($width > 100) 
								$width = 100;
						} else { 
							$width = 0; 
						}
					} ?>
				<div class="business_progress_bar_inside business_progress_bar_inside_partial" style="width: <?php echo $width ?>%; background-image: url('/img/layout/bargraph.gif');">&nbsp;</div>
				<div class="business_progress_bar_current_stock" style="white-space: nowrap;">
					<?php if($producer['info']['name'] == "House") { ?>
						Peasants: <?php echo $user['User']['Population']['total_population']?>/ Capacity: <?php echo $producer['capacity']['current']?>
					<?php } else {?>
						Stored: <?php echo $producer['info']['saved']?> / Capacity: <?php echo $producer['capacity']['current']?>
					<?php } ?>
				</div>
			</div>
		</div>
			
		<?php /**
				* Collection button
				*/
			if($producer['info']['name'] == "House") { ?>
				<div class="collect">Peasants are not collectables!</div>
			<?php } else {
				if($producer['info']['saved'] != 0) { ?>
				<div class="collect">Collect your stored resources.</div>
			<?php } else { ?>
				<div class="collect">You have no resources to collect.</div>
		<?php } } ?>
			
	<?php } else { ?>
		<br /><br /><br /><br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This building requires no upgrades or collections.
	<?php } ?>
	
</div>
<?php } ?>
<?php endforeach; ?>

</center>