<?php
	echo $widget->navigation(
		array(
			array('text'=>__('Brainpower', true), 'url'=>'/m-o/brainpower', 'activeUrl'=>'^m-o/brainpower|^m-o$', 'class'=>'brainpower'),
			array('text'=>__('Statistics', true), 'url'=>'/m-o/statistics', 'activeUrl'=>'^m-o/statistics', 'class'=>'statistics'),
			array('text'=>__('Blacklist', true), 'url'=>'/m-o/blacklist', 'activeUrl'=>'^m-o/blacklist', 'class'=>'blacklist last-child')
		), 
		array('type'=>'ul', 'id'=>'titlebar-m-o')
	);
?>