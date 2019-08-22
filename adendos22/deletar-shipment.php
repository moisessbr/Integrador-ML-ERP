<?php

//Deletar resources de shipments da melidata

$link = mysqli_connect("mysql857.umbler.com","2ahanupr","?2{*}26ObouSh","fr8phust");

if (mysqli_connect_errno())
	{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

//Deleta todas as linhas que contenham /shipments/ na coluna resource.
if (mysqli_query($link,"DELETE FROM MELIDATA WHERE resource like '%/shipments/%'")) {


    $rowsAffected = mysqli_affected_rows($link);

    if ($rowsAffected == 0) {
        echo "Nenhum registro foi deletado.";
    } elseif ($rowsAffected == 1) {
        echo "1 registro foi deletado.";
    } elseif ($rowsAffected > 0) {
        echo "$rowsAffected registros foram deletados.";
    }

} else {

    echo "Error occurred: " . mysqli_error($link);

} 


mysqli_close($link);


?>