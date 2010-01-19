<?php

class lastfmApiAlbum extends lastfmApiBase {
	public $info;
	public $tags;
	
	private $auth;
	private $fullAuth;
	
	function __construct($auth, $fullAuth) {
		$this->auth = $auth;
		$this->fullAuth = $fullAuth;
	}
	
	public function addTags($methodVars) {
		// Only allow full authed calls
		if ( $this->fullAuth == TRUE ) {
			// Check for required variables
			if ( !empty($methodVars['album']) && !empty($methodVars['artist']) && !empty($methodVars['tags']) ) {
				// If the tags variables is an array build a CS list
				if ( is_array($methodVars['tags']) ) {
					$tags = '';
					foreach ( $methodVars['tags'] as $tag ) {
						$tags .= $tag.',';
					}
					$tags = substr($tags, 0, -1);
				}
				else {
					$tags = $methodVars['tags'];
				}
				
				// Set the call variables
				$vars = array(
					'method' => 'album.addtags',
					'api_key' => $this->auth->apiKey,
					'album' => $methodVars['album'],
					'artist' => $methodVars['artist'],
					'tags' => $tags,
					'sk' => $this->auth->sessionKey
				);
				// Generate a call signiture
				$sig = $this->apiSig($this->auth->secret, $vars);
				$vars['api_sig'] = $sig;
				
				// Do the call and check for errors
				if ( $call = $this->apiPostCall($vars) ) {
					// If none return true
					return TRUE;
				}
				else {
					// If there is return false
					return FALSE;
				}
			}
			else {
				// Give a 91 error if incorrect variables are used
				$this->handleError(91, 'You must include album, artist and tags varialbes in the call for this method');
				return FALSE;
			}
		}
		else {
			// Give a 92 error if not fully authed
			$this->handleError(92, 'Method requires full auth. Call auth.getSession using lastfmApiAuth class');
			return FALSE;
		}
	}
	
	public function getInfo($methodVars) {
		// Set the call variables
		$vars = array(
			'method' => 'album.getinfo',
			'api_key' => $this->auth->apiKey,
			'album' => @$methodVars['album'],
			'artist' => @$methodVars['artist'],
			'mbid' => @$methodVars['mbid']
		);
		
		if ( $call = $this->apiGetCall($vars) ) {
			$this->info['name'] = (string) $call->album->name;
			$this->info['artist'] = (string) $call->album->artist;
			$this->info['lastfmid'] = (string) $call->album->id;
			$this->info['mbid'] = (string) $call->album->mbid;
			$this->info['url'] = (string) $call->album->url;
			$this->info['releasedate'] = strtotime(trim((string) $call->album->releasedate));
			$this->info['image']['small'] = (string) $call->album->image;
			$this->info['image']['medium'] = (string) $call->album->image[1];
			$this->info['image']['large'] = (string) $call->album->image[2];
			$this->info['listeners'] = (string) $call->album->listeners;
			$this->info['playcount'] = (string) $call->album->playcount;
			$i = 0;
			foreach ( $call->album->toptags->tag as $tags ) {
				$this->info['toptags'][$i]['name'] = (string) $tags->name;
				$this->info['toptags'][$i]['url'] = (string) $tags->url;
				$i++;
			}
			
			return $this->info;
		}
		else {
			return FALSE;
		}
	}
	
	public function getTags($methodVars) {
		// Only allow full authed calls
		if ( $this->fullAuth == TRUE ) {
			// Check for required variables
			if ( !empty($methodVars['album']) && !empty($methodVars['artist']) ) {
				// Set the variables
				$vars = array(
					'method' => 'album.gettags',
					'api_key' => $this->auth->apiKey,
					'sk' => $this->auth->sessionKey,
					'album' => $methodVars['album'],
					'artist' => $methodVars['artist']
				);
				// Generate a call signiture
				$sig = $this->apiSig($this->auth->secret, $vars);
				$vars['api_sig'] = $sig;
				
				// Make the call
				if ( $call = $this->apiGetCall($vars) ) {
					if ( count($call->tags->tag) > 0 ) {
						$i = 0;
						foreach ( $call->tags->tag as $tag ) {
							$this->tags[$i]['name'] = (string) $tag->name;
							$this->tags[$i]['url'] = (string) $tag->url;
							$i++;
						}
						
						return $this->tags;
					}
					else {
						$this->handleError(90, 'User has no tags for this artist');
						return FALSE;
					}
				}
				else {
					return FALSE;
				}
			}
			else {
				// Give a 91 error if incorrect variables are used
				$this->handleError(91, 'You must include album and artist varialbes in the call for this method');
				return FALSE;
			}
		}
		else {
			// Give a 92 error if not fully authed
			$this->handleError(92, 'Method requires full auth. Call auth.getSession using lastfmApiAuth class');
			return FALSE;
		}
	}
	
	public function removeTag($methodVars) {
		// Only allow full authed calls
		if ( $this->fullAuth == TRUE ) {
			// Check for required variables
			if ( !empty($methodVars['album']) && !empty($methodVars['artist']) && !empty($methodVars['tag']) ) {
				// Set the variables
				$vars = array(
					'method' => 'album.removetag',
					'api_key' => $this->auth->apiKey,
					'album' => $methodVars['album'],
					'artist' => $methodVars['artist'],
					'tag' => $methodVars['tag'],
					'sk' => $this->auth->sessionKey
				);
				// Generate a call signature
				$sig = $this->apiSig($this->auth->secret, $vars);
				$vars['api_sig'] = $sig;
				
				// Do the call
				if ( $call = $this->apiPostCall($vars) ) {
					return TRUE;
				}
				else {
					return FALSE;
				}
			}
			else {
				// Give a 91 error if incorrect variables are used
				$this->handleError(91, 'You must include album, artist and tag varialbes in the call for this method');
				return FALSE;
			}
		}
		else {
			// Give a 92 error if not fully authed
			$this->handleError(92, 'Method requires full auth. Call auth.getSession using lastfmApiAuth class');
			return FALSE;
		}
	}
}

?>