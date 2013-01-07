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

		private $twitter_oauth;
	
		/**
		 * construct params
		 */
		function __construct() {
			
			$this->twitter_oauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
			
			//set params
			$this->protocol = 'oauth1';
			$this->use_nonce = false;
			$this->url_authorize = "https://api.twitter.com/oauth/authorize";
			$this->url_request_token = "https://api.twitter.com/oauth/request_token";
			
			/**
			 * @todo change to set_options with array of 'option_label' => 'option_key' pairs 
			 */
			$this->set_params(array(
				'consumer_key' => '3cQ4PtAvBd8DjChpGI0CTg',
				'consumer_secret' => 'eOXwPsDTOWreG3sMKhBDYuFze7qBTBQMT2B1SJnbo',
				'callback_url' => 'http://david-coombes.com/wp-admin/admin-ajax.php?action=api_con_mngr'
			));
			
			//construct parent
			$this->consumer_key = CONSUMER_KEY;
			$this->consumer_secret = CONSUMER_SECRET;
			parent::__construct();
		}

		/**
		 * Get the request token.
		 * 
		 * @return string 
		 */
		function get_request_token(){
			
			//get signed request url
			$request = $this->build_request( $this->url_request_token, 'GET', array(
				'oauth_nonce' => 'thisisthenonce'
			));
			$url = $request->to_url();
			
			//make get request
			$res = $this->request($url, 'GET');
			parse_str($res['body'], $results);
			
			//return token
			return $results['oauth_token'];
		}
		
		/**
		 * Override request method. 
		 * Twitter needs to get 
		 * @param type $uri
		 * @param type $method
		 * @param type $parameters 
		 *
		function request( $uri, $method, $parameters ){
			
		}
		 * 
		 */
		
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
		 */
		function verify_token(){
			
			//if no token set
			if(!$this->token || empty($this->token))
				return false;
			
			return true;
		}
	}

	endif;
$oauth1 = new API_Con_Twitter();
