<?php
/* SVN FILE:  $Id: link_categories_controller.php 28 2009-05-13 12:52:14Z  $ */
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
 * @version       $Revision: 28 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-05-13 20:52:14 +0800 (Wed, 13 May 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class LinkCategoriesController extends AppController
{
	var $name = 'LinkCategories';
	var $scaffold;
	var $othAuthRestrictions = array('index', 'add', 'edit', 'delete');
	
	/**
	 * interface for getting a link category
	 *
	 * @date 2009-04-10
	 */
	public function get($linkCategoryId = null)
	{
		if(($linkCategories = $this->readCache('linkCategories')) === false)
		{	
			if(!isset($linkCategoryId) || empty($linkCategoryId)) $linkCategories = $this->LinkCategory->find('all');
			else $linkCategories = $this->LinkCategory->read(null, $linkCategoryId);
			
			$this->writeCache('linkCategories', $linkCategories);
		}	

		return $linkCategories;
	}
}
?>