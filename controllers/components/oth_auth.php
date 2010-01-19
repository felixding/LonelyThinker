<?php 

/*
 * othAuth an auth system for cakePHP
 * comments, bug reports are welcome crazylegs AT gmail DOT com
 * @author Othman Ouahbi aka CraZyLeGs
 * @website: http://www.devmoz.com/blog/
 * @version 0.5.4.5
 * @license MIT
 * todo Router::url() in cakeAdmin and probably somewhere else
 */

class othAuthComponent extends Object
{
	
/**
* Constants to modify the behaviour of othAuth Component
*/
	// Form vars
	var $user_login_var        = 'username';
	var $user_passw_var        = 'passwd';
	var $user_group_var        = 'group_id';
	var $user_cookie_var       = 'cookie';
	
	// DB vars
	var $user_table       	   = 'users';
	
	var $user_table_login      = 'username';
	var $user_table_passw      = 'passwd';
	var $user_table_name      = 'name';
	var $user_table_gid        = 'group_id';
	var $user_table_active     = 'active';
	var $user_table_last_visit = 'last_visit';
	var $auth_url_redirect_var = 'from';
	var $show_auth_url_redirect_var = true; // decorate the url or not
	var $user_model       = 'User';
	var $group_model      = 'Group';
	var $permission_model = 'Permission';
	
	var $history_active   = false;
	var $history_model    = 'UserHistory';
	/*
	 * Internals you don't normally need to edit those
	 */
	var $components    = array('Session','RequestHandler');
	var $controller    = true;
	var $gid = 1;
	var $redirect_page;
	var $hashkey       = "mYpERsOnALhaSHkeY";
	var $auto_redirect = true;
	
	var $login_page    = '/users/login';
	var $logout_page   = '';
	var $access_page   = '/users/access_page';
	var $noaccess_page = "/users/login"; // session_flash, flash, back or a page url
	
	var $mode = 'oth';
	var $pass_crypt_method   = 'md5'; // md5, sha1, crypt, crc32,callback
	var $pass_crypt_callback = null; // function name
	var $pass_crypt_callback_file = ''; // file where the function is declared ( in vendors )
	 
	
	var $cookie_active    = true;
	var $cookie_lifetime = '+1 day';
	
	// asc : the most important group is the group with smallest value
	// desc: the most important group is the group with greatest value
	var $gid_order = 'asc'; // asc desc
	var $strict_gid_check = true;
	
	var $kill_old_login = true; // when true, form can have another login with the same hash and del the old
	
	var $allowedAssocUserModels       = array();
	var $allowedAssocGroupModels      = array();
	var $allowedAssocPermissionModels = array();
	
	var $allowedLoginChars = array('@','.','_');
	
	var $error_number = 0;
	
	
	var $login_limit = false; // flag to toggle login attempts feature
	
	var $login_attempts_model = 'LoginAttempts';
	
	
	var $login_attempts_num = 3;
	
	var $login_attempts_timeout = 2; // in minutes
	
	var $login_locked_out = '+1 day';
	
    /**
     * I don't know why othAuth::check() doesn't work in controllers/components, so I wrote this method temporally
     * 
     * @date 2009-3-4
     */
    function sessionValid()
    {
    	return $this->Session->check('othAuth.' . $this->hashkey);
    }
    
    	
	// startup() is kindof useless here because we init the component in beforeFilter,
	// and startup is called after that and before the action.
	// $this->othAuth->controller = &$this;
    function startup(&$controller)
    {
       //$this->controller = &$controller;
    }
    
    function _getGidOp()
    {
    	if($this->strict_gid_check)
    	{
    		return '';
    	}else
    	{
    		return ($this->gid_order == 'desc')? '>=' : '<=';
    	}
    }
    
