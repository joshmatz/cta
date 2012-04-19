<section id="account-details">
	<h3 class="section-title">Account Details</h3>
	<h4 class="subsection-title">Account Name</h4>
	<?php echo $facebook->picture($account['Account']['facebook_id'], array('size' => 'thumb', 'class' => 'facebook-account')); ?>
	<span class="account-name"><?php echo $account['Account']['username']; ?></span> 
</section>

<section id="account-games">
	<h3 class="section-title">Game Accounts</h3>
	<?php //  ?>
	<div class="games-list">
		<?php $category ="";
		foreach($registeredGames as $game): ?>
			<?php 
			$game_id = $game['RegisteredGame']['game_id'];
			
			if($ctagames[$game_id]['category'] != $category): $category = "CTA";?>
				<a href="#" class="game-title"><?php echo $ctagames[$game_id]['name'] ?></a>
				<ul id="game-<?php echo $ctagames[$game_id]['short'] ?>">
			<?php 
			endif; 
			if($category == "CTA"):
			?>
				<li class="" id="<?php echo $ctagames[$game_id]['short'] . $ctagames[$game_id]['server_short']; ?>"><?php echo $ctagames[$game_id]['server'] ?></li>
			<?php 
			endif;
			if($ctagames[$game_id]['category'] != $category): ?>
				</ul>
			<?php 
			endif; ?>
			
			<?php 
			/*
			 * Repeat this for each game in P1
			 */ 			
			if($ctagames[$game_id]['category'] != $category): $category = "RPG";?>
				<a href="#" class="game-title"><?php echo $ctagames[$game_id]['name'] ?></a>
				<ul id="game-<?php echo $ctagames[$game_id]['short'] ?>">
			<?php 
			endif; 
			if($category == "RPG"):
			?>
				<li class="" id="<?php echo $ctagames[$game_id]['short'] . $ctagames[$game_id]['server_short']; ?>"><?php echo $ctagames[$game_id]['server'] ?></li>
			<?php 
			endif;
			if($ctagames[$game_id]['category'] != $category): ?>
				</ul>
			<?php 
			endif; ?>

		<?php 
		endforeach; ?>
	</div>
</section>

<div style="clear:both;"></div>