<?php
	class BbcodeHelper extends Helper {
		
		function sanitize($text) {
			$text = htmlspecialchars($text);
			$text = nl2br($text);
			$text = $this->parse_bbcode($text);
			$text = $this->parse_smilies($text);
			return $text;
		}
			
		function parse_bbcode($text)
		{
			if(true){
			    $tags = array(
			        '#\[b\](.*?)\[/b\]#si' => '<strong>\\1</strong>',
			        '#\[i\](.*?)\[/i\]#si' => '<em>\\1</em>',
			        '#\[u\](.*?)\[/u\]#si' => '<span style="text-decoration: underline;">\\1</span>',
			        '#\[s\](.*?)\[/s\]#si' => '<strike>\\1</strike>',
			        '#\[color=(.*?)\](.*?)\[/color\]#si' => '<span style="color: \\1;">\\2</span>',
			    	'#\[quote="(.*?)"\](.*?)\[/quote\]#si' => '<div class="quote">\\2 <br /><br />~ \\1</div>',
			        '#\[img\](.*?)\[/img\]#si' => '<img src="\\1" border="0" alt="" />',
			        '#\[url=(.*?)\](.*?)\[/url\]#si' => '<a href="\\1" title="\\2" target="_NEW">\\2</a>',
			        '#\[email\](.*?)\[/email\]#si' => '<a href="mailto:\\1" title="Email \\1">\\1</a>',
			        '#\[code\](.*?)\[/code\]#si' => '<code>\\1</code>',
			        '#\[align=(.*?)\](.*?)\[/align\]#si' => '<div style="text-align: \\1;">\\2</div>',
			        '#\[br\]#si' => '<br style="clear: both;" />',
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
		        ':D' => '<img src="/img/smileys/icon_e_biggrin.gif" border="0" alt="Smile" title="Smile" />',
		        ':)' => '<img src="/img/smileys/icon_e_smile.gif" border="0" alt="Sad" title="Sad" />',
		        ':(' => '<img src="/img/smileys/icon_e_sad.gif" border="0" alt="Big Grin" title="Big Grin" />',
		        ':?' => '<img src="/img/smileys/icon_e_confused.gif" border="0" alt="Confused" title="Confused" />'
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