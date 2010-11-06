<?php
/* SVN FILE:  $Id: comments_controller.php 42 2009-09-24 12:53:07Z  $ */
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
class CommentsController extends AppController
{
    var $name = 'Comments';
    var $components = array('Email', 'Cookie', 'SpamShield', 'Blacklist');
    var $helpers = array('Gravatar');
	var $othAuthRestrictions = array('index', 'edit', 'delete', 'index', 'published', 'trash', 'spam', 'move');
	var $paginate;
	var $scaffold;

    /**
     * generate subscription hash
     *
     * @date 2009-03-06
     */
    private function getSubscriptionHash()
    {
    	return md5($this->data['Comment']['ip'].$this->data['Comment']['agent'].time());
    }
     
    /**
     * subscribe to comments of a post
     *
     * @date 2008-12-25
     */
    private function subscribe()
    {
		if(isset($this->data['Comment']['subscription']) && !empty($this->data['Comment']['subscription']))
		{
			//subscribe to the comments only when he/she hasn't subscribed before
			if(!$this->Comment->hasAny(array('post_id'=>$this->data['Comment']['post_id'], 'email'=>$this->data['Comment']['email'])))
			{
				//subscribe
				$this->data['Comment']['subscription'] = $this->getSubscriptionHash();				
			}
			else
			{
				//skip
				$this->data['Comment']['subscription'] = '';
			}
		}
    }
    	
	/**
	 * unsubscribe by a given subscription hash
	 *
	 * @string $hash
	 * @return boolean
	 */
	public function unsubscribe($hash = null)
	{
		if(!$hash) die('Hacking attempt!');
		
		//does the record exists?
		$comment = $this->Comment->find(array('subscription'=>$hash));
		
		$id = $comment['Comment']['id'];
		if(!$id) die('Hacking attempt!');
		
		$this->Comment->id = $id;
		$data['Comment']['subscription'] = '';
		$result = $this->Comment->save($data);
	}
    
    /**
     * 获取所有类型为published的评论，如果指定postId的话，则只取这一postId所对应的
     * @return {Array} comments
     */
    public function getCommentsByPostId($postId = null)
    {
		if(($comments = $this->readCache('comments')) === false)
		{
			$comments = $this->getComments(array('Post.id'=>$postId));
			$this->writeCache('comments', $comments);
		}    
    	return $comments;
    }
    
    public function getLatestComments($limit = 10)
    {
		if(($comments = $this->readCache('comments')) === false)
		{
			$comments = $this->Comment->findAll(array('Comment.status'=>'published'), 'Comment.*, Post.id, Post.title, Post.slug', 'Comment.created DESC', $limit);
			
			$this->writeCache('comments', $comments);
		}
		
    	return $comments;
    }    
    
    /*
     * 找出整个网站的最新评论
     * @params {String|Int} limit 要返回的评论数量
     * @return {Array} comments
     */
    
    private function getComments($conditions, $limit = null)
    {
    	$conditions['Comment.status'] = 'published';
        $comments = $this->Comment->findAll($conditions, 'Comment.*, Post.id, Post.title, Post.slug', 'Comment.created ASC', $limit);

        return $comments;
    }
    


	/**
	 * get all subscribers by a given post_id,
	 *	 
	 * @param $postId Int|String 
	 * @param $subscriptionHashToSkip String optional
	 * @return array subscriptions array including name, email and subscription
	*/
	private function getSubscribers($postId, $subscriptionHashToSkip)
	{
		if(!$postId) return false;
		
		$conditions = array(
			'Comment.status' => 'published',
			'Comment.post_id' => $postId,
			'Comment.subscription' => '<> '
		);
		
		if($subscriptionHashToSkip) array_push($conditions, array('Comment.subscription'=> '<> '.$subscriptionHashToSkip));
		
		$subscribers = $this->Comment->findAll($conditions, 'Comment.name, Comment.email, Comment.subscription');

		return $subscribers;
	}
	   
