<!-- div#primary starts -->
<div id="primary">
	<!-- div.post starts -->
	<div class="post">
		<h2><?php __('Are you sure?'); ?></h2>
		<!-- div.entry starts -->
		<div class="entry">
			<p><?php printf(__("Do you REALLY want to empty %s? This can't be un-done.", true), __(ucfirst($status), true)); ?></p>
			<?php
			echo $form->create(null, array('action'=>$status.'/empty/confirm', 'method'=>'post', 'class'=>'dialog', 'id'=>'EmptyFolder'));
			echo $form->submit(sprintf(__('Empty %s', true), __(ucfirst($status), true)), array('div'=>false));
			echo $html->link(__('No', true), $referrer, array('class'=>'no'));
			echo $form->end();			
			?>
		</div>	
		<!-- div.entry ends -->
	</div>
	<!-- div.post ends -->
</div>
<!-- div#primary ends -->