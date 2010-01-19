<?php
//set page title
$this->pageTitle = $post['Post']['title'];

//debug
//die('tp:'.$session->read('TimePoint.'.$post['Post']['id']));

//admin
if($othAuth->sessionValid())
{
    echo $html->link(__('Edit', true), '/posts/edit/' . $post['Post']['id'], array('id'=>'PostEditLink'));
    echo $html->link(__('Delete', true), '/posts/delete/' . $post['Post']['id'], array('id'=>'PostDeleteLink'));
}
?>
<!-- div#primary starts -->
<div id="primary">
	<!-- div.post starts -->
	<div class="post">
		<h2><?php echo $post['Post']['title']; ?></h2>
		<p class="meta">
			<span class="tags_comments">
				<?php foreach($post['Tag'] as $tag): ?>
					<?php echo $html->link($tag['title'], '/tags/view/' . $tag['slug'], array('title'=>sprintf(__('View all posts in %s', true), $tag['title']), 'rel'=>'tag')); ?>
				<?php endforeach; ?>
				 | 
				<?php echo $html->link('<span class="loaded_comment_count">' . count($comments) . '</span>' . __(' comments', true), '/posts/view/' . $post['Post']['slug'] . '#commentForm', array('title'=>__('add a comment', true)), false, false); ?>
			</span>
			<span class="created"><?php echo $post['Post']['created']; ?></span>
		</p>
		<!-- div.entry starts -->
		<div class="entry">
			<?php echo $geshi->highlight($post['Post']['body']); ?>
		</div>	
		<!-- div.entry ends -->
	</div>
	<!-- div.post ends -->
	<?php echo $this->element('related_posts', array('postId'=>$post['Post']['id'])); ?>
	<!-- div#ad starts -->	
	<?php echo $this->element('ad'); ?>
	<!-- div#ad ends -->
	<?php echo $this->element('comments', $comments);?>
	<!-- div#comment_add starts -->
	<div id="comment_add">
		<a name="commentForm"></a>	
		<?php
		echo $form->create('Comment');	
		echo '<dl>';

		if(isset($post['Post']['comment']) && $post['Post']['comment'] == 'on'):
			//get cookies
			list($commentNameFromCookie, $commentEmailFromCookie, $commentWebsiteFromCookie) = $this->requestAction('/posts/getCookies');
			
			echo '<dt>'.$form->label('Comment.name', __('Tell us your name...', true)).'</dt>';
			echo '<dd>'.$form->input('Comment.name', array('tabindex'=>1, 'maxlength'=>50, 'value'=>$commentNameFromCookie, 'class'=>'text', 'label'=>false, 'div'=>false)).'</dd>';
			echo '<dt>'.$form->label('Comment.email', __('Tell me your email...', true)).'</dt>';
			echo '<dd>'.$form->input('Comment.email', array('tabindex'=>2, 'maxlength'=>50, 'value'=>$commentEmailFromCookie, 'class'=>'text', 'label'=>false, 'div'=>false)).sprintf(__('(Support %s)', true), $html->link('Gravatar', 'http://www.gravatar.com/', array('target'=>'_blank'))).'</dd>';
			echo '<dt>'.$form->label('Comment.website', __('Show us your web/blog (optional)...', true)).'</dt>';
			echo '<dd>'.$form->input('Comment.website', array('tabindex'=>3, 'maxlength'=>50, 'value'=>$commentWebsiteFromCookie, 'class'=>'text', 'label'=>false, 'div'=>false)).'</dd>';
			echo '<dt>'.$form->label('Comment.body', __('Small words from you, big things to others...', true)).'</dt>';
			echo '<dd>'.$this->element('emoticons').$form->input('Comment.body', array('tabindex'=>4, 'cols'=>30, 'rows'=>6, 'class'=>'text', 'label'=>false, 'div'=>false)).'</dd>';
			echo '<dt></dt>';
			echo '<dd>'.$form->checkbox('Comment.subscription', array('tabindex'=>5, 'checked'=>'checked')).$form->label('Comment.subscription', __('Email me for new comments', true)).'</dd>';
			echo '<dt></dt>';
			echo '<dd>'.$form->submit(__('Done, SHOOOOT!', true), array('tabindex'=>6)).'</dd>';
			echo '<dt class="hidden">'.__("Please leave the input below *empty* since this is for anti-spam! (You won't see this text with CSS turning on.)", true).'</dt>';
			echo '<dd class="hidden">'.$form->hidden('Comment.antispam', array('value'=>null)).$form->hidden('Comment.post_id', array('value'=>$post['Post']['id'])).'</dd>';			
		else:
			echo '<dt>'.__('Comment closed.', true).'</dt>';
		endif;
		
		echo '</dl>';
		echo $form->end();
		
		?>				
	</div>
	<!-- div#comment_add ends -->
</div>
<!-- div#primary ends -->
<!-- div#secondary starts -->
<div id="secondary">
	<?php echo $this->element('widgets'); ?>
</div>
<!-- div#secondary ends -->