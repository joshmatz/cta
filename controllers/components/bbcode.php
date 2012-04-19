<?php
class BbcodeComponent extends Object {
	var $name = 'Bbcode';
	
function sanitize($text, $premium = 0) {
	$text = htmlspecialchars($text);
	$text = nl2br($text);
	$text = $this->parse_bbcode($text, $premium);

	return $text;
}
	
function parse_bbcode($text, $premium = 0, $xhtml = true)
{
	if(true){
	    $tags = array(
	        '#\[b\](.*?)\[/b\]#si' => ($xhtml ? '<strong>\\1</strong>' : '<b>\\1</b>'),
	        '#\[i\](.*?)\[/i\]#si' => ($xhtml ? '<em>\\1</em>' : '<i>\\1</i>'),
	        '#\[u\](.*?)\[/u\]#si' => ($xhtml ? '<span style="text-decoration: underline;">\\1</span>' : '<u>\\1</u>'),
	        '#\[s\](.*?)\[/s\]#si' => ($xhtml ? '<strike>\\1</strike>' : '<s>\\1</s>'),
	        '#\[color=(.*?)\](.*?)\[/color\]#si' => ($xhtml ? '<span style="color: \\1;">\\2</span>' : '<font color="\\1">\\2</font>'),
	    	'#\[quote="(.*?)"\](.*?)\[/quote\]#si' => '<div class="quote">\\2 <br /><br />~ \\1</div>',
	        '#\[img\](.*?)\[/img\]#si' => ($xhtml ? '<img src="\\1" border="0" alt="" />' : '<img src="\\1" border="0" alt="">'),
	        '#\[url=(.*?)\](.*?)\[/url\]#si' => '<a href="\\1" title="\\2" target="_NEW">\\2</a>',
	        '#\[email\](.*?)\[/email\]#si' => '<a href="mailto:\\1" title="Email \\1">\\1</a>',
	        '#\[code\](.*?)\[/code\]#si' => '<code>\\1</code>',
	        '#\[align=(.*?)\](.*?)\[/align\]#si' => ($xhtml ? '<div style="text-align: \\1;">\\2</div>' : '<div align="\\1">\\2</div>'),
	        '#\[br\]#si' => ($xhtml ? '<br style="clear: both;" />' : '<br>'),
	    ); 
	} else {
		$tags = array(
	        '#\[b\](.*?)\[/b\]#si' => ($xhtml ? '<strong>\\1</strong>' : '<b>\\1</b>'),
	        '#\[i\](.*?)\[/i\]#si' => ($xhtml ? '<em>\\1</em>' : '<i>\\1</i>'),
	        '#\[u\](.*?)\[/u\]#si' => ($xhtml ? '<span style="text-decoration: underline;">\\1</span>' : '<u>\\1</u>'),
	        '#\[s\](.*?)\[/s\]#si' => ($xhtml ? '<strike>\\1</strike>' : '<s>\\1</s>'),
	        '#\[color=(.*?)\](.*?)\[/color\]#si' => ($xhtml ? '<span style="color: \\1;">\\2</span>' : '<font color="\\1">\\2</font>'),
	        '#\[url=(.*?)\](.*?)\[/url\]#si' => '<a href="\\1" title="\\2">\\2</a>',
	        '#\[email\](.*?)\[/email\]#si' => '<a href="mailto:\\1" title="Email \\1">\\1</a>',
	        '#\[code\](.*?)\[/code\]#si' => '<code>\\1</code>',
	        '#\[align=(.*?)\](.*?)\[/align\]#si' => ($xhtml ? '<div style="text-align: \\1;">\\2</div>' : '<div align="\\1">\\2</div>'),
	        '#\[br\]#si' => ($xhtml ? '<br style="clear: both;" />' : '<br>'),
	    );
	}

    foreach ($tags AS $search => $replace)
    {
        $text = preg_replace($search, $replace, $text);
    }
    return $text;
}

function parse_smilies($text)
{
    $smilies = array(
        ':)' => '<img src="images/smilies/smile.gif" border="0" alt="Smile" title="Smile" />',
        ':(' => '<img src="images/smilies/sad.gif" border="0" alt="Sad" title="Sad" />',
        ':D' => '<img src="images/smilies/biggrin.gif" border="0" alt="Big Grin" title="Big Grin" />',
        ':S' => '<img src="images/smilies/confused.gif" border="0" alt="Confused" title="Confused" />'
        // etc
    );

    foreach ($smilies AS $search => $replace)
    {
        $text = str_replace($search, $replace, $text);
    }
    return $text;
}
}
?>