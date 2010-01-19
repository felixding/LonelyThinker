<?php
//init
$conf = array();
$conf['currentPageId'] = 4;
$conf['allPagesCount'] = 4;
$conf['nextPageId'] = $conf['currentPageId'] + 1;
$conf['referrer'] = Router::url($referrer);

echo $this->element('psy', array('conf'=>$conf, 'answers'=>$this->data['Page']));

$psyAge = 0;

foreach($_COOKIE as $answerId=>$answerValue)
{
	if(!ereg('psyAnswer', $answerId)) continue;
	$psyAge = $answerValue + $psyAge;	
}

echo '<p>经过科学周密严谨冷静翔实仔细反复地计算，你的心理年龄是：</p><div style="padding:1em;margin:1em;"><p><strong style="color:red;font-size:50px;">'.$psyAge.'岁</strong></p></div>';
echo '<p>'.$html->link('再来一次。', Router::url('/pages/psy/page1?reset=true', true)).'</p>';
?>