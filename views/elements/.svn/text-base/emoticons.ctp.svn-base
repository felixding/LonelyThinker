<ul id="emoticons">
<?php
//config
$basedir = './img/emoticons';
$set = 'smileys';
$fulldir = $basedir . '/' . $set;

//load emoticons
$dir = opendir($fulldir);
while($file = readdir($dir))
{
	if($file == '.' || $file == '..' || is_dir($file) || $file == 'options.cfg' || (!eregi('gif', $file) && !eregi('jpg', $file) && !eregi('png', $file))) continue;
	echo '<li>' . $html->image('emoticons/' . $set . '/' . $file, array('alt'=>$file)) . '</li>';
}
closedir($dir);
// . '?set=' . $set
?>
</ul>