<?php
$this->pageTitle = __('Profile', true);

echo '<h2>'.__('Profile', true).'</h2>';

if(isset($saved)) echo $widget->message(__('Profile updated!', true));

echo $form->create('User', array('action'=>'profile', 'class'=>'beautified'));
echo '<dl>';
echo '<dt>'.$form->label('name', __('Name', true)).'</dt>';
echo '<dd>'.$form->input('name', array('value'=>$user['User']['name'], 'label'=>false, 'div'=>false, 'class'=>'text', 'tabindex'=>1)).'</dd>';
echo '<dt>'.$form->label('email', __('Email', true)).'</dt>';
echo '<dd>'.$form->input('email', array('value'=>$user['User']['email'], 'label'=>false, 'div'=>false, 'class'=>'text', 'tabindex'=>2)).'</dd>';
echo '<dt>'.$form->label('passwd', __('New password', true)).'</dt>';
echo '<dd>'.$form->input('passwd', array('label'=>false, 'div'=>false, 'class'=>'text', 'tabindex'=>3)).'</dd>';
echo '<dt>'.$form->label('passwd', __('Confirm new password', true)).'</dt>';
echo '<dd>'.$form->input('confirmpassword', array('type'=>'password', 'label'=>false, 'div'=>false, 'class'=>'text', 'tabindex'=>4)).'</dd>';
echo '<dt></dt>';
echo '<dd>'.$form->submit(__('Save', true), array('class'=>'submit', 'tabindex'=>5)).'</dd>';
echo '</dl>';
echo $form->end();
?>