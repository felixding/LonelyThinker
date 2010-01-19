<?php echo $html->docType('xhtml-trans'); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $html->charset(); ?>
<title>
	<?php
		$title_for_layout = !empty($title_for_layout) ? Configure::read('LT.siteName') . Configure::read('pageTitleSeperator') . $title_for_layout : Configure::read('LT.siteName') . ' | ' . Configure::read('LT.siteSlogan');
		echo $title_for_layout;
	?>
</title>
<?php echo $this->element('css-javascript'); ?>

	<link title="RSS 2.0" type="application/rss+xml" href="<?php echo Router::url('/feed') ;?>" rel="alternate" />
</head>
<body>
<!-- div#page starts -->
<div id="page">
	<!-- div#header starts -->
	<div id="header">
		<?php echo $this->element('header'); ?>
	</div>
	<!-- div#header ends -->
	<!-- div#wrapper starts -->
	<div id="wrapper">
		<!-- div.vistor-sense starts -->
		<?php
		$visitor_sense_message = isset($visitor_sense_message) ? $visitor_sense_message : null;
		echo $this->element('visitor-sense', array('visitor_sense_message'=>$visitor_sense_message)); ?>
		<!-- div.vistor-sense ends -->
		<?php echo $content_for_layout; ?>
	</div>
	<!-- div#wrapper ends -->	
	<!-- div#footer starts -->
	<div id="footer">
		<?php echo $this->element('footer'); ?>
	</div>
	<!-- div#footer ends -->
</div>
<!-- div#page ends -->
<!-- layout:default -->
</body>
</html>