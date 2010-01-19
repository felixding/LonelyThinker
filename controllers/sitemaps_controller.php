<?php
/* SVN FILE:  $Id: sitemaps_controller.php 5 2009-04-21 15:43:15Z  $ */
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
 * @version       $Revision: 5 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-04-21 23:43:15 +0800 (Tue, 21 Apr 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class SitemapsController extends AppController{

	var $name = 'Sitemaps';
	var $uses = array('Post');
	var $helpers = array('Time');
	var $components = array('RequestHandler');
	var $cacheAction = null;

	public function index ()
	{
	
		$this->set('posts', $this->Post->find('all', array( 'conditions' => array('status'=>'published'), 'fields' => array('created','slug'), 'order' => 'Post.created DESC')));

		//debug logs will destroy xml
		Configure::write ('debug', 0);
		
		$this->render('xml/index', 'xml/default');
	}
}
?>