    /**
     * send mails to subscribers
     *
     * @date 2008-12-25
     */
    private function notifySubscribers($options)
    {
    	//postId, commentName and commentBody must be in $options
    	if(!array_key_exists('postId', $options) || !array_key_exists('commentName', $options) || !array_key_exists('commentBody', $options)) return false;
    	
    	//get subscribers
    	$subscribers = $this->getSubscribers($options['postId'], $options['subscriptionHashToSkip']);
    	
    	//add the blog author to the subscribers
 	    $userModel = ClassRegistry::init('Users');
 	    $administrator = $userModel->read('name, email', 1);
    	array_push($subscribers, array('Comment'=>array('name'=>$administrator['Users']['name'], 'email'=>$administrator['Users']['email'], 'subscription'=>'')));
   	
		if(is_array($subscribers) && count($subscribers))
		{
			//mail format
		   	$this->Email->sendAs = 'html';
		   	//element template
    		$this->Email->template = 'new_comment_notification';
    		//from address
	    	$this->Email->from = Configure::read('LT.siteName').' <'.Configure::read('LT.mailer').'>';
	    	//the article title and slug?
	    	$this->Comment->Post->recursive = 0;
	    	$post = $this->Comment->Post->read('Post.title, Post.slug', $options['postId']);
	    	
	    	//for each subscriber, send a mail
			foreach($subscribers as $subscriber)
			{
				//skip the author
				if($subscriber['Comment']['subscription'] == $options['subscriptionHashToSkip']) continue;
				
				//address the message is going to
				$this->Email->to = $subscriber['Comment']['email'];
				//subject for the message
				$this->Email->subject = sprintf(__('Hi %s, the article you subscribed has a new comment!', true), $subscriber['Comment']['name']);
				
				//who leaves the message?
				$this->set('commentName', $options['commentName']);
				
				//to whom?
				$this->set('subscriberName', $subscriber['Comment']['name']);
				
				//what have he/she said?
				$this->set('commentBody', $options['commentBody']);
				
				//what is the article title?
				$this->set('postTitle', $post['Post']['title']);
				
				//what is the article url?
				$this->set('postUrl', Router::url('/'.$post['Post']['slug'], true));
				
				//what is the url to unsubscribe?
				$this->set('unsubscribeUrl', Router::url('/comments/unsubscribe/' . $subscriber['Comment']['subscription'], true));			
	    		
		    	//send email
		    	//$this->Email->_debug = true;
    			$this->Email->send();
    		}
		}    
    }
    
    /**
     * save comment poster's information into cookies
     *
     * @date 2008-12-28
     */
     
	private function saveCookie($options)
    {
    	//the time when the cookie will expire, 1 year for default
    	$this->Cookie->time = 3600*24*365;
    	
    	//security key
    	$securityKey = Configure::read('Security.salt');
    	$this->Cookie->key = $securityKey;

    	//save
    	foreach($options as $key=>$value)
    	{
    		//make sure we don't fuck it up
    		Configure::write('debug', 0);
    		if(isset($key) && !empty($key)) $this->Cookie->write($key, $value);
    	}
    }
    
    /**
     * Comment add security check
     *
     * check the following things:
     * 1) is it a POST request?
     * 2) is the request from localhost?
     * 3) is the data empty?
     * 4) sanitize data
     * 
     * @date 2008-12-30     
     */
    private function securityCheck()
    {
    	/*only a POST request and requesting from /posts/view/* is allowed
		if(!$this->RequestHandler->isPost() || !isPostFromLocalHost() || empty($this->data['Comment']))
		{
			$this->cakeError('error404');
			exit();
		}

		//sanitization
		App::import('Sanitize');
		$this->data = Sanitize::clean($this->data);
		*/
		if(empty($this->data['Comment']))
		{
			//$this->cakeError('error404');
			//exit();
			return false;
		}
	}
    
    /**
     * Prepare data to save
     * 
     * @date 2009-1-20     
     */
    private function prepareData()
    {
		//ip and user agent
		$this->data['Comment']['ip'] = $this->RequestHandler->getClientIp();
		$this->data['Comment']['agent'] = $_SERVER['HTTP_USER_AGENT'];
		
		//ugly hack for debugging on a Mac
		if(ereg('::1', $this->data['Comment']['ip'])) $this->data['Comment']['ip'] = '127.0.0.1';
		
		//return
		return $this->data;   
    }
    
