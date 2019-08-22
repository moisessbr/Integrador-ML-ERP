<?php
// Iniciamos a função do CURL:
$ch = curl_init('189.111.250.175:5050/root/cliente/6331');

curl_setopt_array($ch, [

    // Equivalente ao -X:
    CURLOPT_CUSTOMREQUEST => 'GET',
	
	 // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
        "Accept: application/json",
    ],
    // Equivalente ao -H:
    CURLOPT_HTTPHEADER => [
        "X-Token: 06h8NA1lXDc5Oo94UJEFmYT3Q2kp335yBcp24KlD",
    ],

    // Permite obter o resultado
    CURLOPT_RETURNTRANSFER => 1,
]);

$resposta = (curl_exec($ch));
curl_close($ch);

echo $resposta
?>