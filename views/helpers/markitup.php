<?php  
/** 
 * markItUp! Helpers 
 * @author Jay Salvat 
 * @version 1.0 
 * 
 * Download markItUp! at: 
 * http://markitup.jaysalvat.com 
 * Download $ at: 
 * http://$.com 
 */ 
class MarkitupHelper extends AppHelper { 
    var $helpers = array('Html', 'Form'); 
     
    /** 
     * Generates a form textarea element complete with label and wrapper div with markItUp! applied. 
     * @param  string $fieldName This should be "Modelname.fieldname" 
     * @param  array $settings 
     * @return string  An <textarea /> element. 
     */ 
    function editor($name, $settings = array()) { 
        $config = $this->_build($settings); 
        $settings = $config['settings']; 
        $default = $config['default']; 
        $textarea = array_diff_key($settings, $default); 
        $textarea = am($textarea, array('type' => 'textarea')); 
        $editor = $this->Form->input($name, $textarea); 
        $id = '#'.parent::domId($name); 
        $editor.= $this->Html->scriptBlock('$(function() { $("'.$id.'").markItUp('.$settings['settings'].', { previewParserPath:"'.$settings['parser'].'" } ); });');
        return $this->output($editor); 
    } 

    /** 
     * Link to build markItUp! on a existing textfield 
     * @param  string $title The content to be wrapped by <a> tags. 
     * @param  string $fieldName This should be "Modelname.fieldname" or specific domId as #id. 
     * @param  array  $settings 
     * @param  array  $htmlAttributes Array of HTML attributes. 
     * @param  string $confirmMessage JavaScript confirmation message. 
     * @return string An <a /> element.     
     */ 
    function create($title, $fieldName = "", $settings = array(), $htmlAttributes = array(), $confirmMessage = false) { 
        $id = ($fieldName{0} === '#') ? $fieldName : '#'.parent::domId($fieldName); 
         
        $config = $this->_build($settings); 
        $settings = $config['settings']; 
        $htmlAttributes = am($htmlAttributes, array('onclick' => '$("'.$id.'").markItUpRemove(); $("'.$id.'").markItUp('.$settings['settings'].', { previewParserPath:"'.$settings['parser'].'" }); return false;'));
        return $this->Html->link($title, "#", $htmlAttributes, $confirmMessage, false); 
    }     

    /** 
     * Link to destroy a markItUp! editor from a textfield 
     * @param string  $title The content to be wrapped by <a> tags. 
     * @param string  $fieldName This should be "Modelname.fieldname" or specific domId as #id. 
     * @param array   $htmlAttributes Array of HTML attributes. 
     * @param string  $confirmMessage JavaScript confirmation message. 
     * @return string An <a /> element.     
     */ 
    function destroy($title, $fieldName = "", $htmlAttributes = array(), $confirmMessage = false) { 
        $id = ($fieldName{0} === '#') ? $fieldName : '#'.parent::domId($fieldName); 
        $htmlAttributes = am($htmlAttributes, array('onclick' => '$("'.$id.'").markItUpRemove(); return false;')); 
        return $this->Html->link($title, "#", $htmlAttributes, $confirmMessage, false); 
    } 

    /** 
     * Link to add content to the focused textarea 
     * @param string  $title The content to be wrapped by <a> tags. 
     * @param string  $fieldName This should be "Modelname.fieldname" or specific domId as #id. 
     * @param mixed   $content String or array of markItUp! options (openWith, closeWith, replaceWith, placeHolder and more. See markItUp! documentation for more details : http://markitup.jaysalvat.com/documentation
     * @param array   $htmlAttributes Array of HTML attributes. 
     * @param string  $confirmMessage JavaScript confirmation message. 
     * @return string An <a /> element.     
     */ 
    function insert($title, $fieldName = null, $content = array(), $htmlAttributes = array(), $confirmMessage = false) {
        if (isset($fieldName)) { 
            $content['target'] = ($fieldName{0} === '#') ? $fieldName : '#'.parent::domId($fieldName); 
        } 
        if (!is_array($content)) { 
            $content['replaceWith'] = $content; 
        } 
        $properties = ''; 
        foreach($content as $k => $v) { 
            $properties .= $k.':"'.addslashes($v).'",'; 
        } 
        $properties = substr($properties, 0, -1); 
         
        $htmlAttributes = am($htmlAttributes, array('onclick' => '$.markItUp( { '.$properties.' } ); return false;')); 
        return $this->Html->link($title, "#", $htmlAttributes, $confirmMessage, false); 
    } 

    /** 
     * Parser to use in the preview 
     * @param string  $content The content to be parsed. 
     * @return string Parsed content.     
     */ 
    function parse($content, $parser = '') { 
    // This Helper is designed to be used with several kinds of parser 
    // in a same project. 
        // Drop your favorite parsers in the /vendor/ folder and edit lines below. 
        switch($parser) { 
            case 'bbcode': 
                 App::import('Vendor', 'nbbc', array('file' => 'nbbc.php')); 
                 $bbcode = new BBCode;
                 $content = $bbcode->Parse($content);       
                break; 
            case 'textile': 
                // App::import('Vendor', 'textile', array('file' => 'myFavoriteTextileParser')); 
                // $parsed = myFavoriteTextileParser($content);         
                break; 
            case 'markdown': 
                // App::import('Vendor', 'markdown', array('file' => 'myFavoriteMarkDownParser')); 
                // $parsed = myFavoriteMarkDownParser($content);             
                break; 
            default: 
                // App::import('Vendor', 'favorite', array('file' => 'myFavoriteFavoriteParser')); 
                // $parsed = myFavoriteFavoriteParser($content); 
        } 
        return $content; 
    } 
     
    /** 
     * Adds $ and markItUp! scripts to the page 
     */     
    function beforeRender() { 
       // $this->Html->script('$/$.js', false); 
       // $this->Html->script('/js/markitup/jquery.markitup', false); 
    } 

    /** 
     * Private function. 
     * Builds the settings array and add includes. 
     */     
    function _build($settings) { 
        $default = array(   'set' => 'default',  
                            'skin' => 'markitup',  
                            'settings' => 'mySettings', 
                            'parser' => ''); 
        $settings = am($default, $settings); 
        if ($settings['parser']) { 
            $settings['parser'] = $this->Html->url($settings['parser']); 
        }                 
        
        return array('settings' => $settings, 'default' => $default); 
    } 
} 
?> 