<?
session_start();
$mltoken="$_SESSION[access_token]";

$link10 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

$resultadoship = mysqli_query($link10,"SELECT resource FROM melinotif WHERE resource LIKE '/shipments/%' and status='lida' LIMIT 1");
$row5 = mysqli_fetch_array($resultadoship);
$shipmentlido = $row5[0];
//$shipmentlido='/shipments/25943308796';
{
//echo $row5[0];
echo '<br />';
}

//pesquisar o resource de notificação lido
//pesquisar o resource na melishipments e pegar o numero do shipmentlido

$consultaship0 = mysqli_query($link10,"SELECT * FROM melishipments WHERE resource ='$shipmentlido'");

$shipment55 = mysqli_fetch_array($consultaship0);

$shipment = $shipment55['shipment'];

$consultaship = mysqli_query($link10,"SELECT * FROM meliorders WHERE shipments ='$shipment'");

$order99 = mysqli_fetch_array($consultaship,MYSQLI_ASSOC);

$resourceorders=$order99['resource'];

$skuorders=$order99['produto'];

$datapedido=$order99['data'];

$dataimp="2018-03-01T10:20:00.000-00:00";

if ($shipmentlido== NULL){
	header("Refresh:120");
	echo "Sem pedidos para lançar.<br>";
	die("Aguardando. Nova atualização em 2 minutos.");
}

if ($resourceorders== NULL){
	mysqli_query($link10,"UPDATE melinotif SET status='NOO' WHERE resource ='$shipmentlido'");
	header("Refresh:5");
	echo "Envio sem pedido. Atualizando.";
	die("NOO");	
	
}

if ($datapedido<$dataimp){
	mysqli_query($link10,"UPDATE melinotif SET status='OLD' WHERE resource='$shipmentlido' and status='lida'");
	mysqli_query($link10,"UPDATE melinotif SET status='OLD' WHERE resource='$resourceorders' and status='lida'");
	header("Refresh:5");
	echo "Pedido antigo. Atualizando.";
	die("OLD");
}


