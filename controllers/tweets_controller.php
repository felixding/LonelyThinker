<?php
/* SVN FILE:  $Id: tweets_controller.php 11 2009-04-24 04:02:49Z  $ */
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
 * @version       $Revision: 11 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-04-24 12:02:49 +0800 (Fri, 24 Apr 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */ 
class TweetsController extends AppController
{
    var $name = 'Tweets';
    var $components = array('Twitter');
    var $helpers = array('Time', 'Html');
    var $uses = array();
    
    public function index()
    {
    	//init
    	$this->__username = Configure::read('LT.twitterUsername');
    	$this->__password = null;
    	
    	if(!$this->__username) return false;    

    	if(($tweets = $this->readCache('tweets')) === false)
		{
	        $this->Twitter->username = $this->__username;
	        $this->Twitter->password = $this->__password;
	        
	        $tweets = $this->Twitter->status_user_timeline(
	                        $this->Twitter->username ,
	                        array('count' => 5)
	        );
					
			//caching
			$this->writeCache('tweets', $tweets);
		}

        if(array_key_exists('statuses', $tweets)) return $tweets['statuses']['status'];        
    }
}
?>