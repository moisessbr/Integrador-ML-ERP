<html>
<title>Pedidos</title>
</html>
<?

//Token do MercadoLivre
session_start();
$mltoken="$_SESSION[access_token]";

//Este Script irá consultar o recurso de orders no BD. Depois cadastrará a ordem e o buyer no BD.

$link = mysqli_connect(""host","user","pass","bd");
	
if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$resultado = mysqli_query($link,"SELECT resource FROM melinotif WHERE resource LIKE '/orders/%' and status='novo' LIMIT 1");

if (mysqli_num_rows($resultado) > 0) {

$row = mysqli_fetch_array($resultado);
$dados = $row[0];
//$dados = '/orders/xxxxxxx'; - trocar para fazer o script com uma orders específica

{
echo $row[0];
echo '<br />';
}
//$row = order mais antiga cadastrada no BD

//Pesquisa no Banco de Dados meliorders se o resource já está cadastrado. Se sim, encerra o script; do contrário continua para a próxima parte.




$presource = mysqli_query($link,"SELECT * FROM meliorders WHERE resource='$dados'");

if (mysqli_num_rows($presource) > 0) {

mysqli_query($link,"UPDATE melinotif SET status='DPL' WHERE resource='$dados' and status='novo'");
echo "Resource já cadastrado."; 
 }
else {

   echo "Ainda não cadastrado. Cadastrando.";

//Fim Passo 01-A posteriormente juntar para parar o script aqui.
//inicio Passo 01-B. Acessa o MercadoLivre para obter os dados do JSON da ordem via CURL. 

$urlapi="https://api.mercadolibre.com$dados?access_token=$mltoken";
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
  mysqli_query($link,"UPDATE melinotif SET status='erro1' WHERE resource='$dados' and status='novo' LIMIT 1");
  die ("ERRO NO CURL");
} else {
//Aqui teremos que desmontar o JSON da response com json_decode e inserir os dados no banco de dados.
//echo "<br>";
//echo $response;
//echo $urlapi;
//echo "<br>";
$mlresponde = json_decode($response,true);

if (!isset($mlresponde['id'])){
	die ("<br><font color='red' face='verdana'><b>ERRO: TOKEN INVÁLIDO</b></font><br><font color='black' face='verdana'><b>Atualize a página do Token para continuar capturando os dados.</b></font>");
	(header("Refresh: 120;"));
}

$nshipment=$mlresponde['shipping']['id'];

if ($nshipment == null){
mysqli_query($link,"UPDATE melinotif SET status='retirada' WHERE resource='$dados' LIMIT 1");
}else{
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
$mlresponde2 = json_decode($response2,true);
//SEM FRETE -- DESATIVAR O CUSTO ZERADO E ATIVAR O CUSTO DO MLRESPONDE2
//$shipmentcost=$mlresponde2['receiver']['cost'];
$shipmentcost = '0';
}
}
}
// ATIVAR ESTA LINHA CASO VOLTE A POR FRETE NA VENDA!!!!
//if (!isset($shipmentcost)) $shipmentcost = '0';

$valortotal=(($mlresponde['order_items'][0]['quantity']*$mlresponde['order_items'][0]['unit_price'])+$shipmentcost);
$titulo=$mlresponde['order_items'][0]['item']['title'];
$tituloanuncio=str_replace("'","",$titulo);


//if ($mlresponde['order_items'][0]['item']['seller_custom_field'] == null){
//	$productsku=$mlresponde['order_items'][0]['item']['seller_sku'];
//}else{
//	$productsku=$mlresponde['order_items'][0]['item']['seller_custom_field'];	
//}

if ($mlresponde['order_items'][0]['item']['seller_sku'] == null){
	$productsku=$mlresponde['order_items'][0]['item']['seller_custom_field'];
}else{
	$productsku=$mlresponde['order_items'][0]['item']['seller_sku'];	
}


$link2 = mysqli_connect(""host","user","pass","bd");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
mysqli_query($link,"UPDATE melinotif SET status='errobd' WHERE resource='$dados'  and status='novo' LIMIT 1");
	}
mysqli_query($link2,"INSERT INTO meliorders (resource,orders,shipments,buyerid,produto,titulo,valor,quantidade,frete,valortotal,pagamento,data) VALUES ('$dados','".$mlresponde['id']."','".$mlresponde['shipping']['id']."','".$mlresponde['buyer']['id']."','$productsku','$tituloanuncio','".$mlresponde['order_items'][0]['unit_price']."','".$mlresponde['order_items'][0]['quantity']."','$shipmentcost','$valortotal','".$mlresponde['payments'][0]['id']."','".$mlresponde['date_created']."')");
mysqli_query($link,"UPDATE melinotif SET status='lida' WHERE resource='$dados' LIMIT 1");
	{
echo(mysqli_error($link2));
	}
if ($mlresponde['shipping']['id'] == null){
mysqli_query($link,"UPDATE melinotif SET status='retirada' WHERE resource='$dados' LIMIT 1");	
}
mysqli_close($link2);

$nomecompleto=$mlresponde['buyer']['first_name'] . ' ' .$mlresponde['buyer']['last_name'];
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

if ($ddd_cliente== NULL)$ddd_cliente='10';
if ($numero_cliente== NULL)$numero_cliente='123456789';

$link3 = mysqli_connect(""host","user","pass","bd");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
mysqli_query($link,"UPDATE melinotif SET status='errobd' WHERE resource='$dados' and status='novo' LIMIT 1");
	}
mysqli_query($link3,"INSERT INTO melibuyer (id,nome,tipo,documento,ddd,telefone) VALUES ('".$mlresponde['buyer']['id']."','$nomecompleto','$doctype','".$mlresponde['buyer']['billing_info']['doc_number']."','$ddd_cliente','$numero_cliente')");
	{
echo(mysqli_error($link3));
	}
mysqli_close($link3);

}
mysqli_close($link);
header("Refresh:4");
}else{
	echo "Nenhum pedido para cadastrar. Aguardando.<br> Verificando novamente em 40 segundos.";
	header("Refresh:40");
}



?>