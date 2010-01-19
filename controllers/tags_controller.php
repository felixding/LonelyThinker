<?php
/* SVN FILE:  $Id: tags_controller.php 6 2009-04-23 12:56:27Z  $ */
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
 * @version       $Revision: 6 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-04-23 20:56:27 +0800 (Thu, 23 Apr 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class TagsController extends AppController
{
	var $name = 'Tags';
	var $othAuthRestrictions = array('index', 'add', 'edit', 'delete');
	var $helpers = array('Geshi');
	var $scaffold;
	
	/**
	 * render the tag view page
	 *
	 * I have to say that the approach below is not beautiful at all, I wish Cake could have a better built-in solution for this sort of scenarios
	 * @date 2008-12-29
	 */
	
	public function view($slug = null)
	{
		if(($tag = $this->readCache('tag')) === false)
		{
			//get the tag's id, trigger a 404 error if tag doesn't exist
			$this->Tag->recursive = 0;
			
			if(!$tag = $this->Tag->findBySlug($slug))
			{
				$this->cakeError('error404');
				return false;
			}
					
			//caching
			$this->writeCache('tag', $tag);
		}
		
		$tagId = $tag['Tag']['id'];

		if(($tags = $this->readCache('tags')) === false)
		{		
			$tags = $this->Tag->findAll();
			
			//caching
			$this->writeCache('tags', $tags);
		}
			
		if(($postsTags = $this->readCache('postsTags')) === false)
		{		
			//set pagination parameters
			$this->paginate = array('limit' => 5, 'page' => 1, 'order'=>array('Post.created' => 'desc'));		

			 //get all the PostsTags with this $tagId
			 $postsTags = $this->Tag->PostsTag->findAll(array('tag_id'=>$tagId));
			 
			 //caching
			 $this->writeCache('postsTags', $postsTags);
		}
		 
		$postsIdArray = array();
		 
		//put all these PostsTags' ids into $postsIdArray
		foreach($postsTags as $postsTag)
		{
			$postsIdArray[] = $postsTag['PostsTag']['post_id'];
		}
		
		//finally, we could query
		$posts = $this->readCache('posts');
		$this->params['paging'] = $this->readCache('paging');		
		
		if(!$posts || !$this->params['paging'])
		{		 
			$posts = $this->paginate('Post', array('Post.id'=>$postsIdArray, 'Post.status'=>'published'));
		 	
		 	//caching
			$this->writeCache('posts', $posts);
			$this->writeCache('paging', $this->params['paging']);
		}
		
		/*$posts = $this->paginate('Post', array('Post.id'=>$postsIdArray, 'Post.status'=>'published'));*/
		
		//pass vars and render
		$this->set('tag', $tag);
		$this->set('posts', $posts);
	}
	
	/**
	 * an interface for querying from views
	 */
	 
	public function get()
	{
		if(($tags = $this->readCache('tags')) === false)
		{
			//we don't need so many fields from Post, so let's rebind the models	
			$this->Tag->bindModel(array('hasAndBelongsToMany'=>array(
	    		'Post'=>array(
	    			'conditions' => array('Post.status' => 'published'),
		    		'order' => 'Post.created DESC',
		    		'fields' => 'Post.id'
	    		)
			)));
	
			$tags = $this->Tag->findAll();
			
			$this->writeCache('tags', $tags);
		}

		return $tags;
	}			
}

?>