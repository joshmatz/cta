
Target: <?php echo $name ?><br />
<form id="SpellCast" method="post" action="/spells/castSpell">
<input name="data[Spell][target]" type="hidden" value="<?php echo $target?>" />
Select your spell: <select name="data[Spell][spell]" id="SpellType">
<?php foreach($options as $key => $value): ?>
<option value="<?php echo $key ?>"><?php echo $value ?></option>
<?php endforeach;?>
</select>
<br />Available wizards: <?php echo $available_wizards ?><br />
Resting wizards: <?php echo $resting_wizards ?><br />
Amount of wizards to use: <input name="data[Spell][wizards]" type="text" id="SpellWizards" size="4" />

</select><input type="submit" value="Cast Spell" /></form>