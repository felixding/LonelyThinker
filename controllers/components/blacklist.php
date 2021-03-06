<?php
/* SVN FILE:  $Id: blacklist.php 1 2009-04-16 13:02:44Z  $ */
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
class BlacklistComponent extends Object
{
	/**
	 * Blacklist model instance
	 */
	var $model;
	
	/**
	 * default fields need to be validated
	 *
	 * actual fields will be read from database dynamicaly
	 */
	var $fields = array('name', 'email', 'ip', 'website', 'body', 'antispam');

	/**
	 * default fields need to be learned
	 *
	 * actual fields will be read from database dynamicaly
	 */
	var $fieldsToLearn = array('email', 'website', 'ip', 'antispam');
		
	/**
	 * data to be validated
	 */
	var $data;
	
	/**
	 * invalid fields array
	 */
	var $invalidFields = array();
	

    /**
     * Constructor
     * 
     * @date 2009-1-20
     */
    function startup()
    {
    	//register the model
    	$this->model = ClassRegistry::init('Blacklist');
    	
    	//build the patterns pool
    	$this->build();
    }

    /**
     * Build the patterns pool
     * 
     * @date 2009-1-20
     */
    function build()
    {
    	$blacklists = $this->model->findAll(array('logic'=>'deny', 'status'=>'on'));
    	$patterns = array();
    	
    	foreach($blacklists as $blacklist)
    	{
    		$patterns[$blacklist['Blacklist']['field']][] = $blacklist['Blacklist']['pattern'];
    	}
    	
		return $this->patterns = $patterns;
    }
    
    /**
     * Set the data to be validated
     *
     * data format follows Cake convention, i.e. $this->data['Comment']['name']. just pass $this->data to the function
     * @param $data array 
     * @date 2009-1-20
     */
    function set($data)
    {
		$this->data = $data;
    }
    
    /**
     * Get data model's name
     *
     * for $this->data['Comment'], model's name is Comment
     * @return String model name
     * @date 2009-1-21
     */
    function getModelNameForValidation()
    {
		return 'Comment';//key(next($this->data));
    }    
    
    /**
     * Validate a field with the blacklist patterns
     * 
     * @date 2009-1-20
     */
    function validate()
    {
    	//get data model's name
    	$modelName = $this->getModelNameForValidation();

   		//validate each field
   		foreach($this->data[$modelName] as $modelField=>$modelValue)
   		{
   			//do we have rules for this field?
   			if(array_key_exists($modelField, $this->patterns))
   			{
   				//match each pattern with this field
   				foreach($this->patterns[$modelField] as $pattern)
   				{
   					//if matched, the data is SPAM
   					if(ereg($pattern, $this->data[$modelName][$modelField]))
   					{
   						//add the field to $this->invalidFields
   						$this->invalidFields[] = $modelField;
   					}
   				}
   			}
   		}

   		//now we just check $this->invalidFields, if it had any elements, the data must be SPAM
   		return count($this->invalidFields()) ? false : true;
    }
    
    /**
     * Return invalid fields array
     * 
     * @return Array
     * @date 2009-1-21
     */
    function invalidFields()
    {
    	return $this->invalidFields;
    }
    
    /**
     * Add some data to blacklist
     * 
     * @date 2009-2-7
     */
    function learn($mode)
    {
		$this->data['Blacklist']['field'];
		$this->data['Blacklist']['pattern'];
		
		$this->model->save($this->data);
    }
    
    /**
     * Unlearn a SPAM or a HAM
     * 
     * @date 2009-1-20
     */
    function unlearn($mode)
    {
		$this->b8->unlearn($this->text, $mode);
    }
}
?>