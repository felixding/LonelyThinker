<?php $this->pageTitle = 'LonelyThinker (LT)更新日志'; ?>
<!-- div#primary starts -->
<div id="primary">
	<!-- div.post starts -->
	<div class="post">
		<h2>LonelyThinker (LT)更新日志</h2>
		<dl id="changelogs">
		<?php
foreach(array_reverse(Configure::read('LT.changelogs')) as $changelog)
			{
				echo '<dt><a name="'.$changelog['number'].'"></a>'.$changelog['number'].'</dt>';
				foreach($changelog['log'] as $log)
				{
					echo '<dd>'.$log.'</dd>';
				}
			}
		?>			
		</dl>
	</div>
	<!-- div.post ends -->
	<!-- div#ad starts -->	
	<?php echo $this->renderElement('ad'); ?>
	<!-- div#ad ends -->	
</div>
<!-- div#primary ends -->
<!-- div#secondary starts -->
<div id="secondary">
	<?php echo $this->renderElement('widgets'); ?>
</div>
<!-- div#secondary ends -->