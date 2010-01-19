<?php
/* SVN FILE:  $Id: spam_shield.php 1 2009-04-16 13:02:44Z  $ */
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
class SpamShieldComponent extends Object
{
	/**
	 * b8 instance
	 */
	var $b8;
	
	/**
	 * standard rating
	 *
	 * ratings which are higher than this one will be considered as SPAM
	 */
	var $standardRating = 0.7;
	
	/**
	 * text to be classified
	 */
	var $text;
	
	/**
	 * rating of the text
	 */
	var $rating;
	

    /**
     * Constructor
     * 
     * @date 2009-1-20
     */
    function startup(&$controller)
    {
    	//register a CommentModel to get the DBO resource link
    	$comment = ClassRegistry::init('Comment');

		//import b8 and create an instance
		App::import('Vendor', 'b8/b8');
		$this->b8 = new b8($comment->getDBOResourceLink());
		
		//set standard rating
		$this->standardRating = Configure::read('LT.bayesRating') ? Configure::read('LT.bayesRating') : $this->standardRating;
    }

    /**
     * Set the text to be classified
     * 
     * @param $text String the text to be classified
     * @date 2009-1-20
     */
    function set($text)
    {	
		$this->text = $text;
    }
    
    /**
     * Get Bayesian rating
     * 
     * @date 2009-1-20
     */
    function rate()
    {	
		//get Bayes rating and return
		return $this->rating = $this->b8->classify($this->text);
    }
    
    /**
     * Validate a message based on the rating, return true if it's NOT a SPAM
     * 
     * @date 2009-1-20
     */
    function validate()
    {
		return $this->rate() < $this->standardRating;
    }
    
    /**
     * Learn a SPAM or a HAM
     * 
     * @date 2009-1-20
     */
    function learn($mode)
    {
		return $this->b8->learn($this->text, $mode);
    }
    
    /**
     * Unlearn a SPAM or a HAM
     * 
     * @date 2009-1-20
     */
    function unlearn($mode)
    {
		return $this->b8->unlearn($this->text, $mode);
    }
}
?>