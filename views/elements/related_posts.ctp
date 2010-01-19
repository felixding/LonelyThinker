<?php
/*
 * temporarily disable the feature
*/ 
$relatedPosts = $this->requestAction('/related_posts/get/'.$postId);
if(count($relatedPosts)):
?>
<!-- div#related_posts starts -->
<div id="related_posts">
	<h2><?php __('Related Posts'); ?></h2>
	<ul>
	<?php		
		foreach($relatedPosts as $relatedPost)
		{
			echo '<li><span class="title">' . $html->link($relatedPost['Post']['title'], '/posts/view/'.$relatedPost['Post']['slug'], array('target'=>'_blank')) . '</span><span class="created">' . $relatedPost['Post']['created'] . '</span></li>';
		}
	?>											
	</ul>
</div>	
<!-- div#related_posts ends -->
<?php endif; ?>