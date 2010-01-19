<?php
/* SVN FILE:  $Id: comment.php 42 2009-09-24 12:53:07Z  $ */
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
 * @version       $Revision: 42 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-09-24 20:53:07 +0800 (Thu, 24 Sep 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class Comment extends AppModel
{
    var $name = 'Comment';
	var $belongsTo = array('Post');
    var $validate = array(
       'name' => array(
       	'notEmpty'=>array('rule'=>'notEmpty', 'message'=>'You forgot to tell us your name'),
       	'maxLength'=>array('rule'=>array('maxLength', 50), 'message'=>'What? 50 characters are not enough for your name?!')
       	),
       'email' => array(
       	'email'=>array('rule'=>'email', 'message'=>"Email is required. Don't worry, it won't be public"),
       	'maxLength'=>array('rule'=>array('maxLength', 255), 'message'=>'What? 255 characters are not enough for your email address?!')
       	),       	
       'ip' => array('rule'=>'ip', 'message'=>"Well, I don't understand your IP"),
       'body' => array('rule'=>'notEmpty', 'message'=>'Nothing to say?'),
       'post_id' => array('rule'=>'validatePostId', 'message'=>'Hacking attempt!')
   );
   
    /**
     * before delete a comment, save the delete action as a new event
     *
     * @return Boolean true on new event created
     * @date 2009-03-15
     */
    public function beforeDelete()
    {
    	App::import('Model', 'Event');
    	$this->Event = new Event();
    	
    	$comment = $this->read(null, $this->id);
    	$name = 'COMMENT_DELETE_FROM_'.strtoupper($comment['Comment']['status']);
   	
    	$data = array(
    				'name' => $name,
    				'value'=> $this->id,
    				'priority'=> $this->Event->names[$name]
    			);
    	
    	if($this->Event->add($data)) return true;
    	else return false;
    }   
         
	/**
	 * the post with speicific id must exist
	 *
	 */
	public function validatePostId($data)
	{
		return $this->Post->hasAny(array('Post.id'=>$data, 'Post.status'=>'published', 'Post.comment'=>'on'));
	}

	/**
	 * get the resource link of MySQL connection
	*/
	public function getDBOResourceLink()
	{
		return $this->getDataSource()->connection;	
	}
}
?>