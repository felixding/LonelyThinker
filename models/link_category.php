<?php
/* SVN FILE:  $Id: link_category.php 42 2009-09-24 12:53:07Z  $ */
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
class LinkCategory extends AppModel
{
    var $name = 'LinkCategory';
    var $hasMany = array('Link');
	var $validate = array(		
        'title' => array(
        	'notEmpty'=>array('rule'=>'notEmpty', 'message'=>'You forgot to name a title for the link category'),
        	'maxLength'=>array('rule'=>array('maxLength', 100), 'message'=>'What? 100 characters are not enough for the title?!')
        )
    );	
}
?>