<?php
session_start();
$mltoken="$_SESSION[access_token]";

$link31 = mysqli_connect(""host","user","pass","bd");
 			
$limpaorders = mysqli_query($link31,"DELETE FROM meliorders WHERE resource='/orders/'");

$limpashipments = mysqli_query($link31,"DELETE FROM melishipments WHERE resource='/shipments/'");

$limpanotif = mysqli_query($link31,"DELETE FROM melinotif WHERE resource='/shipments/' OR resource='/orders/'");


if (mysqli_affected_rows($link31) > 0) {

    $atualizados = mysqli_affected_rows($link31);
    echo "Foram excluídos $atualizados erro(s)";
    mysqli_close($link31);
    die(".");					
    //Se não tem itens para atualizar ele fica em standby.
	}else{
		echo "Não há erros a serem limpos. Verifique o status da conexão com o MercadoLivre ou contate o desenvolvedor.";
		mysqli_close($link31);
		die(".");	
	}


?>
 
