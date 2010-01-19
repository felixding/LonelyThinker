<?php
/* SVN FILE:  $Id: match_referrer.php 1 2009-04-16 13:02:44Z  $ */
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
class MatchReferrer
{	   
    /**
     * Every trigger must have a 'sense' method
     * 
     * @param $referrer String Referrer URL that defined by user
     * @return Boolean Return true when actual Referrer matches with the pre-defined one, otherwise return false
     * @date 2009-3-12
     */
    public function sense($referrer = null)
    {
    	if(!$referrer || empty($_SERVER["HTTP_REFERER"])) return false;
		return ereg($referrer, $_SERVER["HTTP_REFERER"]);
    }
}
?>