    /**
     * Get all comments after the time point
     * 
     * @date 2009-1-21
     */
    private function getCommentsAfterTimePoint($postId)
    {
		//get the time point
		$timePoint = $this->getTimePoint($postId);
		
		//debug
		//$this->log('timePoint:'.$timePoint);
		
		//get all comments after that time point
		$options = array(
			'Post.id'=>$this->data['Comment']['post_id'],
			'Comment.created > '=>date('Y-m-d H:i:s', $timePoint)
		);
		return $latestComments = $this->getComments($options);
    }
    
    /**
     * Render errors to view
     * 
     * @date 2009-1-21
     
    private function renderView($template = null)
    {
    	if(!isset($template)) $template = $this->params['action'];
		$invalidFields = array();

		foreach($this->invalidFields as $rule=>$message)
		{
			$rule = 'Comment' . ucfirst($rule);
			$invalidFields[$rule] = $message;
		}
		
		$this->set('invalidFields', $invalidFields);    
		if($this->params['isAjax']) $this->render('add/invalid－json','ajax');					
		else $this->render('add/invalid');  
    }  */  

    /**
     * add a comment
     *
     * @date 2008-12-30
     */

	public function add()
	{	
		//performance
		$this->Comment->Post->recursive = -1;
		
		//security check
		if(empty($this->data['Comment']) || !$post = $this->Comment->Post->read('Post.comment', $this->data['Comment']['post_id']))
		{
			$this->renderView('hacking-attempt');
			return;
		}

		//allow comment?
		if($post['Post']['comment'] == 'off')
		{
			$this->renderView('hacking-attempt');
			return;
		}

		//prepare data, and set data for validatation
		$this->Comment->set($this->prepareData());

		//validate the comment with Vlidation Level 1 - pre-defined rules	
		if(!$this->Comment->validates())
		{
			//it's IL1, just render the errors
			$this->set('invalidFields', $this->Comment->invalidFields());
			$this->renderView('invalid');
			return;
		}
	
		//subscribe to comments
		$this->subscribe();

		//set data for Blacklist validation
		$this->Blacklist->set($this->prepareData());
		
		//set data for Bayesian validation
		$this->SpamShield->set($this->data['Comment']['body']);

		//validate the comment with Vlidation Level 2 - blacklist,	and Vlidation Level 3 - Bayesian
		//@todo integrate blacklist and Bayesian into one ultimate M-O!
		if(!$this->Blacklist->validate() || !$this->SpamShield->validate())
		{
			//set the status
			$this->data['Comment']['status'] = 'spam';
			
			//save
			$this->Comment->save($this->data);

			//learn it
			$this->SpamShield->learn("spam");

			//render
			$this->renderView('unmoderated');
			
			//debug
			//$this->log($this->SpamShield->rating,LOG_DEBUG);
			
			return;
		}
		
		//debug
		//$this->log($this->SpamShield->rating,LOG_DEBUG);		
	
		//it's a normal post
		$this->data['Comment']['status'] = 'published';
		
		//save for publish
		$this->Comment->save($this->data);

		//learn it
		$this->SpamShield->learn("ham");
		
		//do the rest of posting...
		//last insert id
		$thisCommentId = $this->Comment->getLastInsertId();
		
		//send mails to subscribers
		//we need to pass this subscription hash to notifySubscribers() because it doesn't make any sense to notify the author him/her self
		$options = array(
			'postId'=>$this->data['Comment']['post_id'],
			'commentName'=>$this->data['Comment']['name'],
			'commentBody'=>$this->data['Comment']['body'],
			'subscriptionHashToSkip'=>$this->data['Comment']['subscription']
		);
		
		$this->notifySubscribers($options);
		unset($options);
		
		//save user's info into cookies for 1 year
		$options = array(
			'commentName'=>$this->data['Comment']['name'],
			'commentEmail'=>$this->data['Comment']['email'],
			'commentWebsite'=>$this->data['Comment']['website']
		);
		$this->saveCookie($options);
		unset($options);
		
		//if it's an Ajax call, we need to get all comments from the time point when the user requested the page to now, in case that some other people had posted comments during this period
		if($this->params['isAjax'])
		{			
			//get all comments after that time point
			$latestComments = $this->getCommentsAfterTimePoint($this->data['Comment']['post_id']);
			
			//debug
			//$this->log('count latestComments:'.count($latestComments));			
			
			//pass these comments into the view
			$this->set('latestComments', $latestComments);
			$this->set('thisCommentId', $thisCommentId);
			unset($latestComments);
						
			//set a new time point
			$this->setTimePoint($this->data['Comment']['post_id']);		
			
			//render
			$this->render('add/json/saved','ajax');			
		}
		else
		{
			//a non-ajax call, just redirect
			//$this->set('thisCommentUrl',  Router::url($this->referer().'#comment-'.$thisCommentId, true));
			//$this->render('add/html/saved');
			$this->redirect(Router::url($this->referer().'#comment-'.$thisCommentId, true));
		}					
	}

