<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>


<script src="/sd_cta/js/prototype.js" type="text/javascript"></script>
<script src="/sd_cta/js/scriptaculous.js" type="text/javascript"></script>
<script src="/sd_cta/js/droplicious.js" type="text/javascript"></script>
<script src="/sd_cta/js/effects.js" type="text/javascript"></script>
<script src="/sd_cta/js/controls.js" type="text/javascript"></script>

<script src="/sd_cta/js/dragdrop.js" type="text/javascript"></script>
<script src="/sd_cta/js/typeahead.js" type="text/javascript"></script>
<script src="/sd_cta/js/slider.js" type="text/javascript"></script>

<?php 
/* Grab the current server time. */
$gDate = time();
/* Are the seconds shown by default? When changing this, also change the
   JavaScript client code's definition of clockShowsSeconds below to match. */
$gClockShowsSeconds = false;

function getServerDateItems($inDate) {
	return date('Y,n,j,G,',$inDate).intval(date('i',$inDate)).','.intval(date('s',$inDate));
	// year (4-digit),month,day,hours (0-23),minutes,seconds
	// use intval to strip leading zero from minutes and seconds
	//   so JavaScript won't try to interpret them in octal
	//   (use intval instead of ltrim, which translates '00' to '')
}

function clockDateString($inDate) {
    return date('l, F j, Y',$inDate);    // eg "Monday, January 1, 2002"
}

function clockTimeString($inDate, $showSeconds) {
    return date($showSeconds ? 'g:i:s' : 'g:i',$inDate).' ';
}
/*** Clock -- end of server-side support code ***/
?>

<script language="JavaScript" type="text/javascript">
<!--
/* set up variables used to init clock in BODY's onLoad handler;
   should be done as early as possible */
var clockLocalStartTime = new Date();
var clockServerStartTime = new Date(<?php echo(getServerDateItems($gDate))?>);

/* stub functions for older browsers;
   will be overridden by next JavaScript1.2 block */
function clockInit() {
}
//-->
</script>

<script language="JavaScript1.2" type="text/javascript">
<!--
/*** simpleFindObj, by Andrew Shearer

Efficiently finds an object by name/id, using whichever of the IE,
classic Netscape, or Netscape 6/W3C DOM methods is available.
The optional inLayer argument helps Netscape 4 find objects in
the named layer or floating DIV. */
function simpleFindObj(name, inLayer) {
	return document[name] || (document.all && document.all[name])
		|| (document.getElementById && document.getElementById(name))
		|| (document.layers && inLayer && document.layers[inLayer].document[name]);
}

/*** Beginning of Clock 2.1.2, by Andrew Shearer
See: http://www.shearersoftware.com/software/web-tools/clock/
Redistribution is permitted with the above notice intact.

Client-side clock, based on computed time differential between browser &
server. The server time is inserted by server-side JavaScript, and local
time is subtracted from it by client-side JavaScript while the page is
loading.

Cookies: The local and remote times are saved in cookies named
localClock and remoteClock, so that when the page is loaded from local
cache (e.g. by the Back button) the clock will know that the embedded
server time is stale compared to the local time, since it already
matches its cookie. It can then base the calculations on both cookies,
without reloading the page from the server. (IE 4 & 5 for Windows didn't
respect Response.Expires = 0, so if cookies weren't used, the clock
would be wrong after going to another page then clicking Back. Netscape
& Mac IE were OK.)

Every so often (by default, one hour) the clock will reload the page, to
make sure the clock is in sync (as well as to update the rest of the
page content).

Compatibility: IE 4.x and 5.0, Netscape 4.x and 6.0, Mozilla 1.0. Mac & Windows.

History:  1.0   2000-05-09 GIF-image digits
          2.0   2000-06-29 Uses text DIV layers (so 4.0 browsers req'd), &
                         cookies to work around Win IE stale-time bug
		  2.1   2002-10-12 Noted Mozilla 1.0 compatibility; released PHP version.
		  2.1.1 2002-10-20 Fixed octal bug in the PHP translation; the number of
		  				minutes & seconds were misinterpretes when less than 10
		  2.1.2 2003-08-07 The previous fix had introduced a bug when the
		                minutes or seconds were exactly 0. Thanks to Man Bui
		                for reporting the bug.
*/
var clockIncrementMillis = 60000;
var localTime;
var clockOffset;
var clockExpirationLocal;
var clockShowsSeconds = false;
var clockTimerID = null;