    function _getHashOf($str)
	{
		switch($this->pass_crypt_method)
		{
			case 'sha1':
				return ($str == '')? '' : sha1($str);
			break;
			case 'crypt':
				return crypt($str);
			break;
			case 'callback':
				vendor($this->pass_crypt_callback_file);

				if(function_exists($this->pass_crypt_callback))
				{
					return call_user_func($this->pass_crypt_callback,$str);
				}
				return false;
			break;
			case 'md5':
			default:
				return md5($str);
			break;
		}
	}
	function init($auth_config = null) 
	{
		if(is_array($auth_config) && !is_null($auth_config) && !empty($auth_config))
		{
			
			if(isset($auth_config['login_page']))
			{
				$this->login_page = $auth_config['login_page'];
			}
			
			if(isset($auth_config['logout_page']))
			{
				$this->logout_page = $auth_config['logout_page'];
			}
			
			if(isset($auth_config['access_page']))
			{
				$this->access_page = $auth_config['access_page'];
			}
			
			if(isset($auth_config['noaccess_page']))
			{
				$this->noaccess_page = $auth_config['noaccess_page'];
			}else
			{
				$this->noaccess_page = $this->login_page;
			}

			if(isset($auth_config['auto_redirect']))
			{
				$this->auto_redirect = (boolean) $auth_config['auto_redirect'];
			}
			
			if(isset($auth_config['hashkey']))
			{
				$this->hashkey = $auth_config['hashkey'];
			}
			
			if(isset($auth_config['strict_gid_check']))
			{
				$this->strict_gid_check = (boolean) $auth_config['strict_gid_check'];
			}
			
			if(isset($auth_config['mode']))
			{
				$this->mode = $auth_config['mode'];
			}

			if(isset($auth_config['allowModels']) && 
			is_array($auth_config['allowModels']))
			{
				if(isset($auth_config['allowModels']['user']) && 
				is_array($auth_config['allowModels']['user']))
				{
					$this->allowedAssocUserModels = $auth_config['allowModels']['user'];
				}
				
				if(isset($auth_config['allowModels']['group']) && 
				is_array($auth_config['allowModels']['group']))
				{
					$this->allowedAssocGroupModels = $auth_config['allowModels']['group'];
				}
				
				if(isset($auth_config['allowModels']['permission']) && 
				is_array($auth_config['allowModels']['permission']))
				{
					$this->allowedAssocPermissionModels = $auth_config['allowModels']['permission'];
				}
			}
		}
		
		// pass auth data to the view so it can be used by the helper
		$this->_passAuthData();
	}
	
	
	function login($ap = 1,$order ='asc') // username,password,group
   {
	   
	   if(!$this->_checkLoginAttempts())
	   {
	   		return -3; // too many login attempts
	   }
	   
	   $params = null;
	   if(!empty($this->controller->data[$this->user_model]))
	   {
	   		$params[$this->user_model] = $this->controller->data[$this->user_model];
	   }		
		return $this->_login($params);
   }
   
   /**
    * update session when the profile has been updated
    *
    * @author Felix Ding
    * @date 2009-04-15
    */
   function updateSession($row)
   {
   		$session = $this->Session->read('othAuth.'.$this->hashkey);
   		$session[$this->user_model][$this->user_table_name] = $row[$this->user_model][$this->user_table_name];
		$this->Session->write('othAuth.'.$this->hashkey, $session);
   }
   
   function _login($params,$ignore_cookie = false)
   {
	   switch ($this->mode)
	   {
	           case 'oth':
	                   return $this->othLogin($params,$ignore_cookie);
	                   break;
	           case 'nao':
	                   return $this->naoLogin($params,$ignore_cookie);
	                   break;
	           case 'acl':
	                   return $this->aclLogin($params,$ignore_cookie);
	                   break;
	           default:
	                   return $this->othLogin($params,$ignore_cookie);
	                   break;
	   }
   }
	
	function othLogin($params,$ignore_cookie=false) // username,password,group
	{
		 $params = $params[$this->user_model];
		 
		 if($this->Session->valid() && $this->Session->check('othAuth.'.$this->hashkey))
		 {
		 	if(!$this->kill_old_login)
		 	{
		 		return 1;
		 	}
		 } 

		 if(($params == null) || 
		 	!isset($params[$this->user_login_var]) || 
		 	!isset($params[$this->user_passw_var]))
		 {
		 	return 0;
		 }
		 
		 uses('sanitize');
		 $login = Sanitize::paranoid($params[$this->user_login_var],$this->allowedLoginChars);
		 $passw = Sanitize::paranoid($params[$this->user_passw_var]);
	 
		 if($login == "" || $passw == "") 
		 {
		 	return -1;
		 }
		
		if(!$ignore_cookie)
		{
			$passw = $this->_getHashOf($passw);	
		}
		
		$gid_check_op = $this->_getGidOp();//($this->strict_gid_check)?'':'<=';		 
		 $conditions = array();
		 
		 if(isset($params[$this->user_group_var]))
		 {
		 	$this->gid = (int) Sanitize::paranoid($params[$this->user_group_var]);
		 	
		 	// FIX
			if( $this->gid < 1)
			{
				$this->gid = 1;
			}
			$conditions[$this->user_model.'.'.$this->user_table_gid] = $gid_check_op.$this->gid;
		 }

		$conditions[$this->user_model.'.'.$this->user_table_login] = $login;
		$conditions[$this->user_model.'.'.$this->user_table_passw] = $passw;
		$conditions[$this->user_model.'.'.$this->user_table_active] = 1;
		
	    
	    $UserModel = & $this->_createModel();
		
		$row = $UserModel->find($conditions);
		
		
		if( empty($row) /* || $num_users != 1 */ )
		{
			$this->_saveLoginAttempts();
			return -2;
		}
		else
		{
			$this->_deleteLoginAttempts();
			
			if(!$ignore_cookie && 
			    !empty($params[$this->user_cookie_var]) )
			{
				$this->_saveCookie($row);
			}
		
			$this->_saveSession($row);
			
			// Update the last visit date to now
			if(isset($this->user_table_last_visit))
			{	
				$row[$this->user_model][$this->user_table_last_visit] = date('Y-m-d H:i:s');
				$res = $UserModel->save($row,true,array($this->user_table_last_visit)); 
			}
			
			// 0.2.5 save history
			if($this->history_active)
			{
				$this->_addHistory($row);
			}
			
			if($this->auto_redirect == true)
			{
				
				if(!empty($row[$this->group_model]['redirect']))
				{
					$goto = $row[$this->group_model]['redirect'];
				}
				else
				{
					$goto = $this->access_page;
				}
				$back = false;//isset($this->controller->params['url']['url'][$this->auth_url_redirect_var]);
				$this->redirect($goto,$back);
			}
			
			return 1;
		}
		 
	}
	
