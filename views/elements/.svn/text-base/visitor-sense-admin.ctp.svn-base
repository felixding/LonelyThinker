<?php
//the content below is for administrators
if(Configure::read('LT.VisitorSense') == 'disabled')
{
	echo '<div class="vistor-sense">';
	echo '<div class="admin gutter">';
	echo __('Some sensors are disabled when you have logged in.', true);
	if($this->params['controller'] != 'sensors') echo $html->link('Check out VisitorSense', array('controller'=>'sensors'));
	echo '</div>';
	echo '</div>';	
}
?>