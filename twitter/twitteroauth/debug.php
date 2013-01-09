<?php
session_start();

require_once('../../../api-connection-manager/debug.func.php');
require_once('twitteroauth/OAuth.php');
require_once('config.php');
require_once('twitteroauth/twitteroauth.php');


$oauth_token = "113632037-FFzWqR1wPvvNiPIKyTTDKMIL3qoaYaIZu93zHFEN"; //$_SESSION['oauth_token'];
$oauth_token_secret = "kbckGdnJkua5dvpg75CY91SadhRgf9UnYcOseuy4"; //$_SESSION['oauth_token_secret'];

$twit = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret );
$user = $twit->get('account/verify_credentials');
//$twit->get('help/test');
$res = $twit->get('statuses/friends_timeline');

ar_print($res);
ar_print($twit);