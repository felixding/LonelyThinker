<!-- div#bulletin starts -->
<div id="bulletin">
	<div class="gutter">
	    <?php
	    $panels = array('tweets', 'lastfm_recent_tracks');
	    
	    foreach($panels as $panel) echo $this->element('bulletin-panels/'.$panel);
	    ?>
    </div>
</div>
<!-- div#bulletin ends -->