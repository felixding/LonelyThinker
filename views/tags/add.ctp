<?php
$this->pageTitle = isset($tag) ? __('Edit a Tag', true) : __('New Tag', true);
?>
<!-- div#visitor-sense starts -->
<div id="new-tag" class="admin">
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<h2><?php __($this->pageTitle); ?></h2>
			<!-- div.container starts -->
			<div class="container">
				<!-- div.TagAddForm starts -->
				<div class="TagAddForm">
				<?php
					//default values
					$titleDefaultValue =  isset($tag) ? $tag['Tag']['title'] : null;
					$slugDefaultValue =  isset($tag) ? $tag['Tag']['slug'] : null;					
					$descriptionDefaultValue =  isset($tag) ? $tag['Tag']['description'] : null;
					$id = isset($tag) ? $tag['Tag']['id'] : null;

					echo $form->create('Tag', array('action'=>$this->action, 'class'=>'beautified'));
				?>
					<dl>
						<dt><?php echo $form->label('title', __('Title', true));?></dt>
						<dd><?php echo $form->input('title', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>1, 'value'=>$titleDefaultValue));?></dd>
						<dt><?php echo $form->label('slug', __('Slug', true));?></dt>
						<dd><?php echo $form->input('slug', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>2, 'value'=>$slugDefaultValue));?></dd>
						<dt><?php echo $form->label('description', __('Description', true));?></dt>
						<dd><?php echo $form->input('description', array('div'=>false, 'label'=>false, 'class'=>'text wysiwyg', 'tabindex'=>3, 'value'=>$descriptionDefaultValue, 'rows'=>10, 'cols'=>6));?></dd>
						<dt></dt>
						<dd>							
							<?php 
							echo $form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>4));
							echo $form->hidden('id', array('value'=>$id));
							?>
						</dd>
					</dl>
				<?php
				echo $form->end();
				
				$linkActions = array(array('text'=>__('Return to list', true), 'url'=>'/sensors', 'class'=>'return'));
				if(isset($tag) && $tag['Tag']['id'] != 1) $linkActions[] = array('text'=>__('Delete', true), 'url'=>'/tags/delete/'.$tag['Tag']['id'], 'class'=>'delete');
				
				echo $widget->linkActions($linkActions);
				?>
				</div>
				<!-- div.TagAddForm ends -->
			</div>
			<!-- div.container ends -->
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#new-tag ends -->