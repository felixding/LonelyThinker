<?php
$this->pageTitle = __('VisitorSense', true);
?>
<!-- div#visitor-sense starts -->
<div id="visitor-sense" class="admin">
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<h2><?php __('VisitorSense'); ?></h2>					
			<p><?php __('Sense-Act! This is the brand new VisitorSense!'); ?></p>
			<!-- div#VisitorSense starts -->
			<div id="VisitorSense">
				<?php
					//default values
					$triggerDefaultValue =  isset($sensor) ? $sensor['Sensor']['trigger'] : null;
					$actionDefaultValue =  isset($sensor) ? $sensor['Sensor']['action'] : null;
					$triggerOptionDefaultValue =  isset($sensor) ? $sensor['Sensor']['trigger_option'] : null;
					$actionOptionDefaultValue =  isset($sensor) ? $sensor['Sensor']['action_option'] : null;
					$statusDefaultValue =  isset($sensor) ? $sensor['Sensor']['status'] : 'on';
					$id = isset($sensor) ? $sensor['Sensor']['id'] : null;

					echo $form->create('Sensor', array('action'=>$this->action, 'class'=>'beautified'));
				?>
					<dl>			
						<dt><?php echo $form->label('Sensor.trigger', __('Trigger', true));?></dt>
						<dd>
							<?php echo $form->select('Sensor.trigger', $triggers, $triggerDefaultValue, array('tabindex'=>1), null);?>
						</dd>
						<dt><?php echo $form->label('Sensor.trigger_option', __('Trigger Option', true));?></dt>
						<dd><?php echo $form->input('Sensor.trigger_option', array('label'=>false, 'class'=>'text', 'tabindex'=>2, 'value'=>$triggerOptionDefaultValue));?></dd>
						<dt><?php echo $form->label('Sensor.action', __('Action', true));?></dt>
						<dd>
							<?php echo $form->select('Sensor.action', $actions, $actionDefaultValue, array('tabindex'=>3), null);?>
						</dd>
						<dt><?php echo $form->label('Sensor.action_option', __('Action Option', true));?></dt>
						<dd><?php echo $form->input('Sensor.action_option', array('label'=>false, 'class'=>'text', 'tabindex'=>4, 'value'=>$actionOptionDefaultValue));?></dd>				
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
								
								echo $form->radio('Sensor.status', $statusEnumValues2, array('tabindex'=>5, 'value'=>$statusDefaultValue, 'legend'=>false));
								unset($statusEnumValues2);
							?>						
						<dd>							
							<?php 
							echo $form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>6));
							echo $form->hidden('Sensor.id', array('value'=>$id));
							?>
						</dd>
					</dl>
				<?php
				echo $form->end();
				
				$linkActions = array(array('text'=>__('Return to list', true), 'url'=>'/sensors', 'class'=>'return'));
				if(isset($sensor)) $linkActions[] = array('text'=>__('Delete', true), 'url'=>'/sensors/delete/'.$sensor['Sensor']['id'], 'class'=>'delete');
				
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