<?php
/*
  Plugin Name: City Index Login
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: API Connection Manager module for City Index
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

if ( !class_exists( 'CIAPIPHP' ) )
	require_once( 'CIAPI-PHP/CIAPIPHP.class.php' );

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
		
		function get_profile(){
			
			$res = $this->request("https://ciapi.cityindex.com/tradingapi/useraccount/ClientAndTradingAccount");
			$body = json_decode($res['body']);
			return (object) array(
				'username' =>  $body->LogonUserName,
				'id' => $this->get_uid()
			);
		}
		
		function get_uid(){
			if($this->access_token){
				$parts = explode (":", $this->access_token);
				return $parts[0];
			}
			return false;
		}
		
		function request($url, $method="get", $parameters=array(), $die=true){
			
			//headers
			if($this->access_token){
				
				//split token into user and session
				$parts = explode(":", $this->access_token);
				$uid = $parts[0];
				$session = $parts[1];
				
				$this->headers = array(
					'Content-Type' => 'application/json', 'userName' => $uid, 'Session' => $session,
				);
			}
			
			return parent::request($url, $method, $parameters, $die);
		}
		
		function verify_token(){
			return true;
		}
	}
endif;

$module = new CityIndex_API();