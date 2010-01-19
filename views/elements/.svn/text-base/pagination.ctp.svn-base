<!-- pagination div starts -->
<div class="pagination">
	<?php
		$paginator->options(array('url' => $this->passedArgs));
		//$paginator->options(array('url'=>$this->params['pass']));
		//Showing Page echo $paginator->counter();
		//$paginator->options(array('tag'=>''));
		echo $paginator->prev(__('<< Previous', true), array('tag'=>''));
		echo str_replace('|', '', $paginator->numbers());
		echo $paginator->next(__('Next >>', true));

		//set page title
		if(isset($paginator->options['url']['page']) && $paginator->options['url']['page'] > 1) $this->pageTitle = $this->pageTitle.Configure::read('pageTitleSeperator').sprintf(__('Page %s', true), $paginator->options['url']['page']);
	?>			
</div>
<!-- pagination div ends -->