	function naoLogin($params,$ignore_cookie = false) // username,password,group
   	{
		 $params = $params[$this->user_model];
		 
		 if($this->Session->valid() && $this->Session->check('othAuth.'.$this->hashkey))
		 {
		 	if(!$this->kill_old_login)
		 	{
		 		return 1;
		 	}
		 }
		 
		 if($params == null || 
		 	!isset($params[$this->user_login_var]) || 
		 	!isset($params[$this->user_passw_var]))
		 {
		 	return 0;
		 }
		 
		 uses('sanitize');
		 $login = Sanitize::paranoid($params[$this->user_login_var],$this->allowedLoginChars);
		 $passw = Sanitize::paranoid($params[$this->user_passw_var]);
		 if(isset($params[$this->user_group_var]))
		 {
		 	
		 	$this->gid = (int) Sanitize::paranoid($params[$this->user_group_var]);
			if( $this->gid < 1)
			{
				$this->gid = 1;
			}
		 }
	 
		 if($login == "" || $passw == "") 
		 {
		 	return -1;
		 }
		 
		if(!$ignore_cookie)
		{
			$passw = $this->_getHashOf($passw);	
		}
		
		$conditions = array(
							"{$this->user_model}.".$this->user_table_login => "$login",
							"{$this->user_model}.".$this->user_table_passw => "$passw",
							"{$this->user_model}.".$this->user_table_active => 1);
		
		$UserModel =& new $this->user_model;
		$UserModel->unbindAll(array('belongsTo'=>array($this->group_model)));
		$UserModel->recursive = 2;

		$UserModel->{$this->group_model}->unbindAll(array('hasAndBelongsToMany'=>array($this->permission_model)));
		
		$row = $UserModel->find($conditions);
		
		$num_users = (int) $UserModel->findCount($conditions);

       $gids = array();

       if(!empty($row[$this->group_model])){
               foreach ($row[$this->group_model] as $group){
                       $gids[] = $group['level'];
               }
       }

       if($this->strict_gid_check)
       {
       		$allowed = in_array($this->gid,$gids);
       }
       else
       {
       		$allowed = false;
       		switch($this->gid_order)
       		{
       			case 'asc':
	       			foreach($gids as $gid)
	       			{
	       				if($this->gid >= $gid)
	       				{
	       					$allowed = true;
	       					break;
	       				}
	       			}
       			break;
       			case 'desc':
	       			foreach($gids as $gid)
	       			{
	       				if($this->gid >= $gid)
	       				{
	       					$allowed = true;
	       					break;
	       				}
	       			}
       			break;
       		}
       }

       if( empty($row) || $num_users != 1 || !$allowed)
       {
               $this->_saveLoginAttempts();
               return -2;
       }
       else
       {
			$this->_deleteLoginAttempts();
			
			if(!$ignore_cookie && 
			    !empty($params[$this->user_cookie_var]) )
			{
				$this->_saveCookie($row);
			}
			
			$this->_saveSession($row);
			
			// Update the last visit date to now
			if(isset($this->user_table_last_visit))
			{	
				$row[$this->user_model][$this->user_table_last_visit] = date('Y-m-d H:i:s');
				$res = $UserModel->save($row,true,array($this->user_table_last_visit)); 
			}
			
			// 0.2.5 save history
			if($this->history_active)
			{
				$this->_addHistory($row);
			}
			
			$redirect_page = $this->access_page;
			foreach($row[$this->group_model] as $grp)
			{
				if($grp['level'] == $this->gid)
				{
					if(!empty($grp['redirect']))
					{
						$redirect_page = $grp['redirect'];
					}
				}
			}
	
			$this->redirect($redirect_page);
			
			return 1;
       }

	}
	
	// 0.2.5
	function _addHistory(&$row)
	{
		$data[$this->history_model]['username']  = $row[$this->user_model][$this->user_table_login];
		$data[$this->history_model]['fullname']  = $row[$this->user_model]['fullname'];
		$data[$this->history_model]['groupname'] = $row[$this->group_model]['name'];
		if(isset($row[$this->user_model][$this->user_table_last_visit]))
		{
			$data[$this->history_model]['visitdate'] = $row[$this->user_model][$this->user_table_last_visit];
		}else
		{
			$data[$this->history_model]['visitdate'] = date('Y-m-d H:i:s');
		}
		
		loadModel($this->history_model);
		$HistoryModel =& new $this->history_model;
		$HistoryModel->save($data);
		
	}
	function _saveSession($row)
	{	
		 $login = $row[$this->user_model][$this->user_table_login];
		 $passw = $row[$this->user_model][$this->user_table_passw];
		 $gid   = $row[$this->user_model][$this->user_table_gid];
		 $hk    = $this->_getHashOf($this->hashkey.$login.$passw/*.$gid*/);
		 $row["{$this->user_model}"]['login_hash'] = $hk;
 		 $row["{$this->user_model}"]['hashkey']    = $this->hashkey;
		 $this->Session->write('othAuth.'.$this->hashkey,$row);

	}
	
