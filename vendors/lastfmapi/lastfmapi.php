<?php
//set a new include_path
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__FILE__));

// Include helper classes
require_once 'class/apibase.php';
require_once 'class/mainclass.php';
require_once 'class/socket.php';

// Include all files of the API
// TODO: Allow some to be missing
require_once 'api/album.php';
require_once 'api/artist.php';
require_once 'api/auth.php';
require_once 'api/event.php';
require_once 'api/geo.php';
require_once 'api/group.php';
require_once 'api/library.php';
require_once 'api/playlist.php';
require_once 'api/tag.php';
require_once 'api/tasteometer.php';
require_once 'api/track.php';
require_once 'api/user.php';

?>