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
			
			$this->client_id = "815c1b11dd41b653510e";
			$this->client_secret = "5ec6f5f3c3f3c0f00e90f3dcc946cd741ccf9cd7";
			$this->protocol = "oauth2";
			$this->url_authorize = "https://github.com/login/oauth/authorize";
			$this->url_access_token = "https://github.com/login/oauth/access_token";
			
			parent::__construct();
		}
		
		function check_error( array $response ){
			return false;
		}
		
		function get_authorize_url(){
			
			return parent::get_authorize_url(array(
				'scope' => 'repo, user'
			));
		}
	
	}
endif;
$oauth2 = new GitHub_API();
/**
$oauth2 = array(
	
	
	/**
	 * service params 
	 *
	'offline' => false,				//set this to true if service provides refresh tokens
	'button-text' => 'Login with GitHub',
	
	
	/**
	 * Grant vars 
	 *
	'grant-uri' => 'https://github.com/login/oauth/authorize',
	'grant-response-type' => 'query',
	//options to be set by the blog admin
	'grant-options' => array(
		'client_id' => 'Client ID',
		'scope' => 'Scope'
	),
	//parameters that will make up the final grant uri
	'grant-vars' => array(
		'client_id' => '<!--[--grant-client_id--]-->',
		'redirect_uri' => '<!--[--redirect-uri--]-->',
		'state' => '<!--[--[state]--]-->'
	), //end Grant vars
	
	
	/**
	 * Token vars 
	 *
	'token-uri' => 'https://github.com/login/oauth/access_token',
	'token-method' => 'post',
	'token-datatype' => 'json',
	//options to be set by the blog admin
	'token-options' => array(
		'client_secret' => 'Client Secret'
	),
	//set token headers
	'token-headers' => array(
		'Accept' => 'application/json'
	),
	//parameters that will be sent to request token
	'token-vars' => array(
		'client_id' => '<!--[--grant-client_id--]-->',
		'redirect_uri' => '<!--[--redirect-uri--]-->',
		'client_secret' => '<!--[--token-client_secret--]-->',
		'state' => '<!--[--[state]--]-->'
	) //end Token vars
);
 * 
 */