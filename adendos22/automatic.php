<?
session_start();
require 'Meli/meli.php';
require 'configApp.php';

$domain = $_SERVER['HTTP_HOST'];
$appName = explode('.', $domain)[0];

$meli = new Meli($appId, $secretKey);

// Make the refresh proccess
$refresh = $meli->refreshAccessToken();

// Now we create the sessions with the new parameters
$_SESSION['access_token'] = $refresh['body']->access_token;
$_SESSION['expires_in'] = time() + $refresh['body']->expires_in;
$_SESSION['refresh_token'] = $refresh['body']->refresh_token;
						
var_dump($_SESSION['access_token']);								
?>