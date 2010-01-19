<!-- div.panel starts -->
<div id="bulletin-others" class="panel">
	<h2>其它</h2>
	<ul>
		<li>
			<?php
			@ini_set('mbstring.internal_encoding', 'UTF-8');
			$str = '“Apple和它的设计”PDF阅读及下载';
			$strlen = mb_strlen($str);
			$output = '';
			$styles = array('ff8c5b', '4839f1', 'ff201f', 'ff8c5b', '00bb4a', '65641e', '3c8666', '6671f0', '232b5a', 'c21ca2');
			$stylesCount = count($styles);
			
			for($i=0;$i<$strlen;$i++)
			{
				$style = rand(0, $stylesCount-1);
				$style = 'color:#'.$styles[$style];
				$output.= '<span style="'.$style.'">'.mb_substr($str, $i, 1).'</span>';
			}	
			
			echo $html->link($output, 'http://www.slideshare.net/FelixDing/appleanditsdesignv0', array('target'=>'_blank'), false, false);
			?>
		</li>
	</ul>
</div>
<!-- div.panel ends -->