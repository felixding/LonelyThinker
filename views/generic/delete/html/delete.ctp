<?php
$this->params['modelClass'] = Inflector::singularize(Inflector::humanize($this->params['controller']));
$this->pageTitle = __($this->params['modelClass'], true).Configure::read('pageTitleSeperator').__('Delete', true);
?>
<!-- div#primary starts -->
<div id="primary">
	<!-- div.post starts -->
	<div class="post">
		<h2><?php __('You sure?'); ?></h2>
		<!-- div.entry starts -->
		<div class="entry">
			<?php
			$url = ($this->params['modelClass'] == 'Blacklist') ? '/m-o/blacklist/delete/'.$this->params['id'] : '/'.$this->params['controller'].'/delete/'.$this->params['id'];
			echo $form->create($this->params['modelClass'], array('url'=>$url));
			echo $form->hidden($this->params['modelClass'].'.id', array('value'=>$this->params['id']));
			echo $form->submit(__('Delete', true), array('div'=>false));
			echo $html->link(__('No', true), $referrer);		
			echo $form->end();
			?>
		</div>	
		<!-- div.entry ends -->
	</div>
	<!-- div.post ends -->
</div>
<!-- div#primary ends -->