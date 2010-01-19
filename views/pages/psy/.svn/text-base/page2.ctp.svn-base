<?php
//init
$conf = array();
$conf['currentPageId'] = 2;
$conf['allPagesCount'] = 4;
$conf['nextPageId'] = $conf['currentPageId'] + 1;
$conf['referrer'] = Router::url($referrer);

$radios = array(
			11=>array('text'=>'知道‘色即是空’的意思', 'yes'=>1, 'no'=>0, 'neither'=>0),
			array('text'=>'常会什么都不想就行动', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'旅行或什么大事的前一晚会睡不着', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'不知道最近年轻人流行什么', 'yes'=>4, 'no'=>0, 'neither'=>2),
			array('text'=>'有梦想', 'yes'=>1, 'no'=>4, 'neither'=>2),
			array('text'=>'人生至今遇过许多挫折', 'yes'=>3, 'no'=>0, 'neither'=>1),
			array('text'=>'常浪费', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'用报纸打蟑螂是家常便饭', 'yes'=>2, 'no'=>0, 'neither'=>1),
			array('text'=>'没办法一个人住', 'yes'=>0, 'no'=>3, 'neither'=>1),
			array('text'=>'想赶快变成老爷爷/老奶奶', 'yes'=>6, 'no'=>0, 'neither'=>3),
			);			
			
//
echo $this->element('psy', array('conf'=>$conf, 'radios'=>$radios, 'answers'=>$this->data['Page']));
?>