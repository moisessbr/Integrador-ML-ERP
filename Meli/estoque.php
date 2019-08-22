<?

$link4 = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

$buscaid = mysqli_query($link4,"SELECT id FROM pmaster WHERE busca='novo' LIMIT 1");

if (mysqli_num_rows($buscaid) > 0) {

$rowbusca = mysqli_fetch_array($buscaid);
$dados2 = $rowbusca[0];
{
echo $rowbusca[0];
echo '<br />';
}

//$presource2 = mysqli_query($link4,"SELECT * FROM melishipments WHERE resource='$dados2'");

//if (mysqli_num_rows($presource2) > 0) {
 //mysqli_query($link4,"UPDATE melinotif SET status='DPL' WHERE resource='$dados2' and status='novo'");
 //echo "Resource já cadastrado.";


//Consulta o item referente a pesquisa MYSQL

$ch7 = curl_init("USERHOSTURL:8867/root/estoque/$sku");

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
curl_close($ch7);

echo "$resposta7";

}
mysqli_close($link4);

}else{
	echo "Nenhum estoque para atualizar.<br> Verificando novamente em 45 segundos.";
	header("Refresh:45");
}

?>