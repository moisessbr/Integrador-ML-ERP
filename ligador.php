<html>
<title>Autenticador</title>
</html>
<?php
//header("Access-Control-Allow-Origin: *");
//header("Content-Type: text/html; charset=UTF-8");
header("Refresh:300");
session_start();
require 'Meli/meli.php';
require 'configApp.php';

$meli = new Meli($appId, $secretKey, $_SESSION['access_token'], $_SESSION['refresh_token']);
if($_GET['code'] || $_SESSION['access_token']) {
	// If code exist and session is empty
	if($_GET['code'] && !($_SESSION['access_token'])) {
		// If the code was in get parameter we authorize
		$user = $meli->authorize($_GET['code'], $redirectURI);
		// Now we create the sessions with the authenticated user
		$_SESSION['access_token'] = $user['body']->access_token;
		$_SESSION['expires_in'] = time() + $user['body']->expires_in;
		$_SESSION['refresh_token'] = $user['body']->refresh_token;
	} else {
		// We can check if the access token in invalid checking the time
		if($_SESSION['expires_in'] < time()) {
			try {
				// Make the refresh proccess
				$refresh = $meli->refreshAccessToken();
				// Now we create the sessions with the new parameters
				$_SESSION['access_token'] = $refresh['body']->access_token;
				$_SESSION['expires_in'] = time() + $refresh['body']->expires_in;
				$_SESSION['refresh_token'] = $refresh['body']->refresh_token;
			} catch (Exception $e) {
			echo "1 catch\n";
			  	echo "Exception: $e";
			}
		}
	}


//exibe informações da sessão
echo '<pre>';
	print_r($_SESSION);
echo '</pre>';
//echo '<body onload="javascript: window.close()"></body>';
	
} else {
	echo '<body onload="javascript: location.href=\'' . $meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]) . '\'"></body>';
//echo '<p><a alt="Login using MercadoLibre oAuth 2.0" class="btn" href="' . $meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]) . '">Authenticate</a></p>';
	}
?>