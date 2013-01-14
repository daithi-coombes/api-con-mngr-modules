<?php

/*
  Plugin Name: DropBox
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: Dropbox module
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

if (!class_exists("Dropbox_API_Module")):

	class Dropbox_API_Module extends API_Con_Mngr_Module {

		public $user_id;

		function __construct() {
			//set params
			$this->protocol = 'oauth1';
			$this->use_nonce = false;
			$this->url_access_token = "https://api.dropbox.com/1/oauth/access_token";
			$this->url_authorize = "https://api.dropbox.com/1/oauth/authorize";
			$this->url_request_token = "https://api.dropbox.com/1/oauth/request_token";
			$this->user_id = false;
			
			//add additional options
			$this->options = array(
				'callback_url' => '%s'
			);
			parent::__construct();

			$this->get_params();
		}
		
		function check_error(array $response) {
			if(@$response['body'])
				$body = json_decode($response['body']);
			if(isset($body->error))
				return $this->error($body->error);

			return true;
		}

		/**
		 * Use GET url to make POST request for the access token.
		 * @param stdClass $dto 
		 */
		function do_login($dto) {

			//set params
			$this->set_params(array(
				'user_id' => $dto->response['uid']
			));
			
			//post fields and signed request
			$params = array(
				'oauth_consumer_key' => $this->consumer_key,
				'oauth_token' => $this->oauth_token
			);			
			$url = $this->oauth_sign_request($this->url_access_token, "POST", $params);
			
			//make request
			$res = parent::request($url->to_url(), "POST", $params);
			
			//store params
			$tokens = array();
			parse_str($res['body'], $tokens);
			$tokens['token'] = $tokens['oauth_token'];
			$this->set_params($tokens);
		}

		/**
		 * Override get_authorize_url to add the callback parameter
		 * @param array $params
		 * @return string The authorize url 
		 */
		function get_authorize_url($params) {
			ar_print($this);
			$params['oauth_callback'] = $this->callback_url;
			return parent::get_authorize_url($params);
		}
		
		/**
		 * Override the request method to sign requests.
		 * 
		 * @see API_Con_Mngr_Module::request()
		 * @param string $uri The full endpoint url.
		 * @param string $method Default GET. The http method to user.
		 * @param array $parameters Optional. An array of parameters in key
		 * value pairs
		 * @param boolean $die Default true. Wether to die with a login button
		 * if there's an error.
		 * @return array Returns the response array in the WP_HTTP format. 
		 */
		function request($uri, $method='GET', $parameters = array(), $die=true) {
			
			//make sure we have the params
			$this->get_params();
			
			//sign request
			$method = strtoupper($method);
			$request = $this->oauth_sign_request($uri, $method, $parameters);
			
			if ($method == 'POST')
				$url = $request->get_normalized_http_url();
			else
				$url = $request->to_url();
			//send and return result
			return parent::request($url, $method, $parameters, $die);
		}

		/**
		 * Verify token
		 * @return boolean 
		 */
		function verify_token(){
			
			//if no tokens set
			if(empty($this->oauth_token) || empty($this->oauth_token_secret))
				return false;
			
			$res = $this->request("https://api.dropbox.com/1/account/info", "GET", null, false);
			if(is_wp_error($res))
				return false;
			
			return true;
		}
	}

	endif;

$oauth1 = new Dropbox_API_Module();