<?php

$open = true;
if($open || $account['Account']['role'] == 'admin' || $account['Account']['role'] == 'council' ) { ?>

<div class="guideNav">
<?php $beta = false; if($beta) {?>
<div style="text-align:left;">This is an open beta round meaning you are free to play as a real round, 
but keep in mind that there may be glitches in new features. If you encounter bugs or have suggestions, 
please post <a href="http://www.provokeone.com/ctagameforum" target="_new">in the forums</a>. Bugs will be fixed so accounts remain in playable states,
however it's highly unlikely accounts will be restored to previous conditions before the bug(s) occured. Additionally, accounts may be reset with no warning. <br /><br />
Beta rounds tick every three (3) minutes and have no set duration. 
Enjoy the time to play outside the box and experiment with new races, mechanics, army compositions, and strategies. 
Beta round scores will not be recorded.</div>
<?php } ?>
</div>
<?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'create'), 'class' => 'box')); ?>
		<?php 
			echo $form->input('ruler', 
				array( 
					'class' => 'textBox', 
					'label' => '<span>Ruler name: </span>'
				)
			); 
			echo $form->input('race',
				array(
					'options' => array(
						'Elf' => 'Elf', 
						'Dwarf' => 'Dwarf', 
						'Gnome' => 'Gnome',
						'Human' => 'Human',
						'Orc' => 'Orc'
					),
					'class' => 'textBox', 
					'label' => '<span>Race: </span>'
				)
			);
			echo $this->Form->submit('Create Kingdom', array('class' => 'submit'));
		?>
	<?php  ?>
	<div id="raceDescription">
		<span class="race"><h3>Elves</h3>+ Get wood production bonus. <br />+ Can cast more spells with appropriate research. <br />+ Best chances of succeeding in spell casting. <br />+ Can train the best ranged units of the continent. <br />-Slow weapon building speed. <br />-+ Have 4 generals</span>
	</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
$('#UserRace').change(function(){
  var optionSelectedValue = $('#UserRace option:selected').val();
  if(optionSelectedValue == 'Elf') {
    $('#raceDescription').html('<span class="race"><h3>Elves</h3>+ Get wood production bonus. <br />+ Can cast more spells with appropriate research. <br />+ Best chances of succeeding in spell casting. <br />+ Can train the best ranged units of the continent. <br />-Slow weapon building speed. <br />-+ Have 4 generals</span>')
  }
  else if(optionSelectedValue == 'Human') {
    $('#raceDescription').html('<span class="race"><h3>Humans</h3>+ Get iron production bonus. <br />+ Start with 5 bonus basic army strength points.<br />+ Can train the most efficient mounted units.<br />Have no major weak fields. <br />Normal weapon building speed.<br />+- Have 5 generals</span>')
  }
  else if(optionSelectedValue == 'Dwarf') {
    $('#raceDescription').html('<span class="race"><h3>Dwarfs</h3>+ Get iron production bonus. <br />+ Get blacksmithing bonus. <br />+ Can build greater armors allowing to build high defense units. <br />- Have bad spell casting abilities. <br />+ Fast weapon building speed. <br />+ Have 6 generals</span>')
  }
  else if(optionSelectedValue == 'Orc') {
    $('#raceDescription').html('<span class="race"><h3>Orcs</h3>+ Get food production bonus. <br />+ Get iron production bonus. <br />+ Get wood production bonus. <br />+- Train very cheap, but weak army and mounts. <br />- Have reduced spell casting abilities. <br />- Slow weapon building speed. <br />+ Construct buildings faster the average. <br />+ Have 7 generals</span>')
  }
  else if(optionSelectedValue == 'Gnome') {
    $('#raceDescription').html('<span class="race"><h3>Gnomes</h3>+ Can build very powerful war machines. <br />+ Get lower cost technology. <br />+ Can research technology very quickly. <br />- Have reduced spell casting abilities. + Fast weapon building speed. <br />+ Construct buildings faster than the average. <br />-+ Have 4 generals</span>')
  }
  
});
</script>

      <?php } else { ?>
	<div style="text-align:left;">
		Thanks for playing the September/October 2011 round! A new round will start soon.
		To keep updated on the game, visit the <a href="http://cta.provokeone.com/forums" target="_new">Call to Arms forums</a>! 
	</div>
	<br />
	<a href="/fames">Hall of Fames</a> if you click the link!.<br />
	  <?php } ?>
	  
 