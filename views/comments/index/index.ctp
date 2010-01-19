<?php
$this->pageTitle = __('Comments', true).Configure::read('pageTitleSeperator').__(ucfirst($status), true);
echo $this->element('dialog');
?>
<div id="comments-moderation" class="admin">
	<!-- div.titlebar starts -->
	<div class="titlebar">
		<?php echo $this->element('titlebars/comments'); ?>
	</div>
	<!-- div.titlebar ends -->
	<!-- div.content starts -->
	<div class="content">
		<?php
		if(count($comments)) :
		
		//paginator
		echo $this->element('pagination');
		?>
		<table class="beautified">
			<thead>
				<tr>
					<?php
					//array to sort
					$ths = array(
						array('class'=>'title', 'text'=>'Post Title', 'key'=>'Post.title'),
						array('class'=>'name', 'text'=>'Comment Author', 'key'=>null),
						array('class'=>'body', 'text'=>'Comment Body', 'key'=>null),
						array('class'=>'created', 'text'=>'Created', 'key'=>'Comment.created'),
						array('class'=>'ip', 'text'=>'IP', 'key'=>'Comment.ip'),
						array('class'=>'subscription', 'text'=>'Subscription', 'key'=>'Comment.subscription'),
						array('class'=>'actions', 'text'=>'Actions', 'key'=>null)
					);
					
					foreach($ths as $th)
					{
						//does the page can be sorted by this th?
						if(isset($th['key']))
						{
							//is the page now sorted by this th?
							if(isset($this->params['named']['sort']) && isset($this->params['named']['direction']) && $th['key'] == $this->params['named']['sort']) $th['class'].= ' '.$this->params['named']['direction'];
						
							echo '<th class="'.$th['class'].'">'.$paginator->sort(__($th['text'],true), $th['key']).'</th>';
						}
						else
						{
							echo '<th class="'.$th['class'].'">'.__($th['text'],true).'</th>';
						}
					}
					?>									
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach($comments as $comment)
				{			
					//just for better display
					$trClass = is_int($i / 2) ? 'odd' : 'even';
					$comment['Comment']['website'] = !empty($comment['Comment']['website']) ? $text->autoLinkUrls($comment['Comment']['website']) : '';
					$comment['Comment']['subscription'] = !empty($comment['Comment']['subscription']) ? $widget->displayStatus('on') : $widget->displayStatus('off');
					
					//actions html
					if($status == 'published')
					{
						//show gravatar in /comments/published
						$gravatarHtml = $gravatar->imgTag(array('email'=>$comment['Comment']['email'], 'default'=>Configure::read('LT.siteUrl').'/img/avatars/default.jpg'), 'avatar', $comment['Comment']['name']);
						
						//link style, text and icon
						$actionsHtml = $widget->linkActions(
											array(
												array('url'=>'/comments/edit/'.$comment['Comment']['id'], 'text'=>__('Edit', true), 'class'=>'edit'),
												array('seperation'=>true)
											)
										);
						$actionsHtml.= __('Move to', true);
						$actionsHtml.= $widget->formActions(
											array(
												array('url'=>'/comments/move/'.$comment['Comment']['id'].'/spam', 'text'=>__('Spam', true), 'class'=>'spam'),
												array('url'=>'/comments/move/'.$comment['Comment']['id'].'/trash', 'text'=>__('Trash', true), 'class'=>'trash')
											),
											array('model'=>'Comment')
										);						
					}
					elseif($status == 'spam')
					{
						$gravatarHtml = '';
						
						//only show partial body
						$partialCommentLength = 50;
						$viewPartialCommentLink = (mb_strlen($comment['Comment']['body']) > $partialCommentLength) ? $html->link(__('View', true), '/comments/view/'.$comment['Comment']['id'], array('class'=>'more')) : '';
						App::import('Sanitize');
						$comment['Comment']['body'] = Sanitize::html(mb_substr($comment['Comment']['body'], 0, $partialCommentLength, 'UTF-8'));
						$comment['Comment']['body'].= $viewPartialCommentLink;
						
						//link style, text and icon
						$actionsHtml = $widget->linkActions(
											array(
												array('url'=>'/comments/delete/'.$comment['Comment']['id'], 'text'=>__('Delete', true), 'class'=>'delete'),
												array('seperation'=>true)
											)
										);
						$actionsHtml.= __('Move to', true);										
						$actionsHtml.= $widget->formActions(
											array(
												array('url'=>'/comments/move/'.$comment['Comment']['id'].'/published', 'text'=>__('Published', true), 'class'=>'published')
											),
											array('model'=>'Comment')
										);
					}
					else
					{
						$gravatarHtml = '';
						//link style, text and icon
						$actionsHtml = $widget->linkActions(
											array(
												array('url'=>'/comments/edit/'.$comment['Comment']['id'], 'text'=>__('Edit', true), 'class'=>'edit'),
												array('url'=>'/comments/delete/'.$comment['Comment']['id'], 'text'=>__('Delete', true), 'class'=>'delete'),												
												array('seperation'=>true)
											)
										);
						$actionsHtml.= __('Move to', true);
						$actionsHtml.= $widget->formActions(
											array(
												array('url'=>'/comments/move/'.$comment['Comment']['id'].'/published', 'text'=>__('Published', true), 'class'=>'published'),
												array('url'=>'/comments/move/'.$comment['Comment']['id'].'/spam', 'text'=>__('Spam', true), 'class'=>'spam')
											),
											array('model'=>'Comment')
										);						
					}
					
					$comment['Comment']['website'] = (isset($comment['Comment']['website']) && !empty($comment['Comment']['website'])) ? '<span class="website">'.$comment['Comment']['website'].'</span>' : '';
					
					echo 
					'<tr class="'.$trClass.'">
						<td class="title">'.$html->link($comment['Post']['title'], '/posts/view/'.$comment['Post']['slug'], array('target'=>'_blank')).'</td>	
						<td class="name">'
							.$gravatarHtml
							.'<span class="name">'.$comment['Comment']['name'].'</span>'
							.'<span class="email">'.$text->autoLinkEmails($comment['Comment']['email']).'</span>'
							.$comment['Comment']['website']
						.'</td>
						<td class="body">'.$comment['Comment']['body'].'</td>
						<td class="created">'.$comment['Comment']['created'].'</td>
						<td class="ip">'.$comment['Comment']['ip'].'</td>
						<td class="subscription">'.$comment['Comment']['subscription'].'</td>
						<td class="actions">'.$actionsHtml.'</td>				
					</tr>
					';
					
					$i++;
				}
				?>
				</tbody>		
		</table>
		<?php
		//paginator
		echo $this->element('pagination');
		endif;
		?>		
	</div>
	<!-- div.content ends -->
</div>
<!-- div#comments-moderation ends -->