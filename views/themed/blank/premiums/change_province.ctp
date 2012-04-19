<form id="PremiumsChangeProvince" method="post" action="/premiums/changeProvince<?php echo $parms ?>">
	<div class="box">
		<label><span>Province:</span> <select name="data[Premiums][province]" class="dropDown">
			<option value="Thorninn">Thorninn</option>
			<option value="Endaris">Endaris</option>
			<option value="Mur'gug">Mur'gug</option>
			<option value="Kaloria">Kaloria</option>
			<option value="Knute">Knute</option>
			</select></label> 
		<label><span>Province number:</span> <input name="data[Premiums][province_number]" type="text" size="2" value="" class="numberBox" /><?php 
		// echo $form->input('province_number', array('type' => 'text', 'size' => 2, 'div' => false, 'class' => 'numberBox', 'label' => false)); ?> *Must be an integer between 1 and 99.</label>
		<label><span></span> <input type="submit" class="submit" value="Change Province" /></label>

</div>
</form>