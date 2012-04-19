<a href="/trades/"><?php echo $html->image("/img/layout/trade.png", array("alt" => "Make a trade!", "style" => "margin: 0 auto;"))?></a>
<br />
<?php if ($user['User']['premium'] < 24 && $user['User']['premium'] > 0 ) { ?>Your premium account will expire in <?php echo $user['User']['premium']; if($user['User']['premium'] != 1) { ?> hours! <?php } else { ?> hour! <?php } ?>Redeem more <a href="/premiums">Premium Points</a> so you don't lose your advantages!<br /><br /><?php } ?>
<?php if ($user['User']['protection'] > 0){ echo "You are under protection from attack for " . $user['User']['protection'] . " ticks.<br>"; }?>

<div style="height:23px; margin-bottom:0px; margin-top:10px; margin-left: 1px; margin-right: 1px;">
	<a href="/reports/"><?php echo $html->image("/img/layout/reportstab.png", array("alt" => "Reports", "style" => "float:left;")); ?></a>
	<div class="kingdomInfo"><b>Gametime:</b> <?php echo date('j M, Y, g:i', $_SERVER['REQUEST_TIME']) ?> | <b>Ruler:</b> <?php echo $user['User']['ruler'] ?> | <?php if($user['User']['alliance_id']) { ?><b>Alliance:</b> <?php echo $alliance['Alliance']['short_name'] ?> |<?php } ?> <b>KS:</b> <?php echo number_format($user['User']['score']) ?>
	<br /><?php 
		if($newMessages == NULL) { 
		?>
		<a href="/messages">Mailbox</a>
		<?php } else { ?>
		<a href="/messages">You have unread messages!</a>
		<?php }?>
	</div>
</div>
<br />

<div style="margin-top:0px;">
<?php 
	foreach($reports as $report):
?>	
	<div class="report" <?php if($report['Report']['is_read']) {?> style="opacity:.5; filter: alpha(opacity = 50);" <?php }?>>
		<div class="date">
		<b><?php echo date('j M, Y, H:i', $report['Report']['time']) ?></b> <?php if ($user['User']['premium'] || $account['Account']['role'] == 'admin') { ?>| 
			<a href="/reports/view/<?php echo $report['Report']['id'] . "/" . substr( md5($report['Report']['id'] . '123489svk123xfjo4965oinlk1098345klj'), 0, 15 );?>">Public link</a>
			<?php } ?>
		<?php if($report['Report']['is_read'] == 0) {?><span style="color:red" id="report_<?php echo $report['Report']['id'] ?>"><b>New!</b>
		 <?php 
		 echo $this->Js->link( 
		    $html->image("/img/markread.gif", array("alt" => "Mark Read", 'width' => '30px', 'style' => 'vertical-align:middle;')), 
		    array( 'controller' => 'reports', 'action' => 'markRead', $report['Report']['id']), 
		    array( 'update' => '#report_' . $report['Report']['id'], 'escape' => false )
		); 
		?></span>
		<?php }?></div>
		<div class="reportBody">
			<?php echo $report['Report']['body'] ?>
			
		</div>
	</div>
	<div class="separator">&nbsp;</div>
<?php
	endforeach;
	if($countedReports > 5) {
?>
<div style="color:red;"><a href="/reports">You have reports that you have not read!</a></div>

<?php } ?>
</div>

<div style="clear:left; margin-top:30px;">
	<table width="250px">
		<tr>
			<th colspan="2">Support the dev!</th>
		</tr>
		<tr>
			<td width="152px"><a href="/premiums"><?php echo $html->image("/img/premium_points_button.png"); ?></a></td>
			<td><a href='/users/apex' title='MMORPGs' target="_new"><img alt='MMORPGs' title='MMORPGs' src='http://apexwebgaming.com/images/vote_button_1.gif' /></a></td>
		</tr>
		<tr>
			<td><?php echo $facebook->like(); ?></td>
			<td><!--Begin GameSites200 Vote--><p><a href="http://www.gamesites200.com/gaming/in.php?id=5638" target="_NEW"><img src="http://www.gamesites200.com/gaming/vote.gif" alt="Vote on the Gaming Top 200" border="0"></a><br><a href="http://www.gamesites200.com/gaming/"><img src="http://www.gamesites200.com/track.gif" alt="Gaming Top 200 - Online Gaming and Game Sites" border="0"></a></p><!--End Game Sites 200 Code--></td>
		</tr>
		<tr>
			<td><!-- Start GigaTopList.com Vote code --> 
<a href='http://gigatoplist.com/index.php?p=Vote&uid=1010' target='_blank' > 
<img src='http://gigatoplist.com/images/vote/gtl1.png' alt='list of games' border='0'></a> 
<!--End GigaTopList.com Vote code--> </td>
			<td><a href="http://www.gamessiteslist.com/index.php?ID=2484" target="_NEW"><img src="http://www.gamessiteslist.com/images/top_sites.gif" alt="Free Online Games" border="0"></a></td>
		</tr>
		<tr>
			<td>
				<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="11269429">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</td>
			<td></td>
		</tr>
	</table>
</div>