<?php
$this->layout = 'rss/default';
$this->pageTitle = Configure::read('LT.siteName');

foreach($posts as $post)
{
	$item = array(
                'title' => $post['Post']['title'],
                'link'  => '/'.$post['Post']['slug'],
                'guid'  => '/'.$post['Post']['slug'],
                'description' => $geshi->highlight($post['Post']['body']),
                'author' => Configure::read('LT.adminName'),
                'pubDate' => $post['Post']['created']
        );
     
     echo $rss->item(array(), $item);
}
?>