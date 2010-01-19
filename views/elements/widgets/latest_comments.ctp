<?php //if($this->params['controller'] == 'posts' && $this->params['action'] == 'index'): ?>
<?php
	//init
	$latestComments = $this->requestAction('/comments/getLatestComments/10');
	if(count($latestComments)) :
?>
<!-- div#latest_comments starts -->
<div id="latest_comments" class="widget">
	<h2><?php __('Latest Comments'); ?></h2>
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
</div>
<!-- div#latest_comments ends -->
<?php endif;?>