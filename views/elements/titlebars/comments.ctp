<?php
	echo $widget->navigation(
		array(
			array('text'=>sprintf(__('Published (<span>%s</span>)', true), $commentsCountPublished), 'url'=>'/comments', 'activeUrl'=>'^comments[\/]{0,}$', 'class'=>'published'),
			array('text'=>sprintf(__('Spam (<span>%s</span>)', true), $commentsCountSpam), 'url'=>'/comments/spam', 'activeUrl'=>'^comments/spam', 'class'=>'spam'),
			array('text'=>sprintf(__('Trash (<span>%s</span>)', true), $commentsCountTrash), 'url'=>'/comments/trash', 'activeUrl'=>'^comments/trash', 'class'=>'trash last-child'),
			array('text'=>__('M-O', true), 'url'=>'/m-o', 'activeUrl'=>'^m-o/blacklist', 'class'=>'m-o last-child')
		), 
		array('type'=>'ul', 'id'=>'titlebar-comments')
	);
?>