	// null, true to delete the cookie
	function _saveCookie($row,$del = false)
	{
		if($this->cookie_active)
		{
			if(!$del)
			{
				$login  = $row[$this->user_model][$this->user_table_login];
				$passw  = $row[$this->user_model][$this->user_table_passw];
				
				$time   = strtotime($this->cookie_lifetime);
				$data   = $login.'|'.$passw;
				$data   = serialize($data);
				$data   = $this->encrypt($data);
				setcookie('othAuth',$data,$time,'/');
			}else
			{
				setcookie('othAuth','',strtotime('-999 day'),'/');
			}
		}
	}
	
	function _readCookie()
	{
		// does session exists
		if($this->Session->valid() && $this->Session->check('othAuth.'.$this->hashkey))
		{
			return;
		}
		if($this->cookie_active && isset($_COOKIE['othAuth'])) {
			
            $str = $_COOKIE['othAuth'];
            if (get_magic_quotes_gpc())
            {    
                $str=stripslashes($str);
            }
                       
			$str = $this->decrypt($str);
      		
            $str = @unserialize($str);          
            
            list($login,$passw) = explode('|',$str);
            //die($passw);
            
            $data[$this->user_model][$this->user_login_var] = $login;
            $data[$this->user_model][$this->user_passw_var] = $passw;
            $redirect_old = $this->auto_redirect;
            $this->auto_redirect = false;
            $ret = $this->_login($data,true);
            $this->auto_redirect = $redirect_old;
		}
	}
	
	// delete attempts after a successful login
	function _deleteLoginAttempts()
	{
		if($this->login_limit)
		{
			$ip = env('REMOTE_ADDR');
			
			loadModel($this->login_attempts_model);
			$Model = & new $this->login_attempts_model;
			
			$Model->del($ip);
			
			if($this->cookie_active)
			{
				setcookie('othAuth.login_attempts','',time() - 31536000,'/');
			}
		}
		
	}
	function _checkLoginAttempts()
	{
		if($this->login_limit)
		{
			$ip = env('REMOTE_ADDR');
			
			loadModel($this->login_attempts_model);
			
			$Model = & new $this->login_attempts_model;
			
			// delete all expired and timedout records
			$del_sql = "DELETE FROM {$Model->useTable} WHERE expire <= NOW()";
			if($this->login_attempts_timeout > 0)
			{
				$timeout = $this->login_attempts_timeout * 60;
				// 1.5.4 fixed a bug here, thanks to PatDaMilla
				$del_sql .= " OR ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(created) > $timeout )";
				// 
			}
			$Model->query($del_sql);
			
			$row = $Model->find(array($this->login_attempts_model.'.ip'=>$ip));
			
			if(!empty($row))
			{
				$num = $row[$this->login_attempts_model]['num'];
				
				$this->login_attempts_current_num = $num;
				
				if($num >= $this->login_attempts_num)
				{
					return false;
				}
			}else
			{
				$this->login_attempts_current_num = 0;
			}
			
			if($this->cookie_active && isset($_COOKIE['othAuth.login_attempts']))
			{
	            $cdata = $_COOKIE['othAuth.login_attempts'];
	            if (get_magic_quotes_gpc())
	            {    
	                $cdata=stripslashes($cdata);
	            }
	                       
				$cdata = $this->decrypt($cdata);
	      		
	            $cdata = @unserialize($cdata);      
	            
	            $time      = $cdata['t'];
	            $num_tries = $cdata['n'];
	            
	            if($num_tries >= $this->login_attempts_num)
				{
					return false;
				}
	            
	            if($this->login_attempts_current_num == 0 && $num_tries > 0) 
	            {
					$this->login_attempts_current_num = $num_tries;
	            }

			}
		}
		return true;
	} 
	
	function _saveLoginAttempts()
	{
		
		if($this->login_limit)
		{
			$num_tries = $this->login_attempts_current_num + 1;
			
			if (!is_numeric($this->login_locked_out)) 
			{
				$keep_for = (int) strtotime($this->login_locked_out);
				$time   = ($keep_for > 0 ? $keep_for : 999999999);
			}
			else
			{
				$keep_for = $this->login_locked_out;
				$time   = time() + ($keep_for > 0 ? $keep_for : 999999999);
			}
			
			//die(date("Y-m-d H:i:s",$keep_for));
			
			$expire = date("Y-m-d H:i:s", $time);
			$ip     = env('REMOTE_ADDR');
			
			//die(pr($expire));
			$data[$this->login_attempts_model]['ip']     = $ip;
			$data[$this->login_attempts_model]['expire'] = $expire;
			$data[$this->login_attempts_model]['num']    = $num_tries;
			
			if($num_tries <= 1) // dunno why the model doesn't handle this
			{
				$data[$this->login_attempts_model]['created'] = date("Y-m-d H:i:s");
			}
			
			loadModel($this->login_attempts_model);
			$Model = & new $this->login_attempts_model;
			$Model->save($data);
			
			if($this->cookie_active)
			{
				$cdata = $this->encrypt(serialize(array('t'=>time(),'n'=>$num_tries)));
				setcookie('othAuth.login_attempts',$cdata,$time,'/');
			}
		}
	}
	
