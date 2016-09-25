<?php

namespace AsgeirSH\CURLlib;

require_once(__DIR__ . '/CURLInterface.php');

use AsgeirSH\CURLlib\CURLInterface;

class CURL implements CURLInterface {
	
	### The handle to the curler
	private $curl;
	### The URL the request is made to.
	private $url;
	### The port the request is sent to.
	private $port;
	### The METHOD the request uses. 
	## Valid values are GET and POST for now.
	private $method = 'GET';
	### Include headers in output?
	private $headersIncluded = true;
	### The array of headers to use.
	private $headers = array();
	### The user-Agent data
	private $useragent = 'AsgeirSH\CURLlib\CURL';
	### The data to be added in some requests.
	private $data = array();
	### A list of errors that have occurred.
	private $errors = array();

	public $timeout = 12;

	public function __construct($url = null, $method = 'GET') {

		$this->curl = curl_init();
		if(false == $this->curl) {
			throw new Exception('CURLlib: Unable to create the CURL-handle.');
		}

		$this->setURL($url);
		$this->setMethod($method);
	}

	public function setURL($url) {
		$this->url = $url;
		return $this;
	}

	public function setPort($port) {
		$this->port = $port;
		return $this;
	}

	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}

	public function setTimeout($timeout) {
		$this->timeout = $timeout;
		return $this;
	}

	public function addHeader($header) {
		$this->headers[] = $header;
		return $this;
	}

	public function setData(array $data) {
		foreach($data as $key => $value) {
			$this->data[$key] = $value;
		}

		return $this;
	}

	public function setUserAgent($useragent) {
		$this->useragent = $useragent;
		return $this;
	}

	public function hasError() {
		return count($this->errors) > 0;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function close() {
		curl_close($this->curl);
	}

	public function exec() {
		$this->_setup();
		switch ($this->method) {
			case 'POST':
				curl_setopt($this->curl, CURLOPT_POST, true);
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->data);
				break;
			case 'GET':
				curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
				if(!empty($this->data))
					$this->url = $this->url .'?'. http_build_query($this->data);
				break;
			// By default, data is added during 
			default:
				curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->requestType);
				break;
		}

		// Sett URL
		curl_setopt($this->curl, CURLOPT_URL, $this->url);

		// Set extra headers	
		if (!empty($this->headers)) {
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
		}

		// Use custom port
		if(isset($this->port)) {
			curl_setopt($this->curl, CURLOPT_PORT, $this->port);
		}

		// Execute
		#echo 'Executing curl now...'."\n";
		$result = curl_exec($this->curl);
		// Close the connection
		$this->close();

		if($result === false) {
			$this->errors[] = "CURLlib: curl_exec returned false.";
			return false;
		}

		if(true == $this->headersIncluded) {
			$body = $result;
			$attempts = 0;
			// Try to separate headers from body, even if we have to follow a redirect.
			do {
				$attempts++;
				list($header, $body) = explode("\r\n\r\n", $body, 2);	
				/*echo "\n".'Header: '."\n";
				var_dump($header);
				echo "\n".'Body: '."\n";
				var_dump($body);*/
			} while (strpos($header, "HTTP/1.1 100 Continue") !== false && $attempts < 3);
		}
		else 
			$body = $result;

		// Check the status-code of the result


		// Process the result:
		return $this->_analyseBody($body);
	}

	private function _setup() {
		curl_setopt($this->curl, CURLOPT_REFERER, $_SERVER['PHP_SELF']);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->useragent);
		### Includes the headers in the output.
		curl_setopt($this->curl, CURLOPT_HEADER, $this->headersIncluded);
		### So value is returned, not outputted.
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeout);
	}

	private function _analyseBody($result) {
		// Is the result JSON?
		$data = $this->_isJson($result);
		if($data != false)
			return $data;
		// Is the result serialized?
		$data = $this->_isSerialized($result);
		if($data != false) 
			return $data;

		// If the result is neither JSON nor serialized, return the data as-is.
		return $result;
	}

	private function _isJson($result) {
		$decoded = @json_decode($result);
		if(is_object($decoded)) {
			return $decoded;
		}
		return false;
	}

	private function _isSerialized($result) {
		$data = @unserialize($result);
		if ($result === 'b:0;' || $data !== false) {
			return $data;
		}
		return false;
	}
}