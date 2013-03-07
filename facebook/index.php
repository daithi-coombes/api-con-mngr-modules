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
	
	function get_uid(){
		$this->log("facebook::get_uid():");
		$res = $this->request("https://graph.facebook.com/me", 'GET');
		$body = json_decode($res['body']);
		return $body->id;
	}
		
	function check_error(array $response) {
		return false;
	}

	function request($url, $method='GET', $parameters = array(), $die=true){
		
		if(strtolower($method)=='get')
			$parameters['access_token'] = $this->access_token;
		
		return parent::request($url, $method, $parameters, $die);
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
