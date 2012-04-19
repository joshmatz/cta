<center>
<?php /* echo $form->create('Spell', array('action' => 'castRedirect', 'type' => 'post')); ?>
<?php echo $form->input('name', 
	  	array(
	  		'options' => array(
			  	'Tuth'=>'Tuth',
			    'Edilar'=>'Edilar',
			    'Ruch'=>'Ruch',
			    'Khardfee'=>'Khardfee',
			    'Alderd'=>'Alderd'	   
	  		),
	  		'default'=> $province_name
	  	)
	  );
?>

<?php echo $form->input('provinceNumber', array('type' => 'text', 'size' => 2, 'default' => $province_number)); ?>
<?php echo $form->input('target', 
	  	array(
	  		'options' => $options
	  	)
	  );
?>
<?php echo $form->end('Go'); */ ?>
</center>


<form id="AttackAttackRedirect" method="post" action="/spells/castRedirect<?php echo $parms?>">
Province: <select name="data[Spell][name]" id="SpellName">
<option value="Thorninn">Thorninn</option>
<option value="Endaris">Endaris</option>
<option value="Mur'gug">Mur'gug</option>
<option value="Kaloria">Kaloria</option>
<option value="Knute">Knute</option>
</select>
Number: <input name="data[Spell][provinceNumber]" type="text" size="2" id="SpellProvinceNumber" />
Target: <select name="data[Spell][target]" id="SpellTarget">
<?php foreach($options as $key => $value): ?>
<option value="<?php echo $key ?>"><?php echo $value ?></option>
<?php endforeach;?>
</select><input type="submit" value="Go" /></form>