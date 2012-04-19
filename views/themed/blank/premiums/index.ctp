<div class="guide">
You currently have <?php echo $points ?> Premium Points.<br /> <br />

You can use Premium Points to upgrade your account to a Premium Account, <br />
change your race, change your name, change your province, and much more. <br /> <br />

If you need more Premium Points, visit <a href="/premiums/buy">this page to obtain more</a>.<br /> <br />
<?php if($beta = false) { echo "During beta, using premium points is disabled.";
 } else { ?>
<center><h1>Premium Accounts</h1></center>
<?php echo $html->link('Redeem 15 points for 1 month of premium.', "/premiums/buyMonths/1") ?> <br />
<?php echo $html->link('Redeem 30 points for 2 months of premium.', "/premiums/buyMonths/2") ?> <br />
<?php echo $html->link('Redeem 60 points for 4 months of premium.', "/premiums/buyMonths/4") ?> <br />
<?php echo $html->link('Redeem 150 points for 10 months of premium.', "/premiums/buyMonths/10") ?> <br /><br />
* Premium accounts give you: Extended information on the Statistics page. Buildings ratio manager.
Enables a spell scheduler. Uninterrupted same-IP gameplay. No advertisements.<br /><br /><hr style="width:300px;height:1px;border:0;background-color:#DDDDDD"/><br />

<center><h1>Race Change</h1></center>
<?php echo $html->link('Redeem ' . $changePrice . ' points to change your race to Dwarf.', "/premiums/changeRace/Dwarf") ?> <br />
<?php echo $html->link('Redeem ' . $changePrice . ' points to change your race to Elf.', "/premiums/changeRace/Elf") ?> <br />
<?php echo $html->link('Redeem ' . $changePrice . ' points to change your race to Human.', "/premiums/changeRace/Human") ?> <br />
<?php echo $html->link('Redeem ' . $changePrice . ' points to change your race to Orc.', "/premiums/changeRace/Orc") ?> <br />
<?php echo $html->link('Redeem ' . $changePrice . ' points to change your race to Gnome.', "/premiums/changeRace/Gnome") ?> <br />
* Changing your race will reset your unit and weapons (including those training and building) to 0, so as not to give an advantage to players switching from races with inexpensive units.<br /><br /> <hr style="width:300px;height:1px;border:0;background-color:#DDDDDD"/><br />

<center><h1>Name Change</h1></center>
<?php echo $html->link('Redeem 20 points to change your kingdom and ruler names.', "/premiums/changeName") ?> <br />
<br />
<hr style="width:300px;height:1px;border:0;background-color:#DDDDDD"/><br />

</div>
<!-- 
<?php if(isset($birthday) && isset($sex)) { ?>
<center><iframe src="http://www.offers.ads.rockyou.com/userGreeting.php?userId=<?php echo $endUserId . "-" . "2620" . "-" . strtolower(substr(md5($endUserId . 2620 . "201234dfb56242238867930c79c1702c"), 0, 10 ));?>&dob=<?php echo $birthday?>&sex=<?php echo $sex?>" width="650px" height="1230px" frameborder="0" scrolling="no"></iframe></center>
<?php } elseif(isset($birtday)) { ?>
<center><iframe src="http://www.offers.ads.rockyou.com/userGreeting.php?userId=<?php echo $endUserId . "-" . "2620" . "-" . strtolower(substr(md5($endUserId . 2620 . "201234dfb56242238867930c79c1702c"), 0, 10 ));?>&dob=<?php echo $birthday?>" width="650px" height="1230px" frameborder="0" scrolling="no"></iframe></center>
<?php } elseif(isset($sex)) { ?>
<center><iframe src="http://www.offers.ads.rockyou.com/userGreeting.php?userId=<?php echo $endUserId . "-" . "2620" . "-" . strtolower(substr(md5($endUserId . 2620 . "201234dfb56242238867930c79c1702c"), 0, 10 ));?>&sex=<?php echo $sex?>" width="650px" height="1230px" frameborder="0" scrolling="no"></iframe></center>
<?php } else { ?>
<center><iframe src="http://www.offers.ads.rockyou.com/userGreeting.php?userId=<?php echo $endUserId . "-" . "2620" . "-" . strtolower(substr(md5($endUserId . 2620 . "201234dfb56242238867930c79c1702c"), 0, 10 ));?>&dob=<?php echo $birthday?>" width="650px" height="1230px" frameborder="0" scrolling="no"></iframe></center>
<?php } ?>
-->
<?php }	?>