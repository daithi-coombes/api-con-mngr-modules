<?php

/*
  Plugin Name: Twitter
  Plugin URI: https://github.com/cityindex/labs.cityindex.com/tree/master/httpdocs/wp-content/plugins/ci-login
  Description: Twitter service module for API Connection Manager
  Version: 0.1
  Author: Daithi Coombes
  Author URI: http://david-coombes.com
 */

require_once('twitteroauth/config.php');
require_once('twitteroauth/twitteroauth/twitteroauth.php');

if (!class_exists('API_Con_Twitter')):

	class API_Con_Twitter extends API_Con_Mngr_Module{

		/**
		 * 
		 */
		function __construct() {

			/**
			 * @todo change to set_options with array of 'option_label' => 'option_key' pairs 
			 */
			$this->set_params(array(
				'consumer_key' => '3cQ4PtAvBd8DjChpGI0CTg',
				'consumer_secret' => 'eOXwPsDTOWreG3sMKhBDYuFze7qBTBQMT2B1SJnbo',
				'http://david-coombes.com/wp-admin/admin-ajax.php?action=api_con_mngr'
			));
		}

		function set_params( array $test ) {
			;
		}

		/**
		 * Set the header
		 * 
		 * @param API_Con_Mngr_Header $header
		 * @return \API_Con_Mngr_Header 
		 */
		function set_header(API_Con_Mngr_Header $header) {
			return $header;
		}

	}

	endif;
$oauth1 = new API_Con_Twitter();
