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
		
		function __construct(){

			$this->url_authorize = "http://23.21.217.245/ciauth/authorize";
			$this->url_access_token = "http://23.21.217.245/ciauth/token";
			$this->protocol = "oauth2";

			parent::__construct();
		}

		function check_error( array $response ){
			return false;
		}

		function get_authorize_url(){
			return parent::get_authorize_url(array(
				'redirect_uri' => $this->redirect_uri
			)); // . "&redirect_uri=" . $this->redirect_uri;
		}

		function get_uid( $dto = null ){
			if ( $dto->response['username'] )
				return $dto->response['username'];
		}

		function get_profile(){

		}

		function verify_token(){

		}

	}
endif;

$module = new CityIndex_API();