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
			
			$this->client_id = "1086161628880.apps.googleusercontent.com";
			$this->client_secret = "WBfNSAgCMnIHiSMd3IJ1rGeg";
			$this->scope = "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email";
			$this->url_authorize = "https://accounts.google.com/o/oauth2/auth";
			$this->url_access_token = "https://accounts.google.com/o/oauth2/token";
			$this->redirect_uri = "http://127.0.0.1/wp3.5/wp-admin/admin-ajax.php?action=api_con_mngr";

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
	}
endif;
$oauth2 = new Google_API();