<?php

//Token do MercadoLivre
session_start();
$mltoken="$_SESSION[access_token]";

//Passo 01-A. Conecta ao MYSQL e retorna a primeira notificação nova de orders inserida no banco de dados.

$link = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$resultado = mysqli_query($link,"SELECT resource FROM melinotif WHERE resource LIKE '/orders/%'");

$row = mysqli_fetch_array($resultado);
$dados = $row[0];
{
echo $row[0];
echo '<br />';
}
//$row = order mais antiga cadastrada no BD

//Pesquisa no Banco de Dados meliorders se o resource já está cadastrado. Se sim, encerra o script; do contrário continua para a próxima parte.

$presource = mysqli_query($link,"SELECT * FROM meliorders WHERE resource='$dados'");

if (mysqli_num_rows($presource) > 0) {

 echo "Resource já cadastrado.";
 }
// POSTERIORMENTE INSERIR UM COD PRA ATUALIZAR RESOURCE PARA LIDA.
else {

   echo "Ainda não cadastrado.";
}

mysqli_close($link);

//Fim Passo 01-A posteriormente juntar para parar o script aqui.
//inicio Passo 01-B. Acessa o MercadoLivre para obter os dados do JSON da ordem via CURL. 

$urlapi="https://api.mercadolibre.com$dados?access_token=$mltoken";
//PUXA RESOURCE, AQUI ORDERS, DAS NOTIFICACOES
//URL ABAIXO PUXANDO ORDEM TESTE 1 DO ML PARA SEGUIR COM O PROCESSO
//$urlapi="https://api.mercadolibre.com/carrito_mocks/orders/1289342698?access_token=$mltoken";
//URL ABAIXO PUXANDO ORDEM TESTE 2 DO ML PARA SEGUIR COM O PROCESSO
//$urlapi="https://api.mercadolibre.com/carrito_mocks/orders/1289343330?access_token=$mltoken";

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
  echo "<br>";
//  echo $response;
  echo $urlapi;
  echo "<br>";
  
}

$mlresponde = json_decode($response,true);

$link2 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
//POR O $DADOS DE VOLTA NO LUGAR DO RESOURCE
mysqli_query($link2,"INSERT INTO meliorders (resource,orders,shipments,buyerid,produto,titulo,valor,quantidade,frete,valortotal,pagamento,data) VALUES ('$dados','".$mlresponde['id']."','".$mlresponde['shipping']['id']."','".$mlresponde['buyer']['id']."','".$mlresponde['order_items'][0]['item']['seller_custom_field']."','".$mlresponde['order_items'][0]['item']['title']."','".$mlresponde['order_items'][0]['unit_price']."','".$mlresponde['order_items'][0]['quantity']."','".$mlresponde['payments'][0]['shipping_cost']."','".$mlresponde['payments'][0]['total_paid_amount']."','".$mlresponde['payments'][0]['id']."','".$mlresponde['date_created']."')");
	{
echo("Error description: " . mysqli_error($link2));
	}
mysqli_close($link2);

$nomecompleto=$mlresponde['buyer']['last_name'] . ' ' .$mlresponde['buyer']['first_name'];
$numerofull=$mlresponde['buyer']['phone']['area_code'] .$mlresponde['buyer']['phone']['number'];
$ddd_cliente = substr($numerofull, 0, 2);
$numero_cliente = substr($numerofull, 2);
$cpfcnpj=$mlresponde['buyer']['billing_info']['doc_type'];
{
    if($cpfcnpj === "CPF" ) {
        $doctype = 'F';
    } else{
        $doctype = 'J';
    }
}

$link3 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link3,"INSERT INTO melibuyer (id,nome,tipo,documento,ddd,telefone) VALUES ('".$mlresponde['buyer']['id']."','$nomecompleto','$doctype','".$mlresponde['buyer']['billing_info']['doc_number']."','$ddd_cliente','$numero_cliente')");
	{
echo("Error description: " . mysqli_error($link3));
	}
mysqli_close($link3);


var_dump($mlresponde['buyer']['billing_info']['doc_type']);
var_dump($mlresponde['buyer']['billing_info']['doc_number']);
var_dump($cpfcnpj);
var_dump($doctype);

//FIM DO PASSO 01.
//INICIO DO PASSO 02. Ler a notificação de SHIPMENT, consultar se existe ou não.
//PASSO 02-A.

$link4 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$resultadoship = mysqli_query($link4,"SELECT resource FROM melinotif WHERE resource LIKE '/shipments/%'");

$row3 = mysqli_fetch_array($resultadoship);
$dados2 = $row3[0];
{
echo $row3[0];
echo '<br />';
}

//$row = order mais antiga cadastrada no BD

//Pesquisa no Banco de Dados melishipments se o resource já está cadastrado. Se sim, encerra o script; do contrário continua para a próxima parte.

$presource2 = mysqli_query($link4,"SELECT * FROM melishipments WHERE resource='$dados2'");

if (mysqli_num_rows($presource2) > 0) {

 echo "Resource já cadastrado.";
 }
// POSTERIORMENTE INSERIR UM COD PRA ATUALIZAR RESOURCE PARA LIDA.
else {

   echo "Ainda não cadastrado.";
}

mysqli_close($link4);

//FIM DO 02-A.
//PASSO 02-B. PUXAR A ORDEM, O CODIGO IBGE E INSERIR NO BD.

$urlapi2="https://api.mercadolibre.com$dados2?access_token=$mltoken";
//PUXA RESOURCE, AQUI ORDERS, DAS NOTIFICACOES
//URL ABAIXO PUXANDO ORDEM TESTE 1 DO ML PARA SEGUIR COM O PROCESSO
//$urlapi2="https://api.mercadolibre.com/carrito_mocks/shipments/25943308796?access_token=$mltoken";


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
//Aqui teremos que desmontar o JSON da response com json_decode e inserir os dados no banco de dados.
  echo "<br>";
//  echo $response2;
//  echo $urlapi2;
  echo "<br>";
  
}

$mlresponde2 = json_decode($response2,true);


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
  die ("ERRO NO CURL DO CEP");
} else {
//Aqui teremos que desmontar o JSON da response com json_decode e inserir os dados no banco de dados.
  echo "<br>";
// echo $response3;
// echo $urlapi3;
  echo "<br>";
  
}

$viacepresponse = json_decode($response3,true);

$codigoibge=$viacepresponse['ibge'];
$viacepuf=$viacepresponse['uf'];

var_dump ($mlresponde2['destination']['shipping_address']['street_number']);
var_dump ($mlresponde2['destination']['shipping_address']['neighborhood']['name']);
var_dump ($mlresponde2['destination']['shipping_address']['comment']);
var_dump ($response);

$link5 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
mysqli_query($link5,"INSERT INTO melishipments (resource,shipment,cep,endereco,numero,cidade,codigoibge,uf,bairro,complemento) VALUES ('$dados2','".$mlresponde2['id']."','".$mlresponde2['destination']['shipping_address']['zip_code']."','".$mlresponde2['destination']['shipping_address']['street_name']."','".$mlresponde2['destination']['shipping_address']['street_number']."','".$mlresponde2['destination']['shipping_address']['city']['name']."','$viacepuf','$codigoibge','".$mlresponde2['destination']['shipping_address']['neighborhood']['name']."','".$mlresponde2['destination']['shipping_address']['comment']."')");
	{
echo("Error description: " . mysqli_error($link5));
	}
mysqli_close($link5);
//FIM DO PASSO 02.
//INICIO PASSO 03-A. PESQUISAR AS ORDERS COM AQUELE ID DE SHIPMENT.




?>