<?php
//init
$conf = array();
$conf['currentPageId'] = 3;
$conf['allPagesCount'] = 4;
$conf['nextPageId'] = $conf['currentPageId'] + 1;
$conf['referrer'] = Router::url($referrer);

$radios = array(
			21=>array('text'=>'喜欢起哄', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'比起旅行，比较喜欢待在家里', 'yes'=>3, 'no'=>0, 'neither'=>1),
			array('text'=>'能读书，可是不想读', 'yes'=>3, 'no'=>0, 'neither'=>1),
			array('text'=>'人生计划已很完美', 'yes'=>2, 'no'=>0, 'neither'=>1),
			array('text'=>'会突然唱起歌来', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'比起都市，觉得住乡下比较合自己的个性', 'yes'=>2, 'no'=>0, 'neither'=>1),
			array('text'=>'常被人耍', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'情感波动激烈', 'yes'=>0, 'no'=>1, 'neither'=>0),
			array('text'=>'觉得有完结的人生才有意思', 'yes'=>4, 'no'=>0, 'neither'=>2),
			array('text'=>'会把一天当很多天用', 'yes'=>0, 'no'=>2, 'neither'=>1),
			);				
			
//
echo $this->element('psy', array('conf'=>$conf, 'radios'=>$radios, 'answers'=>$this->data['Page']));
?>