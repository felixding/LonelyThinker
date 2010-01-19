<?php
$this->pageTitle = __('M-O', true).Configure::read('pageTitleSeperator').__('Blacklist', true);
echo $this->element('dialog');
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
				echo $widget->linkActions(
						array(
							array('url'=>'/m-o/blacklist/add', 'text'=>__('Add a keyword', true), 'class'=>'add')
						)
					);
					
				if(count($blacklists)):
				
				//paginator
				echo $this->element('pagination');
				?>							
				<table class="beautified" id="blacklist-fields-patterns">
					<thead>
						<tr>
							<?php
							//array to sort
							$ths = array(
								array('class'=>'id', 'text'=>'Id', 'key'=>'Blacklist.id'),
								array('class'=>'status', 'text'=>'Status', 'key'=>'Blacklist.status'),
								array('class'=>'field', 'text'=>'Field', 'key'=>'Blacklist.field'),
								array('class'=>'pattern', 'text'=>'Pattern', 'key'=>null),
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
					<?php foreach($blacklists as $blacklist):?>
						<tr>
							<td class="id"><?php echo $blacklist['Blacklist']['id'];?></td>
							<td class="status"><?php echo $widget->displayStatus($blacklist['Blacklist']['status']); ?></td>
							<td class="field"><?php echo $blacklist['Blacklist']['field'];?></td>
							<td class="pattern"><?php echo $blacklist['Blacklist']['pattern'];?></td>
							<td class="actions">
								<?php									
								echo $widget->linkActions(
										array(
											array('url'=>'/m-o/blacklist/edit/'.$blacklist['Blacklist']['id'], 'text'=>__('Edit', true), 'class'=>'edit'),
											array('url'=>'/m-o/blacklist/delete/'.$blacklist['Blacklist']['id'], 'text'=>__('Delete', true), 'class'=>'delete'),											
										)
									);									
								?>									
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
			<!-- div#blacklist ends -->
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#m-o ends -->	