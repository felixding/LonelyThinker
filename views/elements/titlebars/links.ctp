<?php
	echo $widget->navigation(
		array(
			array('text'=>__('Links', true), 'url'=>'/links', 'activeUrl'=>'^links', 'class'=>'links'),
			array('text'=>__('Link Categories', true), 'url'=>'/link_categories', 'activeUrl'=>'^link_categories', 'class'=>'link-categories last-child')
		), 
		array('type'=>'ul', 'id'=>'titlebar-links')
	);
?>