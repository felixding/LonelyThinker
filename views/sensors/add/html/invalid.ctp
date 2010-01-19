<!-- div#primary starts -->
<div id="primary">
	<!-- div.post starts -->
	<div class="post">
		<h2><?php __('Please check your input:'); ?></h2>
		<!-- div.entry starts -->
		<div class="entry">
			<ul>
			<?php
			foreach($invalidFields as $field=>$message)
			{
				echo '<li>'.__($message, true).'</li>';
			}
			?>
			</ul>
			<p><?php echo $html->link(__('Return and check', true), $referrer, array('class'=>'return')); ?></p>
		</div>	
		<!-- div.entry ends -->
	</div>
	<!-- div.post ends -->
</div>
<!-- div#primary ends -->