<?php
/* SVN FILE:  $Id: match_ip.php 1 2009-04-16 13:02:44Z  $ */
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
class MatchIp
{
    /**
     * Every trigger must have a 'sense' method
     * 
     * @date 2009-3-4
     */
    public function sense($ip = null)
    {
    	if(!$ip) return false;

    	return ereg($ip, RequestHandlerComponent::getClientIP());
    }  
}
?>