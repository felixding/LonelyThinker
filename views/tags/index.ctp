<?php
$this->pageTitle = __('Tags', true);
echo $this->element('dialog');
?>
<!-- div#tags starts -->
<div id="tags" class="admin">
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<h2><?php __('Tags'); ?></h2>
			<?php
			echo $widget->linkActions(
					array(
						array('url'=>'add', 'text'=>__('Add a tag', true), 'class'=>'add')											)
				);

			if(count($tags)) :
			
			//paginator
			echo $this->element('pagination');
			?>							
			<table class="beautified">
				<thead>
					<tr>
						<?php
						//array to sort
						$ths = array(
							array('class'=>'id', 'text'=>'Id', 'key'=>'Tag.id'),
							array('class'=>'title', 'text'=>'Title', 'key'=>null),
							array('class'=>'slug', 'text'=>'Slug (part of the link URL)', 'key'=>null),
							array('class'=>'description', 'text'=>'Description', 'key'=>null),
							array('class'=>'actions', 'text'=>'Actions', 'key'=>null)
						);
						
						foreach($ths as $th)
						{
							//does the page can be sorted by this th?
							if(isset($th['key']))
							{
								//is the page now sorted by this th?
								if(isset($this->params['named']['sort']) && isset($this->params['named']['direction']) && $th['key'] == $this->params['named']['sort']) $th['class'].= ' '.$this->params['named']['direction'];
							
								echo '<th class="'.$th['class'].'">'.$paginator->sort(__($th['text'],true), $th['key']).'</th>';
							}
							else
							{
								echo '<th class="'.$th['class'].'">'.__($th['text'],true).'</th>';
							}
						}
						?>
					</tr>
				</thead>
				<tbody>
				<?php foreach($tags as $tag):?>
					<tr>
						<td class="id"><?php echo $tag['Tag']['id'];?></td>
						<td class="title"><?php echo $tag['Tag']['title'];?></td>
						<td class="slug"><?php echo $tag['Tag']['slug'];?></td>
						<td class="description"><?php echo $tag['Tag']['description'];?></td>
						<td class="actions">
							<ul class="actions">
								<li><?php echo $html->link(__('Edit', true), Router::url('/tags/edit/', true).$tag['Tag']['id'], array('class'=>'edit'));?></li>
								<?php if($tag['Tag']['id'] != 1): ?>
								<li><?php echo $html->link(__('Delete', true), Router::url('/tags/delete/', true).$tag['Tag']['id'], array('class'=>'delete'));?></li>
								<?php endif;?>
							</ul>									
						</td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
			<?php
			//paginator
			echo $this->element('pagination');
			endif;
			?>
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#tags ends -->