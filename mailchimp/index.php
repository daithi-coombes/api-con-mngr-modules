<?php
/*
  Plugin Name: MailChimp
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: MailChimp service module for API Connection Manager
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */
if(!class_exists("MailChimp")):
	class MailChimp extends API_Con_Mngr_Module{

		function __construct(){

			$this->protocol = "oauth2";

			parent::__construct();
		}

		function check_error( array $response ){
			return false;
		}
	}
	$oauth2 = new MailChimp();
endif;