<?php
/* SVN FILE:  $Id: users_controller.php 39 2009-07-16 10:03:24Z  $ */
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
class UsersController extends AppController
{
	var $name = 'Users';
	var $othAuthRestrictions = array('logout', 'preferences');
  
    public function login()
    {
    	if(isset($this->data['User']))
    	{
       		$auth_num = $this->othAuth->login($this->data['User']);
       		
       		//only alert when the user has input a wrong username/password
        	if($auth_num == -2) $this->set('auth_msg', $this->othAuth->getMsg($auth_num));
    	}
    }
    
    public function logout()
    {
    	$this->othAuth->logout();
    	$this->redirect('/');
    }
    
    /**
     * update the use's profile
     *
     * @date 2009-04-15
     */
    public function profile()
    {
    	$this->User->id = $this->othAuth->user('id');
    	$user = $this->User->read();

    	if(!empty($this->data))
    	{	
    		if($this->User->save($this->data))
    		{
    			$this->set('saved', true);
    			$this->othAuth->updateSession($this->data);    			
    		}
    		
    		$user = $this->data;		    		
    	}
    	
    	$this->set('user', $user);
    }
}
?>