function clockInit(localDateObject, serverDateObject)
{
    var origRemoteClock = parseInt(clockGetCookieData("remoteClock"));
    var origLocalClock = parseInt(clockGetCookieData("localClock"));
    var newRemoteClock = serverDateObject.getTime();
    // May be stale (WinIE); will check against cookie later
    // Can't use the millisec. ctor here because of client inconsistencies.
    var newLocalClock = localDateObject.getTime();
    var maxClockAge = 60 * 60 * 1000;   // get new time from server every 1hr

    if (newRemoteClock != origRemoteClock) {
        // new clocks are up-to-date (newer than any cookies)
        document.cookie = "remoteClock=" + newRemoteClock;
        document.cookie = "localClock=" + newLocalClock;
        clockOffset = newRemoteClock - newLocalClock;
        clockExpirationLocal = newLocalClock + maxClockAge;
        localTime = newLocalClock;  // to keep clockUpdate() happy
    }
    else if (origLocalClock != origLocalClock) {
        // error; localClock cookie is invalid (parsed as NaN)
        clockOffset = null;
        clockExpirationLocal = null;
    }
    else {
        // fall back to clocks in cookies
        clockOffset = origRemoteClock - origLocalClock;
        clockExpirationLocal = origLocalClock + maxClockAge;
        localTime = origLocalClock;
        // so clockUpdate() will reload if newLocalClock
        // is earlier (clock was reset)
    }
    /* Reload page at server midnight to display the new date,
       by expiring the clock then */
    var nextDayLocal = (new Date(serverDateObject.getFullYear(),
            serverDateObject.getMonth(),
            serverDateObject.getDate() + 1)).getTime() - clockOffset;
    if (nextDayLocal < clockExpirationLocal) {
        clockExpirationLocal = nextDayLocal;
    }
}

function clockOnLoad()
{
    clockUpdate();
}

function clockOnUnload() {
    clockClearTimeout();
}

function clockClearTimeout() {
    if (clockTimerID) {
        clearTimeout(clockTimerID);
        clockTimerID = null;
    }
}

function clockToggleSeconds()
{
    clockClearTimeout();
    if (clockShowsSeconds) {
        clockShowsSeconds = false;
        clockIncrementMillis = 60000;
    }
    else {
        clockShowsSeconds = true;
        clockIncrementMillis = 1000;
    }
    clockUpdate();
}

function clockTimeString(inHours, inMinutes, inSeconds) {
    return inHours == null ? "-:--" : ((inHours == 0
                   ? "12" : (inHours <= 12 ? inHours : inHours - 12))
                + (inMinutes < 10 ? ":0" : ":") + inMinutes
                + (clockShowsSeconds
                   ? ((inSeconds < 10 ? ":0" : ":") + inSeconds) : "")
                + (inHours < 12 ? " AM" : " PM"));
}

function clockDisplayTime(inHours, inMinutes, inSeconds) {
    
    clockWriteToDiv("ClockTime", clockTimeString(inHours, inMinutes, inSeconds));
}

function clockWriteToDiv(divName, newValue) // APS 6/29/00
{
    var divObject = simpleFindObj(divName);
    //newValue = '<p>' + newValue + '<' + '/p>';
    if (divObject && divObject.innerHTML) {
        divObject.innerHTML = newValue;
    }
    else if (divObject && divObject.document) {
        divObject.document.writeln(newValue);
        divObject.document.close();
    }
    // else divObject wasn't found; it's only a clock, so don't bother complaining
}

