<?php
/*
  Plugin Name: Flowdock
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: Flowdock login module for API Connection Manager
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */
if(!class_exists("FlowDock_API")):
	class FlowDock_API extends API_Con_Mngr_Module{

		function __construct() {

			$this->protocol = "custom";
			$this->options['apikey'] = "%s";

			parent::__construct();
		}

		function get_uid(){

		}

		function get_profile(){
			;
		}
		
		function request( $url, $method='get', $parameters=array(), $die=true){
			
			if(!count($this->headers))
				$this->headers['content-type'] = 'application/x-www-form-urlencoded';
			
			return parent::request($url, $method, $parameters, $die);

		}
		
		function verify_token() {
			;
		}

		function check_error(array $response) {
			;
		}
	}
endif;

$module = new FlowDock_API();