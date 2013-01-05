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
			
			/**
			 * @todo change to set_options with array of 'option_label' => 'option_key' pairs 
			 */
			$this->set_params(array(
				'consumer_key' => '3cQ4PtAvBd8DjChpGI0CTg',
				'consumer_secret' => 'eOXwPsDTOWreG3sMKhBDYuFze7qBTBQMT2B1SJnbo',
				'callback_url' => 'http://david-coombes.com/wp-admin/admin-ajax.php?action=api_con_mngr'
			));
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
		 * Override the get_request_method to use the TwitterOauth Class. 
		 */
		function get_authorize_url( $token ){
			return $this->twitter_oauth->getAuthorizeURL($token);
		}
		
		function get_request_token(){
			$res = $this->twitter_oauth->getRequestToken();
			return $res['oauth_token'];
		}
	}

	endif;
$oauth1 = new API_Con_Twitter();
