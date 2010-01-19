<?php
/* SVN FILE: $Id: routes.php 5 2009-04-21 15:43:15Z  $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 5 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-04-21 23:43:15 +0800 (Tue, 21 Apr 2009) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
 	/**
 	 * Set default controller routes 
 	 */
	Router::connect('/', array('controller' => 'posts', 'action' => 'index'));
	
	/**
	 * M-O
	 */
	Router::connect('/m-o', array('controller' => 'blacklists', 'action' => 'brainpower'));
	Router::connect('/m-o/brainpower', array('controller' => 'blacklists', 'action' => 'brainpower'));
	Router::connect('/m-o/statistics', array('controller' => 'blacklists', 'action' => 'statistics'));
	Router::connect('/m-o/blacklist', array('controller' => 'blacklists', 'action' => 'index'));
	Router::connect('/m-o/blacklist/add', array('controller' => 'blacklists', 'action' => 'add'));
	Router::connect('/m-o/blacklist/edit/:id', array('controller' => 'blacklists', 'action' => 'edit'), array('id'=>$ID));
	Router::connect('/m-o/blacklist/delete/:id', array('controller' => 'blacklists', 'action' => 'delete'), array('id'=>$ID));
	
	/**
	 * Delete action
	 */
	Router::connect('/:controller/delete/:id', array('controller' => $this->params['controller'], 'action' => 'delete'), array('id'=>$ID));
	
	/**
	 * Dashboard for administrators
	 */	
	
	/**
	 * rss sync
	 */
	Router::connect('/feed/*', array('controller' => 'posts', 'action' => 'feed'));
	
	/**
	 * ...and connect the rest of 'Pages' controller's urls.
	 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	
	/**
	 * XML sitemaps
	 */
	Router::connect('/sitemap.xml', array('controller' => 'sitemaps', 'action' => 'index')); 
?>