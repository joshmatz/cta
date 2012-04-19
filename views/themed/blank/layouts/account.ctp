<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>
<script src="/ctagame/js/prototype.js" type="text/javascript"></script>
<script src="/ctagame/js/scriptaculous.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://www.provokeone.com/ctagame/css/account.css" />
</head>
<body>
<div class="main_container">
	<?php if(isset($menu)) {?>
	<div style="width:135px; margin-right: 15px; float: left;">
		<ul class="menu">
			<?php foreach($menu as $item): ?>
				<li class="menu_item <?php if(isset($selected)) { if($item['name'] == $selected) { echo "selected_item"; } } ?>"><a href="<?php echo $item['link'] ?>" <?php if($item['name'] == 'Disband Alliance') { ?> onclick="return confirm('Are you sure you wish to delete <?php echo $alliance['Alliance']['name'] ?>?');" <?php } ?>><?php echo $item['name'] ?></a>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php } ?>
	<div class="content_container" style="float: left; width: 525px;">
		<div class="flash"><b><?php $session->flash() ?></b></div>
		<?php echo $content_for_layout ?>	
	</div>
	<div style="clear: both; font-size: 1px;">&nbsp;</div>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6111920-4");
pageTracker._trackPageview();
} catch(err) {}</script>
<script type="text/javascript">
FB.init("a095a0102375506b9b7d22ef45e5266a");
FB_RequireFeatures(["CanvasUtil"], function()
{
  //You can optionally enable extra debugging logging in Facebook JavaScript client
  //FB.FBDebug.isEnabled = true;
  //FB.FBDebug.logLevel = 4;


  FB.XdComm.Server.init("/ctagame/help/xd_receiver.htm");
  FB.CanvasClient.startTimerToSizeToContent();
});
</script>
</body>
</html>