<?php
/* SVN FILE:  $Id: redirect_to_domain.php 1 2009-04-16 13:02:44Z  $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 5
 *
 * Licensed under The BSD License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2007-2009, Felix Ding (http://dingyu.me)
 * @link          http://lonelythinker.org Project LonelyThinker
 * @package       LonelyThinker
 * @author		  $LastChangedBy: $
 * @version       $Revision: 1 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-04-16 21:02:44 +0800 (四, 16  4 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class RedirectToDomain
{
    /**
     * Every action must have an 'act' method
     * 
     * @date 2009-3-4
     */
    function act($domain = null)
    {
    	$currentUrl = $this->curPageURL();
    	
    	$url = parse_url($currentUrl);
   		$currentDomain = $url['host'];
    	
    	$newUrl = str_replace($currentDomain, $domain, $currentUrl);
    	
    	header('Location: '.$newUrl);
    }
    
    /**
     * get current url
     * courtesy of http://www.webcheatsheet.com/PHP/get_current_page_url.php
     */
    function curPageURL()
    {
		$pageURL = 'http';
		
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
		else
		{
		    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		
		return $pageURL;
	}    
}
?>