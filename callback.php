<?php

$retornoA = file_get_contents('php://input');
$retorno = json_decode($retornoA,true);

//Mysql

$link = mysqli_connect(""host","user","pass","bd");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

mysqli_query($link,"INSERT INTO melinotif (resource,user_id,topic,attempts,sent,received,status) VALUES ('".$retorno['resource']."','".$retorno['user_id']."','".$retorno['topic']."','".$retorno['attempts']."','".$retorno['sent']."','".$retorno['received']."','novo')");
	{
echo("Error description: " . mysqli_error($link));
	}

mysqli_close($link);

//mail('moises@shopdng.com.br', 'Teste JSON 2', $retorno);

print_r($retornoA);


//$retorno = json_decode(file_get_contents('php://input'),true);
//if (json_last_error() === JSON_ERROR_NONE) { 
    //do something with $json. It's ready to use 
//} else { 
    //yep, it's not JSON. Log error or alert someone or do nothing 
//} 

// Mysql

//$link = mysqli_connect(""host","user","pass","bd");
//
//if (mysqli_connect_errno())
//  {
//  echo "Failed to connect to MySQL: " . mysqli_connect_error();
//  }
//
//mysqli_query($link,"INSERT INTO MELIDATA (user_id,resource,received,sent,attempts,status) VALUES ('".$retorno['user_id']."','".$retorno['resource']."','".$retorno['received']."','".$retorno['sent']."','".$retorno['attempts']."','novo')");
//  {
 // echo("Error description: " . mysqli_error($link));
 // }
//
//
//mysqli_close($link);
//
// mail('moises@shopdng.com.br', 'Teste JSON', $retorno);

?>