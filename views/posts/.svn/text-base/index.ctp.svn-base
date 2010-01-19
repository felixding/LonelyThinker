<?php
$this->pageTitle = '';
?>
<!-- div#primary starts -->
<div id="primary">
	<?php
		if($this->params['paging']['Post']['page'] == 1)
		{
			if(Configure::read('LT.bulletin') == 'on') echo $this->element('bulletin');
		}
		else
		{
			echo '<!-- div#tag_description starts -->
				  <div id="tag_description">
				  	<h2 class="title">'.__('Browse all articles in history', true).'</h2>
				  </div>
				  <!-- div#tag_description ends -->';
		}
		
		echo $this->element('posts', $posts);
	?>
	<div class="pagination">
		<?php
			if($this->params['paging']['Post']['pageCount'] > 1 && $this->params['paging']['Post']['page'] == 1) echo $html->link(__('See what happened in the past', true), '/posts/index/page:2');
			else echo $this->element('pagination');
		?>
	</div>	
</div>
<!-- div#primary ends -->
<!-- div#secondary starts -->
<div id="secondary">
	<?php echo $this->element('widgets'); ?>
</div>
<!-- div#secondary ends -->		