    /**
     * Comments moderation
     * 
     * @date 2009-02-06
     */
    public function index()
    {
    	//$this->redirect('/comments/published');
    	$this->status('published');
    }
    
    /**
     * View comment detail
     * 
     * @param $id Int
     * @date 2009-02-06
     */
    public function view($id = null)
    {
    	$this->Comment->id = $id;
    	
    	if(!$comment = $this->Comment->read())
    	{
    		if($this->params['isAjax']) $this->render('404','ajax');
			else $this->cakeError('error404');  
    	}
    	else
    	{
    		$this->set('comment', $comment);
			$this->renderView('view');
		}
    }
   	
    /**
     * EVA - SpamShield Training Center
     * 
     * list all un-learned comments, mark them as SPAM or HAM, and train SpamShield 
     * @date 2009-1-23
    
    public function eva()
    {   	
    	if(!empty($this->data['Comment']))
    	{
			//security check
			//$this->securityCheck();
			
			//get all the comments' id
			$commentIds = array_keys($this->data['Comment']['status']);
			
			//get all the comments' content
			$this->Comment->recursive = -1;
			$commentsToLearn = $this->Comment->findAll(array('Comment.id'=>$commentIds));		
			
			//for each comment, train SpamShield and update comment's status
			foreach($commentsToLearn as $commentToLearn)
			{
				//is the comment a spam or ham? 'spam' = spam, 'published' = 'ham', 'unmoderated' is ignored
				switch($this->data['Comment']['status'][$commentToLearn['Comment']['id']])
				{
					case 'published':
						$bayesStatus = 'ham';
					break;
					
					case 'spam':
						$bayesStatus = 'spam';
					break;
					
					case 'trash':
						$bayesStatus = false;
					break;							
				}
				
				//only update those whose status have been changed
				if($commentToLearn['Comment']['status'] != $this->data['Comment']['status'][$commentToLearn['Comment']['id']])
				{
					//update the status
					$commentToLearn['Comment']['status'] = $this->data['Comment']['status'][$commentToLearn['Comment']['id']];
				}
				
				//only learn ham or spam
				if($bayesStatus)
				{
					$this->SpamShield->set($commentToLearn['Comment']['body']);
					$this->SpamShield->learn($bayesStatus);
					
					//update the learned/unlearned marker
					$commentToLearn['Comment']['learned'] = 'true';
				}
				
				//OK, now just update the database
				$this->Comment->id = $commentToLearn['Comment']['id'];
				//pr($commentToLearn);
				$this->Comment->save($commentToLearn);
			}
		}
		
		//
		$this->Comment->recursive = 0;
		
    	//pagination rocks!
    	$this->paginate = array('limit' => 10, 'page' => 1, 'order'=>array('Comment.created' => 'DESC'), 'fields'=>'Comment.*, Post.id, Post.title, Post.slug');    	
    	
    	//list all comments with given status
    	$comments = $this->paginate('Comment', array('Comment.learned'=>'false'));
    	
    	//render
    	$this->set('unlearnedComments', $comments);
    	$this->render('eva/index');
    } 
     */
     
    /**
     * Get comments with different status
     *
     * @date 2009-02-05
     */
    private function status($status = 'published')
    {
    	//performance
		$this->Comment->recursive = 0;
		
    	//pagination rocks!
    	$this->paginate = array('limit' => 50, 'page' => 1, 'order'=>array('Comment.created' => 'DESC'), 'fields'=>'Comment.*, Post.id, Post.title, Post.slug');    	
    	
    	//list all comments with given status
    	$comments = $this->paginate('Comment', array('Comment.status'=>$status));

    	//performance
		$this->Comment->recursive = -1;
		    	
    	//how many comments in other status?
    	$allStatus = array('published', 'spam', 'trash');
     	$allStatusCount = count($allStatus);
    	for($i=0;$i<$allStatusCount;$i++)
    	{
    		//just to reduce 1 sql query
    		if($allStatus[$i] == $status)
    			$commentsCount = $this->params['paging']['Comment']['count'];
    		else
    			$commentsCount = $this->Comment->find('count', array('conditions'=>array('Comment.status'=>$allStatus[$i])));
	    	
	    	$this->set('commentsCount'.ucfirst($allStatus[$i]), $commentsCount);
    	}
    	
    	//return
    	//return $comments;
    	
    	//render
    	$this->set('status', $status);
    	$this->set('comments', $comments);
    	$this->render('index/index');
    }
    
