<?php

/**
* AppAuth class
* @author zhzhussupovkz@gmail.com
*/
class AppAuth {

	private static $instance;

	//consumer key
	private $consumer_key;

	//consumer secret
	private $consumer_secret;

	//auth url
	private $auth_url = 'https://api.twitter.com/oauth2/token';

	//app
	private $app = 'My Twitter Web App';

	//bearer token
	private $bearer_token;

	public static function init() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	//constructor
	private function __construct() {

		$config = Config::getParams('oauth');
		$this->consumer_key = $config['consumer_key'];
		$this->consumer_secret = $config['consumer_secret'];
		$this->bearer_token = $this->get_bearer_token();
	}

	/**
	* Encoding consumer key and secret
	* @return 
	*/
	private function encoding() {
		$enc_key = urlencode($this->consumer_key);
		$enc_sec = urlencode($this->consumer_secret);
		$bearer_token = $enc_key.':'.$enc_sec;
		return base64_encode($bearer_token);
	}

	//get curl options
	private function get_curl_options($url, $header, $fields) {
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $fields,
			CURLOPT_SSL_VERIFYPEER => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_VERBOSE => true,
			);
		return $options;
	}

	//get bearer token for application-only auth
	private function get_bearer_token() {
		$enc = $this->encoding();
		$header = array(
			'POST /oauth2/token HTTP/1.1',
			'Host: api.twitter.com',
			'User-Agent: '.$this->app,
			'Authorization: Basic '.$enc,
			'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
			'Content-Length: 29',
		);
		$ch = curl_init();
		$opts  = $this->get_curl_options($this->auth_url, $header, 'grant_type=client_credentials');
		curl_setopt_array($ch, $opts);
		$response = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($response);
		return $result->access_token;
	}

	//get bearer token
	public function bearer_token() {
		return $this->bearer_token;
	}
}
