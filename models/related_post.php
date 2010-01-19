<?php
/* SVN FILE:  $Id: related_post.php 41 2009-08-04 13:12:50Z  $ */
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
class RelatedPost extends AppModel
{
	var $name = 'RelatedPost';
	var $belongsTo = array('Post');
	var $hasMany = array('PostsTag');
}
?>