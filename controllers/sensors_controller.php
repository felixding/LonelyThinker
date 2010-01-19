<?php
/* SVN FILE:  $Id: sensors_controller.php 1 2009-04-16 13:02:44Z  $ */
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
class SensorsController extends AppController
{
	var $name = 'Sensors';
	var $othAuthRestrictions = '*';
	var $paginate = array('limit' => 15, 'page' => 1, 'order'=>array('Sensor.created' => 'ASC'));

	/**
	 * list all sensors
	 */
	public function index()
	{
		$this->set('Sensors', $this->paginate('Sensor'));
	}
	
	/**
	 * add a sensor
	 */
	public function add()
	{
		//get sensor's enum fields	    
        $this->set('statusEnumValues', $this->getEnumFields('status'));
        
		//get all sensors' triggers and actions
        $this->set('triggers', $this->VisitorSense->get('trigger'));
        $this->set('actions', $this->VisitorSense->get('action'));  
        	
		//if submitted?
	    if(!empty($this->data['Sensor']))
	    {
	    	//set data, validate and save
	    	//App::import('Sanitize');
	    	//$this->data = Sanitize::clean($this->data);
	    	
	    	$this->Sensor->set($this->data);
	    	
	    	if($this->Sensor->validates())
	    	{
	    		$existingSensor = $this->Sensor->find(
	    			array(
	    				'Sensor.trigger'=>$this->data['Sensor']['trigger'],
		    			'Sensor.trigger_option'=>$this->data['Sensor']['trigger_option'],
		    			'Sensor.action'=>$this->data['Sensor']['action'],
	    				'Sensor.action_option'=>$this->data['Sensor']['action_option']
	    			)
	    		);
	    		
	    		if(!$existingSensor)
	    		{
	    			if($this->Sensor->save($this->data))
	    			{
						$this->renderView('saved');
					}
					else
					{
						//@todo exception handling
					}
				}
				else
				{
					//a rule with same field and pattern exists, I think duplicated data is stupid, let's ask user for further instructions, which include
					//1, edit the existing rule
					//2, go back to list
					$this->set('existingSensor', $existingSensor);		
					$this->renderView('existed');
				}
	    	}
	    	else
	    	{
	    		$this->set('invalidFields', $this->Sensor->invalidFields());
				$this->renderView('invalid');			    	
	    	}
	    	
	    	return;
	    }
	    
	    //render
		$this->render('add/add');
	}
	
	/**
	 * edit a sensor
	 *
	 * @date 2009-02-22
	 */
	
	public function edit($id = null)
	{
		//does the pattern exist?
		if(!empty($this->data['Sensor'])) $this->Sensor->id = $this->data['Sensor']['id'];
		else $this->Sensor->id = $id;
		
		if(!$sensor = $this->Sensor->read()) $this->cakeError('error404');
		
		//get sensor's enum fields	    
        $this->set('statusEnumValues', $this->getEnumFields('status'));
        
		//get all sensors' triggers and actions
        $this->set('triggers', $this->VisitorSense->get('trigger'));
        $this->set('actions', $this->VisitorSense->get('action'));        
		
		//pass the var
		$this->set('sensor', $sensor);
		
	    
	    //if submitted?
	    if(!empty($this->data['Sensor']))
	    {
			//set data, validate and save
	    	//App::import('Sanitize');
	    	//$this->data = Sanitize::clean($this->data);
	    	
	    	$this->Sensor->set($this->data);
	    	
	    	if($this->Sensor->validates())
	    	{
	    		//any same sensor exists?
	    		$existingSensor = $this->Sensor->find(
	    			array(
	    				'Sensor.trigger'=>$this->data['Sensor']['trigger'],
		    			'Sensor.trigger_option'=>$this->data['Sensor']['trigger_option'],
		    			'Sensor.action'=>$this->data['Sensor']['action'],
	    				'Sensor.action_option'=>$this->data['Sensor']['action_option']
	    			)
	    		);
	    		
	    		if(!$existingSensor || $existingSensor['Sensor']['id'] == $this->Sensor->id)
	    		{
	    			if($this->Sensor->save($this->data))
	    			{
						$this->renderView('saved', 'add/');
					}
					else
					{
						//@todo exception handling
					}
				}
				else
				{
					//a rule with same field and pattern exists, I think duplicated data is stupid, let's ask user for further instructions, which include
					//1, edit the existing rule
					//2, go back to list
					$this->set('existingSensor', $existingSensor);					
					$this->renderView('existed', 'add/');
				}
	    	}
	    	else
	    	{
	    		$this->set('invalidFields', $this->Sensor->invalidFields());
				$this->renderView('invalid', 'add/');
	    	}
	    	
	    	return;
	    }
	    
	    //render
		$this->render('add/add');
	}		
}
?>