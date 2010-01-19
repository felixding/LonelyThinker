<?php

#   Copyright (C) 2006-2008 Tobias Leupold <tobias.leupold@web.de>
#
#   This file is part of the b8 package
#
#   This program is free software; you can redistribute it and/or
#   modify it under the terms of the GNU General Public License
#   as published by the Free Software Foundation in version 2
#   of the License.
#
#   This program is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License along
#   with this program; if not, write to the Free Software Foundation, Inc.,
#   59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.

# Get the shared functions class file (if not already loaded)
require_once dirname(__FILE__) . "/../shared_functions.php";

# The default class to split a text into tokens

class lexer_default extends b8SharedFunctions
{

	# Constructor

	function lexer_default()
	{

		# Till now, everything's fine
		# Yes, I know that this is crap ;-)
		$this->constructed = TRUE;

		# Config parts we need
		$config[] = array("name" => "minSize",		  "type" => "int",	"default" => 3);
		$config[] = array("name" => "maxSize",		  "type" => "int",	"default" => 15);
		$config[] = array("name" => "allowNumbers",	  "type" => "bool",	"default" => FALSE);

		# Get the configuration

		$configFile = "config_lexer";

		if(!$this->loadConfig($configFile, $config)) {
			$this->echoError("Failed initializing the configuration.");
			$this->constructed = FALSE;
		}
		
		//set the default encoding for mutil-byte characters
		mb_internal_encoding('UTF-8');		

	}
	
	/**
	 * strip whitespaces from a string
	 *
	 * @author Felix Ding
	 * @url http://dingyu.me
	 * @date 2009-1-19
	 * @param $str String
	 * @return String
	 */
	 
	function strip_whitespaces($str)
	{
		//remove the leading/trailing whitespace
		$str = trim($str);

		//remove any doubled-up whitespace
		$str = preg_replace('/\s(?=\s)/', '', $str);

		//replace any non-space whitespace with a space
		$str = preg_replace('/[\n\r\t]/', ' ', $str);
		
		return $str;
	}
	

	# Split the text up to tokens

	function getTokens($text)
	{

		# Check if we have a string here

		if(!is_string($text)) {
			$this->echoError("The given parameter is not a string (<kbd>" . gettype($text) . "</kbd>). Cannot lex it.");
			return FALSE;
		}

		$tokens = "";
		
		//strip Chinese punctuation
		$text = preg_replace('/[“”！◎＃¥％……※×（）——＋§『』【】，。、‘’；：～·]/u', '', $text);
		
		//debug
		//pr($text);
		//die();
		
		# Get internet and IP addresses		
		
		//exract all Chinese tokens
		preg_match_all('/[一-龥]/u', $text, $raw_tokens_chinese);		
		
		//exract all Latin tokens		
		preg_match_all("/([A-Za-z0-9\_\-\.]+)/", $text, $raw_tokens_latin);
			    
	    //debug
	    //pr($raw_tokens_latin);
	    //pr($raw_tokens_chinese);
	    //die();
	    
		//merge Chinese tokens and Latin's
		$raw_tokens = array_merge($raw_tokens_chinese[0], $raw_tokens_latin[0]);
		
	    //debug
	    //pr($raw_tokens);
	    //die();		   	    

		foreach($raw_tokens as $word) {

			if(strpos($word, ".") === FALSE)
				continue;

			if(!$this->isValid($word))
				continue;

			if(!isset($tokens[$word]))
				$tokens[$word] = 1;
			else
				$tokens[$word]++;

			# Delete the processed parts
			$text = str_replace($word, "", $text);

			# Also process the parts of the urls

			$url_parts = preg_split("/[^A-Za-z0-9!?\$дег'`─╓▄фЎ№▀╔щ╚ш╩ъ┴с└р┬т╙є╥Є╘Ї╟ч]/", $word);

			foreach($url_parts as $word) {

				if(!$this->isValid($word))
					continue;

				if(!isset($tokens[$word]))
					$tokens[$word] = 1;
				else
					$tokens[$word]++;

			}

		}
		
	    //debug
	    //pr($tokens);
	    //die();		

		# Raw splitting of the remaining text
		
		//exract all Chinese tokens, split each Chinese character with preg_match_all()
		preg_match_all('/[一-龥]/u', $text, $raw_tokens_chinese);
				
	    //debug
	    //pr($raw_tokens_chinese);	    
	    //die();
		
		//delete Chinese characters from the text, exract all Latin tokens
		$raw_latin = $this->strip_whitespaces(preg_replace('/[一-龥]!?/u', ' ', $text));
		$raw_tokens_latin = preg_split("/[^A-Za-z0-9!?\$дег'`─╓▄фЎ№▀╔щ╚ш╩ъ┴с└р┬т╙є╥Є╘Ї╟ч]/", $raw_latin);
		
		//merge Chinese tokens and Latin's
		$raw_tokens = array_merge($raw_tokens_chinese[0], $raw_tokens_latin);		
		
	    //debug
	    //pr($raw_tokens_latin);
	    //pr($raw_latin);
	    //die();		

		foreach($raw_tokens as $word) {

			if(!$this->isValid($word))
				continue;

			if(!isset($tokens[$word]))
				$tokens[$word] = 1;
			else
				$tokens[$word]++;

		}
		
	    //debug
	    //pr($tokens);
	    //die();		

		# Get HTML

		preg_match_all("/(<.+?>)/", $text, $raw_tokens);

		foreach($raw_tokens[1] as $word) {

			if(!$this->isValid($word))
				continue;

			# If the text has parameters, just use the tag

			if(strpos($word, " ") !== FALSE) {
				preg_match("/(.+?)\s/", $word, $tmp);
				$word = "{$tmp[1]}...>";
			}

			if(!isset($tokens[$word]))
				$tokens[$word] = 1;
			else
				$tokens[$word]++;

		}
		
		//die(pr($tokens));
		# Return a list of all found tokens
		return($tokens);

	}

	# Check if a token is valid

	function isValid($token)
	{

		# Check for a proper length
		//only when the token is NOT Chinese we need to do the check
		if(!preg_match('/[一-龥]/u', $token))
		{
			if(strlen($token) < $this->config['minSize'] or strlen($token) > $this->config['maxSize'])
			return FALSE;
		}

		# If wanted, exclude pure numbers
		if($this->config['allowNumbers'] == FALSE) {
			if(preg_match("/^[0-9]+$/", $token))
				return FALSE;
		}

		# Otherwise, the token is okay
		return TRUE;

	}

}
?>