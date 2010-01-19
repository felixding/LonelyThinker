<?php
$this->pageTitle = __('Edit a Comment', true);

echo $form->create('Comment', array('url'=>array('controller'=>'comments', 'action'=>'edit', $comment['Comment']['id'])));	
echo '<dl>';
echo '<dt>'.$form->label('Comment.name', __('Tell us your name...', true)).'</dt>';
echo '<dd>'.$form->input('Comment.name', array('tabindex'=>1, 'maxlength'=>50, 'value'=>$comment['Comment']['name'], 'class'=>'text', 'label'=>false, 'div'=>false)).'</dd>';
echo '<dt>'.$form->label('Comment.email', __('Tell me your email...', true)).'</dt>';
echo '<dd>'.$form->input('Comment.email', array('tabindex'=>2, 'maxlength'=>50, 'value'=>$comment['Comment']['email'], 'class'=>'text', 'label'=>false, 'div'=>false)).sprintf(__('(Support %s)', true), $html->link('Gravatar', 'http://www.gravatar.com/', array('target'=>'_blank'))).'</dd>';
echo '<dt>'.$form->label('Comment.website', __('Show us your web/blog (optional)...', true)).'</dt>';
echo '<dd>'.$form->input('Comment.website', array('tabindex'=>3, 'maxlength'=>50, 'value'=>$comment['Comment']['website'], 'class'=>'text', 'label'=>false, 'div'=>false)).'</dd>';
echo '<dt>'.$form->label('Comment.body', __('Small words from you, big things to others...', true)).'</dt>';
echo '<dd>'.$this->element('emoticons').$form->input('Comment.body', array('tabindex'=>4, 'cols'=>30, 'rows'=>6, 'class'=>'text', 'label'=>false, 'div'=>false, 'value'=>$comment['Comment']['body'])).'</dd>';
//echo '<dt></dt>';
//echo '<dd>'.$form->input('Comment.subscription', array('type'=>'checkbox', 'tabindex'=>5, 'value'=>$comment['Comment']['subscription'])).$form->label('Comment.subscription', __('Email me for new comments', true)).'</dd>';
echo '<dt>&nbsp;</dt>';
echo '<dd>'
	.$form->submit(__('Done, SHOOOOT!', true), array('tabindex'=>6))
	.$form->hidden('Comment.id', array('value'=>$comment['Comment']['id']))
	.$form->hidden('Comment.post_id', array('value'=>$comment['Post']['id']))
	.$form->hidden('Comment.ip', array('value'=>$comment['Comment']['ip']))
	.'</dd>';
echo '</dl>';
echo $form->end();
?>