<?php
/* SVN FILE:  $Id: blacklists_controller.php 39 2009-07-16 10:03:24Z  $ */
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
class BlacklistsController extends AppController
{
	var $name = 'Blacklists';
	var $othAuthRestrictions = '*';
	var $paginate = array('limit' => 15, 'page' => 1, 'order'=>array('Blacklist.created' => 'ASC'));
	var $uses = array('Blacklist', 'Comment', 'Event');
	var $helpers = array('FlashChart');
	
	/**
	 * display the blacklist
	 *
	 * @date 2009-02-22
	 */
	public function index()
	{
    	$this->set('blacklists', $this->paginate('Blacklist'));
	}
	
	/**
	 * add a pattern
	 *
	 * @date 2009-02-22
	 */
	
	public function add()
	{
		//get blacklist fields	    
        $this->set('fieldEnumValues', $this->getEnumFields('field'));
        $this->set('statusEnumValues', $this->getEnumFields('status'));
	    
	    //if submitted?
	    if(!empty($this->data['Blacklist']))
	    {
	    	//set data, validate and save
	    	//App::import('Sanitize');
	    	//$this->data = Sanitize::clean($this->data);
	    	
	    	$this->Blacklist->set($this->data);
	    	
	    	if($this->Blacklist->validates())
	    	{
	    		//any same rule exists?
	    		$existingBlacklist = $this->Blacklist->find(
	    			array(
	    				'Blacklist.field'=>$this->data['Blacklist']['field'],
		    			'Blacklist.pattern'=>$this->data['Blacklist']['pattern']
	    			)
	    		);
	    		
	    		if(!$existingBlacklist)
	    		{
	    			if($this->Blacklist->save($this->data))
	    			{
						$this->renderView('saved');
					}
					else
					{
						//@todo exception handling
					}
				}
				else
				{
					//a rule with same field and pattern exists, I think duplicated data is stupid, let's ask user for further instructions, which include
					//1, edit the existing rule
					//2, go back to list
					$this->set('existingBlacklist', $existingBlacklist);
					$this->renderView('existed');
				}
	    	}
	    	else
	    	{
	    		$this->set('invalidFields', $this->Blacklist->invalidFields());
				$this->renderView('invalid');
	    	}
	    	
	    	return;
	    }
	    
	    //render
		$this->render('add/add');
	}
	
	/**
	 * edit a pattern
	 *
	 * @date 2009-02-22
	 */
	
	public function edit()
	{
		//does the pattern exist?
		if(!empty($this->data['Blacklist'])) $this->Blacklist->id = $this->data['Blacklist']['id'];
		else $this->Blacklist->id = $this->params['id'];
		
		if(!$blacklist = $this->Blacklist->read()) $this->cakeError('error404');
		
		//get blacklist fields	    
        $this->set('fieldEnumValues', $this->getEnumFields('field'));
        $this->set('statusEnumValues', $this->getEnumFields('status'));		
		
		//pass the var
		$this->set('blacklist', $blacklist);
		
	    
	    //if submitted?
	    if(!empty($this->data['Blacklist']))
	    {
			//set data, validate and save
	    	//App::import('Sanitize');
	    	//$this->data = Sanitize::clean($this->data);
	    	
	    	$this->Blacklist->set($this->data);
	    	
	    	if($this->Blacklist->validates())
	    	{
	    		//any same rule exists?
	    		$existingBlacklist = $this->Blacklist->find(
	    			array(
	    				'Blacklist.field'=>$this->data['Blacklist']['field'],
		    			'Blacklist.pattern'=>$this->data['Blacklist']['pattern']
	    			)
	    		);
	    		
	    		if(!$existingBlacklist || $existingBlacklist['Blacklist']['id'] == $this->Blacklist->id)
	    		{	    		
	    			if($this->Blacklist->save($this->data))
	    			{
						$this->renderView('saved', 'add/');
					}
					else
					{
						//@todo exception handling
					}
				}
				else
				{
					//a rule with same field and pattern exists, I think duplicated data is stupid, let's ask user for further instructions, which include
					//1, edit the existing rule
					//2, go back to list
					$this->set('existingBlacklist', $existingBlacklist);
					$this->renderView('existed', 'add/');
				}
	    	}
	    	else
	    	{
	    		$this->set('invalidFields', $this->Blacklist->invalidFields());
				$this->renderView('invalid', 'add/');
	    	}
	    	
	    	return;
	    }
	    
	    //render
		$this->render('add/add');
	}		
   	
