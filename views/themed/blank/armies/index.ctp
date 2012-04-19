<div class="instructions"><h3>Instructions</h3>The military manager allows you to train the different units available to you, as well as wizards.
 Each unit has different strengths and weaknesses, so be sure to read <a href="http://www.provokeone.com/ctagamewiki/index.php?title=Units_everything_you_need_to_know.">the Guide</a> for more information. </div>
<?php echo "Available Soldiers: " . number_format($user['User']['Population']['training'])?><br />
<?php echo "$weapons[0]: " . number_format($user['User']['Weapon']['vehicle']) ?> | 
<?php echo "$weapons[1]: " . number_format($user['User']['Weapon']['big_melee']) ?> | 
<?php echo "$weapons[2]: " . number_format($user['User']['Weapon']['small_melee']) ?> | 
<?php echo "$weapons[3]: " . number_format($user['User']['Weapon']['big_armor']) ?> | 
<?php echo "$weapons[4]: " . number_format($user['User']['Weapon']['small_armor']) ?> | 
<?php echo "$weapons[5]: " . number_format($user['User']['Weapon']['range']) ?>
<br />
<br />
<form id="ArmyTrain" method="post" action="/armies/train"><center>
<table>
  <tr>
    <th><center>Unit</center></th>
    <th><center>Price</center></th>
    <th><center>Trained</center></th>
    <th><center>Healing / Per Tick</center></th>
    <th><center>Training / Per Tick</center></th>
    <th><center>To Train</center></th>
  </tr>
  <tr>
    <td><center><div class="tips"><span class="tip" title="<?php echo $unitInfo[0] ?>"><?php echo $units[0] ?></span></div></center></td>
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
    <td><center><?php echo number_format($centerUnits['Center']['weak']) . " / " . number_format($healUnits['Army']['weak'] - $user['Army']['weak']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['weak_training']) . " / " . number_format($unitsTrained['Army']['weak']) ?></center></td>
    <td><center><input name="data[Army][weak]" type="text" size="4" value="" id="ArmyWeak" /></center></td>
  </tr>
  <tr>
    <td><center><div class="tips"><span class="tip" title="<?php echo $unitInfo[1] ?>"><?php echo $units[1] ?></span></div></center></td>
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
    <td><center><?php echo number_format($centerUnits['Center']['standard']) . " / " . number_format($healUnits['Army']['standard'] - $user['Army']['standard']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['standard_training']) . " / " . number_format($unitsTrained['Army']['standard'])?></center></td>
    <td><center><input name="data[Army][standard]" type="text" size="4" value="" id="ArmyStandard" /></center></td>
  </tr>
  <tr>
    <td><center><div class="tips"><span class="tip" title="<?php echo $unitInfo[2] ?>"><?php echo $units[2] ?></span></div></center></td>
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
    <td><center><?php echo number_format($centerUnits['Center']['range']) . " / " . number_format($healUnits['Army']['range'] - $user['Army']['range']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['range_training']) . " / " . number_format($unitsTrained['Army']['range'])?></center></td>
    <td><center><input name="data[Army][range]" type="text" size="4" value="" id="ArmyRange" /></center></td>
  </tr>
  <tr>
    <td><center><div class="tips"><span class="tip" title="<?php echo $unitInfo[3] ?>"><?php echo $units[3] ?></span></div></center></td>
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
    <td><center><?php echo number_format($centerUnits['Center']['mount']) . " / " . number_format($healUnits['Army']['mount'] - $user['Army']['mount']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['mount_training']) . " / " . number_format($unitsTrained['Army']['mount'])?></center></td>
    <td><center><input name="data[Army][mount]" type="text" size="4" value="" id="ArmyMount" /></center></td>
  </tr>
  <tr>
    <td><center><div class="tips"><span class="tip" title="<?php echo $unitInfo[4] ?>"><?php echo $units[4] ?></span></div></center></td>
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
    <td><center><?php echo number_format($centerUnits['Center']['small_elite']) . " / " . number_format($healUnits['Army']['small_elite'] - $user['Army']['small_elite']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['small_elite_training']) . " / " . number_format($unitsTrained['Army']['small_elite'])?></center></td>
    <td><center><input name="data[Army][small_elite]" type="text" size="4" value="" id="ArmySmallElite" /></center></td>
  </tr>
  <tr>
    <td><center><div class="tips"><span class="tip" title="<?php echo $unitInfo[5] ?>"><?php echo $units[5] ?></span></div></center></td>
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
    <td><center><?php echo number_format($centerUnits['Center']['big_elite']) . " / " . number_format($healUnits['Army']['big_elite'] - $user['Army']['big_elite']) ?></center></td>
    <td><center><?php echo number_format($user['Army']['big_elite_training']) . " / " . number_format($unitsTrained['Army']['big_elite']) ?></center></td>
    <td><center><input name="data[Army][big_elite]" type="text" size="4" value="" id="ArmyBigElite" /></center></td>
  </tr>
  <tr>
  	<td><center><div class="tips"><span class="tip" title="<?php echo $unitInfo[6] ?>">Wizard</span></div></center></td>
	<td><center><?php echo "".$wizardPrice ." Gold " ?></center></td>
	<td><center><?php echo number_format($wizards) ?></center></td>
	<td><center>0 / 0</center></td>
	<td><center><?php echo number_format($user['Army']['wizard_training']) . " / " . number_format($unitsTrained['Army']['wizard']) ?></center></td>
	<td><center><input name="data[Army][wizard]" type="text" size="4" value="" id="ArmyWizard" /></center></td>
  </tr>
  <tr>
    <td colspan="4"><div align="right"><?php echo "Available wizard housing: " . number_format($availableWizardHousing) ?> | <?php echo "Available army housing: " . number_format($availableArmyHousing) ?></div></td>
	<td><center><a href="/armies/disband">Disband</a></center></td>
    <td><center><input type="submit" value="Train" /><?php //echo $form->end('Train'); ?></center></td>
  </tr>
</table>
</form>
