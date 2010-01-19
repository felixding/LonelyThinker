<h1><?php echo $html->link(Configure::read('LT.siteName'), '/'); ?></h1>
<span id="slogan"><?php echo Configure::read('LT.siteSlogan'); ?></span>
<?php
echo $widget->navigation(
	array(
		array('text'=>__('New Post', true), 'url'=>'/posts/add', 'activeUrl'=>'^posts/add|^posts/edit'),
		array('text'=>__('Comments', true), 'url'=>'/comments', 'activeUrl'=>'^comments'),
		array('text'=>__('Tags', true), 'url'=>'/tags', 'activeUrl'=>'^tags'),
		array('text'=>__('Links', true), 'url'=>'/links', 'activeUrl'=>'^links|^link_categories'),		
		array('text'=>__('M-O', true), 'url'=>'/m-o', 'activeUrl'=>'^m-o'),
		array('text'=>__('VisitorSense', true), 'url'=>'/sensors', 'activeUrl'=>'^sensors'),
		array('text'=>__('SmartRecommendation', true), 'url'=>'/related_posts', 'activeUrl'=>'^related_posts'),
		array('text'=>__('Settings', true), 'url'=>'/settings', 'activeUrl'=>'^settings')
	), 
	array('type'=>'ul', 'id'=>'menu')
);
echo $widget->navigation(
	array(
		array('text'=>$othAuth->user('name'), 'url'=>'/users/profile'),
		array('text'=>__('Logout', true), 'url'=>'/users/logout?from=/')
	), 
	array('type'=>'ul', 'id'=>'user-toolbox')
);	
?>