	/**
	 * display MO's brainpower
	 *
	 * @date 2009-02-22
	 */
	public function brainpower()
	{
		//brainpower index = 1 - mis-categoried/all
		//0 <= brainpower index <= 100
		//I wish I could have a better solution for this. Any ideas?
		$maximum = 1900;
		$existingCommentsCount = $this->getExistingCommentsCount();
		$deletedCommentsCount = $this->getDeletedCommentsCount();
		$all = $existingCommentsCount['all'] + $deletedCommentsCount;
		//a ugly method...
		$all = ($all > $maximum) ? $maximum : $all;
		$brainpower = intval($all / $maximum * 100);
		
		$this->set('brainpower', $brainpower);
		$this->render('mo/brainpower');
	}	
	
	/**
	 * statistics
	 *
	 * @date 2009-02-22
	 */
	public function statistics()
	{	
		//how many exisiting comments
		$this->set('existingCommentsCount', $this->getExistingCommentsCount());
		
		//how many comments received in last 7 days?
		$this->set('CommentsCountFromDaysAgo', $this->getCommentsCountFromDaysAgo(7));
		
		//how many comments have been deleted?
		//$this->set('deletedCommentsCount', $this->getDeletedCommentsCount());
		
		//get how many hams
		//$this->set('hamsCounts', $this->getHamsCounts());
		
		//get how many spam
		//$this->set('spamsCounts', $this->getSpamsCounts());
		$this->render('mo/statistics');			
	}

	/**
	 * get how many comments received with the given date
	 *
	 * @return Array comments' count in each day
	 * @date 2009-04-11
	 */
	private function getCommentsCountFromDaysAgo($days = 14)
	{
		$counts = array();
		
		while($days)
		{
			$counts[] = $this->getCommentsCountInDaysAgo($days);
			
			$days--;
		}
		
		return $counts;
	}
	
	/**
	 * get how many comments received with the given date
	 *
	 * @return Array comments' count
	 * @date 2009-04-11
	 */
	private function getCommentsCountInDaysAgo($days = 0)
	{
		$date = date("Y-m-d", time() - 86400 * $days);
		$comments = $this->Comment->find('all', array(
							'conditions'=>array("DATE_FORMAT(Comment.created, '%Y-%m-%d')"=>$date),
							'fields'=>'Comment.status, COUNT(*) AS count',
							'group'=>'Comment.status')
							);
		foreach($comments as $comment)
		{
			${$comment['Comment']['status']} = $comment['0']['count'];
		}
		
		$published = isset($published) ? $published : 0;
		$spam = isset($spam) ? $spam : 0;
		$trash = isset($trash) ? $trash : 0;				

		$all = $published + $spam + $trash;
		return array(
				'date' => $date,
				'all' =>$all,
				'published' => $published,
				'spam' => $spam,
				'trash' => $trash
				);
	}
	
	/**
	 * get how many comments have been deleted
	 *
	 * @return Int deleted comments' count
	 * @date 2009-04-11
	 */
	private function getDeletedCommentsCount()
	{
		return $this->Event->findCount(array('Event.name'=>'COMMENT_DELETE_FROM_PUBLISHED', 'Event.name'=>'COMMENT_DELETE_FROM_SPAM', 'Event.name'=>'COMMENT_DELETE_FROM_TRASH'));
	}
	
