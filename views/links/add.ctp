<?php
$this->pageTitle = __('New Link', true);
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
					$linkCategoryIdDefaultValue =  isset($link) ? $link['Link']['link_category_id'] : null;		
					$titleDefaultValue =  isset($link) ? $link['Link']['title'] : null;
					$urlDefaultValue =  isset($link) ? $link['Link']['url'] : null;					
					$descriptionDefaultValue =  isset($link) ? $link['Link']['description'] : null;
					$id = isset($link) ? $link['Link']['id'] : null;
					
					$linkCategories = Set::extract('{n}.LinkCategory', $this->requestAction('/link_categories/get'));
					$linkCategoryOptions = array();
					foreach($linkCategories as $linkCategory) $linkCategoryOptions[$linkCategory['id']] = $linkCategory['title'];

					echo $form->create('Link', array('action'=>$this->action, 'class'=>'beautified'));
				?>
					<dl>
						<dt><?php echo $form->label('link_category_id', __('Link Category', true));?></dt>
						<dd><?php echo $form->input('link_category_id', array('div'=>false, 'label'=>false, 'tabindex'=>1, 'options'=>$linkCategoryOptions, 'value'=>$linkCategoryIdDefaultValue));?></dd>					
						<dt><?php echo $form->label('title', __('Title', true));?></dt>
						<dd><?php echo $form->input('title', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>2, 'value'=>$titleDefaultValue));?></dd>
						<dt><?php echo $form->label('url', __('URL', true));?></dt>
						<dd><?php echo $form->input('url', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>3, 'value'=>$urlDefaultValue));?></dd>
						<dt><?php echo $form->label('description', __('Description', true));?></dt>
						<dd><?php echo $form->input('description', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>4, 'value'=>$descriptionDefaultValue, 'rows'=>10, 'cols'=>6));?></dd>
						<dt></dt>
						<dd>							
							<?php 
							echo $form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>5));
							echo $form->hidden('id', array('value'=>$id));
							?>
						</dd>
					</dl>
				<?php
				echo $form->end();
				
				$linkActions = array(array('text'=>__('Return to list', true), 'url'=>'/links', 'class'=>'return'));				
				if(isset($link)) $linkActions[] = array('text'=>__('Delete', true), 'url'=>'/links/delete/'.$link['Link']['id'], 'class'=>'delete');
				
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