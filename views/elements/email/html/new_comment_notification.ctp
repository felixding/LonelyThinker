<?php
	$s = '<a href="'.$postUrl.'" target="_blank"><i>'.$postTitle.'</i></a>';
	if(isset($unsubscribeUrl) && !empty($unsubscribeUrl)) $unsubscribeLink = ' <a href="'.$unsubscribeUrl.'" target="_blank">'.__('(Unsubscribe)', true).'</a>';
	else $unsubscribeLink = '';
	    	
	printf(__("The article '%s' which you have subscribed has a new comment:", true), $s);
	echo '<p class="message" style="background-color: #ccc;padding: 10px;margin: 10px;">'.$commentName.__(':', true).nl2br(htmlspecialchars($commentBody)).'</p>';
	echo '<p class="copyright" style="color: #555;font-size: 12px;">';

	printf(__('You have left a comment to the article and have subscribed the comments%s.', true), $unsubscribeLink);

	echo '<br />';
	echo '<a href="'.Router::url('/', true).'" target="_blank">'.Configure::read('LT.siteName').' | '.Configure::read('LT.siteSlogan').'</a></p>';
?>		