	function __notcurrent($page)
	{
		if($page == "") return false;
		
		uses('inflector');
		
		$c = strtolower(Inflector::underscore($this->controller->name));
		$a = strtolower($this->controller->action);
		
		$page = strtolower($page.'/');
		
		$c_a = $this->_handleCakeAdmin($c,$a);
		if($page[0] == '/')
		{
			$c_a = '/'.$c_a;
		}
		//die($c_a.' '.$page);
		$not_current = strpos($page,$c_a);
		// !== is required, $not_current might be boolean(false)
		return ((!is_int($not_current)) || ($not_current !== 0));
	}
	
 	function redirect($page = "",$back = false) 
    {     
        if($page == "")  
            //$page = $this->redirect_page; 
            $page = $this->logout_page; 
             
        if(isset($this->auth_url_redirect_var)) 
        { 
            if(!isset($this->controller->params['url'][$this->auth_url_redirect_var])) 
            {     
                if($back == true) 
                { 
		 		     // ==== Ritesh: modified from here ==========
				    $frompage = '/'; 
				    if(isset($this->controller->params['url']['url'])) {
					   $frompage .= $this->controller->params['url']['url'];  //if url is set then set frompage to url 
					   $parameters = $this->controller->params['url'];   // get url array
					   unset($parameters['url']);
					   $para = array();
			           foreach($parameters as $key => $value){ //for each parameter of the url create key=value string 
				       	$para[] =  $key . '=' . $value;
			           }
					   if(count($para) > 0){
					      $frompage .= '?' . implode('&',$para); //attach parameters to the frompage
					   }
				    }
	            	$this->Session->write('othAuth.frompage',$frompage); 
	            	if($this->show_auth_url_redirect_var) {
	            		$page .= "?".$this->auth_url_redirect_var."=".$frompage;
	            	}
	            	//====== end of modification =================
                } 
                else  
                {     
                    if($this->Session->check('othAuth.frompage')) 
                    { 
                        $page = $this->Session->read('othAuth.frompage'); 
                        $this->Session->del('othAuth.frompage'); 
                    } 
                } 
            }    
             
        } 

        if($this->__notcurrent($page))
        {
           if ($this->RequestHandler->isAjax())
           {
                   	// setAjax is deprecated in 1.2
                   if($this->is_11()) //1.1
                   { 
                   	//$this->RequestHandler->setAjax(&$this->controller);
                   }else // 1.2
                   {
						$this->controller->layout = $this->RequestHandler->ajaxLayout;
						$this->RequestHandler->respondAs('html', array('charset' => 'UTF-8'));
                   }
                   // Brute force ! you've got a better way ?
                   /*echo '<script type="text/javascript">window.location = "'. 
                   $this->url($page). 
                   '"</script>'; 
                   */
                   echo '<meta http-equiv="refresh" content="0;url='.$this->url($page).'" />';
                   exit; 
           } 
           else 
           { 
                   $this->controller->redirect($page); 
                   exit; 
           } 
        } 
    }
    

	
    // Logout the user
    //FIX:
    //   logout_page is the logout action OR the the action to redirect to after logout ?
    function logout ($kill_cookie = true)
	{	
		$us = 'othAuth.'.$this->hashkey;
		
		if($this->Session->valid() && $this->Session->check($us))
		{
			$ses = $this->Session->read($us);
			
			if(!empty($ses) && is_array($ses))
			{
				// two logins of different hashkeys can exist
				if($this->hashkey == $ses[$this->user_model]['hashkey'])
				{
					$this->Session->del($us);
					$this->Session->del('othAuth.frompage');
					/*
					$o = $this->Session->check('othAuth');
					if( is_array( $o ) && empty( $o  )) 
					{
						$this->Session->del('othAuth');
					}
					*/
					//unset($_SESSION['othAuth'][$this->hashkey]);
					if($kill_cookie)
					{
						$this->_saveCookie(null,true);
					}					
					if($this->auto_redirect == true)
					{	
						// check if logout_page is the action where logout is called!
						if(!empty($this->logout_page))
						{
							$this->redirect($this->logout_page);
						}
					}
					return true;
				}
			}
		}
		return false;
    }
	

