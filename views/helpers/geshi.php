<?php

App::import('Vendor', 'geshi/geshi');

class GeshiHelper extends Helper
{
	function parse_code($code)
	{
		//html markup
		$code = @preg_replace("#<code(.*?)>(.*?)</code>#s", "\\2", $code);
		
		//language
		if($code[1] == "") $language = "php";
		else $language = @preg_replace("#language=\"(.*?)\"#s", "\\1", $code[1]);
		
		//start GeShi instance
		$geshi = new GeSHi(html_entity_decode(trim($code[0])), $language);
		$geshi->set_header_type(GESHI_HEADER_PRE);
		
		//return		
		return $geshi->parse_code();
	}
	
	function highlight($text)
	{
		return @preg_replace_callback("#<code(.*?)>(.*?)</code>#s", array($this, "parse_code"), $text);
	}
}
?>