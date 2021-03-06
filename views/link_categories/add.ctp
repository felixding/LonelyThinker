<?php
$this->pageTitle = __('New Link Category', true);
?>
<!-- div#visitor-sense starts -->
<div id="new-link" class="admin">
	<!-- div.titlebar starts -->
	<div class="titlebar">		
		<?php echo $this->element('titlebars/links'); ?>
	</div>
	<!-- div.titlebar ends -->
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<!-- div.container starts -->
			<div class="container">
				<!-- div.LinkAddForm starts -->
				<div class="LinkAddForm">
				<?php
					//default values
					$titleDefaultValue =  isset($linkCategory) ? $linkCategory['LinkCategory']['title'] : null;
					$id = isset($linkCategory) ? $linkCategory['LinkCategory']['id'] : null;
					
					echo $form->create('LinkCategory', array('action'=>$this->action, 'class'=>'beautified'));
				?>
					<dl>
						<dt><?php echo $form->label('title', __('Title', true));?></dt>
						<dd><?php echo $form->input('title', array('class'=>'text', 'div'=>false, 'label'=>false, 'tabindex'=>1, 'value'=>$titleDefaultValue));?></dd>					
						<dd>							
							<?php 
							echo $form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>2));
							echo $form->hidden('id', array('value'=>$id));
							?>
						</dd>
					</dl>
				<?php
				echo $form->end();
				
				$linkActions = array(array('text'=>__('Return to list', true), 'url'=>'/link_categories', 'class'=>'return'));				
				if(isset($linkCategory) && $linkCategory['LinkCategory']['id'] != 1) $linkActions[] = array('text'=>__('Delete', true), 'url'=>'/link_categories/delete/'.$linkCategory['LinkCategory']['id'], 'class'=>'delete');
				
				echo $widget->linkActions($linkActions);
				?>
				</div>
				<!-- div.LinkAddForm ends -->
			</div>
			<!-- div.container ends -->
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#new-link ends -->