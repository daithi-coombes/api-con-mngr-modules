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
		
		$this->client_id = "519687751376514";
		$this->client_secret = "9484b0aeb7ad79b89ac46bb1c3ec5515";
		$this->redirect_uri = "http://cityindex.loc/wp3.5/wp-admin/admin-ajax.php?action=api_con_mngr";
		$this->scope = "email";
		$this->url_authorize = "https://www.facebook.com/dialog/oauth";
		$this->url_access_token = "https://graph.facebook.com/oauth/access_token";
		
		parent::__construct();
	}
	
	function get_authorize_url(){
		return parent::get_authorize_url(array(
			'scope' => $this->scope,
			'redirect_uri' => $this->redirect_uri
		));
	}
	
	public function check_error(array $response) {
		return false;
	}
}
endif;

$oauth2 = new Facebook_API();
/**
$oauth2 = array(
	'button-text' => 'Login with FaceBook',
	
	//grant options
	'grant-options' => array(
		'client_id' => 'Client ID',
		'scope' => 'Scope'
	),
	
	//grant access variables
	'grant-uri' => 'https://www.facebook.com/dialog/oauth',
	'grant-vars' => array(
		'client_id' => '<!--[--grant-client_id--]-->',
		'redirect_uri' => '<!--[--redirect-uri--]-->',
		'state' => '<!--[--[state]--]-->',
		'scope' => '<!--[--grant-scope--]-->'
	),
	
	//token options
	'token-options' => array(
		'client_secret' => 'Client Secret'
	),
	
	//access token variables
	'token-uri' => 'https://graph.facebook.com/oauth/access_token',
	'token-method' => 'get',
	'token-vars' => array(
		'client_secret' => '<!--[--token-client_secret--]-->',
		'client_id' => '<!--[--grant-client_id--]-->',
		'redirect_uri' => '<!--[--redirect-uri--]-->',
	)
);
 * 
 */