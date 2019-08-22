<?php
// Iniciamos a função do CURL:



$dados='{"Codigo":0,"Nome":"Manuel","TipoPessoa":"F","DataNascimento":"","Genero":"","DocumentoFiscal":"41231422491","Identidade":"","RazaoSocial":"","CEP":"03128141","Logradouro":"Jose","LogradouroNumero": "123","Bairro": "Prudente","LogradouroComplemento": "Casa","Municipio":"3550308","UnidadeFederativa": "SP","Telefone":"12345678","TelefoneDDD":"11","Celular":"912345678","CelularDDD":"11","Email":"manuel@testuser.com.br","DataCadastro":"","ReceberEmail":false,"Observacoes":"","PrecoValidade": 0,"MinimoVenda":0,"Ativo":true}';


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

	
	CURLOPT_POSTFIELDS=> ($dados),
	
    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);

$resposta = (curl_exec($ch));
curl_close($ch);

echo "$resposta";

echo "$dados"
?>