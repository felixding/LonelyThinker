<?php foreach($posts as $post): ?>
	<!-- div.post starts -->
	<div class="post">
		<h2><?php echo $html->link($post['Post']['title'], '/posts/view/' . $post['Post']['slug']); ?></h2>
		<p class="meta">
			<span class="tags_comments">
				<?php foreach($post['Tag'] as $tag): ?>
					<?php echo $html->link($tag['title'], '/tags/view/' . $tag['slug'], array('title'=>sprintf(__('View all posts in %s', true), $tag['title']), 'rel'=>'tag')); ?>
				<?php endforeach; ?>
				 | 
				<?php echo $html->link(count($post['Comment']) . __(' comments', true), '/posts/view/' . $post['Post']['slug'] . '#commentForm', array('title'=>__('add a comment', true))); ?>
			</span>
			<span class="created"><?php echo $post['Post']['created']; ?></span>
		</p>
		<!-- div.entry starts -->
		<div class="entry">
			<?php echo $geshi->highlight($post['Post']['body']); ?>
		</div>	
		<!-- div.entry ends -->
	</div>
	<!-- post div ends -->
<?php endforeach; ?>