<?php $this->pageTitle =  __('Adding a comment', true); ?>
<!-- div#primary starts -->
<div id="primary">
	<!-- div.post starts -->
	<div class="post">
		<h2><?php __('Your comment have been saved for moderation!'); ?></h2>
		<!-- div.entry starts -->
		<div class="entry">
			<p><?php echo $html->link(__('Return to the post', true), $referrer, array('class'=>'return')); ?></p>
		</div>	
		<!-- div.entry ends -->
	</div>
	<!-- div.post ends -->
</div>
<!-- div#primary ends -->