    /**
     * Move a comment
     *
     * @date 2009-02-06
     */
    public function move($id = null, $to = null)
    {
	    if(!$id || (!$comment = $this->Comment->findById($id)) || ($to != 'published' && $to != 'spam' && $to != 'trash'))
    	{
			if($this->params['isAjax']) $this->render('404','ajax');
			else $this->cakeError('error404');
	    	return;
	    }

	    //get the current status
	    $currentStatus = $comment['Comment']['status'];
	    
	    //mark it as $to
	    $comment['Comment']['status'] = $to;

		//OK, now just update the database
		$this->Comment->id = $id;
		$this->Comment->save($comment);
		
    	$event = array();		
		
    	//ask M-O to learn it
    	if($currentStatus == 'spam' && $to == 'published')
    	{
   			$this->SpamShield->set($comment['Comment']['body']);
			$this->SpamShield->unlearn('spam');
			$this->SpamShield->learn('ham');
			
			$event['name'] = 'COMMENT_MOVE_FROM_SPAM_TO_PUBLISHED';
    	}
    	elseif($currentStatus == 'published' && $to == 'spam')
    	{
    		$this->SpamShield->set($comment['Comment']['body']);
			$this->SpamShield->unlearn('ham');
			$this->SpamShield->learn('spam');
			
			$event['name'] = 'COMMENT_MOVE_FROM_PUBLISHED_TO_SPAM';
    	}
    	else
    	{
    		if($currentStatus == 'published' && $to == 'trash') $event['name'] = 'COMMENT_MOVE_FROM_PUBLISHED_TO_TRASH';
    		elseif($currentStatus == 'spam' && $to == 'trash') $event['name'] = 'COMMENT_MOVE_FROM_SPAM_TO_TRASH';
    	}
    	
    	//ignore if the user move the comment to trash
    	if(isset($event['name']))
    	{
	    	//record the event
	    	App::import('Model', 'Event');
	    	$this->Event = new Event();
	   	
	    	$event['value'] = $this->Comment->id;
	    	$this->Event->add($event);
	    }
    	
		//render
		$this->set('from',  $currentStatus);
		$this->set('to',  $to);
		$this->renderView('moved');	
    }
    
    /**
     * Get published comments
     *
     * @date 2009-02-05
     */
    public function published()
    {
    	$this->status('published');
    }     

    /**
     * Empty a certain folder
     *
     * @param $empty String
     * @param $confirm String
     * @date 2009-02-07
     */
    private function emptyFolder($empty = null, $confirm = null)
    {
   		$this->set('status',  $this->params['action']);
    		
    	if(isset($confirm) && $confirm == 'confirm')
   		{
    		//delete every comment with a $this->params['action'] status
   			$this->Comment->deleteAll(array('Comment.status'=>$this->params['action']));
    			    			
   			$this->render('index/html/confirm');
   		}
   		else
   		{
   			//ask the user to confirm    			
   			$this->render('index/html/empty');
   		}
    }
    
    /**
     * Get trashed comments
     *
     * @date 2009-02-05
     */
    public function trash($empty = null, $confirm = null)
    {
    	if(isset($empty) && $empty == 'empty')
    	{
    		$this->emptyFolder($empty, $confirm);
    	}
    	else
    	{
    		$this->status('trash');
    	}
    }       

    /**
     * Get and set spam comments
     *
     * @date 2009-02-05
     */
    public function spam($empty = null, $confirm = null)
    {
    	if(isset($empty) && $empty == 'empty')
    	{
    		$this->emptyFolder($empty, $confirm);
    	}
    	else
    	{
    		$this->status('spam');
    	}
    } 
}
?>