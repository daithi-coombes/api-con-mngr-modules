<?php

/*
  Plugin Name: DropBox
  Plugin URI: https://github.com/cityindex/labs.cityindex.com/tree/master/httpdocs/wp-content/plugins/ci-login
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

			//construct parent
			$this->consumer_key = 'rlloodn1vrhfaqa';
			$this->consumer_secret = '3a9u8a8nf1vm3x2';
			$this->callback_url = 'http://david-coombes.com/wp-admin/admin-ajax.php?action=api_con_mngr';
			parent::__construct();

			$this->get_params();
		}

		function build_request($url, $method = 'GET', $params = array()) {

			//token must be stdClass
			$token = new OAuthConsumer($this->oauth_token, $this->oauth_token_secret);

			$request = OAuthRequest::from_consumer_and_token($this->consumer, $token, $method, $url, $params);
			$request->sign_request($this->sha1_method, $this->consumer, $token);

			return $request;
		}

		function check_error(array $response) {

			$body = json_decode($response['body']);
			if (isset($body->error))
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
			$url = $this->build_request($this->url_access_token, "POST", $params);
			
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

			$params['oauth_callback'] = $this->callback_url;
			return parent::get_authorize_url($params);
		}

		/**
		 * Override get_request_token to force a POST request
		 * @return array Returns the tokens 
		 */
		function get_request_token() {
			return parent::get_request_token('GET');
		}

		function request($uri, $method, $parameters = array()) {
			
			//make sure we have the params
			$this->get_params();
			
			//sign request
			$method = strtoupper($method);
			$request = $this->build_request($uri, $method, $parameters);

			if ($method == 'POST')
				$url = $request->get_normalized_http_url();
			else
				$url = $request->to_url();

			//send and return result
			return parent::request($url, $method, $parameters);
		}

	}

	endif;

$oauth1 = new Dropbox_API_Module();