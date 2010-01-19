<?php
/* SVN FILE:  $Id: related_posts_controller.php 41 2009-08-04 13:12:50Z  $ */
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
class RelatedPostsController extends AppController {

	var $name = 'RelatedPosts';
	var $scaffold;
	var $othAuthRestrictions = null;
	var $cacheAction = null;
    /**
     * The ratio between title and body when calculating similarity
     */
    //var $ratio = array('title'=>0.3, 'body'=>0.7);
    var $ratio = 0.3;

    /**
     * Get similar posts for a post
     *
     * @param $postId Int
     * @return Array containing Post.id, Post.title, Post.slug and the similarities
     * @date 2009-1-29
     */
    public function index($postId = null)
    {
    	//debug
    	//Configure::write('debug', 2);
    	
    	//we can't do with only 1 post or even less

		if($this->RelatedPost->Post->find('count') < 2)
		{
			if(isset($this->params['requested'])) return false;
			
			$this->render('too_few_posts');
			return;
		}
    	
    	if($postId)
    	{    		
    		//get related posts
    		if($relatedPosts = $this->build($postId))
    		{
    			//insert or update the db
	    		$this->update($postId, $relatedPosts);
	    		
	    		//if called my Post model, just return
	    		//if(isset($this->params['requested'])) return true;
	    		
	    		//turn to next post
	    		$nextPost = $this->RelatedPost->Post->find(array('Post.status'=>'published', 'Post.id >	'=>$postId), 'Post.id', 'ORDER BY Post.id ASC');
	    		
	    		//render
	    		if(!empty($nextPost))
	    		{
	    			$this->set('nextPostId', $nextPost['Post']['id']);
	    		}
	    		else
	    		{
	    			$this->set('done', 1);
	    			$this->render();
	    		}
	    	}
	    	else
	    	{
	    		//
	    		die("Can't get related posts with Post.id = ".$postId);
	    	}
    	}
    	else
    	{
    		$theFirstPost = $this->RelatedPost->Post->find(array('Post.status'=>'published'), 'Post.id', 'ORDER BY Post.id ASC');
    		
    		//render
	   		$this->set('theFirstPostId', $theFirstPost['Post']['id']);    		
    	}
    }
    
    
    /**
     * Interface for the view, to get similar posts for a post
     *
     *
     * @param $postId Int
     * @return Array containing Post.id, Post.title, Post.slug and the similarities
     * @date 2009-1-29
     */
    public function get($postId = null)
    {
    	if(isset($this->params['requested']))
    	{
			if(($relatedPostsCache = $this->readCache('related_posts')) === false)
			{
	        	//for better performance
	    		$this->RelatedPost->recursive = -1;
	    		$relatedPosts = $this->RelatedPost->findAll(array('RelatedPost.post_id'=>$postId), null, 'RelatedPost.similarity DESC', 10);
	    		
	    		if(!$relatedPosts) return null;
	    		
	    		$relatedPostIds = array();
	    		foreach($relatedPosts as $relatedPost)
	    			$relatedPostIds[] = $relatedPost['RelatedPost']['related_post_id'];
	
				//for better performance
	    		$this->RelatedPost->Post->recursive = -1;
	    		$relatedPostsCache = $this->RelatedPost->Post->findAll(array('Post.status'=>'published', 'Post.id'=>$relatedPostIds), null, 'FIELD(Post.id, '.implode(",", $relatedPostIds).')');  
				
				$this->writeCache('related_posts', $relatedPostsCache);	
			}
			
			return $relatedPostsCache;
    	}
    	else
    	{
    		$this->cakeError('error404');
    	}
    }

