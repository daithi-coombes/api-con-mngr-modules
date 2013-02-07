<?php
/*
  Plugin Name: Facebook
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: Facebook login module for API Connection Manager
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

if(!class_exists("Facebook_API")):
class Facebook_API extends API_Con_Mngr_Module{
	
	public function __construct(){
		
		$this->protocol = "oauth2";
		$this->scope = "email";
		$this->url_authorize = "https://www.facebook.com/dialog/oauth";
		$this->url_access_token = "https://graph.facebook.com/oauth/access_token";
		
		parent::__construct();
	}
	
	function get_authorize_url($params=array()){
		return parent::get_authorize_url(array(
			'scope' => $this->scope,
			'redirect_uri' => $this->redirect_uri
		));
	}
	
	function get_uid(){}
		
	public function check_error(array $response) {
		return false;
	}

		/**
		 * Verify token
		 * @return boolean 
		 */
	function verify_token(){
			
			if(empty($this->access_token))
				return false;
			
			return true;
		}
}
endif;

$module = new Facebook_API();
