<?php

class lastfmApiBase {
	public $error;
	
	private $socket;
	
	function apiGetCall($vars) {
		$host = 'ws.audioscrobbler.com';
		$port = 80;
		
		$url = '/2.0/?';
		foreach ( $vars as $name => $value ) {
			$url .= trim(urlencode($name)).'='.trim(urlencode($value)).'&';
		}
		$url = substr($url, 0, -1);
		$url = str_replace(' ', '%20', $url);
		
		$this->socket = new lastfmApiSocket($host, $port);
		$out = "GET ".$url." HTTP/1.0\r\n";
   		$out .= "Host: ".$host."\r\n";
   		$out .= "\r\n";
		$response = $this->socket->send($out, 'array');
		
		$xmlstr = '';
		$record = 0;
		foreach ( $response as $line ) {
			if ( $record == 1 ) {
				$xmlstr .= $line;
			}
			elseif( substr($line, 0, 1) == '<' ) {
				$record = 1;
			}
		}
		
		$xml = new SimpleXMLElement($xmlstr);
		
		if ( $xml['status'] == 'ok' ) {
			// All is well :)
			return $xml;
		}
		elseif ( $xml['status'] == 'failed' ) {
			// Woops - error has been returned
			$this->handleError($xml->error);
			return FALSE;
		}
		else {
			// I put this in just in case but this really shouldn't happen. Pays to be safe
			$this->handleError();
			return FALSE;
		}
	}
	
	function apiPostCall($vars) {
		$host = 'ws.audioscrobbler.com';
		$port = 80;
		
		$url = '/2.0/';
		
		$data = '';
		foreach ( $vars as $name => $value ) {
			$data .= trim($name).'='.trim($value).'&';
		}
		$data = substr($data, 0, -1);
		$data = str_replace(' ', '%20', $data);
		
		$this->socket = new lastfmApiSocket($host, $port);
		
		$out = "POST ".$url." HTTP/1.1\r\n";
   		$out .= "Host: ".$host."\r\n";
   		$out .= "Content-Length: ".strlen($data)."\r\n";
   		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
   		$out .= "\r\n";
   		$out .= $data."\r\n";
		
		$response = $this->socket->send($out, 'array');
		
		$xlstr = '';
		$record = 0;
		foreach ( $response as $line ) {
			if ( $record == 1 ) {
				$xmlstr .= $line;
			}
			elseif( substr($line, 0, 1) == '<' ) {
				$record = 1;
			}
		}
		
		$xml = new SimpleXMLElement($xmlstr);
		
		if ( $xml['status'] == 'ok' ) {
			// All is well :)
			return TRUE;
		}
		elseif ( $xml['status'] == 'failed' ) {
			// Woops - error has been returned
			$this->handleError($xml->error);
			return FALSE;
		}
		else {
			// I put this in just in case but this really shouldn't happen. Pays to be safe
			$this->handleError();
			return FALSE;
		}
	}
	
	function handleError($error = '', $customDesc = '') {
		if ( !empty($error) && is_object($error) ) {
			// Fail with error code
			$this->error['code'] = $error['code'];
			$this->error['desc'] = $error;
		}
		elseif( !empty($error) && is_numeric($error) ) {
			// Fail with custom error code
			$this->error['code'] = $error;
			$this->error['desc'] = $customDesc;
		}
		else {
			//Hard failure
			$this->error['code'] = 0;
			$this->error['desc'] = 'Unknown error';
		}
	}
	
	function apiSig($secret, $vars) {
		ksort($vars);
		
		$sig = '';
		foreach ( $vars as $name => $value ) {
			$sig .= $name.$value;
		}
		$sig .= $secret;
		$sig = md5($sig);
		
		return $sig;
	}
}

?>