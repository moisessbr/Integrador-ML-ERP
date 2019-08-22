<?
session_start();
$mltoken="$_SESSION[access_token]";

$urlnotes="https://api.mercadolibre.com/orders/1643141637/notes?access_token=$mltoken";

$nota='{"note":"test"}';

$ch = curl_init($urlnotes);

curl_setopt_array($ch,[
    CURLOPT_CUSTOMREQUEST=>"POST",
	CURLOPT_POSTFIELDS=>"$nota",
	CURLOPT_RETURNTRANSFER => 1,
	]);
$resposta = (curl_exec($ch));
curl_close($ch);

$respostaML = json_decode($resposta,true);

var_dump($respostaML);

?>