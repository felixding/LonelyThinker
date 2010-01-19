<?php
$this->pageTitle = __('M-O', true).Configure::read('pageTitleSeperator').__('Statistics', true);
?>
<!-- div#m-o starts -->
<div id="m-o" class="admin">
	<!-- div.titlebar starts -->
	<div class="titlebar">
		<?php echo $this->element('titlebars/mo'); ?>
	</div>
	<!-- div.titlebar ends -->
	<!-- div#mo starts -->
	<div class="content">
		<div class="introduction">				
			<!-- div#statistics starts -->
			<div id="statistics">
				<!-- div.overall starts -->
				<div class="overall">
				<?php
				echo $flashChart->begin();
				$titleStyles = '{color:#000;font-size:20px;text-align:left;padding:0 0 15px 30px;}';
			    $flashChart->setTitle(__('Comments percentage', true), $titleStyles);
				$data = array(
	    					array('label'=>'Published', 'count'=>$existingCommentsCount['published']),
	    					array('label'=>'Spam', 'count'=>$existingCommentsCount['spam']),
	    					array('label'=>'Trash', 'count'=>$existingCommentsCount['trash']),
	  					);
	
		    	$flashChart->setData($data, '{n}.count', '{n}.label', 'pie');
	    		echo $flashChart->chart('pie', array(), 'pie', 'pie');
	    		echo $flashChart->render(300, 300, 'pie');
	    		?>
				</div>	    		
				<!-- div.overall ends -->
				<!-- div.recent starts -->
				<div class="recent">
				<?php				
			    $flashChart->setTitle(__('Comments received in last 7 days', true), $titleStyles);
			    $flashChart->setData($CommentsCountFromDaysAgo, '{n}.all', '{n}.date', 'bar');
			    echo $flashChart->chart('bar', array(), 'bar', 'bar');
			    echo $flashChart->render(500, 300, 'bar');
				?>
				</div>	    		
				<!-- div.recent ends -->
			</div>			
			<!-- div#statistics ends -->
		</div>
	</div>
	<!-- div#mo ends -->
</div>
<!-- div#m-o ends -->