    // Confirms that an existing login is still valid
    function check()
	{
		
		// try to read cookie
		$this->_readCookie();
		// is there a restriction list && action is in
		if($this->_validRestrictions())
		{	
			$us 	   = 'othAuth.'.$this->hashkey;
			
			// does session exists
			if($this->Session->valid() && 
			   $this->Session->check($us))
			{
				$ses 	   = $this->Session->read($us);
				$login     = $ses["{$this->user_model}"][$this->user_table_login];
				$password  = $ses["{$this->user_model}"][$this->user_table_passw];
				$gid       = $ses["{$this->user_model}"][$this->user_table_gid];
				$hk        = $ses["{$this->user_model}"]['login_hash'];
				
				
				// is user invalid
				if ($this->_getHashOf($this->hashkey.$login.$password/*.$gid*/) != $hk)
				{	
					$this->logout();
					return false;
				}
				 
               switch ($this->mode)
               {
	               case 'oth':
	                       $permi = $this->_othCheckPermission($ses);
	                      
	                       break;
	               case 'nao':
	                       $permi = $this->_othCheckPermission($ses,true);
	                       break;
	               case 'acl':
	                       $permi = $this->_aclCheckPermission($ses);
	                       break;
	               default:
	                       $permi = $this->_othCheckPermission($ses);
               }
				// check permissions on the current controller/action/p/a/r/a/m/s
				if(!$permi)
				{
					if($this->auto_redirect == true) 
					{
						// should probably add $this->noaccess_page too or just flash
						//print_r($this->controller->params);
						$this->redirect($this->noaccess_page,true);
					}
					return false;
				}
				
				return true;
				
			}
			
			if($this->auto_redirect == true) 
			{
				$this->redirect($this->login_page,true);
			}
			return false;	
		}
		
		return true;
    }
	
	function _validRestrictions()
	{
		$isset   = isset($this->controller->othAuthRestrictions);
		if($isset)
		{
			$oth_res = $this->controller->othAuthRestrictions;
			
			if(is_string($oth_res))
			{
				if(($oth_res === "*") ||(
				Configure::read('Routing.admin') && (($oth_res === Configure::read('Routing.admin')) || $this->isCakeAdminAction())))
				{
					if(
					   $this->__notcurrent($this->login_page) && 
					   $this->__notcurrent($this->logout_page))
					{
						//die('here');
						return true;
					}	
				}
				
			}
			elseif(is_array($oth_res))
			{
				if(defined('CAKE_ADMIN'))
				{
					if(in_array(CAKE_ADMIN,$oth_res))
					{
						if($this->isCakeAdminAction())
						{
							if($this->__notcurrent($this->login_page) && 
							   $this->__notcurrent($this->logout_page))
							{
								return true;
							}
						}
					}
				}
				foreach($oth_res as $r)
				{
					$pos = strpos($r."/",$this->controller->action."/");
					if($pos === 0)
					{
						return true;
					}
				}
			}
		}
		
		return false;
	}
	
	function _othCheckPermission(&$ses,$multi = false)
	{
		uses('inflector');
		
		$c   = strtolower(Inflector::underscore($this->controller->name));
		$a   = strtolower($this->controller->action);
		$h   = strtolower($this->controller->here);
		$c_a = $this->_handleCakeAdmin($c,$a);// controller/admin_action -> admin/controller/action
		
		// extract params
		$aa  =  substr( $c_a, strpos($c_a,'/'));
		
		$params = isset($this->controller->params['pass']) ? implode('/',$this->controller->params['pass']): '';
		
		$c_a_p = $c_a.$params;
		
		$return = false;
		
		if(!isset($ses[$this->group_model][$this->permission_model]))
		{
			return false;
		}
		if(!$multi)
		{
			$ses_perms = $ses[$this->group_model][$this->permission_model];
		}else
		{
           foreach ($ses[$this->group_model] as $groups) 
           {
               if(isset($groups[$this->permission_model])){
                       $ses_perms = am($ses_perms, $groups[$this->permission_model]);
               }
           }
		}
		
		// quickly check if the group has full access (*) or 
		// current_controller/* or CAKE_ADMIN/current_controller/*
		// full params check isn't supported atm
		foreach($ses_perms as $sp)
		{
			if($sp['name'] == '*')
			{
				return true;
			}else
			{
				$sp_name = strtolower($sp['name']);
				$perm_parts = explode('/',$sp_name);
				// users/edit/1 users/edit/*
				//  users/* users/*
				
				if(defined('CAKE_ADMIN'))
				{
					
					if((count($perm_parts) > 1)  && 
					   ($perm_parts[0] == CAKE_ADMIN) &&
					   ($perm_parts[1] == $c) && 
					   ($perm_parts[2] == "*"))
					{
						return true;
					}
				}
				//else
				//{
					if((count($perm_parts) > 1)  && 
					   ($perm_parts[0] == $c) && 
					   ($perm_parts[1] == "*"))
					{
						return true;
					}
				//}

			}
		}
		
		
		if(is_string($this->controller->othAuthRestrictions))
		{
			$is_checkall   = $this->controller->othAuthRestrictions === "*";
			$is_cake_admin = defined('CAKE_ADMIN') && ($this->controller->othAuthRestrictions === CAKE_ADMIN);
			if($is_checkall || $is_cake_admin)
			{
				foreach($ses_perms as $p)
				{	
					if(strpos($c_a_p,strtolower($p['name'])) === 0)
					{
						$return = true;
						break;
					}
				}
			}
		}
		else 
		{
			$a_p_in_array = in_array($a.'/'.$params, $this->controller->othAuthRestrictions);
			
			// if current url is restricted, do a strict compare
			// ex if current url action/p and current/p is in the list
			// then the user need to have it in perms
			// current/p/s current/p
			if($a_p_in_array)
			{
				
				foreach($ses_perms as $p)
				{
					if($c_a_p == strtolower($p['name']))
					{
						$return = true;
						break;
					}
				}
			}
			// allow a user with permssion on the current action to access deeper levels
			// ex: user access = 'action', allow 'action/p'
			else 
			{
				foreach($ses_perms as $p)
				{
					if(strpos($c_a_p,strtolower($p['name'])) === 0)
					{
						$return = true;
						break;
					}
				}
			}
		}
		return $return;
	}
	
