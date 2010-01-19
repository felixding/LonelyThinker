<?php
/* SVN FILE:  $Id: events_controller.php 1 2009-04-16 13:02:44Z  $ */
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
class EventsController extends AppController
{
	var $name = 'Events';
	var $scaffold;
	var $othAuthRestrictions = '*';
	
    
    /**
     * dashboard for administrators
     *
     * @date 2009-03-15
     */
    public function dashboard()
    {	
    	/*
    	//display events whose priorities' are higher than 5
    	$this->set('events', $this->paginate(array('Event.priority'=>' >5'), '*', 'Event.priority DESC'));
    	//display logs from error.log
    	App::import('Model', 'File');
    	$path = APP.'tmp'.DS.'logs'.DS.'error.log';
    	$file = new File($path);
    	$file->open();
    	$logs = $file->read();
    	//pr($logs);
    	*/
    }
    
    /**
     * get events count based on different name
     *
     * @param $name event name
     * @return Int events count
     * @date 2009-03-16
     */
    public function getCount($name = null)
    {
    	if(!$name || in_array($name, $this->Event->names)) return false;
    	
    	return $this->Event->findCount(array('Event.name'=>$name));
    }
}
?>