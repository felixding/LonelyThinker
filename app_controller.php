<?php
/* SVN FILE:  $Id: app_controller.php 41 2009-08-04 13:12:50Z  $ */
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
 * @version       $Revision: 41 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-08-04 21:12:50 +0800 (Tue, 04 Aug 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class AppController extends Controller
{
	var $components  = array('othAuth', 'VisitorSense', 'RequestHandler', 'Security');
	var $helpers = array('Html', 'Form', 'Javascript', 'othAuth', 'Widget', 'Paginator', 'Text', 'Asset');
	var $othAuthRestrictions = array('add','edit','delete');
	var $uses = array('Setting');

	
	/**
	 * callbacks
	 */	
	
    public function beforeFilter()
    {
    	//user auth
        $auth_conf = array(
                    'mode'  => 'oth',
                    'login_page'  => '/users/login',
                    'logout_page' => '/users/logout',
                    'access_page' => '/posts/add',
                    'hashkey'     => 'MySEcEeTHaSHKeYz1',
                    'noaccess_page' => '/users/noaccess',
                    'strict_gid_check' => false
                 );                  
        
        $this->othAuth->controller = &$this;
        $this->othAuth->init($auth_conf);
        $this->othAuth->check();
                
        //make settings below are loaded only once
    	//if(!Configure::read('LT.initialized'))
		//{
			//load config
	        $this->Setting->get();
	        
	        //launch VisitorSense
	        $this->othAuth->controller = &$this;
	        $this->VisitorSense->init();
			$this->VisitorSense->launch();

	        Configure::write('LT.initialized', true);
	    //}
        
        //security blackhole
        //$this->Security->blackHoleCallback = 'denied';
        
        //admin specific settings
        if($this->othAuth->sessionValid())
        {
        	//layout
        	$this->layout = 'admin';
		}        
    }    
    
	public function beforeRender()
	{
		//render enums
		//$this->renderEnums();
	
		//set the referrer, many features need it
		$this->set('referrer',  Router::url($this->referer(), true));
		
		//page title seperator
		Configure::write('pageTitleSeperator', ' - ');
		
		//don't mess up ajax
		if($this->RequestHandler->isAjax()) Configure::write('debug', 0);
	}
	
    /**
     * blackhole for CSRF or other attacks
     *
     * I don't want to waste time in designing a nice view for those attackers
     */
    public function denied()
    {
    	die('Access denied!');
    }
    
    /**
     * throws the invalid fields
     *
     * @return Boolean true if the data validates
     * @date 2009-04-21
     */
    public function validates()
    {
    	if(!$this->{$this->modelClass}->validates())
    	{
    		//$this->{$this->modelClass}->invalidFields = $this->{$this->modelClass}->invalidFields();
    		$this->set('invalidFields', $this->{$this->modelClass}->invalidFields());
    		return false;
    	}
    	
    	return true;
    }

	/*
	 * public function for adding an item
	 *
	 * @date 2009-04-23
	 */
	public function add()
	{
		if(!empty($this->data))
		{
			if($this->{$this->modelClass}->save($this->data))
			{
				$this->redirect('/'.Inflector::pluralize(Inflector::underscore($this->modelClass)).'/');				
			}
			else
			{
				$this->set(Inflector::pluralize(Inflector::underscore($this->modelClass)), $this->data);
			}
			
			if($this->RequestHandler->isAjax()) $this->renderView('saved');
		}		
	}
	
	/*
	 * public function for editing an item model
	 *
	 * @date 2009-04-23
	 */
	public function edit($id = null)
	{
		//does the post exist?
		if(!empty($this->data)) $this->{$this->modelClass}->id = $this->data[$this->modelClass]['id'];
		else $this->{$this->modelClass}->id = $id;
		
		if(!$item = $this->{$this->modelClass}->read()) $this->cakeError('error404');
			
		if(!empty($this->data))
		{
			$item = $this->data;
			
			if($this->{$this->modelClass}->save($this->data))
			{
				$this->redirect('/'.Inflector::pluralize(Inflector::underscore($this->modelClass)));
				return true;
			}
		}
		
		$this->set(lcfirst($this->modelClass), $item);
		$this->render('add');
	}
	    
	/**
	 * public function for showing the model's index page
	 *
	 * @date 2009-04-23
	 */
	
	public function index()
	{
		$this->{$this->modelClass}->recursive = -1;
		$data = $this->paginate($this->modelClass);

		$this->set(Inflector::pluralize(lcfirst($this->modelClass)) , $data);			
	}    
    
	/**
	 * public function for deleting an item
	 *
	 * @date 2009-02-22
	 */
	
	public function delete()
	{
		//if submitted?
	    if(!empty($this->data))
	    {
			$this->{$this->modelClass}->id = $this->data[$this->modelClass]['id'];
				
			if(!$this->{$this->modelClass}->hasAny(array('id'=>$this->{$this->modelClass}->id)))
			{
				$this->renderError('error404');
				return;
			}
						
			$this->{$this->modelClass}->del($this->{$this->modelClass}->id);
			
			$this->renderView('deleted', '/generic/delete/');
			
			return;
		}

		$this->renderView('delete', '/generic/delete/');				
	}

	/**
	 * get cache's key
	 *
	 * @param $key the unique key for each cache file
	 * @todo check if a file with same name exists
	 * @date 2009-03-13
	 */
	private function getCacheKey($key)
	{
		if(empty($key)) return false;
		
		//get file name
		$filename = ereg_replace('[/-]', '_', $this->here).'_'.$key;
		$filename = substr($filename, 1, strlen($filename));
		
		//return the filename/key
		return $filename;
	}
		
	/**
	 * cache model data
	 *
	 * @param $data the data to cache
	 * @param $key the unique key for each cache file
	 * @todo check if a file with same name exists
	 * @date 2009-03-13
	 */
	public function writeCache($key, $data)
	{
		if(empty($key) || empty($data)) return false;
		
		//get file name
		$filename = $this->getCacheKey($key);
		
		Cache::write($filename, $data);
		
		//return the filename/key
		return $filename;
	}
	
	/**
	 * read cache model data
	 *
	 * @param $key the unique key for each cache file
	 * @todo check if a file with same name exists
	 * @date 2009-03-13
	 */
	public function readCache($key)
	{
		if(empty($key)) return false;
		
		//get file name
		$filename = $this->getCacheKey($key);
			
		//return the data
		return Cache::read($filename);
	}	
    
	/**
     * render the given view for either non-ajax request or ajax request
	 *
	 * @param $template String the name of the template
	 * @date 2009-03-06
	 */
	public function renderView($template = null, $path = null)
	{
		if(!$template) return false;
		
		$path = isset($path) ? $path : $this->params['action'] . '/';
	
		if($this->RequestHandler->isAjax()) $this->render($path.'json/'.$template, 'ajax');
		else $this->render($path.'html/'.$template);
	}
	
	/**
     * render an error view for either non-ajax request or ajax request
	 *
	 * @param $template String the name of the template
	 * @date 2009-03-06
	 */
	public function renderError($template = null)
	{
		if(!$template) return false;
	
		if($this->RequestHandler->isAjax()) $this->render('/errors/json/'.$template, 'ajax');
		else $this->cakeError($template);
	}	
	
	/**
	 * temp function for displaying enum in scaffolding
	 *
	 * @date 2009-1-27
	 */
	public function renderEnums()
	{
		foreach($this->modelNames as $model)
		{
			foreach($this->$model->_schema as $var => $field)
			{
				if(strpos($field['type'], 'enum') === false) continue;
	
				preg_match_all("/\'([^\']+)\'/", $field['type'], $strEnum);
	
				if(is_array($strEnum[1]))
				{
					$varName = Inflector::camelize(Inflector::pluralize($var));
					$varName[0] = strtolower($varName[0]);
					$this->set($varName, array_combine($strEnum[1], $strEnum[1]));
				}
			}  
		}
	}
  


	/**
	 * get ENUM fields
	 *
	 * @date 2009-02-22
	 */
	public function getEnumFields($fieldName = 'field')
	{
		//get blacklist fields
	    preg_match_all("/\'([^\']+)\'/", $this->{$this->modelClass}->_schema[$fieldName]['type'], $strEnum);
	    
        if(is_array($strEnum[1])) return array_combine($strEnum[1], $strEnum[1]);
    }
    
	/**
	 * Write the time point into session which indicates when the user requests the page, this is for dealing with Ajax comment
	 *
	 * @param $id Int Post id
	 * @date 2009-1-24
	 */
	public function setTimePoint($id = null)
	{
		if(!$id) return false;
		$this->Session->write('TimePoint.'.$id, time());
	}
	
    /**
     * Get the time point when the user requested the page
     * 
     * @date 2009-1-21
     */
    function getTimePoint($id = null)
    {
    	if(!$id) return false;
		return $this->Session->read('TimePoint.'.$id); 
    }	  
	
}

/* Works out the time since the entry post, takes a an argument in unix time (seconds) */
function time_since($original) {
    // array of time period chunks
    $chunks = array(
        array(60 * 60 * 24 * 365 , __('year', true)),
        array(60 * 60 * 24 * 30 , __('month', true)),
        array(60 * 60 * 24 * 7, __('week', true)),
        array(60 * 60 * 24 , __('day', true)),
        array(60 * 60 , __('hour', true)),
        array(60 , __('minute', true)),
    );
    
    $today = time(); /* Current unix time  */
    $since = $today - $original;
    
    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        
        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            // DEBUG print "<!-- It's $name -->\n";
            break;
        }
    }
    
    //$print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    $name = ($name == __('month', true)) ? __('monthes', true) : $name;
    $print = ($count == 1) ? '1'.$name : "$count$name";
    
    if ($i + 1 < $j) {
        // now getting the second item
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];
        
        // add second item if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print .= ($count2 == 1) ? ', 1'.$name2 : ", $count2 $name2";
        }
    }
    return $print . __('ago', true);   
}

    /**
     * home-cooked lcfirst()
     *
     * @date 2009-04-27
     
    function lcfirst($string = null)
    {
    	if(!$string) return null;
    	
		//dont't know how to do it with RegEx, :p
		return preg_replace('/(^[A-Z])/', strtolower(substr($string, 0, 1)), $string);
    }*/
?>