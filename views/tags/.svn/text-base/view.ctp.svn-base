<?php $this->pageTitle = $tag['Tag']['title']; ?>			
<!-- div#primary starts -->
<div id="primary">
	<!-- div#tag_description starts -->
	<div id="tag_description">
		<h2 class="title"><?php echo $tag['Tag']['title']; ?></h2>
		<span class="description"><?php echo $tag['Tag']['description']; ?></span>
		<span class="posts_count"><?php printf(__('Currently, %s posts in this tag.', true), $paginator->params['paging']['Post']['count']); ?> </span>
	</div>
	<!-- div#tag_description ends -->
	<?php 
		echo $this->element('posts', $posts);
		echo $this->element('pagination');
	?>
</div>
<!-- div#primary ends -->
<!-- div#secondary starts -->
<div id="secondary">
	<?php echo $this->element('widgets'); ?>
</div>
<!-- div#secondary ends -->