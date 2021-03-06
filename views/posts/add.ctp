<?php
$this->pageTitle = ($this->action == 'edit') ? __('Edit a Post', true) : __('New Post', true);
?>
<!-- div#visitor-sense starts -->
<div id="new-post" class="admin">
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<h2><?php __($this->pageTitle); ?></h2>
			<!-- div.container starts -->
			<div class="container">
				<!-- div.PostAddForm starts -->
				<div class="PostAddForm">
				<?php
					//default values
					$titleDefaultValue =  isset($post) ? $post['Post']['title'] : null;
					$slugDefaultValue =  isset($post) ? $post['Post']['slug'] : null;					
					$bodyDefaultValue =  isset($post) ? $post['Post']['body'] : null;
					//$statusesDefaultValue =  isset($post) ? $post['Post']['status'] : null;
					$tagsDefaultValue =  isset($post) ? Set::extract('{n}.id', $post['Tag']) : null;
					//$statusDefaultValue =  isset($post) ? $post['Post']['status'] : 'on';
					$commentDefaultValue =  isset($post) ? $post['Post']['comment'] : 'on';
					$id = isset($post) ? $post['Post']['id'] : null;
					$slugDefaultValue =  isset($post) ? $post['Post']['slug'] : null;
					
					/*
					//localization
					foreach($statuses as $key=>$value)
						$statuses[$key] = __($value, true);
					$statusesDefaultValue = __($statusesDefaultValue, true);
					*/
					
					//saved for drafts
					if(isset($saved) && $saved == true) echo $widget->message(__('Post saved.', true));
					
					$formAction = isset($id) ? $this->action.'/'.$id : $this->action;
					echo $form->create('Post', array('action'=>$formAction, 'class'=>'beautified', 'id'=>'Post'.ucfirst($this->action).'Form'));
				?>
					<dl>
						<dt><?php echo $form->label('title', __('Title', true));?></dt>
						<dd><?php echo $form->input('title', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>1, 'value'=>$titleDefaultValue));?></dd>
						<dt><?php echo $form->label('slug', __('Slug (will be part of the article URL)', true));?></dt>
						<dd><?php echo $form->input('slug', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>2, 'value'=>$slugDefaultValue));?></dd>
						<dd class="slug"><?php echo __('Article URL: ', true).Router::url('/posts/view/', true); ?><span><?php echo $slugDefaultValue; ?></span></dd>
						<dt><?php echo $form->label('body', __('Body', true));?></dt>
						<dd><?php echo $form->input('body', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>3, 'value'=>$bodyDefaultValue, 'rows'=>10, 'cols'=>6));?></dd>
						<?php
						/* hmmm, i don't need the feature at the moment
						<dt><?php echo $form->label('status', __('Status', true));?></dt>
						<dd>
							<?php echo $form->select('status', $statuses, $statusesDefaultValue, array('tabindex'=>4), null);?>
						</dd>
						*/
						?>
						<dt><?php echo $form->label('Tag.Tag', __('Tag', true));?></dt>
						<dd>
							<?php
							//add a 'form-error' class when invalidates
							$tagClass = (isset($form->validationErrors['Post']['tag'])) ? 'mutilple-select form-error' : 'mutilple-select';
							echo $form->select('Tag.Tag', $tags, $tagsDefaultValue, array('tabindex'=>5, 'multiple'=>'multiple', 'class'=>$tagClass), null);
							echo $form->error('tag', __('You forgot to give a tag for the post', true));
							?>
						</dd>
						<dt><?php __('Allow readers to comment?');?></dt>
						<dd>
							<?php
								//translation
								$comment2 = array();
								foreach($comment as $k=>$v)
								{
									$comment2[$k] = __(ucfirst($v), true);
								}
								
								echo $form->radio('comment', $comment2, array('tabindex'=>6, 'value'=>$commentDefaultValue, 'legend'=>false));
								unset($comment2);
							?>
						</dd>
						<dt>&nbsp;</dt>
						<dd>							
							<?php 
							echo $form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>7));
							echo $form->hidden('id', array('value'=>$id));
							?>
						</dd>
					</dl>
				<?php
				echo $form->end();

				if($this->action == 'edit') echo $widget->linkActions(array(array('text'=>__('Delete', true), 'url'=>'/posts/delete/'.$post['Post']['id'], 'class'=>'delete')));
				?>
				</div>
				<!-- div.PostAddForm ends -->
				<?php if(isset($drafts)):?>
				<!-- div.drafts starts -->
				<div class="drafts widget">
					<h2><?php __('Drafts');?></h2>
					<ul class="listview">
					<?php
						foreach($drafts as $draft)
						{
							echo '<li>';
							echo '<span>'.$html->link($draft['Post']['title'], '/posts/edit/'.$draft['Post']['id']).'</span>';
							echo '<span class="created">'.$draft['Post']['created'].'</span>';
							echo '</li>';							
						}
					?>
					</ul>
				</div>
				<!-- div.drafts ends -->
				<?php endif;?>
			</div>
			<!-- div.container ends -->
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#new-post ends -->