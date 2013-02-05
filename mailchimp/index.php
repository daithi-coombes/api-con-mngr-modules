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
		public $dc = "us1";
		public $login_url = "";
		public $api_endpoint = "";
	
		function __construct(){

			//set params
			$this->endpoint = "https://login.mailchimp.com";
			$this->protocol = "oauth2";
			$this->sessions = true;
			$this->options['apikey'] = '%s';
			$this->url_authorize = "https://login.mailchimp.com/oauth2/authorize";
			$this->url_access_token = "https://login.mailchimp.com/oauth2/token";

			//construct parent
			parent::__construct();
		}

		/**
		 * Hook into a successfull login to get and set the endpoint params
		 * 
		 * @param stdClass $dto
		 */
		function do_login( stdClass $dto ){
			
			$this->log("mailchimp do_login():");
			
			$res = $this->request(
					"https://login.mailchimp.com/oauth2/metadata"
					);
			$params = (array) json_decode($res['body']);
			$this->set_params($params);
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
		
		function get_uid(){}
		
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
		
		function request($url, $method = 'GET', $parameters = array(), $die=true) {
			
			//build url
			if(!empty($this->api_endpoint))
				$url = $this->api_endpoint ."/1.3/?method={$url}";
			
			$this->headers = array(
				'Authorization' => "OAuth {$this->access_token}"
			);
			return parent::request($url, $method, $parameters);
		}

		/**
		 * Verify token
		 * @return boolean 
		 */
		function verify_token(){
			return true;
		}
	}
endif;
$module = new MailChimp_API();
