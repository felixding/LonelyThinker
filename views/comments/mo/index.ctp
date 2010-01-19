<?php
$this->pageTitle = __('Comments', true);
?>
<!-- div#comments-moderation starts -->
<div id="comments-moderation">
	<!-- div.titlebar starts -->
	<div class="titlebar">
		<h2><?php __('Comments'); ?></h2>
		<ul>
			<li class="published"><?php echo $html->link(sprintf(__('Published (<span>%s</span>)', true), $commentsCountPublished), '/comments/published', array('class'=>$menu->highlight('comments/published')), false, false); ?></li>
			<li class="spam"><?php echo $html->link(sprintf(__('Spam (<span>%s</span>)', true), $commentsCountSpam), '/comments/spam', array('class'=>$menu->highlight('comments/spam')), false, false); ?></li>
			<li class="trash"><?php echo $html->link(sprintf(__('Trash (<span>%s</span>)', true), $commentsCountTrash), '/comments/trash', array('class'=>$menu->highlight('comments/trash')), false, false); ?></li>
			<li class="m-o"><?php echo $html->link(__('M-O', true), '/comments/mo', array('class'=>$menu->highlight('/comments/mo'))); ?></li>															
		</ul>
	</div>
	<!-- div.titlebar ends -->
	<!-- div#mo starts -->
	<div class="content" id="mo">
		<?php echo $html->image('m-o/m-o.png', array('class'=>'m-o', 'alt'=>'M-O')); ?>
		<div class="introduction">
			<h3>嗨，我是M-O，有什么能效劳的吗？</h3>					
			<p>我是LonelyThinker的清洁机器人，我默默地帮你做着评论分类的工作－让正常评论发表，把垃圾评论（SPAM）放到垃圾袋里。</p>
			<p>我是个爱学习的小家伙！有越多的人在你的Blog上发言，我学到的就越多，而我的智力也会随着留言的数量增长。一般来说，我自己默默地学习就好了，这事儿不用你花心思考虑。不过如果万一我犯了错，比如把正常评论当作垃圾评论（或者反过来），只要你把被我弄错的重新分类，我就会记住这事儿，并争取下次不再犯类似的错误。</p>				
			<fieldset id="brain-power">
				<caption>我当前的智力指数：</caption>
				<div id="indicator-container">
					<div id="indicator">
						<div id="indicator-scale">
							<span>28<span>								
						</div>
					</div>				
				</div>
				<span class="description">蓝色的进度条代表我的智力，越往右越高。</span>
			</fieldset>
			<p>有特别不想见到的评论吗？<a href="#">告诉我</a>它们长什么样，我会直接把它们放入垃圾袋。</p>
		</div>
	</div>
	<!-- div#mo ends -->