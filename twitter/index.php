<?php
/*
  Plugin Name: Twitter
  Plugin URI: https://github.com/cityindex/labs.cityindex.com/tree/master/httpdocs/wp-content/plugins/ci-login
  Description: Twitter service module for API Connection Manager
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

/**
 * Code flow for oauth1 is:
 * request_token
 * user_auth
 * access_token 
 */

require_once('twitteroauth/config.php');
require_once('twitteroauth/twitteroauth/twitteroauth.php');

if (!class_exists('API_Con_Twitter')):

	class API_Con_Twitter extends API_Con_Mngr_Module{

		public $user_id="";
	
		/**
		 * construct params
		 */
		function __construct() {
			
			//$this->twitter_oauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
			
			//set params
			$this->protocol = 'oauth1';
			$this->use_nonce = false;
			$this->url_access_token = "https://api.twitter.com/oauth/access_token";
			$this->url_authorize = "https://api.twitter.com/oauth/authorize";
			$this->url_request_token = "https://api.twitter.com/oauth/request_token";
			
			//construct parent
			$this->consumer_key = CONSUMER_KEY;
			$this->consumer_secret = CONSUMER_SECRET;
			$this->callback_url = 'http://david-coombes.com/wp-admin/admin-ajax.php?action=api_con_mngr';
			parent::__construct();
		}

		function check_error( array $response ){
			
			//get response
			$res = json_decode($response['body']);
			$errs = array();
			
			//check http code
			if($response['response']['code'] != '200')
				$errs[] = "{$response['response']['code']}: {$response['response']['message']}";
			
			//check for errors
			if(@$res->errors)
				foreach($res->errors as $err)
					$errs[] = "{$err->code}: {$err->message}";
			
			//if errors return WP_Error
			if(count($errs))
				return $this->error(implode("\n", $errs));
			
			return true;
		}
		
		/**
		 * Get twitter user_id after successfull login
		 * @see API_Con_Mngr_Module::do_login() 
		 */
		function do_login( $dto ){
			
			//this call will return user_id
			$request = $this->request( $this->url_access_token, 'GET', array(
				'oauth_verifier' => $dto->response['oauth_verifier']
			));
			
			//save params
			$token = OAuthUtil::parse_parameters($request['body']);
			$this->set_params( $token );
		}
		
		/**
		 * Get the request token.
		 * 
		 * @return string 
		 */
		function get_request_token(){
			
			//get tokens
			$url = $this->url_request_token;
			$res = $this->request($url, 'GET');
			if(is_wp_error($res)){
				ar_print($res);
				ar_print($url);
				debug_print();
			}
			parse_str($res['body'], $results);
			$this->set_params($results);
			
			//return token
			return $results;
		}
		
		/**
		 * Override request method. 
		 * Twitter needs to send the user_id with every request
		 * @param type $uri
		 * @param type $method
		 * @param type $parameters 
		 */
		function request( $uri, $method, $parameters=array() ){
			
			//sign request
			$method = strtoupper($method);
			$request = $this->build_request( $uri, $method, $parameters);
			$url = $request->to_url();
			
			//send and return result
			return parent::request( $url, $method, $parameters );
		}
		
		/**
		 * Set the header
		 * 
		 * @param API_Con_Mngr_Header $header
		 * @return \API_Con_Mngr_Header 
		 */
		function set_header(API_Con_Mngr_Header $header) {
			return $header;
		}
		
		/**
		 * Verify token
		 * @return boolean 
		 *
		function verify_token(){
			
			//if no token set
			if(!$this->token || empty($this->token))
				return false;
			
			//check current token
			$res = $this->request('http://api.twitter.com/1/account/verify_credentials.format', 'GET');
			
			return true;
		}
		 * 
		 */
	}

	endif;
$oauth1 = new API_Con_Twitter();
