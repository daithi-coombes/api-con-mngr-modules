<?php
/*
  Plugin Name: MailChimp
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: MailChimp service module for API Connection Manager
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */
if(!class_exists("MailChimp_API")):
	class MailChimp_API extends API_Con_Mngr_Module{

		/** @var string Default us1. The endpoint datacenter prefix */
		private $dc = "us1";
	
		function __construct(){

			//set params
			$this->client_id = "538976041739";
			$this->client_secret = "d1e1c58df4d40c413766d57c8515b6ba";
			$this->redirect_uri = "http://127.0.0.1/wp3.5/wp-admin/admin-ajax.php?action=api_con_mngr";
			$this->protocol = "oauth2";
			$this->sessions = true;
			$this->url_authorize = "https://login.mailchimp.com/oauth2/authorize";
			$this->url_access_token = "https://login.mailchimp.com/oauth2/token";

			//construct parent
			parent::__construct();
		}

		/**
		 * Override the get_authorize_url as mailchimp doesn't handle the
		 * redirect_uri params 
		 * @see API_Con_Mngr_Module::get_authorize_url()
		 */
		function get_authorize_url( $tokens=array() ){
			$fields = array_merge(array(
				'client_id' => $this->client_id,
				'response_type' => 'code'
			), $tokens);
			return $this->url_authorize . "?" . http_build_query($fields);
		}
		/**
		 * return false if no error or error string if one
		 * @param array $response The serivce response in the WP_Http format
		 * @return mixed false|string
		 */
		function check_error( array $response ){
			
			if(!@$response['body']) return false;
			$body = json_decode($response['body']);
			
			if(@$body->error)
				return $this->error($body->error);
			return false;
		}
		
		function request($url, $method = 'GET', $parameters = array()) {
			
			//build uri
			
			return parent::request($url, $method, $parameters);
		}
	}
endif;
$oauth2 = new MailChimp_API();