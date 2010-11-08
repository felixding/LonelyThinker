<?php
echo $rss->items($latestPosts, 'transformRSS');

function transformRSS($data) {
        return array(
                'title' => $data['Post']['title'],
                'link'  => '/'.$data['Post']['slug'],
                'guid'  => '/'.$data['Post']['slug'],
                'description' => $data['Post']['content'],
                'author' => $data['User']['email'],
                'pubDate' => $data['Post']['modified']
        );
}
?>