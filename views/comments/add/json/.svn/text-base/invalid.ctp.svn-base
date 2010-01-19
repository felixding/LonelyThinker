<?php
$newInvalidFields = array();

foreach($invalidFields as $rule=>$message)
{
	$rule = 'Comment' . ucfirst($rule);
	$newInvalidFields[$rule] = $message;
}
$json = array(
			'errorCode'=>'1',
			'invalidFields'=>$newInvalidFields
			);
			
echo $javascript->object($json);
/*
print_r('haha:');
print_r($this);*/
?>