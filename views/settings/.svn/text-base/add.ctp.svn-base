<?php
$this->pageTitle = isset($setting) ? __('Edit a Setting', true) : __('New Setting', true);
?>
<!-- div#visitor-sense starts -->
<div id="new-setting" class="admin">
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<h2><?php __($this->pageTitle); ?></h2>
			<!-- div.container starts -->
			<div class="container">
				<!-- div.SettingAddForm starts -->
				<div class="SettingAddForm">
				<?php
					//default values
					$keyDefaultValue =  isset($setting) ? $setting['Setting']['key'] : null;
					$valueDefaultValue =  isset($setting) ? $setting['Setting']['value'] : null;
					$id = isset($setting) ? $setting['Setting']['id'] : null;

					echo $form->create('Setting', array('action'=>$this->action, 'class'=>'beautified'));
				?>
					<dl>
						<dt><?php echo $form->label('key', __('Key', true));?></dt>
						<dd><?php echo $form->input('key', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>1, 'value'=>$keyDefaultValue));?></dd>
						<dt><?php echo $form->label('value', __('Value', true));?></dt>
						<dd><?php echo $form->input('value', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>2, 'value'=>$valueDefaultValue));?></dd>
						<dt></dt>
						<dd>							
							<?php 
							echo $form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>3));
							echo $form->hidden('id', array('value'=>$id));
							?>
						</dd>
					</dl>
				<?php
				echo $form->end();
				
				$linkActions = array(array('text'=>__('Return to list', true), 'url'=>'/settings', 'class'=>'return'));				
				if(isset($setting)) $linkActions[] = array('text'=>__('Delete', true), 'url'=>'/settings/delete/'.$setting['Setting']['id'], 'class'=>'delete');
				
				echo $widget->linkActions($linkActions);
				?>
				</div>
				<!-- div.SettingAddForm ends -->
			</div>
			<!-- div.container ends -->
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#new-setting ends -->