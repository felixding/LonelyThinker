<?php
$this->pageTitle = __('Login', true);

if(isset($auth_msg)) echo $widget->message(__($auth_msg, true));

echo '<h2>'.__('Login', true).'</h2>';
echo $form->create('User', array('action'=>'login', 'class'=>'beautified')); 
echo '<dl>';
echo '<dt>'.$form->label('username', __('Username', true)).'</dt>';
echo '<dd>'.$form->input('username', array('label'=>false, 'div'=>false, 'class'=>'text', 'tabindex'=>1)).'</dd>';
echo '<dt>'.$form->label('username', __('Password', true)).'</dt>';
echo '<dd>'.$form->input('passwd', array('label'=>false, 'div'=>false, 'class'=>'text', 'tabindex'=>2)).'</dd>';
echo '<dt></dt>';
echo '<dd>'.$form->submit(__('Login', true), array('class'=>'submit', 'tabindex'=>3)).'</dd>';
echo '</dl>';
echo $form->end();
?>