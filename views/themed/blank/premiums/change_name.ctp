It currently takes 20 Premium Points to change your kingdom and ruler names. 
<form id="PremiumsChangeNameForm" method="post" action="/premiums/changeName<?php echo $parms ?>">
<label><span>Ruler Name:</span> <div style="margin: 0px;"><input name="data[User][ruler]" value="" type="text" style="width:197px" id="PremiumsRuler" /></input></div></label> <br />
<label><span>Kingdom Name:</span> <div style="margin: 0px;"><input name="data[User][kingdom]" value="" type="text" style="width:197px" id="PremiumsKingdom" /></input></div></label><br />
<input type="submit" value="Change Name" /></input>
</form>