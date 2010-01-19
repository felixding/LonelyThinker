<?php
$this->params['modelClass'] = Inflector::singularize(Inflector::humanize($this->params['controller']));

$message = __('Deleted!', true);

$json = array(
			'errorCode'=>'0',
			'message'=>$message
			);
			
echo $javascript->object($json);
?>