    /**
     * get posts
     *
     * @return Array containing Post.id, Post.title, Post.slug
     * @date 2009-04-16
     */
    private function getPosts($basePostTagIds)
    {
        //get posts'ids which have the same tags with the base post
       	$relatedPostsIds = $this->RelatedPost->PostsTag->find('all', array(
       		'conditions'=>array('PostsTag.tag_id'=>$basePostTagIds),
       		'recursive'=>-1,
       		'fields'=>array('PostsTag.post_id')
       		)
       	);
       	$relatedPostsIds = Set::extract('/PostsTag/post_id', $relatedPostsIds);

	  	//get these posts
        return $this->RelatedPost->Post->find('all', array(
        	'conditions'=>array('Post.status'=>'published', 'Post.id'=>$relatedPostsIds),
        	'fields'=>'Post.id, Post.title, Post.slug, Post.body',
        	'recursive'=>-1
        	)
        );
    }
    
    /**
     * Build related posts for a post
     *
     *
     * @param $postId Int
     * @return Array containing Post.id, Post.title, Post.slug and the similarities
     * @date 2009-1-29
     */
    private function build($postId = null)
    {
    	//for better performance
    	$this->RelatedPost->Post->recursive = -1;
    	    
    	//get the specific post
    	if(!$basePost = $this->RelatedPost->Post->read('Post.id, Post.title, Post.body', $postId)) return false;
    	
    	$data = array();
    	$similarities = array();
    	
    	//get its tag ids
    	$basePostTagIds = $this->RelatedPost->PostsTag->findByPostId($basePost['Post']['id']);
    	$basePostTagIds = Set::extract('/PostsTag/tag_id', $basePostTagIds);
    	
    	//the base post maybe doesn't have any tags
    	if(!$basePostTagIds) return true;
    	
		//get each other posts' content
		$posts = $this->getPosts($basePostTagIds);
		
		//for each post, compare its body text with the speicific post, get similarity between the two
		foreach($posts as $post)
		{
			//skip the base post itself
			if($post['Post']['id'] == $basePost['Post']['id']) continue;
			
			//get similarity
			App::import('Sanitize');
			similar_text($basePost['Post']['title'], $post['Post']['title'], $title_similarity);
			similar_text(Sanitize::html($basePost['Post']['body'], true), Sanitize::html($post['Post']['body'], true), $body_similarity);
			//$similarity = $this->ratio['title'] * $title_similarity + this->ratio['body'] * $body_similarity;
			$similarity = $this->ratio * $title_similarity + (1 - $this->ratio) * $body_similarity;
			
			//build data array
			$data[] = array(
				'id' => $post['Post']['id'],
				'title' => $post['Post']['title'],
				'slug' => $post['Post']['slug'],
				'similarity' => $similarity		
			);			
		}
		
		//sort the array in DESC order
		//array_multisort($similarities, SORT_DESC, $data);
		
		//return similarity
		return $data;
    }
    
    /**
     * Empty the table
     *
     * @return Boolean
     * @date 2009-1-29
     */
    private function clean()
    {    
    	$this->RelatedPost->deleteAll('RelatedPost.id IS NOT NULL');
    }
        
    /**
     * Update similar posts for a post
     *
     * @param $postId Int
     * @param $relatedPosts Array
     * @return Boolean
     * @date 2009-1-29
     */
    private function update($postId, $relatedPosts)
    {    	
    	foreach($relatedPosts as $relatedPost)
    	{
    		$data = array();
    		$data['RelatedPost']['post_id'] = $postId;
    		$data['RelatedPost']['related_post_id'] = $relatedPost['id'];    		
    		$data['RelatedPost']['similarity'] = $relatedPost['similarity'];
    		
    		//delete the record
    		$this->RelatedPost->deleteAll(array('RelatedPost.post_id'=>$postId, 'RelatedPost.related_post_id'=>$relatedPost['id']));
    		
    		//save
    		$this->RelatedPost->create();
    		$this->RelatedPost->save($data);
    		
    		//update
    		//$this->RelatedPost->updateAll(array('RelatedPost.similarity'=>$relatedPost['similarity']), array('RelatedPost.post_id'=>$postId, 'RelatedPost.related_post_id'=>$relatedPost['id']));
    		
    		unset($data);
    	}
    }    	
}
?>