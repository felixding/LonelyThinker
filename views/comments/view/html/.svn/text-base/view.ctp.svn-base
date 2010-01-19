<h2><?php __('View Comment'); ?></h2>
	<dl>
		<dt class="altrow"><?php __('Id'); ?></dt>
		<dd class="altrow"><?php echo $comment['Comment']['id']; ?></dd>
		<dt><?php __('Post Title'); ?></dt>
		<dd><?php echo $comment['Post']['title']; ?></dd>
		<dt class="altrow"><?php __('Comment Author'); ?></dt>
		<dd class="altrow"><?php echo $comment['Comment']['name']; ?></dd>
		<dt><?php __('Email'); ?></dt>
		<dd><?php echo $comment['Comment']['email']; ?></dd>
		<dt class="altrow"><?php __('Website'); ?></dt>
		<dd class="altrow"><?php echo $comment['Comment']['website']; ?></dd>
		<dt><?php __('IP'); ?></dt>
		<dd><?php echo $comment['Comment']['ip']; ?></dd>
		<dt><?php __('Subscription'); ?></dt>
		<dd><?php echo $comment['Comment']['subscription']; ?></dd>
		<dt><?php __('Created'); ?></dt>
		<dd><?php echo $comment['Comment']['created']; ?></dd>
		<dt class="altrow"><?php __('Modified'); ?></dt>
		<dd class="altrow"><?php echo $comment['Comment']['modified']; ?></dd>
		<dt class="altrow"><?php __('Comment Body'); ?></dt>
		<dd class="altrow">
		<?php 
			App::import('Sanitize');
			echo Sanitize::html($comment['Comment']['body']); 
		?>
		</dd>
		<dt class="altrow"><?php __('Status'); ?></dt>
		<dd class="altrow"><?php echo $comment['Comment']['status']; ?></dd>
		<dt><?php __('Agent'); ?></dt>
		<dd><?php echo $comment['Comment']['agent']; ?></dd>		
	</dl>