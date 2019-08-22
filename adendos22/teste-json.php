<?
session_start();
$mltoken="$_SESSION[access_token]";

$urlapi="https://api.mercadolibre.com/orders/1631106163?access_token=$mltoken";

//$urlapi="https://api.mercadolibre.com/orders/1643527415?access_token=$mltoken";

//PUXA RESOURCE, AQUI ORDERS, DAS NOTIFICACOES

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $urlapi,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array("x-format-new: true"),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
	
if ($err) {
  echo "cURL Error #:" . $err;
  die ("ERRO NO CURL");
} else {
//Aqui teremos que desmontar o JSON da response com json_decode e inserir os dados no banco de dados.
//echo "<br>";
//echo $response;
//echo $urlapi;
//echo "<br>";
}
$mlresponde = json_decode($response,true);


var_dump($response);
echo "<br><br>";
//var_dump($mlresponde['payments'][0]['total_paid_amount']);
//var_dump($mlresponde['payments'][1]['total_paid_amount']);
var_dump($mlresponde['paid_amount']);
var_dump($mlresponde['paid_amount']);
var_dump($valortotal);


$nshipment=$mlresponde['shipping']['id'];

$urlapi2="https://api.mercadolibre.com/shipments/$nshipment/costs?access_token=$mltoken";

$curl2 = curl_init();

curl_setopt_array($curl2, array(
  CURLOPT_URL => $urlapi2,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array("x-format-new: true"),
));

$response2 = curl_exec($curl2);
$err2 = curl_error($curl2);

curl_close($curl2);
	
if ($err2) {
  echo "cURL Error #:" . $err2;
  die ("ERRO NO CURL");
} else {
}
$mlresponde2 = json_decode($response2,true);
$shipmentcost=$mlresponde2['receiver']['cost'];
var_dump($shipmentcost);


?>