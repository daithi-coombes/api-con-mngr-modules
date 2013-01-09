<?php
/*
  Plugin Name: MailChimp
  Plugin URI: https://github.com/david-coombes/api-con-mngr-modules
  Description: MailChimp service module for API Connection Manager
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

class MailChimp extends API_Con_Mngr_Module{
	
	function check_error( $response ){
		return false;
	}
}

$service = new MailChimp();