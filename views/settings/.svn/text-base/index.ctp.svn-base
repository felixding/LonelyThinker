<?php
$this->pageTitle = __('Settings', true);
if(isset($saved) && $saved == true) echo $widget->message(__('Settings updated!', true));
?>
<!-- div#settings starts -->
<div id="settings" class="admin">
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<h2><?php __($this->pageTitle); ?></h2>
			<!-- div.container starts -->
			<div class="container">
				<!-- div.SettingIndexForm starts -->
				<div class="SettingIndexForm">
					<?php
					echo $form->create('Setting', array('action'=>$this->action, 'class'=>'beautified'));
						
					//build up the bulletin radios
					$bulletin = array('on'=>__('On', true), 'off'=>__('Off', true));
					?>				
						<dl>
							<dt><?php echo $form->label('siteName', __('Site Name', true));?></dt>
							<dd><?php echo $form->input('siteName', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>1, 'value'=>Configure::read('LT.siteName')));?></dd>
							<dt><?php echo $form->label('siteSlogan', __('Site Slogan', true));?></dt>
							<dd><?php echo $form->input('siteSlogan', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>2, 'value'=>Configure::read('LT.siteSlogan')));?></dd>
							<dt><?php echo $form->label('mailer', __('Mailer', true));?></dt>
							<dd><?php echo $form->input('mailer', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>3, 'value'=>Configure::read('LT.mailer')));?></dd>
							<dt><?php echo $form->label('bulletin', __('Bulletin', true));?></dt>
							<dd><?php echo $form->radio('bulletin', $bulletin, array('tabindex'=>4, 'legend'=> false, 'value'=>Configure::read('LT.bulletin')));?></dd>	
							<dt><?php echo $form->label('twitterUsername', sprintf(__('%s Username', true), 'Twitter'));?></dt>
							<dd><?php echo $form->input('twitterUsername', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>5, 'value'=>Configure::read('LT.twitterUsername')));?></dd>	
							<dt><?php echo $form->label('lastfmUsername', sprintf(__('%s Username', true), 'last.fm'));?></dt>
							<dd><?php echo $form->input('lastfmUsername', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>6, 'value'=>Configure::read('LT.lastfmUsername')));?></dd>
							<dt><?php echo $form->label('lastfmAPIKey', __('lastfm API Key', true));?></dt>
							<dd><?php echo $form->input('lastfmAPIKey', array('div'=>false, 'label'=>false, 'class'=>'text', 'tabindex'=>7, 'value'=>Configure::read('LT.lastfmAPIKey')));?></dd>
							<dt></dt>
							<dd>							
								<?php 
								echo $form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>8));
								?>
							</dd>
						</dl>
					<?php echo $form->end();?>
				</div>
				<!-- div.SettingIndexForm ends -->
			</div>
			<!-- div.container ends -->
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#settings ends -->