<?php
/* SVN FILE:  $Id: event.php 1 2009-04-16 13:02:44Z  $ */
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
class Event extends AppModel
{	
    var $name = 'Event';
    var $validate = array(
    		'name'=>array('rule'=>'notEmpty'),
    		'value'=>array('rule'=>'notEmpty')
    	);
    var $names = array(
    		'COMMENT_DELETE_FROM_PUBLISHED'=>9,
    		'COMMENT_DELETE_FROM_SPAM'=>9,
    		'COMMENT_DELETE_FROM_TRASH'=>9, 		
    		'COMMENT_MOVE_FROM_SPAM_TO_TRASH'=>9,
    		'COMMENT_MOVE_FROM_SPAM_TO_PUBLISHED'=>9,
    		'COMMENT_MOVE_FROM_PUBLISHED_TO_SPAM'=>9,
    		'COMMENT_MOVE_FROM_PUBLISHED_TO_TRASH'=>9,
    		'COMMENT_MOVE_FROM_TRASH_TO_PUBLISHED'=>9,
    		'COMMENT_MOVE_FROM_TRASH_TO_SPAM'=>9,    		    		
    	);
    	
    /**
     * add a new event
     *
     * @date 2009-03-15
     */
    public function add($data = null)
    {
    	if(empty($data)) return false;    	
    	
    	$this->data['Event']['name'] = $data['name'];
    	$this->data['Event']['value'] = $data['value'];
    	$this->data['Event']['priority'] = $this->names[$data['name']];
    	
    	return $this->save($this->data);
    }
}
?>