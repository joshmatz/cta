<div class="instructions"><h3>Instructions</h3>Queue weapons to be crafted here.  <a href="/populations/">Set population</a> to craft them.</div>
<h3>Forge</h3><br />
You are currently producing <?php echo $weaponsProduced ?> total weapons per hour.
<form id="WeaponBuild" method="post" action="/weapons/build<?php echo $parms?>">
<table>
  <tr>
    <th><center>Weapon </center></th>
    <th><center>Price</center></th>
    <th><center>Built</center></th>
    <th><center>Building</center></th>
    <th><center>To Build</center></th>
  </tr>
  <tr>
    <td><center><?php echo $weapons[0] ?></center></td>
    <td><center><?php echo "$iron[0] iron, $wood[0] wood, $gold[0] gold" ?></center></td>
    <td><center><?php echo $user['Weapon']['vehicle'] ?></center></td>
    <td><center><?php echo $user['Weapon']['vehicle_building'] ?></center></td>
    <td><center><input name="data[Weapon][vehicle_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[1] ?></center></td>
    <td><center><?php echo "$iron[1] iron, $wood[1] wood, $gold[1] gold" ?></center></td>
    <td><center><?php echo $user['Weapon']['big_melee'] ?></center></td>
    <td><center><?php echo $user['Weapon']['big_melee_building'] ?></center></td>
    <td><center><input name="data[Weapon][big_melee_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[2] ?></center></td>
    <td><center><?php echo "$iron[2] iron, $wood[2] wood, $gold[2] gold" ?></center></td>
    <td><center><?php echo $user['Weapon']['small_melee'] ?></center></td>
    <td><center><?php echo $user['Weapon']['small_melee_building'] ?></center></td>
    <td><center><input name="data[Weapon][small_melee_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[5] ?></center></td>
    <td><center><?php echo "$iron[5] iron, $wood[5] wood, $gold[5] gold" ?></center></td>
    <td><center><?php echo $user['Weapon']['range'] ?></center></td>
    <td><center><?php echo $user['Weapon']['range_building'] ?></center></td>
    <td><center><input name="data[Weapon][range_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[3] ?></center></td>
    <td><center><?php echo "$iron[3] iron, $wood[3] wood, $gold[3] gold" ?></center></td>
    <td><center><?php echo $user['Weapon']['big_armor'] ?></center></td>
    <td><center><?php echo $user['Weapon']['big_armor_building'] ?></center></td>
    <td><center><input name="data[Weapon][big_armor_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center><?php echo $weapons[4] ?></center></td>
    <td><center><?php echo "$iron[4] iron, $wood[4] wood, $gold[4] gold" ?></center></td>
    <td><center><?php echo $user['Weapon']['small_armor'] ?></center></td>
    <td><center><?php echo $user['Weapon']['small_armor_building'] ?></center></td>
    <td><center><input name="data[Weapon][small_armor_building]" type="text" size="4" value="" id="WeaponVehicleBuilding" /></center></td>
  </tr>
  <tr>
    <td><center>&nbsp;</center></td>
    <td><center>&nbsp;</center></td>
    <td><center></center></td>
    <td><center><a href="/weapons/destroy<?php echo $parms ?>">Destroy Weapons</a></center></td>
    <td><center> <input type="submit" class="submit" value="Craft" /><?php //echo $form->end('Build') ?></center></td>
  </tr>
</table>
</form>