if ($skuorders== NULL){
	mysqli_query($link10,"UPDATE melinotif SET status='ssku' WHERE resource='$shipmentlido' and status='lida'");
	mysqli_query($link10,"UPDATE melinotif SET status='ssku' WHERE resource='$resourceorders' and status='lida'");
$notaSKU="{'note':'Pedido sem SKU no produto. Lance manualmente.'}";
$urlnotesSKU="https://api.mercadolibre.com$resourceorders/notes?access_token=$mltoken";
$chSKU = curl_init($urlnotesSKU);
curl_setopt_array($chSKU,[
    CURLOPT_CUSTOMREQUEST=>"POST",
	CURLOPT_POSTFIELDS=>"$notaSKU",
	CURLOPT_RETURNTRANSFER => 1,
	]);
$respostaSKU = (curl_exec($chSKU));
curl_close($chSKU);
//echo "$resposta";
	header("Refresh:3");
	echo "Venda Nº '$resourceorders' Não contem SKU. Lance manualmente.";
	die("Sem SKU.");
}else{
if (mysqli_num_rows($consultaship) == 1) {

echo "Encontrado pedido com um item. Enviando ao sistema.";
$consultabuyer = mysqli_query($link10,"SELECT * FROM melibuyer WHERE id ='$order99[buyerid]'");
$buyer1 = mysqli_fetch_array($consultabuyer);

$inserecliente="{'Codigo':0,'Nome':'$buyer1[nome]','TipoPessoa':'$buyer1[tipo]','DataNascimento':'','Genero':'','DocumentoFiscal':'$buyer1[documento]','Identidade':'','RazaoSocial':'','CEP':'$shipment55[cep]','Logradouro':'$shipment55[endereco]','LogradouroNumero': '$shipment55[numero]','Bairro': '$shipment55[bairro]','LogradouroComplemento': '$shipment55[complemento]','Municipio':'$shipment55[codigoibge]','UnidadeFederativa': '$shipment55[uf]','Telefone':'$buyer1[telefone]','TelefoneDDD':'$buyer1[ddd]','Email':'','DataCadastro':'','ReceberEmail':false,'Observacoes':'','PrecoValidade': 0,'MinimoVenda':0,'Ativo':true}";


$ch = curl_init('189.111.250.175:5050/root/cliente');

curl_setopt_array($ch, [
    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'PUT',
	// Equivalente ao -H:
    CURLOPT_HTTPHEADER =>  "Content-Type: application/json",
		 // Equivalente ao -H:
   CURLOPT_HTTPHEADER => "Accept: application/json",
			 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
		"X-Token: 06h8NA1lXDc5Oo94UJEFmYT3Q2kp335yBcp24KlD",
    ],
	CURLOPT_POSTFIELDS=> ($inserecliente),
    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);
$respostacliente = (curl_exec($ch));
curl_close($ch);
//echo "$respostacliente";
//echo "<br>";
//echo "<br>";
//echo "$inserecliente";
//echo "<br>";
//echo "<br>";

$clientesistema=json_decode($respostacliente,true);
$numerocliente=$clientesistema['Model']['Codigo'];

sleep(1);
$inserircliente1 = mysqli_query($link10,"UPDATE melishipments SET cliente='$numerocliente' WHERE shipment='$shipment'");

//CURL - Inserção do pedido.

$inserevenda="{'Codigo':0,'NaoPersistir': false,'ClienteCodigo':'$numerocliente','ClienteTipoPessoa':'$buyer1[tipo]','DocumentoFiscal':'$buyer1[documento]','Identidade':'','TransportadoraCodigo':16,'ValorTotal':'$order99[valortotal]','ValorFrete':'$order99[frete]','ValorTotalBruto':'$order99[valortotal]','DataVenda':'$order99[data]','Entrega':true,'EntregaNome':'$buyer1[nome]','EntregaEmail':'$buyer1[documento]@mlint.shopdng.com.br','NumeroObjeto':'','EntregaTelefone':'$buyer1[ddd]$buyer1[telefone]','EntregaLogradouro':'$shipment55[endereco]','EntregaLogradouroNumero':'$shipment55[numero]','EntregaLogradouroComplemento':'$shipment55[complemento]','EntregaBairro':'$shipment55[bairro]','EntregaMunicipio':'$shipment55[codigoibge]','EntregaUnidadeFederativa':'$shipment55[uf]','EntregaCEP':'$shipment55[cep]','CupomDescontoCodigo':'','CupomDescontoValor':0,'VendaPagamentos':[{'Valor':'$order99[valortotal]','DataVencimento':'','DataCaptura':'$order99[data]','NumeroAutenticacao':'','NumeroAutorizacao':'','FormaPagamentoCodigo':21,'VendaCodigo':0}],'Itens':[{'Codigo':0,'VendaCodigo':0,'ProdutoCodigo':'$order99[produto]','PrecoUnitarioVenda':'$order99[valor]','PrecoUnitarioCusto':0,'EmbaladoParaPresente':false,'Quantidade':'$order99[quantidade]','AtributosEspeciais':'','ItemNome':'$order99[titulo]','ItemValorBruto':'$order99[valor]','ItemValorLiquido':'$order99[valor]','ItemDescontoUnitario':0}],'Observacoes':'','ObservacoesLoja':'','CodigoStatus':0,'DescricaoStatus':'','PrevisaoEntrega':'','Origem':'MERCADOLIVRE','OrigemPedido':'MERCADOLIVRE','CodigoPedidoExterno':'$order99[orders]-$order99[pagamento]','TipoVenda':'V','EnviarEmail':false,'Reservada':false}";

$ch2 = curl_init('189.111.250.175:5050/root/venda');

curl_setopt_array($ch2, [
    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'PUT',
	// Equivalente ao -H:
    CURLOPT_HTTPHEADER =>  "Content-Type: application/json, charset=UTF-8",
		 // Equivalente ao -H:
   CURLOPT_HTTPHEADER => "Accept: application/json",
			 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
		"X-Token: 06h8NA1lXDc5Oo94UJEFmYT3Q2kp335yBcp24KlD",
    ],
	CURLOPT_POSTFIELDS=> ($inserevenda),
    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);
$respostavenda = (curl_exec($ch2));
curl_close($ch2);
//echo "$respostavenda";
//echo "<br>";
//echo "<br>";
//echo "$inserevenda";

$vendasistema=json_decode($respostavenda,true);
$numerovenda=$vendasistema['Model']['Codigo'];
$vendatruefalse=$vendasistema['status'];

sleep(1);

if ($vendatruefalse == false){
	mysqli_query($link10,"UPDATE melinotif SET status='erroV' WHERE resource='$shipmentlido'");
	mysqli_query($link10,"UPDATE melinotif SET status='erroV' WHERE resource ='$order99[resource]'");
	echo "$respostavenda";
$nota="{'note':'Pedido não lançado. Cadastre manualmente.'}";
$urlnotes="https://api.mercadolibre.com$resourceorders/notes?access_token=$mltoken";
$ch = curl_init($urlnotes);
curl_setopt_array($ch,[
    CURLOPT_CUSTOMREQUEST=>"POST",
	CURLOPT_POSTFIELDS=>"$nota",
	CURLOPT_RETURNTRANSFER => 1,
	]);
$resposta = (curl_exec($ch));
curl_close($ch);
	header("Refresh:45");
	die("Venda não lançada.");
}else{
	mysqli_query($link10,"UPDATE melishipments SET venda='$numerovenda' WHERE shipment='$shipment'");
	mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource='$shipmentlido'");
	mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource ='$order99[resource]'");
$nota="{'note':'Pedido Lançado no sistema. Nº$numerovenda'}";
$urlnotes="https://api.mercadolibre.com$resourceorders/notes?access_token=$mltoken";
$ch = curl_init($urlnotes);
curl_setopt_array($ch,[
    CURLOPT_CUSTOMREQUEST=>"POST",
	CURLOPT_POSTFIELDS=>"$nota",
	CURLOPT_RETURNTRANSFER => 1,
	]);
$resposta = (curl_exec($ch));
curl_close($ch);
header("Refresh:45");
echo "Pedido '$order99[orders]' lançado no sistema. Nº$numerovenda";
}

}
if (mysqli_num_rows($consultaship) == 2) {

 echo "Encontrado pedido com dois itens. Enviando ao sistema.";

$order299 = mysqli_fetch_array($consultaship,MYSQLI_ASSOC);

$consultabuyer2 = mysqli_query($link10,"SELECT * FROM melibuyer WHERE id ='$order99[buyerid]'");
$buyer2 = mysqli_fetch_array($consultabuyer2);

$inserecliente2="{'Codigo':0,'Nome':'$buyer2[nome]','TipoPessoa':'$buyer2[tipo]','DataNascimento':'','Genero':'','DocumentoFiscal':'$buyer2[documento]','Identidade':'','RazaoSocial':'','CEP':'$shipment55[cep]','Logradouro':'$shipment55[endereco]','LogradouroNumero': '$shipment55[numero]','Bairro': '$shipment55[bairro]','LogradouroComplemento': '$shipment55[complemento]','Municipio':'$shipment55[codigoibge]','UnidadeFederativa': '$shipment55[uf]','Telefone':'$buyer2[telefone]','TelefoneDDD':'$buyer2[ddd]','Email':'','DataCadastro':'','ReceberEmail':false,'Observacoes':'','PrecoValidade': 0,'MinimoVenda':0,'Ativo':true}";

$ch3 = curl_init('189.111.250.175:5050/root/cliente');

curl_setopt_array($ch3, [
    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'PUT',
	// Equivalente ao -H:
    CURLOPT_HTTPHEADER =>  "Content-Type: application/json",
		 // Equivalente ao -H:
   CURLOPT_HTTPHEADER => "Accept: application/json",
			 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
		"X-Token: 06h8NA1lXDc5Oo94UJEFmYT3Q2kp335yBcp24KlD",
    ],
	CURLOPT_POSTFIELDS=> ($inserecliente2),
    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);
$respostacliente2 = (curl_exec($ch3));
curl_close($ch3);
//echo "$respostacliente2";

$clientesistema2=json_decode($respostacliente2,true);
$numerocliente2=$clientesistema2['Model']['Codigo'];

sleep(1);
$inserircliente3 = mysqli_query($link10,"UPDATE melishipments SET cliente='$numerocliente2' WHERE shipment='$shipment'");
 
$valortotal2orders=$order99['valortotal']+$order299['valortotal'];
$valortotalfrete2orders=$order99['frete']+$order299['frete'];

$inserevenda4="{'Codigo':0,'NaoPersistir': false,'ClienteCodigo':'$numerocliente2','ClienteTipoPessoa':'$buyer2[tipo]','DocumentoFiscal':'$buyer2[documento]','Identidade':'','TransportadoraCodigo':16,'ValorTotal':'$valortotal2orders','ValorFrete':'$valortotalfrete2orders','ValorTotalBruto':'$valortotal2orders','DataVenda':'$order99[data]','Entrega':true,'EntregaNome':'$buyer2[nome]','EntregaEmail':'$buyer2[documento]@mlint.shopdng.com.br','NumeroObjeto':'','EntregaTelefone':'$buyer2[ddd]$buyer2[telefone]','EntregaLogradouro':'$shipment55[endereco]','EntregaLogradouroNumero':'$shipment55[numero]','EntregaLogradouroComplemento':'$shipment55[complemento]','EntregaBairro':'$shipment55[bairro]','EntregaMunicipio':'$shipment55[codigoibge]','EntregaUnidadeFederativa':'$shipment55[uf]','EntregaCEP':'$shipment55[cep]','CupomDescontoCodigo':'','CupomDescontoValor':0,'VendaPagamentos':[{'Valor':'$valortotal2orders','DataVencimento':'','DataCaptura':'$order99[data]','NumeroAutenticacao':'','NumeroAutorizacao':'','FormaPagamentoCodigo':21,'VendaCodigo':0}],'Itens':[{'Codigo':0,'VendaCodigo':0,'ProdutoCodigo':'$order99[produto]','PrecoUnitarioVenda':'$order99[valor]','PrecoUnitarioCusto':0,'EmbaladoParaPresente':false,'Quantidade':'$order99[quantidade]','AtributosEspeciais':'','ItemNome':'$order99[titulo]','ItemValorBruto':'$order99[valor]','ItemValorLiquido':'$order99[valor]','ItemDescontoUnitario':0},{'Codigo':0,'VendaCodigo':0,'ProdutoCodigo':'$order299[produto]','PrecoUnitarioVenda':'$order299[valor]','PrecoUnitarioCusto':0,'EmbaladoParaPresente':false,'Quantidade':'$order299[quantidade]','AtributosEspeciais':'','ItemNome':'$order299[titulo]','ItemValorBruto':'$order299[valor]','ItemValorLiquido':'$order299[valor]','ItemDescontoUnitario':0}],'Observacoes':'','ObservacoesLoja':'','CodigoStatus':0,'DescricaoStatus':'','PrevisaoEntrega':'','Origem':'MERCADOLIVRE','OrigemPedido':'MERCADOLIVRE','CodigoPedidoExterno':'$order99[orders]-$order299[orders]-$order99[pagamento]-$order299[pagamento]','TipoVenda':'V','EnviarEmail':false,'Reservada':false}";

$ch4 = curl_init('189.111.250.175:5050/root/venda');

curl_setopt_array($ch4, [
    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'PUT',
	// Equivalente ao -H:
    CURLOPT_HTTPHEADER =>  "Content-Type: application/json, charset=UTF-8",
		 // Equivalente ao -H:
   CURLOPT_HTTPHEADER => "Accept: application/json",
			 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
		"X-Token: 06h8NA1lXDc5Oo94UJEFmYT3Q2kp335yBcp24KlD",
    ],
	CURLOPT_POSTFIELDS=> ($inserevenda4),
    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);
$respostavenda4 = (curl_exec($ch4));
curl_close($ch4);
//echo "$respostavenda4";
//echo "<br>";
//echo "<br>";
//echo "$inserevenda4";

$vendasistema4=json_decode($respostavenda4,true);
$numerovenda4=$vendasistema4['Model']['Codigo'];
$vendatruefalse2=$vendasistema4['status'];

$resourceorders2=$order299['resource'];

sleep(1);

if ($vendatruefalse4 == false){
	mysqli_query($link10,"UPDATE melinotif SET status='erroV' WHERE resource='$shipmentlido'");
	mysqli_query($link10,"UPDATE melinotif SET status='erroV' WHERE resource ='$order99[resource]'");
	mysqli_query($link10,"UPDATE melinotif SET status='erroV' WHERE resource ='$order299[resource]'");
	echo "$respostavenda4";
header("Refresh:45");
$notaR="{'note':'Pedido não lançado. Cadastre manualmente.'}";
$urlnotesR="https://api.mercadolibre.com$resourceorders/notes?access_token=$mltoken";
$chR = curl_init($urlnotesR);
curl_setopt_array($chR,[
    CURLOPT_CUSTOMREQUEST=>"POST",
	CURLOPT_POSTFIELDS=>"$notaR",
	CURLOPT_RETURNTRANSFER => 1,
	]);
$respostaR = (curl_exec($chR));
curl_close($chR);
	die("Venda não lançada.");
}else{
	mysqli_query($link10,"UPDATE melishipments SET venda='$numerovenda' WHERE shipment='$shipment'");
	mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource='$shipmentlido'");
	mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource ='$order99[resource]'");
	mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource ='$order299[resource]'");
$notaG="{'note':'Pedido Lançado no sistema. Nº$numerovenda4'}";
$urlnotesG="https://api.mercadolibre.com$resourceorders/notes?access_token=$mltoken";
$chG = curl_init($urlnotesG);
curl_setopt_array($chG,[
    CURLOPT_CUSTOMREQUEST=>"POST",
	CURLOPT_POSTFIELDS=>"$notaG",
	CURLOPT_RETURNTRANSFER => 1,
	]);
$respostaG = (curl_exec($chG));
curl_close($chG);
header("Refresh:45");
echo "Pedido '$order99[orders]' lançado no sistema. Nº$numerovenda4";
echo "Pedido '$order299[orders]' lançado no sistema. Nº$numerovenda4";
//sleep(1);
//$inserirvenda16 = mysqli_query($link10,"UPDATE melishipments SET venda='$numerovenda4' WHERE shipment='$shipment'");
//if ($numerovenda4 == null){
	//mysqli_query($link10,"UPDATE melinotif SET status='erroV' WHERE resource='$shipmentlido' LIMIT 1");
	//die("Venda não lançada");
//}else{
	//mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource='$shipmentlido' LIMIT 1");
	//mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource ='$order99[resource]' LIMIT 1");
//}

}
}
 if (mysqli_num_rows($consultaship) == 3) {

 echo "Encontrado pedido com três itens. Enviando ao sistema.";
$order299 = mysqli_fetch_array($consultaship,MYSQLI_ASSOC);
$order399 = mysqli_fetch_array($consultaship,MYSQLI_ASSOC);

$consultabuyer3 = mysqli_query($link10,"SELECT * FROM melibuyer WHERE id ='$order99[buyerid]'");
$buyer3 = mysqli_fetch_array($consultabuyer3);

$inserecliente5="{'Codigo':0,'Nome':'$buyer3[nome]','TipoPessoa':'$buyer3[tipo]','DataNascimento':'','Genero':'','DocumentoFiscal':'$buyer3[documento]','Identidade':'','RazaoSocial':'','CEP':'$shipment55[cep]','Logradouro':'$shipment55[endereco]','LogradouroNumero': '$shipment55[numero]','Bairro': '$shipment55[bairro]','LogradouroComplemento': '$shipment55[complemento]','Municipio':'$shipment55[codigoibge]','UnidadeFederativa': '$shipment55[uf]','Telefone':'$buyer3[telefone]','TelefoneDDD':'$buyer3[ddd]','Email':'','DataCadastro':'','ReceberEmail':false,'Observacoes':'','PrecoValidade': 0,'MinimoVenda':0,'Ativo':true}";

$ch5 = curl_init('189.111.250.175:5050/root/cliente');

curl_setopt_array($ch5, [
    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'PUT',
	// Equivalente ao -H:
    CURLOPT_HTTPHEADER =>  "Content-Type: application/json",
		 // Equivalente ao -H:
   CURLOPT_HTTPHEADER => "Accept: application/json",
			 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
		"X-Token: 06h8NA1lXDc5Oo94UJEFmYT3Q2kp335yBcp24KlD",
    ],
	CURLOPT_POSTFIELDS=> ($inserecliente5),
    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);
$respostacliente5 = (curl_exec($ch5));
curl_close($ch5);
//echo "$respostacliente5";

$clientesistema5=json_decode($respostacliente5,true);
$numerocliente5=$clientesistema5['Model']['Codigo'];

sleep(1);
$inserircliente5 = mysqli_query($link10,"UPDATE melishipments SET cliente='$numerocliente5' WHERE shipment='$shipment'");
 
$valortotal3orders=$order99['valortotal']+$order299['valortotal']+$order399['valortotal'];
$valortotalfrete3orders=$order99['frete']+$order299['frete']+$order399['frete'];

$inserevenda5="{'Codigo':0,'NaoPersistir': false,'ClienteCodigo':'$numerocliente5','ClienteTipoPessoa':'$buyer3[tipo]','DocumentoFiscal':'$buyer3[documento]','Identidade':'','TransportadoraCodigo':16,'ValorTotal':'$valortotal3orders','ValorFrete':'$valortotalfrete3orders','ValorTotalBruto':'$valortotal3orders','DataVenda':'$order99[data]','Entrega':true,'EntregaNome':'$buyer3[nome]','EntregaEmail':'$buyer3[documento]@mlint.shopdng.com.br','NumeroObjeto':'','EntregaTelefone':'$buyer3[ddd]$buyer3[telefone]','EntregaLogradouro':'$shipment55[endereco]','EntregaLogradouroNumero':'$shipment55[numero]','EntregaLogradouroComplemento':'$shipment55[complemento]','EntregaBairro':'$shipment55[bairro]','EntregaMunicipio':'$shipment55[codigoibge]','EntregaUnidadeFederativa':'$shipment55[uf]','EntregaCEP':'$shipment55[cep]','CupomDescontoCodigo':'','CupomDescontoValor':0,'VendaPagamentos':[{'Valor':'$valortotal3orders','DataVencimento':'','DataCaptura':'$order99[data]','NumeroAutenticacao':'','NumeroAutorizacao':'','FormaPagamentoCodigo':21,'VendaCodigo':0}],'Itens':[{'Codigo':0,'VendaCodigo':0,'ProdutoCodigo':'$order99[produto]','PrecoUnitarioVenda':'$order99[valor]','PrecoUnitarioCusto':0,'EmbaladoParaPresente':false,'Quantidade':'$order99[quantidade]','AtributosEspeciais':'','ItemNome':'$order99[titulo]','ItemValorBruto':'$order99[valor]','ItemValorLiquido':'$order99[valor]','ItemDescontoUnitario':0},{'Codigo':0,'VendaCodigo':0,'ProdutoCodigo':'$order299[produto]','PrecoUnitarioVenda':'$order299[valor]','PrecoUnitarioCusto':0,'EmbaladoParaPresente':false,'Quantidade':'$order299[quantidade]','AtributosEspeciais':'','ItemNome':'$order299[titulo]','ItemValorBruto':'$order299[valor]','ItemValorLiquido':'$order299[valor]','ItemDescontoUnitario':0},{'Codigo':0,'VendaCodigo':0,'ProdutoCodigo':'$order399[produto]','PrecoUnitarioVenda':'$order399[valor]','PrecoUnitarioCusto':0,'EmbaladoParaPresente':false,'Quantidade':'$order399[quantidade]','AtributosEspeciais':'','ItemNome':'$order399[titulo]','ItemValorBruto':'$order399[valor]','ItemValorLiquido':'$order399[valor]','ItemDescontoUnitario':0}],'Observacoes':'','ObservacoesLoja':'','CodigoStatus':0,'DescricaoStatus':'','PrevisaoEntrega':'','Origem':'MERCADOLIVRE','OrigemPedido':'MERCADOLIVRE','CodigoPedidoExterno':'$order99[orders]-$order299[orders]-$order399[orders]-$order99[pagamento]-$order299[pagamento]-$order399[pagamento]','TipoVenda':'V','EnviarEmail':false,'Reservada':false}";

$ch6 = curl_init('189.111.250.175:5050/root/venda');

curl_setopt_array($ch6, [
    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'PUT',
	// Equivalente ao -H:
    CURLOPT_HTTPHEADER =>  "Content-Type: application/json, charset=UTF-8",
		 // Equivalente ao -H:
   CURLOPT_HTTPHEADER => "Accept: application/json",
			 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
		"X-Token: 06h8NA1lXDc5Oo94UJEFmYT3Q2kp335yBcp24KlD",
    ],
	CURLOPT_POSTFIELDS=> ($inserevenda5),
    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);
$respostavenda5 = (curl_exec($ch6));
curl_close($ch6);
//echo "$respostavenda5";
//echo "<br>";
//echo "<br>";
//echo "$inserevenda5";

$vendasistema5=json_decode($respostavenda5,true);
$numerovenda5=$vendasistema5['Model']['Codigo'];

sleep(1);
$inserirvenda29 = mysqli_query($link10,"UPDATE melishipments SET venda='$numerovenda5' WHERE shipment='$shipment'");
if ($numerovenda5 == null){
	mysqli_query($link10,"UPDATE melinotif SET status='erroV' WHERE resource='$shipmentlido' LIMIT 1");
	die("Venda não lançada");
}else{
	mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource='$shipmentlido' LIMIT 1");
	mysqli_query($link10,"UPDATE melinotif SET status='OK' WHERE resource ='$order99[resource]' LIMIT 1");
 }
 
// POSTERIORMENTE INSERIR UM COD PRA ATUALIZAR RESOURCE PARA LIDA.

 }
if (mysqli_num_rows($consultaship) == 0) {
	 echo "Nenhuma venda para lançar.";
}
}
//contador de resultados
//$rowcount=mysqli_num_rows($consultaship);
//printf("Result set has %d rows.\n",$rowcount);
//var_dump($resourceorders);
//var_dump($numerovenda);
//var_dump($nota);

?>