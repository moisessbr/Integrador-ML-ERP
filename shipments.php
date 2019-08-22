<html>
<title>Envios</title>
</html>
<?
//Token do MercadoLivre
session_start();
$mltoken="$_SESSION[access_token]";

//Ler a notificação de SHIPMENT, consultar se existe ou não.

$link4 = mysqli_connect(""host","user","pass","bd");

$link5 = mysqli_connect(""host","user","pass","bd");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$resultadoship = mysqli_query($link4,"SELECT resource FROM melinotif WHERE resource LIKE '/shipments/%' and status='novo' LIMIT 1");

if (mysqli_num_rows($resultadoship) > 0) {

$row3 = mysqli_fetch_array($resultadoship);
$dados2 = $row3[0];
{
echo $row3[0];
echo '<br />';
}

$presource2 = mysqli_query($link4,"SELECT * FROM melishipments WHERE resource='$dados2'");

if (mysqli_num_rows($presource2) > 0) {
 mysqli_query($link4,"UPDATE melinotif SET status='DPL' WHERE resource='$dados2' and status='novo'");
 echo "Resource já cadastrado.";
 }
else {

   echo "Ainda não cadastrado. Cadastrando";
   
$urlapi3="https://api.mercadolibre.com$dados2?access_token=$mltoken";


$curl2 = curl_init();

curl_setopt_array($curl2, array(
  CURLOPT_URL => $urlapi3,
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
  mysqli_query($link,"UPDATE melinotif SET status='erro1' WHERE resource='$dados2' LIMIT 1");
  die ("ERRO NO CURL");
} else {
  echo "<br>";
  echo "<br>";

$mlresponde2 = json_decode($response2,true);


if (!isset($mlresponde2['id'])){
	die ("<br><font color='red' face='verdana'><b>ERRO: TOKEN INVÁLIDO ou API Offiline.</b></font><br><font color='black' face='verdana'><b>Atualize a página do Token ou aguarde o retorno da API para continuar capturando os dados.</b></font>");
	(header("Refresh: 120;"));
}

$cepship=$mlresponde2['destination']['shipping_address']['zip_code'];

$urlapi3="https://viacep.com.br/ws/$cepship/json";

$curl3 = curl_init();

curl_setopt_array($curl3, array(
  CURLOPT_URL => $urlapi3,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
//CURLOPT_HTTPHEADER => array("x-format-new: true"),
));

$response3 = curl_exec($curl3);
$err3 = curl_error($curl3);

curl_close($curl3);
	
if ($err3) {
  echo "cURL Error #:" . $err3;
  mysqli_query($link4,"UPDATE melinotif SET status='erro1' WHERE resource='$dados2' LIMIT 1");
  die ("ERRO NO CURL DO CEP");
} else {
//Aqui teremos que desmontar o JSON da response com json_decode e inserir os dados no banco de dados.
  echo "<br>";
// echo $response3;
// echo $urlapi3;
  echo "<br>";
  
$viacepresponse = json_decode($response3,true);

$codigoibge=$viacepresponse['ibge'];
$viacepuf=$viacepresponse['uf'];

$bairro=$mlresponde2['destination']['shipping_address']['neighborhood']['name'];
if ($bairro== null)$bairro= 'Bairro';

//var_dump ($mlresponde2['destination']['shipping_address']['street_number']);
//var_dump ($mlresponde2['destination']['shipping_address']['neighborhood']['name']);
//var_dump ($mlresponde2['destination']['shipping_address']['comment']);
//var_dump ($response);

$nomerua=$mlresponde2['destination']['shipping_address']['street_name'];
$nomeruasemaspas=str_replace("'","",$nomerua);
$nomecidade=$mlresponde2['destination']['shipping_address']['city']['name'];
$nomecidadesemaspas=str_replace("'","",$nomecidade);
$bairrosemaspas=str_replace("'","",$bairro);
$complementoendereco=$mlresponde2['destination']['shipping_address']['comment'];
$complementoenderecosemaspas=str_replace("'","",$complementoendereco);

			if($mlresponde2['logistic']['type']=== "fulfillment"){
				$logistica="132";
			}else{
				$logistica="16";
			}

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link5,"INSERT INTO melishipments (resource,shipment,cep,endereco,numero,cidade,codigoibge,uf,bairro,complemento, logistica) VALUES ('$dados2','".$mlresponde2['id']."','".$mlresponde2['destination']['shipping_address']['zip_code']."','$nomeruasemaspas','".$mlresponde2['destination']['shipping_address']['street_number']."','$nomecidadesemaspas','$codigoibge','$viacepuf','$bairrosemaspas','$complementoenderecosemaspas','$logistica')");
mysqli_query($link5,"UPDATE melinotif SET status='lida' WHERE resource='$dados2' LIMIT 1");
	{
echo(mysqli_error($link5));
	}
}
}
}
mysqli_close($link4);
mysqli_close($link5);
header("Refresh:20");
}else{
	echo "Nenhum pedido para cadastrar. Aguardando.<br> Verificando novamente em 45 segundos.";
	header("Refresh:45");
}

?>