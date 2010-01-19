<?php
//load css
$css = array('lonelythinker');

//load jQuery
$js = array('jquery.1.3.2.min', 'jquery.corners.min', 'jquery.form.2.21', 'jquery.blockUI.2.14', 'lonelythinker', 'lonelythinker.ie');

//display
$html->css($css, null, null, false);
$javascript->link($js, false);
echo $asset->scripts_for_layout();

//version stamp
$stamp = '?'.Configure::read('LT.version');

//css hack for ie
echo '<!--[if IE]>'.$html->css(array('lonelythinker.ie.css'.$stamp)).'<![endif]-->';
?>