function clockGetCookieData(label) {
    /* find the value of the specified cookie in the document's
    semicolon-delimited collection. For IE Win98 compatibility, search
    from the end of the string (to find most specific host/path) and
    don't require "=" between cookie name & empty cookie values. Returns
    null if cookie not found. One remaining problem: Under IE 5 [Win98],
    setting a cookie with no equals sign creates a cookie with no name,
    just data, which is indistinguishable from a cookie with that name
    but no data but can't be overwritten by any cookie with an equals
    sign. */
    var c = document.cookie;
    if (c) {
        var labelLen = label.length, cEnd = c.length;
        while (cEnd > 0) {
            var cStart = c.lastIndexOf(';',cEnd-1) + 1;
            /* bug fix to Danny Goodman's code: calculate cEnd, to
            prevent walking the string char-by-char & finding cookie
            labels that contained the desired label as suffixes */
            // skip leading spaces
            while (cStart < cEnd && c.charAt(cStart)==" ") cStart++;
            if (cStart + labelLen <= cEnd && c.substr(cStart,labelLen) == label) {
                if (cStart + labelLen == cEnd) {                
                    return ""; // empty cookie value, no "="
                }
                else if (c.charAt(cStart+labelLen) == "=") {
                    // has "=" after label
                    return unescape(c.substring(cStart + labelLen + 1,cEnd));
                }
            }
            cEnd = cStart - 1;  // skip semicolon
        }
    }
    return null;
}

/* Called regularly to update the clock display as well as onLoad (user
   may have clicked the Back button to arrive here, so the clock would need
   an immediate update) */
function clockUpdate()
{
    var lastLocalTime = localTime;
    localTime = (new Date()).getTime();
    
    /* Sanity-check the diff. in local time between successive calls;
       reload if user has reset system clock */
    if (clockOffset == null) {
        clockDisplayTime(null, null, null);
    }
    else if (localTime < lastLocalTime || clockExpirationLocal < localTime) {
        /* Clock expired, or time appeared to go backward (user reset
           the clock). Reset cookies to prevent infinite reload loop if
           server doesn't give a new time. */
        document.cookie = 'remoteClock=-';
        document.cookie = 'localClock=-';
        location.reload();      // will refresh time values in cookies
    }
    else {
        // Compute what time would be on server 
        var serverTime = new Date(localTime + clockOffset);
        clockDisplayTime(serverTime.getHours(), serverTime.getMinutes(),
            serverTime.getSeconds());
        
        // Reschedule this func to run on next even clockIncrementMillis boundary
        clockTimerID = setTimeout("clockUpdate()",
            clockIncrementMillis - (serverTime.getTime() % clockIncrementMillis));
    }
}

/*** End of Clock ***/
//-->
</script>


<link rel="stylesheet" type="text/css" media="screen" href="http://www.provokeone.com/sd_cta/css/redesign.css" />
<link rel="stylesheet" type="text/css" media="screen" href="http://www.provokeone.com/sd_cta/css/droplicious.css" />


</head>
<body onload="clockInit(clockLocalStartTime, clockServerStartTime);clockOnLoad();" onunload="clockOnUnload()">
<!-- <script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script> -->
<div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div>


