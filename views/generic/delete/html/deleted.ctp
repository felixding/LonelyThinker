<?php
$this->params['modelClass'] = Inflector::singularize(Inflector::humanize($this->params['controller']));
$this->pageTitle = __($this->params['modelClass'], true).Configure::read('pageTitleSeperator').__('Delete', true);
$goBackTo = ($this->params['modelClass'] == 'Blacklist') ? '/m-o/blacklist' : '/'.$this->params['controller'];
?>
<meta http-equiv="refresh" content="3;url=<?php echo Router::url($goBackTo); ?>" />
<!-- div#primary starts -->
<div id="primary">
	<!-- div.post starts -->
	<div class="post">
		<h2><?php __('Deleted!'); ?></h2>
		<!-- div.entry starts -->
		<div class="entry">
			<p><?php __('Going back...'); ?></p>
		</div>	
		<!-- div.entry ends -->
	</div>
	<!-- div.post ends -->
</div>
<!-- div#primary ends -->