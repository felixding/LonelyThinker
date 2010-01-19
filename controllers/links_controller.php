<?php
/* SVN FILE:  $Id: links_controller.php 24 2009-05-08 13:23:53Z  $ */
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
 * @version       $Revision: 24 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-05-08 21:23:53 +0800 (Fri, 08 May 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class LinksController extends AppController
{
	var $name = 'Links';
	var $scaffold;
	var $othAuthRestrictions = array('index', 'add', 'edit', 'delete');	
	
	public function index()
	{
		$this->{$this->modelClass}->recursive = 1;
		$data = $this->paginate($this->modelClass);

		$this->set(Inflector::pluralize(lcfirst($this->modelClass)) , $data);			
	}	
}
?>