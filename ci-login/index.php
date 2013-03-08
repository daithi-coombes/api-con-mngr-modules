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
	
		//private $endpoint = "http://ec2-107-22-63-28.compute-1.amazonaws.com/ciauth";
		private $endpoint = "http://ec2-23-21-217-245.compute-1.amazonaws.com/ciauth";
	
		function __construct(){
			
			$this->protocol = "oauth2";
			$this->url_authorize = $this->endpoint . "/authorize";
			$this->url_access_token = $this->endpoint . "/token";
			
			parent::__construct();
		}
		
		function check_error( array $res ){
			return false;
		}
		
		function get_authorize_url( $params=array() ){
			return parent::get_authorize_url(array(
				'redirect_uri' => $this->callback_url,
				'state' => 'somevalue'
			));
		}
		
		function get_uid(){
			if($this->access_token){
				$parts = explode (":", $this->access_token);
				$this->log("CI uid => {$parts[0]}");
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
endif;

$module = new CityIndex_API();