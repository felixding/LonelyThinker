<?php
$linkCategories = $this->requestAction('/link_categories/get');
//pr($links);
foreach($linkCategories as $linkCategory) :
?>
<!-- div.link starts -->
<div class="widget link">
	<h2><?php echo $linkCategory['LinkCategory']['title']; ?></h2>
	<ul class="listview">
	<?php
		$links = $linkCategory['Link'];
		
		foreach($links as $link)
		{
			echo '<li><span class="title">' . $html->link($link['title'], $link['url'], array('target'=>'_blank')) . '</span><span class="description">' . $link['description'] . '</span></li>';
		}
		$links = null;
	?>														
	</ul>
</div>
<!-- div.link ends -->
<?php endforeach;?>