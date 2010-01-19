<?php
$commentbody = '';

//先记录一下目前有多少评论
$latestCommentsCount = count($latestComments);
/*
//按照传递过来的$loadedCommentCount，将$allComments数组在comment_count之前的元素截取掉
$newComments = array_slice($allComments, $loadedCommentCount);
$commentCount = $loadedCommentCount + 1;
*/
//生成评论
foreach($latestComments as $latestComment)
{
	$email = md5($latestComment['Comment']['email']);
	$defaultAvatar = urlencode(Configure::read('LT.siteUrl').'/img/avatars/default.jpg');
	
	$gravatarUrl = "http://www.gravatar.com/avatar.php?gravatar_id=".$email."&amp;default=".$defaultAvatar;
	$latestComment['Comment']['name'] = (isset($latestComment['Comment']['website']) && !empty($latestComment['Comment']['website'])) ? $html->link($latestComment['Comment']['name'], $latestComment['Comment']['website'], array('target'=>'_blank')) : $latestComment['Comment']['name'];
	
	$commentbody.= "<li class=\"commentbody hidden\" id=\"comment-" . $latestComment['Comment']['id'] . "\">" . $html->link($html->image($gravatarUrl, array('class'=>'avatar')), 'http://www.gravatar.com', array('target'=>'_blank', 'class'=>'gravatar'), false, false) . "<span class=\"entry\"><span class=\"username\">" . $latestComment['Comment']['name'] . "</span><span class=\"created\">" . $latestComment['Comment']['created'] . "</span><span class=\"message\">" . $widget->nl2p($widget->emoticon(htmlspecialchars($latestComment['Comment']['body']))) . "</span></span></li>";
	//$this->log($commentbody);
	$commentCount++;
}
//$commentHtml = $this->renderElement('comments');
$json = array(
			'errorCode'=>'0',
			'thisCommentId'=>$thisCommentId,
			'latestCommentsCount'=>$latestCommentsCount,
			'commentbody'=>$commentbody,
			'h2Title'=>sprintf(__('<span>%s</span> comments so far', true), $latestCommentsCount)
			);
echo $javascript->object($json);
?>