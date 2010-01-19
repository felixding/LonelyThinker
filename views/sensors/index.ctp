<?php
$this->pageTitle = __('VisitorSense', true);
echo $this->element('dialog');
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
				echo $widget->linkActions(
						array(
							array('url'=>'add', 'text'=>__('Add a sensor', true), 'class'=>'add')								)
					);
				
				if(count($Sensors)) :
				
				//paginator
				echo $this->element('pagination');
				?>							
				<table class="beautified" id="sensors">
					<thead>
						<tr>
							<?php
							//array to sort
							$ths = array(
								array('class'=>'id', 'text'=>'Id', 'key'=>'Sensor.id'),
								array('class'=>'status', 'text'=>'Status', 'key'=>'Sensor.status'),
								array('class'=>'trigger', 'text'=>'Trigger', 'key'=>'Sensor.trigger'),
								array('class'=>'trigger_option', 'text'=>'Trigger Option', 'key'=>null),
								array('class'=>'action', 'text'=>'Action', 'key'=>'Sensor.action'),
								array('class'=>'action_option', 'text'=>'Action Option', 'key'=>null),							
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
					<?php foreach($Sensors as $Sensor):?>
						<tr>
							<td class="id"><?php echo $Sensor['Sensor']['id'];?></td>
							<td class="status"><?php echo $widget->displayStatus($Sensor['Sensor']['status']); ?></td>
							<td class="trigger"><?php echo $Sensor['Sensor']['trigger'];?></td>
							<td class="trigger_option"><?php echo $Sensor['Sensor']['trigger_option'];?></td>
							<td class="action"><?php echo $Sensor['Sensor']['action'];?></td>
							<td class="action_option"><?php echo $Sensor['Sensor']['action_option'];?></td>
							<td class="actions">
								<ul class="actions">
									<li><?php echo $html->link(__('Edit', true), Router::url('/sensors/edit/', true).$Sensor['Sensor']['id'], array('class'=>'edit'));?></li>
									<li><?php echo $html->link(__('Delete', true), Router::url('/sensors/delete/', true).$Sensor['Sensor']['id'], array('class'=>'delete'));?></li>
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
			<!-- div#VisitorSense ends -->
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#visitor-sense ends -->