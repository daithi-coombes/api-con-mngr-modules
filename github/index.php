<?php
/*
  Plugin Name: Github Oauth2
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: Github module for API Connection Manager
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

if(!class_exists("GitHub_API")):
	class GitHub_API extends API_Con_Mngr_Module{
	
		function __construct(){
			
			$this->protocol = "oauth2";
			$this->url_authorize = "https://github.com/login/oauth/authorize";
			$this->url_access_token = "https://github.com/login/oauth/access_token";
			
			parent::__construct();
		}
		
		function check_error( array $response ){
			return false;
		}
		
		function get_authorize_url( $params=array() ){
			
			return parent::get_authorize_url(array(
				'scope' => 'repo, user'
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
$module = new GitHub_API();