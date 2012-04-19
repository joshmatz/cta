<a href="/trades/<?php echo $parms ?>">Market</a> | <a href="/trades/create<?php echo $parms ?>">Create New Offer</a> | <a href="/trades/find<?php echo $parms ?>">Send Resources Directly</a> | 
<a href="/trades/currentOffers<?php echo $parms ?>">View your offers</a><br /><br />
<center><h3>You are sending resources to <?php echo $name; ?></h3>
<form id="MessagesSendForm" method="post" action="/trades/send<?php echo $parms ?>">
<input name="data[target]" type="hidden" value="<?php echo $target?>" id="TradesTarget" />
You are sending resources to <?php echo $name ?>.
<table>
	<tr>
		<td valign="top">Wood Amount:</td>
		<td><input name="data[Trade][wood_amount]" type="text" value="" id="TradesAmount" /><br /></td>
	</tr>
	<tr>
		<td valign="top">Iron Amount:</td>
		<td><input name="data[Trade][iron_amount]" type="text" value="" id="TradesAmount" /><br /></td>
	</tr>
	<tr>
		<td valign="top">Gold Amount:</td>
		<td><input name="data[Trade][gold_amount]" type="text" value="" id="TradesAmount" /><br /></td>
	</tr>
	<tr>
		<td valign="top">Food Amount:</td>
		<td><input name="data[Trade][food_amount]" type="text" value="" id="TradesAmount" /><br /></td>
	</tr>
	<tr>
		<td valign="top"></td>
		<td><div style="text-align:right;"><input type="submit" value="Send" /></div></td>
	</tr>
</table><br />
</form>
</center>
