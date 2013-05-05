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
			$this->sha1_method = false;
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
		 * Override to add access token as param on post url.
		 * @param array $response
		 * @return array 
		 */
		function get_access_token( array $response ){
			$this->token = $response['oauth_token'];
			$this->log("getting access tokens");
			$this->log($this);
			$res = $this->request( $this->url_access_token, "POST", array(
				'oauth_token_secret' => $this->oauth_token_secret,
				'oauth_token' => $this->oauth_token
			), false);
			$tokens = (array) $this->parse_response($res);
			$this->set_params($tokens);
			$this->log($tokens);
			//die();
			return $tokens;
		}
		
		/**
		 * Override get_authorize_url to add the callback parameter
		 * @param array $params
		 * @return string The authorize url 
		 */
		function get_authorize_url($params=array()) {
			$params['oauth_callback'] = $this->callback_url;
			return parent::get_authorize_url($params);
		}
		
		function get_uid(){
			return $this->get_profile()->id;
		}
		
		function get_profile(){
			$res = $this->request(
					"https://api.dropbox.com/1/account/info", "get");
			$body = json_decode($res['body']);
			return (object) array(
				'id' => $body->uid,
				'username' => $body->display_name
					);
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
			
			//sign request
			$this->log($this);
			$method = strtoupper($method);
			$request = $this->oauth_sign_request($uri, $method, $parameters);
			$this->headers = $request->to_header();
			if ($method == 'POST'){
				$url = $request->get_normalized_http_url();		
				$parameters = array_merge($request->get_parameters(), $parameters);
				$this->log("Parameters");
				$this->log($parameters);
			}
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
			
			$this->log("verify dropbox token:");
			$this->log($this);
				$res = $this->request(
						"https://api.dropbox.com/1/account/info",
						"get",
						array(),
						false
				);
				
			//$res = $this->request("https://api.dropbox.com/1/account/info", "POST", null, false);
			$this->log("response:");
			$this->log($res);
			if(is_wp_error($res))
				return false;
			
			return true;
		}
	}

	endif;

$module = new Dropbox_API_Module();