<?php
/* SVN FILE:  $Id: widget.php 34 2009-05-13 13:07:04Z  $ */
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
 * @version       $Revision: 34 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-05-13 21:07:04 +0800 (Wed, 13 May 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class WidgetHelper extends Helper
{
	var $helpers = array('Html', 'Form');

	/**
	 * replace an new line to an paragraph HTML tag
	 *
	 * @author Felix Ding
	 */	
	function nl2p($str)
	{
	  return str_replace('<p></p>', '', '<p>' . preg_replace('#\n|\r#', '</p>$0<p>', $str) . '</p>');
	}

	/**
	 * replace a customized img tag to a HTML img tag
	 *
	 * @author Felix Ding
	 * @date 2008-02-22
	 * @param String message
	 * @return HTML codes including an img tag
	 */
	function emoticon($message)
	{
		if(!$message) return false;

		//define images' path
		$emoticonPath = Router::url('/img/emoticons', true);
		//return eregi_replace("\\[(emotion_)([0-9]*)\\]", "<img src=\"./images/emotions/\\2.gif\">", $message);

		return eregi_replace("\\[(emoticon:)([0-9a-zA-Z_]*)\\]", $this->Html->image($emoticonPath . '/' . 'smileys' . '/\\2.gif', array('alt'=>'\\2.gif', 'class'=>'emoticon')), $message);
	}
	
	/**
	 * display status, 'on' or 'off'
	 *
	 * @param $status String 'on' or 'off'
	*/
	function displayStatus($status)
	{
		if(empty($status) || ($status != 'on' && $status != 'off')) return false;
		return '<span class="'.$status.'">'.__($status, true).'</span>';
	}
	
	/**
	 * make a linkActions menu
	 *
	 * @param $items Array array(url, text, class)
	 * @date 2009-03-09
	 */
	function linkActions($items, $options = array())
	{
		//default options
		$defaultOptions = array(
			'type' => 'ul',
			'id' => ''
		);
		
		foreach($defaultOptions as $key=>$value)
			if(!isset($options[$key]) && !empty($defaultOptions[$key])) $options[$key] = $defaultOptions[$key];
		
		//
		$actions = '<'.$options['type'];
		$actions.= isset($options['id']) ? ' id="'.$options['id'].'"' : '';
		$actions.= ' class="actions">';

		foreach($items as $item)
		{
			if(isset($item['seperation']) && $item['seperation']==true)
			{
				$actions.= '<li class="seperation">&nbsp;</li>';		
			}
			else
			{
				$actions.= '<li>';
				$actions.= $this->Html->link($item['text'], Router::url($item['url'], true), array('class'=>$item['class']));
				$actions.= '</li>';
			}
		}
		
		$actions.= '</'.$options['type'].'>';
		
		//
		return $actions;
	}	
	
	/**
	 * make a formActions menu
	 *
	 * @param $items Array array(url, text, class)
	 * @date 2009-03-09
	 */
	function formActions($items, $options = array())
	{
		//default options
		$defaultOptions = array(
			'type' => 'ul',
			'model'=>'',
			'id' => ''
		);
		
		foreach($defaultOptions as $key=>$value)
			if(!isset($options[$key]) && !empty($defaultOptions[$key])) $options[$key] = $defaultOptions[$key];
		
		//
		$actions = '<'.$options['type'];
		$actions.= isset($options['id']) ? ' id="'.$options['id'].'"' : '';
		$actions.= ' class="actions">';

		foreach($items as $item)
		{
			$actions.= '<li>';
			$actions.= $this->Form->create($options['model'], array('url'=>Router::url($item['url'], true)));
			$actions.= $this->Form->submit($item['text'], array('class'=>$item['class'], 'div'=>false));
			$actions.= $this->Form->end();
			$actions.= '</li>';			
		}
		
		$actions.= '</'.$options['type'].'>';
		
		//
		return $actions;
	}
	
	/**
	 * make a navigation menu
	 *
	 * @params $items Array menu items in format array(text, url [, activeUrl] [, class]). activeUrl can be RegEx, class will be add to the li.
	 * @params $options Array options in format array(type, id, class), type can be either ul or ol
	 * @author Felix Ding
	 * @date 2009-03-05
	 */
	function navigation($items, $options)
	{
		//default options
		$defaultOptions = array(
			'type' => 'ul',
			'id' => '',
			'class' => 'current'
		);
		
		foreach($defaultOptions as $key=>$value)
			if(!isset($options[$key]) && !empty($defaultOptions[$key])) $options[$key] = $defaultOptions[$key];
		
		//
		$navigation = '<'.$options['type'];
		$navigation.= isset($options['id']) ? ' id="'.$options['id'].'"' : '';
		$navigation.= '>';

		foreach($items as $item)
		{			
			if(isset($item['class']))
			{
				$class = ($item == end($items)) ? $item['class'].' last-child' : $item['class'];
				$navigation.= '<li class="'.$class.'">';
			}
			else
			{
				$navigation.= ($item == end($items)) ? '<li class="last-child">' : '<li>';
			}						
			
			if(isset($item['activeUrl']) && ereg($item['activeUrl'], $this->params['url']['url']))
				$navigation.= $this->Html->link($item['text'], $item['url'], array('class'=>$options['class']), false, false);
			else
				$navigation.= $this->Html->link($item['text'], $item['url'], null, false, false);
				
			$navigation.= '</li>';
		}
		
		$navigation.= '</'.$options['type'].'>';
		
		//return
		return $navigation;
		
	}
	
	/**
	 * display a formatted message box
	 *
	 * @param $message String the message to display
	 * @return String formatted message box
	 * @date 2009-04-19
	 */
	function message($message)
	{
		return '<div class="message hidden">'.$message.'</div>';
	}
}
?>