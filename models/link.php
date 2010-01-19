<?php
/* SVN FILE:  $Id: link.php 42 2009-09-24 12:53:07Z  $ */
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
class Link extends AppModel
{
    var $name = 'Link';
    var $belongsTo = array('LinkCategory');
	var $validate = array(
		'link_category_id' => array('rule'=>'validCategoryId', 'message'=>'Make sure you enter a valid link category id'),
        'title' => array(
        	'notEmpty'=>array('rule'=>'notEmpty', 'message'=>'You forgot to name a title for the link'),
        	'maxLength'=>array('rule'=>array('maxLength', 100), 'message'=>'What? 100 characters are not enough for the title?!')
        ),
        'url' => array(
        	'notEmpty'=>array('rule'=>'notEmpty', 'message'=>'Make sure you enter a valid URL'),
        	'maxLength'=>array('rule'=>array('maxLength', 255), 'message'=>'What? 255 characters are not enough for the URL?!')
        )
    );
    
    public function validCategoryId($data)
    {
    	$linkCategory = ClassRegistry::init('LinkCategory');
    	return $linkCategory->findById($data);
    }  
}
?>