	/**
	 * get how many existing comments in each status
	 *
	 * @return Array existing comments' count
	 * @date 2009-04-10
	 */
	private function getExistingCommentsCount()
	{
		$comments = $this->Comment->find('all', array(
							'fields'=>'Comment.status, COUNT(*) AS count',
							'group'=>'Comment.status')
							);
		foreach($comments as $comment)
		{
			${$comment['Comment']['status']} = $comment['0']['count'];
		}
		
		$published = isset($published) ? $published : 0;
		$spam = isset($spam) ? $spam : 0;
		$trash = isset($trash) ? $trash : 0;				

		$all = $published + $spam + $trash;
		return array(
				'all' =>$all,
				'published' => $published,
				'spam' => $spam,
				'trash' => $trash
				);
	}
	
	/**
	 * get how many existing comments with given status
	 *
	 * @return Int existing comments' count
	 * @date 2009-04-10
	 */
	private function getExistingCommentsCountByStatus($status = null)
	{
		if($status) return $this->Comment->findCount(array('Comment.status'=>$status));
		else return $this->Comment->findCount();
	}
				
	/**
	 * get how many published comments
	 *
	 * published comments = existing published comments + trashed hams + deleted hams
	 * @return Int published comments' count
	 * @date 2009-04-10
	 */
	private function getPublishedCommentsCount()
	{
		//how many existing hams?
		$existingHamsCount = $this->getExistingCommentsCountByStatus('published');
		
		//how many of ham have been moved to trash
		$hamsMovedToTrashCount = $this->Event->findCount(array('Event.name'=>'COMMENT_MOVE_FROM_PUBLISHED_TO_TRASH'));
		
		//how many of ham have been deleted?
		$hamsDeletedCount = $this->Event->findCount(array('Event.name'=>'COMMENT_DELETE_FROM_PUBLISHED'));
		
		//ok, now we know how many hams in all we have got
		$hamsCount = $existingHamsCount + $hamsMovedToTrashCount + $hamsDeletedCount;
		
		//return
		return $hamsCount;
	}	
	
	/**
	 * get how many hams
	 *
	 * ham = existing hams + trashed hams + deleted hams
	 * @return Array hams' counts
	 * @date 2009-04-10
	 */
	private function getHamsCounts()
	{
		//how many existing hams?
		$existingHamsCount = $this->getExistingCommentsCountByStatus('published');
		
		//how many of ham have been moved to trash
		$hamsMovedToTrashCount = $this->Event->findCount(array('Event.name'=>'COMMENT_MOVE_FROM_PUBLISHED_TO_TRASH'));
		
		//how many of ham have been deleted?
		$hamsDeletedCount = $this->Event->findCount(array('Event.name'=>'COMMENT_DELETE_FROM_PUBLISHED'));
		
		//ok, now we know how many hams in all we have got
		$hamsCount = $existingHamsCount + $hamsMovedToTrashCount + $hamsDeletedCount;
		
		//return
		//return $hamsCount;
		return array('existingHamsCount'=>$existingHamsCount, 'hamsMovedToTrashCount'=>$hamsMovedToTrashCount, 'hamsDeletedCount'=>$hamsDeletedCount, 'hamsCount'=>$hamsCount);
	}
	
	/**
	 * get how many spams
	 *
	 * spam = existing spam + trashed spam + deleted spam
	 * @return Array spams' counts
	 * @date 2009-04-10
	 */
	private function getSpamsCounts()
	{
		//how many existing spams
		$existingSpamsCount = $this->getExistingCommentsCountByStatus('spam');
		
		//how many of spams have been moved to trash
		$spamsMovedToTrashCount = $this->Event->findCount(array('Event.name'=>'COMMENT_MOVE_FROM_SPAM_TO_TRASH'));
		
		//how many of spams have been deleted?
		$spamsDeletedCount = $this->Event->findCount(array('Event.name'=>'COMMENT_DELETE_FROM_SPAM'));
		
		//ok, now we know how many spams in all we have got
		$spamsCount = $existingSpamsCount + $spamsMovedToTrashCount + $spamsDeletedCount;
		
		//return
		//return $spamsCount;
		return array('existingSpamsCount'=>$existingSpamsCount, 'spamsMovedToTrashCount'=>$spamsMovedToTrashCount, 'spamsDeletedCount'=>$spamsDeletedCount, 'spamsCount'=>$spamsCount);
	}		
}
?>