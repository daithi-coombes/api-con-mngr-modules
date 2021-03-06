<?php

/**
 * A bootstrap for the Dropbox SDK usage examples
* @link https://github.com/BenTheDesigner/Dropbox/tree/master/examples
*/

// Prevent access via command line interface
if (PHP_SAPI === 'cli') {
    exit('bootstrap.php must not be run via the command line interface');
}

// Don't allow direct access to the bootstrap
if(basename($_SERVER['REQUEST_URI']) == 'bootstrap.php'){
    exit('bootstrap.php does nothing on its own. Please see the examples provided');
}

// Set error reporting
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

// Register a simple autoload function
spl_autoload_register(function($class){
    $class = str_replace('\\', '/', $class);
    include_once('../' . $class . '.php');
});

// Set your consumer key, secret and callback URL
$key      = 'rlloodn1vrhfaqa';
$secret   = '3a9u8a8nf1vm3x2';

// Check whether to use HTTPS and set the callback URL
$protocol = (!empty($_SERVER['HTTPS'])) ? 'https' : 'http';
$callback = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Instantiate the Encrypter and storage objects
$encrypter = new \Dropbox\OAuth\Storage\Encrypter('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');

// User ID assigned by your auth system (used by persistent storage handlers)
$userID = 1;

// Instantiate the database data store and connect
//$storage = new \Dropbox\OAuth\Storage\PDO($encrypter, $userID);
//$storage->connect('localhost', 'dropbox', 'username', 'password', 3306);
// Optionally set the table name, default is dropbox_oauth_tokens
// $storage->setTable('oauth_tokens');

// If you use this, comment out lines 44-47
$storage = new \Dropbox\OAuth\Storage\Session($encrypter);

// Instantiate the filesystem store and set the token directory
// Note: If you use this, comment out lines 44-47 and 50
//$storage = new \Dropbox\OAuth\Storage\Filesystem($encrypter, $userID);
//$storage->setDirectory('tokens');

$OAuth = new \Dropbox\OAuth\Consumer\Curl($key, $secret, $storage, $callback);
$dropbox = new \Dropbox\API($OAuth);
