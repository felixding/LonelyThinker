<?php
	//init
	echo 'fuck';
	$latestComments = $this->requestAction('/comments/getLatestComments/10');
	if(count($latestComments)) :
?><? echo 'c:'.count($latestComments)?>
<ul class="listview">
	<?php foreach ($latestComments as $latestComment): ?>
	<li>
		<?php 
			$email = md5($latestComment['Comment']['email']);
			$defaultAvatar = urlencode(Configure::read('LT.siteUrl').'/img/avatars/default.jpg');
			$gravatarUrl = "http://www.gravatar.com/avatar.php?gravatar_id=".$email."&amp;default=".$defaultAvatar;
			echo $html->image($gravatarUrl, array('class'=>'avatar'));
		 ?>	
		<div class="entry">
			<span class="username"><?php echo $html->link($latestComment['Comment']['name'], '/posts/view/'.$latestComment['Post']['slug'] . '#comment-' . $latestComment['Comment']['id']); ?></span>
			<span class="created"><?php echo $latestComment['Comment']['created']; ?></span>
			<?php echo($widget->nl2p($widget->emoticon(htmlspecialchars($latestComment['Comment']['body'])))); ?>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>