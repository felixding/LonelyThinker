<?php
$this->pageTitle = __('M-O', true);
?>
<!-- div#m-o starts -->
<div id="m-o" class="admin">
	<!-- div.titlebar starts -->
	<div class="titlebar">
		<?php echo $this->element('titlebars/mo'); ?>
	</div>
	<!-- div.titlebar ends -->
	<!-- div.content starts -->
	<div class="content">	
		<?php echo $html->image('m-o/m-o.png', array('class'=>'m-o', 'alt'=>'M-O')); ?>
		<!-- div.introduction starts -->
		<div class="introduction">
			<h3><?php __("Hi, I'm M-O."); ?></h3>					
			<p><?php __("You can edit my brain here, tell me what kind of comments you don't like. I will throw them to the trash directly."); ?></p>
			<!-- div#blacklist starts -->
			<div id="blacklist">
				<?php
					$fieldDefaultValue = isset($blacklist) ? $blacklist['Blacklist']['field'] : null;
					$patternDefaultValue =  isset($blacklist) ? $blacklist['Blacklist']['pattern'] : null;
					$statusDefaultValue =  isset($blacklist) ? $blacklist['Blacklist']['status'] : 'on';
					$id = isset($blacklist) ? $blacklist['Blacklist']['id'] : null;		
					echo $form->create('Blacklist', array('action'=>$this->action, 'class'=>'beautified'));
				?>
					<dl>
						<dt><?php echo $form->label('Blacklist.field', __('Field', true));?></dt>
						<dd>
							<?php echo $form->select('Blacklist.field', $fieldEnumValues, $fieldDefaultValue, array('tabindex'=>1), null);?>
						</dd>
						<dt><?php echo $form->label('Blacklist.pattern', __('Pattern', true));?></dt>
						<dd><?php
						echo $form->input('Blacklist.pattern', array('label'=>false, 'class'=>'text', 'tabindex'=>2, 'value'=>$patternDefaultValue));
						printf(__('(Support %s)', true), $html->link(__('Regular Expression', true), 'http://en.wikipedia.org/wiki/Regular_expression', array('target'=>'_blank')));
						?></dd>
						<dt></dt>
						<dt><?php __('Status');?></dt>
						<dd>
							<?php
								//translation
								$statusEnumValues2 = array();
								foreach($statusEnumValues as $k=>$v)
								{
									$statusEnumValues2[$k] = __(ucfirst($v), true);
								}
								
								echo $form->radio('Blacklist.status', $statusEnumValues2, array('tabindex'=>3, 'value'=>$statusDefaultValue, 'legend'=>false));
								unset($statusEnumValues2);
							?>
						</dd>					
						<dd>							
							<?php 
							echo $form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>4));
							echo $form->hidden('Blacklist.id', array('value'=>$id));
							?>
						</dd>
					</dl>
				<?php
				echo $form->end();
				
				$linkActions = array(array('text'=>__('Return to list', true), 'url'=>'/m-o/blacklist', 'class'=>'return'));
				if(isset($blacklist)) $linkActions[] = array('text'=>__('Delete', true), 'url'=>'/m-o/blacklist/delete/'.$blacklist['Blacklist']['id'], 'class'=>'delete');
				
				echo $widget->linkActions($linkActions);
				?>									
			</div>
			<!-- div#blacklist ends -->
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#m-o ends -->