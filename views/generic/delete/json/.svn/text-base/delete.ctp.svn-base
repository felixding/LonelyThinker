<?php
$this->params['modelClass'] = Inflector::singularize(Inflector::humanize($this->params['controller']));
$url = ($this->params['modelClass'] == 'Blacklist') ? '/m-o/blacklist/delete/'.$this->params['id'] : '/'.$this->params['controller'].'/delete/'.$this->params['id'];

$message = '<h2>'.__('You sure?', true).'</h2>';
$message.= $form->create($this->params['modelClass'], array('url'=>$url, 'id'=>$this->params['modelClass'].'DeleteForm', 'class'=>'dialog-delete-form')).$form->hidden($this->params['modelClass'].'.id', array('value'=>$this->params['id'])).$form->submit(__('Delete', true), array('div'=>false)).$html->link(__('No', true), $referrer, array('class'=>'dialog-no')).$form->end();

$json = array(
			'errorCode'=>'0',
			'message'=>$message
			);
			
echo $javascript->object($json);
?>