<?php
$this->pageTitle = __('Link Categories', true);
echo $this->element('dialog');
?>
<!-- div#links starts -->
<div id="links" class="admin">
	<!-- div.titlebar starts -->
	<div class="titlebar">		
		<?php echo $this->element('titlebars/links'); ?>
	</div>
	<!-- div.titlebar ends -->
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<?php
			echo $widget->linkActions(
					array(
						array('url'=>'add', 'text'=>__('Add a link category', true), 'class'=>'add')											)
				);

			if(isset($linkCategories) && count($linkCategories)) :
			
			//paginator
			echo $this->element('pagination');
			?>							
			<table class="beautified">
				<thead>
					<tr>
						<?php
						//array to sort
						$ths = array(
							array('class'=>'id', 'text'=>'Id', 'key'=>'LinkCategory.id'),
							array('class'=>'title', 'text'=>'Title', 'key'=>null),
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
				<?php foreach($linkCategories as $linkCategory):?>
					<tr>
						<td class="id"><?php echo $linkCategory['LinkCategory']['id'];?></td>
						<td class="title"><?php echo $linkCategory['LinkCategory']['title'];?></td>
						<td class="actions">
							<ul class="actions">
								<li><?php echo $html->link(__('Edit', true), Router::url('/link_categories/edit/', true).$linkCategory['LinkCategory']['id'], array('class'=>'edit'));?></li>
								<?php if($linkCategory['LinkCategory']['id'] != 1): ?>
								<li><?php echo $html->link(__('Delete', true), Router::url('/link_categories/delete/', true).$linkCategory['LinkCategory']['id'], array('class'=>'delete'));?></li>
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
<!-- div#links ends -->