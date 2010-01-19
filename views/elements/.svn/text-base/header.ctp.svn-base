<h1><?php echo $html->link(Configure::read('LT.siteName'), '/'); ?></h1>
<span id="slogan"><?php echo Configure::read('LT.siteSlogan'); ?></span>
<?php
echo $widget->navigation(
	array(
		array('text'=>__('blog', true), 'url'=>'/', 'activeUrl'=>'^/$|^posts/index|^posts/view|^tags/view')
	), 
	array('type'=>'ul', 'id'=>'menu')
);
echo $widget->navigation(
	array(
		array('text'=>__('Login', true), 'url'=>'/users/login')
	), 
	array('type'=>'ul', 'id'=>'user-toolbox')
);
?>