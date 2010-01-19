<?php
//init
$conf = array();
$conf['currentPageId'] = 1;
$conf['allPagesCount'] = 4;
$conf['nextPageId'] = $conf['currentPageId'] + 1;
$conf['referrer'] = Router::url($referrer);

//reset?
if($_GET['reset'] == 'true')
{
	foreach($_COOKIE as $answerId=>$answerValue)
	{
		if(!ereg('psy', $answerId)) continue;
		
		//destroy cookie
		setcookie($answerId, null);
	}
}

$radios = array(
			1=>array('text'=>'会有‘这是我的底线，绝对不能让步’的想法', 'yes'=>2, 'no'=>0, 'neither'=>1),
			array('text'=>'不能想困难的事情', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'很能忍耐', 'yes'=>2, 'no'=>0, 'neither'=>1),
			array('text'=>'常哭', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'偶尔会被人说有中年人的味道', 'yes'=>7, 'no'=>0, 'neither'=>2),
			array('text'=>'只要有不称心的事马上就会生气', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'比起同年的朋友，和年长的朋友较合得来', 'yes'=>2, 'no'=>0, 'neither'=>1),
			array('text'=>'一个人早上起不来', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'在意服装和发型', 'yes'=>0, 'no'=>3, 'neither'=>1),
			array('text'=>'起来的时候会说‘よっこいしょ’(好像是伸懒腰时所讲的话)', 'yes'=>5, 'no'=>0, 'neither'=>2),
			);
			
//
echo $this->element('psy', array('conf'=>$conf, 'radios'=>$radios, 'answers'=>$this->data['Page']));
?>