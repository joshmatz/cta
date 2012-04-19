<form id="AttackAttackRedirect" method="post" action="/attacks/attackRedirect<?php echo $parms?>">
Province: <select name="data[Attack][name]" id="AttackName">
<option value="Thorninn">Thorninn</option>
<option value="Endaris">Endaris</option>
<option value="Mur'gug">Mur'gug</option>
<option value="Kaloria">Kaloria</option>
<option value="Knute">Knute</option>
</select>
Number: <input name="data[Attack][provinceNumber]" type="text" size="2" value="" id="AttackProvinceNumber" /> Target: <select name="data[Attack][target]" id="AttackTarget">
<?php foreach($options as $key => $value): ?>
<option value="<?php echo $key ?>"><?php echo $value ?></option>
<?php endforeach;?>
</select><input type="submit" value="Go" /></form>