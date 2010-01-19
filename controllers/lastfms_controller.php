<?php
/* SVN FILE:  $Id: lastfms_controller.php 27 2009-05-08 15:05:55Z  $ */
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
 * @version       $Revision: 27 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-05-08 23:05:55 +0800 (Fri, 08 May 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class LastfmsController extends AppController
{
    var $name = 'Lastfms';
    var $uses = array();
    var $__user = '';    
    var $__apiKey = '';
    
    public function index()
    {
    	//init
    	$this->__user = Configure::read('LT.lastfmUsername');
    	$this->__apiKey = Configure::read('LT.lastfmAPIKey');
    	
    	if(!$this->__user || !$this->__apiKey) return false;
    	
    	if(($lastfmRecentTracks = $this->readCache('lastfm')) === false)
		{
			App::import('Vendor', 'lastfmapi/lastfmapi');
			
			$authVars['apiKey'] = $this->__apiKey;
			$auth = new lastfmApiAuth('setsession', $authVars);
			
			$apiClass = new lastfmApi();
			$packageClass = $apiClass->getPackage($auth, 'user');
			
			$methodVars = array(
				'user' => $this->__user
			);
			
			$lastfmRecentTracks = $packageClass->getRecentTracks($methodVars);
					
			//caching
			$this->writeCache('lastfm', $lastfmRecentTracks);
		}
		
		return $lastfmRecentTracks;
    }
}
?>