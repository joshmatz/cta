<div class="instructions"><h3>Instructions</h3>
To post an offer to the the marketplace, fill out the form below. Offer scope will limit those 
who can view your offer. Offer type is what you want to give away and the requested type is what type of resource you want in return. 
If you're looking for a lot of a particular resource, you can divide it up into multiple offers so people with less resources can take advantage of your offer. 
Your resources will be removed from your warehouse when the offer is created. They will be redeposited if you remove the offer.</div>

<form id="MessagesSendForm" method="post" action="/trades/createOffer<?php echo $parms ?>">
<table>
	<tr>
		<td valign="top">Offer scope: </td>
		<td>
			<select name="data[Trade][scope]" id="TradesScope">
				<option value="alliance">Alliance</option>
				<option value="race">Race</option>
				<option value="world" selected>World</option>
			</select><br />
		</td>
	</tr>
	<tr>
		<td valign="top">Offer type: </td>
		<td>
			<select name="data[Trade][type]" id="TradesType">
				<option value="wood">Wood</option>
				<option value="iron">Iron</option>
				<option value="gold">Gold</option>
				<option value="food">Food</option>
			</select><br /></td>
	</tr>
	<tr>
		<td valign="top">Offer amount:</td>
		<td><input name="data[Trade][amount]" type="text" value="" id="TradesAmount" /><br /></td>
	</tr>
	<tr>
		<td valign="top">Requested type:</td>
		<td>
			<select name="data[Trade][request_type]" id="TradesRequestType">
				<option value="wood">Wood</option>
				<option value="iron">Iron</option>
				<option value="gold">Gold</option>
				<option value="food">Food</option>
			</select><br />
		</td>
	</tr>
	<tr>
		<td valign="top">Requested amount:</td>
		<td><input name="data[Trade][request_amount]" type="text" value="" id="TradesRequestAmount" /><br /></td>
	</tr>
	<tr>
		<td valign="top">Total offers:</td>
		<td><input name="data[Trade][offers]" type="text" value="" id="TradesOffers" /><br /></td>
	</tr>
	<tr>
		<td valign="top"></td>
		<td><div style="text-align:right;"><input type="submit" value="Submit" /></div></td>
	</tr>
</table><br />
</form>

