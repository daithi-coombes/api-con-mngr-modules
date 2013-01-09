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
			parse_str($str, $tokens);
			$this->set_params($tokens);
			
			return;
			/**
			$ch = curl_init($url->to_url());//$url->get_normalized_http_url());
			curl_setopt_array($ch, array(
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $params,
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_VERBOSE        => true,
				CURLOPT_HEADER         => true,
				CURLINFO_HEADER_OUT    => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => false,
			));
			
			$res = curl_exec($ch);
			ar_print($res);
			 * 
			 */
			
			/**
			require_once('Dropbox/Dropbox/API.php');
			require_once('Dropbox/Dropbox/Exception.php');
			require_once('Dropbox/Dropbox/OAuth/Consumer/ConsumerAbstract.php');
			require_once('Dropbox/Dropbox/OAuth/Consumer/Curl.php');
			require_once('Dropbox/Dropbox/OAuth/Storage/StorageInterface.php');
			require_once('Dropbox/Dropbox/OAuth/Storage/Session.php');
			$storage = new Dropbox\OAuth\Storage\Session();
			$curl = new Dropbox\OAuth\Consumer\Curl( 
				$this->consumer_key,
				$this->consumer_secret,
				$storage
			);
			 * 
			 */
			
			/**
			//sign request
			$request = $this->request($this->url_access_token, 'GET', array(
				'oauth_verifier' => $oauth_verifier
					));
			ar_print($request);

			//this call will return user_id
			$token = new OAuthConsumer($this->oauth_token, $this->oauth_token_secret);
			ar_print($token);
			/**
			  $request = $this->request( $this->url_access_token, 'POST', array(
			  'oauth_consumer_key' => $this->consumer_key,
			  'oauth_token' => $dto->response['oauth_token'],
			  'oauth_token_secret' => $this->oauth_token_secret
			  ));
			 * 
			 *
			//save params
			$token = OAuthUtil::parse_parameters($request['body']);
			$this->set_params($token);
			 * 
			 */
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