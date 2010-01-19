<?php
/* SVN FILE:  $Id: settings_controller.php 27 2009-05-08 15:05:55Z  $ */
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
class SettingsController extends AppController
{
	var $name = 'Settings';
	var $othAuthRestrictions = '*';
	
	public function index()
	{
		//$this->set('settings', Set::extract('{n}.Setting', $this->Setting->find('all', array('conditions'=>array('Setting.type'=>'user')))));
		
		if(!empty($this->data))
		{
			foreach($this->data['Setting'] as $key=>$value)
			{
				//skip if nothing has changed
				if(Configure::read('LT.'.$key) == $value) continue;

				//find the Id
				$record = $this->Setting->findByKey($key);
				
				//found?
				if(isset($record) && !empty($record))
				{
					//update the value
					$data = array();
					$data['Setting']['id'] = $record['Setting']['id'];
					$data['Setting']['value'] = $value;
					
					//query
					$this->Setting->save($data);
					
					//gc
					$this->Setting->id = null;
					$data = null;
				}
			}
			
			//refresh
			$this->Setting->get();
			$this->set('saved', true);
		}
	}
}
?>