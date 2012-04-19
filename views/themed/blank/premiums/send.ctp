<?php $ajax->options['url']['?'] = $simpleParms; ?>
<style type="text/css">
    .auto_complete { width:200px; position:absolute; z-index:100; background-color:#ffffff; color: #000000; text-align:left; border:1px solid #bdc7d8; display: block;}
    .auto_complete ul { list-style-type:none; margin:0px; padding:0px; background-color:transparent; }
    .selected { background-color:#3b5998; color:#ffffff; }
    .auto_complete li a { background-color:#ffffff; color:#3b5998; text-decoration:none; padding:2px 6px; display:block; }
    .auto_complete li:hover{ background-color:#3b5998; color:#ffffff; }
</style>
<div style="text-align:left;">
<form id="PremiumSendForm" method="post" action="/premiums/send<?php echo $parms ?>">
To: <div style="margin: 0px;"><?php echo $ajax->autoComplete('Premium.receiver', '/premiums/sendAutoComplete')?></div> <br />
Amount: <div style="margin: 0px;"><input name="data[Premium][amount]" value="" type="text" style="width:197px" id="PremiumAmount" /></input></div><br />
<input type="submit" value="Send" /></input>
</form>

</div>