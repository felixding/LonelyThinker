<?php
$tweets = $this->requestAction('/tweets/index');
if(isset($tweets) && !empty($tweets) && count($tweets)) :
?>
<!-- div#twitter starts -->
<div id="twitter" class="panel">
	<h2><?php echo $html->link('Twitter', 'http://twitter.com/'.Configure::read('LT.twitterUsername'), array('target'=>'_blank'));?></a></h2>
	<ul class="tweets">
		<?php
			foreach($tweets as $tweet)
				echo '<li>'.$tweet['text']['#text'].'<span class="created">'.$html->link(time_since(strtotime($tweet['created_at']['#text'])), 'http://twitter.com/' . $tweet['user']['screen_name']['#text'] . '/statuses/' . $tweet['id']['#text']).'</span></li>';
		?>												
	</ul>
</div>
<!-- div#twitter ends -->
<?php endif;?>