<?php

/**
* GRAVATAR HELPER CLASS
*/

class GravatarHelper extends AppHelper {


	function imgURL($input) {
		return $this->makeURL($input);	
	}

	function imgTag($input, $class = false, $alt = null) {
		$url = $this->makeURL($input);
		$classHTML = $class != false ? 'class="' . $class . '"' : '';
		$output = '<img src="' . $url . '" '. $classHTML . ' alt="'.$alt.'" />';
		return $output;
	}

	/* Private Function to generate a URL
	 * Takes either an array of options (including email)
	 * or a string with the email address- to use the defaults.
	 */
	
	private function makeURL($input) {
		$baseURL = "http://www.gravatar.com/avatar/";
		
		if(is_string($input)) {
			$URL = $baseURL . md5($input);
			return $URL;
		}
		
		if(is_array($input)) {
			$URL = $baseURL . md5($input['email']) . '/?';
			if(array_key_exists('rating', $input)) {$URL .= 'r=' . $input['rating'] . '&';}
			if(array_key_exists('size', $input)) {$URL .= 's=' . $input['size'] . '&';}
			if(array_key_exists('default', $input)) {$URL .= 'default=' . urlencode($input['default']);}
			
			return $URL;
			
		}
		
	}
	
}
?>