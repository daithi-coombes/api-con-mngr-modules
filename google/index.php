<?php
/*
  Plugin Name: Google API
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: API Connection Manager login module for google
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

/**
 * The callback uri in the service should always be: 
 * www.domain.com/wp-admin/admin-ajax.php?action=autoflow&module={$slug}
 * where {$slug} is the slug name of this module set in the below array.
 * 
 * If a security var is allowed with the oauth service, in your grant-vars array
 * set the var name value as _wpnonce
 * ie:
 * $oauth2['grant-vars']['state'] = '_wpnonce'
 * 
 * @link https://developers.google.com/accounts/docs/OAuth2WebServer
 * 
 * Standard vars
 * <!--[--token--]--> will display the refresh token, or the access token if
 * none
 * <!--[--token-access--]--> use the access token
 * <!--[--token-refresh--]--> use the refresh token
 * <!--[--redirect-uri--]--> use the redirect uri (you can find this in the API-Con service options in the dashboard
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
		
		function request( $url, $method="GET", $params=array()){
			
			return parent::request($url, $method, $params);
		}
	}
endif;
$oauth2 = new Google_API();
/**
$oauth2 = array(
	
	/**
	 * Service Params 
	 *
	'offline' => true,
	'button-text' => 'Login with Google',
	
	/**
	 * Grant Params
	 *
	'grant-uri' => 'https://accounts.google.com/o/oauth2/auth',
	'grant-response-type' => 'json',
	//per blog options
	'grant-options' => array(
		'client_id' => 'ID',
		'scope' => 'Scope'
	),
	//params that will make up grant query
	'grant-vars' => array(
		'client_id' => '<!--[--grant-client_id--]-->',	//use client_id value from grant options
		'redirect_uri' => '<!--[--redirect-uri--]-->',
		'state' => '<!--[--[state]--]-->',
		'response_type' => 'code',
		'approval_prompt' => 'auto'
	),// end Grant Params
	
	/**
	 * Token Params 
	 *
	'token-uri' => 'https://accounts.google.com/o/oauth2/token',
	'token-method' => 'POST',
	'token-datatype' => 'json',
	'token-options' => array(
		'client_secret' => 'Client Secret',
	),
	'token-vars' => array(
		'grant_type' => 'authorization_code',
		'client_id' => '<!--[--grant-client_id--]-->',
		'client_secret' => '<!--[--token-client_secret--]-->',
		'redirect_uri' => '<!--[--redirect-uri--]-->'
	),
	
	/**
	 * Revoke Params 
	 *
	'revoke-uri' => 'https://accounts.google.com/o/oauth2/revoke',
	'revoke-method' => 'get',
	'revoke-vars' => array(
		'token' => '<!--[--token-access--]-->'
	),
	
	/**
	 * Offline Params 
	 *
	'offline-token' => array(
		'access_type' => 'offline'	//this will get sent in token request
	),
	'offline-uri' => 'https://accounts.google.com/o/oauth2/token',
	'offline-method' => 'post',
	'offline-datatype' => 'json',
	'offline-vars' => array(
		'refresh_token' => '<!--[--refresh-token--]-->',
		'client_id' => '<!--[--grant-client_id--]-->',
		'client_secret' => '<!--[--token-client_secret--]-->',
		'grant_type' => 'refresh_token'
	)
);
*/