</script>
<script type='text/javascript'>
var mojo_form_widget_params = {};
mojo_form_widget_params.tab_name='get_support';
mojo_form_widget_params.tab_bg_color='#292929';
mojo_form_widget_params.tab_top_offset='400';
mojo_form_widget_params.title='How can we help?';
mojo_form_widget_params.description='Please tell us about your request, please include your kingdom name.';
mojo_form_widget_params.token='9387_28817';
mojo_form_widget_params.site_url='provokeone.mojohelpdesk.com';
</script>
<style type='text/css'>@import url('//asset.mojohelpdesk.com/stylesheets/form_widget.css');</style>
<script type='text/javascript' src='//asset.mojohelpdesk.com/javascripts/form_widget.js'></script>
<div class="main_container">

	<?php if(!$user['User']['premium']) { ?>
	<center>
	<!--   <fb:iframe src="http://www.adparlor.com/serveIframeAd.aspx?appId=7269322&adType=8" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" height="90" width="728"></iframe>-->
	<iframe src='http://rya.rockyou.com/ams/ad.php?placeguid=41CBC40056&type=Leaderboard&title_color=006699' style='border:0px;' width='728' height='90' scrolling='no' frameborder='0'> </iframe>
	<!-- <div id="_adopm_73"></div><script> var width="728";var height="90";var placementid="73";</script><script src='http://cdn.adopm.com/js/re.js'></script> -->
	</center> 
	<?php } ?>
	<img src="http://www.provokeone.com/sd_cta/img/layout/logo.png" />
	<div class="privacy" style="text-align:right;">
		<b><a href="/sd_cta/policies/privacy">Privacy Policy</a> | <a href="/sd_cta/policies/terms">Terms of Service</a> | <a href="http://www.provokeone.com/ctagameforum" target="_new">Forum</a></b><br />
	</div>
	<div class="premiumBox">
		<div class="premiumDisplay"><a href="/sd_cta/premiums"><img src="http://www.provokeone.com/sd_cta/img/layout/premium_points.png" /> <?php echo number_format($points); ?></a></div>
		<div id="countdown"></div>
	</div><br />
	<div class="resources">
		<div class="land"><?php echo $html->image("http://www.provokeone.com/sd_cta/img/land.png", array("alt" => "Land", "height" => 18)) ?>Land:<br /><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['land']); else echo number_format($user['User']['Resource']['land']) ?></b></div>
		<div class="castle"><?php echo $html->image("http://www.provokeone.com/sd_cta/img/castle.png", array("alt" => "Castle", "height" => 16)) ?>Castles:<br /><b><?php if(!isset($user['User']['Building'])) echo number_format($user['Building']['castle']); else echo number_format($user['User']['Building']['castle']) ?></b></div>
		<div class="gold"><?php echo $html->image("http://www.provokeone.com/sd_cta/img/gold.png", array("alt" => "Gold", "height" => 18)) ?>Gold:<br /><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['gold']); else echo number_format($user['User']['Resource']['gold']) ?></b></div>
		<div class="food"><?php echo $html->image("http://www.provokeone.com/sd_cta/img/food.png", array("alt" => "Food", "height" => 18)) ?>Food:<br /><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['food']); else echo number_format($user['User']['Resource']['food']) ?></b></div>
		<div class="iron"><?php echo $html->image("http://www.provokeone.com/sd_cta/img/iron.png", array("alt" => "Iron", "height" => 18)) ?>Iron:<br /><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['iron']); else echo number_format($user['User']['Resource']['iron']) ?></b></div>
		<div class="wood"><?php echo $html->image("http://www.provokeone.com/sd_cta/img/wood.png", array("alt" => "Wood", "height" => 18)) ?>Wood:<br /><b><?php if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['wood']); else echo number_format($user['User']['Resource']['wood']) ?></b></div>
		<!--  <div class="energy"><?php // echo $html->image("http://www.provokeone.com/sd_cta/img/energy.png", array("alt" => "Energy", "height" => 18)) ?>Energy:<br /><b><?php //if(!isset($user['User']['Resource'])) echo number_format($user['Resource']['energy']) . " / " . number_format($user['Resource']['max_energy']); else echo number_format($user['User']['Resource']['energy']) . " / " . number_format($user['User']['Resource']['max_energy']) ?></b></div> -->
		<?php if($newMessages != NULL && $user['User']['premium']) { echo "<a href=\"/sd_cta/messages/\"><img src=\"http://www.provokeone.com/sd_cta/img/layout/mail.png\" alt=\"New Mail!\" style=\"float:right;margin-right:3px;\"></a>"; } ?>
		<?php if($newReport) { echo "<a href=\"/sd_cta/reports/\"><img src=\"http://www.provokeone.com/sd_cta/img/reports_new.png\" alt=\"New Report!\" style=\"float:right;margin-right:3px;\"></a>"; } ?>
	</div>
	<?php  if($mobile) { ?>
	<div class="navBar">
		<div class="home"><b><a href="/sd_cta/">Home</a></b></div> 
		<div class="home"><b><a href="/sd_cta/buildings">Buildings</a></b></div> 
		<div class="home"><b><a href="/sd_cta/populations">Population</a></b></div> 
		<div class="home"><b><a href="/sd_cta/technologies">Technology</a></b></div> 

		<div class="military"><b><a href="/sd_cta/armies">Military</a></b></div> 
		<div class="military"><b><a href="/sd_cta/weapons">Weapons</a></b></div> 
		<div class="military"><b><a href="/sd_cta/attacks">Attack</a></b></div> 
		<div class="military"><b><a href="/sd_cta/spells">Spells</a></b></div> 
		
		<div class="province"><b><a href="/sd_cta/alliances">Alliance</a></b></div> 
		<div class="province"><b><a href="/sd_cta/leaderboards">Ranking</a></b></div> 
		<div class="province"><b><a href="/sd_cta/messages">Mail</a></b></div> 
	</div>
	<?php } else { ?>
		<div class="navbar" style="clear:left; margin-top:10px;">
			<ul id="nav">
				<li><a href="/ctagame">Home</a></li>
				<li>
					<a href="/sd_cta/reports" class="drops" id="link_one">News</a>
					<ul class="licious" id="box_one" style="display:none;">
						<li><a href="/sd_cta/reports">Reports</a></li>
						<li><a href="/sd_cta/messages">Mail</a></li>
						<li><a href="/sd_cta/users/profile">Profile</a></li>
					</ul>
				</li>
				<li>
					<a href="/sd_cta/buildings" class="drops" id="link_two">Economy</a>
					<ul class="licious" id="box_two" style="display:none;">
						<li><a href="/sd_cta/buildings">Building</a></li>
						<li><a href="/sd_cta/populations">Population</a></li>
						<li><a href="/sd_cta/trades">Trade</a></li>
						<li><a href="/sd_cta/technologies">Technology</a></li>
						<li><a href="/sd_cta/statistics">Kingdom Stats</a></li>
					</ul>
				</li>
				<li>
					<a href="/sd_cta/armies" class="drops" id="link_three">Military</a>
					<ul class="licious" id="box_three" style="display:none;">
						<li><a href="/sd_cta/weapons">Weapons</a></li>
						<li><a href="/sd_cta/armies">Units</a></li>
						<li><a href="/sd_cta/strategies">Strategy</a></li>
						<li><a href="/sd_cta/attacks">Attack</a></li>
						<li><a href="/sd_cta/spells">Spell</a></li>
						<li><a href="/sd_cta/retaliations">Retaliations</a></li>
					</ul>
				</li>
				<li>
					<a href="/sd_cta/leaderboards" class="drops" id="link_three">Community</a>
					<ul class="licious" id="box_three" style="display:none;">
						<li><a href="/sd_cta/alliances">Alliance</a></li>
						<li><a href="/sd_cta/leaderboards/findFriends">Friends</a></li>
						<li><a href="/sd_cta/leaderboards">Rankings</a></li>
						<li><a href="/sd_cta/fames">Hall of Fames</a></li>
					</ul>
				</li>
				<li>
					<a href="/sd_cta/forums" class="drops" id="link_three">Forums</a>
					<ul class="licious" id="box_three" style="display:none;">
						<li><a href="http://www.provokeone.com/ctagameforum/" target="_NEW">Public Forum</a></li>
						<li><a href="/sd_cta/forums">Alliance Forum</a></li>
					</ul>
				</li>
				<li>
					<a href="http://www.provokeone.com/ctagameforum/viewtopic.php?f=9&t=221" class="drops" id="link_three">Help</a>
					<ul class="licious" id="box_three" style="display:none;">
						<li><a href="http://www.provokeone.com/ctagameforum/viewtopic.php?f=9&t=221" target="_NEW">Game Guide</a></li>
						<li><a href="/sd_cta/settings">Settings</a></li>
						<li><a href="/sd_cta/premiums">Premium</a></li>
						<?php if($user['User']['admin_status'] == 'admin' || $user['User']['admin_status'] == 'moderator') { ?><li><a href="/sd_cta/admins">Admin</a></li><?php } ?>
					</ul>
				</li>
			</ul>	
			<div style="float:right;">
				<div id="ClockTime" style="margin-right: 15px; margin-top:-20px;
				    width: 200px; height: 20px; z-index: 11; <?php if($user['User']['premium']) { ?>cursor: pointer"<?php }?>
				    <?php if($user['User']['premium']) { ?>onclick="clockToggleSeconds()"<?php }?>>
				  <p><?php echo(clockTimeString($gDate,$gClockShowsSeconds));?></p>
				</div>
				<div id="ClockBkgnd" style="margin-right: 15px; margin-top: -5px;
				    width: 200px; z-index: 10">
				  <?php echo(clockDateString($gDate));?>
				</div>
			</div>
		</div>
	<?php } ?>
	<br /><br />
	
	<?php if(isset($menu)) {?>
	<div style="width:135px; margin-right: 15px; float: left;">
		<ul class="menu">
			<?php foreach($menu as $item): ?>
				<li class="menu_item <?php if(isset($selected)) { if($item['name'] == $selected) { echo "selected_item"; } } ?>"><a href="<?php echo $item['link'] ?>" <?php if($item['name'] == 'Disband Alliance') { ?> onclick="return confirm('Are you sure you wish to delete <?php echo $alliance['Alliance']['name'] ?>?');" <?php } ?>><?php echo $item['name'] ?></a>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php } ?>
	<div style="margin-left:30px;<?php if($account['Account']['role'] != 'admin') { ?>display:block;<?php } ?>">
	<iframe src='http://ads.matomy.com/offer/?id=faa2d952-cf52-44a0-a9af-ca0dc4aaab46&user_id=<?php echo $endUserId ?>' frameborder='0' width='720' height='1400' marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
	</div>
	<div id="content_container" class="content_container" style="<?php if(!empty($reports)) {?>height:1200px; <?php } ?> <?php if(!isset($actions[3])) { if($actions[2] == "") { ?>width:765px;<?php }  } ?>" >

		<!--  <div class="flash" style="color:gray;"><b>Round ends December 23, 12:00PM (noon) Gametime.</b></div>  -->
		<div class="flash"><b><?php $session->flash() ?></b></div>
		
		<?php if(isset($submenu)) { ?>
		<ul class="submenu">
			<?php foreach($submenu as $item): ?>
				<li class="submenu_item <?php if(isset($subselected)) { if($item['name'] == $subselected) { echo "subselected"; } } ?>"><a href="<?php echo $item['link'] ?>" <?php if($item['name'] == 'Disband Alliance') { ?> onclick="return confirm('Are you sure you wish to delete <?php echo $alliance['Alliance']['name'] ?>?');" <?php } ?>><?php echo $item['name'] ?></a>
			<?php endforeach; ?>
		</ul>
		<?php } ?>
		<?php echo $content_for_layout ?>	
	</div>
	<?php if(!$user['User']['premium']) { ?><div class="premium" style="clear:left;"> You're using a non-premium account. To get rid of ads and receive other benefits, <a href="/sd_cta/premiums/">upgrade today</a>!</div> <br /><br /><?php } ?>
	<?php if(!$user['User']['premium']) { ?><br />
<!-- RockYou: --><iframe src='http://rya.rockyou.com/ams/ad.php?placeguid=18D3539962&type=Leaderboard&bg_color=000000&body_color=FFFFCC' style='border:0px;' width='728' height='90' scrolling='no' frameborder='0'> </iframe>
<!-- Cubics:  <fb:iframe src='http://social.bidsystem.com/displayAd.aspx?pid=358643&plid=15965&adSize=468x60&bgColor=%23000000&textColor=%23eeeeee&linkColor=%23888888&channel=&appid=116612&pfid=' width='468' height='60' frameborder='0' border='0' scrolling='no'></fb:iframe> -->
<!-- OfferPal: <div id="_adopm_74"></div><script> var width="728";var height="90";var placementid="74";</script><script src='http://cdn.adopm.com/js/re.js'></script> -->
	<?php } ?>
	</center>
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


  FB.XdComm.Server.init("/sd_cta/help/xd_receiver.htm");
  FB.CanvasClient.startTimerToSizeToContent();
});
</script>

</body>
</html>