<?php
//load css
$css = array('lonelythinker', 'lonelythinker.admin');

//load js
$js = array('jquery.1.3.2.min', 'jquery.corners.min', 'jquery.blockUI.2.14', 'jquery.ui.core.1.7.2', 'jquery.ui.effects.core.1.7.2', 'jquery.ui.effects.transfer.1.7.2', 'jquery.form.2.21', 'jquery.blockUI.2.14', 'lonelythinker', 'lonelythinker.admin', 'swfobject', 'jquery.simpleslug.0.2');

//version stamp
$stamp = '?'.Configure::read('LT.version');

/*
foreach($css as $c) $css2[] = $c.'.css'.$stamp;
foreach($js as $j) $js2[] = $j.$stamp;

$css = $css2;
$js = $js2;
*/

//display
$html->css($css, null, null, false);

//css hack for ie
echo '<!--[if lte IE 6]>'.$html->css(array('lonelythinker.ie.css'.$stamp)).'<![endif]-->';

$javascript->link($js, false);

echo $asset->scripts_for_layout();
?>