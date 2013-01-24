<?php
/*
  Plugin Name: Google API
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: API Connection Manager login module for google
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

if(!class_exists("Google_API")):
	class Google_API extends API_Con_Mngr_Module{

		function __construct(){
			
			$this->options = array(
				'scope' => '%s'
			);
			$this->protocol = "oauth2";
			$this->url_authorize = "https://accounts.google.com/o/oauth2/auth";
			$this->url_access_token = "https://accounts.google.com/o/oauth2/token";

			parent::__construct();
		}

		function check_error(array $response) {
			;
		}
		
		/**
		 * Override the authorize url to add scope and redirect_uri params
		 * @return string The authorize url
		 */
		function get_authorize_url(){
			return parent::get_authorize_url(array(
				'scope' => $this->scope,
				'redirect_uri' => $this->redirect_uri
			));
		}

		function get_uid(){}
		
		/**
		 * Verify token
		 * @return boolean 
		 */
		function verify_token(){
			return true;
		}
	}
endif;
$module = new Google_API();