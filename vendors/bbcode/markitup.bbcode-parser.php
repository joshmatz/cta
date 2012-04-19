<?php
// ----------------------------------------------------------------------------
// markItUp! BBCode Parser
// v 1.0.6
// Dual licensed under the MIT and GPL licenses.
// ----------------------------------------------------------------------------
// Copyright (C) 2009 Jay Salvat
// http://www.jaysalvat.com/
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
// Thanks to Arialdo Martini, Mustafa Dindar for feedbacks.
// ----------------------------------------------------------------------------

define ("EMOTICONS_DIR", "/img/smileys/");

/**
  * Process [quote] bbcode tags in a string.
  * 
  * Processes [quote] bbcode tags in a string, even nested ones.
  * 
  * @access public
  * @author J de Silva
  * @email giddomains@gmail.com
  * @url http://www.gidnetwork.com/b-6.html
  * @param string $str Passed by reference
  * @return void Working with the actual string, not a copy.
  */
function processBBCode_quote( &$str )
{
  // no point proceeding if there are no bb code tags for QUOTE  
  if( false!==strpos($str, '[quote]') or false!==strpos($str, '[QUOTE]') )
  {
    $len      = strlen( $str );
    // some flags
    $pos      = 0;
    $new_str  = null;
    $tag      = null;
    $in_quote = false;
    $nested   = array();
    while( $pos<$len )
    {
      $c = $str{$pos}; // get the current character
      if( $tag )
      {
        $tag .= $c;
        if( $c==']' )
        {
          if( $tag=='[quote]' or $tag=='[QUOTE]' )
          {
            if( $in_quote )
            {
              $nested[] = true;
              $in_quote .= '<blockquote>';
            }
            else
            $in_quote = '<blockquote title="Quoted Text">';
          }
          elseif( $tag=='[/quote]' or $tag=='[/QUOTE]' )
          {
            if( $nested )
            {
              array_pop( $nested );
              $in_quote .= '</blockquote>';
            }
            else
            {
              $new_str = $in_quote . '</blockquote>';
              $in_quote = null;
            }
          }
          else
          {
            // this is some other tag, let it go
            if( $in_quote )
              $in_quote .= $tag;
            else
              $new_str .= $tag;    
          }
          $tag = null;    
        }
      }
      elseif( $in_quote )
      {
        if( $c=='[' )
        {
          $tag = $c;    
        }
        else
          $in_quote .= $c;
      }
      elseif( $c=='[' )
        $tag .= $c;
      else
        $new_str .= $c;
      ++$pos;
    }
    $str = &$new_str;
  }
}


function BBCode2Html($text) {
	$text = trim($text);
	//$text = processBBCode_quote($text);
	// BBCode [code]
	if (!function_exists('escape')) {
		function escape($s) {
			global $text;
			$text = strip_tags($text);
			$code = $s[1];
			$code = htmlspecialchars($code);
			$code = str_replace("[", "&#91;", $code);
			$code = str_replace("]", "&#93;", $code);
			return '<pre><code>'.$code.'</code></pre>';
		}	
	}
	$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escape", $text);

	// Smileys to find...
	$in = array( 	 ':)', 	
					 ':D',
					 ':o',
					 ':O',
					 ':p',
					 ':P',
					 ':(',
					 ';)'
	);
	// And replace them by...
	$out = array(	 '<img alt=":)" src="'.EMOTICONS_DIR.'icon_e_smile.gif" />',
					 '<img alt=":D" src="'.EMOTICONS_DIR.'icon_e_biggrin.gif" />',
					 '<img alt=":o" src="'.EMOTICONS_DIR.'icon_e_surprised.gif" />',
					 '<img alt=":O" src="'.EMOTICONS_DIR.'icon_e_surprised.gif" />',
					 '<img alt=":p" src="'.EMOTICONS_DIR.'icon_razz.gif" />',
					 '<img alt=":P" src="'.EMOTICONS_DIR.'icon_razz.gif" />',
					 '<img alt=":(" src="'.EMOTICONS_DIR.'icon_e_sad.gif" />',
					 '<img alt=";)" src="'.EMOTICONS_DIR.'icon_e_wink.gif" />'
	);
	$text = str_replace($in, $out, $text);
	
	// BBCode to find...
	$in = array( 	 '/\[b\](.*?)\[\/b\]/ms',	
					 '/\[i\](.*?)\[\/i\]/ms',
					 '/\[u\](.*?)\[\/u\]/ms',
					 '/\[img\](.*?)\[\/img\]/ms',
					 '/\[email\](.*?)\[\/email\]/ms',
					 '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
					 '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
					 '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
					 //'/\[quote](.*?)\[\/quote\]/ms',
					 '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
					 '/\[list\](.*?)\[\/list\]/ms',
					 '/\[\*\]\s?(.*?)\n/ms'
	);
	// And replace them by...
	$out = array(	 '<strong>\1</strong>',
					 '<em>\1</em>',
					 '<u>\1</u>',
					 '<img src="\1" alt="\1" />',
					 '<a href="mailto:\1">\1</a>',
					 '<a href="\1">\2</a>',
					 '<span style="font-size:\1%">\2</span>',
					 '<span style="color:\1">\2</span>',
					 //'<blockquote>\1</blockquote>',
					 '<ol start="\1">\2</ol>',
					 '<ul>\1</ul>',
					 '<li>\1</li>'
	);
	$text = preg_replace($in, $out, $text);
		
	// paragraphs
	//$text = str_replace("\r", "", $text);
	//$text = "<p>".preg_replace("/(\n){2,}/", "</p><p>", $text)."</p>";
	$text = nl2br($text);
	
	// clean some tags to remain strict
	// not very elegant, but it works. No time to do better ;)
	if (!function_exists('removeBr')) {
		function removeBr($s) {
			return str_replace("<br />", "", $s[0]);
		}
	}	
	$text = preg_replace_callback('/<pre>(.*?)<\/pre>/ms', "removeBr", $text);
	$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);
	
	$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
	$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);
	
	return $text;
}
?>