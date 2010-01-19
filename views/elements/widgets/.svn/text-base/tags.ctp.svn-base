<!-- div#tags starts -->
<div id="tags" class="widget">
	<h2><?php echo __('Tags'); ?></h2>
	<ul class="listview">
	<?php
	$tags = $this->requestAction('/tags/get');
	//pr($tags);
	foreach($tags as $tag)
	{
		echo '<li>' . $html->link($tag['Tag']['title'], '/tags/view/' . $tag['Tag']['slug']);
		if(isset($tag['Post']) && !empty($tag['Post'])) echo '<span class="description">'. count($tag['Post']).'</span></li>';
		else echo "</li>";
	}
	?>
	</ul>
</div>
<!-- div#tags ends -->