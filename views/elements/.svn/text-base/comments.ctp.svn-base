<!-- div#comments starts -->
<div id="comments">
<?php
	//get comments data
	$commentCount = count($comments);
	if($commentCount) printf('<h2>'.__('<span>%s</span> comments so far', true).'</h2>', $commentCount);
	else echo '<h2>'.__('Anything to say? Come on!', true).'</h2>';
?>
<ol>
	<?php			
		//main	
		foreach ($comments as $comment):
			if($comment['Comment']['status'] != 'published') continue;
	?>
	<li id="comment-<?php echo $comment['Comment']['id']; ?>">
		<?php 
			$email = md5($comment['Comment']['email']);
			$defaultAvatar = Configure::read('LT.siteUrl').'/img/avatars/default.jpg';
			$gravatarUrl = "http://www.gravatar.com/avatar.php?gravatar_id=".$email."&amp;default=".$defaultAvatar;

			echo $html->link($gravatar->imgTag(array('email'=>$comment['Comment']['email'], 'default'=>$defaultAvatar), 'avatar'), 'http://www.gravatar.com', array('target'=>'_blank', 'class'=>'gravatar'), false, false);			
		 ?>	
		<span class="entry">
			<span class="username">
				<?php
					if(isset($comment['Comment']['website']) && !empty($comment['Comment']['website']))
					{
						if(!ereg('http', $comment['Comment']['website'])) $comment['Comment']['website'] = 'http://'.$comment['Comment']['website'];
						echo $html->link($comment['Comment']['name'], $comment['Comment']['website'], array('target'=>'_blank', 'rel'=>'nofollow'));
					}
					else
					{
						echo $comment['Comment']['name'];
					}
				?>
			</span>
			<span class="created"><?php echo $comment['Comment']['created']; ?></span>
			<?php
				if($othAuth->sessionValid())
				{
					echo '<span class="actions">';
				    echo $html->link(__('Edit', true), '/comments/edit/' . $comment['Comment']['id']);
				    echo '</span>';  
				}            
			?>	
			<span class="message"><?php echo($widget->nl2p($widget->emoticon(htmlspecialchars($comment['Comment']['body'])))); ?></span>
		</span>
	</li>
	<?php endforeach; ?>	
</ol>
</div>
<!-- div#comments ends -->