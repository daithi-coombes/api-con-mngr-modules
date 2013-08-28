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
	
		
	
	}
endif;

$module = new CityIndex_API();