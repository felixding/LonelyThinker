<?php
$lastfmRecentTracks = $this->requestAction('/lastfms/index');
if(isset($lastfmRecentTracks) && !empty($lastfmRecentTracks) && count($lastfmRecentTracks)) :
?>
<!-- div#lastfm starts -->
<div id="lastfm" class="panel">
	<h2><a href="http://www.last.fm/user/FelixDing" target="_blank">last.fm</a></h2>
	<ul>
<?php
	$i = 0;
	foreach($lastfmRecentTracks as $lastfmRecentTrack)
	{
		if($i == 3) break;
		$name = $lastfmRecentTrack['name'] ? $lastfmRecentTrack['name'] : __('Unknown track', true);
		$image = $lastfmRecentTrack['images']['small'] ? $lastfmRecentTrack['images']['small'] : 'disk-cover.png';
		$artist = $lastfmRecentTrack['artist']['name'] ? $lastfmRecentTrack['artist']['name'] : __('Unknown artist', true);
		$album = $lastfmRecentTrack['album']['name'] ? $lastfmRecentTrack['album']['name'] : __('Unknown album', true);
		$date = date('m-d H:i', $lastfmRecentTrack['date']);
		$url = $lastfmRecentTrack['url'];		
		
		echo '<li>'.$html->image($image, array('alt'=>$artist.' - '.$album)).'
			<div class="meta">
				<span class="track">'.$name.'</span>
				<span class="artist">'.$artist.'</span>
				<span class="album">'.$album.'</span>
				<div class="rating four"><span>4</span></div>
			</div>
			</li>';
			
		$i++;
	}
?>				
	</ul>			
</div>
<!-- div#lastfm ends -->
<?php endif;?>