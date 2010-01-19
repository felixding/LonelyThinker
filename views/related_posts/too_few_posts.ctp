<?php
$this->pageTitle = __('SmartRecommendation', true);
?>
<!-- div#smartrecommendation starts -->
<div id="smartrecommendation" class="admin">
	<!-- div.content starts -->
	<div class="content">
		<!-- div.introduction starts -->
		<div class="introduction">
			<?php
				echo '<h2>'.__('Recommend smartly', true).'</h2>';
				echo '<p><strong>'.__("You need at least 2 posts to use this feature, go and post some more!", true).'</strong></p>';
				echo '<p>'.__("SmartRecommendation can recommend similar posts to the readers when they're reading a post.", true).'</p>';
				echo '<p>'.__("The recommendation is very smart, for each post on your blog, it scans all other posts, analyze their content, find the most similar ones, save them to the database and recommend them to the readers.", true).'</p>';
				echo '<p>'.__("To activate the feature, the only thing you need do is to generate the recommendation cache by clicking the button below, and LonelyThinker takes care of the rest.", true).'</p>';
			?>
		</div>
		<!-- div.introduction ends -->
	</div>
	<!-- div.content ends -->
</div>
<!-- div#smartrecommendation ends -->