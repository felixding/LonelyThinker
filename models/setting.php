<?php
/* SVN FILE:  $Id: setting.php 42 2009-09-24 12:53:07Z  $ */
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
class Setting extends AppModel {

    var $name = 'Setting';
    var $key = 'LT';
	var $validate = array(
        'key' => array(
       		'notEmpty'=>array('rule'=>'notEmpty', 'message'=>'You forgot to give a key for the setting'),
       		'maxLength'=>array('rule'=>array('maxLength', 48), 'message'=>'What? 100 characters are not enough for this?!')
       	)
    );

    //retrieve configuration data from the db
    function get()
    {
        // get all settings from db
        $settings = $this->find('all', array('fields'=>array('id','key','value')));

        // parse each setting
        foreach($settings as $setting)
        {
            // build the array for use later
            $data_array = array(
                        'id' =>    $setting['Setting']['id'],
                        'key' => $setting['Setting']['key'],
                        'value' => $setting['Setting']['value'],
                        'checksum' => md5($setting['Setting']['value']) );

            // write the config
            Configure::write($this->key . '.' . $setting['Setting']['key'], $setting['Setting']['value']);
        }
    }
}
?>