   function _aclCheckPermission(&$ses)
   {
           //die('c');
           $c   = Inflector::underscore($this->controller->name);
           $a   = $this->controller->action;

           $aco = "$c:$a";

           $login = $ses["{$this->user_model}"][$this->user_table_login];

           return $this->_aclCheckAccess($login, $aco);
   }

   function _aclCheckAccess($aro_alias, $aco)
   {
           // Check access using the component:
           $access = $this->Acl->check($aro_alias, $aco, $action = "*");
           if ($access === false)
           {
                   return false;
           }
           else
           {
                   return true;
           }
   }
   
	function _handleCakeAdmin($c,$a)
	{
		if(defined('CAKE_ADMIN'))
		{
			$strpos = strpos($a,CAKE_ADMIN.'_');
			if($strpos === 0)
			{
				$function = substr($a,strlen(CAKE_ADMIN.'_'));
				if($c == null) return $function.'/';
				$c_a = CAKE_ADMIN.'/'.$c.'/'.$function.'/';
				return $c_a;
			}else
			{
				if($c == null) return $a.'/';
			}	
		}
		return $c.'/'.$a.'/';
	}
	
	function getSafeCakeAdminAction()
	{
		if(defined('CAKE_ADMIN'))
		{
			$a = $this->controller->action;
			$strpos = strpos($a,CAKE_ADMIN.'_');
			if($strpos === 0)
			{
				$function = substr($a,strlen(CAKE_ADMIN.'_'));
				
				return $function;
			}
		}
		return $this->controller->action;
	}
	
	function isCakeAdminAction()
	{
		if(defined('CAKE_ADMIN'))
		{
			$a = $this->controller->action;
			$strpos = strpos($a,CAKE_ADMIN.'_');
			if($strpos === 0)
			{
				return true;
			}
		}
		return false;
	}
	
	// helper methods
	function user($arg)
	{
		$us = 'othAuth.'.$this->hashkey;
		// does session exists
		if($this->Session->valid() && $this->Session->check($us))
		{
			$ses = $this->Session->read($us);
			if(isset($ses["{$this->user_model}"][$arg]))
			{
				return $ses["{$this->user_model}"][$arg];
			}
			else
			{
				return false;
			}
		}
		return false;	
	}
	
	// helper methods
	function group($arg)
	{
		$us = 'othAuth.'.$this->hashkey;
		// does session exists
		if($this->Session->valid() && $this->Session->check($us))
		{
			$ses = $this->Session->read($us);
			if(isset($ses["{$this->group_model}"][$arg]))
			{
				return $ses["{$this->group_model}"][$arg];
			}
			else
			{
				return false;
			}
		}
		return false;	
	}
	
	
	// helper methods
	function permission($arg)
	{
		$us = 'othAuth.'.$this->hashkey;
		// does session exists
		if($this->Session->valid() && $this->Session->check($us))
		{
			$ses = $this->Session->read($us);
			if(isset($ses[$this->group_model][$this->permission_model]))
			{
				$ret = array();
				if(is_array($ses[$this->group_model][$this->permission_model]))
				{
					for($i = 0; $i < count($ses[$this->group_model][$this->permission_model]); $i++ )
					{
						$ret[] = $ses[$this->group_model][$this->permission_model][$i][$arg];	
					}
				}
				return $ret;
			}
			else
			{
				return false;
			}
		}
		return false;	
	}
	
	function getData($arg = '',$only = true)
	{
		$us = 'othAuth.'.$this->hashkey;
		// does session exists
		if($this->Session->valid() && $this->Session->check($us))
		{
			$data = $this->Session->read($us);
			$arg = strtolower($arg);
			
			if($arg == 'user')
			{
				$data = $data['User'];
				
			}elseif($arg == 'group')
			{
				if($only)
				{
					unset($data['Group']['Permission']);
				}
				
				$data = $data['Group'];
				
			}elseif($arg == 'permission')
			{
				$data = $data['Group']['Permission'];
			}
			
			return $data;
		}
		return false;
	}
	
