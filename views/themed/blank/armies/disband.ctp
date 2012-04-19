<div class="instructions"><h3>Instructions</h3>The military manager allows you to train the different units available to you, as well as wizards.
 Each unit has different strengths and weaknesses, so be sure to read <a href="http://www.provokeone.com/ctagamewiki/index.php?title=Units_everything_you_need_to_know.">the Guide</a> for more information. </div>
<div style="color:red"><b>You are disbanding troops on this page. To train troops instead, <a href="/armies">click here</a>.</b></div>
<?php echo "Available Soldiers: " . number_format($user['User']['Population']['training'])?><br />
<?php echo "$weapons[0]: " . number_format($user['User']['Weapon']['vehicle']) ?> | 
<?php echo "$weapons[1]: " . number_format($user['User']['Weapon']['big_melee']) ?> | 
<?php echo "$weapons[2]: " . number_format($user['User']['Weapon']['small_melee']) ?> | 
<?php echo "$weapons[3]: " . number_format($user['User']['Weapon']['big_armor']) ?> | 
<?php echo "$weapons[4]: " . number_format($user['User']['Weapon']['small_armor']) ?> | 
<?php echo "$weapons[5]: " . number_format($user['User']['Weapon']['range']) ?>
<br />
<br />
<form id="ArmyTrain" method="post" action="/armies/disband"><center>
<table>
  <tr>
    <th><center>Unit</center></th>
    <th><center>Price</center></th>
    <th><center>Trained</center></th>
    <th><center>Per Hour</center></th>
    <th><center>Training</center></th>
    <th><center>To Disband</center></th>
  </tr>
  <tr>
    <td><center><a href="/guides/units#<?php echo $user['User']['race']?>"><?php echo $units[0] ?></a></center></td>
    <td><center><?php 
    for($i = 0; $i < 7; $i++) {
    	if($price[0][$i] > 0) {
    		if($i < 6)
    			echo "".number_format($price[0][$i]) ." $weapons[$i], ";
    		else 
    			echo "".number_format($price[0][$i]) ." $weapons[$i]";
    	}
    }
    ?></center></td>
    <td><center><?php echo number_format($user['Army']['weak']) ?></center></td>
    <td><center><?php echo number_format($unitsTrained['Army']['weak']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['weak_training']) ?></center></td>
    <td><center><input name="data[Army][weak]" type="text" size="4" value="" id="ArmyWeak" /></center></td>
  </tr>
  <tr>
    <td><center><a href="/guides/units#<?php echo $user['User']['race']?>"><?php echo $units[1] ?></a></center></td>
    <td><center><?php 
    for($i = 0; $i < 7; $i++) {
    	if($price[1][$i] > 0) {
    		if($i < 6)
    			echo "".number_format($price[1][$i]) ." $weapons[$i], ";
    		else 
    			echo "".number_format($price[1][$i]) ." $weapons[$i]";
    	}
    }
    ?></center></td>
    <td><center><?php echo number_format($user['Army']['standard']) ?></center></td>
    <td><center><?php echo number_format($unitsTrained['Army']['standard']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['standard_training']) ?></center></td>
    <td><center><input name="data[Army][standard]" type="text" size="4" value="" id="ArmyStandard" /></center></td>
  </tr>
  <tr>
    <td><center><a href="/guides/units#<?php echo $user['User']['race']?>"><?php echo $units[2] ?></a></center></td>
    <td><center><?php 
    for($i = 0; $i < 7; $i++) {
    	if($price[2][$i] > 0) {
    		if($i < 6)
    			echo "".number_format($price[2][$i]) ." $weapons[$i], ";
    		else 
    			echo "".number_format($price[2][$i]) ." $weapons[$i]";
    	}
    }
    ?></center></td>
    <td><center><?php echo number_format($user['Army']['range']) ?></center></td>
    <td><center><?php echo number_format($unitsTrained['Army']['range']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['range_training']) ?></center></td>
    <td><center><input name="data[Army][range]" type="text" size="4" value="" id="ArmyRange" /></center></td>
  </tr>
  <tr>
    <td><center><a href="/guides/units#<?php echo $user['User']['race']?>"><?php echo $units[3] ?></a></center></td>
    <td><center><?php 
    for($i = 0; $i < 7; $i++) {
    	if($price[3][$i] > 0) {
    		if($i < 6)
    			echo "".number_format($price[3][$i]) ." $weapons[$i], ";
    		else 
    			echo "".number_format($price[3][$i]) ." $weapons[$i]";
    	}
    }
    ?></center></td>
    <td><center><?php echo number_format($user['Army']['mount']) ?></center></td>
    <td><center><?php echo number_format($unitsTrained['Army']['mount']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['mount_training']) ?></center></td>
    <td><center><input name="data[Army][mount]" type="text" size="4" value="" id="ArmyMount" /></center></td>
  </tr>
  <tr>
    <td><center><a href="/guides/units#<?php echo $user['User']['race']?>"><?php echo $units[4] ?></a></center></td>
    <td><center><?php 
    for($i = 0; $i < 7; $i++) {
    	if($price[4][$i] > 0) {
    		if($i < 6)
    			echo "".number_format($price[4][$i]) ." $weapons[$i], ";
    		else 
    			echo "".number_format($price[4][$i]) ." $weapons[$i]";
    	}
    }
    ?></center></td>
    <td><center><?php echo number_format($user['Army']['small_elite']) ?></center></td>
    <td><center><?php echo number_format($unitsTrained['Army']['small_elite']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['small_elite_training']) ?></center></td>
    <td><center><input name="data[Army][small_elite]" type="text" size="4" value="" id="ArmySmallElite" /></center></td>
  </tr>
  <tr>
    <td><center><a href="/guides/units#<?php echo $user['User']['race']?>"><?php echo $units[5] ?></a></center></td>
    <td><center><?php 
    for($i = 0; $i < 7; $i++) {
    	if($price[5][$i] > 0) {
    		if($i < 6)
    			echo "".number_format($price[5][$i]) ." $weapons[$i], ";
    		else 
    			echo "".number_format($price[5][$i]) ." $weapons[$i]";
    	}
    }
    ?></center></td>
    <td><center><?php echo number_format($user['Army']['big_elite']) ?></center></td>
    <td><center><?php echo number_format($unitsTrained['Army']['big_elite']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['big_elite_training']) ?></center></td>
    <td><center><input name="data[Army][big_elite]" type="text" size="4" value="" id="ArmyBigElite" /></center></td>
  </tr>
  <tr>
  	<td><center><a href="/guides/magic"><?php echo "Wizard" ?></a></center></td>
	<td><center><?php echo "".$wizardPrice ." Gold " ?></center></td>
	<td><center><?php echo number_format($wizards) ?></center></td>
	<td><center><?php echo number_format($unitsTrained['Army']['wizard']) ?></center></td>
	<td><center><?php echo number_format($user['Army']['wizard_training']) ?></center></td>
	<td><center><input name="data[Army][wizard]" type="text" size="4" value="" id="ArmyWizard" /></center></td>
  </tr>
  <tr>
    <td colspan="4"><div align="right"><?php echo "Available wizard housing: " . number_format($availableWizardHousing) ?> | <?php echo "Available army housing: " . number_format($availableArmyHousing) ?></div></td>
    <td><center><a href="/armies">Train</a></center></td>
    <td><center><input type="submit" value="Disband" /><?php //echo $form->end('Train'); ?></center></td>
  </tr>
</table>
</form>
<b>To change your strategy for battle, <a href="/strategies">meet with your generals</a><br /> to tell them how you'd like your army to fight!</b>
<br /><br />
