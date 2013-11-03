<?php

//Config class
class Config {

	//application params
	private static $params = array();

	//set params
	public static function setParams($key, $value) {
		self::$params[$key] = $value;
	}

	//get params
	public static function getParams($key) {
		return self::$params[$key];
	}
}

//configurate
$params = array(
	'consumer_key' => 'YOUR CONSUMER KEY',
	'consumer_secret' => 'YOUR CONSUMER SECRET',
	'callback_url' => 'YOUR APP CALLBACK URL',
	);

Config::setParams('oauth', $params);