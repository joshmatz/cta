<style type="text/css">
  div.slider { width:256px; margin:10px 0; background-color:#ccc; height:10px; position: relative; }
  div.slider div.handle { width:10px; height:15px; background-color:#f00; cursor:move; position: absolute; }

  div#zoom_element { width:50px; height:50px; background:#2d86bd; position:relative; }
</style>

<table class="population">
	<tr>
		<th>Category</th>
		<th></th>
		<th>Total</th>
		<th>Per Building</th>
		<th>Per Hour*</th>
	</tr>
	<tr>
		<td>Lumberers</td>
		<td><div id="lumberers_slider" class="slider"><div class="handle"></div></div></td>
		<td><div id="lumberers_total"><div id="lumberers_total"><?php echo number_format($user['User']['Population']['lumbers'])?></div></td>
		<td><div id="lumberers_per"><div id="lumberers_per"><?php echo number_format(round($user['User']['Population']['lumbers'] / $user['User']['Building']['mill'])) ?></div></td>
		<td></td>
	</tr>
	<tr>
		<td>Miners</td>
		<td><div id="miners_slider" class="slider"><div class="handle"></div></div></td>
		<td><div id="miners_total"><?php echo number_format($user['User']['Population']['miners'])?></div></td>
		<td><div id="miners_per"><?php echo number_format(round($user['User']['Population']['miners'] / $user['User']['Building']['mine'])) ?></div></td>
		<td></td>
	</tr>
	<tr>
		<td>Farmers</td>
		<td><div id="farmers_slider" class="slider"><div class="handle"></div></div></td>
		<td><div id="farmers_total"><?php echo number_format($user['User']['Population']['agriculture'])?></div></td>
		<td><div id="farmers_per"><?php echo number_format(round($user['User']['Population']['agriculture'] / $user['User']['Building']['farm'])) ?></div></td>
		<td></td>
	</tr>
	<tr>
		<td>Builders</td>
		<td><div id="builders_slider" class="slider"><div class="handle"></div></div></td>
		<td><div id="builders_total"><?php echo number_format($user['User']['Population']['building'])?></div></td>
		<td><div id="builders_per"><?php echo number_format(round($user['User']['Population']['building'] / $perBuilding)) ?></div></td>
		<td></td>
	</tr>
	<tr>
		<td>Training</td>
		<td><div id="training_slider" class="slider"><div class="handle"></div></div></td>
		<td><div id="training_total"><?php echo number_format($user['User']['Population']['training'])?></div></td>
		<td><div id="training_per"><?php echo number_format(round($user['User']['Population']['training'])) ?></div></td>
		<td></td>
	</tr>
	<tr>
		<td>Blacksmiths</td>
		<td><div id="blacksmiths_slider" class="slider"><div class="handle"></div></div></td>
		<td><div id="blacksmiths_total"><?php echo number_format($user['User']['Population']['blacksmiths'])?></div></td>
		<td><div id="blacksmiths_per"><?php echo number_format(round($user['User']['Population']['blacksmiths'] / $user['User']['Building']['forge'])) ?></div></td>
		<td></td>
	</tr>
	<tr>
		<td>Explorers</td>
		<td><div id="explorers_slider" class="slider"><div class="handle"></div></div></td>
		<td><div id="explorers_total"><?php echo number_format($user['User']['Population']['explorers'])?></div></td>
		<td><div id="explorers_per"><?php echo number_format(round($user['User']['Population']['explorers'])) ?></div></td>
		<td></td>
	</tr>
	<tr>
    	<td colspan="5"><div style="text-align:right;"><?php echo "Total Population: ". number_format($user['User']['Population']['total_population']); ?></div></td>
 	</tr>
</table>


<script type="text/javascript">
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

  (function() {
    var zoom_slider = $('zoom_slider'),
        rgb_slider = $('rgb_slider'),
        box = $('zoom_element'),
        lumberers_slider = $('lumberers_slider'),
        miners_slider = $('miners_slider'),
        farmers_slider = $('farmers_slider'),
        builders_slider = $('builders_slider'),
        training_slider = $('training_slider'),
        blacksmiths_slider = $('blacksmiths_slider'),
        explorers_slider = $('explorers_slider'),
        lumberers_total = <?php echo $user['User']['Population']['lumbers']?>,
        miners_total = <?php echo $user['User']['Population']['miners']?>,
        farmers_total = <?php echo $user['User']['Population']['agriculture']?>,
        builders_total = <?php echo $user['User']['Population']['building']?>,
        training_total = <?php echo $user['User']['Population']['training']?>,
        blacksmiths_total = <?php echo $user['User']['Population']['blacksmiths']?>,
        explorers_total = <?php echo $user['User']['Population']['explorers']?>,
        mills = <?php echo $user['User']['Building']['mill'] ?>,
        mines = <?php echo $user['User']['Building']['mine'] ?>,
        farms = <?php echo $user['User']['Building']['farm'] ?>,
        per_building = <?php echo $perBuilding ?>,
        forges = <?php echo $user['User']['Building']['forge'] ?>;


    var lumberersSlider = new Control.Slider(lumberers_slider.down('.handle'), lumberers_slider, {
        range: $R(0, <?php echo $user['User']['Population']['total_population']?>),
        sliderValue: <?php echo $user['User']['Population']['lumbers']?>,
        increment: <?php echo $user['User']['Population']['total_population']?>/256,
        onSlide: function(value) {
          document.getElementById("lumberers_total").innerHTML = addCommas(Math.round(value));
          document.getElementById("lumberers_per").innerHTML = addCommas(Math.round(value/mills));

          
        },
        onChange: function(value) { 
       	  document.getElementById("lumberers_total").innerHTML = addCommas(Math.round(value));
          document.getElementById("lumberers_per").innerHTML = addCommas(Math.round(value/mills)); 
        }
      });

    var minersSlider = new Control.Slider(miners_slider.down('.handle'), miners_slider, {
        range: $R(0, <?php echo $user['User']['Population']['total_population']?>),
        sliderValue: <?php echo $user['User']['Population']['miners']?>,
        increment: <?php echo $user['User']['Population']['total_population']?>/256,
        onSlide: function(value) {
            document.getElementById("miners_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("miners_per").innerHTML = addCommas(Math.round(value/mines));
          },
          onChange: function(value) { 
         	document.getElementById("miners_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("miners_per").innerHTML = addCommas(Math.round(value/mines)); 
          }
      });

    var farmersSlider = new Control.Slider(farmers_slider.down('.handle'), farmers_slider, {
        range: $R(0, <?php echo $user['User']['Population']['total_population']?>),
        sliderValue: <?php echo $user['User']['Population']['agriculture']?>,
        increment: <?php echo $user['User']['Population']['total_population']?>/256,
        onSlide: function(value) {
            document.getElementById("farmers_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("farmers_per").innerHTML = addCommas(Math.round(value/farms));
          },
          onChange: function(value) { 
         	document.getElementById("farmers_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("farmers_per").innerHTML = addCommas(Math.round(value/farms)); 
          }
      });

     var buildersSlider =  new Control.Slider(builders_slider.down('.handle'), builders_slider, {
        range: $R(0, <?php echo $user['User']['Population']['total_population']?>),
        sliderValue: <?php echo $user['User']['Population']['building']?>,
        increment: <?php echo $user['User']['Population']['total_population']?>/256,
        onSlide: function(value) {
            document.getElementById("builders_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("builders_per").innerHTML = addCommas(Math.round(value/per_building));
          },
          onChange: function(value) { 
         	document.getElementById("builders_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("builders_per").innerHTML = addCommas(Math.round(value/per_building)); 
          }
      });

     var trainingSlider = new Control.Slider(training_slider.down('.handle'), training_slider, {
        range: $R(0, <?php echo $user['User']['Population']['total_population']?>),
        sliderValue: <?php echo $user['User']['Population']['training']?>,
        increment: <?php echo $user['User']['Population']['total_population']?>/256,
        onSlide: function(value) {
            document.getElementById("training_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("training_per").innerHTML = addCommas(Math.round(value));
          },
          onChange: function(value) { 
         	document.getElementById("training_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("training_per").innerHTML = addCommas(Math.round(value)); 
          }
      });

     var blacksmithsSlider = new Control.Slider(blacksmiths_slider.down('.handle'), blacksmiths_slider, {
        range: $R(0, <?php echo $user['User']['Population']['total_population']?>),
        sliderValue: <?php echo $user['User']['Population']['blacksmiths']?>,
        increment: <?php echo $user['User']['Population']['total_population']?>/256,
        onSlide: function(value) {
            document.getElementById("blacksmiths_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("blacksmiths_per").innerHTML = addCommas(Math.round(value/forges));
          },
          onChange: function(value) { 
         	document.getElementById("blacksmiths_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("blacksmiths_per").innerHTML = addCommas(Math.round(value/forges)); 
          }
      });

     var explorersSlider = new Control.Slider(explorers_slider.down('.handle'), explorers_slider, {
        range: $R(0, <?php echo $user['User']['Population']['total_population']?>),
        sliderValue: <?php echo $user['User']['Population']['explorers']?>,
        increment: <?php echo $user['User']['Population']['total_population']?>/256,
        onSlide: function(value) {
            document.getElementById("explorers_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("explorers_per").innerHTML = addCommas(Math.round(value));
          },
          onChange: function(value) { 
         	document.getElementById("explorers_total").innerHTML = addCommas(Math.round(value));
            document.getElementById("explorers_per").innerHTML = addCommas(Math.round(value)); 
          }
      });

  })();
</script>