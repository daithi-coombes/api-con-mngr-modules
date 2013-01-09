api-con-mngr-modules
====================

Modules for the API Connection Manager to connect to service providers


Dependencies
============
These modules require the API Connection Manager to be installed.
Please see the instructions at:
https://github.com/david-coombes/api-connection-manager


Usage
=====
Extend the API_Con_Mngr_Module class and construct it in a variable thats
on of the following:
 * ```php $oauth1 = new My_Class();```
 * ```php $oauth2 = new My_Class();```
 * ```php $service = new My_Class();```

Your class must create the method
```php ::verify_token() ```
This is because it would be impossible for the parent class to know all the
different response data types for all the services. This method must return a
boolean true or false.

An example of the minimum requirement for a module class:
```php
class My_Class extends API_Con_Mngr_Module{
	
	$this->token = "permanent_token_value";

	/**
	 * This method must be created to verify access tokens
	 * @return boolean Returns true|false
	 */
	function verify_token(){
		return true;
	}
}
$service = new My_Class();
```

A more common minimum class:
```php
class My_Class extends API_Con_Mngr_Module{

	/**
	 * This method must be created to verify access tokens
	 * @return boolean Returns true|false
	 */
	function verify_token(){
		
		//if no token set
		if(!$this->token || empty($this->token))
			return false;
		
		//make request
		$res = $this->request(
			'http://example.com/oauth1/verify_token',
			'GET',
			array(
				'oauth_token' => $this->token
			);
		);

		//check request response
		$response = json_decode($res['body']);
		if($response->valid)
			return true;
		else
			return false;
	}
}
$oauth1 = new My_Class();
```

Plugins can then make calls to the service by calling your class like so:
```php
//get the module object
$module = $API_Connection_Manager->get_service('My_Class/index.php');

//make request to the service
$response = $module->get_result(
	'https://example.com/get_data',
	'GET',
	array('param1'=>'value1')
	);

//parse the response
$data = json_decode($response['body']);
```