<?php
$this->pageTitle = __('M-O', true).Configure::read('pageTitleSeperator').__('Blacklist', true);
?>
<!-- div#primary starts -->
<div id="primary">
	<!-- div.post starts -->
	<div class="post">
		<h2><?php __('Record exists!'); ?></h2>
		<!-- div.entry starts -->
		<div class="entry">
			<p><?php __('A record with same Field and Pattern exists, you can'); ?></p>
			<ul>
			<?php
				echo '<li>'.$html->link(__('Check the existing record', true), '/m-o/blacklist/edit/'.$existingBlacklist['Blacklist']['id']).'</li>';
				echo '<li>'.$html->link(__('Return', true), '/m-o/blacklist').'</li>';
			?>
			</ul>			
		</div>	
		<!-- div.entry ends -->
	</div>
	<!-- div.post ends -->
</div>
<!-- div#primary ends -->