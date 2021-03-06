<?php
/* SVN FILE:  $Id: visitor_sense.php 1 2009-04-16 13:02:44Z  $ */
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
class VisitorSenseComponent extends Object
{
	/**
	 * Model instance
	 */
	var $model;
	
	/**
	 * Controller instance
	 */
	var $controller;	
	
	/**
	 * Components
	 */
	var $components = array('othAuth');//, 'Session'
	
	/**
	 * Sensors object
	 */    
    var $sensors;	

	/**
	 * Switch button for enabling or disabling the sensors
	 * 1 - enabled (default)
	 * -1 - disabled because the administrator is logged in
	 * -2 - disabled because the administrator is at /users/login
	 */    
    var $status = 1;


    /**
     * Constructor
     * 
     * @date 2009-1-20
     */
    public function init()//&$controller
    {
    	//register the model
    	$this->model = ClassRegistry::init('Sensor');
    	
    	//register the controller
    	//$this->controller = $controller;
    	
    	//build the sensors matrix
    	$this->build();    	

    	//run the hook
    	//$this->launch();
    }
    
    /**
     * Build sensors matrix - oh yeah, what a cool name!
     * 
     * @date 2009-1-27
     */    
    private function build()
    {
    	$this->sensors->matrix = $this->model->findAll(array('Sensor.status'=>'on'));
    }
    
    /**
     * load a sensor's trigger or an action
     * 
     * @param $type String the element type of the sensor, either 'trigger' or 'action'
     * @param $name String the element name of the sensor, which follows CakePHP convention. For example, if the class name is MatchReferrer, then filename should be match_referrer.php.
     * @date 2009-2-25
     */
    private function load($type, $name)
    {
    	if(!App::import('Vendor', 'sensors'.DS.Inflector::pluralize($type).DS.$name)) return false;
    	$this->sensors->{Inflector::pluralize($type)}->{$name} = new $name();
    	
    	//this line just sucks!
    	$this->sensors->{Inflector::pluralize($type)}->{$name}->controller = $this->controller;
    	
    	return true;
    }

    /**
     * inform the administrator that certain sensors are disabled when he/she is in
     * 
     * @date 2009-3-4
     */
    private function inform()
    {
    	//when the administrator is logged in
    	Configure::write('LT.VisitorSense', 'disabled');
    }
    
    /**
     * get sensor's running status
     * disable the sensors when an administrator is logged in, or he/she is accessing the log in page
     * 
     * @date 2009-3-3
     */
    private function getStatus()
    {
    	//when the administrator is logged in
    	if($this->othAuth->sessionValid()) return $this->status = -1;
    	
    	//when he/she is accessing the log in page
    	if(Router::url() == Router::url($this->othAuth->login_page)) return $this->status = -2;
    	
    	//ok, we don't need to disable the sensors
    	return $this->status = 1;
    }    
        
    /**
     * Launch the sensors
     * 
     * @date 2009-3-3
     */
    public function launch()
    { 
		foreach($this->sensors->matrix as $sensor)
		{
			//sensor properties
			$sensorName = Inflector::camelize($sensor['Sensor']['name']);
			$sensorTrigger = $sensor['Sensor']['trigger'];
			$sensorTriggerOption = $sensor['Sensor']['trigger_option'];
			$sensorAction = Inflector::camelize($sensor['Sensor']['action']);
			$sensorActionOption = $sensor['Sensor']['action_option'];
			
			//load the sensor
			if(!$this->load('trigger', $sensorTrigger) || !$this->load('action', $sensorAction)) $this->cakeError('error404');
			
			//sense
			if(method_exists($this->sensors->triggers->{$sensorTrigger}, 'sense'))
			{
				if($this->sensors->triggers->{$sensorTrigger}->sense($sensorTriggerOption))
				{
					if(method_exists($this->sensors->actions->{$sensorAction}, 'act'))
					{
						//do we run?
    					switch($this->getStatus())
    					{
    						case 1:
    							//pass the options to sensor action
    							$this->sensors->actions->{$sensorAction}->options = array('trigger'=>$sensorTrigger, 'TriggerOption'=>$sensorTriggerOption);
    							$this->sensors->actions->{$sensorAction}->act($sensorActionOption);
    						break;
    						
    						case -1:
    							//inform the administrator that certain sensors are disabled when he/she is in
    							$this->inform();
    							return;
    						break;
    						
    						case -2:
    							//well, just disable the sensors
    							return;
    						break;
    						
    						default:
    						break;
    					}						
					}
					else
					{
						$this->cakeError('error404');
					}
				}
			}
			else
			{
				$this->cakeError('error404');
			}		
			
			//gc
			unset($sensorName, $sensorTrigger, $sensorAction, $sensorOption);
		}
	}    
    
    /**
     * get all sensors' triggers or actions
     * 
     * @param $type String the element type of the sensor, either 'trigger' or 'action'     
     * @return triggers or actions array in format 'A trigger name'=>'TriggerName'
     * @date 2009-3-4
     */
    public function get($type)
    {
    	$path = APP.'vendors'.DS.'sensors'.DS.Inflector::pluralize($type);
    	
    	App::import(array('Folder', 'File'));
    	$folder = new Folder();
    	$file = new File($path);
    	
    	$elements = array();
    	
    	$tree = $folder->tree($path);
    	
    	foreach($tree[1] as $f)
    	{
    		$file->name = basename($f);
    		$file->info['extension'] = 'php';
    		
    		$element = Inflector::camelize($file->name());
    		
    		$elements[$element] = $element;
    	}
    	
    	return $elements;
    }
}
?>