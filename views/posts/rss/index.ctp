<?php
echo $rss->items($latestPosts, 'transformRSS');

function transformRSS($data) {
        return array(
                'title' => $data['Post']['title'],
                'link'  => '/posts/view/'.$data['Post']['slug'],
                'guid'  => '/posts/view/'.$data['Post']['slug'],
                'description' => $data['Post']['content'],
                'author' => $data['User']['email'],
                'pubDate' => $data['Post']['modified']
        );
}
?>