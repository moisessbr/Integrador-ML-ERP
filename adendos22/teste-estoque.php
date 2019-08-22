<?php
//Token do MercadoLivre
session_start();
$mltoken="$_SESSION[access_token]";

$urlapi5="https://api.mercadolibre.com/items/MLB689024685?access_token=$mltoken";


$curl5 = curl_init();

curl_setopt_array($curl5, array(
  CURLOPT_URL => $urlapi5,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array("x-format-new: true"),
));

$response5 = curl_exec($curl5);
$err5 = curl_error($curl5);

curl_close($curl5);


$mlresponde5 = json_decode($response5,true);

$estoque=$mlresponde5['available_quantity'];



$urlapi6="https://api.mercadolibre.com/items/MLB689024685/variations/17619048150?access_token=$mltoken";


$curl6 = curl_init();

curl_setopt_array($curl6, array(
  CURLOPT_URL => $urlapi6,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array("x-format-new: true"),
));

$response6 = curl_exec($curl6);
$err6 = curl_error($curl6);

curl_close($curl6);


$mlresponde6 = json_decode($response6,true);

$estoque6=$mlresponde6['available_quantity'];




// Iniciamos a função do CURL:

//$dados='{"Codigo":0,"Nome":"Manuel","TipoPessoa":"F","DataNascimento":"","Genero":"","DocumentoFiscal":"41231422491","Identidade":"","RazaoSocial":"","CEP":"03128141","Logradouro":"Jose","LogradouroNumero": "123","Bairro": "Prudente","LogradouroComplemento": "Casa","Municipio":"","UnidadeFederativa": "SP","Telefone":"12345678","TelefoneDDD":"11","Celular":"912345678","CelularDDD":"11","Email":"manuel@testuser.com.br","DataCadastro":"","ReceberEmail":false,"Observacoes":"","PrecoValidade": 0,"MinimoVenda":0,"Ativo":true}';


$ch = curl_init('USERHOSTURL:8867/root/estoque/6911');

curl_setopt_array($ch, [

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

$resposta = (curl_exec($ch));
curl_close($ch);

echo "$resposta";
echo "<br>$estoque";
echo "<br>$estoque6";

//echo "$dados"