<?php
$this->pageTitle = __('M-O', true).Configure::read('pageTitleSeperator').__('Brainpower', true);
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
		<?php echo $html->image('m-o/m-o.png', array('class'=>'m-o', 'alt'=>'M-O')); ?>
		<div class="introduction">
			<h3><?php __("Hi, I'm M-O."); ?></h3>					
			<p><?php __("I'm the cleaner of LonelyThinker. I help you to classify the comments, let the HAMs (normal comments left by humans) be published and mark the SPAMs (rubbish comments, advertisements etc)."); ?></p>
			<p><?php __("You know what? I can learn by myself! The more people leave comments, the more I learn. My brain power grows as time goes by. Generally speaking, I just learn quietly, you don't need to worry anything. But if I make a mistake, let's say mark a HAM as a SPAM (or vice versa), you just correct me and I can remember and make sure I won't make similar mistakes in the future."); ?></p>				
			<fieldset id="brain-power">
				<span><?php __("My current Brainpower:"); ?></span>
				<div id="indicator-container">
					<div id="indicator" style="width:<?php echo $brainpower;?>%">
						<div id="indicator-scale">
							<span style="padding-left:<?php echo $brainpower;?>%"><?php echo $brainpower;?></span>								
						</div>
					</div>				
				</div>
				<span class="description"><?php __("The blue bar indicates my brain power, longer is higher."); ?></span>
			</fieldset>
			<p><?php printf(__("Any special comments that you don't want to see? %s what they look like, I will trash them as soon as I get them.", true), $html->link(__('Tell me', true), '/m-o/blacklist')); ?></p>
		</div>
	</div>
	<!-- div#mo ends -->
</div>
<!-- div#m-o ends -->