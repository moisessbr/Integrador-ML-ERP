<html>
<title>Estoque</title>
</html>
<?

$link4 = mysqli_connect(""host","user","pass","bd");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$buscaid = mysqli_query($link4,"SELECT * FROM pmaster WHERE busca='A' LIMIT 1");

if (mysqli_num_rows($buscaid) > 0) {
	
	$dadosbusca = mysqli_fetch_array($buscaid,MYSQLI_ASSOC);

	$idbusca=$dadosbusca['id'];
	$codigobusca=$dadosbusca['codigo'];
	$estoquebusca=$dadosbusca['estoque'];

$rowbusca = mysqli_fetch_array($buscaid);
//$dados2 = $rowbusca[0];
{
//echo $rowbusca[0];
echo '<br />';
echo "Id do Registro: $idbusca";
echo '<br />';
echo "Código do item: $codigobusca";
echo '<br />';
echo "Estoque do banco: $estoquebusca";
}

//$presource2 = mysqli_query($link4,"SELECT * FROM melishipments WHERE resource='$dados2'");

//if (mysqli_num_rows($presource2) > 0) {
 //mysqli_query($link4,"UPDATE melinotif SET status='DPL' WHERE resource='$dados2' and status='novo'");
 //echo "Resource já cadastrado.";


//Consulta o item referente a pesquisa MYSQL

$ch7 = curl_init("USERHOSTURL:8867/root/estoque/$codigobusca");

curl_setopt_array($ch7, [


    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'GET',
	
	// Equivalente ao -H:
    CURLOPT_HTTPHEADER =>  "Content-Type: application/json",
	
		 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => "Accept: application/json",
 
			 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
		"X-Token: 06h8NA1lXDc5Oo94UJEFmYT3Q2kp335yBcp24KlD",
    ],

	
	//CURLOPT_POSTFIELDS=> ($dados),
	
    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);

$resposta7 = (curl_exec($ch7));
//curl_close($ch7);

$err7 = curl_error($ch7);

curl_close($ch7);
	
if ($err7) {
  echo "cURL Error #:" . $err3;
  header("Refresh:45");
  die ("Erro no cURL. Atualizando em breve.");
} else {

$dadossistema=json_decode($resposta7,true);

$codigosistema=$dadossistema['Model']['Estoque']['Codigo'];
$estoquesistemac=$dadossistema['Model']['Estoque']['EstoqueAtual'];
$estoquesistema=round($estoquesistemac);
//$estoquesistema=5; << teste

if ($estoquesistema <> $estoquebusca)
	{
	echo 'Diferente';
	mysqli_query($link4,"UPDATE pmaster SET estoque='$estoquesistema', status='ATT', busca='B' WHERE id='$idbusca'");
	header("Refresh:3");
	}
else{
	echo '<p></p>Condição: Igual';
	mysqli_query($link4,"UPDATE pmaster SET busca='B' WHERE id='$idbusca'");
	header("Refresh:3");
	echo '<br />';
	}

echo "Estoque do sistema: $estoquesistema";
//echo '<br />';
//echo "$codigosistema";
//echo '<br />';
//echo "$resposta7";
//echo '<br />';
//var_dump(json_decode($resposta7));

//$dadossistema = json_decode($resposta7,true);

//$estoquesistema=$dadossistema['EstoqueAtual'];
//$codigosistema=$dadossistema['Codigo'];

mysqli_close($link4);
}
}else{
	echo "Todos os dados atualizados.<br> Iniciando nova varredura em 45 segundos.";
	mysqli_query($link4,"UPDATE pmaster SET busca='A' ");
	header("Refresh:45");
}

?>