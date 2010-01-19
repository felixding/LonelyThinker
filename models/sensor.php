<?php
/* SVN FILE:  $Id: sensor.php 39 2009-07-16 10:03:24Z  $ */
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
class Sensor extends AppModel
{
    var $name = 'Sensor';
    var $validate = array(
        'trigger' => array('rule'=>'notEmpty', 'message'=>'Tigger is required'),
        'trigger_option' => array('rule'=>'notEmpty', 'message'=>'Tigger option is required'),
        'action' => array('rule'=>'notEmpty', 'message'=>'Action is required'),
        'action_option' => array('rule'=>'notEmpty', 'message'=>'Action option is required')        
    );    
}
?>