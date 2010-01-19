<?php
/* SVN FILE:  $Id: redirect_to_url.php 1 2009-04-16 13:02:44Z  $ */
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
class RedirectToUrl
{
	/**
	 * optional var for advanced actions
	 */
	var $options = array();
	
    /**
     * Every action must have an 'act' method
     * 
     * @date 2009-3-4
     */
    function act($url)
    {    
    	header('Location:'.$url);
    }
}
?>