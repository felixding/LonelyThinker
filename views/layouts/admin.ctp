<?php echo $html->docType('xhtml-trans'); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $html->charset(); ?>
<title>
	<?php
		$title_for_layout = !empty($title_for_layout) ? Configure::read('LT.siteName') . Configure::read('pageTitleSeperator') . $title_for_layout : Configure::read('LT.siteName');
		echo $title_for_layout;
	?>
</title>
<?php echo $this->element('css-javascript-admin'); ?>
</head>
<body>
<!-- div#page starts -->
<div id="page">
	<!-- div.vistor-sense starts -->
	<?php echo $this->element('visitor-sense-admin'); ?>
	<!-- div.vistor-sense ends -->
	<!-- div#header starts -->
	<div id="header">
		<?php echo $this->element('header-admin'); ?>
	</div>
	<!-- div#header ends -->
	<!-- div#wrapper starts -->
	<div id="wrapper">
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
<!-- layout:admin -->
<!-- baseUrl:<?php echo Router::url('/', true);?> -->
</body>
</html>