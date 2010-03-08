<?php
/* SVN FILE:  $Id: posts_controller.php 24 2009-05-08 13:23:53Z  $ */
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
 * @version       $Revision: 24 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-05-08 21:23:53 +0800 (Fri, 08 May 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class PostsController extends AppController
{
	var $name = 'Posts';
	var $scaffold;
	var $components = array('Cookie', 'Session');
	var $helpers = array('Gravatar', 'Rss', 'Session', 'Geshi');
	var $othAuthRestrictions = array('add','edit','delete');
	var $paginate = array('limit' => 5, 'page' => 1, 'order'=>array('Post.created' => 'desc'));

	
	/**
	 * blog homepage
	 */
	 
	public function index()
	{	
		//get latest posts
		$posts = $this->readCache('posts');
		$this->params['paging'] = $this->readCache('paging');
		
		if(!$posts || !$this->params['paging'])
		{
			$posts = $this->paginate('Post', array('Post.status'=>'published'));
			
			//caching
			$this->writeCache('posts', $posts);
			$this->writeCache('paging', $this->params['paging']);
		}
			
		$this->set('posts', $posts);
	}
	
	/**
	 * rss feed
	 */
	 
	public function feed()
	{
		//$channel = array ('title' => Configure::read('LT.siteName'), 'link' => Configure::read('LT.siteUrl'), 'description' => Configure::read('LT.siteSlogan'));
		//$this->set('channel', $channel);
		$posts = $this->Post->findAll(array('Post.status'=>'published'), '*', 'ORDER BY Post.created DESC', 30);
		
		$this->set('posts', $posts);
		$this->render('rss/feed');
	}
	
	/**
	 * add a post
	 */
	public function add()
	{
		if(!empty($this->data))
		{	   		
			if($this->Post->save($this->data))
			{
				//if($this->data['Post']['status'] == 'published') $this->redirect('/posts/view/'.Inflector::slug($this->data['Post']['slug'], '-'));
				//else $this->set('saved', true);
				$this->redirect('/posts/view/'.$this->data['Post']['slug']);
			}
			else
			{
				$this->set('post', $this->data);
			}
			
			if($this->RequestHandler->isAjax()) $this->renderView('saved');
		}
		
		//$this->set('drafts', $this->getDrafts());
		//$this->set('statuses', $this->getEnumFields('status'));
		$this->set('comment', $this->getEnumFields('comment'));
		
		//extract the tags
		$tags = Set::extract('{n}.Tag', $this->requestAction('/tags/get'));
		$tagsForTheView = array();
		foreach($tags as $tag) $tagsForTheView[$tag['id']] = $tag['title'];
		$this->set('tags', $tagsForTheView);
	}
	
	/**
	 * edit a post
	 */
	public function edit($id = null)
	{
		//does the post exist?
		if(!empty($this->data)) $this->Post->id = $this->data['Post']['id'];
		else $this->Post->id = $id;
		
		if(!$post = $this->Post->read()) {
			$this->cakeError('error404');
			return;
		}
			
		if(!empty($this->data))
		{	   		
			$post = $this->data;
			$post['Tag'] = $post['Tag']['Tag'];
			
			/**/
			if($this->Post->save($this->data))
			{
				$this->redirect('/posts/view/'.Inflector::slug($this->data['Post']['slug'], '-'));
				return;
			}
		}
		
		//replace '-' with a white space
	   	//$post['Post']['slug'] = isset($post['Post']['slug']) ? str_replace('-', ' ', $post['Post']['slug']) : '';
	   			
		$this->set('post', $post);
		$this->set('comment', $this->getEnumFields('comment'));
		
		//extract the tags, tried with Set library but failed
		$tags = Set::extract('{n}.Tag', $this->requestAction('/tags/get'));
		$tagsForTheView = array();
		foreach($tags as $tag) $tagsForTheView[$tag['id']] = $tag['title'];		
		$this->set('tags', $tagsForTheView);
		
		$this->render('add');
	}	
	
	/**
	 * render the view page
	 */
	public function view($slug = null)
	{		
		//set post
		if(($post = $this->readCache('post')) === false)
		{
			if(!$post = $this->Post->findBySlug($slug))
			{
				$this->cakeError('error404');
				return false;
			}
			
			$this->writeCache('post', $post);
		}
		
		$this->set('post' ,$post);
		
		//get comments data
		$comments = $this->requestAction('/comments/getCommentsByPostId/' . $post['Post']['id']);
		
		$this->set('comments', $comments);
		
		//set a time point 
		$this->setTimePoint($post['Post']['id']);
	}
	
	/**
	 * get cookies about pass them to the view for CommentAddForm
	 */
	
	public function getCookies()
	{	
		return array($this->Cookie->read('commentName'), $this->Cookie->read('commentEmail'), $this->Cookie->read('commentWebsite'));
	}
	
	/**
	 * render the view page
	 */
	/*
	private function getDrafts()
	{	
		$this->Post->recursive = -1;
		$drafts = $this->Post->findAll(array('Post.status'=>'draft'), '*', 'ORDER BY Post.created DESC');
			
		return $drafts;
	}
	*/	
}

?>