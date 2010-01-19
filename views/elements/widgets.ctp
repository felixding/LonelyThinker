<?php
$packages = array(
				array('url'=>'Posts.index', 'widgets'=>array('about', 'tags', 'latest_comments')),
				array('url'=>'Posts.view', 'widgets'=>array('about', 'tags')),
				array('url'=>'Tags.view', 'widgets'=>array('about', 'tags'))
			);	
					
$url = $this->name . '.' . $this->action;

foreach($packages as $package)
{
	if(eregi($package['url'], $url))
	{	
		for($i=0;$i<count($package['widgets']);$i++)
		{
			echo $this->element('widgets/' . $package['widgets'][$i]);
		}
		break;
	}
}
?>