	// passes data to the view to be used by the helper
	function _passAuthData()
	{
		
		$data = get_object_vars($this);
		
		unset($data['controller']);
		unset($data['components']);
		unset($data['Session']);
		unset($data['RequestHandler']);
		
		$this->controller->set('othAuth_data',$data);
	}
	
	
	function encrypt($string)
	{
    	$key = $this->hashkey;
    	$result = '';
    	for($i=0; $i<strlen($string); $i++) {
      		$char = substr($string, $i, 1);
     		$keychar = substr($key, ($i % strlen($key))-1, 1);
     		$char = chr(ord($char)+ord($keychar));
     		$result.=$char;
   		}

   		return base64_encode($result);
  	}

  	function decrypt($string) 
  	{
   		$key = $this->hashkey;
   		$result = '';
   		$string = base64_decode($string);

   		for($i=0; $i<strlen($string); $i++) {
     		$char = substr($string, $i, 1);
     		$keychar = substr($key, ($i % strlen($key))-1, 1);
     		$char = chr(ord($char)-ord($keychar));
     		$result.=$char;
   		}

   		return $result;
  }
	function getMsg($id) 
	{
		switch($id) {
		case 1:
			{
				return "You are already logged in.";
			}break;
		case 0:
			{
				return "Please login!";
			}break;
		case -1:
			{
				 return $this->user_login_var."/".$this->user_passw_var." empty";
			}break;
		case -2:
			{
				 return "Wrong ".$this->user_login_var."/".$this->user_passw_var;
			}break;
		case -3:
			{
				 return "Too many login attempts.";
			}break;
		default:
			{
				 return "Invalid error ID";
			}break;
		
		}
	}
	
	/*
	 * Create the User model to be used in login methods.
	 */
	function _createModel()
	{
		// since we don't know if the models have extra associations we need to
		// unbind all the models, and bind only the ones we're interested in
		// mainly for performance ( and security )
		

		if (ClassRegistry::isKeySet($this->user_model))
		{
			$UserModel =& ClassRegistry::getObject($this->user_model); 
		} 
		else 
		{ 
			loadModel($this->user_model);
			
			$UserModel =& new $this->user_model; 
			
		}
		
        $forUser  = array('belongsTo'=>array($this->group_model),
                          'hasOne'=>array(),
                          'hasMany'=>array(),
                          'hasAndBelongsToMany'=>array()
                         );
        $forGroup = array('belongsTo'=>array(),
                          'hasOne'=>array(),
                          'hasMany'=>array(),
                          'hasAndBelongsToMany'=>array($this->permission_model)
                         );
        $forPerm  =  array('belongsTo'=>array(),
                           'hasOne'=>array(),
                           'hasMany'=>array(),
                           'hasAndBelongsToMany'=>array()
                          );
		
		
		$forUser  = $this->_mergeModelsToKeep($forUser,$this->allowedAssocUserModels);
		$forGroup = $this->_mergeModelsToKeep($forGroup,$this->allowedAssocGroupModels);
		$forPerm  = $this->_mergeModelsToKeep($forPerm,$this->allowedAssocPermissionModels);

		// TODO:
		// should save the old recursive for the three models
		// add default recursives for user 2, for group 1, for permission 1
		// so that extra models can be fetched if supplied
		$UserModel->recursive = 2;
		$UserModel->unbindAll($forUser);
		$UserModel->{$this->group_model}->unbindAll($forGroup);
		
		$UserModel->{$this->group_model}->{$this->permission_model}->unbindAll($forPerm);
																		
		return $UserModel; 
	}
	
	function _mergeModelsToKeep($initial,$toAdd)
	{
		if(!empty($toAdd))
		{
			if(isset($toAdd['belongsTo']))
			{
				$initial['belongsTo'] =
				am($initial['belongsTo'],$toAdd['belongsTo']);
			}
			if(isset($toAdd['hasOne']))
			{
				$initial['hasOne'] = am($initial['hasOne'],	$toAdd['hasOne']);
			}
			if(isset($toAdd['hasMany']))
			{
				$initial['hasMany'] = am($initial['hasMany'],	$toAdd['hasMany']);
			}
			if(isset($toAdd['hasAndBelongsToMany']))
			{
				$initial['hasAndBelongsToMany'] = am($initial['hasAndBelongsToMany'],
													 $toAdd['hasAndBelongsToMany']);
			}
		}

		return $initial;
	}

	// is it cake version 1.1 ?
    function is_11()
    {
    	return (function_exists('strip_plugin'));
    }	
   function url($url = null)
   {
		if($this->is_11()) // 1.2 doesn't have strip_plugin
        {
           $base = strip_plugin($this->controller->base, $this->controller->plugin);
           
           if (empty($url))
           {
                   return $this->controller->here;
           }
           elseif ($url{0} == '/')
           {
                   $output = $base . $url;
           }
           else
           {
                   $output = $base.'/'.strtolower($this->controller->params['controller']).'/'.$url;
           }
           return preg_replace('/&([^a])/', '&\1', $output);
        }
        else
        {
        	return Router::url($url, false); // for 1.2
        }
   }
	
}
?>