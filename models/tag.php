<?php
/* SVN FILE:  $Id: tag.php 39 2009-07-16 10:03:24Z  $ */
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
 * @version       $Revision: 39 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-07-16 18:03:24 +0800 (Thu, 16 Jul 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */ 
class Tag extends AppModel
{
	var $name = 'Tag'; 	
    var $hasAndBelongsToMany = array(
    		'Post'=>array(
    			'conditions' => array('Post.status' => 'published'),
	    		'order' => 'Post.created DESC'
    		)
    	);
	var $validate = array(
        'title' => array('rule'=>'notEmpty', 'message'=>'You forgot to name a title for the tag'),
        'slug' => array(
       		'notEmpty'=>array('rule'=>'notEmpty', 'message'=>'You forgot to give a slug for the tag'),
       		'isUnique'=>array('rule'=>'isUnique', 'message'=>'This slug has already been taken'),
       		'custom'=>array('rule' =>array('custom', '/^[a-z0-9(\s)]{3,}$/'), 'message'=>'A valid slug must be 3 characters long at least, and consist of lower case English words, numbers and white spaces inbetween only')
       		)
    );

	/**
	 * callbacks
	 */
    public function beforeSave()
    {
    	$this->data['Tag']['slug'] = Inflector::slug($this->data['Tag']['slug'], '-');
    	return true;
    }
}
?>