<?php
/*
  Plugin Name: City Index Login
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: API Connection Manager module for City Index
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

require_once('CIAPI-PHP/CIAPIPHP.class.php');

if(!class_exists("CityIndex_API")):
	
	class CityIndex_API extends API_Con_Mngr_Module{
	
		private $endpoint = "http://ec2-107-22-63-28.compute-1.amazonaws.com/ciauth";
	
		function __construct(){
			
			$this->protocol = "oauth2";
			$this->url_authorize = $this->endpoint . "/token";
			$this->url_access_token = $this->endpoint . "/token";
			
			parent::__construct();
		}
		
		function check_error( array $res ){
			return false;
		}
		
		function get_authorize_url( $params=array() ){
			return parent::get_authorize_url(array(
				'redirect_uri' => $this->callback_url
			));
		}
		
		function get_uid(){
			if($this->access_token){
				$parts = explode (":", $this->access_token);
				return $parts[0];
			}
			return false;
		}
		
		function request($url, $method="get", $parameters=array(), $die=true){
			
			return parent::request($url, $method, $parameters, $die);
		}
		
		function verify_token(){
			return true;
		}
	}
	
	/**
	 * @deprecated use the oauth2 class above
	 *
	class CityIndex_API extends API_Con_Mngr_Module{
		
		private $api;
		private $username='';
		private $endpoint;
		protected $session='';
	
		function __construct(){
			
			require_once('CIAPI-PHP/CIAPIPHP.class.php');
			$this->api = new CIAPIPHP();
			$this->endpoint = "https://ciapipreprod.cityindextest9.co.uk/tradingapi/";
			$this->login_form = array(
				'fields' => array(
					'text' => 'UserName',
					'password' => 'Password'
				),
				'endpoint' => false
			);
			$this->protocol = "service";
			
			parent::__construct();
		}
	
		function check_error( array $res ){
			
			if($this->api->get_errors())
				print $this->get_login_button();
				
			return false;
		}
		
		function get_uid(){
			return false;
		}
		
		function login_form_callback( stdClass $dto){
			
			//login
			$res = $this->api->logIn( $dto->response['UserName'], $dto->response['Password'] );
			$this->check_error( (array) $res );

			return $res->Session;
		}
		
		function request( $target, $method='get', $parameters=array(), $die=true){
			
			$url = $this->endpoint . trim($target,"/");
			$this->headers = array(
				'UserName' => "DM509022", //$this->username,
				'Session' => $this->session
			);
			$this->log($this->headers);
			
			return parent::request($url, $method, $parameters, $die);
		}
		
		function verify_token(){
			return true;
		}
	}
	 * 
	 */
endif;

$module = new CityIndex_API();