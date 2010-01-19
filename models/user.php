<?php
/* SVN FILE:  $Id: user.php 39 2009-07-16 10:03:24Z  $ */
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
class User extends AppModel
{
    var $name = 'User';
    var $belongsTo = 'Group';
	var $validate = array(
        'name' => array('rule'=>'notEmpty', 'message'=>'You forgot the name'),
        'email' => array(
        	'email'=>array('rule'=>'email', 'message'=>"Make sure you have input an valid email address"),
        	'isUnique'=>array('rule'=>'isUnique', 'message'=>"This email address is already in use, please try another")
        	),
        'passwd' => array(
        	'required' => array('rule'=>'areCharactersValid', 'message'=>'Must be 6 characters or longer'),
        	'length' => array('rule'=>'doPasswordsMatch', 'message'=>"Your passwords don't match")
		)
    );

	/**
	 * custom validation rules
	 */
	public function areCharactersValid()
	{
		if(isset($this->data['User']['passwd']) && !empty($this->data['User']['passwd'])) return preg_match('/[a-zA-Z0-9\_\-]{6,}$/i', $this->data['User']['passwd']);
		else return true;
	}
    
    /**
     * custom validation rules
     *
     * courtesy of http://edwardawebb.com/programming/php-programming/cakephp/complex-validation-cakephp-12
     */
    public function doPasswordsMatch()
    {
		$passed = true;
		
		//only run if there are two password feield (like NOT on the contact or signin pages..)
		if(isset($this->data['User']['confirmpassword']))
		{
			if($this->data['User']['passwd'] != $this->data['User']['confirmpassword'])
			{
				$this->invalidate('passwd');
				
				//they didnt condifrm password
				$passed = false;
			}
			else
			{
				//hash password before saving
				$this->data['User']['passwd']=md5($this->data['User']['passwd']);
			}
		}

		return $passed;
	}
}
?>