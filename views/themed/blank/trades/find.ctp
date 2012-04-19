<a href="/trades/<?php echo $parms ?>">Market</a> | <a href="/trades/create<?php echo $parms ?>">Create New Offer</a> | <a href="/trades/find<?php echo $parms ?>">Send Resources Directly</a> | 
<a href="/trades/currentOffers<?php echo $parms ?>">View your offers</a><br /><br /><div class="instructions"><center><h3>Instructions</h3></center>
On this page you can select a user you want to send resources to and when the a user is selected you'll be presented with a page to 
select the type of resources to send.</div>
<center>
<form id="TradeFind" method="post" action="/trades/findRedirect<?php echo $parms?>">
Province: <select name="data[Trade][name]" id="AttackName">
<option value="Tuth">Tuth</option>
<option value="Edilar">Edilar</option>
<option value="Ruch">Ruch</option>
<option value="Khardfee">Khardfee</option>
<option value="Alderd">Alderd</option>
</select>
Number: <input name="data[Trade][provinceNumber]" type="text" size="2" id="AttackProvinceNumber" /> 
Target: <select name="data[Trade][target]" id="AttackTarget">
<?php foreach($options as $key => $value): ?>
<option value="<?php echo $key ?>"><?php echo $value ?></option>
<?php endforeach;?>
</select><input type="submit" value="Go" /></form>