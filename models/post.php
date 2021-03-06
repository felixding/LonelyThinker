<?php
/* SVN FILE:  $Id: post.php 9 2009-04-23 13:45:47Z  $ */
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
 * @version       $Revision: 9 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-04-23 21:45:47 +0800 (Thu, 23 Apr 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class Post extends AppModel
{
	var $name = 'Post';
	var $hasMany = array(
			'Comment'=>array(
				'conditions' => array('Comment.status' => 'published')
			),
			'RelatedPost'
		);
	var $hasAndBelongsToMany = array('Tag');
	var $validate = array(
        'title' => array('rule'=>'notEmpty', 'message'=>'You forgot to name a title for the post'),
        'slug' => array(
       		'notEmpty'=>array('rule'=>'notEmpty', 'message'=>"You forgot to give a slug for the post"),
       		'isUnique'=>array('rule'=>'isUnique', 'message'=>"This slug has already been taken"),
       		'custom'=>array('rule' =>array('custom', '/^[a-z0-9(\-)]{3,}$/'), 'message'=>"A valid slug must be 3 characters long at least, and consist of lower case English letters, numbers and '-' inbetween only")
       		),
        'body' => array('rule'=>'notEmpty', 'message'=>'Nothing to say?')
    );
   
	/**
	 * callbacks
	 */
    public function beforeValidate()
    {
   		//if the post doesn't belong to any tags, we just invalidate the form    	
    	if(empty($this->data['Tag']['Tag']))
	   	{
	   		$this->invalidate('tag');
		}
				
		//slugify
    	$this->data['Post']['slug'] = Inflector::slug($this->data['Post']['slug'], '-');
    	return true;
    }
    
    public function afterSave()
    {
		//clean the cache
		Cache::clear();
		    
    	//(re)generate related posts' cache
    	$this->requestAction('/related_posts/index/'.$this->getLastInsertId());
    }
	
}
?>