<div class="instructions"><h3>Instructions</h3>Queue weapons to be crafted here.  <a href="/populations/">Set population</a> to craft them.</div>
<h3>Forge Deconstructor</h3>
<div style="color:red"><b>You are deconstrucing weapons on this page. To craft weapons instead, <a href="/weapons">click here</a>.</b></div>
<form id="WeaponBuild" method="post" action="/weapons/destroy">
<table>
  <tr>
    <th><center>Weapon </center></th>
    <th><center>Built</center></th>
    <th><center>Building</center></th>
    <th><center>To Destroy</center></th>
  </tr>
  <tr>
    <td><center><?php echo $weapons[0] ?></center></td>
    <td><center><?php echo $user['Weapon']['vehicle'] ?></center></td>
    <td><center><?php echo $user['Weapon']['vehicle_building'] ?></center></td>
    <td><center><input name="data[Weapon][vehicle_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[1] ?></center></td>
    <td><center><?php echo $user['Weapon']['big_melee'] ?></center></td>
    <td><center><?php echo $user['Weapon']['big_melee_building'] ?></center></td>
    <td><center><input name="data[Weapon][big_melee_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[2] ?></center></td>
    <td><center><?php echo $user['Weapon']['small_melee'] ?></center></td>
    <td><center><?php echo $user['Weapon']['small_melee_building'] ?></center></td>
    <td><center><input name="data[Weapon][small_melee_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[5] ?></center></td>
    <td><center><?php echo $user['Weapon']['range'] ?></center></td>
    <td><center><?php echo $user['Weapon']['range_building'] ?></center></td>
    <td><center><input name="data[Weapon][range_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[3] ?></center></td>
    <td><center><?php echo $user['Weapon']['big_armor'] ?></center></td>
    <td><center><?php echo $user['Weapon']['big_armor_building'] ?></center></td>
    <td><center><input name="data[Weapon][big_armor_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[4] ?></center></td>
    <td><center><?php echo $user['Weapon']['small_armor'] ?></center></td>
    <td><center><?php echo $user['Weapon']['small_armor_building'] ?></center></td>
    <td><center><input name="data[Weapon][small_armor_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center>&nbsp;</center></td>
    <td><center></center></td>
    <td><center><a href="/weapons/index<?php echo $parms ?>">Craft Weapons</a></center></td>
    <td><center> <input type="submit" class="submit" value="Destroy" /><?php //echo $form->end('Build') ?></center></td>
  </tr>
</table>
</form>