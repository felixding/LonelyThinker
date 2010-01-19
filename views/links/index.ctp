<?php
$this->pageTitle = __('Links', true);
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
						array('url'=>'add', 'text'=>__('Add a link', true), 'class'=>'add')											)
				);

			if(count($links)) :
			
			//paginator
			echo $this->element('pagination');
			?>							
			<table class="beautified">
				<thead>
					<tr>
						<?php
						//array to sort
						$ths = array(
							array('class'=>'id', 'text'=>'Id', 'key'=>'Link.id'),
							array('class'=>'link_category', 'text'=>'Link Category', 'key'=>null),
							array('class'=>'title', 'text'=>'Title', 'key'=>null),
							array('class'=>'url', 'text'=>'URL', 'key'=>null),
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
				<?php foreach($links as $link):?>
					<tr>
						<td class="id"><?php echo $link['Link']['id'];?></td>
						<td class="link_category"><?php echo $link['LinkCategory']['title'];?></td>
						<td class="title"><?php echo $link['Link']['title'];?></td>
						<td class="url"><?php echo $link['Link']['url'];?></td>
						<td class="description"><?php echo $link['Link']['description'];?></td>
						<td class="actions">
							<ul class="actions">
								<li><?php echo $html->link(__('Edit', true), Router::url('/links/edit/', true).$link['Link']['id'], array('class'=>'edit'));?></li>
								<li><?php echo $html->link(__('Delete', true), Router::url('/links/delete/', true).$link['Link']['id'], array('class'=>'delete'));?></li>
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